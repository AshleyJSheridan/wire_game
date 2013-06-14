<?php
/**
 * Facebook Controller
 *
 * @package MM FB APP
 * @version 2.0.0
 */
class FacebookController extends Zend_Controller_Action {
    
    protected $_facebookAppUrl;
    protected $_facebookCampaign;
    protected $_facebookCampaignName;
    protected $_facebookData;
    protected $_facebookPage;
    protected $_appSettings;
    protected $_appContents;
    protected $_appFormElements;
        
    public function init() {
        
        $fbDBConnect                    = new MM_Facebook();
        $this->_appSettings             = $fbDBConnect->getAppSettings();
        $this->_appContents             = $fbDBConnect->getAppContents($this->_appSettings['facebookCampaignName']);
        $this->_appFormElements         = $fbDBConnect->getAppFormElements($this->_appSettings['facebookCampaignName']);
        //var_dump($this->_appFormElements);
        
        // define app page we are currently on
        $this->_facebookCampaign        = $this->getRequest()->getParam('campaign');
        $this->_facebookAppUrl          = $this->_appSettings['facebookAppUrl'];
        
        $this->_facebookCampaignName    = $this->_appSettings['facebookCampaignName'];
	$this->_facebookPage            = $this->getRequest()->getParam('action');
	$this->_ajaxParam               = $this->getRequest()->getParam('ajax');

	// set page titles
	$this->view->pageTitle          = $this->_appSettings['title'];

	// set page Google Analytics
	$this->view->pageGA             = $this->_appSettings['gaId'];

	// set page css
	$this->view->pageStyles         = $this->_facebookCampaign.'.css';

	// set share button / JS options
	$this->view->facebookShare                      = array();
	$this->view->facebookShare['title']		= $this->_appSettings['title'];
	$this->view->facebookShare['url']		= $this->_facebookAppUrl . '/app_' . $this->_appSettings['appId'];
	$this->view->facebookShare['summary']     	= $this->_appSettings['summary'];
	$this->view->facebookShare['image']		= '/assets/img/facebook/competition/fbShareImage.jpg';
	$this->view->facebookShare['appID']             = $this->_appSettings['appId'];
	$this->view->facebookShare['campaign']          = $this->_facebookCampaign;
	$this->view->facebookShare['campaignName']      = $this->_facebookCampaignName;
        
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
        //var_dump($this->view->formElements);

        // set layout
	$this->_helper->layout->setLayout('facebook');

	// load facebook SDK
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

        // reference the right views folder location
	$this->view->addScriptPath(APPLICATION_PATH . '/views/scripts/campaign/' . $this->_facebookCampaign);
	
        // if the user has not liked the page, we redirect to the like gate. uncomment after development is done so the likegate works properly
	/*if($this->_facebookPage != 'like' && $this->_facebookPage != 'submit' && !$pageStatus->liked && !$this->_ajaxParam) {
            $this->_redirect('/facebook/' . $this->_facebookCampaign . '/like/');
	}*/
    }

    /**
     * Root / Index page Action
     */
    public function indexAction() {
        // create form
	$form               = new MM_Facebookform($this->_appFormElements);
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
            
            if(isset($formData['fbEmail'])) {
                
                if ($form->isValid($formData)) {
                    
                    if ($formHasImageField)
                    {
                        // The file upload field handling start
                        $upload = new Zend_File_Transfer();
                        $files = array_keys($upload->getFileInfo());
                        $upload->addFilter('Rename', realpath(APPLICATION_PATH . '/../public_html/uploads/'.$this->_facebookCampaign.'/').'/'.$formData['fbEmail'].'-'.time().'.jpg', $files[0]);
                        $upload->receive($files[0]);

                        $formData['fbimage'] = $formData['fbEmail'].'-'.time().'.jpg';
                        unset(
                            $formData['MAX_FILE_SIZE']
                        );
                        // File upload field handling ends
                    }
                    
                    // split mandatory data into separate array
                    $mandatoryData = array(
                        'fbEmail'   => $formData['fbEmail'],
                        'fbComp'    => $formData['campaignName']
                    );

                    // remove submit from form info, as well as mandatory data
                    unset(
                        $formData['fbEmail'],
                        $formData['submitBtn'],
                        $formData['campaign'],
                        $formData['campaignName']
                    );
                    // add into DB
                    $fbDBConnect = new MM_Facebook();
                    $insertID = $fbDBConnect->addMandatoryData($mandatoryData);
                    $insertEX = $fbDBConnect->addDetailData($formData,$insertID);
                    if($insertID && $insertEX) {
                        if( $this->_ajaxParam ) {
                            $jsonData = utf8_encode(Zend_Json::encode('submit'));
                            $this->getResponse()
                                ->setHeader('Content-Type', 'text/html')
                                ->setBody($jsonData)
                                ->sendResponse();
                            exit;
                        } else {
                            $this->_redirect('/facebook/' . $this->_facebookCampaign . '/submit/');
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
                            if($formData['fbimage'] == '' && $formHasImageField)
                            {
                                $emptyImageError = $form->getElement('fbimage')->getAttribs();
                                $errorMessages['fbimage'] = $emptyImageError['message'];
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
