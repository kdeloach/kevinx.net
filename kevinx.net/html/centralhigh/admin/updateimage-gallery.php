<?php
  include 'include/admin-header.php';
  include 'include/auth.php';
  
  if($_POST){
    #check that id is cool
    if(is_numeric($_POST['id'])){
      $id=$_POST['id'];
      
      #pull data associated with id
      $sql->query("SELECT ver,ext FROM gallery WHERE id='$id'");
      #if its cool to continue...
      if($tmp=$sql->fetch_assoc()){
        echo 'a';
        #'stump' of image location, edit to change, durr
        $stump="../../images/gallery/$id";
          
        #if the item is verified, but previously wasnt
        if($_POST['good']=='y'&&$tmp['ver']=='n'){
          #moves file
          if(file_exists($stump)){
            rename($stump,"$stump.$tmp[ext]");
            #updates dbstuff
            $sql->query("UPDATE gallery SET ver='y' WHERE id='$id'");
          }
        }else if($_POST['good']=='n'&&$tmp['ver']=='y'){
          #opposite of last thing
          if(file_exists("$stump.$tmp[ext]")){
            rename("$stump.$tmp[ext]",$stump);
            $sql->query("UPDATE gallery SET ver='n' WHERE id='$id'");
          }
        }
        #update blurb regardless
        $blurb=addslashes($_POST['blurb']);
        $cat=addslashes($_POST['cat']);
        $sql->query("UPDATE gallery SET blurb='$blurb',cat='$cat' WHERE id='$id'")or die(mysql_error());
      }
    }
    #send it back
    header("Location: manage-gallery.php");
    exit;
  }
  
  #if u want to delete
  if(isset($_GET['del'])&&isset($_GET['id'])){
    if(is_numeric($_GET['id'])){
      $id=$_GET['id'];
      $stump='/var/www/kevinx.net/html/centralhigh/images/gallery/'.$id;
      #drops from db
      $sql->query("DELETE FROM gallery WHERE id='$id'");
      #deletes both, error suppression, easier then checking
      @unlink($stump);
      @unlink($stump.$tmp['ext']);
    }
    header('Location: manage-gallery.php');
    exit;
  }
  
  ###############################################
  
  #needs a valid id to continue
  if(!empty($_GET['id'])){
    if(is_numeric($_GET['id'])){
      $id=$_GET['id'];
      #pull all the goodies
      $sql->query("SELECT id,blurb,ext,ver,cat FROM gallery WHERE id='$id'");

      if($tmp=$sql->fetch_assoc()){
        #'stump' of image location
        $stump="../../images/gallery/$id";
        
        #checks for .ext version, if not uses the image script to do it
        if(file_exists($stump)){
          echo "<img src=\"image.php?id=$id\" />";
        }else if(file_exists("$stump.$tmp[ext]")){
          echo "<img src=\"$stump.$tmp[ext]\" />";
        }else{
          #unlikely
          echo "Image doesn't exist, you should probably delete this.";
        }
?>
<script language="javascript">
function changeup(){
  if(document.imager.changer.checked==true){
    document.getElementById('old').disabled=true;
    document.getElementById('new').disabled=false;
  }else{
    document.getElementById('old').disabled=false;
    document.getElementById('new').disabled=true;
  }
}
</script>
<?php
        println('<form method="POST" name="imager">');
        #delete that bitch
        println('<input type="button" value="Delete" onClick="document.location=\'updateimage-gallery.php?del&id='.$id.'\'" /><br /><br />');
        
        #Approved? Visible? whatever
        println('Visible?: <select name="good">');
        println('<option value="y" '.(($tmp['ver']=='y')?'SELECTED':'').'>Yes</option>');
        println('<option value="n" '.(($tmp['ver']=='n')?'SELECTED':'').'>No</option>');
        println('</select>');
        
        #blurb
        println('<br /><input type="text" name="blurb" value="'.$tmp['blurb'].'" />');
        
        println("Category:<select id=\"old\" name=\"cat\">\n");
        $sql->query("SELECT DISTINCT cat FROM gallery");
        while($tmp2=$sql->fetch_assoc()){
          println("<option value=\"$tmp2[cat]\"".(($tmp['cat']==$tmp2['cat'])?'SELECTED':'').">$tmp2[cat]</option>\n");
        }
        println('</select>');
        #or new category
        println('New:<input type="checkbox" name="changer" onChange="changeup()" />');
        println('<input type="text" id="new" name="cat" DISABLED />');
        
        #id
        println('<input type="hidden" name="id" value="'.$id.'" />');
        
        #done
        println('<br /><input type="submit" value="Update" /></form>');
        
        #yeah, watever
      }else header('Location: manage-gallery.php');
    }else header('Location: manage-gallery.php');
  }else header('Location: manage-gallery.php');

  include 'include/admin-footer.php';
?>
