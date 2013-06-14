<?php
/**
 * Facebook
 * 
 * @package MM FB APP
 * @version 2.0.0
 */
class MM_Facebook extends Zend_Db_Table
{
    protected $_name = 'mm_facebook_comp';
    
    // save the mandatory data in seperate table fields
    public function addMandatoryData($data) {
        if($data && is_array($data)) {
            $insert_id = $this->insert($data);		
            return $insert_id;
        } else {
            throw new Exception('Could not insert mandatory data');
	}	
    }
    
    // save the extra data in the ref table
    public function addDetailData($data,$parent) {
        if($data && is_array($data) && $parent) {
            foreach($data as $fbKey => $fbVal) {
                $this->getAdapter()->query("
                    INSERT INTO mm_facebook_comp_details(fbID,fbdField,fbdData) VALUES ('$parent','$fbKey','$fbVal');
		");
            }
            return true;
        } else {
            throw new Exception('Could not insert data');
        }	
    }
    
    // load the application settings from tha database
    public function getAppSettings() {
        $row = $this->getAdapter()->query("SELECT * FROM mm_facebook_app_settings;")->fetch();
        if(!$row) {
            throw new Exception('Could not load application settings data from database');
        } else {
            return $row;
        }	
    }
    
    // load the application texts from tha database
    public function getAppContents($campaignName) {
        $row = $this->getAdapter()->query("SELECT * FROM mm_facebook_app_texts WHERE facebookCampaignName = '".$campaignName."';")->fetch();
        if(!$row) {
            throw new Exception('Could not load application text contents from database');
        } else {
            return $row;
        }	
    }
    
    // load the application form elements from tha database
    public function getAppFormElements($campaignName) {
        $row = $this->getAdapter()->query("SELECT * FROM mm_facebook_app_form_elements WHERE facebookCampaignName = '".$campaignName."' ORDER BY elementOrder;")->fetchAll();
        if(!$row) {
            throw new Exception('Could not load application form elements from database');
        } else {
            return $row;
        }	
    }
}
