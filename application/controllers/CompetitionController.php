<?php
/**
 * Competition Controller
 *
 * @package TMW WIRE GAME
 * @version 1.0.0
 */
class CompetitionController extends Zend_Controller_Action {
    
    protected $_facebookAppUrl;
    protected $_tmwCampaign;
    protected $_campaignName;
    protected $_facebookData;
    protected $_competitionPage;
    protected $_appSettings;
    protected $_appContents;
    protected $_appFormElements;
        
    public function init() {
        $this->_tmwDBConnect            = new TMW_Competition();
        $this->_tmwDBGameStatus         = new TMW_CurrentGameState();
        $this->_gameStatus              = $this->_tmwDBGameStatus->getCurrentGameState();
        
        $this->_appSettings             = $this->_tmwDBConnect->getAppSettings();
        $this->_appContents             = $this->_tmwDBConnect->getAppContents($this->_appSettings['campaignName']);
        $this->_appFormElements         = $this->_tmwDBConnect->getAppFormElements($this->_appSettings['campaignName']);
        
        // define app page we are currently on
        $this->_tmwCampaign             = $this->getRequest()->getParam('campaign');
        $this->_facebookAppUrl          = $this->_appSettings['facebookAppUrl'];
        
        $this->_campaignName            = $this->_appSettings['campaignName'];
        $this->_competitionPage         = $this->getRequest()->getParam('action');
        $this->_ajaxParam               = $this->getRequest()->getParam('ajax');

        // set page titles
        $this->view->pageTitle          = $this->_appSettings['title'];

        // set page Google Analytics
        $this->view->pageGA             = $this->_appSettings['gaId'];

        // set page css
        $this->view->pageStyles         = $this->_tmwCampaign.'.css';

        // set share button / JS options
        $this->view->facebookShare                  = array();
        $this->view->facebookShare['title']         = $this->_appSettings['title'];
        $this->view->facebookShare['url']           = $this->_facebookAppUrl . '/app_' . $this->_appSettings['appId'];
        $this->view->facebookShare['summary']       = $this->_appSettings['summary'];
        $this->view->facebookShare['image']         = '/assets/img/competition/fbShareImage.jpg';
        $this->view->facebookShare['appID']         = $this->_appSettings['appId'];
        $this->view->facebookShare['campaign']      = $this->_tmwCampaign;
        $this->view->facebookShare['campaignName']  = $this->_campaignName;
        
        //set page texts
        $this->view->contents                           = array();
        $this->view->contents['headerText1']            = $this->_appContents['headerText1'];
        $this->view->contents['headerText2']            = $this->_appContents['headerText2'];
        $this->view->contents['introText']              = $this->_appContents['introText'];
        $this->view->contents['introVideo']             = $this->_appContents['introVideo'];
        $this->view->contents['thankText']              = $this->_appContents['thankText'];
        $this->view->contents['tncText']                = $this->_appContents['tncText'];
        $this->view->contents['policyText']             = $this->_appContents['policyText'];
        $this->view->contents['displayImage']           = $this->_appContents['displayImage'];
        
        //set page form elements texts
        $this->view->formElements                       = $this->_appFormElements;
        $this->registerRFHandleId                       = null;

        // set layout
        $this->_helper->layout->setLayout('competition');        

        // reference the right views folder location
        $this->view->addScriptPath(APPLICATION_PATH . '/views/scripts/campaign/' . $this->_tmwCampaign);
        
        // If it has FB app id load the facebook sdk        
        if(!is_null($this->_appSettings['appId'])){// load facebook SDK
            require_once APPLICATION_PATH . '/../library/facebook/facebook.php';

            $facebook = new Facebook(array(
                    'appId'     => $this->_appSettings['appId'],
                    'secret'    => $this->_appSettings['secret'],
                    'cookie'    => true
                ));

            // request from facebook
            $this->_facebookData = $signed_request = $facebook->getSignedRequest();

            // define sessions for is liked, etc
            $pageStatus = new Zend_Session_Namespace('pageStatus');

            if(isset($signed_request['page'])) {
                    // define liked
                    $pageStatus->liked = $signed_request['page']['liked'];
            }

            // if the user has not liked the page, we redirect to the like gate. uncomment after development is done so the likegate works properly
            /*if($this->_competitionPage != 'like' && $this->_competitionPage != 'submit' && !$pageStatus->liked && !$this->_ajaxParam) {
                    $this->_redirect('/competition/' . $this->_tmwCampaign . '/like/');
            }*/
            
        }
        
        //$this->fixPlayerDbFields();
    }

    /**
     * Root / Index page Action
     */
    public function indexAction() {
        
        $ajaxFeed = $this->getRequest()->getParam('ajaxFeed');        
        $playerId = $this->_gameStatus['playerId'];
        
        if(isset($playerId) && !empty($playerId)){
            $playerDetails = $this->_tmwDBConnect->getPlayerDetails($playerId, $this->_tmwCampaign);
        }
        else{
            $playerDetails['firstname']         = 'TMW';
            $playerDetails['lastname']          = 'Agency';
            $playerDetails['twitterhandle']     = 'tmwagency';
            $playerDetails['playerScore']       = '0';
            $playerDetails['playerProgress']    = '0';
            $playerDetails['playerTime']        = '0';
        }
        
        if(isset($playerDetails['twitterhandle']) && !empty($playerDetails['twitterhandle'])){
            $playerTwitterImg = $this->gettwitterdetailsAction($playerDetails['twitterhandle']);            
        }
        else
        {
            $playerTwitterImg = '/assets/img/admin/logo.jpg';              
        }
        
        $playerDetails['playerTwitterImg'] = $playerTwitterImg;
        
        $this->view->playerDetails = $playerDetails;
        
        if(!$ajaxFeed){
            $this->view->twitterFeed = Zend_Json::decode($this->gettwitterfeedAction());            
        }     
        else{
            $this->view->twitterFeed = array();
        }
        
        if(!$ajaxFeed){ 
            $scoreBoard             = $this->_tmwDBConnect->getScoreList($this->_tmwCampaign);
            $this->view->scoreList  = $scoreBoard;
            
            $galleryList            = $this->_tmwDBConnect->getGalleryList($this->_tmwCampaign);
            $this->view->galleryList  = $galleryList;
        }     
        else{
            $this->view->scoreList = array();
        }
    }

    /**
     * Register page Action
     */
    public function registerAction() {
        
        // create form
        $form               = new TMW_Competitionform($this->_appFormElements);        
        $formHasImageField  = false;        
        $form->setAttrib('enctype', 'multipart/form-data');
        
        foreach($this->_appFormElements as $formElement)
        {
            if($formElement['elementType'] == 'File' && $formElement['elementVisibility'] == true )
            {
                $formHasImageField = true;
            }
        }
        
        if ($this->getRequest()->getMethod() == 'GET') {
            $this->view->registerRFHandleId = $this->_request->getQuery('RFHandleId');
        }
        else{
            $this->view->registerRFHandleId = null;            
        }

        // posting
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            
            if(isset($formData['playerEmail'])) {
                
                if ($form->isValid($formData)) {
                    if ($formHasImageField)
                    {
                        // The file upload field handling start
                        $upload = new Zend_File_Transfer();
                        $files = array_keys($upload->getFileInfo());
                        $upload->addFilter('Rename', realpath(APPLICATION_PATH . '/../public_html/uploads/'.$this->_tmwCampaign.'/').'/'.$formData['playerEmail'].'-'.time().'.jpg', $files[0]);
                        $upload->receive($files[0]);

                        $formData['playerImage'] = $formData['playerEmail'].'-'.time().'.jpg';
                        unset(
                            $formData['MAX_FILE_SIZE']
                        );
                        // File upload field handling ends
                    }
                    
                    // split mandatory data into separate array
                    if(empty($formData['RFHandleId'])){
                        $formData['RFHandleId'] = null;
                    }
                    $mandatoryData = array(
                        'playerEmail'   => $formData['playerEmail'],
                        'RFHandleId'    => $formData['RFHandleId'],
                        'campaign'      => $formData['campaignName']
                    );

                    // remove submit from form info, as well as mandatory data
                    unset(
                        $formData['playerEmail'],
                        $formData['submitBtn'],
                        $formData['campaign'],
                        $formData['fbTermsConditions'],
                        $formData['campaignName'],
                        $formData['RFHandleId']
                    );
                    
                    // Add the score and the progress percentance for the user to be used in the game
                    $formData['playerScore']    = 0;
                    $formData['playerProgress'] = 0;
                    $formData['playerTime']     = 0;
                    
                    // add into DB
                    $insertID = $this->_tmwDBConnect->addMandatoryData($mandatoryData);
                    $insertEX = $this->_tmwDBConnect->addDetailData($formData,$insertID);
                    if($insertID && $insertEX) {
                        if( $this->_ajaxParam ) {
                            $jsonData = utf8_encode(Zend_Json::encode('submit'));
                            $this->getResponse()
                                ->setHeader('Content-Type', 'text/html')
                                ->setBody($jsonData)
                                ->sendResponse();
                            exit;
                        } else {
                            $this->_redirect('/submit');
                        }
                    }                                
            } else {
                    // set up for ajax validation
                    if( $this->_ajaxParam ) {
                        $errorReturned = $form->getMessages();
                        foreach($errorReturned as $field => $errors) {
                            $element = $form->getElement($field);
                            $message = $element->getAttribs();
                            $errorMessages[$field] = $message['data-message'];
                        }
                        if (empty($errorReturned))
                        {
                            if($formData['playerImage'] == '' && $formHasImageField)
                            {
                                $emptyImageError = $form->getElement('playerImage')->getAttribs();
                                $errorMessages['playerImage'] = $emptyImageError['data-message'];
                            }
                        }
                        $jsonData = utf8_encode(Zend_Json::encode($errorMessages));
                        $this->getResponse()
                            ->setHeader('Content-Type', 'text/html')
                            ->setBody($jsonData)
                            ->sendResponse();
                        exit;
                    } else {
                        $form->populate($formData);
                    }
                }
            }
        }
        $this->view->form = $form;
    }
    
    /**
     * RSVP page Action
     */
    public function rsvpAction() {
        $this->registerAction();
    }

    /**
     * Game Feed page
     */
    public function gamefeedAction() {
        $this->indexAction();
    }

    /**
     * Submit page
     */
    public function submitAction() {
	// view only
    }

    /**
     * Like Gate page
     */
    public function likeAction() {
    	// view only
    }

    /**
     * The photogallery for the Online Game Feed page
     */
    private function photogalleryAction() {
    	
    }

    /**
     * Load the twitter user details
     */
    private function gettwitterdetailsAction($twitterUsername) {
        require_once APPLICATION_PATH . '/../library/TwitterAPIExchange.php';
        
        // Set up your settings with the keys you get from the dev site
        $settings = array(
            'oauth_access_token'        => $this->_appSettings['oauth_access_token'],
            'oauth_access_token_secret' => $this->_appSettings['oauth_access_token_secret'],
            'consumer_key'              => $this->_appSettings['consumer_key'],
            'consumer_secret'           => $this->_appSettings['consumer_secret']
        );
        
        $url                = 'https://api.twitter.com/1.1/users/show.json';        
        $queryfields        = '?screen_name=' . $twitterUsername . '&skip_status=1';
        $requestMethod      = 'GET';
        
        $twitter            = new TwitterAPIExchange($settings);
        $twitterUserDetails = $twitter->setGetfield($queryfields)->buildOauth($url, $requestMethod)->performRequest();
        $twitterUserDetails = Zend_Json::decode($twitterUserDetails);
        
        if(empty($twitterUserDetails) || !isset($twitterUserDetails)){
            $largeImageUrl = '/assets/img/competition/logo.jpg';
        }
        else{
            $largeImageUrl      = str_replace("normal", "bigger", $twitterUserDetails['profile_image_url']);            
        }
        
        return $largeImageUrl;
    }

    /**
     * Load the twitter user details
     */
    public function gettwitterfeedAction() {
        require_once APPLICATION_PATH . '/../library/TwitterAPIExchange.php';
        
        $ajaxFeed = $this->getRequest()->getParam('ajaxFeed'); 
        
        // Set up your settings with the keys you get from the dev site
        $settings = array(
            'oauth_access_token'        => $this->_appSettings['oauth_access_token'],
            'oauth_access_token_secret' => $this->_appSettings['oauth_access_token_secret'],
            'consumer_key'              => $this->_appSettings['consumer_key'],
            'consumer_secret'           => $this->_appSettings['consumer_secret']
        );
        
        $url                = 'https://api.twitter.com/1.1/statuses/user_timeline.json';        
        $queryfields        = '?screen_name=' . $this->_appSettings['twitter_user'] . '&count=3';
        $requestMethod      = 'GET';
        
        $twitter            = new TwitterAPIExchange($settings);
        $twitterFeed        = $twitter->setGetfield($queryfields)->buildOauth($url, $requestMethod)->performRequest();
        
        if($ajaxFeed){
            $this->getResponse()
                    ->setHeader('Content-Type', 'text/html')
                    ->setBody($twitterFeed)
                    ->sendResponse();
            exit;
        }
        else{
            return $twitterFeed;
        }
    }

    /**
     * Get the player details json
     */
    public function getplayerAction() {
        
        $playerId           = $this->getRequest()->getParam('id');        
        $playerDetails      = $this->_tmwDBConnect->getPlayerDetails($playerId, $this->_tmwCampaign);        
        $playerTwitterImg   = $this->gettwitterdetailsAction($playerDetails['twitterhandle']);
        
        $playerDetails['playerTwitterImg']  = $playerTwitterImg;        
        $this->view->playerDetails          = $playerDetails;
        
        $jsonData = utf8_encode(Zend_Json::encode($playerDetails));
        $this->getResponse()
                ->setHeader('Content-Type', 'text/html')
                ->setBody($jsonData)
                ->sendResponse();
        exit;
    }

    /**
     * Get the scoreboard details json
     */
    public function getscoresAction() {
        $scores  = $this->_tmwDBConnect->getScoreList($this->_tmwCampaign);
        
        $jsonData = utf8_encode(Zend_Json::encode($scores));
        $this->getResponse()
                ->setHeader('Content-Type', 'text/html')
                ->setBody($jsonData)
                ->sendResponse();
        exit;
    }

    /**
     * Post twitter message
     */
    private function posttotwitterAction($twitterUser, $twitterImg, $winner = false) {
        require_once APPLICATION_PATH . '/../library/TwitterAPIExchange.php';
        
        // Set up your settings with the keys you get from the dev site
        $settings = array(
            'oauth_access_token'        => $this->_appSettings['oauth_access_token'],
            'oauth_access_token_secret' => $this->_appSettings['oauth_access_token_secret'],
            'consumer_key'              => $this->_appSettings['consumer_key'],
            'consumer_secret'           => $this->_appSettings['consumer_secret']
        );
        
        if($winner){
			$twitterMsg = '@' . $twitterUser . ' Has just beaten the #TMWIRED Game. http://party.tmw.co.uk - ';  
        }
        else{
			$twitterMsg = '@' . $twitterUser . ' Was beaten by the #TMWIRED Game. http://party.tmw.co.uk - ';     
        }
        
        //$twitterMsg = '@' . $twitterUser . ' ' .$this->_appContents['twitter_post'] . ' #tmwired. See more on http://party.tmw.co.uk';
        // To be removed this is only for testing 
        //$twitterImg = APPLICATION_PATH . '/../public_html/assets/img/competition/logo.jpg';
        $twitterImg = APPLICATION_PATH . '/../public_html' . $twitterImg;
                
        $url                = 'https://api.twitter.com/1.1/statuses/update_with_media.json';
        $requestMethod      = 'POST';
        $queryfields = array(
            'media[]'   => "@{$twitterImg}",
            'status'    => $twitterMsg
        );
        
        $twitter            = new TwitterAPIExchange($settings);
        $twitterPostStatus  = $twitter->buildOauth($url, $requestMethod)->setPostfields($queryfields)->performRequest();
        
        /*$this->getResponse()
                ->setHeader('Content-Type', 'text/html')
                ->setBody($twitterPostStatus)
                ->sendResponse();
        exit;*/
        return;
    }

    /**
     * Start a new Game AJAX callback
     */
    public function gamestartAction() {
        
        $playerRFHandleId = $this->getRequest()->getParam('playerRFHandleId');
        //$playerRFHandleId = 'bardis';        //REMOVE ONLY FOR TESTING
        
        $gameStatus     = 'on';
        $gameProgress   = 0;
        $playerPhoto    = null;
        
        // Getting player id from the RF handle
        $usedPlayerId       = $this->_tmwDBConnect->getPlayerIdfromRF($playerRFHandleId, $this->_tmwCampaign);
        
        // Save the current user in the current game state
        $this->_tmwDBGameStatus->setCurrentPlayer($usedPlayerId['playerId'], $gameStatus, $gameProgress, null);        
        
        if(isset($usedPlayerId['playerId']) && !empty($usedPlayerId['playerId'])){
            $playerDetails = $this->_tmwDBConnect->getPlayerDetails($usedPlayerId['playerId'], $this->_tmwCampaign);
        }
        else{
            $playerDetails['firstname']         = 'TMW';
            $playerDetails['lastname']          = 'Agency';
            $playerDetails['twitterhandle']     = 'tmwagency';
            $playerDetails['playerScore']       = '0';
            $playerDetails['playerProgress']    = '0';
            $playerDetails['playerTime']        = '0';
        }
        
        if(isset($playerDetails['twitterhandle']) && !empty($playerDetails['twitterhandle'])){
            $playerTwitterImg   = $this->gettwitterdetailsAction($playerDetails['twitterhandle']);
            $playerDisplayName  = $playerDetails['twitterhandle'];
        }
        else
        {
            $playerTwitterImg = '/assets/img/admin/logo.jpg'; 
            $playerDisplayName  = $playerDetails['firstname'] . ' ' . $playerDetails['lastname'][0];             
        }
        
        $playerDetails['playerTwitterImg']  = $playerTwitterImg;
        $playerDetails['playerDisplayName'] = $playerDisplayName;
        $playerDetails['playerScore']       = '0';
        
        $gameStartData['playerDetails'] = $playerDetails;        
        // Getting the latest scoreboard
        $gameStartData['scoreBoard']    = $this->_tmwDBConnect->getScoreList($this->_tmwCampaign);        
        // Resetting the video progress
        $gameStartData['videoProgress'] = 0;
        
    	$jsonData = utf8_encode(Zend_Json::encode($gameStartData));
        
        $this->getResponse()
                ->setHeader('Content-Type', 'text/html')
                ->setBody($jsonData)
                ->sendResponse();
        exit;
    }
    
    /**
	* End a Game AJAX callback
	*/
    public function gameendAction($playerPhoto = '/assets/img/admin/logo.jpg') {
        
        $gameStatus = 'off';
        $playerProgress       = $this->getRequest()->getParam('playerProgress');
        $playerTime           = $this->getRequest()->getParam('playerTime');
        //$usedPlayerId      = $this->getRequest()->getParam('playerId');
        // Getting player id from the current game status table
        $usedPlayerId = $this->_tmwDBGameStatus->getCurrentPlayer();
        $playerPhoto =  $this->_tmwDBGameStatus->getCurrentPlayerPhoto();
        //var_dump($usedPlayerId);
        //var_dump($playerPhoto);
        
        if(isset($usedPlayerId['playerId'])){
            $playerDetails = $this->_tmwDBConnect->getPlayerDetails($usedPlayerId['playerId'], $this->_tmwCampaign);
            //$playerPhoto = $usedPlayerId['player_photo'];
            //var_dump($playerPhoto);
            
            $this->_tmwDBConnect->setPlayerPhoto($playerPhoto['player_photo'], $usedPlayerId['playerId']);
            
            // TO BE REMOVED ONLY FOR TESTING THOSE SHOULD COME FROM MOTION SERVICE
            //$playerDetails['playerProgress'] = '69';
            //$playerDetails['playerTime'] = rand(100,300);
            if($playerProgress > $playerDetails['playerProgress']){
				$playerDetails['playerProgress'] = $playerProgress;
				$playerDetails['playerTime'] = $playerTime;
            }
            //var_dump($playerDetails['playerProgress']);
            //var_dump($playerDetails['playerTime']);
        }
        else{
            $playerDetails['firstname'] = 'TMW';
            $playerDetails['lastname'] = 'Agency';
            $playerDetails['twitterhandle'] = 'tmwagency';
            $playerDetails['playerScore'] = '0';
            $playerDetails['playerProgress'] = '0';
            $playerDetails['playerTime'] = '0';
        }
        
        if(isset($playerDetails['twitterhandle']) && !empty($playerDetails['twitterhandle'])){
            $playerTwitterImg = $this->gettwitterdetailsAction($playerDetails['twitterhandle']);
        }
        else
        {
            $playerTwitterImg = '/assets/img/admin/logo.jpg';
        }
        
        $playerDetails['playerTwitterImg'] = $playerTwitterImg;
        
        // Calculate score
        $playerDetails['playerScore'] = $playerDetails['playerProgress'] * 10000 + $playerDetails['playerTime'];
        
        // Save playerscore, playertime and player progress        
        $this->_tmwDBConnect->setPlayerScoreTimeProgress($usedPlayerId['playerId'], $playerDetails['playerScore'], $playerDetails['playerTime'], $playerDetails['playerProgress']);
        
        $gameEndData['playerDetails'] = $playerDetails;
        //var_dump($gameEndData);
        // Getting the latest scoreboard
        $gameEndData['scoreBoard'] = $this->_tmwDBConnect->getScoreList($this->_tmwCampaign);
        // setting the video progress
        $gameEndData['videoProgress'] = $playerDetails['playerProgress'];
        // setting the video progress
        $gameEndData['playerPhoto'] = $playerPhoto['player_photo'];
        //var_dump($gameEndData);
        
        // Post messege to Twitter
        $winner = false;
        if($playerDetails['playerProgress'] == 100){
			$winner = true;
        }
        $this->posttotwitterAction($playerDetails['twitterhandle'], $gameEndData['playerPhoto'], $winner);
        
        // Save the current user in the current game state
        $this->_tmwDBGameStatus->setCurrentPlayer(null, $gameStatus, 0, $playerPhoto['player_photo']);
        
        
		$jsonData = utf8_encode(Zend_Json::encode($gameEndData));
        //var_dump($jsonData);
		//die;
        
        $this->getResponse()
                ->setHeader('Content-Type', 'text/html')
                ->setBody($jsonData)
                ->sendResponse();
        exit;
    }
    
    public function resetuserdetailsAction(){
        $gameStatus     = 'off';
        
        $playerDetails['firstname']         = 'TMW';
        $playerDetails['lastname']          = 'Agency';
        $playerDetails['twitterhandle']     = 'tmwagency';
        $playerDetails['playerScore']       = '0';
        $playerDetails['playerProgress']    = '0';
        $playerDetails['playerTime']        = '0';
        
        if(isset($playerDetails['twitterhandle']) && !empty($playerDetails['twitterhandle'])){
            $playerTwitterImg = $this->gettwitterdetailsAction($playerDetails['twitterhandle']);            
        }
        else
        {
            $playerTwitterImg = '/assets/img/admin/logo.jpg';              
        }
        
        $playerDetails['playerTwitterImg']  = $playerTwitterImg;
        
        $resetGameData['playerDetails']     = $playerDetails;
        
        // Save the current user in the current game state
        $this->_tmwDBGameStatus->setCurrentPlayer(null, $gameStatus, $playerDetails['playerProgress'], null);
        
        $jsonData = utf8_encode(Zend_Json::encode($resetGameData));
        
        $this->getResponse()
                ->setHeader('Content-Type', 'text/html')
                ->setBody($jsonData)
                ->sendResponse();
        exit;
    }

    /**
     * The sample contents from the motion tracking url
     */
    public function getmotiondataAction() {
        
        $state = false;
        
        if($state){
            $motiondata['RFHandleId']       = 'bardis';
            $motiondata['game_status']      = true;
            $motiondata['game_progress']    = 0;
            $motiondata['game_time']        = 0;
        }
        else{
            $motiondata['RFHandleId']       = null;
            $motiondata['game_status']      = false;
            $motiondata['game_progress']    = 69;
            $motiondata['game_time']        = rand(100, 300);            
        }
        
        
    	$jsonData = utf8_encode(Zend_Json::encode($motiondata));
        
        $this->getResponse()
                ->setHeader('Content-Type', 'text/html')
                ->setBody($jsonData)
                ->sendResponse();
        exit;
    }
    
    public function fixPlayerDbFields(){    
                    
                    // Add the score and the progress percentance for the user to be used in the game
                    $dbData['playerScore']    	= 0;
                    $dbData['playerProgress'] 	= 0;
                    $dbData['playerTime']     	= 0;
                    $allPlayersDetails     		= $this->_tmwDBConnect->getAllPlayerDetails($this->_tmwCampaign);
                    				
					
                    foreach($allPlayersDetails as $playerId => $playerDBDetails)
					{
					var_dump($playerId);
						if(!isset($playerDBDetails["playerScore"])){
							$insertEX = $this->_tmwDBConnect->addDbData($dbData,$playerId);
						}
					}
    
    }
}
