<?php

	// Admin - Settings
	// September 30, 2005 07:11:13 PM - started
	// September 30, 2005 07:52:23 PM - finished first draft
	// September 30, 2005 09:04:17 PM - added form to add variables; added ability to delete variables be clearing the value 
	// September 30, 2005 09:23:41 PM - added name conflict validation when creating new variables
	// September 30, 2005 09:42:04 PM - added comments field
	// October 08, 2005 02:28:29 AM - arrived at the marshes and died at the hands of the basilisk; ffvii
	// October 08, 2005 07:52:33 PM - changed input field names; ex: varname[comments] --> setting[varid][comments]

	// template header
	require 'include/admin-header.php';
	
	// Verify logged in
	require ROOT.PATH_CMS.'include/auth.php';
	/////////////////////////////////////
	
	///////////
	// Save data
		if(isset($_POST['save']))
		{
			// Save each value - hope nobody screws up
			foreach($_POST['setting'] as $_id=>$arr)//array('value'=>$value,'comments'=>$comments)
			{
				$_value = addslashes($arr['value']);
				$_comments = addslashes($arr['comments']);
				
				// Delete variable if empty
				if(empty($_value))
				{
					$sql->query("SELECT name FROM settings WHERE id='$_id'");
					$res = $sql->fetch_assoc();
					$_name = $res['name'];
					
					$sql->query("DELETE FROM settings WHERE id='$_id'");
					
					// Record action	
					accesslog('Variable Destroyed ('. $_name .')');
					
					// don't stop executing script now...
					// this is setup to make mass changes, so you can delete/modify several vars at once
					// exiting now would mess this up
				}
				// Else Update the variable
				else
				{
					$sql->query("UPDATE settings SET value='$_value',comments='$_comments' WHERE id='$_id'");
				}
			}
			
			// Record action
			accesslog('Updated Settings');
			
			// Redirect
			header('Location: settings.php?confirm=1');
			exit;
		}
	////////////
	
	/////////////
	// Create new variable
		if(isset($_POST['add']))
		{
			$_name = $_POST['name'];
			$_value = $_POST['value'];
			
			if(empty($_name) || empty($_value))
				die('Error: Both fields must be filled out');
				
			// See if there is a name conflict
			$sql->query("SELECT id FROM settings WHERE name='$_name'");
			
			if($sql->num_rows() > 0)
				die('Error: Name conflict');
				
			// Insert new variable
			$sql->query("INSERT INTO settings (id,name,value) VALUES('','$_name','$_value')");
			
			// Record action	
			accesslog('Variable Created ('. $_name .')');
			
			// Redirect
			//header('Location: settings.php?confirm=1');
			header('Location: settings.php');
			exit;
		}
	//////////////
	
	//////////
	// Display settings in table
	
	// If data saved successfully, confirm
	if(isset($_GET['confirm']))
		println(dialog('Settings Updated', SUCCESS));//println('<div id="success" style="margin-left: 0;">Settings Updated</div>');	
	
	println('<form method="post" action="">');
	println('<table id="highlight">');
	println('<tr>');
	//println(' <th>ID</th>');
	println('<th>Name</th>');
	println('<th>Value</th>');
	println('<th>Comments</th>');
	println('</tr>');
	
	$sql->query("SELECT id,name,value,comments FROM settings ORDER BY id ASC");
	
	while($row = $sql->fetch_assoc())
	{
		$_id = $row['id'];
		$_name = $row['name'];
		$_value = $row['value'];
		$_comments = $row['comments'];
		
		println('<tr>');
		//println(' <td align="center">'. $row['id'] .'</td>');
		println('<td>'. $_name .'</td>');
		println('<td><input type="text" size="30" name="setting['. $_id .'][value]" value="'. $_value .'" /></td>');
		//println(' <td><textarea name="'. $row['name'] .'[comments]">'. $row['comments'] .'</textarea></td>');
		println('<td><input type="text" size="30" name="setting['. $_id .'][comments]" value="'. $_comments .'" /></td>');
		println('</tr>');
	}
	println('</table>');
	println('<p><input type="submit" name="save" value="Save Changes" /></p>');
	println('</form>');
	
	/////////
	
	println('<p>&nbsp;</p>');
	
	////////////
	// Add field form
	println('<form method="post" action="">');
	println('<fieldset>');
	println('<legend>Create New Variable</legend>');
	println('<table>');
	println('<tr>');
	println('<th align="right">Name:</th>');
	println('<td><input type="text" name="name" /></td>');
	println('</tr>');
	println('<tr>');
	println('<th align="right">Value:</th>');
	println('<td><input type="text" name="value" /></td>');
	println('</tr>');
	println('<tr>');
	println('<td colspan="2" align="right"><input type="submit" name="add" value="Add Variable" /></td>');
	println('</tr>');
	println('</table>');
	println('</fieldset>');
	println('</form>');
	
	//////////

	/////////////////////////////////////////
	// template footer
	require 'include/admin-footer.php';
	
?>