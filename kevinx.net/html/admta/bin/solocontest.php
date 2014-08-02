<?php

// Form inputs

function preferences_form3($monitor, $pickup){
  	echo '<input type="hidden" name="monitor" value="" />';
  	
  	echo '<p><label for="pickup">Pickup preference:</label>
	  		<select name="pickup" id="pickup">
				<option value="teacher" '.($pickup=='teacher'?'selected="selected"':'').'>Teacher Pickup</option>
				<option value="dropoff" '.($pickup=='dropoff'?'selected="selected"':'').'>Drop off a Strait Music</option>
				<option value="mail" '.($pickup=='mail'?'selected="selected"':'').'>Mail</option>
				<option value="pickup" '.($pickup=='pickup'?'selected="selected"':'').'>Student/Parent Pickup</option>
	  		</select>
  	</p>';
}

  // Solo Contest
  function input_form3(){
    echo '
      <p><label for="student_id">Student Name:</label>'. studentDropdown('student_id') .'</p>
      <p><label for="duration">Playing Duration:</label><input name="duration" type="text" id="duration" /></p>
      
      <fieldset><legend>Required Selection</legend>
        <p><label for="rs1">Composition:</label><input type="text" name="composition" id="rs1" size="40" /></p>
        <p><label for="rs2">Composer:</label><input type="text" name="composer" id="rs2" size="40" /></p>
      </fieldset>
      
      <fieldset><legend>Contrasting Selection</legend>
        <p><label for="cs1">Composition:</label><input type="text" name="composition2" id="cs1" size="40" /></p>
        <p><label for="cs2">Composer:</label><input type="text" name="composer2" id="cs2" size="40" /></p>
      </fieldset>
	  
	  <p><label for="norating">Check to request NO RATING:</label><input type="checkbox" name="rating" id="norating" value="0" /></p>
    ';
  }
  
  function editform3($data){
	extract($data);
    return '
      <p><label for="student_id">Student Name:</label>'. studentDropdown('student_id',$student_id) .'</p>
      <p><label for="duration">Playing Duration:</label><input name="duration" type="text" id="duration" value="'.$duration.'" /></p>
      
      <fieldset><legend>Required Selection</legend>
        <p><label for="rs1">Composition:</label><input type="text" name="composition" id="rs1" size="40" value="'.$composition.'" /></p>
        <p><label for="rs2">Composer:</label><input type="text" name="composer" id="rs2" size="40" value="'.$composer.'" /></p>
      </fieldset>
      
      <fieldset><legend>Contrasting Selection</legend>
        <p><label for="cs1">Composition:</label><input type="text" name="composition2" id="cs1" size="40" value="'.$composition2.'" /></p>
        <p><label for="cs2">Composer:</label><input type="text" name="composer2" id="cs2" size="40" value="'.$composer2.'" /></p>
      </fieldset>
	  
	  <p><label for="norating">Check to request NO RATING:</label><input type="checkbox" name="rating" id="norating" value="0" '. ($rating==0?'checked="checked"':'') .' /></p>
    ';
  }

// Form output instructions ---------------

  // Solo Contest
  function form3(&$pdf, $row){
    global $students;
    
    ///////////////////////
    // Proccess db info
    extract($row); // Could be any data from the registrations table

    $student1 = $students[$student_id];
    $student1_name = "$student1[fname] $student1[lname]";
    $grade = $student1['grade'];
    $age = $student1['age'];
    
    /////////////////////
    
    setfont($pdf);
    
    // Name
    $pdf->setXY(28, 32.5);
    $pdf->Cell(70, LH, $student1_name, NO_BORDER, CELL_RIGHT, LEFT_ALIGN);
    
    // Grade
    $pdf->setXY(119, 32.5);
    $pdf->Cell(11, LH, $grade, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
    // Age
    $pdf->setXY(138, 32.5);
    $pdf->Cell(9, LH, $age, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
    // Length of study
    $pdf->setXY(172, 32.5);
    $pdf->Cell(22, LH, $lengthstudy, NO_BORDER, CELL_NEXTLINE, LEFT_ALIGN);
    
    // Playing Duration
    $pdf->setXY(42, 40.5);
    $pdf->Cell(33, LH, $duration, NO_BORDER, CELL_NEXTLINE, LEFT_ALIGN);
    
    // Required Composition
    $pdf->setXY(22, 57);
    $pdf->SetFontSize(9);
    $pdf->MultiCell(61, LH, $composition, NO_BORDER, LEFT_ALIGN);
    
    // Constrasting Composition
    $pdf->setXY(111.5, 57);
    $pdf->SetFontSize(9);
    $pdf->MultiCell(63.5, LH, $composition2, NO_BORDER, LEFT_ALIGN);
    
    // Required Composer
    $pdf->setXY(22, 67);
    $pdf->Cell(61, LH, $composer, NO_BORDER, CELL_RIGHT);
    
    // Constrasting Composer
    $pdf->setXY(111.5, 67);
    $pdf->Cell(63.5, LH, $composer2, NO_BORDER, CELL_RIGHT);
	
	// NO Rating checkbox
    $pdf->setXY(74.5, 255);
	$pdf->SetFontSize(18);
    $pdf->Cell(5, LH, !$rating ? 'x' : '', NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
  }
  
  // Solo Contest Masterlist
  function form3_M(&$pdf, $teacher_info, $count=0){
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
    
	// Name
	$name = "$name $lname";
    $pdf->setXY(39, 28.5);
    $pdf->Cell(70, LH, $name, NO_BORDER);
    
	// Phone
    $pdf->setXY(124, 28.5);
    $pdf->Cell(61.5, LH, $phone, NO_BORDER);
    
	// Address
    $pdf->setXY(39, 36.5);
    $pdf->Cell(70, LH, $address, NO_BORDER);
    
	// Email
    $pdf->setXY(124, 36.5);
    $pdf->Cell(61.5, LH, $email, NO_BORDER);
    
	// City, zip
    $pdf->setXY(39, 44.5);
    $pdf->Cell(70, LH, $city_zip, NO_BORDER);
    
	// # of students
    $pdf->setXY(47, 53);
    $pdf->Cell(60, LH, $num_students, NO_BORDER);

	// Amount enclosed
    $pdf->setXY(144, 53);
    $pdf->Cell(40, LH, $amount_enclosed, NO_BORDER);
    //////////
 
    switch($pickup){
		case 'teacher':
		    $pdf->setXY(113, 67.5);
		    $pdf->Cell(4, 4, 'X', NO_BORDER);
			break;
		case 'dropoff': 
		    $pdf->setXY(155.5, 67.5);
		    $pdf->Cell(4, 4, 'X', NO_BORDER);
			break;
		case 'mail':
		    $pdf->setXY(175.5, 67.5);
		    $pdf->Cell(4, 4, 'X', NO_BORDER);
			break;
		case 'pickup':
		    $pdf->setXY(60.5, 74);
		    $pdf->Cell(4, 4, 'X', NO_BORDER);
			break;
    }

    //////////
    
    // for each student registration
    $i=1; $nl=7.6; $y = 113.5-$nl;
    while( $row=$stmt->fetch() ){ 
    	
      $y += $nl;
      $count++;

      $student = $students[$row['student_id']];
      $student['name'] = "$student[lname], $student[fname]";
      $student['grade'] = str_replace('A', 'Adult', $student['grade']);
      
      $pdf->setXY(37.5, $y);
      $pdf->Cell(60, LH, $student['name'], NO_BORDER);
      
      $pdf->setXY(99, $y);
      $pdf->Cell(21, LH, $student['grade'], NO_BORDER, CELL_RIGHT, CENTER_ALIGN);

      if( $i == 18 && $count+1<$stmt->rowCount() ){
		  $pdf->addPage(); 
		  $pdf->useTemplate($tplidx, 0,0,0);
		  return form3_M($pdf, $teacher_info, $count);
      }

      $i++; 
    }
    
  }
  


?>