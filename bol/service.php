<?php
/*
 * @version 2.0.0
 * @copyright Copyright (C) 2016 ArtMedia. All rights reserved.
 * @license OSCL, see http://www.oxwallplus.com/oscl
 * @website http://artmedia.biz.pl
 * @author Arkadiusz Tobiasz
 * @email kontakt@artmedia.biz.pl
 */

class SECURITY_BOL_Service
{
    const KEY = 'security';
    const SECURIMAGE_KEY = 'securimage';
    
    const EVENT_CAPTCHA_COLLECTOR = 'security.captcha_collector';
    const EVENT_GET_CAPTCHA = 'security.get_captcha';
            
    private static $classInstance;
    
    public static function getInstance() {
        if(self::$classInstance === null) {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    private function __construct() {
    }
    
    public function getCaptchaServices() {
        $language = OW::getLanguage();
        $defaultServices = array(
            'SECURITY_CLASS_SecurimageElement' => 'Securimage',
            'SECURITY_CLASS_RecaptchaElement' => 'Google reCaptcha'
        );
        
        $eventManager = OW::getEventManager();
        $event = new SECURITY_CLASS_CaptchaCollector(self::EVENT_CAPTCHA_COLLECTOR);
        $eventManager->trigger($event);
        $defaultServices = array_merge($defaultServices, $event->getData());
        return $defaultServices;
    }
    
    public function getRecaptchaSettings() {
        $config = OW::getConfig();
        return unserialize($config->getValue(self::KEY, 'recaptchaSettings'));
    }
    
    public function getSecurimageSettings() {
        $config = OW::getConfig();
        return unserialize($config->getValue(self::KEY, 'securimageSettings'));
    }
}