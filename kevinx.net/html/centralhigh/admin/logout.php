<?php

	// Admin - Logout Page
	// September 26, 2005 06:52:35 PM  - created

	// template header
	require 'include/admin-header.php';
	/////////////////////////////////////
	
	// Record action
	//accesslog('Logged Out');
	
	// Destroy user session
	$_SESSION = array();
	
	session_destroy();
	
	// Redirect to login form in a few seconds
	//header('Refresh: 1; login.php');
	
	// Redirect to indeb instantly
	header('Location: index.php');
	exit;
	
	// Confirm status
	//println(dialog('You Have Logged Out', SUCCESS, true));////println('<div id="success">You Have Logged Out</div>');
	
	/////////////////////////////////////////
	// template footer
	//require 'include/admin-footer.php';
	
?>