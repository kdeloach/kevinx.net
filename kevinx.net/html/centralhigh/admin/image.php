<?php

  include 'include/constants.php';
  include 'include/dbconnect.php';
  
  if(isset($_GET['id'])){
    if(is_numeric($_GET['id'])){
      $id=$_GET['id'];
      $sql->query("SELECT type FROM gallery WHERE id='$id'");
    }
  }
  $stump=ROOT.PATH."images/gallery/$id";
  if(@$tmp=$sql->fetch_assoc()&&file_exists($stump)){
    echo header("Content-type: $tmp[type]");
    echo file_get_contents($stump);
  }else{
    header("Content-type: image/jpeg");
    $img=ImageCreate(200,25);
    $bg=ImageColorAllocate($img,255,255,255);
    $color=ImageColorAllocate($img,0,0,0);
    ImageString($img,3,0,0,'file does not exist',$color);
    ImageJPEG($img);
  }
