<?php require '../inc/config.php';require '../inc/lib.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Camp Connect - Admin Panel</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<base href="<?php echo $config['url'];?>/" />

<link rel="stylesheet" type="text/css" href="style.css" /> 
<link rel="stylesheet" type="text/css" href="thickbox.css" /> 
<style type="text/css">

</style>
<script type="text/javascript" src="jquery-1.2.1.min.js"></script>
<script type="text/javascript" src="thickbox.js"></script>
<script type="text/javascript">
  $(document).ready(function(){

  });
</script>
</head>

<body>

  <div id="container">
  
   <div id="header">
     <a href="index.php"><img src="images/logo.gif" id="logo" alt="Camp Connect" /></a>
   </div>
   
   <div id="menu">
   <a href="admin/UploadPhoto.php"><img src="images/uploadphoto.gif" alt="Upload photos" /></a>
   <a href="admin/UploadVideo.php"><img src="images/uploadvideo.gif" alt="Upload videos" /></a>
   <a href="admin/#"><img src="images/uploadaudio.gif" alt="Listen to audio clips" /></a>
   <a href="admin/UpdateAdmin.php"><img src="images/updateadmin.gif" alt="Write emails and notes" /></a></div>
   
   <div id="content">
   
Login form
     
   </div>

  </div>

  
  <p>
    <a href="http://validator.w3.org/check?uri=referer"><img
        src="http://www.w3.org/Icons/valid-xhtml10-blue"
        alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a>
  </p>
</body>
</html>

