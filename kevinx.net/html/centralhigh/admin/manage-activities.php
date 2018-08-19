<?php
  include 'include/admin-header.php';
  include 'include/auth.php';
  
  #Check for necessary actions
  if(isset($_GET['act'])&&isset($_GET['id'])){
    $act=$_GET['act'];
    if(is_numeric($_GET['id'])){
      #Delete entry
      if($act=='del'){
        #Retrieve extension and check for existance
        $sql->query("SELECT image FROM activities WHERE id='$_GET[id]'");
        if($tmp=$sql->fetch_assoc()){
          #Delete from db and delete actual file (error suppression)
          $sql->query("DELETE FROM activities WHERE id='$_GET[id]'");
          @unlink(ROOT.PATH."images/clubs/$_GET[id].$tmp[image]");
          echo dialog('Deleted',SUCCESS);
        }
      }
    }
  }
  
  #If Search
  if(!empty($_GET['search'])){
    #Check criteria
    if(!preg_match('/\W/',$_GET['search'])){
      $crit=addslashes($_GET['search']);
      $sql->query("SELECT id,name,cat,image FROM activities WHERE name LIKE '%$crit%' OR cat LIKE '%$crit%' ORDER BY cat");
    }
  }else{#if not pull all
    $sql->query('SELECT id,name,cat,image FROM activities ORDER BY cat');
  }
  #SEARCH FORM#
?>

<form>
<input type="text" name="search" />
<input type="submit" value="Search" />
</form>


<?php
  ##############

  #Print entries
  println('<table width="75%" id="highlight">');

  #Table headers
  println('<tr><th>id</th><th>Name</th><th>Category</th><th>Image</th><th colspan="2"></th></tr>');

  #Print rows
  while($tmp=$sql->fetch_assoc()){
    #image To look for
    $image="/".PATH."images/clubs/$tmp[id].$tmp[image]";
    
    println("<tr><td>$tmp[id]</td><td>$tmp[name]</td><td>$tmp[cat]</td>");
    println("<td align=\"center\"><a href=\"images-activities.php?id=$tmp[id]\">");

    #check if an image exists and prints it if it does
    if(file_exists(ROOT.$image))
      println("<img width=\"50\" src=\"$image\" /><br />");
    else
      println('Add');

    println('</a></td>');
    #delete
    println("<td><a href=\"$_SERVER[PHP_SELF]?act=del&id=$tmp[id]\">Delete</td>");
    #edit
    println("<td><a href=\"edit-activities.php?id=$tmp[id]\">Edit</a></td></tr>\n");
  }
  
  #end table
  println('</table>');
  
  include 'include/admin-footer.php';
  
?>
