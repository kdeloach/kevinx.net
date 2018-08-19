<?php
  include 'include/admin-header.php';
  include 'include/auth.php';
  
  if(isset($_GET['act'])&&$_GET['id']){
    if($_GET['act']=='del'){
      $_GET['id']=addslashes($_GET['id']);
      if(is_numeric($_GET['id'])){
        $sql->query("DELETE FROM pages WHERE id='$_GET[id]'");
      }
}
  }
 
echo "This will eventually replace cms...";
  $dir='';
  if(isset($_GET['dir'])){
    $dir=addslashes($_GET['dir']);
  }

  $sql->query("SELECT * FROM pages WHERE name LIKE '$dir%' ORDER BY name");
  
  #explode directory name
  $dirs=explode('/',$dir);
  
  #start table
  println('<table width="100%" id="highlight">');
  println('<tr><th>File</th><th>URL</th><th>Created</th><th>Modified</th><th colspan="2"></th></tr>');
  
  #for levels
  $level=0;
  if(is_array($dirs)){
    $level=sizeof($dirs)-1;
    #kill the last element in dirs, which is '', and
    #change the one before to '' so that it may implode right
    unset($dirs[$level]);
    $dirs[$level-1]='';
    println('<tr><td><a href="manage-pages.php?dir='.implode('/',$dirs).'">../</a></td>');
    println('<td>/pages/'.implode('/',$dirs).'</td>');
    println('<td align="center">-</td><td align="center">-</td><td align="center">-</td><td align="center">-</td>');
  }
  
  #print stuff
  $used=array();
  while($tmp=$sql->fetch_assoc()){
    #break up name
    $file=explode('/',$tmp['name']);
    #check if is a directory and it reaches to the proper directory level
    if(is_array($file)&&isset($file[$level])){
      #checks if its already been printed
      if(in_array($file[$level],$used)){
	continue;
      }else{
	$used[]=$file[$level];
      }

      #prints directory
      if(isset($file[$level+1])){
        println('<tr><td><a href="manage-pages.php?dir='.$dir.$file[$level].'/">'.$file[$level].'/</a><br /></td>');
        println('<td>/pages/'.$dir.$file[$level].'/</td>');
        println('<td align="center">-</td><td align="center">-</td><td align="center">-</td><td align="center">-</td>');
      }else{
        #prints file
	    println('<tr><td>'.$file[$level].'</td>');
	    println('<td>/pages/'.$dir.$file[$level]).'</td>';
        println('<td align="center">'.date('Y-m-d H:i:s',$tmp['created']).'</td>');
        #Modified
        println('<td align="center">'.date('Y-m-d H:i:s',$tmp['modified']).'</td>');
        #Edit
        println('<td><a href="edit-pages.php?id='.$tmp['id'].'">Edit</a></td>');
        #Delete
        println('<td><a href="'.$_SERVER['PHP_SELF'].'?act=del&id='.$tmp['id'].'">Delete</a></td></tr>');
      }
    }
  }
  println('</table>');
  
  println('<div align="right"><input type="button" value="New" onClick="window.location=\'edit-pages.php?dir='.$dir.'\'" /></div>');
  
  include 'include/admin-footer.php';
?>
