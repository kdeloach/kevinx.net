<?php include '../header.php'; $title='Edit Registration'; ?>
<?php include '../auth.php' ?>


<div id="nav">
  <?php include '_nav.php' ?>
</div>

<div id="subnav">
  <?php include '_subnav.php' ?>
</div>

<link type="text/css" rel="styleSheet" href="../datepicker/css/datepicker.css" />
<script type="text/javascript" src="../datepicker/js/datepicker.js"></script>

<div id="content">
  

<?php

  teachersonly();
  
  include '../bin/forminputs.php';  
  
  include '../bin/SA_form.php';
  include '../bin/solocontest.php';
  include '../bin/baroque.php';
  include '../bin/duetfestival.php';
  include '../bin/honorsrecital.php';
  include '../bin/hymnfestival.php';
  include '../bin/jazzpoprock.php';
  include '../bin/romantic.php';
  include '../bin/theorymusic.php';
 

  // HANDLE POST REQUESTS
  if(!empty($_POST)){ hp(); } 
  function hp(){
    global $error,$dbh;
	
	$id = $_POST['id'];
	
	// form values we don't want to save to the database
	unset(
		$_POST['save'],
		$_POST['id']
	);
  
	if(!isset($_POST['rating']))
		$_POST['rating']=1;
	else
		$_POST['rating']=0;
  
    $items=array_values($_POST);
	$items_str=array();
	
	foreach($_POST as $k=>$v)
		$items_str[]="$k=?";
	$items_str=implode(', ', $items_str);
	
	$items[]=$id;
	
	$query="UPDATE registrations SET $items_str WHERE id=?";
	//die($query."\n".print_r($items,true));
    $stmt = $dbh->prepare($query);
    $res=$stmt->execute( $items );
 
    header('Location: manage-registrations.php?updated');
    exit;
  }
  ////
?>



<?php
  
  if( isset($_REQUEST['id']) )
    $id=$_REQUEST['id'];
  else
    die('<p class="err">Invalid ID</p>');
  
  $stmt = $dbh->prepare('SELECT * FROM events,forms,students,registrations WHERE events.form_id=forms.id AND registrations.event_id=events.id AND archived=0 AND registrations.student_id=students.id AND registrations.id=?');
  
  if(!$stmt->execute( array($id) )){
    echo(print_r($stmt->errorInfo(),true));
    die('<div class="err">Could not connect to the database</div>');
  }
    
  $row = $stmt->fetch();
  extract($row);
  
?>


<?php

  echo '
  <h2>Edit Event</h2>
  <form method="post" action="edit-registration.php" name="form" class="form">
    <input type="hidden" name="id" value="'.$id.'" />
  ';
    
    $due_date = date('l, M j Y', strtotime($due_date));
	$due_date2 = date('n/j', strtotime($due_date));
	$starts_at2 = date('n/j', strtotime($starts_at));
    $dp_date = date('Y, n-1, j', strtotime($due_date));
    $title .= ( $numrecital ? " #$numrecital":'' );

    echo '
      <p><label>Event Name:</label><big><small>'.$starts_at2.'</small> '. $title .'</big></p>
      
	  ';

	if(function_exists("editform$form_id")){
		eval("echo editform$form_id(\$row);");
	} else {
		echo '<div class="err" style="clear:both">No form data associated with this event.</div>';
	}

?>

<p><label>&nbsp;</label><input type="submit" name="save" value="Update Event" class="bigbtn" /> <input type="reset" name="reset" value="Reset" class="bigbtn" /> &nbsp; <a href="manage-registrations.php" style="color:#f00">cancel</a></p>
</form>



</div>
