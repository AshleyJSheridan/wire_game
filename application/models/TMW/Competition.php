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
                $this->getAdapter()->query("INSERT INTO tmw_wire_comp_details(playerId,detailsField,detailsData) VALUES ('$parent','$fbKey','$fbVal');");
            }
            return true;
        } else {
            throw new Exception('Could not insert data');
        }	
    }
    
    // save the extra data in the ref table
    public function addDbData($data,$parent) {
        if($data && is_array($data) && $parent) {
            foreach($data as $fbKey => $fbVal) {
                $this->getAdapter()->query("INSERT INTO tmw_wire_comp_details(playerId,detailsField,detailsData) VALUES ('$parent','$fbKey','$fbVal');");
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
    
    // load the score list from tha database
    public function getScoreList($campaignName){        
        $data = $this->getAdapter()->query("SELECT c.playerId, group_concat( d.detailsData separator '|*|') as `playerData` , group_concat( d.detailsField separator '|*|') as `playerDataKeys` FROM `tmw_wire_comp` as c join `tmw_wire_comp_details` as d on c.playerId = d.playerId WHERE c.campaign = '".$campaignName."' GROUP BY c.playerId;")->fetchAll();

        if(!$data) {
            throw new Exception('Could not load score details from database');
        } else {
            
            $scoreList                  = array();
            $normalizedPlayerDetails    = array();
            
            foreach($data as $playerDetails) {
                
                $playerDataKeysArray            = explode('|*|', $playerDetails['playerDataKeys']);
                $playerDataArray                = explode('|*|', $playerDetails['playerData']);
                $normalizedPlayerDetails        = array_combine($playerDataKeysArray, $playerDataArray);                
                $normalizedPlayerDetails['id']  = $playerDetails['playerId'];
                
                $scoreList[]                    = $normalizedPlayerDetails;
            }
            
            // Obtain a list of user scores
            foreach ($scoreList as $key => $row) {
				if(isset($row['playerScore'])){
					$scores[$key]  = $row['playerScore'];
				}
            }

            // Sort the data with volume descending, edition ascending
            // Add $data as the last parameter, to sort by the common key
            array_multisort($scores, SORT_DESC, $scoreList);
            $topTen = array_slice($scoreList, 0, 5);
            
            return $topTen;
        }
        
    }
    
    // load the user list from the database
    public function getUserList($campaignName) {
        $data = $this->getAdapter()->query("SELECT c.playerId FROM `tmw_wire_comp` as c WHERE c.campaign = '".$campaignName."';")->fetchAll();
        
        if(!$data) {
            throw new Exception('Could not load user list from database');
        } else {
            return $data;
        }	
    }
    
    // load the user list from the database
    public function getPlayerIdfromRF($RFHandleId, $campaignName) {
        $data = $this->getAdapter()->query("SELECT c.playerId FROM `tmw_wire_comp` as c WHERE c.campaign = '".$campaignName."' AND c.RFHandleId = '".$RFHandleId."';")->fetch();
        
        if(!$data) {
            return null; //throw new Exception('Could not find user from database');
        } else {
            return $data;
        }	
    }
    
    // load the player details from the database
    public function getPlayerDetails($playerId, $campaignName) {
        $data = $this->getAdapter()->query("SELECT c.playerId, group_concat( d.detailsData separator '|*|') as `playerData` , group_concat( d.detailsField separator '|*|') as `playerDataKeys` FROM `tmw_wire_comp` as c join `tmw_wire_comp_details` as d on c.playerId = d.playerId WHERE c.campaign = '".$campaignName."' AND c.playerId = '".$playerId."' GROUP BY c.playerId;")->fetchAll();

        if(!$data) {
            throw new Exception('Could not load user details from database');
        } else {
            $normalizedPlayerDetails    = array();
            
            foreach($data as $playerDetails) {
                
                $playerDataKeysArray            = explode('|*|', $playerDetails['playerDataKeys']);
                $playerDataArray                = explode('|*|', $playerDetails['playerData']);
                $normalizedPlayerDetails        = array_combine($playerDataKeysArray, $playerDataArray);                
                $normalizedPlayerDetails['id']  = $playerDetails['playerId'];
            }
            
            return $normalizedPlayerDetails;
        }	
    }
    
    // load the player details from the database
    public function getAllPlayerDetails($campaignName) {
        $data = $this->getAdapter()->query("SELECT c.playerId, group_concat( d.detailsData separator '|*|') as `playerData` , group_concat( d.detailsField separator '|*|') as `playerDataKeys` FROM `tmw_wire_comp` as c join `tmw_wire_comp_details` as d on c.playerId = d.playerId WHERE c.campaign = '".$campaignName."' GROUP BY c.playerId;")->fetchAll();

        if(!$data) {
            throw new Exception('Could not load user details from database');
        } else {
            $normalizedPlayerDetails    = array();
            
            foreach($data as $playerDetails) {
                
                $playerDataKeysArray            = explode('|*|', $playerDetails['playerDataKeys']);
                $playerDataArray                = explode('|*|', $playerDetails['playerData']);
                $normalizedPlayerDetails[]        = array_combine($playerDataKeysArray, $playerDataArray);                
                //$normalizedPlayerDetails[]['id']  = $playerDetails['playerId'];
            }
            
            return $normalizedPlayerDetails;
        }	
    }
    
    // load the player gallery from the database
    public function getGalleryList($campaignName) {
        $data = $this->getAdapter()->query("SELECT c.playerId, group_concat( d.detailsData separator '|*|') as `playerData` , group_concat( d.detailsField separator '|*|') as `playerDataKeys` FROM `tmw_wire_comp` as c join `tmw_wire_comp_details` as d on c.playerId = d.playerId WHERE c.campaign = '".$campaignName."' GROUP BY c.playerId;")->fetchAll();

        if(!$data) {
            throw new Exception('Could not load user details from database');
        } else {
            $normalizedPlayerDetails    = array();
            
            foreach($data as $playerDetails) {
                
                $playerDataKeysArray            = explode('|*|', $playerDetails['playerDataKeys']);
                $playerDataArray                = explode('|*|', $playerDetails['playerData']);
                $normalizedPlayerDetails[]        = array_combine($playerDataKeysArray, $playerDataArray);                
                //$normalizedPlayerDetails[]['id']  = $playerDetails['playerId'];
            }
            
            foreach($normalizedPlayerDetails as $detailsKey => &$normPlayerDetails){
				if(!isset($normPlayerDetails['wiredPhoto'])){
					unset($normalizedPlayerDetails[$detailsKey]);
				}
            }
            
            return $normalizedPlayerDetails;
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

    // set the Current Player score at game end
    public function setPlayerScore($playerId, $playerScore) {        
        $this->getAdapter()->query("UPDATE tmw_wire_comp_details SET detailsData = '$playerScore' WHERE playerId = '$playerId' AND detailsField = 'playerScore'");
    }  

    // set the Current Player details at game end
    public function setPlayerScoreTimeProgress($playerId, $playerScore, $playerTime, $playerProgress) {        
        $this->getAdapter()->query("UPDATE tmw_wire_comp_details SET detailsData = '$playerScore' WHERE playerId = '$playerId' AND detailsField = 'playerScore'");
        $this->getAdapter()->query("UPDATE tmw_wire_comp_details SET detailsData = '$playerTime' WHERE playerId = '$playerId' AND detailsField = 'playerTime'");
        $this->getAdapter()->query("UPDATE tmw_wire_comp_details SET detailsData = '$playerProgress' WHERE playerId = '$playerId' AND detailsField = 'playerProgress'");
    }    
    
    // save the game user photo data in the ref table
    public function setPlayerPhoto($playerPhoto, $playerId) {
		$data = $this->getAdapter()->query("SELECT detailsData FROM tmw_wire_comp_details WHERE playerId = '$playerId' AND detailsField = 'wiredPhoto'")->fetchAll();
		//var_dump($data);
        if(!$data) {
			$this->getAdapter()->query("INSERT INTO tmw_wire_comp_details(playerId,detailsField,detailsData) VALUES ('$playerId','wiredPhoto','$playerPhoto');");
            return true; 
        } else {
			$this->getAdapter()->query("UPDATE tmw_wire_comp_details SET detailsData = '$playerPhoto' WHERE playerId = '$playerId' AND detailsField = 'wiredPhoto'");
		}   
            
    } 
    
}