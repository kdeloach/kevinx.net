<?php
  
  
  /////////////////////////
  
  function studentDropdown($name, $id=-1){
    global $dbh;
    
    $stmt = $dbh->prepare("SELECT id,fname,lname,grade FROM students WHERE teacher_id=? ORDER BY FIELD(grade,'K','1','2','3','4','5','6','7','8','9','10','11','12','A'),lname,fname");
    
    $str='<select name="'.$name.'" id="'.$name.'">';
    if($stmt->execute( array( id() ) )){
      while($row=$stmt->fetch()){
		$selected=$row['id']==$id?'selected="selected"':'';
        $str.='<option value="'.$row['id'].'" '.$selected.'>'.$row['fname'].' '.$row['lname'].'</option>';
		}
    }
    $str .= '</select>';
	
	if($stmt->rowCount()==0){
		die('<div class="err" style="clear:both">You do not have any students to register. <a href="../students/register-students.php">Register students &#187;</a></div>');
	}
    return $str;
  }

?>