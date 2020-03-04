<?php
/*
 * @version 2.0.0
 * @copyright Copyright (C) 2016 ArtMedia. All rights reserved.
 * @license OSCL, see http://www.oxwallplus.com/oscl
 * @website http://artmedia.biz.pl
 * @author Arkadiusz Tobiasz
 * @email kontakt@artmedia.biz.pl
 */

class SECURITY_CLASS_EventHandler
{
    private $key;
    private $config;
    private $eventManager;
    
    private static $classInstance;

    public static function getInstance() {
        if(self::$classInstance === null) {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }
    
    public function __construct() {
        $this->key = SECURITY_BOL_Service::KEY;
        $this->config = OW::getConfig();;
        $this->eventManager = OW::getEventManager();
    }
    
    public function genericInit() {
        $this->eventManager->bind(SECURITY_BOL_Service::EVENT_GET_CAPTCHA, [$this, 'getCaptcha']);
    }

    public function init() {
        $this->genericInit();
    }
    
    public function getCaptcha(OW_Event $event) {
        if($this->config->getValue($this->key, 'enableCaptcha')) {
            $class = $this->config->getValue($this->key, 'captchaType');
            if(class_exists($class)) {
                $element = new $class('captcha');
            } else {
                $element = new SECURITY_CLASS_SecurimageElement('captcha');
            }
        } else {
            $element = false;
        }
        $event->setData($element);
    }

}
