<?php

	// Admin - Manage Range
	// October 18, 2005 11:28:07 PM - created
	// October 21, 2005 06:38:16 PM - renamed from range-groups.php to range-users.php

	// template header
	require 'include/admin-header.php';
	
	// Verify logged in
	require '../include/auth.php';
	/////////////////////////////////////

	// UPDATE Index
	if(isset($_POST['update']))
	{
		foreach($_POST['file'] as $_id => $arr)
		{
			$_file = $arr['file'];
			
			// DELETE file
			if(empty($_file))
			{ 
				// Get filename
				$sql->query("SELECT file FROM rangefile WHERE id='$_id'");
				$sql->fetch_assoc();
				$res = $res['file'];
				
				// Delete
				$sql->query("DELETE FROM rangefile WHERE id='$_id'");
				
				// Record
				accesslog('Filename Deleted ('. $res .')');
			}
			// UPDATE Filename
			else if(file_exists($_file))
			{
				// Update
				$sql->query("UPDATE rangefile SET file='$_file' WHERE id='$_id'");	
			}
		}
		
		// Record
		accesslog('Index Updated');
		
		// Reload
		header('Location: range-users.php?confirm=4');
		exit;
	}
	// ADD File to Index
	else if(isset($_POST['add']) && !empty($_POST['add']))
	{
		// Escape characters
		$_file = addslashes(trim($_POST['file']));
		$_file = htmlspecialchars($_file, ENT_QUOTES);
		
		// Verify it exists
		if(!file_exists($_file))
		{
			header('Location: range-users.php?confirm=1');
			exit;	
		}
		
		// Verifiy it isn't already indexed
		$sql->query("SELECT id FROM rangefile WHERE file='$_file'");
		
		if($sql->num_rows() > 0)
		{
			header('Location: range-users.php?confirm=3');
			exit;	
		}
		
		// INSERT
		$sql->query("INSERT INTO rangefile (file) VALUES('$_file')");
		$_rid = $sql->insert_id();
		
		$res = $sql->query("SELECT id FROM groups");
		
		while($line = $sql->fetch_assoc($res))
		{
			$_pid = $line['id'];
			
			$sql->query("INSERT INTO groups2range (group_id,range_id,perm) VALUES('$_pid','$_rid','Y')");
		}
		
		// Record action
		accesslog('File Indexed ('. $_file .')');
		
		// Reload
		header('Location: range-users.php?confirm=2');
		exit;
	}
	
	/////////////////////////////////////
	
	if(isset($_GET['confirm']) && $_GET['confirm'] == 1)
	{
		println(dialog('Non-existent File', FAILURE));	
	}
	else if(isset($_GET['confirm']) && $_GET['confirm'] == 2)
	{
		println(dialog('File Indexed', SUCCESS));	
	}
	else if(isset($_GET['confirm']) && $_GET['confirm'] == 3)
	{
		println(dialog('File Already Indexed', FAILURE));	
	}
	else if(isset($_GET['confirm']) && $_GET['confirm'] == 4)
	{
		println(dialog('Index Updated', SUCCESS));	
	}
	
	/////////////////////////////////////
	
	////////////
	// Load pages list in table
	
	$sql->query("SELECT id,file FROM rangefile ORDER BY file ASC");
	
	if($sql->num_rows() > 0)
	{
		println('<form method="post" action="">');
		println('<table id="highlight">');
		println('<tr>');
		println('<th>ID</th>');
		println('<th>File</th>');
		println('</tr>');
		
		while($line = $sql->fetch_assoc())
		{
			$_id = $line['id'];
			$_file = $line['file'];
			
			println('<tr>');
			println('<td align="center">'. $_id .'</td>');
			println('<td><input type="text" name="file['. $_id .'][file]" value="'. $_file .'" /></td>');
			println('</tr>');
		}
		
		println('</table>');
		println('<p><input type="submit" name="update" value="Update Index" /></p>');
		println('</form>');
		
		///
		
		println('<p>&nbsp;</p>');
	}
	
	////////////
	// Add page
	println('<form method="post" action="">');
	println('<fieldset>');
	println('<legend>Add to Index</legend>');
	println('<table>');
	println('<tr>');
	println('<th align="right">Filename:</th>');
	println('<td><input type="text" name="file" /></td>');
	println('</tr>');
	println('<tr>');
	println('<td colspan="2" align="right"><input type="submit" name="add" value="Add Page" /></td>');
	println('</tr>');
	println('</table>');
	println('</fieldset>');
	println('</form>');

	/////////////////////////////////////////
	// template footer
	require 'include/admin-footer.php';
	
?>
