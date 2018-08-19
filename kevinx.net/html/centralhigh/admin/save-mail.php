<?php

include '../include/constants.php';
include 'include/dbconnect.php';
  
$sql->select_db('centralhigh');

if(!empty($_POST)){
  $ref=$_SERVER['HTTP_REFERER'];
  $ref=explode('?',$ref."?");
  $ref=str_replace('www.','',$ref[0]);
  if($ref!=='http://centralhigh.net/beta/admin/compose-mail.php')continue;

  $p=$_POST;

  $p['content']=addslashes($p['content']);
  $p['subject']=addslashes($p['subject']);
  $groups='';
  if(!empty($p['groups'])){
    foreach($p['groups'] as $id=>$val){
      if($val==true)$groups.="$id, ";
    }
  }
  $now=time();

  if(isset($p['id'])){
    $sql->query("UPDATE `mailing_list_email` SET subject='$p[subject]',body='$p[content]',recipients='$groups',lastmod='$now'");
    $id=$p['id'];
  }else{
    $sql->query("INSERT INTO mailing_list_email(id,created,recipients,subject,body,lastmod) VALUES('',$now,'$groups','$p[subject]','$p[content]',$now)");
    $id=$sql->insert_id();
  }

  header("Location: compose-mail.php?mid=$id&done=1");
  exit;
}

header("Location: compose-mail.php");
exit;

?>
