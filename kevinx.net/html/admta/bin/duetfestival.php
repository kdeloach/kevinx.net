<?php

// Form inputs

function preferences_form8($monitor, $pickup){
	return false;
}

  function input_form8(){
    echo '
      <p><label for="student_id">Primo:</label>'. studentDropdown('student_id') .'</p>
	  <p><label for="student2_id">Secondo:</label>'. studentDropdown('student2_id') .'</p>
      <p><label for="duration">Playing Duration:</label><input name="duration" type="text" id="duration" /></p>
      
	<p><label for="rs1">Composition:</label><input type="text" name="composition" id="rs1" size="40" /></p>
	<p><label for="rs2">Composer:</label><input type="text" name="composer" id="rs2" size="40" /></p>

	<p><label for="timepreference">Student Performance Time Preference:</label>
			<select name="timepreference" id="timepreference">
				<option value="2">(No preference)</option>
				<option value="0">Early AM</option>
				<option value="1">Early PM</option>
				<option value="3">Late AM</option>
				<option value="4">Late PM</option>
			</select>
	</p>	
	<p><label for="norating">Check to request NO RATING:</label><input type="checkbox" name="rating" id="norating" value="0" /></p>	  
    ';
  }
  
  function editform8($data){
	extract($data);
    return '
      <p><label for="student_id">Primo:</label>'. studentDropdown('student_id',$student_id) .'</p>
	  <p><label for="student2_id">Secondo:</label>'. studentDropdown('student2_id', $student2_id) .'</p>
      <p><label for="duration">Playing Duration:</label><input name="duration" type="text" id="duration" value="'.$duration.'" /></p>
      
	<p><label for="rs1">Composition:</label><input type="text" name="composition" id="rs1" size="40" value="'.$composition.'" /></p>
	<p><label for="rs2">Composer:</label><input type="text" name="composer" id="rs2" size="40" value="'.$composer.'" /></p>

	<p><label for="timepreference">Student Performance Time Preference:</label>
			<select name="timepreference" id="timepreference">
				<option value="2" '. ($timepreference==2?'selected="selected"':'') .'>(No preference)</option>
				<option value="0" '. ($timepreference==0?'selected="selected"':'') .'>Early AM</option>
				<option value="1" '. ($timepreference==1?'selected="selected"':'') .'>Early PM</option>
				<option value="3" '. ($timepreference==3?'selected="selected"':'') .'>Late AM</option>
				<option value="4" '. ($timepreference==4?'selected="selected"':'') .'>Late PM</option>
			</select>
	</p>	
	<p><label for="norating">Check to request NO RATING:</label><input type="checkbox" name="rating" id="norating" value="0" '. ($rating==0?'checked="checked"':'') .' /></p>	  
    ';
  }

// Form output instructions ---------------

  function form8(&$pdf, $row){
    global $students;
    
    ///////////////////////
    // Proccess db info
    extract($row); // Could be any data from the registrations table

    $student1 = $students[$student_id];
    $student1['name'] = "$student1[fname] $student1[lname]";

    $student2 = $students[$student2_id];
    $student2['name'] = "$student2[fname] $student2[lname]";

    /////////////////////
    
    setfont($pdf);
    
	// Student 1
	/////////////////////
	extract($student1);
	
    // Name
    $pdf->setXY(28, 31.5);
    $pdf->Cell(70, LH, $name, NO_BORDER, CELL_RIGHT, LEFT_ALIGN);
    
    // Grade
    $pdf->setXY(118, 31.5);
    $pdf->Cell(11, LH, $grade, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
    // Age
    $pdf->setXY(137, 31.5);
    $pdf->Cell(9, LH, $age, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
    // Length of study
    $pdf->setXY(171, 31.5);
    $pdf->Cell(24, LH, $lengthstudy, NO_BORDER, CELL_NEXTLINE, LEFT_ALIGN);
    
	// Student 2
	////////////////////
	extract($student2);
	
    // Name
    $pdf->setXY(32, 39.5);
    $pdf->Cell(66, LH, $name, NO_BORDER, CELL_RIGHT, LEFT_ALIGN);
    
    // Grade
    $pdf->setXY(118, 39.5);
    $pdf->Cell(11, LH, $grade, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
    // Age
    $pdf->setXY(137, 39.5);
    $pdf->Cell(9, LH, $age, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
    // Length of study
    $pdf->setXY(171, 39.5);
    $pdf->Cell(24, LH, $lengthstudy, NO_BORDER, CELL_NEXTLINE, LEFT_ALIGN);
	
    // Playing Duration
    $pdf->setXY(42, 47.5);
    $pdf->Cell(33, LH, $duration, NO_BORDER, CELL_NEXTLINE, LEFT_ALIGN);
	
    //  Composition
    $pdf->setXY(21, 63.5);
    $pdf->SetFontSize(9);
    $pdf->Cell(82.5, LH, $composition, NO_BORDER, LEFT_ALIGN);

    //  Composer
    $pdf->setXY(107.5, 63.5);
	$pdf->SetFontSize(9);
    $pdf->Cell(87.5, LH, $composer, NO_BORDER, CELL_RIGHT);
	
	// NO Rating checkbox
    $pdf->setXY(73.5, 248);
	$pdf->SetFontSize(18);
    $pdf->Cell(5, LH, !$rating ? 'x' : '', NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
  }
  
  // Schedule
  function form8_S(&$pdf, $teacher_info, $count=0){
    global $stmt, $students, $form, $tplidx;
    
    setfont($pdf);
    
    ////////////////
    // Teacher stuff
    extract($teacher_info);
    
    $num_pairs = $stmt->rowCount();
    $amount_enclosed = number_format($num_pairs * $form['price'], 2);

	// Teacher name
	$name = "$name $lname";
	$pdf->setXY(39.5, 29.5);
	$pdf->Cell(86, LH, $name, NO_BORDER);
	
	// Amount enclosed
	$pdf->setXY(147, 29.5);
	$pdf->Cell(48, LH, "Amount Enclosed: \$$amount_enclosed", "B"); //B=bottom border
	
    //////////
	
	// number of duet pairs
	$pdf->setXY(65, 37.5);
	$pdf->Cell(13.5, LH, $num_pairs, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
	
	/////////
    
    // for each student registration
    $i=1; $nl=LH*2+2.7; $y = 71-$nl;
    while( $row=$stmt->fetch() ){ $y += $nl; $count++;

      $student1 = $students[$row['student_id']];
      $student1['name'] = "$student1[fname] $student1[lname]";
      $student1['grade'] = str_replace('A', 'Adult', $student1['grade']);
	  
      $student2 = $students[$row['student2_id']];
      $student2['name'] = "$student2[fname] $student2[lname]";
      $student2['grade'] = str_replace('A', 'Adult', $student2['grade']);
	  
	  $p=array('Early AM', 'Early PM', '', 'Late AM', 'Late PM');
	  $preference = $p[$row['timepreference']];
      
	  // student name 1
      $pdf->setXY(35.5, $y);
      $pdf->Cell(70, LH, $student1['name'], NO_BORDER);
	  
	  // student 2 name
      $pdf->setXY(35.5, $y+LH+1);
      $pdf->Cell(70, LH, $student2['name'], NO_BORDER);
      
	  // duration
      $pdf->setXY(107.5, $y);
	  $pdf->setFontSize(9);
      $pdf->Cell(16.5, LH, $row['duration'], NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
	  setFont($pdf);
	  
	  // time preference
      $pdf->setXY(125.5, $y);
	  $pdf->setFontSize(9);
      $pdf->Cell(19, LH, $preference, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
	  setFont($pdf);
	  
      if( $i == 14 && $count+1<$stmt->rowCount() ){
		  $pdf->addPage(); 
		  $pdf->useTemplate($tplidx, 0,0,0);
		  return form8_S($pdf, $teacher_info, $count);
      }
	  
	  $i++;
    }
    
  }
  
  
  function form8_M(&$pdf, $teacher_info, $count=0){
    global $stmt, $students,$form, $tplidx;
    
    setfont($pdf);
    
    ////////////////
    // Teacher stuff
    extract($teacher_info);
    
    $num_students = $stmt->rowCount();
    $amount_enclosed = number_format($num_students * $form['price'], 2);
    
	// Teacher #
    $pdf->setXY(181, 10);
    $pdf->Cell(15.5, 11.8, $teacher_no, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
	// Teacher name
	$name = "$name $lname";
    $pdf->setXY(39, 26.5);
    $pdf->Cell(70, LH, $name, NO_BORDER);
    
	// Phone
    $pdf->setXY(124, 26.5);
    $pdf->Cell(61.5, LH, $phone, NO_BORDER);
	
    // Address
    $pdf->setXY(39, 34.5);
    $pdf->Cell(70, LH, $address, NO_BORDER);
    
	// Email
    $pdf->setXY(124, 34.5);
    $pdf->Cell(61.5, LH, $email, NO_BORDER);
    
	// City, zip
    $pdf->setXY(39, 42.5);
    $pdf->Cell(70, LH, $city_zip, NO_BORDER);
	
	// Event title
    $pdf->setXY(124, 42.5);
    $pdf->Cell(61.5, LH, $form['title'], NO_BORDER);
    
	// # of Students
    $pdf->setXY(47, 50.5);
    $pdf->Cell(60, LH, $num_students, NO_BORDER);
    
	// amount enclosed (not used)
    $pdf->setXY(144, 50.5);
    $pdf->Cell(40, LH, $amount_enclosed, NO_BORDER);
    //////////////
    
    switch($monitor){
    	case 'early_am':
		    $pdf->setXY(76.5, 66.7);
		    $pdf->Cell(4, 4, 'X', NO_BORDER);
    		break;
    	case 'late_am':
		    $pdf->setXY(92.6, 66.7);
		    $pdf->Cell(4, 4, 'X', NO_BORDER);    	
    		break;
    	case 'early_pm':
		    $pdf->setXY(149.6, 66.7);
		    $pdf->Cell(4, 4, 'X', NO_BORDER);
    		break;
    	case 'late_pm':
		    $pdf->setXY(166.1, 66.7);
		    $pdf->Cell(4, 4, 'X', NO_BORDER);
    		break;
    }
 
    switch($pickup){
		case 'teacher':
		    $pdf->setXY(89, 77);
		    $pdf->Cell(4, 4, 'X', NO_BORDER);
			break;
		case 'dropoff': 
		    $pdf->setXY(131, 77);
		    $pdf->Cell(4, 4, 'X', NO_BORDER);
			break;
		case 'mail':
		    $pdf->setXY(151, 77);
		    $pdf->Cell(4, 4, 'X', NO_BORDER);
			break;
		case 'pickup':
		    $pdf->setXY(60, 83);
		    $pdf->Cell(4, 4, 'X', NO_BORDER);
			break;
    }
    
    //////////
    
    // for each student registration
    $i=1; $nl=7.8; $y = 103-$nl;
    while( $row=$stmt->fetch() ){ 
    
      $studentid = $row['student_id'];	
    	
      // This is a hack just for this event
      // This loop needs to execute one or twice only
      
      do {
	      $y += $nl; $count++;
	
	      $student = $students[$studentid];
	      $student['name'] = "$student[fname] $student[lname]";
	      $student['grade'] = str_replace('A', 'Adult', $student['grade']);
	      
		  $p=array('Early AM', 'Early PM', '', 'Late AM', 'Late PM');
		  $preference = $p[$row['timepreference']];
		  
		  // Name
	      $pdf->setXY(37.5, $y);
	      $pdf->Cell(60, LH, $student['name'], NO_BORDER);
	      
		  // Duration
		  $pdf->setFontSize(9);
	      $pdf->setXY(99, $y);
	      $pdf->Cell(21, LH, $row['duration'], NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
		  
		  // Time preference
	      $pdf->setXY(121.5, $y);
	      $pdf->Cell(20.5, LH, $preference, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
		  setFont($pdf);
		  
		  // This should ALWAYS execute
		  if($row['student2_id'] && $studentid!=$row['student2_id']){
		  	$studentid = $row['student2_id'];
		    continue;
		  }
			  
	      if( $i == 20 && $count+1<$stmt->rowCount() ){
			  $pdf->addPage(); 
			  $pdf->useTemplate($tplidx, 0,0,0);
			  return form8_M($pdf, $teacher_info, $count);
	      }
	      
	      $i++;
		  
		  // stop the loop
		  break;
	  } while(true);
	  
    }
    
  }


?>