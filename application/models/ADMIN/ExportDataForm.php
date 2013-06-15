<?php
class ADMIN_ExportDataForm extends Zend_Form
{
    private $_campaignNames;
    
    public function init()
    {
        if(!empty($this->_campaignNames))
        {
            $campaignName = $this->createElement('select', 'campaignName', array('attribs' => array('message' => 'Please select a Facebook Campaign Name')));
            $campaignName->setLabel('Select Facebook Campaign Name:')->setRequired(true)->addMultiOptions($this->_campaignNames);

            $isAjax = $this->createElement('hidden', 'isAjax', array('value' => false));
            $isAjax->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
            $formName = $this->createElement('hidden', 'formName', array('value' => 'appExport'));
            $formName->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');

            $submitSettings = $this->createElement('submit', 'submitExport');    
            $submitSettings->setLabel('submit')->setIgnore(true)->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');

            $this->addElements(array(
                            $campaignName,
                            $isAjax,
                            $formName,
                            $submitSettings
            ));
        }
    }
    
    public function setCampaignNames($campaignNames) {
        $this->_campaignNames = $campaignNames;
        $this->init();
    }

    public function getCampaignNames() {
        return $this->_campaignNames;
    }
}
?>