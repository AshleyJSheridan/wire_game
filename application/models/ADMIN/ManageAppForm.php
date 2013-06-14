<?php
class ADMIN_ManageAppForm extends Zend_Form
{
    private $_fieldsCount;
    private $_fieldTypes;
    
    public function init()
    {
        $this->setIsArray(true);
        
        if(!empty($this->_fieldsCount))
        {
            for($i = 0; $i < $this->_fieldsCount; $i++)
            {
                $fieldForm[$i] = New Zend_Form();

                $facebookCampaignName = $fieldForm[$i]->createElement('hidden', 'facebookCampaignName', array('isArray' => true));
                $facebookCampaignName->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');

                $elementDbId = $fieldForm[$i]->createElement('hidden', 'id', array('isArray' => true));
                $elementDbId->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');

                $elementType = $fieldForm[$i]->createElement('hidden', 'elementType', array('isArray' => true, 'attribs' => array('message' => 'Please select a form element type')));
                $elementType->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');

                $elementName = $fieldForm[$i]->createElement('text', 'elementName', array('isArray' => true, 'attribs' => array('message' => 'Please add a name')));
                $elementName->setLabel('Name:');
                $elementName->addDecorator(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formElementColumn'));

                $elementLabel = $fieldForm[$i]->createElement('text', 'elementLabel', array('isArray' => true, 'attribs' => array('message' => 'Please add a label')));
                $elementLabel->setLabel('Label:');
                $elementLabel->addDecorator(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formElementColumn'));

                $elementValue = $fieldForm[$i]->createElement('text', 'elementValue', array('isArray' => true, 'attribs' => array('message' => 'Please add a default value')));
                $elementValue->setLabel('Default Value:');
                $elementValue->addDecorator(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formElementColumn'));

                $elementError = $fieldForm[$i]->createElement('text', 'elementError', array('isArray' => true, 'attribs' => array('message' => 'Please add an error message')));
                $elementError->setLabel('Error Message:');
                $elementError->addDecorator(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formElementColumn'));
                
                $elementVisibility = $fieldForm[$i]->createElement('select', 'elementVisibility', array('multiple' => false, 'isArray' => true, 'value' => false, 'attribs' => array('message' => 'Please select visibility option')));
                $elementVisibility->setLabel('Display:');
                $elementVisibility->addMultiOptions(array(true => 'yes', false => 'no'));
                $elementVisibility->addDecorator(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formElementColumn'));

                $elementRequired = $fieldForm[$i]->createElement('select', 'elementRequired', array('multiple' => false, 'isArray' => true, 'value' => false, 'attribs' => array('message' => 'Please select if field is required')));
                $elementRequired->setLabel('Required:');
                $elementRequired->addMultiOptions(array(true => 'yes', false => 'no'));
                $elementRequired->addDecorator(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formElementColumn'));

                $elementOrder = $fieldForm[$i]->createElement('text', 'elementOrder', array('isArray' => true,'attribs' => array('message' => 'Please select field element order')));
                $elementOrder->setLabel('Order:');
                $elementOrder->addDecorator(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formElementColumn'));

                $elementExtras = $fieldForm[$i]->createElement('hidden', 'elementExtras', array('isArray' => true, 'attribs' => array('message' => 'Please add some select options')));
                $elementExtras->setLabel('Available Options:');
                $elementExtras->addDecorator(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'formElementColumn radioOptions'));

                $fieldForm[$i]->addElement($facebookCampaignName)
                            ->addElement($elementDbId)
                            ->addElement($elementType)
                            ->addElement($elementName)                        
                            ->addElement($elementLabel)
                            ->addElement($elementValue)
                            ->addElement($elementError)
                            ->addElement($elementVisibility)
                            ->addElement($elementRequired)
                            ->addElement($elementOrder)
                            ->addElement($elementExtras);

                $fieldForm[$i]->setDecorators(array(
                    'FormElements',
                    array(
                        array('data' => 'HtmlTag'),
                        array('tag' => 'div', 'class' => 'formElementRow formElementRow'.$i.' formElementRow'.$this->_fieldTypes[$i])
                    )
                ));

                $this->addSubForm($fieldForm[$i], 'fieldForm'.$i);
            }

            $isAjax = $this->createElement('hidden', 'isAjax', array('value' => false));
            $isAjax->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');
            $formName = $this->createElement('hidden', 'formName', array('value' => 'appFields'));
            $formName->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');

            $submitFields = $this->createElement('submit', 'submitFields');    
            $submitFields->setLabel('submit')->setIgnore(true)->removeDecorator('DtDdWrapper')->removeDecorator('HtmlTag')->removeDecorator('Label');

            $this->addElements(array(
                            $isAjax,
                            $formName,
                            $submitFields
            ));
        }
    }
    
    public function setFieldsCount($fieldsCount) {
        $this->_fieldsCount = $fieldsCount;
    }

    public function getFieldsCount() {
        return $this->_fieldsCount;
    }
    
    public function setFieldTypes($fieldTypes) {
        $this->_fieldTypes = $fieldTypes;
        $this->init();
    }

    public function getFieldTypes() {
        return $this->_fieldTypes;
    }
}
?>