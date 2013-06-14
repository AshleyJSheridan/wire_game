<?php
/**
 * Admin
 * 
 * @package MM FB APP
 * @version 2.0.0
 */
class ADMIN_CampaignNames extends Zend_Db_Table
{
    protected $_name = 'mm_facebook_comp_names';
    
    // save the Settings data
    public function insertCampaignName($newName) {
        
        if($newName) { 
            $row = $this->getAdapter()->query("SELECT COUNT(1) FROM mm_facebook_comp_names WHERE compName = '".$newName."'")->fetch();
            if($row['COUNT(1)']) { 
                return 0;  
            }
            else
            {
                $data = array('compName' => $newName);         
                $insert_id = $this->insert($data);
                return $insert_id;
            }
        }
    }
    
    // export campaign data
    public function exportData($campaignName) {
        
        $data = $this->getAdapter()->query("SELECT c.fbID, c.fbDateTime, c.fbEmail, group_concat( d.fbdData separator '|*|') as `submittedData` , group_concat( d.fbdField separator '|*|') as `submittedDataFields` FROM `mm_facebook_comp` as c join `mm_facebook_comp_details` as d on c.fbID = d.fbID WHERE c.fbComp = '".$campaignName."' GROUP BY c.fbID;")->fetchAll();

        foreach($data as &$datarow)
        {
            $datarow['submittedData']       = explode('|*|', $datarow['submittedData']);
            $datarow['submittedDataFields'] = explode('|*|', $datarow['submittedDataFields']);
            
            foreach($datarow['submittedDataFields'] as $key => $fieldName)
            {  
                $datarow[$fieldName] = $datarow['submittedData'][$key];
            }
            
            unset($datarow['submittedData']);
            unset($datarow['submittedDataFields']);
        }
        return $data;	
    }
    
    // get all Campaign names
    public function getAllNames() {
       
        $row = $this->getAdapter()->query("SELECT * FROM mm_facebook_comp_names;")->fetchAll();
        
        foreach ($row as $key => $campaignNames)
        {
                $parsedData[$campaignNames['compName']] = $campaignNames['compName'];
        }        
        
        return $parsedData;
    }
    
}
