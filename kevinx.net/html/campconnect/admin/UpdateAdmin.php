<?php require '../inc/config.php';require '../inc/lib.php';require_login(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Camp Connect - Admin Panel</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" type="text/css" href="../style.css" /> 
<style type="text/css">

</style>
<script type="text/javascript" src="../jquery-1.2.1.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    img = new Image(1,1);
    img.src='images/h_uploadphoto.gif';
    img.src='images/h_uploadvideo.gif';
    img.src='images/h_uploadaudio.gif';
    img.src='images/h_updateadmin.gif';
    
    $(".hover").mouseover(function(){
        $(this).attr('src','../images/h_'+$(this).attr('name') );
    }).mouseout(function(){
        $(this).attr('src','../images/'+$(this).attr('name') );
    });
  });
</script>
</head>

<body>

  <div id="container">
  
   <div id="header">
     <a href="../index.php"><img src="../images/logo.gif" id="logo" alt="Camp Connect" /></a>
   </div>
   
   <div id="menu">
   <a href="UploadPhoto.php"><img src="../images/uploadphoto.gif" name="uploadphoto.gif" class="hover" alt="Upload photos" /></a>
   <a href="UploadVideo.php"><img src="../images/uploadvideo.gif" name="uploadvideo.gif" class="hover" alt="Upload videos" /></a>
   <a href="#"><img src="../images/uploadaudio.gif" name="uploadaudio.gif" class="hover" alt="Listen to audio clips" /></a>
   <a href="UpdateAdmin.php"><img src="../images/updateadmin.gif" name="updateadmin.gif" class="hover" alt="Write emails and notes" /></a></div>
   
   <div id="content">
   
<form method="post" action="">

<?php
  
if(isset($_POST['save'])){
  $t=isset($_POST['title']) ? mysql_escape_string($_POST['title']) : '';
  $db->query("update `info` set `value`='$t' where `key`='title'");
  header('Location:UpdateAdmin.php');
  exit;
}

  $data=array();
  $db->query("select `key`,`value` from `info`");
  while(list($k,$v)=$db->result())
    $data[$k]=$v;

?>


<fieldset>
<legend>Camp Name</legend>
<input type="text" name="title" value="<?php echo $data['title']; ?>" />
</fieldset>

<p><input type="submit" name="save" value="Save" /> <input type="reset" value="Cancel" /></p>
</form>
     
   </div>

  </div>

</body>
</html>

