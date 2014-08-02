<?php

// Form inputs

function preferences_form6($monitor, $pickup){
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

  function input_form6(){
    echo '
      <p><label for="student_id">Student Name:</label>'. studentDropdown('student_id') .'</p>
      <p><label for="duration">Playing Duration:</label><input name="duration" type="text" id="duration" /></p>
	  
	  <!-- -->
	  <!--p><label for="seal">Seal Color:</label>
		<select name="seal" id="seal">
			<option>Silver (2 hymns)</option>
			<option>Gold (3 hymns)</option>
		</select>
	  </p-->
	  
	  <table style="float:left; clear:left; margin-bottom: 20px">
		<tr>
			<th>Composition</th>
			<th>Composer</th>
		</tr>
		<tr>
			<td>1. <input type="text" name="composition" size="40" /></td>
			<td><input type="text" name="composer" size="40" /></td>
		</tr>
		<tr>
			<td>2. <input type="text" name="composition2" size="40" /></td>
			<td><input type="text" name="composer2" size="40" /></td>
		</tr>
		<tr>
			<td>3. <input type="text" name="composition3" size="40" /></td>
			<td><input type="text" name="composer3" size="40" /></td>
		</tr>
	</table>
	  
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
  
  function editform6($data){
	extract($data);
    return '
      <p><label for="student_id">Student Name:</label>'. studentDropdown('student_id', $student_id) .'</p>
      <p><label for="duration">Playing Duration:</label><input name="duration" type="text" id="duration" value="'.$duration.'" /></p> 
	  
	  <table style="float:left; clear:left; margin-bottom: 20px">
		<tr>
			<th>Composition</th>
			<th>Composer</th>
		</tr>
		<tr>
			<td>1. <input type="text" name="composition" size="40" value="'.$composition.'" /></td>
			<td><input type="text" name="composer" size="40" value="'.$composer.'" /></td>
		</tr>
		<tr>
			<td>2. <input type="text" name="composition2" size="40" value="'.$composition2.'" /></td>
			<td><input type="text" name="composer2" size="40" value="'.$composer2.'" /></td>
		</tr>
		<tr>
			<td>3. <input type="text" name="composition3" size="40" value="'.$composition3.'" /></td>
			<td><input type="text" name="composer3" size="40" value="'.$composer3.'" /></td>
		</tr>
	</table>
	  
		<p><label for="timepreference">Student Performance Time Preference:</label>
			<select name="timepreference" id="timepreference">
				<option value="2" '. ($timepreference==2?'selected="selected"':'') .'>(No preference)</option>
				<option value="0" '. ($timepreference==0?'selected="selected"':'') .'>Early AM</option>
				<option value="1" '. ($timepreference==1?'selected="selected"':'') .'>Early PM</option>
				<option value="3" '. ($timepreference==3?'selected="selected"':'') .'>Late AM</option>
				<option value="4" '. ($timepreference==4?'selected="selected"':'') .'>Late PM</option>
			</select>
		</p>	
	  <p><label for="norating">Check to request NO RATING:</label><input type="checkbox" name="rating" id="norating" value="0"  '. ($rating==0?'checked="checked"':'') .' /></p>
	  
    ';
  }

// Form output instructions ---------------

  function form6(&$pdf, $row){
    global $students;
    
    ///////////////////////
    // Proccess db info
    extract($row); // Could be any data from the registrations table

    $student1 = $students[$id];
    $student1_name = "$student1[fname] $student1[lname]";
    $grade = $student1['grade'];
    $age = $student1['age'];
    
	// number of hymns
	$hymns = ( !empty($composition) ? 1 : 0 ) +
			 ( !empty($composition2) ? 1 : 0 ) +
			 ( !empty($composition3) ? 1 : 0 );
	
    /////////////////////
    
    setfont($pdf);
    
    // Name
    $pdf->setXY(28.5, 31.5);
    $pdf->Cell(70, LH, $student1_name, NO_BORDER, CELL_RIGHT, LEFT_ALIGN);
    
    // Grade
    $pdf->setXY(118.5, 31.5);
    $pdf->Cell(11, LH, $grade, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
    // Age
    $pdf->setXY(137.5, 31.5);
    $pdf->Cell(9, LH, $age, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
    // Length of study
    $pdf->setXY(171, 31.5);
    $pdf->Cell(25, LH, $lengthstudy, NO_BORDER, CELL_NEXTLINE, LEFT_ALIGN);
    
    // Playing Duration
    $pdf->setXY(42, 39.5);
    $pdf->Cell(33, LH, $duration, NO_BORDER, CELL_NEXTLINE, LEFT_ALIGN);
    
	// Seal Color
	if($hymns>1){
		$off=31.5*(3-$hymns);
	    $pdf->setXY(155+$off, 39.5);
	    $pdf->Cell(9, LH, 'X', NO_BORDER, CELL_NEXTLINE, CENTER_ALIGN);
	}
	
    // Composition 1
    $pdf->setXY(24, 56);
    $pdf->SetFontSize(9);
    $pdf->Cell(84.5, LH, $composition, NO_BORDER, LEFT_ALIGN);
	
    // Composer  1
    $pdf->setXY(122, 55.5);
    $pdf->SetFontSize(9);
    $pdf->Cell(72.5, LH, $composer, NO_BORDER, LEFT_ALIGN);
	
    // Composition 2
    $pdf->setXY(24, 64);
    $pdf->SetFontSize(9);
    $pdf->Cell(84.5, LH, $composition2, NO_BORDER, LEFT_ALIGN);
	
    // Composer  2
    $pdf->setXY(122, 64);
    $pdf->SetFontSize(9);
    $pdf->Cell(72.5, LH, $composer2, NO_BORDER, LEFT_ALIGN);
	
    // Composition 3
    $pdf->setXY(24, 72);
    $pdf->SetFontSize(9);
    $pdf->Cell(84.5, LH, $composition3, NO_BORDER, LEFT_ALIGN);
	
    // Composer  3
    $pdf->setXY(122, 72);
    $pdf->SetFontSize(9);
    $pdf->Cell(72.5, LH, $composer3, NO_BORDER, LEFT_ALIGN);
	
	// NO Rating checkbox
    $pdf->setXY(73.5, 249);
	$pdf->SetFontSize(18);
    $pdf->Cell(5, LH, !$rating ? 'x' : '', NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
  }
  

  function form6_M(&$pdf, $teacher_info, $count=0){
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
		  return form6_M($pdf, $teacher_info, $count);
      }
	  
	  $i++;
    }
    
  }
  


?>