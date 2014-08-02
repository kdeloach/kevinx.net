<?php include '../header.php'; $title='Download Forms'; ?>
<?php include '../auth.php' ?>


<div id="nav">
  <?php include '_nav.php' ?>
</div>

<div id="subnav">
  <?php include '_subnav.php' ?>
</div>

<div id="content">
  

<?php

  teachersonly();

?>



<?php
  
  $teacher_id = id();
  
  // Select events
  //$stmt = $dbh->prepare('SELECT events.id, forms.title, events.due_date, events.created_at, (SELECT count(student_id)+count(student2_id) FROM registrations WHERE event_id=events.id) as registrants, hasEventForm, hasMasterList, registrations.teacher_id FROM events,forms,registrations WHERE events.form_id=forms.id AND archived=0 AND events.id=registrations.event_id ORDER BY due_date DESC,created_at DESC');
  $stmt = $dbh->prepare('SELECT events.id, forms.title, events.due_date, events.created_at, '
                       .'(SELECT count(student_id)+count(student2_id) FROM registrations as r2,students as s2 WHERE r2.event_id=events.id AND r2.teacher_id=? AND r2.student_id=s2.id AND s2.teacher_id=r2.teacher_id) as registrants, '
					   .'hasEventForm, hasMasterList, starts_at, forms.id as formid, events.numrecital '
					   .'FROM events,forms,registrations '
					   .'WHERE events.form_id=forms.id '
					   .'AND archived=0 '
					   .'AND registrations.teacher_id=? '
					   .'AND events.id=registrations.event_id '
					   .'GROUP BY id '
					   .'ORDER BY due_date DESC,created_at DESC');
					   
  if(!$stmt->execute( array($teacher_id,$teacher_id) )){
    echo(print_r($stmt,true));
    die('<div class="err">Could not connect to the database</div>');
  }
    
?>

<?php

  echo '
  <h2>Download Forms</h2>
  <table width=100% style="margin-bottom:10px"" cellspacing=1 cellpadding=5><tr><td width=200 style="background:#eee">My Forms</td><td style="background:#eee"><a href="../pdf/SA_EnrollmentForm_'.id().'.pdf">Download SA Enrollment Form</a> <img src="../images/small_pdf.gif" alt="PDF" /></td></tr></table>
  ';
  
  if( $stmt->rowCount()<=0 ){
    echo('<p class="err">You have not registered any students for an event. <a href="register-events.php">Register for an event &#187;</a></p>');
  } else {
  
	  echo '
	  <table class="cooltable" cellpadding="5" cellspacing="0" width="100%">
	    <tr>
	      <!--th width="50">Id</th-->
	      <th>Title</th>
	      <th>Event Form</th>
	      <th>Master List Form</th>
	      <th>Registrants</th>
	      <th class="na">&nbsp;</th>
	    </tr>
	  '; $i=0;
	  while($row = $stmt->fetch()){
	    list( $id, $title, $due_date, $created_at, $registrants, $hasEventForm, $hasMasterList, $starts_at2, $formid, $numrecital ) = $row;
	    
	    if( $registrants==0 ) continue;
	    
	    $due_date = date('l, M j Y', strtotime($due_date));
		$due_date2 = date('n/j', strtotime($due_date));
		$starts_at2 = date('n/j', strtotime($starts_at2));
	    $created_at = date('n/j', strtotime($created_at));
	    $classes=(++$i%2==0?'alt':'');
	    $title .= ( $numrecital ? " #$numrecital":'' );

	    echo '
	    <tr class="'.$classes.'">
	      <!--td class="small center">'. $id.'</td-->
	      <td><small>'.$starts_at2.'</small> '. $title .'</td>
	      <td style="text-align:center">'. ( $registrants > 0 && $hasEventForm ? '<a href="../pdf/'. pdfname("$starts_at2 $title $teacher_id $id") .'.pdf">Download</a>' : '-' ) .'</td>';
	      
	      switch($formid){
	      	
	      	// Duet Festival is a special case, it needs two masterlists
	      	case 8: /* ID of Duet Festival (located in 'forms' table in db) */
	      		echo '<td style="text-align:center">'. ( $registrants > 0 && $hasMasterList ? '<a href="../pdf/'. pdfname("Schedule $created_at $title $teacher_id $id") .'.pdf">Schedule</a> - <a href="../pdf/'. pdfname("Masterlist $created_at $title $teacher_id $id") .'.pdf">Master List</a>' : '-' ) .'</td>';
      	    	break;
      	    
      	  	default:
      	  		echo '<td style="text-align:center">'. ( $registrants > 0 && $hasMasterList ? '<a href="../pdf/'. pdfname("Masterlist $created_at $title $teacher_id $id") .'.pdf">Download</a>' : '-' ) .'</td>';
      	    	break;
  	      }
	      
	      echo '<td style="text-align:center">'. $registrants .'</td>
	      <td class="na">&nbsp;</td>
	    </tr>
	    '; 

	  }
	  echo '</table>';
  }
  
  if( isadmin() ){
  	
  	  $where=' id=-1 ';
  	  $show ='';
  	  if(isset($_GET['view'])){
  	  		$show = mysql_escape_string($_GET['view']);
  	  		if($show=='everyone'){
  	  			$where = ' id!=? ';
  	  		} else {
  	  			$where = " id='$show' ";	
  	  		}
  	  }
  	
	  // Select events
	  //$stmt = $dbh->prepare('SELECT events.id, forms.title, events.due_date, events.created_at, (SELECT count(student_id)+count(student2_id) FROM registrations WHERE event_id=events.id) as registrants, hasEventForm, hasMasterList, registrations.teacher_id FROM events,forms,registrations WHERE events.form_id=forms.id AND archived=0 AND events.id=registrations.event_id ORDER BY due_date DESC,created_at DESC');
	  $stmt2 = $dbh->prepare("SELECT name,id,lname, ( select count(distinct rs.event_id) from registrations as rs where rs.teacher_id=users.id) as total FROM users WHERE id!=?");
	  
	  if(!$stmt2->execute( array(id()) )){
	    echo(print_r($stmt2,true));
	    die('<div class="err">Could not connect to the database</div>');
	  }
  
	
	echo '<hr style="clear:both"/>';
	
	$info=array();
	while($row2 = $stmt2->fetch()){
		$info[]=$row2;
	}
	
	echo '<form method="get" action="" class="form"><p style="margin:5px 0"><label for="view">View forms for:</label>
	<select name="view" id="view">
		<option value="everyone">Everyone</option>';
	
	foreach($info as $t){
		$name = "$t[name] $t[lname]";
		$count = $t['total'];
		echo "<option value='$t[id]' ". ($show==$t['id']?'selected="selected"':'') .">$name ($count)</option>\n";		
	}
	echo '</select> <input type="submit" value="Go" /></p></form><hr style="clear: both;" />';
	
$sql = "SELECT name,id,lname FROM users WHERE $where";
$stmt2 = $dbh->prepare($sql);
if(!$stmt2->execute( array(id()) )){
	echo(print_r($stmt2,true));
	die('<div class="err">Could not connect to the database</div>');
}
  
	while($row2 = $stmt2->fetch()){
		$teacher_id = $row2['id'];
		$name = trim("$row2[name] $row2[lname]");
		
		if(empty($name))
			$name = "<em>(No name provided)</em>";
		else
			$name = "<a href='../users/profile.php?id=$teacher_id'>$name</a>";
		
		echo "<table width=100% style='margin-bottom:10px' cellspacing=1 cellpadding=5><tr><td width=200 style='background:#eee'>$name</td><td style='background:#eee'><a href=\"../pdf/SA_EnrollmentForm_$teacher_id.pdf\">Download SA Enrollment Form</a> <img src=\"../images/small_pdf.gif\" alt=\"PDF\" /></td></tr></table>";
		
		$stmt = $dbh->prepare('SELECT events.id, forms.title, events.due_date, events.created_at, '
						   .'(SELECT count(student_id)+count(student2_id) FROM registrations as r2,students as s2 WHERE r2.event_id=events.id AND r2.teacher_id=? AND r2.student_id=s2.id AND s2.teacher_id=r2.teacher_id) as registrants, '
						   .'hasEventForm, hasMasterList, starts_at '
						   .'FROM events,forms,registrations '
						   .'WHERE events.form_id=forms.id '
						   .'AND archived=0 '
						   .'AND registrations.teacher_id=? '
						   .'AND events.id=registrations.event_id '
						   .'GROUP BY id '
						   .'ORDER BY due_date DESC,created_at DESC');
		 
		  if(!$stmt->execute( array($teacher_id, $teacher_id) )){
		    echo(print_r($stmt,true));
		    die('<div class="err">Could not connect to the database</div>');
		  }
		
		if( $stmt->rowCount()<=0 ){
			echo('<p class="err" style="clear:both;margin-left:0">No students registered for events</p>');
		} else {
		/////////////////////
		  echo '
		  <table class="cooltable" cellpadding="5" cellspacing="0" width="100%">
		    <tr>
		      <!--th width="50">Id</th-->
		      <th>Title</th>
		      <th>Event Form</th>
		      <th>Master List Form</th>
		      <th>Registrants</th>
		      <th class="na">&nbsp;</th>
		    </tr>
		  '; $i=0;
		  while($row = $stmt->fetch()){
		    list( $id, $title, $due_date, $created_at, $registrants, $hasEventForm, $hasMasterList, $starts_at ) = $row;
		    
		    $due_date = date('l, M j Y', strtotime($due_date));
			$due_date2 = date('n/j', strtotime($due_date));
			$starts_at2 = date('n/j', strtotime($starts_at));
		    $created_at = date('n/j', strtotime($created_at));
		    $classes=(++$i%2==0?'alt':'');

		    echo '
		    <tr class="'.$classes.'">
		      <!--td class="small center">'. $id.'</td-->
		      <td><small>'.$starts_at2.'</small> '. $title .'</td>
		      <td style="text-align:center">'. ( $registrants > 0 && $hasEventForm ? '<a href="../pdf/'. pdfname("$created_at $title $teacher_id $id") .'.pdf">Download</a>' : '-' ) .'</td>
		      <td style="text-align:center">'. ( $registrants > 0 && $hasMasterList ? '<a href="../pdf/'. pdfname("Masterlist $created_at $title $teacher_id $id") .'.pdf">Download</a>' : '-' ) .'</td>
		      <td style="text-align:center">'. $registrants .'</td>
		      <td class="na">&nbsp;</td>
		    </tr>
		    ';

		  }
		  echo '</table>';
		}
		echo '<p style="clear:both"></p>';
		///////////////		  
		
	}
  

  
  }
  
  function pdfname($str){
    return preg_replace("/[^a-z0-9]/i", '_', $str); 
  }

?>

</form>



</div>
