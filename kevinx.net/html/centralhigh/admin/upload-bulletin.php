<?php
# This now requires AbiWord - gentoo package abiword
include 'include/admin-header.php';
include 'include/auth.php';
?>
<script language="javascript">
var oldval=null;
var olddiv=null;
var httpfetcher=false;

function initFetcher(){
  if(typeof XMLHttpRequest!='undefined'){
    try{
      httpfetcher=new XMLHttpRequest();
    }catch(e){}
  }
}

function checkForm(){
  if(document.upload.bulletin.value.match(/\.doc$/i)){
    return true;
  }else{
    alert('File must be ".doc".');
    document.upload.bulletin.focus();
    return false;
  }
}

function edit(nm){
  if(null!=oldval)return;
  olddiv=document.getElementById(nm);
  oldval=olddiv.innerHTML;
  document.getElementById(nm).innerHTML='<input type=text name=update value='+oldval+'><input type=button onClick=sendd(document.updater.update) value=Change><input type=button onclick=cancel() value=Cancel>';
}

function cancel(){
  if(olddiv){
    olddiv.innerHTML=oldval;
    olddiv=null;
    oldval=null;
  }
}

function sendd(){
  if(httpfetcher==false)initFetcher();
  if(httpfetcher==false)return;
  httpfetcher.open('POST','updatebulletin.buffer2.php',true);
  httpfetcher.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
  httpfetcher.onreadystatechange=function(){
    if(httpfetcher.readyState==4){
      document.getElementById('content').innerHTML=httpfetcher.responseText;
      stripe('highlight');
    }
  }
  httpfetcher.send('olddate='+olddiv.id+'&newdate='+document.updater.update.value);
  cancel();
}
</script>
<?php

#Confirmation crap
if(isset($_GET['success'])){
  @$success=date('Y-m-d',$_GET['success']);
  if(isset($_GET['setdate'])){
    echo "<p>I could not find a date for the bulletin, I used today's date instead.  See below to change it.</p>\n";
  }
  if(isset($_GET['del'])){
    echo "<p>Another bulletin has been stored for this date, $success, it has been replaced.</p>\n";
  }
  echo "<p>Bulletin for $success has been successfully stored.</p>\n";
}



######################
#Handle uploaded file#
######################

if($_FILES){
  $doc=$_FILES['bulletin']['tmp_name'];
/*
  #open file
  $content=file_get_contents($doc);

  #kill off the first few lines and last MANY
  preg_match_all('/CENTRAL HIGH SCHOOL(.*)BELL SCHEDULES/i',$content,$matches);

  $content=$matches[0][0];

  #Clean up apostrophes
  $content=str_replace(chr(146),'\'',$content);

  #Clean up hyperlinks
  $content=preg_replace('{HYPERLINK "([^ ])*}','',$content);

  #kill off the rest of the invalid characters
  $badchars= include 'badchars.php';
  $content=str_replace($badchars,'',$content);

  #Get rid of 'BELL SCHEDULES', that terrible line that starts that section, and
  #marks the end of the text we want
  $content=preg_replace('/BELL SCHEDULES$/','',$content);
*/
  move_uploaded_file($doc, '/tmp/bulletin.doc');
  system('abiword -t text /tmp/bulletin.doc');

  $content=file_get_contents('/tmp/bulletin.text');
  unlink('/tmp/bulletin.text');
  unlink('/tmp/bulletin.doc');

  ##################################
  #By this point it's straight text#
  ##################################

  #explode content to retrieve date
  $text = preg_split('/(\n|\r)/',$content);

  #scans
  $date=-1;
  for($i=0;$i<sizeof($text)&&$date<strtotime('January 1 2006');$i++){
    $format=preg_replace('/([1-3]{0,1}[0-9]), (20[0-9]{2})/','\1 \2',trim($text[$i]));
    @$date=strtotime($format);
  }

  #Parse headers
  $content=preg_replace('/(\r|\n)(STAFF & STUDENTS|STAFF AND STUDENTS|STAFF|STUDENTS|COUNSELO(\w|\W)+MESSAGES|CLUBS\/MEETINGS)(\r|\n)/i','<br /><span>\2</span><br />',$content);
  #Convert line breaks
  $content=preg_replace('/(\r|\n)/',"<br />\n",$content);
  #Clean up line braks
  $content=preg_replace('/(<br \/>(\r|\n)*){3,}/','<br /><br >',$content);



  ############################
  #Prepare for database entry#
  ############################

  $rep='';
  if($date==-1){
    $rep='&setdate';
    $date=mktime();
  }
  
  $cleandate=date('Y-m-d',$date);
  
  $deleted='';
  $sql->query("SELECT day FROM daily_bulletins WHERE day='$cleandate'")or die(mysql_error());
  if($tmp=$sql->fetch_assoc()){
    $sql->query("DELETE FROM daily_bulletins WHERE day='$cleandate'") or die(mysql_error());
    $deleted='&del';
  }
  
  $content=addslashes($content);
  $sql->query("INSERT INTO daily_bulletins VALUES('$cleandate',$date,'$content')");
  accesslog("Posted bulletin for $cleandate");
  
  header("Location: upload-bulletin.php?success=$date$rep$deleted");
}

?>
<h1>Upload:</h1>
<form name="upload" enctype="multipart/form-data" method="POST" action="upload-bulletin.php" onSubmit="return checkForm();">
  Bulletin: <input type="file" name="bulletin" />
  <input type="submit" value="Post Bulletin" />
</form><br />
<h1>Bulletins:</h1>
<form method="GET" name="updater" onSubmit="return false">
<div align="center" id="content">
<table id="highlight" width="75%">
<tr><th width="100">Date (Click to change)</th><th>Content</th></tr>
<?php
$current=date('Y-m-%',mktime());

#$sql->query("SELECT * FROM daily_bulletins WHERE day LIKE '$current' ORDER BY day DESC");
$sql->query("SELECT * FROM daily_bulletins ORDER BY day DESC LIMIT 25");
while($tmp=$sql->fetch_assoc()){
  $sample=substr($tmp['content'],0,500).'...';
  echo "      <tr><td><div id=\"$tmp[unix]\" onClick=\"edit($tmp[unix])\">$tmp[day]</div></td>";
  echo "        <td>$sample...</td></tr>\n";
}
?>
</table>
</div>
</form>
<?php
include 'include/admin-footer.php';
?>
  
