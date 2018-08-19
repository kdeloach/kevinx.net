<?php

	// Admin - Edit News Page
	// October 04, 2005 10:56:49 PM - started
	// October 05, 2005 01:34:13 AM - finished first draft
	// October 05, 2005 02:13:54 AM - announcements show up in pages now
	// October 05, 2005 04:09:23 PM - added visibility (delete/recover) option
	// October 05, 2005 04:34:16 PM - can edit news now
	// October 10, 2005 04:39:12 PM - only news that user has posted will be visible now
	// October 12, 2005 07:04:11 PM - renamed file from edit-news.php TO manage-news.php
	// April 17, 2006 10:27:16 PM - No more recover/delete!!! Delete is permanent now

	// template header
	require 'include/admin-header.php';
	
	// Verify logged in
	require ROOT.PATH_CMS.'include/auth.php';
	
	/////////////////////////////////////
	
  $_start=(isset($_GET['start']))?$_GET['start']:0;
    
	// Archive news
	if(isset($_GET['archive']))
	{
		$_id = $_GET['archive'];	
		
		$sql->query("UPDATE news SET status=1 WHERE id='$_id'");
		
		// Record action
		accesslog('Archived Article ('. $_id .')');
		
		// Redirect
		header('Location: manage-news.php?start='. $_start .'&confirm=1');
		exit;
	}

	// Restore news
	if(isset($_GET['restore']))
	{
		$_id = $_GET['restore'];	
		
		$sql->query("UPDATE news SET status=0 WHERE id='$_id'");
		
		// Record action
		accesslog('Restored Article ('. $_id .')');
		
		// Redirect
		header('Location: manage-news.php?start='. $_start .'&confirm=3');
		exit;
	}

	// Delete news
	if(isset($_GET['delete']))
	{
		$_id = $_GET['delete'];
		
		$sql->query("DELETE FROM news WHERE id='$_id'");
		
		// Record action
		accesslog('Deleted Article ('. $_id .')');
		
		// Redirect
		header('Location: manage-news.php?start='. $_start .'&confirm=6');
		exit;
	}
	
	// If operation was successful, confirm
	if(isset($_GET['confirm'])) {
		switch($_GET['confirm']) {
			case 1:
			println(dialog('Article Archived', SUCCESS));
			break;
			case 6:
			println(dialog('Article Deleted', FAILURE));
			break;
			case 2:
			println(dialog('News Edited', SUCCESS));
			break;
			case 3:
			println(dialog('Article Restored', SUCCESS));
			break;
			case 5:
			println(dialog('News Posted', SUCCESS));
		}
	}
		
	////
	
	$res = $sql->query("SELECT MAX(id) as max,COUNT(id) as num FROM news");
	$tmp = $sql->fetch_assoc($res);
	$max = $tmp['max'];
	$num = $tmp['num'];
	
	$tmp = null;
	$res = null;
	
//	$_start = (isset($_GET['start']) ? addslashes($_GET['start']) : $max);
	$_limit = (isset($_GET['limit']) ? addslashes($_GET['limit']) : 12);
	
	$pages = ceil($num/$_limit);
	
	// Display all announcements
	println('<table id="highlight">');
	println('<tr>');
	println('<th>ID</th>');
	println('<th>Title</th>');
	println('<th>Author</th>');
	println('<th>Date</th>');
	println('<th>&nbsp;</th>');
	println('<th>&nbsp;</th>');
	println('</tr>');
	
	// *************************************************** //
	// TODO: if the user does not exist, wierd existential related problems occur //
	// where newsitems by that user are completely ommitted //
	// TEMPORARY?  all newsitems MUST have posters... //
	// *************************************************** //
	// Grab ALL news (cross-reference poster id to user id to get user name!)
	$sql->query(
		"SELECT news.id,title,timestamp,status,users.name".
		" FROM news,users".
		" WHERE users.id=news.poster".
		//" OR news.poster=0".
		//" AND news.id<='$_start'".
		//" AND poster='". get('id') ."'".
		" ORDER BY status ASC, timestamp DESC".
		" LIMIT $_limit".
		" OFFSET $_start"
	);
	
	$date_format = get_setting('date_format');
	
	// Iterate through news
	while($line = $sql->fetch_assoc())
	{
		$_id = $line['id'];
		$_title = $line['title'];
		$_poster = $line['name'];
		$_date = date($date_format, $line['timestamp']);
		$_status = $line['status'];
	
/*		if($_start == null)
			$_start = $_id;*/
			
		//println('<tr class="high">');
		println('<tr>');
		println('<td align="center">'. $_id .'</td>');
		println('<td>'. $_title .'</td>');
		println('<td>'. $_poster .'</td>');
		println('<td>'. $_date .'</td>');
		println('<td><a href="post-news.php?edit='. $_id .'&start='. $_start .'&limit='. $_limit .'">Edit</a></td>');
		if ($_status == 0) {
			println('<td><a href="manage-news.php?archive='. $_id .'&start='. $_start .'&limit='. $_limit .'">Archive</a></td>');
		} else {
			println('<td><a href="manage-news.php?restore='. $_id .'&start='. $_start .'&limit='. $_limit .'">Restore</a><br/><a href="manage-news.php?delete='. $_id .'&start='. $_start .'&limit='. $_limit .'">Delete</a></td>');
		}
		println('</tr>');
	}
	
	println('</table>');
	
	// Page links
	println('<p>Page ');
	
	for($i = 1; $i <= $pages; $i++)
	{
		//println(sprintf(' <a href="?start=%d">Page %d</a> ', $next, $i));	
	
/*		$sql->query(
			 "SELECT id"
			." FROM news"
//			." WHERE id<='$max'"
			." ORDER BY status ASC, timestamp DESC"
			." OFFSET $_start"
			." LIMIT $_limit"
		);
		
		$last = 0;
		while($tmp = $sql->fetch_assoc()) { $last = $tmp['id']; }
*/		
		//println(' '.$_start.' - '.$last.' ');
		
		if($_start == $num)
			$ex = ' style="border:1px solid #999;background-color:#ddd;padding:1px 5px;font-size:normal;text-decoration:none;"';
		else
			$ex = ' style="border:1px solid #999;background-color:#eee;padding:1px 3px;font-size:smaller;text-decoration:none;"';
			
		println(sprintf('<a href="manage-news.php?start=%d&limit='.$_limit.'"'.$ex.'>%d</a>', ($i-1)*$_limit, $i));
		
		if($i<$pages)
			println(', ');
		
//		$max = $i*$_limit;
	}
	
	println('</p>');
	
	
	////////////////////////
	// template footer
	require 'include/admin-footer.php';
	
?>
