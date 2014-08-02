<?php include '../header.php'; $title='Edit Event'; ?>
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

  adminsonly();
  
  include '../bin/honorsrecital.php';

  // HANDLE POST REQUESTS
  if(!empty($_POST)){ hp(); } 
  function hp(){
    global $error,$dbh;
  
    $id = $_POST['id'];
    $time = strtotime($_POST['date']);
    $starts_at = strtotime($_POST['starts_at']);
	$numrecital = isset($_POST['numrecital']) ? $_POST['numrecital'] : null;
    
    $stmt = $dbh->prepare("UPDATE events SET due_date=FROM_UNIXTIME(?), numrecital=?, starts_at=FROM_UNIXTIME(?) WHERE id=?");
    $res=$stmt->execute( array( $time, $numrecital, $starts_at, $id ) );
 
    header('Location: manage-events.php?updated');
    exit;
  }
  ////
?>



<?php
  
  if( isset($_REQUEST['id']) )
    $id=$_REQUEST['id'];
  else
    die('<p class="err">Invalid ID</p>');
  
  $stmt = $dbh->prepare('SELECT events.id, forms.title, due_date, created_at, (SELECT count(1) FROM registrations WHERE event_id=events.id) as registrants,numrecital,forms.id as form_id,starts_at FROM events,forms WHERE events.form_id=forms.id AND events.id=?');
  
  if(!$stmt->execute( array($id) )){
    //die(print_r($stmt->errorInfo(),true));
    die('<div class="err">Could not connect to the database</div>');
  }
    
  $row = $stmt->fetch();
  extract($row);
  
?>


<?php

  echo '
  <h2>Edit Event</h2>
  <form method="post" action="edit-event.php" name="form" class="form">
    <input type="hidden" name="id" value="'.$id.'" />
  ';
    
    $due_date = date('l, M j Y', strtotime($due_date));
	$due_date2 = date('n/j', strtotime($due_date));
    $created_at = date('n/j', strtotime($created_at));
    $starts_at2 = date('n/j', strtotime($starts_at));
    $dp_date = date('Y, n-1, j', strtotime($due_date));
    $dp_starts_at = date('Y, n-1, j', strtotime($starts_at));

    echo '
      <p><label>Event Name:</label><big><small>'.$starts_at2.'</small> '. $title .'</big></p>
      
	  ';
	  
	if(function_exists("editevent$form_id")){
		eval("echo editevent$form_id(\$row);");
	}
	  
	echo '
	  
      <p><label>Registrations due by:</label>
      
    <input type="hidden" name="date" value="" />
    <span id="date"></span>
      <script type="text/javascript">
        var dp = new DatePicker( new Date('.$dp_date.') );
        dp.setShowNone(false);
        dp.onchange = function(){ document.forms.form.date.value=dp.getDate().toUTCString() }
        dp.onchange()
        $("#date").append( dp.create() );
      </script>
      
      </p>
      
      
      <p><label>Event starts:</label>
      
    <input type="hidden" name="starts_at" value="" />
    <span id="starts_at"></span>
      <script type="text/javascript">
        var dp2 = new DatePicker( new Date('.$dp_starts_at.') );
        dp2.setShowNone(false);
        dp2.onchange = function(){ document.forms.form.starts_at.value=dp2.getDate().toUTCString() }
        dp2.onchange()
        $("#starts_at").append( dp2.create() );
      </script>
      
      </p>
      
    ';

?>

<p><label>&nbsp;</label><input type="submit" name="save" value="Update Event" class="bigbtn" /> &nbsp; <a href="manage-events.php" style="color:#f00">cancel</a></p>
</form>



</div>
