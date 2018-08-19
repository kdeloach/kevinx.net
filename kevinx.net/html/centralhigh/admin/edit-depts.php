<?php

	// Admin - Edit Departments Page
	// October 08, 2005 02:35:16 AM - made coffee
	// October 08, 2005 02:37:23 AM - started
	// October 08, 2005 02:52:39 AM - went to sleep
	// October 08, 2005 09:07:38 PM - finished first draft
	// October 08, 2005 09:32:58 PM - when creating/deleting depts, also modify related entry in deptheads table

	// template header
	require 'include/admin-header.php';
	
	// Verify logged in
	require ROOT.PATH_CMS.'include/auth.php';
	/////////////////////////////////////
	
	// Save departments info
	if(isset($_POST['save']))
	{
		foreach($_POST['dept'] as $_id=>$arr)
		{
			$_tag = addslashes($arr['tag']);
			$_name = addslashes($arr['name']);
			
			// If name is empty, delete all references
			if(empty($_name))
			{
				$sql->query("SELECT id,name FROM depts WHERE id='$_id'");
				$tmp = $sql->fetch_assoc();
				$_name = $tmp['name'];
				
				// Delete from depts table
				$sql->query("DELETE FROM depts WHERE id='$_id'");
				
				// Also delete from deptheads table
				$sql->query("DELETE FROM deptheads WHERE dept_id='$_id'");
				
				// ALSO delete this from the teachers2depts table (WON'T delete the teachers in this dept)
				$sql->query("DELETE FROM teachers2depts WHERE dept_id='$_id'");
				
				// Record action	
				accesslog('Department Removed ('. $_name .')');
			}
			// Else, update data
			else
			{
				$sql->query("UPDATE depts SET name='$_name',tag='$_tag' WHERE id='$_id'");
			}
			
		}
		
		// Record action
		accesslog('Updated Departments');
		exit;
		
		// Redirect
		//header('Location: edit-depts.php?confirm=1');
	}
	// Create New Department
	else if(isset($_POST['add']))
	{
		$_name = addslashes($_POST['name']);
		$_tag = tag($_name);
		
		// Insert into depts table
		$sql->query("INSERT INTO depts (name,tag) VALUES('$_name','$_tag')");
		
		// Pull last inserted id
		$_id = $sql->insert_id();
		
		// Also insert row in deptheads table; default is null
		$sql->query("INSERT INTO deptheads (dept_id) VALUES('$_id')");
		
		// Record action
		accesslog('Added Department ('. $_name .')');
		
		// Redirect
		header('Location: edit-depts.php?confirm=1');
		exit;
	}
	
	////////////
	
	// If data saved successfully, confirm
	if(isset($_GET['confirm']))
		println(dialog('Departments Updated', SUCCESS));
		
	// Display depts in table
	println('<form method="post" action="">');
	println('<table id="highlight">');
	println('<tr>');
	println('<th>ID</th>');
	println('<th>Name</th>');
	println('<th>Tag</th>');
	println('</tr>');
	
	$sql->query("SELECT id,name,tag FROM depts ORDER BY id ASC");
	
	while($row = $sql->fetch_assoc())
	{
		$_id = $row['id'];
		$_name = $row['name'];
		$_tag = $row['tag'];
		
		println('<tr>');
		println('<td align="center">'. $_id .'</td>');
		println('<td><input type="text" name="dept['. $_id.'][name]" value="'. $_name .'" /></td>');
		println('<td><input type="text" name="dept['. $_id.'][tag]" value="'. $_tag .'" /></td>');
		println('</tr>');
	}	
	
	println('</table>');
	println('<p>');
	println('<input type="reset" value="Reset" />');
	println('<input type="submit" name="save" value="Save Changes" />');
	println('</p>');
	println('</form>');
	
	////////////
	
	println('<p>&nbsp;</p>');
	
	///////////////
	// Add Department form
	println('<form method="post" action="">');
	println('<fieldset>');
	println('<legend>Create New Department</legend>');
	println('<table>');
	println('<tr>');
	println('<th align="right">Name:</th>');
	println('<td><input type="text" name="name" /></td>');
	println('</tr>');
	println('<tr>');
	println('<td colspan="2" align="right"><input type="submit" name="add" value="Add Department" /></td>');
	println('</tr>');
	println('</table>');
	println('</fieldset>');
	println('</form>');
	
	//////////////
	
	//////////////////
	// template footer
	require 'include/admin-footer.php';
	
?>