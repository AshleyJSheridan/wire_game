<?php
/**
 * Facebook Form Elements
 * 
 * @package MM FB APP
 * @version 2.0.0
 */
class MM_Facebookform extends Zend_Form
{
    public function __construct($appFormElements) {
        
        $fbFormArray                    = Array();
        $radioCheckBoxDecoratorArray    = Array();
        $fileDecoratorArray             = Array();
        
         $NotIdentical = new MM_NotIdentical();
        
            foreach($appFormElements as $formElement)
            {
                if($formElement['elementType'] == 'Text' && $formElement['elementVisibility'])
                {
                    $NotIdentical = new MM_NotIdentical(array('token' => $formElement['elementValue']));
                    
                    $fbFormArray[] = new Zend_Form_Element_Text($formElement['elementName'], array(
                                        'required'          => $formElement['elementRequired'],
					'label'             => $formElement['elementLabel'],
                                        'value'             => $formElement['elementValue'],
					'description'       => ($formElement['elementRequired']) ? "": "",
					'validators'        => ($formElement['elementRequired']) ? array('NotEmpty', $NotIdentical): array(),
					'attribs'           => array('message' => $formElement['elementError'])
                                        ));
                    
                    //$email->addValidator('UniqueEmail',false, array(new Model_User()));
                }
                else if($formElement['elementType'] == 'Email')
                {
                    $fbFormArray[] = new Zend_Form_Element_Text('fbEmail', array(
                                        'required'          => true,
					'label'             => $formElement['elementLabel'],
                                        'value'             => $formElement['elementValue'],
					'description'       => "",
					'validators'        => array('EmailAddress'),
					'attribs'           => array('message' => $formElement['elementError'])
                                        ));
                }
                else if($formElement['elementType'] == 'Textarea' && $formElement['elementVisibility'])
                {
                    $fbFormArray[] = new Zend_Form_Element_Textarea($formElement['elementName'], array(
                                        'class'             => 'textarea',
                                        'required'          => $formElement['elementRequired'],
					'label'             => $formElement['elementLabel'],
                                        'value'             => $formElement['elementValue'],
					'description'       => ($formElement['elementRequired']) ? "": "",
					'validators'        => ($formElement['elementRequired']) ? array('NotEmpty'): array(),
					'attribs'           => array(
                                                                    'cols'      => 35,
                                                                    'maxLength' => '140',
                                                                    'rows'      => 5,
                                                                    'message'   => $formElement['elementError']
                                                                )
                                        ));
                }
                else if($formElement['elementType'] == 'Checkbox' && $formElement['elementVisibility'])
                {
                    $radioCheckBoxDecoratorArray[] = $formElement['elementName'];
                    
                    $fbFormArray[] = new Zend_Form_Element_Checkbox($formElement['elementName'], array(
                                        'required'          => $formElement['elementRequired'],
					'description'       => ($formElement['elementRequired']) ? $formElement['elementLabel']."": $formElement['elementLabel'],
                                        'value'             => $formElement['elementValue'],
                                        'uncheckedValue'    => '',
					'validators'        => ($formElement['elementRequired']) ? array('NotEmpty'): array(),
					'attribs'           => array('message' => $formElement['elementError'])
                                        ));
                }
                else if($formElement['elementType'] == 'File' && $formElement['elementVisibility'])
                {
                    $fileDecoratorArray[] = 'fbimage';
                    
                    $fbFormArray[] = new Zend_Form_Element_File('fbimage', array(
                                        'required'          => $formElement['elementRequired'],
                                        'setAllowEmpty'     => false,
                                        'size'              => '44',
                                        'onchange'          => 'getElementById("FileField").value = getElementById("fbimage").value;',
                                        'maxFileSize'       => '3120000',
					'label'             => $formElement['elementLabel'],
					'description'       => ($formElement['elementRequired']) ? "": "",
                                        'validators'        => array(
                                                                    array('Count', true, array( 'min' => 1, 'max' => 1 )),  // ensure only 1 file
                                                                    array('Size', true, 3120000),                           // limit to 3 mb
                                                                    array('Extension', true, array( 'jpg,jpeg,png,gif' ))   // only JPEG, PNG, and GIFs
                                                               ),   
					'attribs'           => array('message' => $formElement['elementError'])
                                        ));
                }
                else if($formElement['elementType'] == 'Radio' && $formElement['elementVisibility'])
                {
                    $radioCheckBoxDecoratorArray[] = $formElement['elementName'];
                    
                    $radioOptions           = explode("|*|", $formElement['elementExtras']);
                    $parsedRadioOptions     = Array();
                    
                    foreach($radioOptions as $optionCopy)
                    {
                        $optionValue                        = preg_replace('/[^a-zA-Z0-9%\[\]\.\(\)%&-]/s', '_', strtolower($optionCopy));
                        $parsedRadioOptions[$optionValue]   = $optionCopy;
                    }
                    
                    $defaultRadioValue = array_keys($parsedRadioOptions);
                    
                    $fbFormArray[] = new Zend_Form_Element_Radio($formElement['elementName'], array(
                                        'required'          => $formElement['elementRequired'],
					'label'             => $formElement['elementLabel'],
                                        'multiOptions'      => $parsedRadioOptions,
					'description'       => ($formElement['elementRequired']) ? "": "",
					'separator'         => '',
					'attribs'           => array('message' => $formElement['elementError']),                        
                                        'value'             => $defaultRadioValue[0],
                                        ));
                }
            }
            
            $fbFormArray[] =    new Zend_Form_Element_Checkbox('fbTermsConditions',array(
					'required'          => true,
					'uncheckedValue'    => '',
					'attribs'           => array('message' => 'Please read and agree with the Terms & Conditions')
				));
            
            $fbFormArray[] =    new Zend_Form_Element_Submit('submitBtn', array(
					'label'         => 'Enter Now',
					'required'      => false
                                ));
            
            // create Zend form
            $this->addElements($fbFormArray);

            $this->setElementDecorators(array(
			'ViewHelper',
			array('Errors'),
			array('HtmlTag', array('tag' => 'dd')),
			array('Label', array('tag' => 'dt')),
			array('Description', array('class' => 'required'))
            ));
               
            $this->setElementDecorators(array(
                        'ViewHelper',
			array('Errors'),
			array('HtmlTag', array('tag' => 'dd')),
			array('Label', array('tag' => 'span')),
			array('Description', array('class' => 'description'))
                    ),
                    $radioCheckBoxDecoratorArray
            );
                
            $this->setElementDecorators(array(
                        'File',
                        'Errors',
			array('HtmlTag', array('tag' => 'dd')),
			array('Label', array('tag' => 'dt')),
			array('Description', array('class' => 'required'))
                    ),
                    $fileDecoratorArray
            );

            // take off button label
            $button = $this->getElement('submitBtn');
            $button->removeDecorator('Label');
	}
}