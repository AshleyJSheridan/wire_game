<?php

class WebSocket_WebSocket implements WebSocket_Observer {

    protected $socket;
    protected $handshakeChallenge;
    protected $hixieKey1;
    protected $hixieKey2;
    protected $host;
    protected $port;
    protected $origin;
    protected $requestUri;
    protected $url;
    protected $hybi;
    protected $_frames = array();
    protected $_messages = array();
    protected $_head = '';
    protected $_timeOut = 1;

    // mamta
    public function __construct($url, $useHybie = true) {
        $this->hybi = $useHybie;
        $parts = parse_url($url);

        $this->url = $url;

        if (in_array($parts['scheme'], array('ws', 'wss')) === false)
            throw new WebSocketInvalidUrlScheme();

        $this->scheme = $parts['scheme'];

        $this->host = $parts['host'];
        $this->port = $parts['port'];

        $this->origin = 'http://' . $this->host;

        if (isset($parts['path']))
            $this->requestUri = $parts['path'];
        else
            $this->requestUri = "/";

        if (isset($parts['query']))
            $this->requestUri .= "?" . $parts['query'];

        // mamta
        if ($useHybie) {
            $this->buildHeaderArray();
        } else {
            $this->buildHeaderArrayHixie76();
        }
    }

    public function onDisconnect(WebSocket_Socket $s) {

    }

    public function onConnectionEstablished(WebSocket_Socket $s) {

    }

    public function onMessage(IWebSocketConnection $s, IWebSocketMessage $msg) {
        $this->_messages[] = $msg;
    }

    public function onFlashXMLRequest(WebSocket_ConnectionFlash $connection) {

    }

    public function setTimeOut($seconds) {
        $this->_timeOut = $seconds;
    }

    public function getTimeOut() {
        return $this->_timeOut;
    }

    /**
     * TODO: Proper header generation!
     * TODO: Check server response!
     */
    public function open() {
        $errno = $errstr = null;

        $protocol = $this->scheme == 'ws' ? "tcp" : "ssl";

        $this->socket = stream_socket_client("$protocol://{$this->host}:{$this->port}", $errno, $errstr, $this->getTimeOut());
        // socket_connect($this->socket, $this->host, $this->port);

        $buffer = $this->serializeHeaders();

        fwrite($this->socket, $buffer, strlen($buffer));

        // wait for response
        $buffer = WebSocket_Functions::readWholeBuffer($this->socket);
        $headers = WebSocket_Functions::parseHeaders($buffer);

        $s = new WebSocket_Socket($this, $this->socket, $immediateWrite = true);

        if ($this->hybi)
            $this->_connection = new WebSocket_ConnectionHybi($s, $headers);
        else
            $this->_connection = new WebSocket_ConnectionHixie($s, $headers, $buffer);

        $s->setConnection($this->_connection);

        return true;
    }

    private function serializeHeaders() {
        $str = '';

        foreach ($this->headers as $k => $v) {
            $str .= $k . " " . $v . "\r\n";
        }
        # mamta add key 3 needed for the handshake/swithching protocol compatible with glassfish
        $key3 = WebSocket_Functions::genKey3();
        $str .= "\r\n" . $key3;

        return $str;
    }

    public function addHeader($key, $value) {
        $this->headers[$key . ":"] = $value;
    }

    protected function buildHeaderArray() {
        $this->handshakeChallenge = WebSocket_Functions::randHybiKey();

        $this->headers = array("GET" => "{$this->url} HTTP/1.1", "Connection:" => "Upgrade", "Host:" => "{$this->host}:{$this->port}", "Sec-WebSocket-Key:" => "{$this->handshakeChallenge}", "Sec-WebSocket-Origin:" => "{$this->origin}", "Sec-WebSocket-Version:" => 8, "Upgrade:" => "websocket");

        return $this->headers;
    }

    # mamta: hixie 76

    protected function buildHeaderArrayHixie76() {
        $this->hixieKey1 = WebSocket_Functions::randHixieKey();
        $this->hixieKey2 = WebSocket_Functions::randHixieKey();
        $this->headers = array("GET" => "{$this->url} HTTP/1.1", "Connection:" => "Upgrade", "Host:" => "{$this->host}:{$this->port}", "Origin:" => "{$this->origin}", "Sec-WebSocket-Key1:" => "{$this->hixieKey1->key}", "Sec-WebSocket-Key2:" => "{$this->hixieKey2->key}", "Upgrade:" => "websocket", "Sec-WebSocket-Protocol: " => "hiwavenet");

        return $this->headers;
    }

    public function send($string) {
        $this->_connection->sendString($string);
    }

    public function sendMessage($msg) {
        $this->_connection->sendMessage($msg);
    }

    public function sendFrame(IWebSocketFrame $frame) {
        $this->_connection->sendFrame($frame);
    }

    /**
     * @return WebSocket_Frame
     */
    public function readFrame() {
        $buffer = WebSocket_Functions::readWholeBuffer($this->socket);

        $this->_frames = array_merge($this->_frames, $this->_connection->readFrame($buffer));

        return array_shift($this->_frames);
    }

    public function readMessage() {
        while (count($this->_messages) == 0)
            $this->readFrame();



        return array_shift($this->_messages);
    }

    public function close() {
        /**
         * @var WebSocket_Frame
         */
        $frame = null;
        $this->sendFrame(WebSocket_Frame::create(WebSocketOpcode::CloseFrame));

        $i = 0;
        do {
            $i++;
            $frame = $this->readFrame();
        } while ($i < 2 && $frame && $frame->getType() == WebSocketOpcode::CloseFrame);

        @fclose($this->socket);
    }

}