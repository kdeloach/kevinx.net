<?php
  include 'include/admin-header.php';
  include 'include/auth.php';

  if($_POST){
    $name=$_POST['name'];
    $name=stripslashes(preg_replace('/(%| |\*|@|#|!)/','',$name));
    $content=stripslashes($_POST['content']);
    $now=mktime();
    if(strlen($name)>0){
      if(isset($_POST['id'])){
        $id=addslashes($_POST['id']);
        if(is_numeric($id)){
          $sql->query("SELECT id FROM pages WHERE id='$id'");
          if($tmp=$sql->fetch_assoc()){
            $sql->query('UPDATE pages SET name="'.$sql->escape_string($name).'",content="'.$sql->escape_string($content).'",modified="'.$now.'" WHERE id="'.$id.'"');
	    accesslog("Edited $name");
          }
        }
      }else{
        $sql->query('INSERT INTO pages(`name`,`content`,`created`,`modified`) VALUES("'.$name.'","'.$content.'","'.$now.'","'.$now.'")');
	accesslog("Created $name");
      }
      
      $dir=explode('/',$name);
      $dir[sizeof($dir)-1]='';
      $dir=implode('/',$dir);
      header('Location: manage-pages.php?dir='.$dir);
      exit;
    }
    header('Location: manage-pages.php');
    exit;
  }
  
  ################################################
  
  $id=false;
  $name='';
  $content='';
  if(isset($_GET['id'])){
    $id=addslashes($_GET['id']);
    if(is_numeric($id)){
      $sql->query("SELECT name,content FROM pages WHERE id='$id'");
      if($tmp=$sql->fetch_assoc()){
        $name=$tmp['name'];
	$content=$tmp['content'];
      }else{
        $id=false;
      }
    }else{
      $id=false;
    }
  }else if(isset($_GET['dir'])){
    #im trusting that the user isnt a jackass, and that dir is valid
    #seeing how this is in a password protected region and all
    $name=$_GET['dir'];
  }
  
  println('<form method="POST" onSubmit="return submitForm();">');
  
  println('Name: <input type="text" name="name" value="'.$name.'" /><br />');
  println('Content:');
  println('<script type="text/javascript">');
  println('<!--');
  println("writeRichText('content', '".preg_replace('/(\r|\n)/','',$content)."', 800, 500, true, false);");
  println('//-->');
  println('</script><br />');
  
  if($id!==false) println('<input type="hidden" value="'.$id.'" name="id" />');
  
  println('<input type="submit" value="Update" />');
  println('</form>');

  include 'include/admin-footer.php';

?>
