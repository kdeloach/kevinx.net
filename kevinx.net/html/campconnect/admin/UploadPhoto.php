<?php require '../inc/config.php';require '../inc/lib.php';require_login();set_time_limit(0); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Camp Connect - Admin Panel</title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!--base href="<?php echo $config['url'];?>/" /-->

<link rel="stylesheet" type="text/css" href="../style.css" /> 
<link rel="stylesheet" type="text/css" href="../thickbox.css" /> 
<style type="text/css">
#filelist li {
   padding:5px;
}
#uploadform {
  display:none; 
}
#scrollingpanel {
  overflow:auto;
  overflow-x:hidden;
  height:300px;
  width:100%;
  border-bottom:1px solid #000;
}
#tagpanel {
  text-align:left;
  border:8px solid #ccc; 
  padding:15px;margin-top:20px;
}
#tagpanel p {
  padding:0 0 4px 0;margin:0; 
}
#tagpanel span {
  float:left;width:100px;height:2.5em;
}
#tagpanel label {
 padding:5px;
} 
.delico {
 cursor:pointer; 
}
.disabled {
  background-color:#ddd; 
  color:#999;
} 
</style>
<script type="text/javascript" src="../jquery-1.2.1.min.js"></script>
<script type="text/javascript" src="../thickbox.js"></script>
<script type="text/javascript">
  var imgz=[];
  var ASC=0,DESC=1;
  var SORT_FILE=1,SORT_TIME=2;
  var SORTBY,SORTORD;
  function additem(item,arr){
    arr[arr.length]=item;
  }
  $(document).ready(function(){

    $.ajaxSettings.cache = false;

    SORTBY=$('#sortby_field').val();
    SORTORD=$('#sortby_dir').val();

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
        
    $.getJSON('../json_images.php',
      function(data){
        $.each(data, function(i,item){
          additem(item, imgz);
        });
        resort();
        redraw();
        updatetagpanel();
    });
  });

  function map(a,fn){
    var arr=[],tmp=null;
    $.each(a,function(i,item){
      tmp=fn(item);
      if(tmp!=null)
        arr[arr.length]=tmp;
    }); 
    
    return arr;
  }
  
  function redraw(){
      $('#imgdiv').empty();
      var str='';
      str+='<table border="0" cellpadding="0" cellspacing="11" width="100%"><tr>';
      $.each(imgz, function(i,item){
        var id=item[0];var filename=item[1];var time=item[2];
        if(i%4==0)
          str+= '</tr><tr>';
        str+='<td style="vertical-align:top"><div style="float:left"><input type="checkbox" id="box'+i+'" onclick="tag(this.checked,'+i+')" /><br /><img src="../images/del2.gif" class="delico" onclick="del(\''+filename+'\')" /></div><!--label for="box'+i+'"--><img src="../img_z_q75_143x107_'+filename+'" onclick="tag2(\''+i+'\')" width="143" height="107" alt="Image" title="'+filename+'" /><!--/label--></td>';
       
      });
      str+='</tr></table>';
      $('#imgdiv').append(str);
  }
  function removeitem(i,arr){
     return map(arr, function(val){
       return val==i ? null : val;
     });
  }
  function tag2(i){
    $('#box'+i).attr( 'checked', !$('#box'+i).attr('checked') );
    tag($('#box'+i).attr('checked'),i);
  }
  var active=[];
  function tag(val,i){
    if(!val) {active=removeitem(i,active);updatetagpanel();return;}
    additem(i,active);
    updatetagpanel();
  }
  function updatetagpanel(){
    if(active.length==0){
      $('#tagpanel input').removeAttr('checked');
      $('#tagpanel input').attr('disabled','true');
      $('#tagpanel').addClass('disabled');
    } else if (active.length>0) {
       $('#tagpanel input').removeAttr('disabled').removeAttr('checked');
       $('#tagpanel').removeClass('disabled');
       
      // foreach of this images' tags
      $.each(active, function(i,n){
        $.each(imgz[n][3], function(n,tag){
            $('#'+tag).attr('checked','true');
        })
      });
    }
    
  }
  function del(filename){
    var res=confirm("Do you really want to delete "+filename+"?");
    if(!res) return;
    imgz=map(imgz, function(item){
      return item[1]==filename ? null : item;
    });
    $.getJSON('del_image.php', {src:filename});
    redraw();
  }
  function resort(){
    cancelit();
    if(SORTBY==SORT_FILE)
      imgz.sort();
    else if(SORTBY==SORT_TIME)
      imgz.sort(function(a,b){
        return a[2]-b[2];
      });
    if(SORTORD==ASC)
      imgz.reverse();
  }
  function sortorder(ord){
    SORTORD=ord;
    resort();
    redraw();
  }
  function sortby(by){
    SORTBY=by;
    resort();
    redraw();
  }
  function cancelit(){
    active=[];
    $('#imgdiv input').removeAttr('checked');
    updatetagpanel();  
  }
  function update(){
    var units=[],activities=[],idz=[];
    $.each(active, function(n,i){
      var id=fnid(i);
      imgz[i][3]=[];
      imgz[i][3][0]=[];
      imgz[i][3][1]=[];
      $.each( $('.unit:checked'), function(n,item){
        additem(item.value,imgz[i][3][0]);
      });
      $.each( $('.act:checked'), function(n,item){
        additem(item.value,imgz[i][3][1]);
      });
    });
    
    units=map(imgz, function(item){
        if(!item[3][0])  return [];
        return item[3][0];
    });
    activities=map(imgz, function(item){
        if(!item[3][1])  return [];
        return item[3][1];
    });
    idz=map(imgz, function(item){
        return item[0];
    });
    
    $.getJSON('save_tags.php', {'u[]':units,'a[]':activities,'i[]':idz,'r':math.random()});

    return true;
  }
  function fni(id){
    var n=false;
    $.each(imgz, function(i,item){
        if(imgz[i][0]==id){
          n=i;
        }
    }); 
    return n;
  }
  function fnid(i){
    return imgz[i][0];
  }
</script>
</head>
<body>

<div id="uploadform">
  <form enctype="multipart/form-data" action="upload.php" method="post">
    <ol id="filelist">
      <li>Choose a file to upload: <input name="pics[]" type="file" /></li>
      <li>Choose a file to upload: <input name="pics[]" type="file" /></li>
      <li>Choose a file to upload: <input name="pics[]" type="file" /></li>
      <li>Choose a file to upload: <input name="pics[]" type="file" /></li>
      <li>Choose a file to upload: <input name="pics[]" type="file" /></li>
    </ol>
    <p>&nbsp;</p>
    <p><input type="submit" name="submit" value="Upload File" style="float:left;" /></p>
  </form>
</div>

  <div id="container">
  
   <div id="header">
     <a href="../index.php"><img src="../images/logo.gif" id="logo" alt="Camp Connect" /></a>
   </div>
   
   <div id="menu">
   <a href="UploadPhoto.php"><img src="../images/uploadphoto.gif" name="uploadphoto.gif" class="hover" alt="Upload photos" /></a>
   <a href="UploadVideo.php"><img src="../images/uploadvideo.gif" name="uploadvideo.gif" class="hover" alt="Upload videos" /></a>
   <a href="#"><img src="../images/uploadaudio.gif" name="uploadaudio.gif" class="hover" alt="Listen to audio clips" /></a>
   <a href="UpdateAdmin.php"><img src="../images/updateadmin.gif" name="updateadmin.gif" class="hover" alt="Write emails and notes" /></a></div>
   
   <form method="post" action="" onsubmit="return update();">
   <div id="content">
   
   <table cellpadding="0" cellspacing="0" border="0" width="100%">
   <tr><td rowspan="2" style="vertical-align:top">
   
   <p style="font-weight:bold;font-size:.9em;text-align:left;width:150px;">
   Upload Pictures:<br />
   <input type="button" value="Upload Files" alt="#TB_inline?height=220&width=400&inlineId=uploadform" title="Upload Files" class="thickbox" /><br />
   <br />
   Sort by:<br />
   <select id="sortby_field" onchange="sortby(this.value)">
    <option value="2">Date Added</option>
    <option value="1">Filename</option>
   </select>
   <select id="sortby_dir" onchange="sortorder(this.value)">
    <option value="1">Descend</option>
    <option value="0">Ascend</option>
   </select>
   </p>
     
   </td><td>
   
   <div id="scrollingpanel">
    <div id="imgdiv">&nbsp;</div>
   </div>
   
   </td></tr><tr><td>
   
    <div id="tagpanel">
      <div id="units"><p style="font-weight:bold">Unit</p>
      <span><input type="checkbox" id="K&#145;tanim" value="K&#145;tanim" disabled="disabled" class="unit" /><label for="K&#145;tanim">K&#145;tanim</label></span>
      <span><input type="checkbox" id="Bonim" value="Bonim" disabled="disabled" class="unit" /><label for="Bonim">Bonim</label></span>
      <span><input type="checkbox" id="Ofarim" value="Ofarim" disabled="disabled" class="unit" /><label for="Ofarim">Ofarim</label></span>
      <span><input type="checkbox" id="Tzofilm" value="Tzofilm" disabled="disabled" class="unit" /><label for="Tzofilm">Tzofilm</label></span>
      <span><input type="checkbox" id="Olim" value="Olim" disabled="disabled" class="unit" /><label for="Olim">Olim</label></span>
      <span><input type="checkbox" id="Machon" value="Machon" disabled="disabled" class="unit" /><label for="Machon">Machon</label></span></div>
      
      <div id="activities"><p style="font-weight:bold;clear:both;">Activites</p>
      <span><input type="checkbox" id="Baseball" value="Baseball" disabled="disabled" class="act" /><label for="Baseball">Baseball</label></span>
      <span><input type="checkbox" id="Football" value="Football" disabled="disabled" class="act" /><label for="Football">Football</label></span>
      <span><input type="checkbox" id="Soccer" value="Soccer" disabled="disabled" class="act" /><label for="Soccer">Soccer</label></span>
      <span><input type="checkbox" id="Swim" value="Swim" disabled="disabled" class="act" /><label for="Swim">Swim</label></span>
      <span><input type="checkbox" id="Boating" value="Boating" disabled="disabled" class="act" /><label for="Boating">Boating</label></span>
      <span><input type="checkbox" id="Art" value="Art" disabled="disabled" class="act" /><label for="Art">Art</label></span>
      <span><input type="checkbox" id="Breira" value="Breira" disabled="disabled" class="act" /><label for="Breira">Breira</label></span>
      <span><input type="checkbox" id="Limud" value="Limud" disabled="disabled" class="act" /><label for="Limud">Limud</label></span>
      <span><input type="checkbox" id="Drama" value="Drama" disabled="disabled" class="act" /><label for="Drama">Drama</label></span></div>
      
      <p style="clear:both"><input type="submit" name="save" value="Save" /> <input type="reset" id="cancel" value="Cancel" onclick="cancelit()" /></p>
    </div>
   
   </td></tr></table>
   
   </div>
   </form>

  </div>

</body>
</html>

