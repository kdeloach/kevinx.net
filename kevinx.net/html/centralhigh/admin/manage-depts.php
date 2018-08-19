<?php

	// Admin - Manage Departments Page
	// October 08, 2005 02:35:16 AM - made coffee
	// October 08, 2005 02:37:23 AM - started
	// October 08, 2005 02:52:39 AM - went to sleep
	// October 08, 2005 10:18:47 PM - finished first draft
	// October 21, 2005 07:50:01 PM - reduced 26 queries to 2 !@#$

	// template header
	require 'include/admin-header.php';
	
	// Verify logged in
	require ROOT.PATH_CMS.'include/auth.php';
	/////////////////////////////////////
	
	// Update department heads
	if(isset($_POST['save']))
	{
		// For each depts
		foreach($_POST['dept'] as $_id=>$teacher_id)
		{
			// Update department head
			$sql->query("UPDATE deptheads SET teacher_id='$teacher_id' WHERE dept_id='$_id'");
		}
		
		// Record action
		accesslog('Updated Departments');
		
		// Redirect
		header('Location: manage-depts.php?confirm=1');
		exit;
	}
	
	///////////////////////
	
	// If data saved successfully, confirm
	if(isset($_GET['confirm']))
		println(dialog('Departments Updated', SUCCESS));
	
	// Display depts in table
	println('<form method="post" action="">');
	println('<table id="highlight">');
	println('<tr>');
	println('<th>ID</th>');
	println('<th>Name</th>');
	println('<th>Head</th>');
	println('<th>Tag</th>');
	println('<th>Page</th>');
	println('</tr>');
	
	$sql->query(
		"SELECT teachers2depts.dept_id,teachers2depts.teacher_id,teachers.lname,depts.name as deptname,depts.tag,deptheads.teacher_id as head".
		" FROM teachers2depts,depts,teachers,deptheads".
		" WHERE teachers2depts.dept_id=depts.id".
		" AND teachers2depts.teacher_id=teachers.id".
		" AND teachers2depts.dept_id=deptheads.dept_id".
		//" ORDER BY depts.name ASC"
		" ORDER BY teachers.lname ASC"
	);
	
	$teachers = array();
	$depts = array();
	
	while($row = $sql->fetch_assoc())
	{
		//println('<xmp>'.print_r($row,true).'</xmp>');
		
		$depts[$row['dept_id']] = array(
			'deptname' => $row['deptname'],
			'tag' => $row['tag'],
			'head' => $row['head'],
		);
		
		$teachers[$row['dept_id']][] = array(
			'teacher_id' => $row['teacher_id'],
			'lname' => $row['lname'],
		);
	}
	
	// sort by departments
	ksort($depts);
	
	foreach($depts as $id=>$row)
	{
		$name = $row['deptname'];
		$tag = $row['tag'];
		$head = $row['head'];
		
		println('<tr>');
		println('<td align="center">'. $id .'</td>');
		println('<td>'. $name .'</td>');
		println('<td>');
		
			// Generate a select menu with teachers
			println('<select name="dept['. $id .']">');
			
				println('<option value="null">-- Nobody --</option>');
				
				foreach($teachers[$id] as $t_deptid=>$t_arr)
				{
					$t_id = $t_arr['teacher_id'];
					$t_name = $t_arr['lname'];
					
					$selected = '';
					
					if($t_id == $head)
						$selected = ' selected="selected"';
						
					println('<option value="'. $t_id .'"'. $selected .'>'. $t_name .'</option>');
				}
			
			println('</select>');
		
		println('</td>');
		println('<td>'. $tag .'</td>');
		println('<td><a href="pages-depts.php?id='.$id.'">Edit Page</a></td>');
		println('</tr>');
	}	
	
	println('</table>');
	println('<p>');
	println('<input type="reset" value="Reset" />');
	println('<input type="submit" name="save" value="Save Changes" />');
	println('</p>');
	println('</form>');
	
	//////////////////
	// template footer
	require 'include/admin-footer.php';
	
?>
