<?php
/*
 * @version 2.0.0
 * @copyright Copyright (C) 2016 ArtMedia. All rights reserved.
 * @license OSCL, see http://www.oxwallplus.com/oscl
 * @website http://artmedia.biz.pl
 * @author Arkadiusz Tobiasz
 * @email kontakt@artmedia.biz.pl
 */
require_once OW_DIR_LIB.'securimage/securimage.php';

class SECURITY_CLASS_SecurimageValidator extends FORM_Validator
{
    protected $objectName;
    
    public function __construct($objectName) {
        $errorMessage = OW::getLanguage()->text('base', 'form_validator_captcha_error_message');

        if(empty($errorMessage)) {
            $errorMessage = 'Captcha Validator Error!';
        }
        
        $this->objectName = $objectName;
        $this->setErrorMessage($errorMessage);
    }

    public function isValid($value) {
        // doesn't check empty values
        if((is_array($value) && sizeof($value) === 0) || $value === null || mb_strlen(trim($value)) === 0) {
            return true;
        }

        if(is_array($value)) {
            foreach($value as $val) {
                if(!$this->checkValue($val)) {
                    return false;
                }
            }
            return true;
        } else {
            return $this->checkValue($value);
        }
    }

    public function checkValue($value) {
        $this->securimage = new Securimage();
        $sessionKey = SECURITY_BOL_Service::SECURIMAGE_KEY;
        if(OW::getSession()->get($sessionKey)) {
            OW::getSession()->delete($sessionKey);
            return true;
        } else {
            if($this->securimage->check($value)) {
                return true;
            }
        }
        
        return false;
    }

    public function getJsValidator() {
        return "{
            validate: function(value) {
                if(!window." . $this->objectName . ".validate(value)) {
                    throw " . json_encode($this->getError()) . ";
                }
            },
            getErrorMessage: function() {
                return " . json_encode($this->getError()) . ";
            }
        }";
    }
}

