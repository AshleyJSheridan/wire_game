<?php

/**
 * NotIdentical Validator
 * 
 * @package MM FB APP
 * @version 2.0.0
 */

/** @see Zend_Validate_Abstract */
require_once 'Zend/Validate/Abstract.php';

class MM_NotIdentical extends Zend_Validate_Abstract
{
    /**
     * Error codes
     * @const string
     */
    const SAME      = 'Same';

    /**
     * Error messages
     * @var array
     */
    protected $_messageTemplates = array(
        self::SAME      => "The two given tokens are the same",
    );

    /**
     * @var array
     */
    protected $_messageVariables = array(
        'token' => '_tokenString'
    );

    /**
     * Original token against which to validate
     * @var string
     */
    protected $_tokenString;
    protected $_token;

    /**
     * Sets validator options
     *
     * @param  mixed $token
     * @return void
     */
    public function __construct($token = null)
    {
        if ($token instanceof Zend_Config) {
            $token = $token->toArray();
        }

        if (is_array($token) && array_key_exists('token', $token)) {
            $this->setToken($token['token']);
        } else if (null !== $token) {
            $this->setToken($token);
        }
    }

    /**
     * Retrieve token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * Set token against which to compare
     *
     * @param  mixed $token
     * @return Zend_Validate_Identical
     */
    public function setToken($token)
    {
        $this->_tokenString = (string) $token;
        $this->_token       = $token;
        return $this;
    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if and only if a token has been set and the provided value
     * matches that token.
     *
     * @param  mixed $value
     * @param  array $context
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        $this->_setValue((string) $value);

        if (($context !== null) && isset($context) && array_key_exists($this->getToken(), $context)) {
            $token = $context[$this->getToken()];
        } else {
            $token = $this->getToken();
        }

        if ($token === null) {
            $this->_error(self::MISSING_TOKEN);
            return false;
        }
        
        if (($value === $token) || ($value == $token)) {
            $this->_error(self::SAME);
            return false;
        }
        return true;
    }
}
