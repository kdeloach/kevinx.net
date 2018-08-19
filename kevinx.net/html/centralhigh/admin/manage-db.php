<?php

	// Admin - Manage DB
	// October 16, 2005 03:00:02 PM - created

	// template header
	require 'include/admin-header.php';
	
	// Verify logged in
	require ROOT.PATH_CMS.'include/auth.php';
	/////////////////////////////////////

	// Backup Database
	if(isset($_POST['backup']))
	{
		// Backup directory
		$backupdir = ROOT.PATH_BACKUP;
		
		$db = $sql->db;
		$user = $sql->user;
		$pass = $sql->pass;
		
		// Filename: db-20050113-240000.sql
		$file = $backupdir.$db.'-'.date('Ymd-His', time()).'.sql';
		
		exec("mysqldump -u \"$user\" --password=\"$pass\" $db", $output);
		
		$output = implode("\n", $output);
		
		// SAve file
		$fps = fopen($file, 'w');
		fwrite($fps, $output);
		fclose($fps);
		
		// Record Action
		accesslog('Database Saved');
		
		// Reload	
		header('Location: manage-db.php?confirm=1');
		exit;
	}
	// DELETE Backup
	else if(isset($_GET['delete']))
	{
		$file = $_GET['delete'];
		
		// Strip '.' and '..' so they can't delete arbitrary files
		$file = eregi_replace("^(\.\.)?(.{1,})$", '\2', $file);
		
		// Path of files
		$path = ROOT.PATH_BACKUP;
		
		// If not a file
		if(!file_exists($path.$file))
		{
			header('Location: manage-db.php?confirm=3');
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
		accesslog('Deleted Backup ('. $file .')');
		
		// Reload
		header('Location: manage-db.php?confirm=2');
		exit;
	}
	
	/////////////////////////////////////
	
	if(isset($_GET['confirm']) && $_GET['confirm'] == 1)
	{
		println(dialog('Database Saved', SUCCESS));	
	}
	else if(isset($_GET['confirm']) && $_GET['confirm'] == 2)
	{
		println(dialog('Backup Deleted', SUCCESS));	
	}
	else if(isset($_GET['confirm']) && $_GET['confirm'] == 3)
	{
		println(dialog('Invalid File', FAILURE));	
	}
	
	/////////////////////////////////////
	
	// Path to backup folder
	$dir = ROOT.PATH_BACKUP;
	$files = listdir($dir);
	
	if(!empty($files))
	{
		println('<table id="highlight">');
		println('<tr>');
		println('<th>Filename</th>');
		println('<th>Date Created</th>');
		println('<th>Size</th>');
		println('<th>&nbsp;</th>');
		println('</tr>');
		
		foreach($files as $file)
		{
			println('<tr>');
			println('<td>'. $file .'</td>');
			println('<td align="center">'. date(get_setting('date_format'), filemtime($dir.$file)) .'</td>');
			//println('<td><a href="manage-db.php?restore='. $file .'">Restore</a></td>');
			println('<td>'. floor(filesize($dir.'/'.$file)/1024) .' kb</td>');
			println('<td><a href="manage-db.php?delete='. $file .'">Delete</a></td>');
			println('</tr>');
		}
		
		println('</table>');
	}
		
	println('<form method="post" action="">');
	println('<p><input type="submit" name="backup" value="Backup Database" /></p>');
	println('</form>');

	/////////////////////////////////////////
	// template footer
	require 'include/admin-footer.php';
	
?>