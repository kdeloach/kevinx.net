<?php

	// Admin - User Accounts
	// September 30, 2005 02:50:00 PM - started
	// September 30, 2005 05:23:07 PM - finished first draft
	// October 09, 2005 11:50:11 PM - deleted 'email' requirement to add new users
	// October 10, 2005 06:20:47 PM - email is optional, but if used it MUST be valid
	// October 15, 2005 03:52:16 PM - default_page can be selected when creating a new user
	// October 16, 2005 01:51:17 AM - added friendly confirmation messages
	// October 16, 2005 02:45:24 AM - can delete users now
	// October 21, 2005 06:17:00 PM - renamed manage-users.php to manage-users.php
	// October 26, 2005 11:42:44 PM - added GROUPS code

	// template header
	require 'include/admin-header.php';
	
	// Verify logged in
	require ROOT.PATH_CMS.'include/auth.php';
	/////////////////////////////////////
	
	/////////////
	// Add User
		if(isset($_POST['register']))
		{
			// Validate name
			$_name = $_POST['name'];
			
			if(empty($_name))
			{
				//die('Error: No name entered');
				header('Location: manage-users.php?confirm=1');
				exit;
			}
				
			$sql->query("SELECT id FROM users WHERE name='$_name' OR alias='$_name'");
			
			if($sql->num_rows() > 0)
			{
				//die('Error: Name already taken');
				header('Location: manage-users.php?confirm=2');
				exit;
			}
			
			// Validate password
			$pw1 = $_POST['pw1'];
			$pw2 = $_POST['pw2'];
			
			if($pw1 != $pw2)
			{
				//die('Error: Passwords don\'t match');
				header('Location: manage-users.php?confirm=3');
				exit;
			}
			
			$_pass = encrypt($pw1, $_name);
				
			// Validate email
			/*$_email = $_POST['email'];
			
			if(!empty($_email) && !valid_email($_email))
			{
				//die('Error: Invalid email');
				header('Location: manage-users.php?confirm=4');
				exit;
			}
			*/
				
			// Default Page
			$_default = $_POST['default'];
				
			// Group
			$_group = $_POST['group'];
			
			// Everything's good, add the user
			$sql->query(
				"INSERT INTO users".
				" (name,alias,pass,default_page,group_id)".
				" VALUES('$_name','$_name','$_pass','$_default','$_group')"
			);
		
			// Record action
			accesslog('Added User ('. $_name .')');
		
			// Redirect	
			header('Location: manage-users.php?confirm=5');
			exit;
		}
		// DELETE User
		else if(isset($_GET['delete']))
		{
			// Escape bad characters
			$_id = addslashes($_GET['delete']);
			$_name = get('name');
			
			// Delete
			$sql->query("DELETE FROM users WHERE id='$_id'");
			
			// Record action
			accesslog('Deleted User ('. $_name .')');
			
			// Reload
			header('Location: manage-users.php?confirm=6');
			exit;
		}
		// CHANGE Group
		else if(isset($_POST['update']))
		{
			foreach($_POST['group'] as $userid=>$arr)
			{
				foreach($arr as $groupid)	
				{
					$sql->query("UPDATE users SET group_id='$groupid' WHERE id='$userid'");	
				}
			}
			
			accesslog('Updated User Groups');
			
			header('Location: manage-users.php?confirm=7');
			exit;
		}
	/////////////
	
		if(isset($_GET['confirm']) && $_GET['confirm'] == 1)
		{
			println(dialog('Name Empty', FAILURE));	
		}
		else if(isset($_GET['confirm']) && $_GET['confirm'] == 2)
		{
			println(dialog('Name Taken', FAILURE));	
		}
		else if(isset($_GET['confirm']) && $_GET['confirm'] == 3)
		{
			println(dialog('Password Mismatch', FAILURE));	
		}
		else if(isset($_GET['confirm']) && $_GET['confirm'] == 4)
		{
			println(dialog('Invalid Email', FAILURE));	
		}
		else if(isset($_GET['confirm']) && $_GET['confirm'] == 5)
		{
			println(dialog('Added User', SUCCESS));
		}
		else if(isset($_GET['confirm']) && $_GET['confirm'] == 6)
		{
			println(dialog('User Deleted', SUCCESS));
		}
		else if(isset($_GET['confirm']) && $_GET['confirm'] == 7)
		{
			println(dialog('Users Updated', SUCCESS));
		}
	
	/////////////
	
	$res = $sql->query(
		 "SELECT id,name,alias,email,last_login,group_id"
		." FROM users"
		." ORDER BY group_id DESC"
	);
	
	if($sql->num_rows() > 0)
	{
		// Display users in a table
		println('<form method="post" action="">');
		println('<table id="highlight">');
		println('<tr>');
		println('<th>ID</th>');
		println('<th>Name</th>');
		println('<th>Alias</th>');
		println('<th>E-mail</th>');
		println('<th>Last Login</th>');
		println('<th>Group</th>');
		println('<th>&nbsp;</th>');
		println('</tr>');
		
		// Output users
		while($row = $sql->fetch_assoc($res))
		{
			$_id = $row['id'];
			$_name = $row['name'];
			$_alias = $row['alias'];
			$_email = $row['email'];
			$_group = $row['group_id'];
			
			$_last_login = $row['last_login'];
			
			if(!empty($_email))
				$_email = '<a href="mailto:'. $_email .'">'. $_email .'</a>';
			
			if(empty($_last_login))
				$_date = 'Never';
			else 
				$_date = date(get_setting('date_format'), $_last_login); //l, j M Y G:i:s
			
			println('<tr>');	
			println('<td align="center">'. $_id .'</td>');
			println('<td><a href="access-log.php?search='. $_name .'">'. $_name .'</a></td>');
			println('<td>'. $_alias .'</td>');
			println('<td>'. $_email .'</td>');
			println('<td>'. $_date .'</a></td>');
			println('<td>');
			
				$sql->query("SELECT id,name FROM groups");
				println('<select name="group['.$_id.']['.$_group.']">');
				while($line = $sql->fetch_assoc())
				{
					$_gid = $line['id'];
					$_gname = $line['name'];
					
					if($_gid == $_group) $sel = ' selected="selected"'; else $sel = '';
					
					println('<option value="'.$_gid.'"'.$sel.'>'.$_gname.'</option>');
				}
				println('</select>');
				
			println('</td>');
			println('<td><a href="manage-users.php?delete='. $_id .'">Delete</a></td>');
			println('</tr>');	
		}
		
		println('</table>');
		println('<p><input type="submit" name="update" value="Update Users" /></p>');
		println('</form>');
		//////////////
		
		println('<p>&nbsp;</p>');
	}
	
	//////////////
	// Add-User form
	
	// Path to the admin folder
	$dir = ROOT.PATH_CMS;
	
	// Files array
	$files = listdir($dir);
	
	// Default Page
	$default = 'post-news.php';
	
	println('<fieldset style="margin-top: 15px;">');
	println('<legend>Register New User</legend>');
	println('<form method="post" action="">');
	println('<table>');
	println('<tr>');
	println('<th align="right">Name:</th>');
	println('<td><input type="text" name="name"></td>');
	println('</tr>');
	/*println('<tr>');
	println('<th align="right">E-mail:</th>');
	println('<td><input type="text" name="email"></td>');
	println('</tr>');*/
	println('<tr>');
	println('<th align="right">Group:</th>');
	println('<td>');
	
				$sql->query("SELECT id,name FROM groups");
				println('<select name="group">');
				while($line = $sql->fetch_assoc())
				{
					$_gid = $line['id'];
					$_gname = $line['name'];
					
					println('<option value="'.$_gid.'">'.$_gname.'</option>');
				}
				println('</select>');
				
	println('</td>');
	println('</tr>');
	println('<tr>');
	println('<th align="right">Default Page:</th>');
	println('<td>');
	
		println('<select name="default">');
		
		foreach($files as $file)
		{
			if($file == $default)
				println('<option value="'. $file .'" selected="selected">'. $file .'</option>');
			else
				println('<option value="'. $file .'">'. $file .'</option>');
		}
		
		println('</select>');
	
	println('</td>');
	println('</tr>');
	println('<tr>');
	println('<th align="right">Password:</th>');
	println('<td><input type="password" name="pw1"><br /><input type="password" name="pw2"></td>');
	println('</tr>');
	println('<tr>');
	println('<td colspan="2" align="right"><input type="submit" name="register" value="Add User"></td>');
	println('</tr>');
	println('</table>');
	println('</form>');
	println('</fieldset>');
	
	//////////////
	
	
	/////////////////////////////////////////
	// template footer
	require 'include/admin-footer.php';
	
?>
