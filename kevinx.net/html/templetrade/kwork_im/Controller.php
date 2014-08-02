<?php

abstract class Controller
{
	var $config;
	
	function __construct()
	{
		$this->config = Config::instance();
	}
    
    function onLoad()
    {
    }
    
    function render($templateName, $args=array())
    {
		$appPath = $this->config->setting('appPath');
		$templatePath =  $appPath . DIRECTORY_SEPARATOR . 'templates';
		$cachePath =  $appPath . DIRECTORY_SEPARATOR . 'templates/cache';
		
        $loader = new Twig_Loader_Filesystem($templatePath);
        $twig = new Twig_Environment($loader, array(
            //'cache' => $cachePath
        ));
		
        $templateFileName = $templateName;
        if(!StringHelper::endsWith($templateFileName, '.html'))
        {
            $templateFileName .= '.html';
        }
        $template = $twig->loadTemplate($templateFileName);
		
        $viewArgs = array();
        foreach($args as $key => $val)
        {
			if(is_object($val))
			{
				$rcProp = new ReflectionObject($val);
				if($rcProp->isSubClassOf('TemplateControl'))
				{
					$viewArgs[$key] = $val->render();
				}
			}
            else
            {
                $viewArgs[$key] = $val;
            }
        }
           
        return $template->render($viewArgs);
    }
}