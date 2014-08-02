<?php require '../inc/config.php';require '../inc/lib.php';require_login();set_time_limit(0); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Camp Connect - Admin Panel</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<link rel="stylesheet" type="text/css" href="../style.css" /> 
<style type="text/css">
td {
  text-align:left; 
}
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
   
<form enctype="multipart/form-data" method="post" action="upload_vids.php">
<input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
<fieldset>
<legend>Upload Video</legend>
<table><tr>
<td>Accepted format: .FLV<br />Max Filesize: 10MB</td><td><input type="file" name="vid[]"  /></td></tr><tr>
<td>Comments:</td><td><textarea rows="5" cols="60" name="comments"></textarea></td></tr></table>
</fieldset>

<p><input type="submit" name="save" value="Upload" onclick="this.disabled=true;this.value='Uploading...'" /></p>
</form>
     
   </div>

  </div>

</body>
</html>

