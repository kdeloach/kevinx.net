<?php

	// Admin File Manager
	// October 12, 2005 09:08:06 PM - created
	// October 15, 2005 02:35:27 PM - renames uploaded file if same file already exists
	// October 15, 2005 11:00:19 PM - doesn't upload invalid files anymore...
	// October 16, 2005 02:56:03 AM - upload repaired - wouldn't upload any files
	// October 16, 2005 07:48:50 PM - fixed bug where you couldn't upload files (should probably start testing my bug fixes...)

	// template header
	require 'include/admin-header.php';
	
	// Verify logged in
	require ROOT.PATH_CMS.'include/auth.php';
	/////////////////////////////////////

	// UPLOAD File(s)
	if(isset($_FILES['file']))
	{
		$name = strtolower($_FILES['file']['name'][0]);
		
		//print_r($_FILES['file']);
		//exit;
		
		// If not a real file
		if(eregi("^\.\.", $name) || ereg("(\*|\?|/|\|\\/)", $name))
		{
			header('Location: file-manager.php?confirm=3');
			exit;	
		}
		
		// Get extension
		$ext = substr($name, strpos($name, '.')+1, strlen($name)-strpos($name, '.'));
		// Get name without extension
		$name = substr($name, 0, strpos($name, '.'));
		
		$tmp_name = $_FILES['file']['tmp_name'][0];
		$path = ROOT.PATH_UPLOAD;
		
		$i = 1;
		$tmp = '';
		
		// Rename file if already exists
		while(file_exists($path.$name.$tmp.'.'.$ext))
		{
			$tmp = '-'. $i++;
		} 
		
		// New Destination
		$destination = $path.$name.$tmp.'.'.$ext;
		//echo $destination;
		//exit;
		// Move uploaded file
		move_uploaded_file($tmp_name, $destination) or die('error...');
		
		// Record action
		accesslog('Uploaded File ('.$name.$tmp.'.'.$ext .')');
		
		// Reload
		header('Location: file-manager.php?confirm=1&name='.$name.$tmp.'.'.$ext);
		exit;
	}
	// DELETE File
	else if(isset($_GET['delete']))
	{
		$file = $_GET['delete'];
		
		// Strip '.' and '..' so they can't delete arbitrary files
		$file = eregi_replace("^(\.\.)?(.{1,})$", '\2', $file);
		
		// Path of file
		$path = ROOT.PATH_UPLOAD.'/';
		
		// If not a file
		if(!file_exists($path.$file))
		{
			header('Location: file-manager.php?confirm=3');
			exit;	
		}
		
		// DELETE file
		$res = unlink($path.$file);
		
		if(!$res)
		{
			header('Location: file-manager.php?confirm=3');
			exit;	
		}
		
		// Record action
		accesslog('Deleted File ('. $file .')');
		
		// Reload
		header('Location: file-manager.php?confirm=2&name='.$file);
		exit;
	}
	
	//////////////////////////////////
	
	if(isset($_GET['confirm']) && $_GET['confirm'] == 1)
	{
		println(dialog('File Uploaded: <span style="color: #000;">'.htmlspecialchars($_GET['name'], ENT_QUOTES).'</span>', SUCCESS));
	}
	else if(isset($_GET['confirm']) && $_GET['confirm'] == 2)
	{
		println(dialog('File Deleted: <span style="color: #000;">'.htmlspecialchars($_GET['name'], ENT_QUOTES).'</span>', SUCCESS));
	}
	else if(isset($_GET['confirm']) && $_GET['confirm'] == 3)
	{
		println(dialog('Invalid File', FAILURE));
	}
	
	///////////////////////
	// List Files in Upload folder
	
	// Path to the upload folder
	$dir = ROOT.PATH_UPLOAD;
	// URL to upload folder
	$url = URL.PATH_UPLOAD;
	
	$files = listdir($dir);

	/////
	
	// Only output a table if there are files
	if(!empty($files))
	{
		println('<table id="highlight">');
		println('<tr>');
		println('<th>Filename</th>');
		println('<th>Last Modified</th>');
		println('<th>&nbsp;</th>');
		println('<th>&nbsp;</th>');
		println('</tr>');
		
		foreach($files as $file)
		{
			println('<tr>');
			println('<td>'. $file .'</td>');
			println('<td align="center">'. date(get_setting('date_format'), filemtime($dir.'/'.$file)) .'</td>');
			println('<td><a href="'. $url.$file .'">Link</a></td>');
			println('<td><a href="file-manager.php?delete='. $file .'">Delete</a></td>');
			println('</tr>');
		}
		
		println('</table>');
		
		println('<p>&nbsp;</p>');
	}
	
	/////////////////////
	
	
	//////////////////////
	// Upload File Form
	println('<fieldset>');
	println('<legend>File Upload</legend>');
	println('<form enctype="multipart/form-data" method="post" action="">');
	println('<input type="file" name="file[]" />');
	println('<input type="submit" name="upload" value="Upload" />');
	println('</form>');
	println('</fieldset>');

	/////////////////////////////////////////
	// template footer
	require 'include/admin-footer.php';
	
?>