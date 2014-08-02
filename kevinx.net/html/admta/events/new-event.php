<?php include '../header.php'; $title='New Event'; ?>
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

  // do not remove this
  include '../bin/honorsrecital.php';
  
  function step3(){
    global $error,$dbh;
    
    $form_id = $_POST['event'];
    $time = strtotime( $_POST['date'] );
    $starts = strtotime( $_POST['starts'] );
	$numrecital = isset($_POST['numrecital']) ? $_POST['numrecital'] : null;
    $stmt = $dbh->prepare( "INSERT INTO events (form_id,due_date,created_at,archived,numrecital,starts_at) VALUES(?, FROM_UNIXTIME(?), NOW(),0,?,FROM_UNIXTIME(?))" );
    if( !$stmt->execute( array($form_id, $time, $numrecital, $starts) ) ){
       print_r( $stmt->errorInfo() );
       //exit;
	   die('<div class="err">Something went wrong</div>');
    }
    
    header('Location: ?success');
    exit;
  }


  // Get current students 
  $stmt = $dbh->prepare("SELECT id,fname,lname,grade FROM students WHERE teacher_id=? ORDER BY FIELD(grade,'K','1','2','3','4','5','6','7','8','9','10','11','12','A'),lname,fname");
  
  $students=array();
  if($stmt->execute( array( id() ) )){
    while($row=$stmt->fetch())
      $students[]=$row;  
  }
  
  $stmt = $dbh->prepare("SELECT id,title FROM forms ORDER BY id DESC");
  
  $forms=array();
  if( $stmt->execute() ){
    while($row=$stmt->fetch())
      $forms[$row['id']]=$row;  
  }
  
  if(isset($_GET['success']))
    echo '<p class="notice">Event created!</p>';
?>

<h2>New Event</h2>

  <form method="post" action="new-event.php" class="form" name="form"> 
   
   
   <?php 
   
	$step = isset($_POST['step']) ? $_POST['step']:1;
	if(!function_exists("step$step"))
		die('<div class="err">I\'m confused!</div>');
	eval("step$step();");
   
    function step1(){
		global $forms; 
	?>
	
		<input type="hidden" name="step" value="2" />
		
	    <p><label for="evt-name">Event Name:</label>
	    
	    <select name="event">
	      <?php
	        foreach( $forms as $row ) {
	            echo "<option value=\"$row[id]\">$row[title]</option>\n";
	        }
	      ?>
	    </select>
		
		<p><label>&nbsp;</label><input type="submit" name="create" value="Next Step" class="bigbtn" /></p>
	    
	    </p>
		<?
	}
	function step2(){
		global $forms;
		$event_id=$_POST['event'];
	?>
	
		<input type="hidden" name="step" value="3" />
		<input type="hidden" name="event" value="<?=$event_id?>" />
		
	    <p><label for="evt-name">Event Name:</label><?=$forms[$_POST['event']]['title']?></p>

		<?
			if(function_exists("newevent$event_id")){
				eval("echo newevent$event_id();");
			}
		?>
		
	    <p><label>Registrations due by:</label>
	    <input type="hidden" name="date" value="" />
	    <span id="date"></span>
	      <script type="text/javascript">
	        var dp = new DatePicker( );
	        dp.setShowNone(false);
	        dp.onchange = function(){ document.forms.form.date.value=dp.getDate().toUTCString() }
	        dp.onchange()
	        $("#date").append( dp.create() );
	      </script>
	    </p>
	    
	    <p><label>Event Date:</label>
	    <input type="hidden" name="starts" value="" />
	    <span id="starts"></span>
	      <script type="text/javascript">
	        var dp2 = new DatePicker( );
	        dp2.setShowNone(false);
	        dp2.onchange = function(){ document.forms.form.starts.value=dp2.getDate().toUTCString() }
	        dp2.onchange()
	        $("#starts").append( dp2.create() );
	      </script>
	    </p>
	    
	    <p><label>&nbsp;</label><input type="submit" name="create" value="Create Event" class="bigbtn" /> &nbsp; <a href="new-event.php" style="color:#f00">cancel</a></p>
	<? } ?>
	    
  </form>

