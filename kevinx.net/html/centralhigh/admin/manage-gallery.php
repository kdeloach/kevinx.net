<?php

include 'include/admin-header.php';
include 'include/auth.php';

  if($_POST&&$_FILES){

    #set all that stuff
    $image=$_FILES['image'];
    $name=addslashes($_POST['name']);
    $tmp_name=$image['tmp_name'];
    $type=addslashes($image['type']);
    $blurb=addslashes($_POST['blurb']);
    
    #get the extension
    $ext=explode('.',$image['name']);
    $ext=addslashes($ext[sizeof($ext)-1]);

    #check type...
    if(preg_match('/^image\//',$type)&&preg_match('/(gif|jpg|jpeg|png)/i',$ext)){
      #if its all good insert all that business, does it first to get an id
      $sql->query("INSERT INTO gallery(`id`,`type`,`blurb`,`ext`,`ver`) VALUES('','$type','$blurb','$ext','y')");
      $id=$sql->insert_id();
      
      #established target (adds extension since stuff loaded from here is prolly good)
      $target=ROOT.PATH."images/gallery/$id.$ext";

      #movem!
      if(!move_uploaded_file($tmp_name,$target)){
        #if it fails, delete
        $sql->query("DELETE FROM gallery WHERE id='$id'");
      }else{
        #pat on the back if not
        header("Location: $_SERVER[PHP_SELF]?success");
        exit;
      }
    }
    #send it back anyway
    header("Location: $_SERVER[PHP_SELF]?error");
    exit;
  }
  
  ##################################################
  
  #Upload form
?>
  <form name="imageloader" enctype="multipart/form-data" method="post">
    Image:<input type="file" name="image" /><br />
    Description:<input type="text" name="blurb" />
    <input type="submit" name="upload" value="Upload" />
  </form>

<?php

#pull all them, maybe some organization later

$sql->query('SELECT * FROM gallery ORDER BY id DESC');

println('<table id="highlight">');

#table header
println('<tr><th>id</th><th>Thumb</th><th>Description</th><th>Category</th><th></th>From<th>Approved</th><th></th></tr>');

#print rows
while($tmp=$sql->fetch_assoc()){
  #id
  println("<tr><td>$tmp[id]</td>");
  #thumb
  println("<td><img src=\"thumb.php?id=$tmp[id]\" /></td>");
  #blurb
  println("<td>$tmp[blurb]</td>");
  #category
  println("<td>$tmp[cat]</td>");
  #from
  $tmpmail=(!empty($tmp['email']))?"($tmp[email])":'n/a';
  println("<td><center>$tmp[name] $tmpmail</center></td>");
  #verified
  println("<td>".(($tmp['ver']=='y')?'Yes':'No')."</td>");
  #update
  println("<td><a href=\"updateimage-gallery.php?id=$tmp[id]\">View</a>");
  println("</td></tr>");
}
println('</table>');
?>
