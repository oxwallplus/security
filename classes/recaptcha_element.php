<?php
/*
 * @version 2.0.0
 * @copyright Copyright (C) 2016 ArtMedia. All rights reserved.
 * @license OSCL, see http://www.oxwallplus.com/oscl
 * @website http://artmedia.biz.pl
 * @author Arkadiusz Tobiasz
 * @email kontakt@artmedia.biz.pl
 */

class SECURITY_CLASS_SecurimageElement extends ELEMENT_Captcha
{
    protected $key;
    protected $siteKey;
    protected $secret;
    public $objectName = null;

    /**
     * Constructor.
     *
     * @param string $name
     */
    public function __construct($name) {
        parent::__construct($name);
        
        $this->key = SECURITY_BOL_Service::KEY;
        $settings = SECURITY_BOL_Service::getInstance()->getRecaptchaSettings();
        $this->siteKey = $settings['siteKey'];
        $this->secret = $settings['secretKey'];
        
        $this->addValidator(new SECURITY_CLASS_RecaptchaValidator());
        $this->setLabel(OW::getLanguage()->text(SECURITY_BOL_Service::KEY, 'captcha_label'));
    }

    /**
     * @see FormElement::renderInput()
     *
     * @param array $params
     * @return string
     */
    public function renderInput($params = null)
    {
        parent::renderInput($params);

        return '<div class="row justify-content-md-center">
            <div class="col-md-auto g-recaptcha" data-sitekey="'.$this->siteKey.'"></div>
            <script type="text/javascript"
                    src="https://www.google.com/recaptcha/api.js">
            </script>
        </div>';
    }

}
