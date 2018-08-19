<?php
  include 'include/admin-header.php';
  include 'include/auth.php';

  if($_POST){
    $content=addslashes($_POST['content']);
    if(isset($_POST['id'])){
      $id=addslashes($_POST['id']);
      if(is_numeric($id)){
        $sql->query("SELECT id FROM depts WHERE id='$id'");
        if($tmp=$sql->fetch_assoc()){
          $sql->query("UPDATE depts SET content='$content' WHERE id='$id'");
        }
      }
    }
    
    header('Location: manage-depts.php');
    exit;
  }
  
  ################################################
  
  $id=false;
  $content='';
  if(isset($_GET['id'])){
    if(is_numeric($_GET['id'])){
      $sql->query("SELECT id,content FROM depts WHERE id='$_GET[id]'");
      if($tmp=$sql->fetch_assoc()){
        $id=$tmp['id'];
        $content=addslashes($tmp['content']);
      }
    }
  }
 //if($id===false){header('Location: manage-depts.php');exit;} 
  println('<form method="POST" onSubmit="return submitForm();">');
  
  println('Content:');
  println('<script type="text/javascript">');
  println('<!--');
  println("writeRichText('content', '".preg_replace('/(\r|\n)/','',$content)."', 600, 500, true, false);");
  println('//-->');
  println('</script><br />');
  
  if($id!==false) println('<input type="hidden" value="'.$id.'" name="id" />');
  
  println('<input type="submit" value="Update" />');
  println('</form>');

  include 'include/admin-footer.php';

?>
