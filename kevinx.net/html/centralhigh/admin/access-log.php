<?php

	// Admin - Access Log
	// September 30, 2005 07:53:24 PM - started
	// September 30, 2005 09:49:06 PM - finished first draft
	// October 05, 2005 04:37:57 PM - had a party
	// October 22, 2005 01:09:00 PM - can now search through everything but the date

	// template header
	require 'include/admin-header.php';
	
	// Verify logged in
	require ROOT.PATH_CMS.'include/auth.php';
	/////////////////////////////////////

	//////////////
	// Optionally, filter by something
	$_query = '';
	
	if(isset($_GET['search']))
		$_query = addslashes($_GET['search']);
		
	/////////////
		
	$_l = 15;
	
	println('<p>Limited to <strong>'. $_l .'</strong> results due to long loading times!</p>');
	
	println('<form method="get" action="">');
	println('<input type="text" name="search" />');
	println('<input type="submit" value="Search" />');
	println('</form>');
	
	//////////
	// Display settings in table
	
	$sql->query(
		 "SELECT access_log.user_id,access_log.page,access_log.action,access_log.time,users.name"
		." FROM access_log,users"
		." WHERE (users.name LIKE '$_query'"
		." OR page LIKE '%$_query%'"
		." OR action LIKE '%$_query%')"
		." AND users.id=access_log.user_id"
		." ORDER BY time DESC"
		. (!isset($_GET['search']) ? " LIMIT $_l" : '')
	);
	
	if($sql->num_rows() > 0)
	{
		println('<table id="highlight">');
		println('<tr>');
		println('<th>User</th>');
		println('<th>Page</th>');
		println('<th>Action</th>');
		println('<th>Date</th>');
		println('</tr>');
		
		// Display actions, newer ones at the top
		
		$date_format = get_setting('date_format');
		
		while($row = $sql->fetch_assoc())
		{
			$_user = $row['name'];
			$_page = $row['page'];
			// Remove path
			$_page = str_replace('/'.PATH_CMS, '', $_page);
			// Remove query string
			$_page = eregi_replace('^(.{1,}\.php)(\?.{1,})?$', '\1', $_page);
			$_action = $row['action'];
			$_time = date($date_format, $row['time']);
			
			println('<tr>');
			println('<td>'. $_user .'</td>');
			println('<td>'. $_page .'</td>');
			println('<td>'. $_action .'</td>');
			println('<td>'. $_time .'</td>');
			println('</tr>');
		}
		
		println('</table>');
		
		println('<p><strong>'. $sql->num_rows() .'</strong> Result(s)</p>');
	}
	else
	{
		println('<p>(No Results)</p>');
	}
	
	/////////

	/////////////////////////////////////////
	// template footer
	require 'include/admin-footer.php';
	
?>