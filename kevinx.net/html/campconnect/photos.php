<?php require 'inc/config.php';require 'inc/lib.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Camp Connect</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" type="text/css" href="style.css" />
<link rel="stylesheet" type="text/css" href="flora.tabs.css" /> 
<style type="text/css">

#thumbnailview_image {
  padding:0;
  width:556px;
  height:431px; 
}
#filmstripview_left,#filmstripview_right {
}
#selected_image {
 padding:0;margin:0; 
}
#thumbnailview, #filmstripview {
    font-size:1em;
    font-weight:normal;
    font-family:arial,verdana,sans-serif;
}

#thumbnailview_thumbs {
  overflow:auto;
  overflow-x:hidden;
  height:430px;
  width:100%;
  border-bottom:1px solid #000;
  background: #ccc url(images/thumbfade.gif) bottom left repeat-x;
}
.thumb {

}
#filmstripview_thumbs {
  overflow:auto;overflow-y:hidden;
  padding:0;margin:auto auto;
  width:950px;height:115px;
}
.strip {
   
}
#sort {
  margin-left:11px;
}
#panel {
 margin:auto auto;  
}


</style>
<script type="text/javascript" src="jquery-1.2.1.min.js"></script>
<script type="text/javascript" src="ui.tabs.js"></script>
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
    
    $(".hover").mouseover(function(){
        $(this).attr('src','images/h_'+$(this).attr('name') );
    });
    $(".hover").mouseout(function(){
        $(this).attr('src','images/'+$(this).attr('name') );
    });
    
    $('#loading').ajaxStart(function(){ $(this).show(); }).ajaxStop(function(){ $(this).hide(); });
    
    $.getJSON('json_images.php',
      function(data){
        $.each(data, function(i,item){
          addItem(item);
        });
        
        drawImgz();
        
      select(imgz[0][1]);
        
      if(qsParm['r'])
        select(qsParm['r']);
      if(qsParm['l'])
        select(qsParm['l']);
    });
    
    $("#panel > ul").tabs(1,{ fxFade:false, fxSlide:false, fxSpeed:'normal' });
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
      $('#filmstripview_thumbs').empty();
      var tv_str='',fs_str='';
      tv_str+='<table border="0" cellpadding="0" cellspacing="11" width="100%"><tr>';
      fs_str+='<table border="0" cellpadding="0" cellspacing="0"><tr>';
      $.each(imgz, function(i,item){
        var id=item[0], filename=item[1], time=item[2];
        if(i%2==0) tv_str+='</tr><tr>';
        tv_str += '<td class="thumb"><a href="#" onclick="javascript:select(\''+filename+'\')"><img id="'+filename+'" src="img_z_152x114_'+filename+'" alt="Image" width="152" height="114" /></a></td>';
        fs_str += '<td class="thumb" style="padding:0 4px 0 4px"><a href="#" onclick="javascript:select(\''+filename+'\')"><img id="'+filename+'" src="img_z_118x89_'+filename+'" alt="Image" width="118" height="89" /></a></td>';
      });
      tv_str+='</tr></table>';
      $('#thumbnailview_thumbs').append(tv_str);
      $('#filmstripview_thumbs').append(fs_str);
  }
  function addLeft(filename){
    var file = 'img_z_q75_478x370_'+filename;
    var img=new Image();img.src=file;
    //$.get(file,null,function(data){
    //$.ajax({ type:"GET",url:file,async:false,success:function(){
          $('#right_image').attr('src',$('#left_image').attr('src'));
          $('#left_image').attr('src',img.src);
      //}});
  }
  function select(filename){
    var file = 'img_q75_556x431_'+filename;
    var img=new Image();img.src=file;
    //$.get(file,null,function(data){
   /* $.ajax({ type:"POST",url:file,async:false,
error: function(xhr, status, ex) {
                var msg = "";
                msg += status + "\n";
                msg += xhr.status + "\n\n";
                msg += ex;
                alert('PATH: ' + file);
                alert('REPORT: ' + msg);
        },
    success:function(data){
          $('#selected_image').attr('src',img.src);
          addLeft(filename);
      }});*/
      $('#selected_image').attr('src',img.src);
      addLeft(filename);
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
     
     <div id="sort" style="float:left;font-weight:bold;clear:left;">Sort by: 
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
     
        <div id="panel" style="width:989px;">
            <ul>
                <li><a href="#thumbnailview"><span>Thumbnail View</span></a></li>
                <li><a href="#filmstripview"><span>Filmstrip View</span></a></li>
            </ul>
            <div id="thumbnailview">

            <table cellspacing="11" cellpadding="0" width="100%">
              <tr>
                <td id="thumbnailview_image"><img src="images/trans.gif" alt="Image" id="selected_image" /></td>
                <td><div id="thumbnailview_thumbs">&nbsp;</div></td>
              </tr>
            </table>
            
            </div>
            <div id="filmstripview">

            <table cellspacing="11" cellpadding="0" width="100%" border="0">
              <tr>
                <td id="filmstripview_left"><img src="images/trans.gif" alt="Image" id="left_image" /></td>
                <td id="filmstripview_right"><img src="images/trans.gif" alt="Image" id="right_image" /></td>
              </tr>
              <tr>
                <td colspan="2"><div id="filmstripview_thumbs">&nbsp;</div></td>
              </tr>
            </table>
            
            </div>
        </div>
     
   </div>
  
  </div>

</body>
</html>

