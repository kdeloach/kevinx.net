<?php

  include 'include/admin-header.php';
  include 'include/auth.php';

  #lets just assume FILES is set
  if($_POST){
    if(is_numeric($_POST['id'])){
      #pull extension
      $sql->query("SELECT image FROM activities WHERE id='$_POST[id]' LIMIT 1");
      
      if($tmp=$sql->fetch_assoc()){
        #if delete
        if(isset($_POST['delete'])){
          #cutem both off
          $sql->query("UPDATE activities SET image='' WHERE id='$_POST[id]'");
          @unlink(ROOT.PATH."images/clubs/$_POST[id].$tmp[image]");
        }else{
          #set stuff
          $image=$_FILES['image'];
          $tmp_name=$image['tmp_name'];
          
          #extension...
          $ext=explode('.',$image['name']);
          $ext=$ext[sizeof($ext)-1];

          #it cool?
          if(preg_match('/^image\//',$image['type'])&&preg_match('/(gif|jpg|jpeg|png)/i',$ext)){
            #target filename...
            $target=ROOT.PATH."images/clubs/$_POST[id].$ext";
            
            #movit
            if(move_uploaded_file($tmp_name,$target)){
              $sql->query("UPDATE activities SET image='$ext' WHERE id='$_POST[id]'");
            }
          }
        }
      }
    }
    #get out
    header('Location: manage-activities.php');
    exit;
  }
  
  #########################################################

  #kinda like the frontend...
  $good=false;
  
  #need an id skipper!
  if(!empty($_GET['id'])){
    if(is_numeric($_GET['id'])){
      #get extension and name, innw
      $sql->query("SELECT image,name FROM activities WHERE id='$_GET[id]'");

      if($tmp=$sql->fetch_array()){
        #its good!!!
        $good=true;
        
        #name
	    println('<p>'.$tmp['name'].'</p>');
	    
	    #form
        println('<form enctype="multipart/form-data" method="post">');

        #image...
        $image="/".PATH."images/clubs/$_GET[id].$tmp[image]";
        if(file_exists(ROOT.$image)){
          println("<img src=\"$image\" /><br />");
          
          #delete it
          println('<input type="submit" name="delete" value="Delete" /><br /><br />');
        }
        println('<input type="file" name="image" /><input type="submit" value="Upload" />');
        println('<input type="hidden" name="id" value="'.$_GET['id'].'" />');
        println('</form>');
      }
    }
  }

  #get it now?
  if(!$good)header('Location: manage-activities.php');

  include 'include/admin-footer.php';

?>
