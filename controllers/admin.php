<?php
/*
 * @version 2.0.0
 * @copyright Copyright (C) 2016 ArtMedia. All rights reserved.
 * @license OSCL, see http://www.oxwallplus.com/oscl
 * @website http://artmedia.biz.pl
 * @author Arkadiusz Tobiasz
 * @email kontakt@artmedia.biz.pl
 */

class SECURITY_CTRL_Admin extends OW_BackendController
{
    private $key;
    private $language;
    
    public function __construct() {
        parent::__construct();

        $this->key = SECURITY_BOL_Service::KEY;
        $this->language = OW::getLanguage();
        
        $this->setPageTitle($this->language->text($this->key, 'admin_title').' | '.$this->getPageTitle());
        $this->setPageHeading($this->language->text($this->key, 'admin_title'));
        $this->setPageHeadingIconClass('ow_ic_star');

        $this->assign('key', $this->key);
    }

    public function index() {        
        $security = new SECURITY_FORM_Settings();
        $recaptcha = new SECURITY_FORM_Recaptcha();
        $securimage = new SECURITY_FORM_Securimage();
        
        if(OW::getRequest()->isPost()) {
            $ajax = OW::getRequest()->isAjax();
            $status = 'error';
            $data = OW::getRequest()->getPost();
            
            if($data['form_name'] == 'recaptcha') {
                $form = $recaptcha;
            } elseif($data['form_name'] == 'securimage') {
                $form = $securimage;
            } else {
                $form = $security;
            }
            
            if($form->isValid($data)) {
                $data = $form->getValues();

                $result = $form->processForm($data);
                $message = $result['message'];
                $status = $result['status'];
            } else {
                $message = $this->language->text('admin', 'settings_submit_error_message');
                foreach($form->getErrors() as $error) {
                    if($error) {
                        $message .= '<br />'.(is_array($error) ? implode('<br />', $error) : $error);
                    }
                }
            }
            
            if($ajax) {
                exit(json_encode(array('status' => $status, 'message' => $message)));
            } else {
                if($status == 'error') {
                    OW::getFeedback()->error($message);
                } else {
                    OW::getFeedback()->info($message);
                }
            }
        }
        
        $settings = OW::getConfig()->getValues($this->key);
        $security->bind($settings);
        $security->setAjax(true);
        $security->setAction(OW::getRouter()->urlForRoute($this->key.'.admin'));
        $security->setAjaxResetOnSuccess(false);
        $security->bindJsFunction(Form::BIND_SUCCESS, "function(data){ if(data.status == 'success') { OW.info(data.message); } else { OW.error(data.message); } }");
        
        $this->addForm($security);
        if($data = unserialize($settings['recaptchaSettings'])) {
            $recaptcha->bind($data);
        }
        $recaptcha->setAjax(true);
        $recaptcha->setAction(OW::getRouter()->urlForRoute($this->key.'.admin'));
        $recaptcha->setAjaxResetOnSuccess(false);
        $recaptcha->bindJsFunction(Form::BIND_SUCCESS, "function(data){ if(data.status == 'success') { OW.info(data.message); } else { OW.error(data.message); } }");
        
        $this->addForm($recaptcha);
        
        if($data = unserialize($settings['securimageSettings'])) {
            $securimage->bind($data);
        }
        $securimage->setAjax(true);
        $securimage->setAction(OW::getRouter()->urlForRoute($this->key.'.admin'));
        $securimage->setAjaxResetOnSuccess(false);
        $securimage->bindJsFunction(Form::BIND_SUCCESS, "function(data){ if(data.status == 'success') { OW.info(data.message); } else { OW.error(data.message); } }");
        
        $this->addForm($securimage);
    }

    
}