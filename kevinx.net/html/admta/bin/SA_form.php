<?php
  include '../bin/prices.php';

  function SA_form(&$pdf, $teacher_info, $startwith=null){
    global $stmt, $students, $prices, $tplidx;

    // Teacher stuff
    extract($teacher_info);
    
    // name
    $name = "$name $lname";
    $pdf->setXY(136, 18);
    $pdf->Cell(65, LH, $name, NO_BORDER);
    
    // date
    
    $pdf->setXY(155, 12);
    $pdf->Cell(40, LH, date('m/d/Y'), NO_BORDER);
    
    $pdf->setFillColor(255,255,255);
    $pdf->Rect(20, 18, 22, LH, 'F');
    $pdf->setXY(20, 18); 
    $pdf->Cell(22, LH, date('Y').'-'.(date('Y')+1), NO_BORDER);

    $t_112_fall=0; $t_112_spring=0;
    $t_ka_fall=0; $t_ka_spring=0;
    $total=0;
        
    foreach($students as $row){
	    $fe=$row['fe']==1;
	    $se=$row['se']==1;
	    
        if(is_numeric($row['grade'])){
        	if($fe) $t_112_fall++;
        	if($se) $t_112_spring++;
        }
        else {
        	if($fe) $t_ka_fall++;
        	if($se) $t_ka_spring++;
        }
    }
    
    // Fall
    $pdf->setXY(54.3, 34.5); 
    $pdf->Cell(12.4, LH, $t_112_fall, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
    $pdf->Rect(70.5, 34.5, 11, LH, 'F');
    $pdf->setXY(70.5, 34.5); 
    $pdf->setFontSize(9);
    $pdf->Cell(11, LH, '$'.number_format($prices['fall']['grades_1_12'],2), NO_BORDER, CELL_RIGHT, RIGHT_ALIGN );
    $pdf->setFontSize(12);
    
    $pdf->Rect(85.5, 34.5, 20, LH, 'F');
    $pdf->setXY(85.5, 34.5); 
    $pdf->Cell(20.5, LH, '$'.number_format($t_112_fall*$prices['fall']['grades_1_12'],2), NO_BORDER, CELL_RIGHT, RIGHT_ALIGN);
    
    $pdf->setXY(54.3, 41); 
    $pdf->Cell(12.4, LH, $t_ka_fall, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
    $pdf->Rect(70.5, 41.4, 11, LH, 'F');
    $pdf->setXY(70.5, 41); 
    $pdf->setFontSize(9);
    $pdf->Cell(11, LH, '$'.number_format($prices['fall']['grades_k_adult'],2), NO_BORDER, CELL_RIGHT, RIGHT_ALIGN );
    $pdf->setFontSize(12);
    
    $pdf->Rect(85.5, 41, 20, LH, 'F');
    $pdf->setXY(85.5, 41); 
    $pdf->Cell(20.5, LH, '$'.number_format($t_ka_fall*$prices['fall']['grades_k_adult'],2), NO_BORDER, CELL_RIGHT, RIGHT_ALIGN);
    
    $pdf->Rect(85.8, 48, 20, LH, 'F');
    $pdf->setXY(85.5, 48); 
    //$pdf->Cell(20.5, LH, '$'.number_format($prices['fall']['teacher_dues'],2), NO_BORDER, CELL_RIGHT, RIGHT_ALIGN);
    
    $total = $t_112_fall*$prices['fall']['grades_1_12'] + $t_ka_fall*$prices['fall']['grades_k_adult'] + $prices['fall']['teacher_dues'];
    $pdf->Rect(85.5, 55, 20, LH, 'F');
    $pdf->setXY(85.5, 55); 
    $pdf->Cell(20.5, LH, '$'.number_format($total,2), NO_BORDER, CELL_RIGHT, RIGHT_ALIGN);
    
    // Spring
    $pdf->setXY(146.5, 34.5); 
    $pdf->Cell(11.5, LH, $t_112_spring, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
    $pdf->Rect(162, 34.5, 12.5, LH, 'F');
    $pdf->setXY(162, 34.5); 
    $pdf->setFontSize(9);
    $pdf->Cell(12.5, LH, '$'.number_format($prices['spring']['grades_1_12'],2), NO_BORDER, CELL_RIGHT, RIGHT_ALIGN );
    $pdf->setFontSize(12);
    
    $pdf->Rect(178, 34.5, 20, LH, 'F');
    $pdf->setXY(178.5, 34.5); 
    $pdf->Cell(20, LH, '$'.number_format($t_112_spring*$prices['spring']['grades_1_12'],2), NO_BORDER, CELL_RIGHT, RIGHT_ALIGN);
    
    $pdf->setXY(146.5, 41); 
    $pdf->Cell(11.5, LH, $t_ka_spring, NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
    
    $pdf->Rect(162, 41, 12.5, LH, 'F');
    $pdf->setXY(162, 41); 
    $pdf->setFontSize(9);
    $pdf->Cell(12.5, LH, '$'.number_format($prices['spring']['grades_k_adult'],2), NO_BORDER, CELL_RIGHT, RIGHT_ALIGN );
    $pdf->setFontSize(12);
    
    $pdf->Rect(178, 41.5, 20, LH, 'F');
    $pdf->setXY(178.5, 41); 
    $pdf->Cell(20, LH, '$'.number_format($t_ka_spring*$prices['spring']['grades_k_adult'],2), NO_BORDER, CELL_RIGHT, RIGHT_ALIGN);
    
    $pdf->Rect(178, 48, 20, LH, 'F');
    $pdf->setXY(178.5, 48); 
    //$pdf->Cell(20, LH, '$'.number_format($prices['fall']['teacher_dues'],2), NO_BORDER, CELL_RIGHT, RIGHT_ALIGN);
    
    $total = $t_112_spring*$prices['spring']['grades_1_12'] + $t_ka_spring*$prices['spring']['grades_k_adult'] + $prices['spring']['teacher_dues'];
    $pdf->Rect(178, 55, 17, LH, 'F');
    $pdf->setXY(178.5, 55); 
    $pdf->Cell(20, LH, '$'.number_format($total,2), NO_BORDER, CELL_RIGHT, RIGHT_ALIGN);
    
    //////////
    
    // for each student registration
    $i=0; $nl=8.1; $y = 96-$nl;
   foreach( $students as $k=>$row ){ 
   	 if( isset($startwith) ){
   	 	if( $startwith==$k ){
	   	 	unset($startwith);
   	 	}
	   	 else{
		   	continue;
	   	}
   	 }
   	 
   	  $y += $nl;
	
      if( $i == 20 ){
		  //$students = array_slice( $students, $k, true );
		      	
		  $pdf->addPage(); 
		  $pdf->useTemplate($tplidx, 0,0,0);
		  return SA_form($pdf, $teacher_info, $k);
      }
   	
      $student = $students[$row['id']];
      //unset($students[$row['id']]);
      $student['grade'] = str_replace('A', 'Adult', $student['grade']);
      
      $pdf->setXY(26.5, $y);
      $pdf->Cell(40, LH, $student['lname'], NO_BORDER);
      
      $pdf->setXY(68, $y);
      $pdf->Cell(38, LH, $student['fname'], NO_BORDER);
      
      $pdf->setXY(107, $y);
      $pdf->Cell(12.5, LH, $student['grade'], NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
      
      $pdf->setXY(121, $y);
      $pdf->Cell(24, LH, $student['home_school'] ? 'Y' : '', NO_BORDER, CELL_RIGHT, CENTER_ALIGN);
      
      $pdf->setXY(147, $y);
      $pdf->setFontSize(8);
      $pdf->MultiCell(51.5, LH, $student['info'], NO_BORDER);
      setfont($pdf);
      
      $i++;
    }
    
    return;
  }
  


?>