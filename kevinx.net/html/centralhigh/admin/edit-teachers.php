<?php

	// Admin - Edit Teachers Page
	// October 08, 2005 11:09:37 PM - started
	// October 10, 2005 02:35:03 AM - wow, still procrastina-- working on this...
	// October 10, 2005 06:11:07 PM - editing teachers complete
	// October 10, 2005 06:30:29 PM - first draft complete
	// November 06, 2005 01:13:39 PM - don't validate emails

	// template header
	require 'include/admin-header.php';
	
	// Verify logged in
	require ROOT.PATH_CMS.'include/auth.php';
	/////////////////////////////////////
	
	// Generate departments checkboxes for use in forms
	// $cols - the amount of columns to divide the checkboxes into
	function generate_checkboxes($cols=4)
	{
		global $sql;
		
		// Find out how many departments there are
		$res = $sql->query("SELECT count(id) as num FROM depts");
		$tmp = $sql->fetch_assoc($res);
		
		$num = $tmp['num'];
		
		// id's of checkboxes to be checked by default
		$checked = array();
		
		// If a teacher is being edited...find out what depts they're in
		if(isset($_GET['edit']))
		{
			$_teacher_id = htmlspecialchars($_GET['edit'], ENT_QUOTES);
				
			$res = $sql->query("SELECT dept_id FROM teachers2depts WHERE teacher_id='$_teacher_id'");
			
			// put the id's for all departments they belong to, in an array
			while($line = $sql->fetch_assoc($res))
				$checked[$line['dept_id']] = true;
		}
		
		// Collect depts data into an array for easier manipulation
		$res = $sql->query("SELECT id,name FROM depts");
		
		$depts = array();
		
		while($line = $sql->fetch_assoc($res))
			$depts[] = $line;
		
		// Start to output checkboxes
		println('<table>');
		
		for($i = 0; $i < ceil($num / $cols); $i++)
		{
			println('<tr>');
			
			$n = 0;
			
			for($n = 0; $n < $cols; $n++)
			{	
				$pos = ($n+($cols*$i));
				
				if(!isset($depts[$pos]))
					break;
					
				$_id = $depts[$pos]['id'];
				$_name = $depts[$pos]['name'];
				
				$sel = '';
					
				if(isset($checked[$_id]))
					$sel = 'checked="checked"';
				
				println('<td><input type="checkbox" name="depts[]" id="'. $_id .'" value="'. $_id .'" '. $sel .'/> <label for="'. $_id .'">'. $_name .'</label></td>');
			}
			
			println('</tr>');
		}
		
		println('</table>');
	}
	
	//////////////////////////
		
	// Edit teacher	
	if(isset($_GET['edit']))
	{
		$_id = htmlspecialchars($_GET['edit'], ENT_QUOTES);
		
		// Get the teachers data using some crazy mysql magic
		$sql->query("SELECT fname,lname,email,website FROM teachers WHERE id='$_id'");
		$tmp = $sql->fetch_assoc();
		
		$_fname = htmlspecialchars($tmp['fname'], ENT_QUOTES);
		$_lname= htmlspecialchars($tmp['lname'], ENT_QUOTES);
		$_email = htmlspecialchars($tmp['email'], ENT_QUOTES);
		$_website = htmlspecialchars($tmp['website'], ENT_QUOTES);
		
		// Add Teacher form
		println('<form method="post" action="edit-teachers.php">');
		println('<input type="hidden" name="id" value="'. $_id .'" />');
		//println('<fieldset>');
		//println('<legend>Edit Teacher</legend>');
		println('<table>');
		println('<tr>');
		println('<th align="right">First Name:</th>');
		println('<td><input type="text" name="fname" value="'. $_fname .'" /></td>');
		println('</tr>');
		println('<tr>');
		println('<th align="right">Last Name:</th>');
		println('<td><input type="text" name="lname" value="'. $_lname .'" /></td>');
		println('</tr>');
		println('<tr>');
		println('<th align="right">Email:</th>');
		println('<td><input type="text" name="email" value="'. $_email .'" /></td>');
		println('</tr>');
		println('<tr>');
		println('<th align="right">Website:</th>');
		println('<td><input type="text" name="website" value="'. $_website .'" /></td>');
		println('</tr>');
		println('<tr>');
		println('<th align="right">Departments:</th>');
		println('<td>');
			
			// Output department checkboxes
			generate_checkboxes();
			
		println('</td>');
		println('</tr>');
		println('<tr>');
		println('<td colspan="2" align="right"><input type="submit" name="edit" value="Edit Teacher" /></td>');
		println('</tr>');
		println('</table>');
		//println('</fieldset>');
		println('</form>');
	}
	// Save edited teacher
	else if(isset($_POST['edit']))
	{
		$_id = htmlspecialchars($_POST['id'], ENT_QUOTES);
		$_fname = htmlspecialchars($_POST['fname'], ENT_QUOTES);
		$_lname= htmlspecialchars($_POST['lname'], ENT_QUOTES);
		$_email = htmlspecialchars($_POST['email'], ENT_QUOTES);
		$_website = htmlspecialchars($_POST['website'], ENT_QUOTES);
		
		$sql->query("UPDATE teachers SET fname='$_fname',lname='$_lname',email='$_email',website='$_website' WHERE id='$_id'");
		
		// Update their departments
		$_depts = $_POST['depts'];
		
		// Delete all departments this teacher belongs to
		$sql->query("DELETE FROM teachers2depts WHERE teacher_id='$_id'");
		
		// Insert all the departments selected for this teacher from the edit form
		foreach($_depts as $_d)
			$sql->query("INSERT INTO teachers2depts (dept_id,teacher_id) VALUES('$_d','$_id')");
		
		// If this teacher doesn't belong to any departments, give them a default dept
		if(empty($_depts))
			$sql->query("INSERT INTO teachers2depts (dept_id,teacher_id) VALUES('0','$_id')");
		
		// Record action
		accesslog('Teacher Edited ('. $_fname .' '. $_lname .')');
		
		// Reload
		header('Location: edit-teachers.php?confirm=3');
		exit;
	}
	else if(isset($_POST['add']))
	{
		$_fname = htmlspecialchars($_POST['fname'], ENT_QUOTES);
		$_lname= htmlspecialchars($_POST['lname'], ENT_QUOTES);
		$_email = htmlspecialchars($_POST['email'], ENT_QUOTES);
		$_website = htmlspecialchars($_POST['website'], ENT_QUOTES);
		
		/*if(!empty($_email) && !valid_email($_email))
		{
			die('Error: Invalid email');	
		}*/
		
		$sql->query("INSERT INTO teachers (fname,lname,email,website) VALUES('$_fname','$_lname','$_email','$_website')");
		
		$_id = $sql->insert_id();
		$_depts = $_POST['depts'];
		
		// Insert all the departments selected for this teacher
		foreach($_depts as $_d)
			$sql->query("INSERT INTO teachers2depts (dept_id,teacher_id) VALUES('$_d','$_id')");
		
		// If this teacher doesn't belong to any departments, give them a default dept
		if(empty($_depts))
			$sql->query("INSERT INTO teachers2depts (dept_id,teacher_id) VALUES('0','$_id')");
		
		// Record action
		accesslog('Teacher Added ('. $_fname .' '. $_lname .')');
		
		// Reload
		header('Location: edit-teachers.php?confirm=1');
		exit;
	}
	// Remove teacher
	else if(isset($_GET['remove']))
	{
		$_id = $_GET['remove'];
		
		$sql->query("SELECT fname,lname FROM teachers WHERE id='$_id'");
		$tmp = $sql->fetch_assoc();
		
		$_fname = $tmp['fname'];
		$_lname = $tmp['lname'];
		
		$sql->query("DELETE FROM teachers WHERE id='$_id'");
		$sql->query("DELETE FROM teachers2depts WHERE teacher_id='$_id'");
		
		// Record action	
		accesslog('Teacher Removed ('. $_fname .' '. $_lname .')');
		
		// Reload page
		header('Location: edit-teachers.php?confirm=2');
		exit;
	}
	else
	{
		///////////////////////////////	
			if(isset($_GET['confirm']) && $_GET['confirm'] == 1)
			{
				println(dialog('Teacher Added', SUCCESS));
			}
			else if(isset($_GET['confirm']) && $_GET['confirm'] == 2)
			{
				println(dialog('Teacher Removed', SUCCESS));
			}
			else if(isset($_GET['confirm']) && $_GET['confirm'] == 3)
			{
				println(dialog('Teacher Edited', SUCCESS));
			}
		////////////////////////////////
		
		//////////
		// Display teachers in a table;
		
		$dirs = array('ASC','DESC');
		@$_dir = (isset($_GET['d']) ? (2 / $_GET['d']) : 1);
		
		println('<table id="highlight">');
		println('<tr>');
		println('<th><a href="edit-teachers.php?sortby=id&d='. $_dir .'">ID</a></th>');
		println('<th><a href="edit-teachers.php?sortby=fname&d='. $_dir .'">First Name</a></th>');
		println('<th><a href="edit-teachers.php?sortby=lname&d='. $_dir .'">Last Name</a></th>');
		println('<th><a href="edit-teachers.php?sortby=dept&d='. $_dir .'">Departments</a></th>');
		println('<th><a href="edit-teachers.php?sortby=email&d='. $_dir .'">Email</a></th>');
		println('<th><a href="edit-teachers.php?sortby=website&d='. $_dir .'">Website</a></th>');
		println('<th>&nbsp;</th>');
		println('<th>&nbsp;</th>');
		println('</tr>');
		
		// Check if sortby is overridden, escape slashes just in case someone messes with the query string....
		$_sortby = (isset($_GET['sortby']) ? addslashes($_GET['sortby']) : 'dept');
		@$_dir = $dirs[$_dir-1];
		
		// Select teachers info AND departments they belong to
		// NOTE: if belong to more than 1 dept, returns same teacher multiple times..."depts.name" is not an array...(confusing)
		$sql->query(
			"SELECT teachers.id,fname,lname,email,website,depts.name as dept".
			" FROM teachers,teachers2depts,depts".
			" WHERE (teachers2depts.dept_id=depts.id".
			" OR teachers2depts.dept_id='NULL')".
			" AND teachers2depts.teacher_id=teachers.id".
			" ORDER BY $_sortby $_dir"
		);
		
		$teachers = array();
		
		while($row = $sql->fetch_assoc())
		{
			$_id = $row['id'];
			$_dept = $row['dept'];
			
			// If teacher is already in the array, it means they belong in multiple depts
			// So we want to add this dept to the array of depts they belong to
			// Instead of adding the teacher to the list again
			if(isset($teachers[$_id]))
			{
				$teachers[$_id]['depts'][] = $_dept;
			}
			else
			{
				// Start array of depts
				$_depts = array($_dept);
				
				// Replace String dept, with array of depts
				unset($_dept);
				$row['depts'] = $_depts;
				
				// Add row to array; ready for output
				$teachers[$_id] = $row;
			}
		}
		
		foreach($teachers as $_id=>$row)
		{
			$_fname = $row['fname'];
			$_lname = $row['lname'];
			$_email = $row['email'];
			$_website = $row['website'];
			$_depts = $row['depts'];
			
			// Add link to website if exists
			if(!empty($_website))
				$_website = '<a href="'. $_website .'" target="_blank">Link</a>';
			
			// Convert depts array to string
			$_depts = implode(', ', $_depts);
				
			println('<tr>');
			println('<td align="center">'. $_id .'</td>');
			println('<td>'. $_fname .'</td>');
			println('<td>'. $_lname .'</td>');
			println('<td>'. $_depts .'</td>');
			println('<td>'. $_email .'</td>');
			println('<td align="center">'. $_website .'</td>');
			println('<td><a href="edit-teachers.php?edit='. $_id .'">Edit</a></td>');
			println('<td><a href="edit-teachers.php?remove='. $_id .'">Remove</a></td>');
			println('</tr>');
		}
		
		println('</table>');
		
		//////////
		
		println('<p>&nbsp;</p>');
		
		///////////////
		// Add Teacher form
		println('<form method="post" action="">');
		println('<fieldset>');
		println('<legend>Create New Teacher</legend>');
		println('<table>');
		println('<tr>');
		println('<th align="right">First Name:</th>');
		println('<td><input type="text" name="fname" /></td>');
		println('</tr>');
		println('<tr>');
		println('<th align="right">Last Name:</th>');
		println('<td><input type="text" name="lname" /></td>');
		println('</tr>');
		println('<tr>');
		println('<th align="right">Email:</th>');
		println('<td><input type="text" name="email" /></td>');
		println('</tr>');
		println('<tr>');
		println('<th align="right">Website:</th>');
		println('<td><input type="text" name="website" /></td>');
		println('</tr>');
		println('<tr>');
		println('<th align="right">Departments:</th>');
		println('<td>');
			
			// Output department checkboxes
			generate_checkboxes();
			
		println('</td>');
		println('</tr>');
		println('<tr>');
		println('<td colspan="2" align="right"><input type="submit" name="add" value="Add Teacher" /></td>');
		println('</tr>');
		println('</table>');
		println('</fieldset>');
		println('</form>');
		
		//////////////

	}
		
	//////////////////
	// template footer
	require 'include/admin-footer.php';
	
?>