<?php

	// Admin - Login Page
	// September 26, 2005 11:47:09 PM - started
	// September 26, 2005 04:29:21 PM - finished first draft
	// September 30, 2005 02:48:08 PM - removed login confirmation message
	// October 01, 2005 02:05:18 PM  - added referrer code
	// October 13, 2005 03:51:11 AM - added http_auth login method!@!!#$!...took all night to do
	// October 14, 2005 07:53:20 PM - goes to custom page on login now
	// November 01, 2005 11:14:59 PM - removed http_auth ability...
	// November 24, 2005 04:37:59 PM - updated sql object style, put settings in session cookies

	// template header
	require 'include/admin-header.php';
	/////////////////////////////////////
		
	// If logged in already, get the hell out of here
	if(islogged())
	{
		header('Location: index.php');
		exit;
	}
	
	// debug - print POST data	
	//print_r($_POST);
		
	// Authenticate
	if(isset($_REQUEST['user']) && isset($_REQUEST['pass']))
	{
		// Escape dangerous characters
		$_user = addslashes($_POST['user']);
		$_pass = addslashes($_POST['pass']);
		$_ref = addslashes($_POST['ref']);
		
		// Get user data
		$sql->query("SELECT id,name,alias,pass FROM users WHERE name='$_user' OR alias='$_user'");
		$data = $sql->fetch_assoc();

		// compare hashes (debug)
		//println('<p>a: '.$data['pass'].'</p>');
		//println('<p>b: '.encrypt($pass, $user).'</p>');
				
		// If no user exists or passwords no match
		if(empty($data) || $data['pass'] != encrypt($_pass, $data['name']))
		{
			if(!isset($_ref))
				header('Location: login.php?attempt=true');
			else
				header('Location: login.php?ref='. $_ref .'&attempt=true');
			exit;
		}
		// Start session
		$_SESSION['islogged'] = true;
		$_SESSION['user_id'] = $data['id'];
		
		// Put settings into session cookies
		$sql->query("SELECT name,value FROM settings");
		
		while($sng = $sql->fetch_assoc())
		{
			$name = $sng['name'];
			$value = $sng['value'];
			
			$_SESSION[$name] = $value;
		}
		
		// Record to DB time of login
		$sql->query("UPDATE users SET last_login='". time() ."' WHERE id='$data[id]'");
		
		// Record action
		//accesslog('Logged In');
		
		// debug
		//println('logged in!');
		
		// Forward page after a few seconds to index
		//header('Refresh: 1; index.php');
		
		// Refresh instantly
		//if(!isset($_POST['ref']))
//			header('Location: index.php');
		//else
			@header('Location: index.php?ref='.$_POST['ref']);
			
		exit;
	}

	// If not logged in, print the form / authenticate
	if(!islogged())
	{
		// If they failed at a login, print error
		if(isset($_GET['attempt']))
			println(dialog('Incorrect Login Information', FAILURE, true));
		// If success login, print msg
		//else if(islogged())
		//	println(dialog('You Have Logged In', SUCCESS, true));
		
		// Print login form
		println('<div id="login">');
		println('<form method="post" action="login.php">');
		
		if(isset($_GET['ref']))
			println('<input type="hidden" name="ref" value="'. htmlspecialchars($_GET['ref']) .'" />');
		
		println('<p>Username:<br /><input type="text" name="user" /></p>');
		println('<p>Password:<br /><input type="password" name="pass" /></p>');
		println('<p style="text-align: right;"><input type="submit" name="login" value="Login" /></p>');
		println(' </form>');
		println('</div>');
		println('<p>&nbsp;</p>');
		println('<p align="center"><a href="http://www.mozilla.org/products/firefox/" target="_blank"><img src="images/getff.gif" width="110" height="32" alt="100% Firefox Compatible" border="0"/></a></p>');
		println('<p align="center"><a href="http://www.mozilla.org/products/firefox/" target="_blank">100% Firefox Compatible</a></p>');
	}
	
	/////////////////////////////////////////
	// template footer
	require 'include/admin-footer.php';
	
?>
