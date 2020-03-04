<?php
/*
 * @version 2.0.0
 * @copyright Copyright (C) 2016 ArtMedia. All rights reserved.
 * @license OSCL, see http://www.oxwallplus.com/oscl
 * @website http://artmedia.biz.pl
 * @author Arkadiusz Tobiasz
 * @email kontakt@artmedia.biz.pl
 */

class SECURITY_CLASS_RecaptchaValidator extends FORM_Validator
{
    private $secret;
    
    public function __construct() {
        $errorMessage = OW::getLanguage()->text('base', 'form_validator_captcha_error_message');

        if(empty($errorMessage)) {
            $errorMessage = 'Captcha Validator Error!';
        }
        
        $settings = SECURITY_BOL_Service::getInstance()->getRecaptchaSettings();
        $this->secret = $settings['secretKey'];
        
        $this->setErrorMessage($errorMessage);
    }

    public function isValid($value) {
        $value = OW::getRequest()->getPost('g-recaptcha-response');
        if($value) {
            $recaptcha = new \ReCaptcha\ReCaptcha($this->secret);
            $response = $recaptcha->verify($value, $_SERVER['REMOTE_ADDR']);
            if($response->isSuccess()) {
                return true;
            }
        }
        return false;
    }
    
    public function getJsValidator() {
        return "{
            validate: function(value) {
                var recaptchaResponse = grecaptcha.getResponse();
                if(recaptchaResponse == '') {
                    throw " . json_encode($this->getError()) . ";
                }
            },
            getErrorMessage: function() {
                return " . json_encode($this->getError()) . ";
            }
        }";
    }
}

