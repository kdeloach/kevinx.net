<?php

	// Admin - Header
	// September 26, 2005 04:56:23 PM  - removed output buffering; couldn't pass POST data with it on
	// September 27, 2005 12:17:33 AM  - satisfied with design at this stage, wrote navigation JS

	// debug
	//error_reporting(0);
	error_reporting(E_ALL);
	
	// G-Zip the output buffer
	//ini_set('zlib.output_compression', 'On'); 
	
	// Enable sessions
	session_start();

	// Start capturing output
	//ob_start('ob_gzhandler');
	ob_start();
	
	// Constants
	require 'include/constants.php';
	
	// Connect to the DB && yield $sql variable
	require ROOT.PATH_CMS.'include/dbconnect.php';
	require ROOT.PATH_CMS.'include/functions.php';
	
	// Start timer - get time in nanoseconds as float
	$GLOBALS['microtime_start'] = getmicrotime(true);

	// Declare version number
	define('VER', '1.2');
	
	////////////////////////////////////////////////
	
	$url = URL;
	
	// MENUS HERE
	
		// Manage Pages (Select Menu)
		$options = array(
			 'index.php' => '-- select page --'
			,'manage-news.php' => 'Announcements'
			,'edit-teachers.php' => 'Teachers'
			,'manage-depts.php' => 'Departments'
			,'manage-mail.php' => 'Mailing List'
			,'upload-bulletin.php' => 'Daily Bulletins'
			,'manage-content.php' => 'Content Manager'
			//,'edit-about.php' => 'About CHS'
			,'manage-activities.php' => 'Activities'
			,'manage-pages.php' => 'Pages'
			,'manage-gallery.php' => 'Media'
			//,'directory.php' => 'Student Directory'
		);
	
		// Submenus
		$pages = array(
			'news' => array(
				'post-news.php' => 'Post News',
				'manage-news.php' => 'Manage News',
				//'display-news.php' => 'Display Options',
				'http://centralhigh.net/' => 'View Site &#187;',
			),
			'depts' => array(
				'manage-depts.php' => 'Manage Departments',
				'edit-depts.php' => 'Edit Departments',
			),
			'users' => array(
				'manage-users.php' => 'User Accounts',
				'groups-users.php' => 'Manage Groups',
			),
			'db' => array(
				//'manage-db.php' => 'Backup Database',
				'http://centralhigh.net/private/pma/' => 'phpMyAdmin &#187;'
			),
			'mail' => array(
				'compose-mail.php' => 'Compose Mail',
				'groups-mail.php' => 'Groups',
				'manage-mail.php' => 'Manage',
				//'' => '',
			),
            'content' => array(
                'manage-content.php' => 'View top'
            ),
            'activities' => array(
                'categories-activities.php' => 'Categories',
                'manage-activities.php' => 'Manage',
                'edit-activities.php' => 'Edit'
            ),
            'pages' => array(
                'manage-pages.php' => 'Manage',
                'edit-pages.php'.(isset($_GET['dir'])?'?dir='.$_GET['dir']:'') => 'Compose'
            ),
            'gallery' => array(
                'categories-gallery.php' => 'Categories',
                'manage-gallery.php' => 'Manage'
            )
		);
		
		// CMS Pages (Left)
		$cms_pages = array(
			'edit-profile.php' => 'Edit Profile',
			'manage-users.php' => 'User Accounts',
			'settings.php' => 'Settings',
			'file-manager.php' => 'File Manager',
			'manage-db.php' => 'Database',
			//'edit-templates.php' => 'Templates',
			//'sandbox.php' => 'Sandbox',
			'access-log.php' => 'Access Log',
			//'logout.php' => 'Logout',
		);
		
	////////////////////////////////////////////////
	
	println('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">');
	println('<html lang="en" xml:lang="en-US">');
	println('<head>');
	println('<title>Cheesy Management System</title>');
	println('<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />');
	println('<base href="'. $url.PATH_CMS .'" />');
	println('<link rel="stylesheet" href="style.css" type="text/css" />');
	println('<link rel="stylesheet" href="js/rte/rte.css" type="text/css" />');
	println('<script type="text/javascript" src="js/send.js"></script>');
	//println('<script type="text/javascript" src="js/updatep.js"></script>');
	println('<script type="text/javascript" src="js/tables.js"></script>');
	println('<script type="text/javascript" src="js/rte/html2xhtml.js"></script>');
	println('<script type="text/javascript" src="js/rte/richtext.js"></script>');
	//println('<script type="text/javascript" src="js/rte/richtext_compressed.js"></script>');
	println('<script type="text/javascript" src="js/rte/init.js"></script>');
	println('</head>');
	println('<body onload="stripe(\'highlight\');">');

	////////////////////////////////////////////////
	
	println();
	println('<h1>Cheesy Management System (CMS)</h1>');
	println();
	
	if(islogged())
	{
		println('<div id="topbar">');
		println('<table cellpadding="0" cellspacing="0" border="0">');
		println('<tr>');
		println('<td>');
		println('Manage Page: ');
		println('<select name="sections" id="sections" style="font-size: small;" onchange="send();">');
		
		// Generate select menu
		///////
		// Extract filename
		$script = scriptname();
		
		// Isolates part of the scriptname; post-news.php -> news
		$start = strrpos($script, '-');
		$stop = strrpos($script, '.');
		$len = strlen($script);
		$script_tag = substr($script, $start+1, ($len-$start)-($len-$stop)-1);
		
		foreach($options as $file=>$title)
		{
			$start = strrpos($file, '-');
			$stop = strrpos($file, '.');
			$len = strlen($file);
			$tag = substr($file, $start+1, ($len-$start)-($len-$stop)-1);
			
			$selected = '';
			
			//if($script_tag == $tag)
			if($tag == $script_tag)
				$selected = ' selected="selected"';
				
			println('<option value="'. $file .'"'. $selected .'>'. $title .'</option>');
		}
		
		println('</select>');
		///////
		
		println('</td>');
		println('<td align="right">');
		println('You are logged in as <strong>'. get('name') .'</strong> (<a href="logout.php">Logout</a>)');
		println('</td>');
		println('</tr>');
		println('</table>');
		println('</div>');	
		println();
	}

	println('<table id="container" cellpadding="0" cellspacing="0" border="0" width="100%">');
	println('<tr>');
	println('<td id="menu" valign="top">');
	
  if(islogged())
	{
		// Print a Menu
		println('<div>');	
		println('<ul>');
		
		foreach($cms_pages as $file=>$title)
		{
			$active = '';
			
			$start = strrpos($file, '-');
			$stop = strrpos($file, '.');
			$len = strlen($file);
			$tag = substr($file, $start+1, ($len-$start)-($len-$stop)-1);
			
			//if($file == script_name())
			if($tag == $script_tag)
				$active = ' class="active"';
			
			println('<li><a href="'. $file .'"'. $active .'>'. $title .'</a></li>');
		}
		
		println('</ul>');
		println('</div>');
	}
	
	////////////////////////////////////////////////
	
	println('</td>');
	println('<td id="content_cell" valign="top" width="100%">');
	println('<div id="content_div">');
	
	////////////////////////////////////////////////
	// Some sections span multiple pages and require submenus
	
	if(islogged() && isset($pages[$script_tag]))
	{
		println('<div id="submenu">');
		println('<ul>');
		
		foreach($pages[$script_tag] as $file=>$title)
		{
			$active = '';
			
			if($file == $script)
				$active = ' class="active"';

			println('<li><a href="'. $file .'"'. $active .'>'. $title .'</a></li>');
		}
			
		println('</ul>');
		println('</div>');
		println();
	}
	
?>

