<?php require 'inc/config.php';require 'inc/lib.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Camp Connect</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style.css" /> 
<link rel="stylesheet" type="text/css" href="thickbox.css" /> 
<style type="text/css">
#preview {
  margin:0;padding:0;
  position:relative;
  top:18px;left:1px;
  cursor:pointer;
}
#player1 {
  display:none;
}
.scell,.bcell {
  background-color:#fff; 
}
.scell img, .bcell img {
  display:block; 
  margin: auto auto;
}
.scell {
  width:152px;
  height:152px;
}
.bcell {
  /* width and height don't matter much here
     since the size is really affected by the smaller cells
     not to mention the cellspacing
   */
}

</style>
<script type="text/javascript" src="jquery-1.2.1.min.js"></script>
<script type="text/javascript" src="thickbox.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    drawflashplayer();
    
    img = new Image(1,1);
    img.src='images/h_see.gif';
    img.src='images/h_watch.gif';
    img.src='images/h_listen.gif';
    img.src='images/h_write.gif';
    
    $(".hover").mouseover(function(){
        $(this).attr('src','images/h_'+$(this).attr('name') );
    }).mouseout(function(){
        $(this).attr('src','images/'+$(this).attr('name') );
    });
    
  });
  function drawflashplayer(){
    var so = new SWFObject('flvplayer.swf','single','500','400','7');
    so.addParam('allowfullscreen','true');
    so.addParam('allowscriptaccess','always');
    <?php
      
      $filename='';
      $time=time();
      $db->query("select filename,date from videos order by date desc");
      while(list($fn,$date)=$db->result()){
        $filename=$fn;
        $time=strtotime($date);
      }
    
    ?>
    so.addVariable('file','video/<?php echo $filename; ?>');
    so.addVariable('height','400');
    so.addVariable('width','500');
    so.addVariable('autostart','true');
    //so.addVariable('image','images/preview.jpg');
    //so.addVariable('frontcolor','0xFFFFFF');
    //so.addVariable('backcolor','0x000000');
    so.write('player1');
  }
</script>
</head>

<body>

<p id="player1"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.</p>
<script type="text/javascript" src="swfobject.js"></script>

  <div id="container">
  
   <div id="header">
     <img src="images/logo.gif" id="logo" alt="Camp Connect" />
     <span id="toplinks">
       <a href="#"><img src="images/cart.gif" alt="Cart" /></a><img src="images/pipe.gif" class="pipe" alt="" /><a href="#"><img src="images/myaccount.gif" alt="My Account" /></a><img src="images/pipe.gif" class="pipe" alt="" /><a href="#"><img src="images/help.gif" alt="Help" /></a>
     </span>
   </div>
   
   <div id="menu">
   <a href="photos.php"><img src="images/see.gif" name="see.gif" alt="See all camp videos" class="hover" /></a>
   <a href="video.php"><img src="images/watch.gif" name="watch.gif" alt="Watch the latest videos" class="hover" /></a>
   <a href="#"><img src="images/listen.gif" name="listen.gif" alt="Listen to audio clips" class="hover" /></a>
   <a href="#"><img src="images/write.gif" name="write.gif" alt="Write emails and notes" class="hover" /></a></div>
   
   <div id="content">
   
    <?php
      $pics = GetLatestPics(10); // get 10 latest pics from db
    ?>
   
     <span id="title"><?php echo getTitle(); ?></span>
     
     <table border="0" cellspacing="11" cellpadding="0" style="margin:auto auto;background-color:#ccc;width:980px;">
      <tr>
        <td rowspan="2" colspan="2" class="bcell" style="background:#fff url(images/screen.gif) no-repeat;background-position:3px 7px;vertical-align:top;">
          <div id="preview"><a href="#TB_inline?width=520&height=405&inlineId=player1" title="Latest Video - Added <?php echo date('m/d/y',$time); ?>" class="thickbox"><img src="images/preview.jpg" alt="Image" width="286" height="203" border="0" /></a></div>
          <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
          <p><img src="images/txt_latestvideo.gif" alt="Watch the latest video" /></p>
        </td>
        <td class="scell"><?php PrintNextPic(); ?></td>
        <td class="scell"><?php PrintNextPic(); ?></td>
        <td class="scell"><?php PrintNextPic(); ?></td>
        <td class="scell"><?php PrintNextPic(); ?></td>
      </tr>
      <tr>
        <td class="scell"><?php PrintNextPic(); ?></td>
        <td class="scell"><?php PrintNextPic(); ?></td>
        <td rowspan="2" colspan="2" class="bcell" style="background-color:#000"><a href="photos.php"><img src="images/more.gif" alt="Image" title="See what is going on around the camp" /></a></td>
      </tr>
      <tr>
        <td class="scell"><?php PrintNextPic(); ?></td>
        <td class="scell"><?php PrintNextPic(); ?></td>
        <td class="scell"><?php PrintNextPic(); ?></td>
        <td class="scell"><?php PrintNextPic(); ?></td>
      </tr>
     </table>
     
   </div>

  </div>

</body>
</html>

