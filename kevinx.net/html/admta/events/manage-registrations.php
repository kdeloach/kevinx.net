<?php include '../header.php'; $title='Manage Registrations'; ?>
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

  // HANDLE POST REQUESTS
  if(!empty($_POST)){ hp(); } 
  function hp(){
    global $error,$dbh;
  
    if(isset($_POST['delete'])){
      // This loop should only run once
      $id=-1;
      foreach($_POST['delete'] as $k=>$v)
        $id = $k;
        
      $stmt = $dbh->prepare("DELETE FROM registrations WHERE id=?");
      $stmt->execute( array( $id ) );
      
      header('Location: manage-registrations.php'); 
      exit;
    } else if ( isset($_POST['config']) ){
      $id=-1;
      foreach($_POST['config'] as $k=>$v)
        $id = $k;
        
      header('Location: edit-registration.php?id='.$k); 
      exit;
    }
 
    header('Location: manage-registrations.php');
    exit;
  }
  ////
?>



<?php
  
  $stmt = $dbh->prepare('SELECT registrations.id, events.id as event_id, forms.title, events.due_date, students.fname, students.lname, students.id as student_id, starts_at, numrecital FROM events,forms,students,registrations WHERE events.form_id=forms.id AND registrations.event_id=events.id AND archived=0 AND registrations.student_id=students.id AND students.teacher_id=? ORDER BY forms.title ASC,events.due_date DESC,events.created_at DESC');
  
  if(!$stmt->execute( array(id()) )){
    echo(print_r($stmt,true));
    die('<div class="err">Could not connect to the database</div>');
  }
  
  if(isset($_GET['updated']))
    echo '<p class="notice">Registration updated!</p>';
    
    
?>

<?php

  echo '
  <h2>Manage Registrations</h2>';
  
  if( $stmt->rowCount()<=0 ){
    die('<p class="err">There aren\'t any students registered for events. <a href="register-events.php">Register for an event &#187;</a></p>');
  }
  
  echo '
  <form method="post" action="manage-registrations.php" name="form">
  <table class="cooltable" cellpadding="5" cellspacing="0" width="100%">
    <tr>
      <!--th width="50">Id</th-->
	  <th>Student</th>
      <th>Event</th>
      <th class="na" width="55">&nbsp;</th>
    </tr>
  '; $i=0;
  while($row = $stmt->fetch()){
    list( $id, $event_id, $title, $due_date, $fname, $lname, $student_id, $starts_at, $numrecital ) = $row;
    
    $due_date = date('l, M j Y', strtotime($due_date));
	$due_date2 = date('n/j', strtotime($due_date));
	$starts_at2 = date('n/j', strtotime($starts_at));
	$name = "$fname $lname";
    $classes=(++$i%2==0?'alt':'');
    $title .= ( $numrecital ? " #$numrecital":'' );

    echo '
    <tr class="'.$classes.'">
      <!--td class="small center">'. $id.'</td-->
	  <td>'.$name.'</td>
      <td><small>'.$starts_at2.'</small> '. $title .'</td>
      <td class="na">
        <input type="image" name="config['.$id.']" title="Edit" alt="Edit" src="../images/config.gif"/>
        <input src="../images/delete.gif" alt="Delete" title="Delete" name="delete['.$id.']" onclick="return confirm(\'You will lose any unsaved changes...\nDelete this registration?\');" type="image" />
      </td>
    </tr>
    ';

  }
  echo '</table>';
   
  

?>

</form>



</div>
