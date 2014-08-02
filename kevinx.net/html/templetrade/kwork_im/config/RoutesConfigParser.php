<?php

class RoutesConfigParser implements IConfigParser
{
    var $config;
    
    function __construct($config)
    {
        $this->config = $config;
    }
    
    function evaluate($section)
    {
        $this->config->routes = new RouteMap();
        
        foreach($section as $node)
        {
			$rcRoute = new ReflectionClass('Route');
			$attrs = $node->attributes();
            $pattern = null;
            
            if(isset($attrs['pattern']))
            {
                $pattern = trim((string)$attrs['pattern']);
            }
			
			$args = array();
            foreach($attrs as $key => $val)
            {
				$args[$key] = (string)$val;
            }
			
			$route = $rcRoute->newInstanceArgs(array($pattern, $args));
            $this->config->routes->add($route);
        }
    }
}