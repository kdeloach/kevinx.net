<?php

class MasterController extends Controller
{    
	var $siteUrl;
 
    // Controls
	var $navigation;
    var $btnSearch;
    var $btnLogin;
    var $btnRegister;
	
	function onLoad()
	{
		parent::onLoad();
        
		$this->siteUrl = (string)$this->config->setting('siteUrl');
        
        $this->navigation = new NavigationUserControl();
        $this->btnSearch = new ButtonUserControl('Search');
        $this->btnLogin = new ButtonUserControl('Login');
        $this->btnLogin->cssClass = 'pill';
        $this->btnRegister = new ButtonUserControl('Register');
        $this->btnRegister->cssClass = 'pill';
	}
    
    /// 
    /// Common view args across all pages
    ///
    function masterViewArgs()
    {
        return array(
            'siteUrl' => $this->siteUrl,
            'navigation' => $this->navigation,
            'btnSearch' => $this->btnSearch,
            'btnLogin' => $this->btnLogin,
            'btnRegister' => $this->btnRegister
        );
    }
}