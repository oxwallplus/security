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
    public $objectName = null;

    /**
     * Constructor.
     *
     * @param string $name
     */
    public function __construct($name) {
        parent::__construct($name);
        
        $this->key = SECURITY_BOL_Service::KEY;

        $this->addAttribute('type', 'text');
        $this->objectName = $this->getName().'_'.$this->getId();
        $this->setRequired();

        $this->addValidator(new SECURITY_CLASS_SecurimageValidator($this->objectName));
        
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

        if($this->value !== null) {
            $this->addAttribute('value', str_replace('"', '&quot;', $this->value));
        }

        OW::getDocument()->addScript(OW::getPluginManager()->getPlugin($this->key)->getStaticJsUrl().'securimage.js');

        $image = OW::getRouter()->urlFor('SECURITY_CTRL_Securimage', 'index');
        $options = array(
            'captcha' => $image,
            'ajax' => OW::getRouter()->urlFor('SECURITY_CTRL_Securimage', 'ajax'),
            'refreshId' => $this->objectName.'-refresh',
            'captchaId' => $this->objectName.'-captcha'
        );
        $code = '
            window.'.$this->objectName.' = new Securimage('.json_encode($options).');
            window.'.$this->objectName.'.listen();
        ';

        OW::getDocument()->addOnloadScript($code);
       
        return '<div class="mb-3" id="'.$this->objectName.'">
            <div class="row justify-content-md-center">
                <div class="col-md-auto">
                    <img src="'.$image.'" id="'.$this->objectName.'-captcha">
                    <i class="fa fa-sync-alt" id="'.$this->objectName.'-refresh" style="cursor:pointer;"></i>
                </div>
            </div>
            <div class="row justify-content-md-center" id="'.$this->objectName.'-input">
                <div class="col-md-auto">' . UTIL_HtmlTag::generateTag('input', $this->attributes) . '</div>
            </div>
        </div>
        ';
    }

}
