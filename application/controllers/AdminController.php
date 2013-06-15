<?php
class AdminController extends Zend_Controller_Action
{
    public function init()
    {
        $_GET['param'] = ADMIN_Helper::GetUrlParam();
        $this->_helper->layout->setLayout('admin');
    }
 
    public function loginAction()
    {        
        $users = new ADMIN_Users();
        
        if($this->getRequest()->isPost()){
            $data =  $this->getRequest()->getPost();
            if(!empty($data['username']) && !empty($data['password']))
            {
                $auth = Zend_Auth::getInstance();

                $authAdapter = new Zend_Auth_Adapter_DbTable($users->getAdapter(),'tmw_wire_app_users');

                $authAdapter->setIdentityColumn('username')
                            ->setCredentialColumn('password');

                $authAdapter->setIdentity($data['username'])
                            ->setCredential($data['password']);

                $result = $auth->authenticate($authAdapter);
 
                if($result->isValid()){
                    $storage = new Zend_Auth_Storage_Session();
                    $storage->write($authAdapter->getResultRowObject());
                    $this->_redirect('admin/');
                } else {
                    $this->view->errorMessage = "Invalid username or password. Please try again.";
                } 
            } else {
                $this->view->errorMessage = "Invalid username or password. Please try again.";
            } 
        }
    }
    
    public function logoutAction()
    {
        $storage = new Zend_Auth_Storage_Session();
        $storage->clear();
        $this->_redirect('admin/login');
    }
    
    public function indexAction()
    {
        $storage    = new Zend_Auth_Storage_Session();
        $data       = $storage->read();
        
        if(!$data){
            $this->_redirect('admin/login');
        }
                
        $this->view->username   = $data->username;
        $this->view->errorClass = "red";
        
        $appSettings = new ADMIN_ManageAppSettingsForm();
        $appSettings->setAttrib('id', 'appSettings');
        $dbConnect = new ADMIN_AppSettings();
        $appSettings->populate($dbConnect->getData());
        $currentCompName = $appSettings->getElement('campaignName')->getValue();
        
        $this->view->manageAppSettingsForm = $appSettings;
                
        $appTexts = new ADMIN_ManageAppTextsForm();
        $appTexts->setAttrib('id', 'appTexts');
        $dbConnect = new ADMIN_AppTexts();
        $appTexts->populate($dbConnect->getData());
        $appTexts->getElement('campaignName')->setValue($currentCompName);
        
        $this->view->manageAppTextsForm = $appTexts;
        
        $appFields = new ADMIN_ManageAppForm();
        $appFields->setAttrib('id', 'appFields');
        $dbConnect = new ADMIN_AppFields();
        $loadedFormElements = $dbConnect->getData();
        $fieldsCount = count($loadedFormElements);
        $appFields->setFieldsCount($fieldsCount);
        for($j = 0; $j < $fieldsCount; $j++)
        {
            $fieldTypes[] = $loadedFormElements[$j]["elementType"];
        }
        $appFields->setFieldTypes($fieldTypes);
        
        for($i = 0; $i < $fieldsCount; $i++)
        {
            $appFields->getSubForm('fieldForm'.$i)->populate($loadedFormElements[$i]);
            $appFields->getSubForm('fieldForm'.$i)->getElement('campaignName')->setValue($currentCompName);
        }
        
        $this->view->manageAppForm = $appFields;
        
        $appImages = new ADMIN_ManageImagesForm();
        $appImages->setAttrib('id', 'appImages');
        
        $this->view->manageImagesForm = $appImages;
        
        $appExport = new ADMIN_ExportDataForm();
        $appExport->setAttrib('id', 'appExport');
        $dbConnect = new ADMIN_CampaignNames();
        $campaignNames = $dbConnect->getAllNames();
        $appExport->setCampaignNames($campaignNames);
        
        $this->view->exportDataForm = $appExport;
        
        // posting
	if ($this->_request->isPost()) {
            
            $formData = $this->_request->getPost();
            
            $formName       = $formData['formName'];
            $ajaxResponce   = $formData['isAjax'];
            
            if(isset($formName)) {
                
                switch ($formName) {
                    case 'appSettings':
                        $appSettings = $this->progressAppSettings($appSettings, $formData, $ajaxResponce);
                        
                        $currentCompName = $appSettings->getElement('campaignName')->getValue();
                        $appTexts->getElement('campaignName')->setValue($currentCompName);
                        for($i = 0; $i < $fieldsCount; $i++)
                        {
                            $appFields->getSubForm('fieldForm'.$i)->getElement('campaignName')->setValue($currentCompName);
                        }
                        
                        $dbNamesConnect = new ADMIN_CampaignNames();
                        $insertNewCampaignName = $dbNamesConnect->insertCampaignName($currentCompName);
                        if($insertNewCampaignName)
                        {
                            $this->view->errorMessage  .= ' New campaign has been created.';
                            $this->view->errorClass = "green";
                        }
                        else
                        {      
                            $this->view->errorMessage  .= ' The campaign name that you use has already been used in the past. Please change the name or during data export you will get merged data.';                   
                        }
                        break;
                        
                    case 'appTexts':
                        $appTexts = $this->progressAppTexts($appTexts, $formData, $ajaxResponce);
                        break;
                    
                    case 'appFields':
                        $appFields = $this->progressAppFields($appFields, $formData, $ajaxResponce, $fieldsCount);
                        break;
                    
                    case 'appImages':
                        $appImages = $this->progressAppImages($appImages, $formData, $ajaxResponce);
                        break;
                    
                    case 'appExport':
                        $appExport = $this->progressDataExport($appExport, $formData, $ajaxResponce);
                        break;
                    
                    default:
                        $this->_redirect('admin/');
                        break;
                }
            }
            
            $this->view->manageAppSettingsForm  = $appSettings;
            $this->view->manageAppTextsForm     = $appTexts;
            $this->view->manageAppForm          = $appFields;
            $this->view->manageImagesForm       = $appImages;
            $this->view->exportDataForm         = $appExport;
        }
    }
    
    public function progressAppSettings($form, $formData, $ajaxResponce)
    {
        $dbConnect      = new ADMIN_AppSettings();
        $dbNamesConnect = new ADMIN_CampaignNames();
        
        $formData['campaignName']  = preg_replace("/[^a-zA-Z0-9\-]/", "", str_replace(' ', '-', strtolower($formData['campaignName'])));
        
        if ($form->isValid($formData)) {
           // remove submit from form info, as well as not needed data
           unset(
                $formData['submitSettings'],
                $formData['formName'],
                $formData['isAjax']
           );
           
           $insertID = $dbConnect->setData($formData);
           
           if($insertID) {
               if( $ajaxResponce ) {
                   $jsonData = utf8_encode(Zend_Json::encode('submit'));
                   $this->getResponse()
                        ->setHeader('Content-Type', 'text/html')
                        ->setBody($jsonData)
                        ->sendResponse();
                   exit;
               } else {
                   $form->populate($formData);
               }
               $this->view->errorMessage    = 'Competition Details saved with success.';
               $this->view->errorClass      = "green";
            } else {
                // set up for ajax validation
                if( $ajaxResponce ) {
                    $errorReturned = $form->getMessages();
                    
                    foreach($errorReturned as $field => $errors) {
                        $element = $form->getElement($field);
                        $message = $element->getAttribs();
                        $errorMessages[$field] = $message['message'];
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
                $this->view->errorMessage  = 'There where errors. Competition details were not saved.';
            }
        }
        return $form;
    }
    
    public function progressAppTexts($form, $formData, $ajaxResponce)
    {
        $dbConnect = new ADMIN_AppTexts();
        
        if ($form->isValid($formData)) {
           // remove submit from form info, as well as not needed data
           unset(
                $formData['submitTexts'],
                $formData['formName'],
                $formData['isAjax']
           );
           
           $insertID = $dbConnect->setData($formData);
           
           if($insertID) {
               if( $ajaxResponce ) {
                   $jsonData = utf8_encode(Zend_Json::encode('submit'));
                   $this->getResponse()
                        ->setHeader('Content-Type', 'text/html')
                        ->setBody($jsonData)
                        ->sendResponse();
                   exit;
               } else {
                   $form->populate($formData);
               }
               $this->view->errorMessage  = 'Texts saved with success.';
               $this->view->errorClass = "green";
            } else {
                // set up for ajax validation
                if( $ajaxResponce ) {
                    $errorReturned = $form->getMessages();
                    
                    foreach($errorReturned as $field => $errors) {
                        $element = $form->getElement($field);
                        $message = $element->getAttribs();
                        $errorMessages[$field] = $message['message'];
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
                $this->view->errorMessage  = 'There where errors. Competition texts were not saved.';
            }
        }
        return $form;
    }
    
    public function progressAppFields($form, $formData, $ajaxResponce, $fieldsCount)
    {
        $dbConnect      = new ADMIN_AppFields();
        
        foreach ($formData['elementName'] as &$fieldValue)
        {
            $fieldValue = preg_replace("/[^a-zA-Z0-9]/", "", str_replace(' ', '', strtolower($fieldValue)));
        }
        
        if ($form->isValid($formData)) {
            
            // remove submit from form info, as well as not needed data            
            foreach($formData as $key => $value) {
                if(strpos($key, '$') !== false)
                {
                    unset($formData[$key]);
                }
            }
            
            unset(
                $formData['radioOptionInput'],
                $formData['submitFields'],
                $formData['formName'],
                $formData['isAjax']
            );
           
            $insertID = $dbConnect->setData($formData, $fieldsCount);
           
            if($insertID) {
               if( $ajaxResponce ) {
                   $jsonData = utf8_encode(Zend_Json::encode('submit'));
                   $this->getResponse()
                        ->setHeader('Content-Type', 'text/html')
                        ->setBody($jsonData)
                        ->sendResponse();
                   exit;
               } else {
                   $parsedData = array();
                   
                   foreach ($formData as $key => $fields)
                   {
                       for($i = 0; $i < $fieldsCount; $i++)
                       {
                           $parsedData[$i][$key] = (isset($fields[$i])) ?  $fields[$i] : 0;
                       }
                   }
                   
                   for($i = 0; $i < $fieldsCount; $i++)
                   {
                       $form->getSubForm('fieldForm'.$i)->populate($parsedData[$i]);
                   }
               }
               $this->view->errorMessage  = 'Form Fields saved with success.';
               $this->view->errorClass = "green";
            } else {
                // set up for ajax validation
                if( $ajaxResponce ) {
                    $errorReturned = $form->getMessages();
                    
                    foreach($errorReturned as $field => $errors) {
                        $element = $form->getElement($field);
                        $message = $element->getAttribs();
                        $errorMessages[$field] = $message['message'];
                    }
                    
                    $jsonData = utf8_encode(Zend_Json::encode($errorMessages));
                    $this->getResponse()
                         ->setHeader('Content-Type', 'text/html')
                         ->setBody($jsonData)
                         ->sendResponse();
                    exit;
                } else {
                    $parsedData = array();
                    
                    foreach ($formData as $key => $fields)
                    {
                        for($i = 0; $i < $fieldsCount; $i++)
                        {
                            $parsedData[$i][$key] = $fields[$i];
                        }
                    }
                    
                    for($i = 0; $i < $fieldsCount; $i++)
                    {
                        $form->getSubForm('fieldForm'.$i)->populate($parsedData[$i]);
                    }
		}
                $this->view->errorMessage  = 'There where errors. Competition Form Fields were not saved.';
            }
        }
        return $form;
    }
    
    public function progressAppImages($form, $formData, $ajaxResponce)
    {        
        if ($form->isValid($formData)) {       
            $filesUploaded = false;
            // The file upload field handling start
            $upload = new Zend_File_Transfer_Adapter_Http();
            $files = array_keys($upload->getFileInfo());
            $upload->addFilter('Rename', array('target' => realpath(APPLICATION_PATH . '/../public_html/assets/img/competition/').'/addImage.jpg', 'overwrite' => true), $files[0]);
            $filesUploaded = $upload->receive($files[0]);
            $upload->addFilter('Rename', array('target' => realpath(APPLICATION_PATH . '/../public_html/assets/img/competition/').'/fbShareImage.jpg', 'overwrite' => true), $files[1]);
            $filesUploaded = $upload->receive($files[1]);
            // File upload field handling ends           
           
           if($filesUploaded) {
               if( $ajaxResponce ) {
                   $jsonData = utf8_encode(Zend_Json::encode('submit'));
                   $this->getResponse()
                        ->setHeader('Content-Type', 'text/html')
                        ->setBody($jsonData)
                        ->sendResponse();
                   exit;
               } else {
                   
               }
               $this->view->errorMessage  = 'Images uploaded with success.';
               $this->view->errorClass = "green";
            } else {
                // set up for ajax validation
                if( $ajaxResponce ) {
                    $errorReturned = $form->getMessages();
                    
                    foreach($errorReturned as $field => $errors) {
                        $element = $form->getElement($field);
                        $message = $element->getAttribs();
                        $errorMessages[$field] = $message['message'];
                    }
                    
                    $jsonData = utf8_encode(Zend_Json::encode($errorMessages));
                    $this->getResponse()
                         ->setHeader('Content-Type', 'text/html')
                         ->setBody($jsonData)
                         ->sendResponse();
                    exit;
                } else {
                    
		}
                $this->view->errorMessage  = 'Only one or no images were uploaded.';
            }
        }
        return $form;
    }
    
    public function progressDataExport($form, $formData, $ajaxResponce)
    {
        $dbConnect = new ADMIN_CampaignNames();
        
        if ($form->isValid($formData)) {
           // remove submit from form info, as well as not needed data
           unset(
                $formData['submitExport'],
                $formData['formName'],
                $formData['isAjax']
           );
           
           $exportedData = $dbConnect->exportData($formData['campaignName']);
           
           if(!empty($exportedData)) {
               if( $ajaxResponce ) {
                   $jsonData = utf8_encode(Zend_Json::encode('submit'));
                   $this->getResponse()
                        ->setHeader('Content-Type', 'text/html')
                        ->setBody($jsonData)
                        ->sendResponse();
                   exit;
               } else {
                   $this->query_to_csv($exportedData, 'competition-data');
               }
               $this->view->errorMessage  = 'Data exported with success.';
               $this->view->errorClass = "green";
            } else {
                // set up for ajax validation
                if( $ajaxResponce ) {
                    $errorReturned = $form->getMessages();
                    
                    foreach($errorReturned as $field => $errors) {
                        $element = $form->getElement($field);
                        $message = $element->getAttribs();
                        $errorMessages[$field] = $message['message'];
                    }
                    
                    $jsonData = utf8_encode(Zend_Json::encode($errorMessages));
                    $this->getResponse()
                         ->setHeader('Content-Type', 'text/html')
                         ->setBody($jsonData)
                         ->sendResponse();
                    exit;
                } else {                   
		}
                $this->view->errorMessage  = 'There was no data for that competition. Data was not exported.';
            }
        }
        return $form;
    }
    
    function query_to_csv($dataForCSV, $filename, $attachment = true) {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNeverRender();
        
        // send response headers to the browser
        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment;filename='.$filename.'.csv');
        $fp = fopen('php://output', 'w');        
        
        foreach ($dataForCSV[0] as $key => $value) {
            $headers[] = $key;
        }
        fputcsv($fp, $headers, ';', '"');
        
        foreach ($dataForCSV as $dataRow) {
            fputcsv($fp, $dataRow, ';', '"');
        }
       
        fclose($fp);
    }
}