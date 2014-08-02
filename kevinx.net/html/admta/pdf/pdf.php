<?php

  include '../header.php';
  include '../auth.php';
  //include '../bin/formoutputs.php';
  
  ob_end_clean(); # turn off html templating
  ob_start();

  teachersonly();

  $arr = explode('_', $_GET['event_id']);
  //die( print_r($arr, true) );
  
  //$season=count($arr==4) ? array_pop( $arr ) : null;
  $type = strtolower( array_shift( $arr ) );
  $event_id = mysql_escape_string( array_pop( $arr ) ); # id is the last element in the array
  $teacher_id = mysql_escape_string( array_pop( $arr ) );

  $SA = $type == 'sa'; 
  $masterlist = $type=='masterlist';
  // this is admittedly, a hack
  $schedule = $type=='schedule';
  
  if($SA) {
  	$teacher_id=$event_id;
  }
  
  // PDF libraries
  require '../bin/fpdf153/fpdf.php';
  require '../bin/fpdi-1.2/fpdi.php';
  
  // Event forms
  include '../bin/SA_form.php';
  include '../bin/solocontest.php';
  include '../bin/baroque.php';
  include '../bin/duetfestival.php';
  include '../bin/honorsrecital.php';
  include '../bin/hymnfestival.php';
  include '../bin/jazzpoprock.php';
  include '../bin/romantic.php';
  include '../bin/theorymusic.php';
  

  // Constants -----------------------------

  // Text alignment
  define('LEFT_ALIGN', 'L');
  define('CENTER_ALIGN', 'C');
  define('RIGHT_ALIGN', 'R');
  // Border
  define('NO_BORDER', 0);
  define('BORDER', 1);
  // Can also use combination of L,T,R,B
  // Where to go after the call?
  define('CELL_RIGHT', 0);
  define('CELL_NEXTLINE', 1);
  define('CELL_BELOW', 2);
  // Line-height
  define('LH', 5);
  
  // --------------------------------------
  
  // consistent formatting
  function setfont(&$pdf){
    $pdf->SetFont('Arial','',12);
  }
  
  // Find the form_id
  $stmt = $dbh->prepare("SELECT forms.id,events.numrecital,forms.title,forms.price FROM forms,events WHERE events.form_id=forms.id AND events.id=?");
  
  if( !($res=$stmt->execute(array($event_id))) ){
    die('Event does not exist');
  }
  $form = $stmt->fetch(PDO::FETCH_ASSOC);
  
  // PDF template to use
  
  $suffix='';
  if( $schedule ) $suffix = 'S';
  if( $masterlist ) $suffix = 'M';
  
  if( $SA ){
    $pdfFile = "SA_enroll_form.pdf";
    
    // use the teacher id to find the event id
  }
  else {
    $pdfFile = "Event_Form_$form[id]". (!empty($suffix)?"_$suffix":'') .".pdf";
    
  }
  
  // Make sure the PDF template exists
  if( !file_exists($pdfFile) ){
    die('Event Form does not exist'); 
  }
    
  // Create PDF
  $pdf = new FPDI(); 
  setFont($pdf);
  $pagecount = $pdf->setSourceFile($pdfFile); 
 
  // Get teacher info

  $teacher_info=array();
  if( !$SA ){
   
    $stmt = $dbh->prepare('SELECT *,users.id as tid FROM users,registrations WHERE registrations.event_id=? AND users.id=? LIMIT 1');
    if( !$stmt->execute(array( $event_id, $teacher_id )) ){
      die('Database error 2'); 
    }
    $teacher_info=$stmt->fetch(PDO::FETCH_ASSOC);

	// Duet Festival 
	if($form['id']==8)
		$orderby = "timepreference,lname,fname";
	// Theory and World or Music
	else if($form['id']==2)
		$orderby = "FIELD(grade2,'K','1','2','3','4','5','6','7','8','9','10','11','12','A'),lname,fname";
	else
		//$orderby = "timepreference,FIELD(grade,'K','1','2','3','4','5','6','7','8','9','10','11','12','A'),lname,fname";
		$orderby = "lname,fname";
		
    $query = "SELECT *,IF(ASCII(testlevel)=0, grade, testlevel) as grade2 FROM registrations,students WHERE event_id=? AND student_id=students.id AND registrations.teacher_id=? ORDER BY $orderby";
  
  } else {
  
    $stmt = $dbh->prepare('SELECT * FROM users WHERE id=? LIMIT 1');
    if( !$stmt->execute(array( $teacher_id )) ){
      die('Database error 2'); 
    }
    $teacher_info=$stmt->fetch(PDO::FETCH_ASSOC);

    $query = "SELECT * FROM students WHERE teacher_id=? AND teacher_id=? ORDER BY lname,fname";
  }
  
  
  
  // Get all students
  // Theory and World of Music is the only one where students get ordered by class first
  if($form['id']==2)
  	$orderby = "FIELD(grade,'K','1','2','3','4','5','6','7','8','9','10','11','12','A'),lname,fname";
  else
	$orderby = "lname,fname";  	

  $stmt = $dbh->prepare("SELECT *, FLOOR(DATEDIFF(CURDATE(),birthdate)/(30*12)) as age, fallenrollment as fe, springenrollment as se  FROM students WHERE teacher_id=? ORDER BY $orderby");

  if(!$stmt->execute( array( $teacher_id ) )){
    die('You do not have any students');
  }
  $students=array();
    while($row=$stmt->fetch(PDO::FETCH_ASSOC))
      $students[ $row['id'] ]=$row;
  
      
  // Get the data, do not mess with this
  //die($query);
  $stmt = $dbh->prepare($query);
  
  if( !$stmt->execute(array( $event_id, $teacher_id )) ){
	echo(print_r($stmt,true));
    die('Database error'); 
  }
    
  
  
  if( $masterlist || $schedule  ){
    // Masterlist template
    
    $tplidx = $pdf->ImportPage(1); 
    $pdf->addPage(); 
    $pdf->useTemplate($tplidx, 0,0,0); 
    
    $stmt2 = $dbh->prepare("select monitor,pickup from event_preferences where event_id=? and teacher_id=? limit 1");
	  if( !$stmt2->execute(array( $event_id, $teacher_id )) ){
		echo(print_r($stmt,true));
	    die('Database error'); 
	  }
	  
	list($monitor, $pickup) = $stmt2->fetch(PDO::FETCH_NUM);
    $teacher_info['monitor'] = $monitor;
    $teacher_info['pickup'] = $pickup;
    
    $funcname = "form$form[id]_$suffix";
    
    if( !function_exists($funcname) ){
    	die("PDF information for this form does not exist!  ($funcname)");
    }
    
    eval("$funcname(\$pdf, \$teacher_info);");
    
  } elseif( $SA ){
    // Student enrollment
    
    $tplidx = $pdf->ImportPage(1); 
    $pdf->addPage(); 
    $pdf->useTemplate($tplidx, 0,0,0); 
    
    SA_form($pdf, $teacher_info);
  } else {
    // Event form template
    
    $page=1;
    
    // Generate the pages
    while( $row=$stmt->fetch() ){
      $tplidx = $pdf->ImportPage(1); 
      $pdf->addPage(); 
      $pdf->useTemplate($tplidx, 0,0,0); 
      
      eval("form$form[id](\$pdf, \$row);");
    }
    
  }
  
  ob_end_clean();
  
  @$pdf->Output(); 
  $pdf->closeParsers();

?>