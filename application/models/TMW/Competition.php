<?php
/** 
 * @package TMW WIRE GAME
 * @version 1.0.0
 */
class TMW_Competition extends Zend_Db_Table
{
    protected $_name = 'tmw_wire_comp';
    
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
                    INSERT INTO tmw_wire_comp_details(playerId,detailsField,detailsData) VALUES ('$parent','$fbKey','$fbVal');
		");
            }
            return true;
        } else {
            throw new Exception('Could not insert data');
        }	
    }
    
    // load the application settings from tha database
    public function getAppSettings() {
        $row = $this->getAdapter()->query("SELECT * FROM tmw_wire_app_settings;")->fetch();
        if(!$row) {
            throw new Exception('Could not load application settings data from database');
        } else {
            return $row;
        }	
    }
    
    // load the application texts from tha database
    public function getAppContents($campaignName) {
        $row = $this->getAdapter()->query("SELECT * FROM tmw_wire_app_texts WHERE campaignName = '".$campaignName."';")->fetch();
        if(!$row) {
            throw new Exception('Could not load application text contents from database');
        } else {
            return $row;
        }	
    }
    
    // load the application form elements from tha database
    public function getAppFormElements($campaignName) {
        $row = $this->getAdapter()->query("SELECT * FROM tmw_wire_app_form_elements WHERE campaignName = '".$campaignName."' ORDER BY elementOrder;")->fetchAll();
        if(!$row) {
            throw new Exception('Could not load application form elements from database');
        } else {
            return $row;
        }	
    }
}
