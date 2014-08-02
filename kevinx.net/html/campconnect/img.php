<?php
  
  include 'inc/config.php';
  ////
  
  $SaveCachedCopy=false;
  $ImageQuality = 60;
  
  ////

  $imgname=null;
  $w=null;
  $h=null;
  $zoom=false;
  
  if(!isset($_GET['src']))
    exit;
  else
    $imgname=stripslashes($_GET['src']); 

  if(isset($_GET['w']) && is_numeric($_GET['w'])){
    $w = $_GET['w'];}
  if(isset($_GET['h']) && is_numeric($_GET['h']))
    $h = $_GET['h'];
  
  if(isset($_GET['q']) && is_numeric($_GET['q']))
    $ImageQuality=$_GET['q'];
  if(isset($_GET['z']))
    $zoom=(bool)$_GET['z'];
    
  ///
  
  $cachefilename = $config['upload_path'] . '/cache/' . md5($_SERVER['QUERY_STRING']) . '.jpg';
  
  if($SaveCachedCopy && file_exists($cachefilename)) {
    $src = imagecreatefromjpeg( $cachefilename );
    list($width,$height,$type) = getimagesize( $cachefilename );
    header("Content-type: $type");
    //header("Expires: " . gmdate("D, d M Y H:i:s", time() + 60*60*24*7) . " GMT");  //expires in a week
    imagejpeg($src);
    exit;
  }
  
  if(!file_exists( $config['upload_path'] .'/'. $imgname ))
    die('Cannot find '. $config['upload_path'] .'/'. $imgname);
  
  $src = imagecreatefromjpeg( $config['upload_path'] .'/'. $imgname );
  list($width,$height,$type) = getimagesize( $config['upload_path'] .'/'. $imgname );
  $ext = substr($imgname, strrpos($imgname, '.'));

  $newwidth=$w;
  $newheight=$h;
  $offsety=0;$offsetx=0;
    
  if($zoom){
    
    if($newheight > $newwidth){
       $scaledwidth=round(($width/$height)*$newheight);
       $offsetx=round(abs($scaledwidth-$newwidth)/2);
    } else {
       $scaledheight=round(($height/$width)*$newwidth);
       $offsety=round(abs($scaledheight-$newheight)/2);
    }

  }
  
  $tmp=imagecreatetruecolor($newwidth,$newheight);
  imagecopyresampled($tmp,$src,0,0,$offsetx,$offsety,$newwidth,$newheight,$width-$offsetx*2,$height-$offsety*2);
  
  $filename = $config['upload_path'] . '/thumbs/t_' . $imgname;
  
  header("Content-type: $type");
  header("Expires: " . gmdate("D, d M Y H:i:s", time() + 60*60*24*7) . " GMT");  //expires in a week
  // cache img
  if($SaveCachedCopy)
    imagejpeg($tmp, $cachefilename, $ImageQuality);
  // output img
  imagejpeg($tmp,null, $ImageQuality);
  
  imagedestroy($src);
  imagedestroy($tmp);

?>