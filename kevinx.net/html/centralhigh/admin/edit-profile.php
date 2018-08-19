<?php

	// Admin - Edit Profile Pie
	// September 30, 2005 02:50:00 PM - started
	// September 30, 2005 07:03:18 PM - finished first draft
	// October 05, 2005 01:33:57 AM - added alias
	// October 09, 2005 11:51:47 PM - made email field optional
	// October 15, 2005 03:55:55 PM - default_page is now in select menu -> previously in input text box
	// October 15, 2005 09:00:04 PM - helpful/friendly error messages

	// template header
	require 'include/admin-header.php';
	
	// Verify logged in
	require ROOT.PATH_CMS.'include/auth.php';
	/////////////////////////////////////
	
	//////////////
	// Save changes to their profile
		if(isset($_POST['update']))
		{
			// Validate email (only if the field is not empty!)
			$_email = $_POST['email'];
			
			if(!empty($_email))
			{
				if(!valid_email($_email))
				{
					//die('Error: Invalid email');
					header('Location: edit-profile.php?confirm=5');
					exit;
				}
			}
				
			// Make sure alias is not taken
			$_alias = $_POST['alias'];
			
			// Default page
			$_default = $_POST['default'];
			
			///
			
			$sql->query("SELECT * FROM users WHERE (name='$_alias' OR alias='$_alias') AND id!='". getid() ."'");
			
			if($sql->num_rows() > 0)
			{
				//die('Error: Name already taken');
				header('Location: edit-profile.php?confirm=4');
				exit;
			}
			
			$_id = getid();
			
			// Update email
			$sql->query(
				"UPDATE users".
				" SET email='$_email', alias='$_alias', default_page='$_default'".
				" WHERE id='$_id'"
			);
			
			// Record action
			accesslog('Updated Profile');
			
			// Redirect
			header('Location: edit-profile.php?confirm=1');
			exit;
		}
		// Change password
		else if(isset($_POST['changepw']))
		{
			// Validate password
			$pw1 = $_POST['pw1'];
			$pw2 = $_POST['pw2'];
			
			if($pw1 != $pw2)
			{
				//die('Error: Passwords don\'t match');
				header('Location: edit-profile.php?confirm=3');
				exit;
			}
			
			// Get old pass hash
			$_oldpass = $_POST['oldpw'];
			$_oldpass = salt($_oldpass, get('name'));
			
			$sql->query("SELECT name FROM users WHERE id='". getid() ."' AND pass='$_oldpass'");
			
			if($sql->num_rows() == 0)
			{
				// Redirect - password change error
				header('Location: edit-profile.php?confirm=2');
				exit;
			}
			
			// New pass hash
			$_pass = salt($pw1, get('name'));
			
			// Update password
			$sql->query("UPDATE users SET pass='$_pass' WHERE id='". getid() ."'");
			
			// Record action
			accesslog('Changed Password');
			
			// Logout
			header('Location: logout.php');
			exit;
		}
	//////////////
	
	// If data saved successfully, confirm
	if(isset($_GET['confirm']) && $_GET['confirm'] == 1)
		println(dialog('Profile Updated', SUCCESS));
	// If password change failed, shout
	else if(isset($_GET['confirm']) && $_GET['confirm'] == 2)
		println(dialog('Incorrect Password', FAILURE));
	// If password don't match
	else if(isset($_GET['confirm']) && $_GET['confirm'] == 3)
		println(dialog('Password Mismatch', FAILURE));
	// If alias is already taken
	else if(isset($_GET['confirm']) && $_GET['confirm'] == 4)
		println(dialog('Name Already Taken', FAILURE));
	// If email is invalid
	else if(isset($_GET['confirm']) && $_GET['confirm'] == 5)
		println(dialog('Invalid Email', FAILURE));
	
	//////////////
	// Display current user information
	
	$res = $sql->query("SELECT alias,email,default_page FROM users WHERE id='". getid() ."'");
	$tmp = $sql->fetch_assoc($res);
	
	$_email = $tmp['email'];
	$_alias = $tmp['alias'];
	$_default = $tmp['default_page'];
	
	////
	// Path to the admin folder
	$dir = ROOT.PATH_CMS;
	
	$files = listdir($dir);
	/////
	
	// Change email
	println('<fieldset>');
	//println('<legend>Change User Information</legend>');
	println('<form method="post" action="">');
	println('<table>');
	println('<tr>');
	println('<th align="right">Alias:</th>');
	println('<td><input type="text" name="alias" value="'. $_alias .'"></td>');
	println('</tr>');
	println('<tr>');
	println('<th align="right">E-mail:</th>');
	println('<td><input type="text" name="email" value="'. $_email .'"></td>');
	println('</tr>');
	println('<tr>');
	println('<th align="right">Default Page:</th>');
	println('<td>');
	
		println('<select name="default">');
		
		foreach($files as $file)
		{
			if($file == $_default)
				println('<option value="'. $file .'" selected="selected">'. $file .'</option>');
			else
				println('<option value="'. $file .'">'. $file .'</option>');
		}
		
		println('</select>');
	
	println('</td>');
	println('</tr>');
	println('<tr>');
	println('<td colspan="2" align="right"><input type="submit" name="update" value="Update Profile"></td>');
	println('</tr>');
	println('</table>');
	println('</form>');
	println('</fieldset>');
	
	println('<p>&nbsp;</p>');
	
	// Change password
	println('<fieldset>');
	//println('<legend>(Optional)</legend>');
	println('<form method="post" action="">');
	println('<table>');
	println('<tr>');
	println('<th align="right">Old Password:</th>');
	println('<td><input type="password" name="oldpw"></td>');
	println('</tr>');
	println('<tr>');
	println('<th align="right">New Password:</th>');
	println('<td><input type="password" name="pw1"><br /><input type="password" name="pw2"></td>');
	println('</tr>');
	println('<tr>');
	println('<td colspan="2" align="right"><input type="submit" name="changepw" value="Change Password"></td>');
	println('</tr>');
	println('</table>');
	println('</form>');
	println('</fieldset>');
	
	//////////////
	
	/////////////////////////////////////////
	// template footer
	require 'include/admin-footer.php';
	
?>
