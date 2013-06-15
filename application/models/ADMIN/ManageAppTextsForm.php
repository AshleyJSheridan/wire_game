<?php
class ADMIN_ManageAppTextsForm extends Zend_Form
{
    public function init()
    {
        $campaignName = $this->createElement('hidden', 'campaignName');
        $campaignName->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
        
        $headerText1 = $this->createElement('textarea', 'headerText1', array('attribs' => array('message' => 'Please enter copy for the thick header text', 'cols' => 35, 'maxLength' => '255', 'rows' => 5)));
        $headerText1->setLabel('The copy of the thick header text:');
        
        $headerText2 = $this->createElement('textarea', 'headerText2', array('attribs' => array('message' => 'Please enter copy for the bold header text', 'cols' => 35, 'maxLength' => '255', 'rows' => 5)));
        $headerText2->setLabel('The copy of the bold header text:');
        
        $introText = $this->createElement('textarea', 'introText', array('attribs' => array('message' => 'Please enter copy for the intro text paragraph', 'cols' => 35, 'rows' => 5)));
        $introText->setLabel('The copy of the intro text paragraph:');
        
        $introVideo = $this->createElement('text', 'introVideo', array('attribs' => array('message' => 'Please enter YouTube Video ID')));
        $introVideo->setLabel('The YouTube Video ID:');
        
        $displayImage = $this->createElement('checkbox', 'displayImage', array('attribs' => array('message' => 'Please select Ad Image is displayed')));
        $displayImage->setLabel('Display Ad Image:');
        
        $thankText = $this->createElement('textarea', 'thankText', array('attribs' => array('message' => 'Please enter copy for the thank page text paragraph', 'cols' => 35, 'rows' => 5)));
        $thankText->setLabel('The copy of the thank page text paragraph:');
        
        $tncText = $this->createElement('text', 'tncText', array('attribs' => array('message' => 'Please enter a URL for the T&C')));
        $tncText->setLabel('The URL of the T&C page:');
        
        $policyText = $this->createElement('text', 'policyText', array('attribs' => array('message' => 'Please enter URL for the policy')));
        $policyText->setLabel('The URL of the Policy page:');
        
        $isAjax = $this->createElement('hidden', 'isAjax', array('value' => false));
        $isAjax->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
        $formName = $this->createElement('hidden', 'formName', array('value' => 'appTexts'));
        $formName->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
                
        $submitTexts = $this->createElement('submit', 'submitTexts');    
        $submitTexts->setLabel('submit')->setIgnore(true)->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
        
        $this->addElements(array(
                        $campaignName,
                        $headerText1,
                        $headerText2,
                        $introText,
                        $thankText,
                        $tncText,
                        $policyText,
                        $displayImage,
                        $introVideo,
                        $isAjax,
                        $formName,
                        $submitTexts
        ));
    }
}
?>