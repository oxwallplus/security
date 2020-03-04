<?php
/*
 * @version 2.0.0
 * @copyright Copyright (C) 2016 ArtMedia. All rights reserved.
 * @license OSCL, see http://www.oxwallplus.com/oscl
 * @website http://artmedia.biz.pl
 * @author Arkadiusz Tobiasz
 * @email kontakt@artmedia.biz.pl
 */

class SECURITY_FORM_Settings extends Form
{    
    private $key;
    private $language;
    
    public function __construct() {
        $this->key = SECURITY_BOL_Service::KEY;
        parent::__construct('settings');
        
        $this->language = OW::getLanguage();
        
        $enableCaptcha = new ELEMENT_Checkbox('enableCaptcha');
        $enableCaptcha->setToggle(true);
        $enableCaptcha->addAttributes(array(
            'data-off' => $this->language->text('base', 'no'),
            'data-on'=> $this->language->text('base', 'yes')
        ));
        $enableCaptcha->setLabel($this->language->text($this->key, 'enable_captcha'), array('class' => 'col-sm-4 col-form-label'));
        $this->addElement($enableCaptcha);
        
        $captchaType = new ELEMENT_Select('captchaType');
        $captchaType->setOptions(SECURITY_BOL_Service::getInstance()->getCaptchaServices());
        $captchaType->setRequired();
        $captchaType->setLabel($this->language->text($this->key, 'captcha_service'), array('class' => 'col-sm-4 col-form-label'));
        $this->addElement($captchaType);
        
        $submit = new ELEMENT_Button('submit');
        $submit->setValue($this->language->text('base', 'edit_button'));
        $this->addElement($submit);
    }
    
    public function processForm($data) {        
        unset($data['form_name']);
        unset($data['csrf_token']);
        
        $config = OW::getConfig();
        
        foreach($data as $name => $value) {
            if(is_int($value)) {
                $value = (int)$value;
            }
            $config->saveConfig($this->key, $name, $value);
        }
        return array('status' => 'success', 'message' => $this->language->text('admin', 'main_settings_updated'));
        
    }
}

