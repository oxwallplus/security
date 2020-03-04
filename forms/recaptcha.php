<?php
/*
 * @version 2.0.0
 * @copyright Copyright (C) 2016 ArtMedia. All rights reserved.
 * @license OSCL, see http://www.oxwallplus.com/oscl
 * @website http://artmedia.biz.pl
 * @author Arkadiusz Tobiasz
 * @email kontakt@artmedia.biz.pl
 */

class SECURITY_FORM_Recaptcha extends Form
{    
    private $key;
    private $language;
    
    public function __construct() {
        $this->key = SECURITY_BOL_Service::KEY;
        parent::__construct('recaptcha');
        
        $this->language = OW::getLanguage();
        
        $siteKey = new ELEMENT_Text('siteKey');
        $siteKey->setHasInvitation($this->language->text($this->key, 'recaptcha_site_key'));
        $siteKey->setDescription(sprintf($this->language->text($this->key, 'recaptcha_desc'), 'https://www.google.com/recaptcha/admin'));
        $siteKey->setLabel($this->language->text($this->key, 'recaptcha_site_key'), array('class' => 'col-sm-4 col-form-label'));
        $this->addElement($siteKey);
        
        $secretKey = new ELEMENT_Text('secretKey');
        $secretKey->setHasInvitation($this->language->text($this->key, 'recaptcha_secret_key'));
        $secretKey->setLabel($this->language->text($this->key, 'recaptcha_secret_key'), array('class' => 'col-sm-4 col-form-label'));
        $this->addElement($secretKey);
        
        $submit = new ELEMENT_Button('submit');
        $submit->setValue($this->language->text('base', 'edit_button'));
        $this->addElement($submit);
    }
    
    public function processForm($data) {        
        unset($data['form_name']);
        unset($data['csrf_token']);
        
        $config = OW::getConfig();
        $config->saveConfig($this->key, 'recaptchaSettings', serialize($data));

        return array('status' => 'success', 'message' => $this->language->text('admin', 'main_settings_updated'));
        
    }
}

