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

class SECURITY_CTRL_Securimage extends OW_ActionController
{
    const CAPTCHA_WIDTH = 200;
    const CAPTCHA_HEIGHT = 68;
    
    private $key;
    private $securimage;
    
    public function __construct() {
        parent::__construct();

        $this->key = SECURITY_BOL_Service::KEY;
        $this->securimage = new Securimage();
    }

    public function index($params = array()) {        
        
        $settings = SECURITY_BOL_Service::getInstance()->getSecurimageSettings();
        $this->securimage->image_width = isset($params['width']) ? (int) $params['width'] : self::CAPTCHA_WIDTH;
        $this->securimage->image_height = isset($params['height']) ? (int) $params['height'] : self::CAPTCHA_HEIGHT;
        $this->securimage->ttf_file = OW_DIR_LIB.'securimage/AHGBold.ttf';
        $this->securimage->perturbation = 0.45;
        $this->securimage->image_bg_color = new Securimage_Color(0xf6, 0xf6, 0xf6);
        $this->securimage->text_angle_minimum = -5;
        $this->securimage->text_angle_maximum = 5;
        $this->securimage->use_transparent_text = true;
        $this->securimage->text_transparency_percentage = 30; // 100 = completely transparent
        $this->securimage->num_lines = mt_rand($settings['captchaLines']['from'], $settings['captchaLines']['to']);;
        $this->securimage->line_color = new Securimage_Color("#7B92AA");
        $this->securimage->signature_color = new Securimage_Color("#7B92AA");
        $this->securimage->text_color = new Securimage_Color("#7B92AA");
        $this->securimage->code_length = mt_rand($settings['captchaLength']['from'], $settings['captchaLength']['to']);
        $this->securimage->captcha_type = $settings['captchaType'] < 3 ? $settings['captchaType'] : mt_rand(0,2);
        $this->securimage->image_signature = $settings['captchaSignature'];
	$this->securimage->signature_color = new Securimage_Color('#000000');
        
        exit($this->securimage->show());
    }

    public function ajax() {
        if(!OW::getRequest()->isAjax()) {
            throw new Redirect404Exception();
        }

        $code = OW::getRequest()->getPost('code');
        if(!$code) {
            $result = false;
        } else {
            $key = SECURITY_BOL_Service::SECURIMAGE_KEY;
            if(OW::getSession()->get($key)) {
                $result = true;
            } else {
                if($this->securimage->check($code)) {
                    $result = true;
                } else {
                    $result = false;
                }
            }
        }
        
        OW::getSession()->set($key, $result);
        exit(json_encode(array('result' => $result)));
    }
}