<?php

	// Admin - Post news
	// October 04, 2005 08:34:42 PM - finished first draft
	// October 05, 2005 04:33:51 PM - updated to have ability to edit news
	// October 10, 2005 04:47:04 PM - only original authors may edit their articles now

	// template header
	require 'include/admin-header.php';
	
	// Verify logged in
	require ROOT.PATH_CMS.'include/auth.php';
	/////////////////////////////////////
	
	// Save announcement
	if(isset($_POST['save']))
	{
		// Parse input
		$_title = htmlspecialchars($_POST['title'], ENT_QUOTES);
		// TODO: wtf kind of filtering is this?  strip out the single quotes????
		$_content = str_replace('\'', '&#039;', trim($_POST['content']));
		$_time = time();
		$_poster = getid();
		$_id = !empty($_POST['id']) ? $_POST['id'] : null;
		
		// Save...
		if(empty($_id))
		{
			$sql->query("INSERT INTO news (title,content,timestamp,poster) VALUES('$_title','$_content','$_time','$_poster')");
			
			// Record action
			accesslog('Posted Article ('. $sql->insert_id() .')');
			
			// Redirect
			header('Location: post-news.php?confirm=1');
			exit;	
		}
		// OR Edit
		else
		{
			// Get original poster
			//$sql->query("SELECT id FROM news WHERE id='$_id' AND poster='$_poster'");
			//$tmp = $sql->fetch_assoc();
			
			///print_r($tmp);
			//exit;
			
			// If there is an author mis-match
			/*if(empty($tmp))
			{
				// Record action
				accesslog('Error ('. $_id .')');
				
				// Redirect
				header('Location: manage-news.php?confirm=4');
				exit;
			}
			// Authors match, save the article
			else
			{*/
				$sql->query("UPDATE news SET title='$_title',content='$_content' WHERE id='$_id'");// AND poster='$_poster'");
				
				// Record action
				accesslog('Edited Article ('. $_id .')');
				
				// Redirect
				header('Location: manage-news.php?'.$_SERVER['QUERY_STRING'].'&confirm=2');
				exit;
			//}
		}
	}
	
	// If news saved successful, confirm
	if(isset($_GET['confirm']) && $_GET['confirm'] == 1)
	{
		header('Location: manage-news.php?confirm=5');
		exit;
	}
	// If news does not exist to edit
	else if(isset($_GET['confirm']) && $_GET['confirm'] == 3)
		println(dialog('Article Non-existent', FAILURE));
	// If someone else besides the author tries to edit article
	else if(isset($_GET['confirm']) && $_GET['confirm'] == 4)
		println(dialog('Error', FAILURE));
	else
	{
		$_id = (isset($_GET['edit']) ? htmlspecialchars($_GET['edit']) : '');
		$_title = '';
		$_content = '';
		$_time = time();
		
		if(!empty($_id))
		{
			$sql->query("SELECT id,title,content,timestamp FROM news WHERE id='$_id'");
			$tmp = $sql->fetch_assoc();
			
			if(empty($tmp))
			{
				header('Location: post-news.php?confirm=3');
				exit;
			}
				
			$_title = $tmp['title'];
			$_content = str_replace(array("\n","\r"), array('',''), $tmp['content']);
			$_time = $tmp['timestamp'];
		}
		
		@$action_str = isset($_GET['edit']) ? "?start=$_&limit=" : '';
		
		// Post news form
		println('<form method="post" action="" onsubmit="return submitForm();">');
		println('<input type="hidden" name="id" value="'. $_id .'" />');
		println('<table cellpadding="5">');
		println('<tr>');
		println('<td>Title:</td>');
		println('<td><input type="text" size="30" name="title" value="'.$_title.'"></td>');
		println('</tr>');
		println('<tr>');
		println('<td>Date:</td>');
		println('<td>'. date(get_setting('date_format'), $_time) .'</td>');
		println('</tr>');
		println('<tr>');
		println('<td valign="top">News:</td>');
		println('<td>');
		
			// Rich text editor
			println('<script type="text/javascript">');
			println('<!--');
			//Usage: writeRichText(fieldname, html, width, height, buttons, readOnly)
			println('writeRichText(\'content\', \''.preg_replace("/(\n|\r)/",'',addslashes($_content)) .'\', 400, 200, true, false);');
			println('//-->');
			println('</script>');
		
		println('</td>');
		println('</tr>');
		println('<tr>');
		println('<td colspan="2" align="right">');
		//println('<input type="button" name="preview" value="Preview" onclick="previewHTML(this.form);" />');
		println('<input type="submit" name="save" value="Publish News" />');
		println('</td>');
		println('</tr>');
		println('</table>');
		println('</form>');
	}
	
	////////////////////////
	// template footer
	require 'include/admin-footer.php';
	
?>
