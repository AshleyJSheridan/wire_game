<?php

class ADMIN_Former
{
    private $table;
    private $key;
    public $mandatory;
    public $fields;
    public $values;
    public $incorrects;
    public $message="";
    private $loaded=false;


    public function __construct($fields, $mandatory = null,  $table=null, $primary=null)
    {
        foreach($fields as $key => $value)
            $this->fields[$key] = (object)$value;
        //$this->message = new stdClass();
        $this->table = $table;
        $this->key = $primary;
        $this->mandatory = $mandatory;
    }

    public function getControl($name)
    {
		
        if($this->loaded || isset($this->values)) return self::getControlValue($name);
		
		// validation fails
		if(is_array($this->incorrects) && in_array($name,$this->incorrects))
			$failString = '<p class="error_msg">Value is required and can\'t be empty</p>';
		else
			$failString = '';			
		
		// mandatory
		if(is_array($this->mandatory) && in_array($name,$this->mandatory))
			$reqString = '<div><span class="form-required">*</span></div>';
		else
			$reqString = '';
			
        if(!isset($this->fields[$name]->type)) return $reqString.'<input type="text" id="'.$name.'" name="'.$name.'" />'.$failString;
        
		switch($this->fields[$name]->type)
        {
            case 'hidden':
                return $reqString.'<input type="hidden" id="'.$name.'" name="'.$name.'" />'.$failString;
                break;
            case 'password':
                return $reqString.'<input type="password" id="'.$name.'" name="'.$name.'" />'.$failString;
                break;
            case 'text':
                return $reqString.'<input type="text" id="'.$name.'" name="'.$name.'" />'.$failString;
                break;
            case 'textarea':
                return $reqString.'<textarea id="'.$name.'" name="'.$name.'"></textarea>'.$failString;
                break;
            case 'radio':
                $options = '';
                foreach($this->fields[$name]->options as $key => $option)
                {
                    $id = $name."_".$key;
                    $checked = (isset($this->fields[$name]->checked) && $this->fields[$name]->checked == $key) ? " checked='true' " : "";
                    //$label = isset($this->fields[$name]->label) ? $this->fields[$name]->label : $name;
                    //if(isset($this->fields[$name]->assoc))
                        $options = $options . "<input type='radio' $checked name='{$name}' id='{$id}' value='{$key}' /> <label for='{$id}'>$option</label><br>";
                    //else
                    //    $options = $options . "<input type='radio' name='{$name}' id='{$id}' value='{$option}' /> <label for='{$id}'>$name</label>";
                    if(isset($this->fields[$name]->dilimeter))  $options = $options.$this->fields[$name]->dilimeter;
                }
                return $reqString.$options.$failString;
                break;
            case 'checkbox':
                $label = isset($this->fields[$name]->label) ? $this->fields[$name]->label : $name;
                $checked = isset($this->fields[$name]->checked) ? "checked='checked'" : "";
                return "$reqString<input $checked id='{$name}' name='{$name}' type='checkbox' value='1' /> <label for='{$name}'> $label </label>$failString";
                break;
            case 'select':
                $options = '';
                if(isset($this->fields[$name]->default)) $options = $options."<option value='-1'>{$this->fields[$name]->default}</option>";
                if(!isset($this->fields[$name]->range_from))
                {
                    foreach($this->fields[$name]->options as $key => $option)
                    {
                        //if(isset($this->fields[$name]->assoc))
                            $options = $options."<option value='{$key}'>$option</option>";
                        //else
                        //    $options = $options."<option value='{$option}'>$option</option>";
                    }
                    return "$reqString<select id='{$name}' name='{$name}' >	$options  	</select>$failString";
                }
                else
                {
                    for($i = $this->fields[$name]->range_from; $i <= $this->fields[$name]->range_to; $i++)
                        $options = $options."<option value='{$i}'>$i</option>";
                    return "$reqString<select id='{$name}' name='{$name}' >	$options  	</select>$failString";
                }
                break;
            case "file":
                return "$reqString<div class='form_file'> <input type='file' id='{$name}' name='{$name}' /> </div>$failString";
                break;
        }
    }

    public function getControlValue($name)
    {
		
		// validation fails
		if(is_array($this->incorrects) && in_array($name,$this->incorrects))
			$failString = '<p class="error_msg">Value is required and can\'t be empty</p>';
		else
			$failString = '';			
		
		// mandatory
		if(is_array($this->mandatory) && in_array($name,$this->mandatory))
			$reqString = '<div><span class="form-required">*</span></div>';
		else
			$reqString = '';
			
        if(!isset($this->fields[$name]->type)) return $reqString.'<input type="text" id="'.$name.'" value="'.$this->values[$name].'" name="'.$name.'" />'.$failString;
        switch($this->fields[$name]->type)
        {
            case 'hidden':
                return $reqString.'<input type="hidden" id="'.$name.'" name="'.$name.'" value="'.$this->values[$name].'" />'.$failString;
                break;
            case 'password':
                return $reqString.'<input type="password" id="'.$name.'" name="'.$name.'" />'.$failString;
                break;
            case 'text':
                return $reqString.'<input type="text" id="'.$name.'" value="'.((isset($this->values[$name])) ? $this->values[$name] : '' ).'" name="'.$name.'" />'.$failString;
                break;
            case 'textarea':
                return $reqString.'<textarea id="'.$name.'" name="'.$name.'">'.$this->values[$name].'</textarea>'.$failString;
                break;
            case 'radio':
			
                $options = '';
                foreach($this->fields[$name]->options as $key => $option)
                {
                    $id = $name."_".$key;
                    //$checked = "";
                    //if($this->values[$name] == $key) $checked=" checked='true' ";

                    //$checked = (isset($this->fields[$name]->checked) && $this->fields[$name]->checked == $key) ? " checked='true' " : "";

                    if ($name == 'pRoute')
                    {
                        if (is_null($this->values[$name]))
                        {
                            $checked = ($key == '0') ? " checked='true' " : "";
                        }
                        else
                        {
                            $checked = ($key == '1') ? " checked='true' " : "";                            
                        }    
                    }
                    else
                    {
                        $checked = ($this->values[$name] == $key) ? " checked='true' " : "";
                        if($this->values[$name]==null)
                            $checked = (isset($this->fields[$name]->checked) && $this->fields[$name]->checked == $key) ? " checked='true' " : "";
                    }

                    //$label = isset($this->fields[$name]->label) ? $this->fields[$name]->label : $name;
                    //if(isset($this->fields[$name]->assoc))
                        $options = $options . "<input type='radio' $checked name='{$name}' id='{$id}' value='{$key}' /> <label for='{$id}'>$option</label><br />";
                    //else
                    //    $options = $options . "<input type='radio' name='{$name}' id='{$id}' value='{$option}' /> <label for='{$id}'>$name</label>";
                    if(isset($this->fields[$name]->dilimeter))  $options = $options.$this->fields[$name]->dilimeter;
                }
                return $reqString.$options.$failString;
                break;
            case 'checkbox':
                $label = isset($this->fields[$name]->label) ? $this->fields[$name]->label : $name;
                $checked="";
                if($this->values[$name]) $checked=" checked='true' ";
                return "$reqString<input $checked id='{$name}' name='{$name}' type='checkbox' value='1' /> <label for='{$name}'> $label </label>$failString";
                break;
            case 'select':
                $options = '';
                if(isset($this->fields[$name]->default)) $options = $options."<option value='-1'>{$this->fields[$name]->default}</option>";
                if(!isset($this->fields[$name]->range_from))
                {
                    foreach($this->fields[$name]->options as $key => $option)
                    {
                        //$selected = "";
                        //if(isset($this->fields[$name]->assoc))
                            $selected = "";
                            if(@$this->values[$name] == $key) $selected=" selected='selected' ";
                            $options = $options."<option $selected value='{$key}'>$option</option>";
                        //else
                        //    $options = $options."<option value='{$option}'>$option</option>";
                    }
                    return "$reqString<select id='{$name}' name='{$name}' >	$options  	</select>$failString";
                }
                else
                {
                    for($i = $this->fields[$name]->range_from; $i <= $this->fields[$name]->range_to; $i++)
                    {
                        $selected = "";
                            if($this->values[$name] == $i) $selected=" selected='selected' ";
                        $options = $options."<option $selected value='{$i}'>$i</option>";
                    }
                    return "$reqString<select id='{$name}' name='{$name}' >	$options  	</select>$failString";
                }
                break;
            case "file":
                return "$reqString<div class='form_file'> <img src='{$this->fields[$name]->path}/{$this->values[$name]}' /> <input type='file' id='{$name}' name='{$name}' /> </div>$failString";
                break;
        }
    }

    public function submit($post,$insertData = true)
    {

        $this->incorrects = array();
		
        if($post){
            foreach ($post as $key => $value) {
                if (array_key_exists($key, $this->fields)) {
                    if ($value != - 1) {
                        if (empty($value)) {
                            // if(is_numeric($value)) {
                            $this->values[$key] = $value;
                            // }
                        } else {
                            $this->values[$key] = $value;
                        }
                    }
                }
            }
	        
			if($this->mandatory)
	        {
					
	             foreach($this->mandatory as $mandatory)
	             {
					 
					 $type = $this->fields[$mandatory]->type;

					 switch($type) {			 
					 	case 'text':
							if($post[$mandatory] == '')
								 $this->incorrects[] = $mandatory;
							break; 
						case 'textarea':
							if($post[$mandatory] == '')
								 $this->incorrects[] = $mandatory;
							break; 
						case 'select':
							if($post[$mandatory] == '' || $post[$mandatory] == 0)
								$this->incorrects[] = $mandatory;
						case 'radio':
							if(!isset($post[$mandatory]))
								$this->incorrects[] = $mandatory;	
					 }

	             }

	         }

        }

         if(isset($post['keystring']) && $post['keystring'] != $_SESSION['captcha_keystring']) $this->incorrects[] = 'captcha';
         if(isset($post['confirm']) && $post['password'] != $post['confirm']) $this->incorrects[] = $post['confirm']."-".$post['password'];

         if(count($this->incorrects) === 0)
         {
			 if($insertData) {
				 
				 $formtable = new formtable();
				 $formtable->_name = $this->table;
				 $formtable->_key = $this->key;
	
				 if(isset($this->values[$this->key]))
				 {
	
					 $update = array();
					 foreach($this->values as $key=>$value)
					 {
						 if(isset($this->fields[$key]->date))
						 {
							 if($value == 'dd.mm.yy') continue;
							 list($d,$m,$y)=explode(".",$value);
							 $update[$key] = "$y-$m-$d";
	
						 }
						 else if(isset($this->fields[$key]->type) && $this->fields[$key]->type=='checkbox' )
						 {
							 if(isset($this->values[$key]) && isset($_POST[$key]))
								 $update[$key] = 1;
							 else $update[$key] = null;
						 }
	
						 else if (isset($this->fields[$key]->type) && $this->fields[$key]->type=='password') $update[$key] = md5($value);
						 else $update[$key] = ($value=='null' || $value===null) ? null : str_replace("\\","",$value) ;
					 }
	
					 $result = $formtable->update($update,"$this->key={$this->values[(string)$this->key]}");;
					 
					 return $this->values[$this->key];
				 }
				 else
				 {
					 $row = $formtable->fetchNew();
					if($post) {
						 foreach($this->values as $key=>$value)
						 {
							 if(isset($this->fields[$key]->date))
							 {
								 list($d,$m,$y)=explode(".",$value);
								 $row->$key = "$y-$m-$d";
							 }
							 else if(isset($this->fields[$key]->type) && $this->fields[$key]->type=='checkbox' && isset($this->values[$key])) $row->$key = 1;
							 else if (isset($this->fields[$key]->type) && $this->fields[$key]->type=='password') $row->$key = md5($value);
							 else $row->$key = str_replace("\\","",$value);
						 }
				   }
					 $this->message = "Form succefull submitted";
					 return $row->save();
				 }
			 }
		 }
         else
         {
			 $this->message = "Please fill all required fields";
             return false;
         }
    }

    public function load($id)
    {
        $this->fields[] = $this->key;
        $this->values[$this->key] = $id;
        $formtable = new formtable();
        $formtable->_name = $this->table;
        $formtable->_key = $this->key;
        $data = $formtable->find($this->values[(string)$this->key])->current();

        foreach($this->fields as $key => $value)
        {
            try
            {
                @$this->values[$key] = $data->$key;
            }
            catch(Exception $e)
            {

            }
        }

        $this->loaded = true;
    }

    public function setValues($array)
    {
        foreach($array as $key=>$value)
        {
             $this->fields[] = $key;
             $this->values[$key] = $value;
        }
    }

}

class formtable extends Zend_Db_Table
{
    public $_name = '';
    public $_key = '';
}


?>