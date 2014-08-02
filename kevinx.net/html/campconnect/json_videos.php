<?php

  require 'inc/config.php';
  require 'inc/dbconnect.php';

  // lets get all the images from the db, put in an array and json encode the output
  
  $arr=array();
  
  $res=$db->query("select id,filename,date,(select count(*) from vtags where vtags.pic_id=videos.id) as tagqt,description from videos order by date DESC");
  
  while(list($id,$filename,$date,$tagqt,$desc)=$db->result($res)){
    $tags=array();
    if($tagqt>0){
      $res2=$db->query("select tag from vtags where pic_id=$id"); 
      while(list($tag)=$db->result($res2))
        $tags[]=$tag;
    }
    $arr[]=array($id,$filename,strtotime($date),$tags,$desc,date('m/d/y',strtotime($date)));
  }
    
  //print_r($arr);
  echo json_encode($arr);
  
?>