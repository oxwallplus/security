<?php
/*
 * @version 2.0.0
 * @copyright Copyright (C) 2016 ArtMedia. All rights reserved.
 * @license OSCL, see http://www.oxwallplus.com/oscl
 * @website http://artmedia.biz.pl
 * @author Arkadiusz Tobiasz
 * @email kontakt@artmedia.biz.pl
 */

class SECURITY_FORM_Securimage extends Form
{    
    private $key;
    private $language;
    
    public function __construct() {
        $this->key = SECURITY_BOL_Service::KEY;
        parent::__construct('securimage');
        
        $this->language = OW::getLanguage();
        
        $captchaType = new ELEMENT_Select('captchaType');
        $captchaType->setOptions(array(
            '0' => $this->language->text($this->key, 'string_captcha'),
            '1' => $this->language->text($this->key, 'math_captcha'),
            '2' => $this->language->text($this->key, 'word_captcha'),
            '3' => $this->language->text($this->key, 'random_captcha'),
        ));
        $captchaType->setLabel($this->language->text($this->key, 'captcha_type'), array('class' => 'col-sm-4 col-form-label'));
        $this->addElement($captchaType);
        
        $options = array();
        for($i = 4; $i <= 10; $i++) {
            $options[$i] = $i;
        }
        
        $captchaLength = new ELEMENT_Range('captchaLength');
        $captchaLength->setMin(3);
        $captchaLength->setMax(10);
        $captchaLength->setLabel($this->language->text($this->key, 'captcha_length'), array('class' => 'col-sm-4 col-form-label'));
        $this->addElement($captchaLength);
        
        $captchaLines = new ELEMENT_Range('captchaLines');
        $captchaLines->setMin(0);
        $captchaLines->setMax(10);
        $captchaLines->setLabel($this->language->text($this->key, 'captcha_lines'), array('class' => 'col-sm-4 col-form-label'));
        $this->addElement($captchaLines);
        
        $captchaSignature = new ELEMENT_Text('captchaSignature');
        $captchaSignature->setHasInvitation($this->language->text($this->key, 'captcha_signature'));
        $captchaSignature->setLabel($this->language->text($this->key, 'captcha_signature'), array('class' => 'col-sm-4 col-form-label'));
        $this->addElement($captchaSignature);
        
        $submit = new ELEMENT_Button('submit');
        $submit->setValue($this->language->text('base', 'edit_button'));
        $this->addElement($submit);
    }
    
    public function processForm($data) {        
        unset($data['form_name']);
        unset($data['csrf_token']);
        
        $config = OW::getConfig();
        $config->saveConfig($this->key, 'securimageSettings', serialize($data));

        return array('status' => 'success', 'message' => $this->language->text('admin', 'main_settings_updated'));
        
    }
}

