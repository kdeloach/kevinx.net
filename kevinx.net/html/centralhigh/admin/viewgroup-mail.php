<?php
include 'include/constants.php';
include 'include/dbconnect.php';

@$ref=$_SERVER['HTTP_REFERER'];
if(!preg_match('/admin\/groups-mail\.php/',$ref))exit;
if(isset($_GET['gid'])&&@is_numeric($_GET['gid'])){
  $sql->query("SELECT col,grp FROM mailing_list_groups WHERE id='$_GET[gid]' LIMIT 1");

  if($tmp=$sql->fetch_assoc()){
    $group=$tmp['grp'];
    $col=$tmp['col'];

    $sql->query("SELECT email FROM mailing_list WHERE $col='1'");

    echo "<table>\n";
    echo "<tr><th>$group</th></tr>\n";
    
    while($tmp=$sql->fetch_assoc()){
      echo "<tr><td>$tmp[email]</td></tr>\n";
    }

    echo "</table>";
  }
}
