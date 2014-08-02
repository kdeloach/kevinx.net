<?php

  require '../inc/config.php';
  require '../inc/lib.php'; 
  
  set_time_limit(0);
  error_reporting(0);
  
  define('RED','red');
  define('BLACK','black');
  define('GREEN','green');
  
  $accepted_types = array(
    'image/jpeg',
    'image/pjpeg',
  );
  
  if(isset($_FILES['pics'])){
    
    for($i=0,$c=sizeof($_FILES['pics']['name']);$i<$c;$i++){
      $insert=true;
      echo '<div style="float:left;padding:5px;margin:5px;background-color:#f0f0f0;">';
      
      list($name, $type, $tmp_name, $err, $size)=array(
        $_FILES['pics']['name'][$i],
        $_FILES['pics']['type'][$i],
        $_FILES['pics']['tmp_name'][$i],
        $_FILES['pics']['error'][$i],
        $_FILES['pics']['size'][$i],
      );
      if($size<=0) {
        Msg("Field <b>".($i+1)."</b> was skipped because it was empty.", BLACK); 
        echo '</div>';continue;
      }
      if(!in_array($type, $accepted_types)){
       Msg("<b>$name</b> was not uploaded because its filetype is not supported ($type).", RED);
       echo '</div>';continue;
      }
      
      if(file_exists( $config['upload_path'] .'/'. $name )){
        //$oldname=$name;
        //$name = time().'_'.$name;
        //Msg("<b>$oldname</b> was renamed to <b>$name</b> because of a naming conflict. It may have been uploaded previously.", BLACK);
        Msg("A file called <b>$name</b> already exists, it will be overwritten.", BLACK);
        $insert=false;
      }
      
      // save img to upload folder
      $result = move_uploaded_file($tmp_name, $config['upload_path'] . '/'. $name);
      if(!$result){
        Msg("<b>$name</b> could not be uploaded.",RED); 
      } else {
        Msg("<b>$name</b> was uploaded successfuly!",GREEN);  
      }
      
      // save entry in db
      $result=false;
      if($insert){
      $result=$db->insert('pictures',
        array(
          'filename'=>$name,
          'date'=>date('Y-m-d H:i:s') 
      ));}
      if(!$result){
        Msg("There was an error inserting an entry for <b>$name</b> into the database.", RED); 
      } else {
        Msg("<b>$name</b> has been added to the database.", GREEN); 
      }
      
      echo '</div>'; 
    }
    
  } else {
   Msg("There are no files to upload.", BLACK);
  }    
  
  echo '<hr style="clear:both" />';
  echo '<p><input type="button" value="Upload more files" onclick="document.location.href=\'UploadPhoto.php\'" /></p>';
  
  //header('Refresh: 5; url=UploadPhoto.php');
  //exit;
  
  function Msg($str,$color){
    echo "<p style=\"color:$color;font-size:1em\">$str</p>"; 
  }
  
?>