<?php
include 'include/constants.php';
include 'include/dbconnect.php';

if(!empty($_GET['id'])){
  $id=addslashes($_GET['id']);

  $sql->query("SELECT type,ext FROM gallery WHERE id='$id' LIMIT 1");

  if($tmp=$sql->fetch_assoc()){
    $type=explode('/',$tmp['type']);
    $type=$type[1];

    $image=ROOT.PATH.'images/gallery/'.((file_exists(ROOT.PATH."images/gallery/$id"))?$id:$id.'.'.$tmp['ext']);
    
    eval("\$original=ImageCreateFrom$type('$image');");
    if(isset($original)){
      $thumb=ImageCreate(75,75);
      $x=ImageSX($original);
      $y=ImageSY($original);
      ImageCopyResized($thumb,$original,0,0,0,0,100,100,$x,$y);

      header('Content-type: image/'.strtolower($type));
      eval("Image$type(\$thumb);");
    }
  }
}

?>
