<?php

	// Admin - Index Page
	// September 26, 2005 04:43:36 PM - created
	// October 14, 2005 06:33:30 AM - added default_page code
	// October 14, 2005 07:54:38 PM - goes to custom page when logged in now

	// template header
	require 'include/admin-header.php';
	
	// Verify logged in
	require 'include/auth.php';
	/////////////////////////////////////
	
	// The following ASSUMES you are logged already
	
	$id = getid();
	
	$res = $sql->query("SELECT default_page FROM users WHERE id='$id'");
	$tmp = $sql->fetch_assoc($res);
	
	$page = $tmp['default_page'];
	
	if(isset($_GET['ref']) && !empty($_GET['ref']))
		$page = urlencode($_GET['ref']);
	
	header('Location: '. $page);
	exit;
	
	//header('Location: post-news.php');

	/////////////////////////////////////////
	// template footer
	//require 'include/admin-footer.php';
	
?>
