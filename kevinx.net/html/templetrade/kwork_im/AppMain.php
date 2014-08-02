<?php

function _basic_autoload($className)
{
	$filename = "$className.php";
	if((@include $filename) !== false)
		return true;
	return false;
}
function _pear_autoload($className)
{
	$filename = str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
	if((include $filename) !== false)
		return true;
	return false;
}
//spl_autoload_register('_basic_autoload');
spl_autoload_register('_pear_autoload');

// ------------------------------------------

$work_path = dirname(__FILE__);
$include_paths = array(
    get_include_path(),
    $work_path,
    $work_path . DIRECTORY_SEPARATOR . 'config',
	$work_path . DIRECTORY_SEPARATOR . 'handlers',
	$work_path . DIRECTORY_SEPARATOR . 'helpers'
);
set_include_path(implode(PATH_SEPARATOR, $include_paths));

// ------------------------------------------

class AppMain
{
    public static function handleRequest()
    {      
        Twig_Autoloader::register();
        self::executeHandlers();
    }
	
	private static function executeHandlers()
	{
        $config = Config::instance();
		
		if($handlers = $config->section('handlers'))
		{
			$uri = $_SERVER['REQUEST_URI'];
		
			foreach($handlers as $handler)
			{
				$pattern = $handler->requestPattern();
				$regexPattern = self::regexPattern($pattern);
				
				if(preg_match($regexPattern, $uri))
				{
					$handler->handleRequest();
					break;
				}
			}
		}
	}
	
	///
	/// Converts the simple syntax used for handler patterns to regex.
	///
	private static function regexPattern($pattern)
	{
		$regexPattern = preg_quote($pattern, '/');
		$regexPattern = str_replace('\*', '.+', $regexPattern);
		$regexPattern = sprintf('/^%s$/', $regexPattern);
		return $regexPattern;
	}
}