<?php
/**
 * Admin
 * 
 * @package MM FB APP
 * @version 2.0.0
 */
class ADMIN_AppSettings extends Zend_Db_Table
{
    protected $_name = 'mm_facebook_app_settings';
    
    // save the Settings data
    public function setData($data) {
        if($data && is_array($data)) {
            
            $where = array(
                'id = ?' => 1 // you can quote() this value
            );
            
            $insert_id = $this->update($data, $where);		
            return $insert_id;
        } else {
            throw new Exception('Could not insert application settings data');
	}	
    }
    
    // get the Settings data
    public function getData() {
       
        $row = $this->getAdapter()->query("SELECT * FROM mm_facebook_app_settings WHERE id = 1;")->fetch();
        
        if(!$row) {
            throw new Exception('Could not load application settings from database');
        } else {
            return $row;
        }	
    }
    
    // get the Active Comp Name
    public function getActiveComp() {
       
        $row = $this->getAdapter()->query("SELECT facebookCampaignName FROM mm_facebook_app_settings WHERE id = 1;")->fetch();
        
        if(!$row) {
            throw new Exception('Could not load application settings from database');
        } else {
            return $row;
        }	
    }
    
}
