<?php

	// Website Header
	// September 26, 2005 04:32:09 PM  - added output buffering

	// debug
	//error_reporting(0);
	error_reporting(E_ALL);
	
	// Constants
	require 'constants.php';
	
	// Connect to the DB && yield $sql variable
	require 'dbconnect.php';
	require 'functions.php';
	
	// Start timer - get time in nanoseconds as float
	$_GLOBALS['microtime_start'] = getmicrotime(true);
	
	/////////////////////////////////////////
	
	// page ID
	$_p = (!isset($_GET['p']) || empty($_GET['p']) ? 'root' : $_GET['p']);
	
	// echo $_SERVER['REQUEST_URI'];
	
	$url = URL;
	
	println('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">');
	println('<html lang="en" xml:lang="en-US">');
	println('<head>');
	println('<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />');
	println('<title>Central High School of Philadelphia</title>');
	println('<link rel="stylesheet" href="'. $url.PATH .'style.css" type="text/css" />	');
	println('</head>');
	println('<body>');
	println();
	println('<!-- Header -->');
	println('<div id="banner">');
	println('<a href="index.php"><img src="'. $url.PATH .'images/banner.gif" width="600" height="100" alt="" /></a>');
	println('</div>');
	println();
	println('<!-- Navigation -->');
	println('<div id="nav">');
	println('<ul>');
					
	////////////////////////////////////////////////////////
	// Navigation Menu
	// links have to be added manually, couldn't think of a better way, sorry!
	
	println('<li><a href="'. $url.PATH .'" title="Home">Home</a></li>');
	println('<li><a href="'. $url.PATH .'about/" title="About">About</a></li>');
	println('<li><a href="'. $url.PATH .'departments/" title="Departments">Departments</a></li>');
	println('<li><a href="'. $url.PATH .'activities/" title="Activities">Activities</a></li>');
	println('<li><a href="'. $url.PATH .'publications/" title="Publications">Publications</a></li>');
	println('<li><a href="'. $url.PATH .'multimedia/" title="Multimedia">Multimedia</a></li>');
	println('<li><a href="'. $url.PATH .'resources/" title="Resources">Resources</a></li>');
		
	//////////////////////////////////////////////////////////////
	
	println('</ul>');
	println('</div>');
	println();
	
	include 'bcal.php';
	
	println('<!-- Content -->');
	println('<div id="content">');
		
	/////////////////////////////////////////
	
	// start capture
	ob_start();
	
	/////////////////
	
?>
