<?php

  include 'include/admin-header.php';
  include 'include/auth.php';
  
  #Set empties
  $name='';
  $blurb='';
  $cat='';
  $url='';
  
  if($_POST){
    #set variables
    $id=addslashes($_POST['id']);
    $name=addslashes($_POST['name']);
    $tag=str_replace(' ','',strtolower($name));
    $blurb=addslashes($_POST['content']);
    #fix up categories, not really necessary maybe
    $cat=addslashes($_POST['cat']);
    $tag=strtolower(str_replace(' ','',addslashes($_POST['cat'])));
    $tag=(preg_match('/[a-zA-Z-]/',$tag))?$tag:'';
    $url=addslashes($_POST['url']);
    
    
    #Check if entry already exists, if it does updates, if not, creates
    $sql->query("SELECT id FROM activities WHERE id='$id'");
    if($sql->fetch_assoc()){
      $sql->query("UPDATE activities SET name='$name',blurb='$blurb',cat='$cat',url='$url',tag='$tag' WHERE id='$id'");
      accesslog("Edited $name");
    }else{
      $sql->query("INSERT INTO activities(`id`,`name`,`blurb`,`cat`,`url`,`tag`) VALUES('','$name','$blurb','$cat','$url','$tag')");
      accesslog("Created $name");
    }
    
    #Refresh to unset post data
    header("Location: manage-activities.php");
    exit;
  }
  
  #####################################################
  
  #dont need it?
  //$tmp=false;
  
  #checks if id is set and pulls data associated with id
  if(isset($_GET['id'])){
    $id=$_GET['id'];
    if(is_numeric($id)){
      $sql->query("SELECT * FROM activities WHERE id='$id'");
      if($tmp=$sql->fetch_assoc()){
        $id=$id;
        $name=$tmp['name'];
        $cat=$tmp['cat'];
        $blurb=preg_replace('/(\r|\n)/','',addslashes($tmp['blurb']));
        $url=$tmp['url'];
        $image=$tmp['image'];
      }

    }
  }
  #########Javascript shit###########
?>
<script language="javascript">
function changeup(){
  if(document.clubber.changer.checked==true){
    document.getElementById('old').disabled=true;
    document.getElementById('new').disabled=false;
  }else{
    document.getElementById('old').disabled=false;
    document.getElementById('new').disabled=true;
  }
}
</script>
<?php
  ###################################

  println('<form method="POST" enctype="multipart/form-data" name="clubber" onSubmit="return submitForm();">');
  println('<table>');
  
  #print name field
  println("<tr><td align=\"right\">Name:</td><td><input type=\"text\" name=\"name\" value=\"$name\" /></td></tr>");

  #category selection
  println("<tr><td align=\"right\">Category:</td><td><select id=\"old\" name=\"cat\">\n");
  $sql->query("SELECT DISTINCT cat FROM activities");
  while($tmp=$sql->fetch_assoc()){
    println("<option value=\"$tmp[cat]\"".(($cat==$tmp['cat'])?'SELECTED':'').">$tmp[cat]</option>\n");
  }
  println('</select>');
  #or new category
  println('New:<input type="checkbox" name="changer" onChange="changeup()" />');
  println('<input type="text" id="new" name="cat" DISABLED /></td></tr>');
  println('<tr><td align="right">URL:</td><td><input type="text" name="url" value="'.$url.'" /></tr>');
  println('<td align="right">Body:</td>');
  println('<td><script type="text/javascript">');
  println('<!--');
  println("writeRichText('content', '$blurb', 400, 200, true, false);");
  println('//-->');
  println('</script></td>');
  println('</tr>');
  
  #if id is set
  if(isset($id)) println('<input type="hidden" value="'.$id.'" name="id" />');
  
  #end it
  println('</table>');
  println('<input type="submit" value="Save" />');
  println('</form>');
  
  include 'include/admin-footer.php';

?>
