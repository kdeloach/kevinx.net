<?php

// Form inputs

function preferences_form9($monitor, $pickup){
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

  function input_form9(){
    echo '
      <p><label for="student_id">Student Name:</label>'. studentDropdown('student_id') .'</p>
      <p><label for="duration">Playing Duration:</label><input name="duration" type="text" id="duration" /></p>
	  
	  <p><label for="scale">Scale:</label>
	  <select name="scale" id="scale">
<option value="Level 1">Level 1, 5 finger hand position, hands alone or together</option>
<option value="Level 2">Level 2, tetrachord scales</option>
<option value="Level 3">Level 3, scale--no. of octaves at teacher\'s discretion, hands alone</option>
<option value="Level 4">Level 4, Scale--no. of octaves at teacher\'s discretion, hands together</option>
<option value="Level 5">Level 5, Scale--contrary motion, hands together</option>
<option value="Level 6">Level 6, Double note scale--choose 3rds, 6ths, or 10ths--no. of octaves at teacher\'s discretion, hands together</option>
	  </select>
	  </p>
	  <p><label for="cadence">Cadence:</label>
<select name="cadence" id="cadence">
<option value="Level A">Level A, Tonic Chord, broken or solid, hands alone</option>
<option value="Level B">Level B, I-V-I, All root position chords, hands alone</option>
<option value="Level C">Level C, I-IV-I-V-I, All root position chords, hands alone or together</option>
<option value="Level D">Level D, I-V(7)-I, from root position, root only or solid chords in the bass, hands together</option>
<option value="Level E">Level E, I-IV-I-V (and/or V7)-I, from root position, root only or solid chords in the bass, hands together</option>
<option value="Level F">Level F, I-IV-I-V (and/or V7)-I, all inversion, root only or solid chords in the bass, hands together</option>
</select>
	  </p>
      
      <fieldset><legend>Baroque Selection</legend>
        <p><label for="rs1">Composition:</label><input type="text" name="composition" id="rs1" size="40" /></p>
        <p><label for="rs3">Key of:</label><input type="text" name="key1" id="rs3" size="5" /></p>
        <p><label for="rs2">Composer:</label><input type="text" name="composer" id="rs2" size="40" /></p>
      </fieldset>
      
      <fieldset><legend>Classical Selection</legend>
        <p><label for="cs1">Composition:</label><input type="text" name="composition2" id="cs1" size="40" /></p>
        <p><label for="cs3">Key of:</label><input type="text" name="key2" id="rs3" size="5" /></p>
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
  
  function editform9($data){
	extract($data);
    return '
      <p><label for="student_id">Student Name:</label>'. studentDropdown('student_id', $student_id) .'</p>
      <p><label for="duration">Playing Duration:</label><input name="duration" type="text" id="duration" value="'.$duration.'" /></p>
	  
	  <p><label for="scale">Scale:</label>
	  <select name="scale" id="scale">
<option value="Level 1" '. ($scale=='Level 1'?'selected="selected"':'') .'>Level 1, 5 finger hand position, hands alone or together</option>
<option value="Level 2" '. ($scale=='Level 2'?'selected="selected"':'') .'>Level 2, tetrachord scales</option>
<option value="Level 3" '. ($scale=='Level 3'?'selected="selected"':'') .'>Level 3, scale--no. of octaves at teacher\'s discretion, hands alone</option>
<option value="Level 4" '. ($scale=='Level 4'?'selected="selected"':'') .'>Level 4, Scale--no. of octaves at teacher\'s discretion, hands together</option>
<option value="Level 5" '. ($scale=='Level 5'?'selected="selected"':'') .'>Level 5, Scale--contrary motion, hands together</option>
<option value="Level 6" '. ($scale=='Level 6'?'selected="selected"':'') .'>Level 6, Double note scale--choose 3rds, 6ths, or 10ths--no. of octaves at teacher\'s discretion, hands together</option>
	  </select>
	  </p>
	  <p><label for="cadence">Cadence:</label>
<select name="cadence" id="cadence">
<option value="Level A" '. ($cadence=='Level A'?'selected="selected"':'') .'>Level A, Tonic Chord, broken or solid, hands alone</option>
<option value="Level B" '. ($cadence=='Level B'?'selected="selected"':'') .'>Level B, I-V-I, All root position chords, hands alone</option>
<option value="Level C" '. ($cadence=='Level C'?'selected="selected"':'') .'>Level C, I-IV-I-V-I, All root position chords, hands alone or together</option>
<option value="Level D" '. ($cadence=='Level D'?'selected="selected"':'') .'>Level D, I-V(7)-I, from root position, root only or solid chords in the bass, hands together</option>
<option value="Level E" '. ($cadence=='Level E'?'selected="selected"':'') .'>Level E, I-IV-I-V (and/or V7)-I, from root position, root only or solid chords in the bass, hands together</option>
<option value="Level F" '. ($cadence=='Level F'?'selected="selected"':'') .'>Level F, I-IV-I-V (and/or V7)-I, all inversion, root only or solid chords in the bass, hands together</option>
</select>
	  </p>
      
      <fieldset><legend>Baroque Selection</legend>
        <p><label for="rs1">Composition:</label><input type="text" name="composition" id="rs1" size="40" value="'.$composition.'" /></p>
        <p><label for="rs3">Key of:</label><input type="text" name="key1" id="rs3" size="5" value="'.$key1.'" /></p>
        <p><label for="rs2">Composer:</label><input type="text" name="composer" id="rs2" size="40" value="'.$composer.'" /></p>
      </fieldset>
      
      <fieldset><legend>Classical Selection</legend>
        <p><label for="cs1">Composition:</label><input type="text" name="composition2" id="cs1" size="40" value="'.$composition2.'" /></p>
        <p><label for="cs3">Key of:</label><input type="text" name="key2" id="rs3" size="5" value="'.$key2.'" /></p>
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

  function form9(&$pdf, $row){
    global $students;
    
    ///////////////////////
    // Proccess db info
    extract($row); // Could be any data from the registrations table

    $student1 = $students[$id];
    $student1_name = "$student1[fname] $student1[lname]";
    $grade = $student1['grade'];
    
    $age = $student1['age'];

    /////////////////////
    
    setfont($pdf);
    
    // Name
    $pdf->setXY(28, 29.5);
    $pdf->Cell(70, LH, $student1_name, NO_BORDER, CELL_RIGHT, LEFT_ALIGN);
    
    // Grade
    $pdf->setXY(119, 29.5);
    $pdf->Cell(11, LH, $grade, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
    // Age
    $pdf->setXY(138, 29.5);
    $pdf->Cell(9, LH, $age, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
    // Length of study
    $pdf->setXY(172, 29.5);
    $pdf->Cell(22, LH, $lengthstudy, NO_BORDER, CELL_NEXTLINE, LEFT_ALIGN);
    
    // Playing Duration
    $pdf->setXY(162, 37.5);
    $pdf->Cell(33, LH, $duration, NO_BORDER, CELL_NEXTLINE, LEFT_ALIGN);
    
	// Musicical Levels
	// Scale
    $pdf->setXY(58, 38);
    $pdf->SetFontSize(9);
    $pdf->Cell(8, LH, $scale, NO_BORDER, CELL_NEXTLINE, LEFT_ALIGN);
	// Cadence
    $pdf->setXY(82, 38);
    $pdf->SetFontSize(9);
    $pdf->Cell(8, LH, $cadence, NO_BORDER, CELL_NEXTLINE, LEFT_ALIGN);
	
    // Baroque Composition
    $pdf->setXY(21, 54.5);
    $pdf->SetFontSize(9);
    $pdf->Cell(80, LH, $composition, NO_BORDER, LEFT_ALIGN);
	
	// Baroque Key of
    $pdf->setXY(51.5, 48.5);
	setFont($pdf);
    $pdf->Cell(9, LH, $key1, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
    // Baroque Composer
    $pdf->setXY(21, 64.5);
	$pdf->SetFontSize(9);
    $pdf->Cell(80, LH, $composer, NO_BORDER, CELL_RIGHT);
	
    // Classical Composition
    $pdf->setXY(110, 54.5);
    $pdf->SetFontSize(9);
    $pdf->MultiCell(82.5, LH, $composition2, NO_BORDER, LEFT_ALIGN);    
	
	// Classical Key of
    $pdf->setXY(140.5, 48.5);
	setFont($pdf);
    $pdf->Cell(9, LH, $key2, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
	
    // Classical Composer
    $pdf->setXY(110, 64.5);
	$pdf->SetFontSize(9);
    $pdf->Cell(82.5, LH, $composer2, NO_BORDER, CELL_RIGHT);
	
    /*
	// Musicial Requirement
	// 0 - 82.5                         diff=29
	// 1 - 111.5
    $pdf->setXY(82.5 + $musicreq*29, 73);
	$pdf->SetFontSize(18);
    $pdf->Cell(5, LH, 'x', NO_BORDER, CELL_RIGHT, CENTER_ALIGN);*/
	
	// NO Rating checkbox
    $pdf->setXY(74, 246);
	$pdf->SetFontSize(18);
    $pdf->Cell(5, LH, !$rating ? 'x' : '', NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
  }
  
  // Solo Contest Masterlist
  function form9_M(&$pdf, $teacher_info, $count=0){
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
    while( $row=$stmt->fetch() ){ $y += $nl; $count++;

      $student = $students[$row['student_id']];
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
	  
      if( $i == 20 && $count+1<$stmt->rowCount() ){
		  $pdf->addPage(); 
		  $pdf->useTemplate($tplidx, 0,0,0);
		  return form9_M($pdf, $teacher_info, $count);
      }
	  
	  $i++;
    }
    
  }
  


?>