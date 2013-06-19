<?php
class ADMIN_ManageImagesForm extends Zend_Form
{
    public function init()
    {        
        $adImage = new Zend_Form_Element_File('playerImage', array(
                                        'size'              => '34',
                                        'maxFileSize'       => '3120000',
                                        'label'             => 'Ad Image',
                                        'validators'        => array(
                                                                    array('Count', true, array( 'min' => 0, 'max' => 1 )),  // ensure only 1 file
                                                                    array('Size', true, 3120000),                           // limit to 3 mb
                                                                    array('Extension', true, array( 'jpg,jpeg,png,gif' ))   // only JPEG, PNG, and GIFs
                                                               ),   
                                        'attribs'           => array('message' => 'Please upload the image for the Ad')
                                        ));
        
        $shareImage = new Zend_Form_Element_File('shareImage', array(
                                        'size'              => '34',
                                        'maxFileSize'       => '3120000',
                                        'label'             => 'Share Image Icon',
                                        'validators'        => array(
                                                                    array('Count', true, array( 'min' => 0, 'max' => 1 )),  // ensure only 1 file
                                                                    array('Size', true, 3120000),                           // limit to 3 mb
                                                                    array('Extension', true, array( 'jpg,jpeg,png,gif' ))   // only JPEG, PNG, and GIFs
                                                               ),   
                                        'attribs'           => array('message' => 'Please upload the image for the share icon')
                                        ));
        
        $isAjax = $this->createElement('hidden', 'isAjax', array('value' => false));
        $isAjax->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
        $formName = $this->createElement('hidden', 'formName', array('value' => 'appImages'));
        $formName->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
                
        $submitImages = $this->createElement('submit', 'submitImages');    
        $submitImages->setLabel('submit')->setIgnore(true)->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
        
        $this->addElements(array(
                        $adImage,
                        $shareImage,
                        $isAjax,
                        $formName,
                        $submitImages
        ));
    }
}
?>