<?php
/**
 * Admin
 * 
 * @package TMW WIRE GAME
 * @version 1.0.0
 */
class ADMIN_CampaignNames extends Zend_Db_Table
{
    protected $_name = 'tmw_wire_comp_names';
    
    // save the Settings data
    public function insertCampaignName($newName) {
        
        if($newName) { 
            $row = $this->getAdapter()->query("SELECT COUNT(1) FROM tmw_wire_comp_names WHERE compName = '".$newName."'")->fetch();
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
        
        $data = $this->getAdapter()->query("SELECT c.playerId, c.registeredOn, c.playerEmail, group_concat( d.detailsData separator '|*|') as `submittedData` , group_concat( d.detailsField separator '|*|') as `submittedDataFields` FROM `tmw_wire_comp` as c join `tmw_wire_comp_details` as d on c.playerId = d.playerId WHERE c.campaign = '".$campaignName."' GROUP BY c.playerId;")->fetchAll();

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
       
        $row = $this->getAdapter()->query("SELECT * FROM tmw_wire_comp_names;")->fetchAll();
        
        foreach ($row as $key => $campaignNames)
        {
                $parsedData[$campaignNames['compName']] = $campaignNames['compName'];
        }        
        
        return $parsedData;
    }
    
}
