<?php
class ADMIN_ManageAppSettingsForm extends Zend_Form
{
    public function init()
    {
        $campaignName = $this->createElement('text', 'campaignName', array('attribs' => array('message' => 'Please enter a Name for this campaign')));
        $campaignName->setLabel('Facebook Campaign Name:')->setRequired(true);
        
        $title = $this->createElement('text', 'title', array('attribs' => array('message' => 'Please enter a Title for this campaign')));
        $title->setLabel('Facebook Campaign Title:')->setRequired(true);
        
        $facebookAppUrl = $this->createElement('text','facebookAppUrl', array('attribs' => array('message' => 'Please enter a Facebook Page URL for this campaign')));
        $facebookAppUrl->setLabel('Facebook Page URL:')->setRequired(true);
        
        $appId = $this->createElement('text', 'appId', array('attribs' => array('message' => 'Please enter a Competition ID/API Key for this campaign')));
        $appId->setLabel('Competition ID/API Key:')->setRequired(true);
        
        $secret = $this->createElement('text', 'secret', array('attribs' => array('message' => 'Please enter a Competition Secret Key for this campaign')));
        $secret->setLabel('Competition Secret:')->setRequired(true);
        
        $summary = $this->createElement('text', 'summary', array('attribs' => array('message' => 'Please enter a Competition Share Text for this campaign')));
        $summary->setLabel('Competition Share Text:')->setRequired(true);
        
        $gaId = $this->createElement('text', 'gaId', array('attribs' => array('message' => 'Please enter a Google Analytics ID for this campaign')));
        $gaId->setLabel('Google Analytics ID:');
        
        $isAjax = $this->createElement('hidden', 'isAjax', array('value' => false));
        $isAjax->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
        $formName = $this->createElement('hidden', 'formName', array('value' => 'appSettings'));
        $formName->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
                
        $submitSettings = $this->createElement('submit', 'submitSettings');    
        $submitSettings->setLabel('submit')->setIgnore(true)->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
                
        $this->addElements(array(
                        $campaignName,
                        $title,
                        $facebookAppUrl,
                        $facebookAppUrl,
                        $appId,
                        $secret,
                        $summary,
                        $gaId,
                        $isAjax,
                        $formName,
                        $submitSettings
        ));
    }
}
?>