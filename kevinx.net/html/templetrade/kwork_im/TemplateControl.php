<?php

abstract class TemplateControl
{
	var $config;
	
	function __construct()
	{
		$this->config = Config::instance();
	}
	
	function templateFileName()
	{
		$rc = new ReflectionObject($this);
		return $rc->getName() . '.html';
	}
    
    function onLoad()
    {
    }
    
    function render()
    {
        $this->onLoad();
		
		if($this->config == null)
		{
			debug_print_backtrace();
		}
		
		$appPath = $this->config->setting('appPath');
		$templatePath =  $appPath . DIRECTORY_SEPARATOR . 'templates';
		$cachePath =  $appPath . DIRECTORY_SEPARATOR . 'templates/cache';
		
        $loader = new Twig_Loader_Filesystem($templatePath);
        $twig = new Twig_Environment($loader, array(
            //'cache' => $cachePath
        ));
		
		if($this->templateFileName() == null)
			return;
		
        $template = $twig->loadTemplate($this->templateFileName());
		
        $vals = array();
        $rc = new ReflectionObject($this);
        foreach($rc->getProperties() as $prop)
        {
            $key = $prop->getName();
			$val = $this->{$key};
			
			if(is_object($val))
			{
				$rcProp = new ReflectionObject($val);
				if($rcProp->isSubClassOf('TemplateControl'))
				{
					$vals[$key] = $val->render();
				}
			}
			else
			{
				$vals[$key] = $val;
			}
        }
           
        return $template->render($vals);
    }
}