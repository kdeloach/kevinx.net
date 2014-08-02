<?php
  include '../header.php'; $title='Register for Events'; 
  include '../auth.php' ;
  include '../bin/forminputs.php' ;
  
  include '../bin/solocontest.php';
  include '../bin/baroque.php';
  include '../bin/duetfestival.php';
  include '../bin/honorsrecital.php';
  include '../bin/hymnfestival.php';
  include '../bin/jazzpoprock.php';
  include '../bin/romantic.php';
  include '../bin/theorymusic.php';

?>

<div id="nav">
  <?php include '_nav.php' ?>
</div>

<div id="subnav">
  <?php include '_subnav.php' ?>
</div>

<div id="content">
  

<?php

  teachersonly();

  // HANDLE POST REQUESTS
  if(!empty($_POST)){  hp(); }
  function hp(){
    global $error,$dbh;
    
    if( !isset($_POST['save']) )
      return;
    unset($_POST['save']);
    //$_POST['id']='';  
    
    foreach($_POST as $k=>$v){
      $_POST[ mysql_escape_string($k) ] = mysql_escape_string( $v ); 
    }
    
    //print_r($_POST);
    //exit;
    
    // do not delete, used for the header redirect
    $event_id = $_POST['event_id'];
    
    $keys=array_keys($_POST);
    $vals=array_values($_POST);
    
    $keys=implode(', ', $keys).", created_at";
    $vals="'".implode("', '", $vals)."', NOW()";
    
    $query = "INSERT INTO registrations ($keys) VALUES($vals)";
    
    $stmt = $dbh->prepare($query);
    if( !$stmt->execute() ){
      print_r($stmt);
      //print_r( $stmt->errorInfo(), true );
      die('<div class="err">Database error...  Registration failed.</div>');
      exit;
    }
      
    header('Location: '.$_SCRIPT['SCRIPT_NAME'].'?success&step=3&event='.$event_id);    
    exit;
  }
  // end

  
  // Get events
  $stmt = $dbh->prepare('SELECT events.id, forms.title, created_at, due_date, starts_at, numrecital FROM events,forms WHERE events.form_id=forms.id AND archived=0 ORDER BY due_date DESC,created_at DESC');
  
  $events=array();
  if($stmt->execute() ){
    while($row=$stmt->fetch())
      $events[]=$row;  
  }
  
  if(isset($_GET['success']))
    echo '<p class="notice">Registration successful!</p>';
    
?>

<h2>Register for Events</h2>

<?php
  if( $stmt->rowCount()<=0 ){
    die('<p class="err">There aren\'t any events yet. <a href="new-event.php">Create a new event &#187;</a></p>');
  }  
?>

<style type="text/css">
	.bigbtn option { margin-top: 5px; }
</style>
<form method="post" action="register-events.php" name="form" class="form">
<?php

  // functional wizard
  $step=1;
  if(isset($_REQUEST['step']) && function_exists("step$_REQUEST[step]"))
    $step=$_REQUEST['step'];
  eval("step$step();");
    
  function step1() {
    global $events;
    
    echo '<input type="hidden" name="step" value="2" />';
    echo '<p><label for="event">Event:</label><select name="event" id="event" class="bigbtn">';  
    foreach($events as $evt)  {
      
      $past_due = strtotime($evt['due_date']) < time();
	  $due_date2 = date('n/j', strtotime($evt['due_date']));
      $created_at = date('n/j', strtotime($evt['created_at']));
      $starts_at = date('n/j', strtotime($evt['starts_at']));
      $title = $evt['title'] . ( $evt['numrecital'] ? " #$evt[numrecital]":'' );
      
      echo "<option value=\"$evt[id]\"". ($past_due?' disabled="disabled"':'') .">$starts_at $title ". ($past_due ? ' - Registration closed' : "(deadline: $due_date2)") ."</option>\n";
    }
    echo '</select></p>';
    echo '<p><label>&nbsp;</label><input type="submit" name="register" value="Register" /></p>';
  }
  
  
  function step2() {	
  	global $dbh;
  	
    $event_id = @$_POST['event'];
    
    if(!isset($event_id)){
		echo '<div class="err">Could not locate event</div>';
		return;
    }
    
    $stmt = $dbh->prepare( "SELECT events.id as id,forms.id as formid,forms.title as formtitle,numrecital FROM forms,events WHERE forms.id=events.form_id AND events.id=?" );
    
    if( $stmt->execute( array($event_id) ) ){
      $res=$stmt->fetch();
      extract($res);
    }
    
    $formtitle .= ( $numrecital ? " #$numrecital":'' );
  	
  	$data = array();
  	$data[] = $_POST['event'];
  	$data[] = id();
  	
  	$stmt = $dbh->prepare("SELECT id,monitor,pickup FROM event_preferences WHERE event_id=? AND teacher_id=?");
  	$stmt->execute( $data );
	list($id, $monitor, $pickup) = $stmt->fetch();
	
  	// This step was submitted, save the data then continue to next step
  	if( isset($_POST['savepref']) ){	
  		
  		$data[] = $_POST['monitor'];
  		$data[] = $_POST['pickup'];
  		
  		if(!$id) {
  			$stmt = $dbh->prepare("INSERT INTO event_preferences (event_id,teacher_id,monitor,pickup) VALUES(?,?,?,?)");
  		} else {
  			$data[] = $id;
	  		$stmt = $dbh->prepare("UPDATE event_preferences SET event_id=?, teacher_id=?, monitor=?, pickup=? WHERE id=?");
  		}
	  		
  		if( $stmt->execute( $data ) ){
  			return step3();
  		} else {
  			echo '<div class="notice" style="margin-bottom:15px;background-color:#FE9E8B;">Could not save time preferences for this event.  Please try again.  - <a href="?step=3&event='.$_POST['event'].'" style="color:#f00">skip &gt;</a></div>';
  		}
  	}
  	
    if(function_exists( "preferences_form$formid" )){
      ob_start();
  	  echo '<input type="hidden" name="step" value="2" />';
  	  echo '<input type="hidden" name="event" value="'. $event_id .'" />';
  	  echo '<p><label for="name">Event:</label>'.$formtitle.'</p>';
      eval("\$res=preferences_form$formid(\$monitor, \$pickup);");
      
      // This is a clever hack, which will go to the next step if preferences_form$formid() returns false (has no settings to fill out)
      if($res===false){
      	ob_end_clean();
      	return step3();
      }
      
      echo '<p><input type="submit" name="savepref" value="Next Step" class="bigbtn" />  &nbsp; <a href="register-events.php" style="color:#f00">cancel</a></p>';
      ob_flush();
    } else {
      echo '<div class="err">No form preferences are associated with this event!</div>'; 
    }
  	
  	
  }
  
  
  function step3() {
    global $dbh;
    
    $event_id = @$_REQUEST['event'];
    
    if(!isset($event_id)){
		echo '<div class="err">Could not locate event</div>';
		return;
    }
    
    $id=-1;
    $stmt = $dbh->prepare( "SELECT events.id as id,forms.id as formid,forms.title as formtitle, numrecital FROM forms,events WHERE forms.id=events.form_id AND events.id=?" );
    
    if( $stmt->execute( array($event_id) ) ){
      $res=$stmt->fetch();
      extract($res);
    }
    $formtitle .= ( $numrecital ? " #$numrecital":'' );
    
    if(function_exists( "input_form$formid" )){
      echo '<p><label for="name">Event:</label>'.$formtitle.'</p>';
      eval("input_form$formid();");
      echo '<input type="hidden" name="event_id" value="'.$id.'" />';
      echo '<input type="hidden" name="teacher_id" value="'.id().'" />';
      echo '<p><input type="submit" name="save" value="Register another student" class="bigbtn" />  &nbsp; <a href="register-events.php" style="color:#f00">cancel</a></p>';
    } else {
      echo '<div class="err">No form data is associated with this event!</div>'; 
    }
  }
  
?>

</form>

