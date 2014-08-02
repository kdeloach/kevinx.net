<?php

  require '../inc/config.php';
  require $config['path'].'/inc/dbconnect.php';

  // lets get all the images from the db, put in an array and json encode the output
  
  $arr=array();
  
  $src=isset($_GET['src']) ? stripslashes($_GET['src']) : null;
  if(!$src || !file_exists($config['upload_path'].'/'.$src))
    die('Could not delete '.$src);
  $src=mysql_escape_string($src);
  
  $db->query("delete from pictures where filename='$src'");
  
  $res=unlink( $config['upload_path'] . '/' . $src );
  
  if(!$res)
    die('Another process was using '+$src+'; Please delete it manually from your upload folder');
    
  die($filename+' was deleted!');
  
?>