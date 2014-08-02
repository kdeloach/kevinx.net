<?php

// Form inputs

function preferences_form7($monitor, $pickup){
  	echo '<p><label for="monitor">Monitoring time preference:</label>
	  		<select name="monitor" id="monitor">
		  			<option value="early" '.($monitor=='early'?'selected="selected"':'').'>Early</option>
		  			<option value="late" '.($monitor=='late'?'selected="selected"':'').'>Late</option>
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

  function input_form7(){
    echo '

      <p><label for="student_id">Student Name:</label>'. studentDropdown('student_id') .'</p>
      <p><label for="duration">Playing Duration:</label><input name="duration" type="text" id="duration" /></p>
	  
		<p><label for="rs1">Composition:</label><input type="text" name="composition" id="rs1" size="40" /></p>
		<p><label for="rs2">Composer:</label><input type="text" name="composer" id="rs2" size="40" /></p>
		  
		<p><label for="timepreference">Student Performance Time Preference:</label>
			<select name="timepreference" id="timepreference">
				<option value="1">(No preference)</option>
				<option value="0">Early</option>
				<option value="2">Late</option>
			</select>
		</p>	
	  
    ';
  }
  
  function editform7($data){
	extract($data);
    return '
      <p><label for="student_id">Student Name:</label>'. studentDropdown('student_id', $student_id) .'</p>
      <p><label for="duration">Playing Duration:</label><input name="duration" type="text" id="duration" value="'.$duration.'" /></p>
	  
		<p><label for="rs1">Composition:</label><input type="text" name="composition" id="rs1" size="40" value="'.$composition.'" /></p>
		<p><label for="rs2">Composer:</label><input type="text" name="composer" id="rs2" size="40" value="'.$composer.'" /></p>
		  
		<p><label for="timepreference">Student Performance Time Preference:</label>
			<select name="timepreference" id="timepreference">
				<option value="1">(No preference)</option>
				<option value="0" '. ($timepreference=='0'?'selected="selected"':'') .'>Early</option>
				<option value="2" '. ($timepreference=='2'?'selected="selected"':'') .'>Late</option>
			</select>
		</p>	
	  
    ';
  }

  // custom form fields when creating an event
  function newevent7(){
	return '<p><label for="numrecital">Which Honors Recital is being entered?</label>
		<select name="numrecital" id="numrecital">
			<option>1</option>
			<option>2</option>
			<option>3</option>
			<option>4</option>
			<option>5</option>
		</select></p>
	';
  }
  function editevent7($data){
	return '<p><label for="numrecital">Which Honors Recital is being entered?</label>
		<select name="numrecital" id="numrecital">
			<option '. ($data['numrecital']==1?'selected="selected"':'') .'>1</option>
			<option '. ($data['numrecital']==2?'selected="selected"':'') .'>2</option>
			<option '. ($data['numrecital']==3?'selected="selected"':'') .'>3</option>
			<option '. ($data['numrecital']==4?'selected="selected"':'') .'>4</option>
			<option '. ($data['numrecital']==5?'selected="selected"':'') .'>5</option>
		</select></p>
	';
  }
  
// Form output instructions ---------------

  
  function form7_M(&$pdf, $teacher_info, $count=0){
    global $stmt, $students, $form, $tplidx;
    
    setfont($pdf);
    
    ////////////////
    // Teacher stuff
    extract($teacher_info);
    
    $num_students = $stmt->rowCount();
    //$amount_enclosed = number_format($num_students * $form['price'], 2);
    
	// Recital #
	$n=array(0, 13.5, 32, 49, 66);
	$pdf->setLineWidth(0);
    $pdf->Rect(92 + $n[$form['numrecital']-1], 36, 8, 8, 'D');
	$pdf->setLineWidth(0);
	
	// Name
	$name = "$name $lname";
    $pdf->setXY(40, 46);
    $pdf->Cell(69, LH, $name, NO_BORDER);
	
	// Phone
    $pdf->setXY(126, 46);
    $pdf->Cell(57, LH, $phone, NO_BORDER);
	
	// Address
    $pdf->setXY(40, 54);
    $pdf->Cell(69, LH, $address, NO_BORDER);
	
	// Email
    $pdf->setXY(126, 54);
    $pdf->Cell(57, LH, $email, NO_BORDER);
	
	// City
    $pdf->setXY(34, 62);
    $pdf->Cell(56, LH, $city_zip, NO_BORDER);

	// Ommit zip code
	$pdf->setFillColor(255,255,255);
	$pdf->Rect(92, 62, 50, 5, 'F');

    //////////
    
    // for each student registration
    $i=1; $nl=22.7; $y = 75.4-$nl;
    while( $row=$stmt->fetch() ){ $y += $nl; $count++;

      $student = $students[$row['student_id']];
      $student['name'] = "$student[fname] $student[lname]";
      $student['grade'] = str_replace('A', 'Adult', $student['grade']);
      
	  //$p=array('Early AM', 'Early PM', '', 'Late AM', 'Late PM');
	  //$preference = $p[$row['timepreference']];

	  // Name
      $pdf->setXY(39, $y);
      $pdf->Cell(60, LH, $student['name'], NO_BORDER);
      
	  // Time preference
	  $pdf->setFontSize(16);
	  
	  switch( $row['timepreference'] ){
		  case 0: // Early
		      $pdf->setXY(162.5, $y+4);
		      $pdf->Cell(13.5, 5, 'X', NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
		      break;
		      
		  case 2: // Late
		      $pdf->setXY(177.5, $y+4);
		      $pdf->Cell(13.5, 5, 'X', NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
		      break;
	  }
	  setFont($pdf);
	  
	  // Age
      $pdf->setXY(39, $y+LH);
	  $pdf->setFontSize(9);
      $pdf->Cell(10, LH, $student['age'], NO_BORDER, CELL_RIGHT, LEFT_ALIGN);
	  
	  // Composer
      $pdf->setXY(24, $y+15);
      $pdf->Cell(42, LH, $composer, NO_BORDER);
	  
	  //Composition
      $pdf->setXY(68, $y+15);
      $pdf->Cell(107, LH, $composition, NO_BORDER);
	  
	  //Duration
	  $pdf->setFontSize(8);
      $pdf->setXY(177, $y+15);
      $pdf->Cell(14.5, LH, $duration, NO_BORDER);
	  setFont($pdf);
	  
      if( $i == 8 && $count+1<$stmt->rowCount() ){
		  $pdf->addPage(); 
		  $pdf->useTemplate($tplidx, 0,0,0);
		  return form7_M($pdf, $teacher_info, $count);
      }
	  
	  $i++;
	  
    }
    
  }
  


?>