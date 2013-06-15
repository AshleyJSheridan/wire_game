<?php
/**
 * Admin
 * 
 * @package TMW WIRE GAME
 * @version 1.0.0
 */
class ADMIN_AppFields extends Zend_Db_Table
{
    protected $_name = 'tmw_wire_app_form_elements';
    
    // save the Form Elements data
    public function setData($data, $fieldsCount) {
        
        $parsedData = array();
        foreach ($data as $key => $fields)
        {
            for($i = 0; $i < $fieldsCount; $i++)
            {                   
                $parsedData[$i][$key] = (isset($fields[$i])) ?  $fields[$i] : 0;
            }
        }       
        
        foreach ($parsedData as $key => $data)
        {
            $where = array(
                'id = ?' => $data['id']
            );
            
            $insert_row = $this->update($data, $where);
        }
        
        return 1;        
    }
    
    // get the Form Elements data
    public function getData() {
       
        $row = $this->getAdapter()->query("SELECT * FROM tmw_wire_app_form_elements ORDER BY elementOrder;")->fetchAll();
        
        if(!$row) {
            throw new Exception('Could not load application fields from database');
        } else {
            return $row;
        }	
    }
    
}
