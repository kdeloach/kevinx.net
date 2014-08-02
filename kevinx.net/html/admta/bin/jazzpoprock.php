<?php

// Form inputs

function preferences_form5($monitor, $pickup){
  	echo '<p><label for="monitor">Monitoring time preference:</label>
	  		<select name="monitor" id="monitor">
		  			<option value="early_am" '.($monitor=='early_am'?'selected="selected"':'').'>Early AM</option>
		  			<option value="late_am" '.($monitor=='late_am'?'selected="selected"':'').'>Late AM</option>
		  			<option value="early_pm" '.($monitor=='early_pm'?'selected="selected"':'').'>Early PM</option>
		  			<option value="late_pm" '.($monitor=='late_pm'?'selected="selected"':'').'>Late PM</option>
		  			<option value="ex_chair" '.($monitor=='ex_chair'?'selected="selected"':'').'>Chair: Exempt from monitoring</option>
		  			<option value="ex_cochair" '.($monitor=='ex_cochair'?'selected="selected"':'').'>Co-Chair: Exempt from monitoring</option>
		  			<option value="ex_faculty" '.($monitor=='ex_faculty'?'selected="selected"':'').'>Faculty: Exempt from monitoring</option>
		  			<option value="ex_boardmember" '.($monitor=='ex_boardmember'?'selected="selected"':'').'>Board member: Exempt from monitoring</option>
	  		</select>
  	</p>';
  	
  	echo '<p><label for="pickup">Pickup preference:</label>
	  		<select name="pickup" id="pickup">
				<option value="teacher" '.($pickup=='teacher'?'selected="selected"':'').'>Teacher Pickup</option>
				<option value="dropoff" '.($pickup=='dropoff'?'selected="selected"':'').'>Drop off a Strait Music</option>
				<option value="mail" '.($pickup=='mail'?'selected="selected"':'').'>Mail</option>
				<option value="pickup" '.($pickup=='pickup'?'selected="selected"':'').'>Student/Parent Pickup</option>
	  		</select>
  	</p>';
}

  function input_form5(){
    echo '
      <p><label for="student_id">Student Name:</label>'. studentDropdown('student_id') .'</p>
      <p><label for="duration">Playing Duration:</label><input name="duration" type="text" id="duration" /></p>

      <fieldset><legend>Composition #1</legend>
        <p><label for="rs1">Composition:</label><input type="text" name="composition" id="rs1" size="40" /></p>
        <p><label for="rs2">Composer:</label><input type="text" name="composer" id="rs2" size="40" /></p>
      </fieldset>
      
      <fieldset><legend>Composition #2</legend>
        <p><label for="cs1">Composition:</label><input type="text" name="composition2" id="cs1" size="40" /></p>
        <p><label for="cs2">Composer:</label><input type="text" name="composer2" id="cs2" size="40" /></p>
      </fieldset>
	  
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
  
  function editform5($data){
	extract($data);
    return '
      <p><label for="student_id">Student Name:</label>'. studentDropdown('student_id',$student_id) .'</p>
      <p><label for="duration">Playing Duration:</label><input name="duration" type="text" id="duration" value="'.$duration.'" /></p>

      <fieldset><legend>Composition #1</legend>
        <p><label for="rs1">Composition:</label><input type="text" name="composition" id="rs1" size="40" value="'.$composition.'" /></p>
        <p><label for="rs2">Composer:</label><input type="text" name="composer" id="rs2" size="40" value="'.$composer.'" /></p>
      </fieldset>
      
      <fieldset><legend>Composition #2</legend>
        <p><label for="cs1">Composition:</label><input type="text" name="composition2" id="cs1" size="40"  value="'.$composition2.'" /></p>
        <p><label for="cs2">Composer:</label><input type="text" name="composer2" id="cs2" size="40" value="'.$composer2.'" /></p>
      </fieldset>
	  
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

  function form5(&$pdf, $row){
    global $students;
    
    ///////////////////////
    // Proccess db info
    extract($row); // Could be any data from the registrations table

    @$student1 = $students[$id];
    $student1_name = "$student1[fname] $student1[lname]";
    $grade = $student1['grade'];
    $age = $student1['age'];
    
    /////////////////////
    
    setfont($pdf);
    
    // Name
    $pdf->setXY(28, 30);
    $pdf->Cell(70, LH, $student1_name, NO_BORDER, CELL_RIGHT, LEFT_ALIGN);
    
    // Grade
    $pdf->setXY(119, 30);
    $pdf->Cell(11, LH, $grade, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
    // Age
    $pdf->setXY(138, 30);
    $pdf->Cell(9.5, LH, $age, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
    // Length of study
    $pdf->setXY(172, 30);
    $pdf->Cell(24, LH, $lengthstudy, NO_BORDER, CELL_NEXTLINE, LEFT_ALIGN);
    
    // Playing Duration
    $pdf->setXY(43, 38);
    $pdf->Cell(24, LH, $duration, NO_BORDER, CELL_NEXTLINE, LEFT_ALIGN);
    
    //  Composition 1
    $pdf->setXY(21, 51);
    $pdf->SetFontSize(9);
    $pdf->Cell(80, LH, $composition, NO_BORDER, LEFT_ALIGN);
	
    //  Composer 1
    $pdf->setXY(21, 64);
	$pdf->SetFontSize(9);
    $pdf->Cell(80, LH, $composer, NO_BORDER, CELL_RIGHT);
	
    //  Composition 2
    $pdf->setXY(110, 51);
    $pdf->SetFontSize(9);
    $pdf->MultiCell(82.5, LH, $composition2, NO_BORDER, LEFT_ALIGN);    

    // Composer 2
    $pdf->setXY(110, 64);
	$pdf->SetFontSize(9);
    $pdf->Cell(82.5, LH, $composer2, NO_BORDER, CELL_RIGHT);
	
	// NO Rating checkbox
    $pdf->setXY(73.5, 248.5);
	$pdf->SetFontSize(18);
    $pdf->Cell(5, LH, !$rating ? 'x' : '', NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
  }
  
  // Solo Contest Masterlist
  function form5_M(&$pdf, $teacher_info, $count=0){
    global $stmt, $students, $form, $tplidx;

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
    
	// Amount Enclosed
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
    while( $row=$stmt->fetch() ){ $y += $nl; $count++;

      $student = $students[$row['student_id']];
      $student['name'] = "$student[lname], $student[fname]";
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
	  
      if( $i == 20 && $count+1<$stmt->rowCount() ){
		  $pdf->addPage(); 
		  $pdf->useTemplate($tplidx, 0,0,0);
		  return form5_M($pdf, $teacher_info, $count);
      }
      
      $i++;
	  
    }
    
  }
  


?>