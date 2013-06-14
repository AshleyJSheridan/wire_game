<?php

class ADMIN_FormDefaults extends Zend_Form {
	
	public function __construct($action,$elementArray,$editid = false,$cloneparam = false,$formDetail = false,$parent,$multipart = false) {
		
		// create Zend form			
		$parent->setAction('/control/'.$action.'/');
		$parent->addElements($elementArray);
		$parent->setAttrib('id',$action);		
		
		if($multipart) {
			$parent->setAttrib('enctype', 'multipart/form-data');	
		}
		
		// if editing, load parameters, define clone as standard add action		
		if($editid ) {	
			$parent->populate($formDetail);
			if($cloneparam && $cloneparam == 'clone')
				$parent->setAction('/control/'.$action.'/');
			else
				$parent->setAction('/control/'.$action.'/'.$editid.'/');	
		}

		$this->setFormDecorators($parent);
	}
	
	public function setFormDecorators($parent) {
		
		// decorators
		$parent->clearDecorators();
		$parent->addDecorator('FormElements')
		 ->addDecorator('Form');
		
		$parent->setElementDecorators(array(
			array('ViewHelper'),
			array('HtmlTag'),
			array('Label', array('class' => 'input_label','requiredSuffix' => '<span class="form-required">*</span>', 'escape' => false)),
			array('Errors', array('class' => 'error_msg')),
			array('Description', array('class' => 'description'))
		));
		
		// take off button label
		$button = $parent->getElement('submit');  
		$button->removeDecorator('Label');
		
		// loop elements, check NotEmpty validators, and redefine
		$elements = $parent->getElements();
		foreach($elements as $singleElement) {
			
			if($singleElement->getValidator('NotEmpty')) {
				
				$notEmpty = new Zend_Validate_NotEmpty();
				$notEmpty->setMessage( 'Value is required and can\'t be empty' );
				$singleElement->addValidator($notEmpty);

			}

		}	
	}
		
}