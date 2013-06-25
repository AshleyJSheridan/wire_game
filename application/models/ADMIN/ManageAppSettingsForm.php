<?php
class ADMIN_ManageAppSettingsForm extends Zend_Form
{
    public function init()
    {
        $campaignName = $this->createElement('text', 'campaignName', array('attribs' => array('message' => 'Please enter a Name for this campaign')));
        $campaignName->setLabel('Competition Campaign Name:')->setRequired(true);
        
        $title = $this->createElement('text', 'title', array('attribs' => array('message' => 'Please enter a Title for this campaign')));
        $title->setLabel('Competition Campaign Title:')->setRequired(true);
        
        $twitter_user = $this->createElement('text','twitter_user', array('attribs' => array('message' => 'Please enter the default Twiter User for this campaign')));
        $twitter_user->setLabel('Default Twitter User:')->setRequired(true);
        
        $consumer_key = $this->createElement('text','consumer_key', array('attribs' => array('message' => 'Please enter the Twitter consumer key for this campaign')));
        $consumer_key->setLabel('Twitter consumer key:')->setRequired(true);
        
        $consumer_secret = $this->createElement('text','consumer_secret', array('attribs' => array('message' => 'Please enter a Twitter consumer secret for this campaign')));
        $consumer_secret->setLabel('Twitter consumer secret:')->setRequired(true);
        
        $oauth_access_token = $this->createElement('text','oauth_access_token', array('attribs' => array('message' => 'Please enter a Twitter oauth_access_token for this campaign')));
        $oauth_access_token->setLabel('Twitter oauth_access_token:')->setRequired(true);
        
        $oauth_access_token_secret = $this->createElement('text','oauth_access_token_secret', array('attribs' => array('message' => 'Please enter a Twitter oauth_access_token_secret for this campaign')));
        $oauth_access_token_secret->setLabel('Twitter oauth_access_token_secret:')->setRequired(true);
        
        $facebookAppUrl = $this->createElement('text','facebookAppUrl', array('attribs' => array('message' => 'Please enter a Facebook Page URL for this campaign')));
        $facebookAppUrl->setLabel('Facebook Page URL:');
        
        $appId = $this->createElement('text', 'appId', array('attribs' => array('message' => 'Please enter a Facebook ID/API Key for this campaign')));
        $appId->setLabel('Facebook ID/API Key:');
        
        $secret = $this->createElement('text', 'secret', array('attribs' => array('message' => 'Please enter a Facebook Secret Key for this campaign')));
        $secret->setLabel('Facebook Secret:');
        
        $summary = $this->createElement('text', 'summary', array('attribs' => array('message' => 'Please enter a Facebook Share Text for this campaign')));
        $summary->setLabel('Facebook Share Text:');
        
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
                        $twitter_user,
                        $consumer_key,
                        $consumer_secret,
                        $oauth_access_token,
                        $oauth_access_token_secret,
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