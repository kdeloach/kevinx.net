<?php require 'inc/config.php';require 'inc/lib.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Camp Connect</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" type="text/css" href="style.css" />
<link rel="stylesheet" type="text/css" href="flora.tabs.css" /> 
<style type="text/css">

#selected_video {
  padding:0;
  width:556px;
  height:431px; 
}
#thumbnailview_thumbs {
  overflow:auto;
  overflow-x:hidden;
  height:430px;
  width:100%;
  border-bottom:1px solid #000;
  background: #ccc url(images/thumbfade.gif) bottom left repeat-x;
}
#sort {
  margin-left:11px;
}
#panel {
 margin:auto auto;  
}
.thumb {
 font-weight:normal;text-align:left; 
} .thumb img {
  margin:5px; 
}


</style>
<script type="text/javascript" src="jquery-1.2.1.min.js"></script>
<script type="text/javascript" src="swfobject.js"></script>
<script type="text/javascript">
  var ASC=0,DESC=1;
  var SORT_FILE=1,SORT_TIME=2;
  var SORTBY;
  var imgz=[];
  
  function addItem(item){
    imgz[imgz.length]=item;
  }
  $(document).ready(function(){
    
    img = new Image(1,1);
    img.src='images/h_see.gif';
    img.src='images/h_watch.gif';
    img.src='images/h_listen.gif';
    img.src='images/h_write.gif';
    
    qs();

    <?php
      /*
      $filename='';
      $time=time();
      $db->query("select filename,date from videos order by date desc");
      while(list($fn,$date)=$db->result()){
        $filename=$fn;
        $time=strtotime($date);
      }
      
      echo "drawplayer('video/$filename');";*/
    
    ?>
    
    $(".hover").mouseover(function(){
        $(this).attr('src','images/h_'+$(this).attr('name') );
    });
    $(".hover").mouseout(function(){
        $(this).attr('src','images/'+$(this).attr('name') );
    });
    
    $('#loading').ajaxStart(function(){ $(this).show(); }).ajaxStop(function(){ $(this).hide(); });
    
    $.getJSON('json_videos.php',
      function(data){
        $.each(data, function(i,item){
          addItem(item);
        });
        resort();
        drawImgz();
        select(imgz[0][1]);
        
        if(qsParm['l'])
          select(qsParm['l']);
    });
    
  });
var qsParm = [];
function qs() {
  var query = window.location.search.substring(1);
  var parms = query.split('&');
  for (var i=0; i<parms.length; i++) {
    var pos = parms[i].indexOf('=');
    if (pos > 0) {
      var key = parms[i].substring(0,pos);
      var val = parms[i].substring(pos+1);
      qsParm[key] = val;
    }
  }
}
  function drawImgz(){
      $('#thumbnailview_thumbs').empty();
      var tv_str='';
      tv_str+='<table border="0" cellpadding="0" cellspacing="11" width="100%">';
      $.each(imgz, function(i,item){
        var id=item[0], filename=item[1], time=item[2], desc=item[4],date=item[5];
        tv_str += '<tr><td class="thumb"><a href="#" onclick="javascript:select(\''+filename+'\')" style="float:left"><img id="'+filename+'" src="images/preview.jpg" alt="Image" width="152" height="114" /></a> <small>(Added '+date+')</small> - '+desc+'</td></tr>';
      });
      tv_str+='</table>';
      $('#thumbnailview_thumbs').append(tv_str);
  }
  function select(filename){
    var file = 'video/'+filename;
    drawplayer(file);
  }
  function resort(){
    if(SORTBY==SORT_FILE)
      imgz.sort();
    else if(SORTBY==SORT_TIME)
      imgz.sort(function(a,b){
        return a[2]-b[2];
      });

      drawImgz();
  }
  function drawplayer(filename){
    $('#selected_video').empty();
    var so = new SWFObject('flvplayer.swf','single','556','431','7');
    so.addParam('allowfullscreen','true');
    so.addParam('allowscriptaccess','always');
    so.addVariable('file',filename);
    so.addVariable('height','431');
    so.addVariable('width','556');
    so.addVariable('autostart','true');
    so.write('selected_video');
  }
  function sortby(by){
    SORTBY=by;
    resort();
    drawImgz();
  }
  
</script>
</head>

<body>

  <div id="container">
  
   <div id="header">
     <a href="index.php"><img src="images/logo.gif" id="logo" alt="Camp Connect" /></a>
     <span id="toplinks">
       <a href=""><img src="images/cart.gif" alt="Cart" /></a><img src="images/pipe.gif" class="pipe" alt="" /><a href=""><img src="images/myaccount.gif" alt="My Account" /></a><img src="images/pipe.gif" class="pipe" alt="" /><a href=""><img src="images/help.gif" alt="Help" /></a>
     </span>
   </div>
   
   <div id="menu">
   <a href="photos.php"><img src="images/see.gif" name="see.gif" alt="See all camp videos" class="hover" /></a>
   <a href="video.php"><img src="images/watch.gif" name="watch.gif" alt="Watch the latest videos" class="hover" /></a>
   <a href="#"><img src="images/listen.gif" name="listen.gif" alt="Listen to audio clips" class="hover" /></a>
   <a href="#"><img src="images/write.gif" name="write.gif" alt="Write emails and notes" class="hover" /></a></div>
   
   <div id="content">

<span id="loading" style="position:absolute;top:57%;left:45%;display:none;"><img src="images/ajax-loader.gif" /></span>
   
     <span id="title" style="float:left;"><?php echo getTitle(); ?></span>
     
     <div id="sort" style="float:left;font-weight:bold;clear:left;margin-bottom:5px;">Sort by: 
       <select id="sortby_field" onchange="sortby(this.value)">
         <option value="2">Date Added</option>
         <option value="1">Filename</option>
       </select>
       <select id="sortby_unit" onchange="sortunit(this.value)">
        <option>Bunk Unit</option>
       </select> 
       <select id="sortby_act" onchange="sortact(this.value)">
        <option>Activity</option>
       </select>
     </div>
 
        <div id="panel" style="width:989px;clear:both;" class="ui-tabs-panel">
            <table cellspacing="11" cellpadding="0" width="100%">
              <tr>
                <td id="thumbnailview_video"><div id="selected_video"><a href="http://www.macromedia.com/go/getflashplayer">Get the Flash Player</a> to see this player.</div></td>
                <td width="100%"><div id="thumbnailview_thumbs">&nbsp;</div></td>
              </tr>
            </table>
        </div>
     
   </div>
  
  </div>

</body>
</html>

