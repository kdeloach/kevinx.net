<?php

  require $config['path'].'/inc/dbconnect.php';
  
  ////////
  session_start();
  
  
  $pics=array();
  function GetLatestPics($n=10){
    global $db,$pics;
    
    $pics=array();
    $db->query("select id,filename,date from pictures order by date DESC limit $n");
    while(list($id,$filename,$date)=$db->result()){
      $pics[$id]=new structImage($id,$filename,$date);
    }
    $pics=array_reverse($pics);
    
    return $pics;
  }
  function GetAllPics(){
    global $db;
    
    $pics=array();
    $db->query("select id,filename,date from pictures order by date ASC");
    while(list($id,$filename,$date)=$db->result()){
      $pics[$id]=new structImage($id,$filename,$date);
    }
    
    return $pics;
  }
  
  function nextpic(&$arr=null){
    global $pics;
    
    if(empty($arr))
      $arr=&$pics;
    if(empty($arr))
      return MissingImageStruct();
    
    return array_pop($arr);
  }

  function MissingImageStruct(){ // create obj only when needed
    return new structImage('0','missingo.jpg',time());
  }
  
  function PrintNextPic($thumb=true,&$arr=null){
    $img=nextpic($arr);
    
    echo sprintf('<a href="photos.php?l=%2$s"><img src="img_152x152_%2$s" width="152" height="152" alt="Image" title="Added on %3$s" /></a>', 
      $img->id, 
      $img->filename,//$thumb ? $img->thumb : $img->filename, 
      date('m/d/y',$img->time)
    );
  }
  
  function getTitle(){
    global $db;
    $data=array();
    $db->query("select `key`,`value` from `info`");
    while(list($k,$v)=$db->result())
      $data[$k]=$v;
      
    return $data['title'];
  }
  

  
  function require_login(){
    // if user not loggedin -> send them to Login.php
    // save referrer uri in session
    
    // if time()-session[loginTime]>=config[cookie_expiration] destroy session
  }
  
  
  class structImage {
     var $id;
     var $filename;
     var $thumb;
     var $time;
     function structImage($id,$filename,$date){
       $this->id=$id;
       $this->filename=$filename;
       $this->thumb='thumbs/t_'.$filename;
       $this->time=strtotime($date);
     }
  }
  
?>