<?php

	// Admin - Manage Groups Page
	// October 18, 2005 11:27:18 PM - created
	// October 26, 2005 07:15:00 PM - DONE FINALLY...i hope (oh snap, there's no delete method)
	// October 26, 2005 07:52:05 PM - renamed frome groups-users.php to groups-users.php
	// October 26, 2005 11:42:56 PM - misc updates; table renamed from presets to groups; more complete (need a delete method)

	// template header
	require 'include/admin-header.php';
	
	// Verify logged in
	require ROOT.PATH_CMS.'include/auth.php';
	/////////////////////////////////////
	// Actions
	
	if(isset($_POST['add']) && !empty($_POST['name']))
	{
		$_name = addslashes($_POST['name']);
		$_name = htmlspecialchars($_name);
		
		// Make sure this isn't taken already
		$sql->query("SELECT 1 FROM groups WHERE name='$_name'");
		
		if($sql->num_rows() > 0)
		{
			header('Location: groups-users.php?confirm=1');	
			exit;
		}
		
		// ADD Group
		$sql->query("INSERT INTO groups (name) VALUES('$_name')");
		$_pid = $sql->insert_id();
		
		// Record action
		accesslog('Group Added ('. $_name .')');
		
		// Reload		
		header('Location: groups-users.php?confirm=2');
		exit;
	}
	else if(isset($_POST['del']))
	{
		$id=$_POST['id'];
		
		// 2 is the id for the default group
		// dont' change it...
		$sql->query("UPDATE users SET group_id=2 WHERE group_id='$_POST[id]'");
	}
	else if(isset($_POST['save']))
	{
		$box = $_POST['box'];
		
		//print_r($box);exit;
		
		// clear table
		$sql->query("TRUNCATE TABLE rangefile");
		
		foreach($box as $group_id=>$arr)
			foreach($arr as $file)
				$sql->query("INSERT INTO rangefile (group_id,file) VALUES('$group_id','$file')");
				
		accesslog('Updated Groups');
		
		header('Location: groups-users.php?confirm=3');
		exit;
	}
	
	/////////////////////////////////////
	// Confirmation messages
	
	if(isset($_GET['confirm']) && $_GET['confirm'] == 1) 
		println(dialog('Name Already Taken', FAILURE));
	else if(isset($_GET['confirm']) && $_GET['confirm'] == 2)
		println(dialog('Group Added', SUCCESS));	
	else if(isset($_GET['confirm']) && $_GET['confirm'] == 3)
		println(dialog('Group Updated', SUCCESS));	
	
	/////////////////////////////////////////////////////////////////////////////////////////////

	
	$groups = array();
	$sql->query("SELECT id,name FROM groups");
	while($ln = $sql->fetch_assoc())
		$groups[$ln['id']] = array('name'=>$ln['name'],'perms'=>array());
		
	//print_r($groups);exit;
	
	$sql->query(
		 "SELECT rangefile.group_id as group_id,rangefile.file"
		." FROM rangefile"
		." ORDER BY rangefile.file"
	);
	while($ln = $sql->fetch_assoc())
	{
		$groups[$ln['group_id']]['perms'][] = $ln['file'];
	}
	
	//print_r($groups);exit;
	
	println('<form method="post" action="">');
	println('<p>Check off any pages you want to be restricted for a certain group.</p>');
	println('<table id="highlight">');
	
	///
	$grps_row_str = '<tr>'."\n";
	$grps_row_str .= '<th>&nbsp;</th>'."\n";
	foreach($groups as $id=>$arr)
		$grps_row_str .= '<th>'.$arr['name'].'</th>'."\n";
	$grps_row_str .= '</tr>'."\n";
	////
	
	$i = 0;
	$dir = listdir(ROOT.PATH_CMS, array());
	
	foreach($dir as $file)
	{
		if($i%10 == 0)
			echo $grps_row_str;
		
		println('<tr>');
		println('<td><a href="'.$file.'">'.$file.'</a></td>');
		
		foreach($groups as $id=>$arr)
		{
			$chck = '';
			if(in_array($file, $arr['perms']))
				$chck = ' checked="checked"';
			
			println('<td style="text-align:center;"><input type="checkbox" name="box['.$id.'][]" value="'.$file.'"'.$chck.' /></td>');
		}
			
		println('</tr>');
		
		$i++;
	}
	
	println('</table>');
	println('<p><input type="submit" name="save" value="Save" /></p>');
	println('</form>');
	
	println('<p>&nbsp;</p>');
	
	////////////
	// Add group form
	println('<form method="post" action="">');
	println('<fieldset>');
	println('<legend>Create New Group</legend>');
	println('<table>');
	println('<tr>');
	println('<th align="right">Name:</th>');
	println('<td><input type="text" name="name" /></td>');
	println('</tr>');
	println('<tr>');
	println('<td colspan="2" align="right"><input type="submit" name="add" value="Create Group" /></td>');
	println('</tr>');
	println('</table>');
	println('</fieldset>');
	println('</form>');


        ////////////
        // DELETE a group
        println('<form method="post" action="">');
        println('<fieldset>');
        println('<legend>Delete Group</legend>');
        println('<table>');
        println('<tr>');
        println('<td><select name="id">');

	foreach($groups as $id=>$arr)
	{
		if($arr['name']!='default')
			echo '<option value="'.$id.'">'.$arr['name'].'</option>';
	}
	
	
	println('</select><input type="submit" name="del" value="Blast!" /></td>');
        println('</tr>');
        println('</table>');
        println('</fieldset>');
        println('</form>');
				

	/////////////////////////////////////////
	// template footer
	require 'include/admin-footer.php';
	
?>
