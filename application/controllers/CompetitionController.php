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
        $this->view->facebookShare['summary']     	= $this->_appSettings['summary'];
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
    }

    /**
     * Root / Index page Action
     */
    public function indexAction() {
        
        //$campaignPlayerList     = $this->_tmwDBConnect->getUserList($this->_tmwCampaign);
        $this->view->scoreList  = $this->_tmwDBConnect->getScoreList($this->_tmwCampaign);
    }

    /**
     * Register page Action
     */
    public function registerAction() {
        
        // create form
        $form               = new TMW_Competitionform($this->_appFormElements);
        $formHasImageField  = false;
        
        foreach($this->_appFormElements as $formElement)
        {
            if($formElement['elementType'] == 'File' && $formElement['elementVisibility'] == true )
            {
                $formHasImageField = true;
            }
        }
        
        $form->setAttrib('enctype', 'multipart/form-data');

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
                    $mandatoryData = array(
                        'playerEmail' => $formData['playerEmail'],
                        'campaign'    => $formData['campaignName']
                    );

                    // remove submit from form info, as well as mandatory data
                    unset(
                        $formData['playerEmail'],
                        $formData['submitBtn'],
                        $formData['campaign'],
                        $formData['fbTermsConditions'],
                        $formData['campaignName']
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
                            $this->_redirect('/competition/' . $this->_tmwCampaign . '/submit/');
                        }
                    }                                
            } else {
                    // set up for ajax validation
                    if( $this->_ajaxParam ) {
                        $errorReturned = $form->getMessages();
                        foreach($errorReturned as $field => $errors) {
                                        $element = $form->getElement($field);
                                        $message = $element->getAttribs();
                                        $errorMessages[$field] = $message['message'];
                        }
                        if (empty($errorReturned))
                        {
                            if($formData['playerImage'] == '' && $formHasImageField)
                            {
                                $emptyImageError = $form->getElement('playerImage')->getAttribs();
                                $errorMessages['playerImage'] = $emptyImageError['message'];
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
        
        // create form
        $form               = new TMW_Competitionform($this->_appFormElements);
        $formHasImageField  = false;
        
        foreach($this->_appFormElements as $formElement)
        {
            if($formElement['elementType'] == 'File' && $formElement['elementVisibility'] == true )
            {
                $formHasImageField = true;
            }
        }
        
        $form->setAttrib('enctype', 'multipart/form-data');

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
                    $mandatoryData = array(
                        'playerEmail' => $formData['playerEmail'],
                        'campaign'    => $formData['campaignName']
                    );

                    // remove submit from form info, as well as mandatory data
                    unset(
                        $formData['playerEmail'],
                        $formData['submitBtn'],
                        $formData['campaign'],
                        $formData['fbTermsConditions'],
                        $formData['campaignName']
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
                            $this->_redirect('/competition/' . $this->_tmwCampaign . '/submit/');
                        }
                    }                                
            } else {
                    // set up for ajax validation
                    if( $this->_ajaxParam ) {
                        $errorReturned = $form->getMessages();
                        foreach($errorReturned as $field => $errors) {
                                        $element = $form->getElement($field);
                                        $message = $element->getAttribs();
                                        $errorMessages[$field] = $message['message'];
                        }
                        if (empty($errorReturned))
                        {
                            if($formData['playerImage'] == '' && $formHasImageField)
                            {
                                $emptyImageError = $form->getElement('playerImage')->getAttribs();
                                $errorMessages['playerImage'] = $emptyImageError['message'];
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
    
}
