<?php include '../header.php'; $title='Manage Students'; ?>
<?php include '../auth.php' ?>


<div id="nav">
  <?php include '_nav.php' ?>
</div>

<div id="subnav">
  <?php include '_subnav.php' ?>
</div>

<div id="content">
  

<?php

  teachersonly();

  // HANDLE POST REQUESTS
  if(!empty($_POST)){ hp(); } 
  function hp(){
    global $error,$dbh;
  
    if(isset($_POST['delete'])){
      // This loop should only run once
      $id=-1;
      foreach($_POST['delete'] as $k=>$v)
        $id = $k;
        
      $stmt = $dbh->prepare("update students set teacher_id=null WHERE id=?");
      $stmt->execute( array( $id ) );
      
      header('Location: manage-students.php'); 
      exit;
    }
    
    // Update students
    $stmt = $dbh->prepare("UPDATE students SET fname=?, lname=?, home_school=?, info=?, birthdate=FROM_UNIXTIME(?), lengthstudy=?, fallenrollment=?, springenrollment=?, grade=? WHERE id=?");
    $n=1;
    $stmt->bindParam($n++, $fname);
    $stmt->bindParam($n++, $lname);
    $stmt->bindParam($n++, $home_school);
    $stmt->bindParam($n++, $info);
    $stmt->bindParam($n++, $birthdate);
    $stmt->bindParam($n++, $lengthstudy);
    $stmt->bindParam($n++, $fe);
    $stmt->bindParam($n++, $se);
    $stmt->bindParam($n++, $grade);
    $stmt->bindParam($n++, $id);

    foreach($_POST['student'] as $id=>$data){
      extract($data);
      
      $birthdate = strtotime($birthdate);
      $home_school=isset($data['home_school'])?1:0;
      $fe=isset($data['fe'])?1:0;
      $se=isset($data['se'])?1:0;
     
      $stmt->execute();
    }
    
 
    header('Location: manage-students.php?success=1');
    exit;
  }
  ////
?>



<?php
  
  $stmt = $dbh->prepare("SELECT id, fname, lname, home_school, info, birthdate, lengthstudy, fallenrollment, springenrollment, grade FROM students WHERE teacher_id=? order by FIELD(grade,'K','1','2','3','4','5','6','7','8','9','10','11','12','A'),lname,fname");
  
  if(!$stmt->execute( array( id() ) )){
    //die(print_r($stmt,true));
    die('<div class="err">Could not connect to the database</div>');
  }
    
?>


<?php

  echo '
  <h2>Manage Students</h2>';
  
  if(isset($_GET['success']))
    echo '<p class="notice">Students Updated!</p>';
  
  if( $stmt->rowCount()<=0 )
    die('<p class="err">You do not have any students yet. <a href="register-students.php">Register students &#187;</a></p>');
  
  echo '
  <p>After you make any updates press the <tt>Update Students</tt> button.</p>
  <form method="post" action="manage-students.php">
  <p><input type="submit" name="save" value="Update Students" class="bigbtn" /></p>
  <table class="cooltable" cellpadding="5" cellspacing="0" width="100%">
    <tr>
      <!--th width="50">Id</th-->
      <th>First Name</th>
      <th>Last Name</th>
      <th>Grade</th>
      <th width="140">Home Schooled?</th>
      <th>Information</th>
      <th>Birth Date</th>
      <th>Length of Study</th>
      <th>Enrollment</th>
      <th class="na" width="10">&nbsp;</th>
    </tr>
  '; $i=0;
  while($row = $stmt->fetch()){
    list( $id, $fname, $lname, $home_school, $info, $birthdate, $lengthstudy, $fe, $se, $grade  ) = $row;
    
    $home_school=$home_school==1;

    $classes=(++$i%2==0?'alt':'');

    echo '
    <tr class="'.$classes.'">
      <!--td class="small center">'. $id.'</td-->
      <td><input type="text" name="student['.$id.'][fname]" value="'. $fname .'" size="15" class="bigbtn" /></td>
      <td><input type="text" name="student['.$id.'][lname]" value="'. $lname .'" size="15" class="bigbtn" /></td>
      <td  class="center">
        <select name="student['.$id.'][grade]"  class="bigbtn" style="padding:0;">';
          $arr = array('K'=>'K',1=>1, 2=>2, 3=>3, 4=>4, 5=>5, 6=>6, 7=>7, 8=>8, 9=>9, 10=>10, 11=>11,12=>12, 'A'=>'Adult');
          foreach ($arr as $k=>$v){
              echo "<option value='$k' ".($grade==$k?'selected="selected"':'').">$v</option>\n";
            }
      echo '</select>
      </td>
      <td class="center"><label for="home_school'.$id.'" class="bigbtn">Yes</label> <input type="checkbox" name="student['.$id.'][home_school]" id="home_school'.$id.'" '. ($home_school?'checked="checked"':'') .' /></td>
      <td><textarea name="student['.$id.'][info]">'. $info.'</textarea></td>
      <td><input type="text" name="student['.$id.'][birthdate]" value="'. date('m/d/Y', strtotime($birthdate)) .'" size="15" class="bigbtn" /></td>
      <td><input type="text" name="student['.$id.'][lengthstudy]" value="'. $lengthstudy .'" size="15" class="bigbtn" /></td>
      <td align="center">
      	<input type="checkbox" name="student['.$id.'][fe]" id="fe'.$id.'" '.($fe==1?'checked':'').' /> <label for="fe'.$id.'">Fall</label> 
      	<input type="checkbox" name="student['.$id.'][se]" id="se'.$id.'" '.($se==1?'checked':'').' /> <label for="se'.$id.'">Spring</label>
      </td>
      <td class="na"><input src="../images/delete.gif" alt="Delete" title="Delete" name="delete['.$id.']" onclick="return confirm(\'You will lose any unsaved changes...\nDelete this student?\');" type="image" /></td>
    </tr>
    ';

  }
  echo '</table>';
   
  

?>

<p><input type="submit" name="save" value="Update Students" class="bigbtn" /></p>
</form>



</div>
