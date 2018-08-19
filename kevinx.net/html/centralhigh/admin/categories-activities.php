<?php

  require 'include/admin-header.php';
  require ROOT.PATH_CMS.'include/auth.php';

  #updates all fields and redirects
  if($_POST){
    foreach($_POST['cat'] as $cats){
      $old=addslashes($cats['old']);
      $new=addslashes($cats['new']);
      $tag=str_replace(' ','',strtolower($new));
      #unnecisary?(sp)
      //if(!preg_match('/\W/',$new))
        $sql->query("UPDATE activities SET cat='$new',tag='$tag' WHERE cat='$old'");
    }
    header("Location: $_SERVER[PHP_SELF]");
    exit;
  }
  
  ##########################################33
  
  #Pull distinct categories and number of
  $sql->query("SELECT DISTINCT cat,COUNT(cat) AS num FROM activities GROUP BY cat ORDER BY cat");

  println('<form method="POST">');
  println('<table id="highlight">');
  println('<tr><th></th><th>Category</th></tr>');

  #iterator for categories
  $i=0;
  #print categories, prolly coulda been a for loop
  while($tmp=$sql->fetch_assoc()){
    println("<tr><td>$tmp[num]</td><td>");
    println("<input type=\"hidden\" name=\"cat[$i][old]\" value=\"$tmp[cat]\" />");
    println("<input type=\"text\" name=\"cat[$i][new]\" value=\"$tmp[cat]\" />");
    println('</td></tr>');
    $i++;
  }
  
  #Close it
  println('</table>');
  println('<input type="submit" value="Update" />');
  println('</form>');
  
  require 'include/admin-footer.php';
?>
