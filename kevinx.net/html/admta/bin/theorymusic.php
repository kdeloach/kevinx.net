<?php

// Form inputs

function preferences_form2($monitor, $pickup){
  	echo '<p><label for="monitor">Monitoring test preference:</label>
	  		<select name="monitor" id="monitor">
		  			<option value="early" '.($monitor=='early'?'selected="selected"':'').'>Early test</option>
		  			<option value="late" '.($monitor=='late'?'selected="selected"':'').'>Late test</option>
	  		</select>
  	</p>';
  	echo '<input type="hidden" name="pickup" value="" />';
}

  function input_form2(){
    echo '
      <p><label for="student_id">Student Name:</label>'. studentDropdown('student_id') .'</p>
      <p><label for="testlevel">Test Level:</label>
		<select name="testlevel" id="testlevel">
			<option value="">(same as grade level)</option>
			<option value="K">Kindergarten</option>
			<option>1</option>
			<option>2</option>
			<option>3</option>
			<option>4</option>
			<option>5</option>
			<option>6</option>
			<option>7</option>
			<option>8</option>
			<option>9</option>
			<option>10</option>
			<option>11</option>
			<option>12</option>
			<option value="A">Adult</option>
		</select></p>
	  
    ';
  }
  
  function editform2($data){
	extract($data);
    return '
      <p><label for="student_id">Student Name:</label>'. studentDropdown('student_id',$student_id) .'</p>
      <p><label for="testlevel">Test Level:</label>
		<select name="testlevel" id="testlevel">
			<option value="" '. ($testlevel==''?'selected="selected"':'') .'>(same as grade level)</option>
			<option value="K" '. ($testlevel=='K'?'selected="selected"':'') .'>Kindergarten</option>
			<option '. ($testlevel==1?'selected="selected"':'') .'>1</option>
			<option '. ($testlevel==2?'selected="selected"':'') .'>2</option>
			<option '. ($testlevel==3?'selected="selected"':'') .'>3</option>
			<option '. ($testlevel==4?'selected="selected"':'') .'>4</option>
			<option '. ($testlevel==5?'selected="selected"':'') .'>5</option>
			<option '. ($testlevel==6?'selected="selected"':'') .'>6</option>
			<option '. ($testlevel==7?'selected="selected"':'') .'>7</option>
			<option '. ($testlevel==8?'selected="selected"':'') .'>8</option>
			<option '. ($testlevel==9?'selected="selected"':'') .'>9</option>
			<option '. ($testlevel==10?'selected="selected"':'') .'>10</option>
			<option '. ($testlevel==11?'selected="selected"':'') .'>11</option>
			<option '. ($testlevel==12?'selected="selected"':'') .'>12</option>
			<option value="A" '. ($testlevel=='A'?'selected="selected"':'') .'>Adult</option>
		</select></p>
	  
    ';
  }

// Form output instructions ---------------


  function form2_M(&$pdf, $teacher_info, $count=0){
    global $stmt, $students,$form,$tplidx;
    
    setfont($pdf);
    
    ////////////////
    // Teacher stuff
    extract($teacher_info);
    
    $num_students = $stmt->rowCount();
    $amount_enclosed = number_format($num_students * $form['price'], 2);
    
    $name = "$name $lname";
    
	// Teacher name
    $pdf->setXY(39, 30.5);
    $pdf->Cell(80, LH, $name, NO_BORDER);
    
	// Phone
    $pdf->setXY(137, 30.5);
    $pdf->Cell(61.5, LH, $phone, NO_BORDER);
	
    // Address
    $pdf->setXY(39, 38.5);
    $pdf->Cell(80, LH, $address, NO_BORDER);
    
	// Email
    $pdf->setXY(137, 38.5);
    $pdf->Cell(61.5, LH, $email, NO_BORDER);
    
	// City, zip
    $pdf->setXY(38, 46.5);
    $pdf->Cell(80, LH, $city_zip, NO_BORDER);
	
	// # of Students
    $pdf->setXY(48, 55);
    $pdf->Cell(12, LH, $num_students, NO_BORDER);
    
	// Amount Enclosed
    $pdf->setXY(97, 55);
    $pdf->Cell(12, LH, $amount_enclosed, NO_BORDER);
    
    //////////////
    $monitor = 'late';
    switch($monitor){
    	case 'early':
		    $pdf->setXY(157, 73);
		    $pdf->Cell(4, 4, 'X', NO_BORDER);
    		break;
    	case 'late':
		    $pdf->setXY(130, 73);
		    $pdf->Cell(4, 4, 'X', NO_BORDER);    	
    		break;
    } 
    
    //////////
    
    // for each student registration
    $i=1; $nl=8.4; $y = 114-$nl;
    while( $row=$stmt->fetch() ){ $y += $nl; $count++;

      $student = $students[$row['student_id']];
      $student['name'] = "$student[lname], $student[fname]";
      $student['grade'] = str_replace('A', 'Adult', $student['grade']);
	  $testlevel = !$row['testlevel'] ? $student['grade'] : $row['testlevel'];

	  // Last  Name
      $pdf->setXY(33, $y);
      $pdf->Cell(50, LH, $student['lname'], NO_BORDER);
	  
	  // First  Name
      $pdf->setXY(87, $y);
      $pdf->Cell(51, LH, $student['fname'], NO_BORDER);
      
	  // Test level
	  $pdf->setXY(141, $y);
	  $pdf->Cell(23, LH, $testlevel, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
  
	  if($student['grade']!=$testlevel){
		  // Grade
	      $pdf->setXY(165, $y);
	      $pdf->Cell(29, LH, $student['grade'], NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
	  }
	  
	  
      if( $i == 16 && $count+1<$stmt->rowCount() ){
		  $pdf->addPage(); 
		  $pdf->useTemplate($tplidx, 0,0,0);
		  return form2_M($pdf, $teacher_info, $count);
      }
      
      $i++;

    }
    
  }
  


?>