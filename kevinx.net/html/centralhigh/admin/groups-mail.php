<?php
    //header
	require 'include/admin-header.php';
	require ROOT.PATH_CMS.'include/auth.php';

///HANDLE INPUT///
if($_POST){
  if(!empty($_POST['groups'])){
    foreach($_POST['groups'] as $id=>$val){
	  $short=strtolower(str_replace(' ','',$val['grp']));
      if(empty($val['grp'])){
        $sql->query("SELECT col FROM mailing_list_groups WHERE id='$id'");
        $col=$sql->fetch_assoc();
        $col=$col['col'];
        $sql->query("DELETE FROM mailing_list_groups WHERE id='$id'");
        $sql->query("ALTER TABLE mailing_list DROP $col");
}else if(!preg_match('/\W/',$short)){
        $sql->query("SELECT col FROM mailing_list_groups WHERE id='$id'");
        $col=$sql->fetch_assoc();
        $col=$col['col'];
        $sql->query("UPDATE mailing_list_groups SET grp='$val[grp]',col='$short' WHERE id='$id'");
        $sql->query("ALTER TABLE mailing_list CHANGE $col $short ENUM('0','1') NOT NULL DEFAULT '0'");
      }
    }
  }
  if(!empty($_POST['add'])){
    $tag=strtolower(str_replace(' ','',$_POST['add']));
    if(!preg_match('/\W/',$tag)){
      $sql->query("INSERT INTO mailing_list_groups VALUES('','$_POST[add]','$tag')");
      $sql->query("ALTER TABLE mailing_list ADD $tag ENUM('0','1') NOT NULL DEFAULT '0'");
    }
  }
  header("Location: $_SERVER[PHP_SELF]");
}
///END HANDLE///
///DISPLAY CURRENT DATA FOR EDITING///
  $sql->query('SELECT * FROM mailing_list_groups ORDER BY grp');
  
  echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">\n";
  echo "  <table id=\"highlight\" cellpadding=\"5\">";
  echo "    <tr><th>Name</td><th>Col</th><th>View</th></tr>\n";
  while($tmp=$sql->fetch_assoc()){
    echo "    <tr><td><input type=\"text\" name=\"groups[$tmp[id]][grp]\" value=\"$tmp[grp]\" size=\"10\" /></td><td>$tmp[col]</td>\n";
    echo "      <td><input type=\"button\" onClick=\"window.open('viewgroup-mail.php?gid=$tmp[id]','$tmp[grp]','height=400,width=300');\" value=\"X\" /></td></tr>\n";
  }
  echo "  </table>\n";
  echo "<input type=\"submit\" value=\"Update\" />\n";
  echo "</form>";
  
  echo "<form method=\"POST\">";
  echo "  <strong>Add group:</strong><input type=\"text\" name=\"add\" size=\"10\" /><br /><br />\n";
  echo "  <input type=\"submit\" value=\"Create\" />\n";
  echo "</form>";
///END DISPLAY///


	//footer
	include 'include/admin-footer.php';
?>
