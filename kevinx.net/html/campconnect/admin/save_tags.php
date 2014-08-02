<?php

  require '../inc/config.php';
  require $config['path'].'/inc/dbconnect.php';

  $units=isset($_GET['u']) ? $_GET['u'] : array();
  $activities=isset($_GET['a']) ? $_GET['a'] : array();
  $idz=isset($_GET['i']) ? $_GET['i'] : array();
  
  $db->query(" TRUNCATE TABLE `tags`  ");
  
  $i=0;
  foreach($idz as $id){
    $id=mysql_escape_string($id); 
    $units[$i]=explode(',',$units[$i]);
    $activities[$i]=explode(',',$activities[$i]);
    
    foreach($units[$i] as $u){
      $u=mysql_escape_string($u);
      if(empty($u)) continue;
      $hash=md5($id.$u.'Unit');
      $db->query(" insert ignore into tags (pic_id,type,tag,hash) values('$id','Unit','$u','$hash') ");
    }
    foreach($activities[$i] as $u){
      $u=mysql_escape_string($u);
      if(empty($u)) continue;
      $hash=md5($id.$u.'Activities');
      $db->query(" insert ignore into tags (pic_id,type,tag,hash) values('$id','Activities','$u','$hash') ");
    }
    $i++;
  }

  echo 1;
    
?>