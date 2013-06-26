<?php
/**
 * Curent Game Status
 * 
 * @package TMW WIRE GAME
 * @version 1.0.0
 */
class TMW_CurrentGameState extends Zend_Db_Table
{
    protected $_name = 'tmw_wire_current_state';
    
    // save the Settings data
    public function setData($data) {
        if($data && is_array($data)) {
            
            $where = array(
                'id = ?' => 1 // you can quote() this value
            );
            
            $insert_id = $this->update($data, $where);		
            return $insert_id;
        } else {
            throw new Exception('Could not insert current game status data');
	}	
    }
    
    // get the current game status data
    public function getCurrentGameState() {
        
        $row = $this->getAdapter()->query("SELECT * FROM tmw_wire_current_state WHERE id = 1")->fetch();
        
        if(!$row) {
            throw new Exception('Could not load current game status data from database');
        } else {
            return $row;
        }	
    }
    
    // get the Current Player
    public function getCurrentPlayer() {
       
        $row = $this->getAdapter()->query("SELECT playerId FROM tmw_wire_current_state WHERE id = 1;")->fetch();
        
        if(!$row) {
            throw new Exception('Could not load Current Player from database');
        } else {
            return $row;
        }	
    }
    
    // set the Current Player
    public function setCurrentPlayer($currentPlayerId, $gameStatus, $gameProgress, $playerPhoto) {
        
        $data = array(
            'playerId'      => $currentPlayerId,
            'game_status'   => $gameStatus,
            'game_progress' => $gameProgress,
            'player_photo'  => $playerPhoto
        );
        
        $where = array(
            'id = ?' => 1 
        );
        
        $this->update($data, $where);
    }
    
    // get the Current Progress
    public function getCurrentProgress() {
       
        $row = $this->getAdapter()->query("SELECT game_progress FROM tmw_wire_current_state WHERE id = 1;")->fetch();
        
        if(!$row) {
            throw new Exception('Could not load Current Progress from database');
        } else {
            return $row;
        }	
    }
    
    // get the Current Player Photo
    public function getCurrentPhoto() {
       
        $row = $this->getAdapter()->query("SELECT player_photo FROM tmw_wire_current_state WHERE id = 1;")->fetch();
        
        if(!$row) {
            throw new Exception('Could not load Current Player Photo from database');
        } else {
            return $row;
        }	
    }
    
    // get the Game State Photo
    public function getGameState() {
       
        $row = $this->getAdapter()->query("SELECT game_status FROM tmw_wire_current_state WHERE id = 1;")->fetch();
        
        if(!$row) {
            throw new Exception('Could not load Game Status from database');
        } else {
            return $row;
        }	
    }
    
}
