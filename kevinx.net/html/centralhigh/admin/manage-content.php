<?php
// this script doesn't work!@#$$#@
// RTE won't return the text you TYPE in the textarea
// we'll have to abandon it for our own (or something better)

include 'include/admin-header.php';
include 'include/auth.php';
  $_dir = (!isset($_GET['dir']) || empty($_GET['dir']) ? 'root' : $_GET['dir']);
  $_id = (!isset($_GET['id']) || empty($_GET['id']) ? 'null' : $_GET['id']);
  $_act = (!isset($_GET['act']) || empty($_GET['act']) ? 'null' : $_GET['act']);
		    
		    
			// edit page - ///////////////////////////////


  if($_act == 'edt'){
		// if form was submitted
    if(isset($_POST['save'])){
      $_id = $_POST['id'];
	  $_name = addslashes($_POST['name']);
	  //echo $_POST['content'];
	  $_content = addslashes($_POST['content']);
	  //echo $_content;
	  $sql->query("UPDATE cms_pages SET belongsTo='".$_POST['belongsTo']."', name='$_name', last_mod=NOW(), content='$_content' WHERE id='$_id'");
	  header('Location: manage-content.php?p='. $_page .'&dir='. $_POST['belongsTo']);
	  exit;
	}else{
	  $sql->query("SELECT * FROM cms_pages WHERE id='". $_id ."'");
	  $row = $sql->fetch_assoc();
?>
<h2>Edit Page</h2>
  <table cellpadding="4" cellspacing="1" style="border: none;">
    <tbody>
      <form method="post" action="manage-content.php?p=cm&act=edt" onSubmit="return submitForm();">
		<input type="hidden" name="id" value="<?=$row['id']?>">
		  <tr>
		     <td style="text-align: right; font-weight: bold;">Owner:</td>
		     <td><?php echo cm_select_menu($sql, $row['belongsTo'], $row['id']);?></td>
	       </tr>
		   <tr>
			 <td style="text-align: right; font-weight: bold;">Title:</td>
		     <td><input type="text" name="name" size="55" value="<?php echo $row['name']?>" /></td>
           </tr>
		   <tr>
			 <td valign="top" style="text-align: right; font-weight: bold;">Content:</td>
	         <td><script type="text/javascript">
                 <!--
                   writeRichText('content','<?php echo preg_replace('/(\r|\n)/',"\\n",addslashes($row['content']));?>', 400, 200, true, false);
                 -->
               </script>

             </td>
		   </tr>
	       <tr>
			 <td colspan="2" style="text-align: right;">
			   <input type="submit" name="save" value="Save" />
			   <input type="button" value="Cancel" onClick="history.back()">
		     </td>
		   </tr>
	     </form>
       </tbody>
	 </table>
<?php
    }


  }else if($_act == 'mov'){
    $_d = (!isset($_GET['d']) || ($_GET['d']!='u' && $_GET['d']!='d') ? 'u' : htmlspecialchars($_GET['d']));
	$_id = (!isset($_GET['id']) || empty($_GET['id']) ? 'null' : htmlspecialchars($_GET['id']));

    $_d = ($_d == 'd' ? 1 : -1);

	$sql->query("SELECT rank,belongsTo FROM cms_pages WHERE id='$_id' LIMIT 1");
	$row_1 = $sql->fetch_assoc();

	if ( empty($row_1) || $row_1['belongsTo'] != 'root' ){
	  echo '<h2>Error!</h2>';
	  echo '<p>Sorry, you can only change the rank of "root" pages.</p>';
    }else{
	  $target = $row_1['rank']+$_d;

      $sql->query("SELECT id,rank FROM cms_pages WHERE rank='$target' LIMIT 1");
      $row_2 = $sql->fetch_assoc();

      if ( !empty($row_2) ){
        $id_1 = $_id;
		$id_2 = $row_2['id'];
		$rank_1 = $row_1['rank'];
		$rank_2 = $row_2['rank'];

		$sql->query("UPDATE cms_pages SET rank='-1' WHERE id='$id_1' LIMIT 1");
		$sql->query("UPDATE cms_pages SET rank='$rank_1' WHERE id='$id_2' LIMIT 1");
		$sql->query("UPDATE cms_pages SET rank='$rank_2' WHERE id='$id_1' LIMIT 1");
      }
      header('Location: handle-content.php?p=cm');
      exit;
	}
  }else if($_act == 'del'){
	if(isset($_POST['verify'])){
	  $_id = $_POST['id'];

      $sql->query("DELETE FROM cms_pages WHERE id='". $_id ."' LIMIT 1");
	  header('Location: manage-content.php?p='. $_page .'&dir='. $_POST['belongsTo']);
	  exit;
	}else{
      $sql->query("SELECT id, name, belongsTo FROM cms_pages WHERE id='". $_id ."'");
	  $row = $sql->fetch_assoc();

      if(empty($row['title']))
		$row['title'] = '(No Title)';

    	// see if it has children
		$sql->query("SELECT id FROM cms_pages WHERE belongsTo='$row[id]'");

		echo '<h2>Delete Page</h2>';

		if($sql->num_rows() > 0){
		  echo '<p>Please delete all child pages of <strong>'. htmlspecialchars($row['name']) .'</strong> first.</p>';
		}else{
		  echo ' <form method="post" action="manage-content.php?p=cm&act=del">';
		  echo ' <input type="hidden" name="id" value="'. $row['id'] .'">';
		  echo ' <input type="hidden" name="belongsTo" value="'. $row['belongsTo'] .'">';
		  echo ' <p>Are you sure you wish to delete <strong>'. htmlspecialchars($row['name']) .'</strong>?</p>';
		  echo ' <p>';
	      echo '  <input type="submit" name="verify" value="Yes">';
		  echo '  <input type="button" value="No" onClick="history.back()">';
		  echo ' </p>';
		}
      }
	}else if($_act == 'add'){
	  if(isset($_POST['save'])){
	    $rank = 'NULL';
		$_name = addslashes($_POST['name']);
		$_content = addslashes($_POST['content']);


		if ( $_POST['belongsTo'] == 'root' ){
		  $sql->query("SELECT rank FROM cms_pages ORDER BY rank DESC");
		  $arr = $sql->fetch_assoc();

          $highest_rank = $arr['rank'];

          $rank = $highest_rank+1;
		}

    	$sql->query("INSERT INTO cms_pages (belongsTo, name, content, date_created, last_mod, rank) VALUES('$_POST[belongsTo]', '$_name', '$_content', NOW(), NOW(), '$rank')");
					header('Location: manage-content.php?p='. $_page .'&dir='. $_POST['belongsTo']);
					exit;
      }else{
		echo '<h2>New Page</h2>';
		echo '<table cellpadding="4" cellspacing="1" style="border: none;">';
		echo ' <tbody>';
		echo '  <form method="post" action="manage-content.php?p=cm&act=add" onSubmit="return submitForm();">';
		echo '  <tr>';
		echo '   <td style="text-align: right; font-weight: bold;">Owner:</td>';
		echo '   <td>'. cm_select_menu($sql, $_dir) .'</td>';
		echo '  </tr>';
		echo '  <tr>';
		echo '   <td style="text-align: right; font-weight: bold;">Title:</td>';
		echo '   <td><input type="text" name="name" size="55" /></td>';
		echo '  </tr>';
		echo '  <tr>';
		echo '   <td valign="top" style="text-align: right; font-weight: bold;">Content:</td>';
		echo '   <td>';
        echo "     <script type=\"text/javascript\">\n";
        echo "        <!--\n";
        echo "          writeRichText('content', '', 400, 200, true, false);\n";
        echo "        //-->\n";
        echo "        </script>";
        echo '</td>';
		echo '  </tr>';
		echo '  <tr>';
		echo '   <td colspan="2" style="text-align: right;">';
		echo '    <input type="submit" name="save" value="Save" />';
		echo '    <input type="button" value="Cancel" onClick="history.back()">';
		echo '   </td>';
		echo '  </tr>';
		echo '  </form>';
		echo ' </tbody>';
		echo '</table>';
      }
	}else{
	  echo '<h2>Content Manager</h2>';

      $curdir = $_dir;
	  $tree = array();

      $roots = array('root', 'news');

      while(!in_array($curdir, $roots)){
        $sql->query("SELECT id,name,belongsTo,date_created,last_mod FROM cms_pages WHERE id='". htmlspecialchars($curdir) ."' LIMIT 1");
        $arr = $sql->fetch_assoc();

		$tree[] = '<a href="manage-content.php?p=cm&dir='. $arr['id'] .'">'. $arr['name'] .'</a>';
        $curdir = $arr['belongsTo'];
      }

      $tree[] = '[<a href="manage-content.php?p=cm&dir=root">root</a> | <a href="manage-content.php?p=cm&dir=news">news</a>]';
      $tree = array_reverse($tree);

      echo implode($tree, ' &#062; ');
	  echo '<p>Welcome, care to <a href="manage-content.php?p=cm&act=add&dir='. htmlspecialchars($_dir) .'">add a page</a>?</p>';
	  echo '<table style="width: 100%;" border="0" cellpadding="4" id="highlight" cellspacing="0">';
	  echo ' <thead>';
#	  echo '  <th>&nbsp</th>';
	  echo '  <th>&nbsp</th>';
	  echo '  <th>Title</th>';
	  echo '  <th>Date Created</th>';
	  echo '  <th>Last Modified</th>';
	  echo '  <th>&nbsp</th>';
	  echo '  <th>&nbsp</th>';
	  echo ' </thead>';

      $sql->query("SELECT id,name,date_created,last_mod FROM cms_pages WHERE belongsTo='$_dir' ORDER BY rank ASC");

				////////
      $queries = array();

      while($row = $sql->fetch_assoc())
        $queries[] = $row;

				////////

      $i = 0;

				//while($row = $sql->fetch_assoc())
      foreach($queries as $row){
        $sql->query("SELECT id FROM cms_pages WHERE belongsTo='$row[id]'");

        if($sql->num_rows() > 0)
	  	  $title = '<a href="manage-content.php?p=cm&dir='. $row['id'] .'">'. htmlspecialchars($row['name']) .'</a>';
        else
		  $title = htmlspecialchars($row['name']);
		  
        echo '<tr>';
        #echo '  <tr><td></td>';
        #echo '   <td align="center" width="22"><a href="manage-content.php?p=cm&act=mov&d=u&id='. $row['id'] .'"><img src="images/arrow_asc.png" /></a><a href="manage-content.php?p=cm&act=mov&d=d&id='. $row['id'] .'"><img src="images/arrow_desc.png" /></a></td>';
		echo '   <td align="center">'. $row['id'] .'</td>';
		echo '   <td width="50%">'. $title .'</td>';
		echo '   <td align="center">'. date('m/d/Y', strtotime($row['date_created'])) .'</td>';
		echo '   <td align="center">'. date('m/d/Y', strtotime($row['last_mod'])) .'</td>';
		echo '   <td align="center" width="16"><a href="manage-content.php?p=cm&act=edt&id='. $row['id'] .'">Edit</a></td>';
		echo '   <td align="center" width="16"><a href="manage-content.php?p=cm&act=del&id='. $row['id'] .'">Delete</a></td>';
		echo '  </tr>';

      }

  	  echo ' </tbody>';
	  echo '</table>';
	}
			
include 'include/admin-footer.php';

function cm_select_menu_rec($sql, $owner, $selected, $disabled_branch, $l=1, $i2=0)
{
	$mysql = new Mysql;
	$mysql->lnk = $sql->lnk;

	$output = '';

	$mysql->query("SELECT id, name FROM cms_pages WHERE belongsTo='$owner'") or die(mysql_error());

	while($row = $mysql->fetch_assoc())
	{
		if(empty($row['name'])) $row['name'] = '';

		$output .= '<option value="'. $row['id'].'"';

		if($row['id'] == $selected)
			$output .= ' selected="selected"';

		if($row['id'] == $disabled_branch || $disabled_branch == -1)
			$output .= ' disabled="disabled"';

		$output .= '>';

		for($i = 0; $i < $l; $i++)
		{
			$output .= '&#149; ';
        }
		$output .= $row['id'] .'. '. htmlspecialchars($row['name']);

		$output .= '</option>';

		//////////

		$tmp = new Mysql;
		$tmp->lnk = $mysql->lnk;

		$tmp->query("SELECT id FROM cms_pages WHERE belongsTo='$row[id]'") or die(mysql_error());

		if($tmp->num_rows() > 0)
			$output .= cm_select_menu_rec($sql, $row['id'], $selected, (($row['id'] == $disabled_branch || $disabled_branch == -1) ? -1 : $disabled_branch), $l+1);
	}

	return $output;
}
function cm_select_menu($sql, $selected='', $disabled_branch='')
{
	$output  = '<select name="belongsTo">';
	$output .= '<option value="news"'.($selected == 'news' ? ' selected="selected"' : '').'>news</option>';
	$output .= cm_select_menu_rec($sql, 'news', $selected, $disabled_branch);
	$output .= '<option value="root"'.($selected == 'root' ? ' selected="selected"' : '').'>root</option>';
	$output .= cm_select_menu_rec($sql, 'root', $selected, $disabled_branch);
	$output .= '</select>';

	return $output;
}
?>
