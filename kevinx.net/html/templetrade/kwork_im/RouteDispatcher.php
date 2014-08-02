<?php

class RouteDispatcher
{
    function __construct()
    { 
    }
    
    function dispatch($route, $matches)
    {
        $className = $route->args['controller'];
        $methodName = $route->args['action'];
        $objController = null;
        
        try
        {
            $rc = new ReflectionClass($className);
            $objController = $rc->newInstance();
        }
        catch(ReflectionException $ex)
        {
			echo 'Could not load controller class.';
            //print_r($route);
			//debug_print_backtrace();
        }
        
        $objController->onLoad();
        
        try
        {
            $rm = new ReflectionMethod($className, $methodName);
            $orderedArgs = self::prepareActionArgs($matches, $rm->getParameters());
            $rm->invokeArgs($objController, $orderedArgs);
        }
        catch(ReflectionException $ex)
        {
			echo 'Could not load controller action.';
            //print_r($route);
			//debug_print_backtrace();
        }
    }
    
    ///
    ///  Puts arguments from route in the right order according to the method signature.
    ///
    static function prepareActionArgs($routeArgs, $rmParams)
    {
        $result = array();
        foreach($rmParams as $param)
        {
            $val = null;
            $key = strtolower($param->name);
            if(isset($routeArgs[$key]))
            {
                $val = $routeArgs[$key];
            }
            else
            {
                $val = $param->getDefaultValue();
            }
            $result[$key] = $val;
        }
        return $result;
    }
}