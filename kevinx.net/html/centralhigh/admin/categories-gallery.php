<?php

  require 'include/admin-header.php';
  require ROOT.PATH_CMS.'include/auth.php';

  if($_POST){
    #updatem onebyone
    foreach($_POST['cat'] as $cats){
      $old=addslashes($cats['old']);
      $new=addslashes($cats['new']);
      //if(!preg_match('/\W/',$new))
        $sql->query("UPDATE gallery SET cat='$new' WHERE cat='$old'")or die(mysql_error());
    }//header("Location: $_SERVER[PHP_SELF]");
  }
  
  #getemall
  $sql->query("SELECT DISTINCT cat,COUNT(cat) AS num FROM gallery GROUP BY cat ORDER BY cat");
  println('<form method="POST">');
  println('<table id="highlight">');
  println('<tr><th></th><th>Category</th></tr>');

  $i=0;
  while($tmp=$sql->fetch_assoc()){
    println("<tr><td>$tmp[num]</td><td>");
    println("<input type=\"hidden\" name=\"cat[$i][old]\" value=\"$tmp[cat]\" />");
    println("<input type=\"text\" name=\"cat[$i][new]\" value=\"$tmp[cat]\" />");
    println("</td></tr>\n");
    $i++;
  }
  println('</table>');
  println('<input type="submit" value="Update" />');
  println('</form>');
  
  require 'include/admin-footer.php';
?>
