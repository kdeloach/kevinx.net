<?php include '../header.php'; $title='Manage Events'; ?>
<?php include '../auth.php' ?>

<div id="nav">
  <?php include '_nav.php' ?>
</div>

<div id="subnav">
  <?php include '_subnav.php' ?>
</div>

<div id="content">
  

<?php

  adminsonly();

  // HANDLE POST REQUESTS
  if(!empty($_POST)){ hp(); } 
  function hp(){
    global $error,$dbh;
  
    if(isset($_POST['archive'])){
      foreach($_POST['archive'] as $k=>$v)
        $id = mysql_escape_string($k); 
      
      $stmt = $dbh->prepare("UPDATE events SET archived=1 WHERE id=?");
      $stmt->execute( array($id) );
    }
    else if(isset($_POST['delete'])){
      // This loop should only run once
      $id=-1;
      foreach($_POST['delete'] as $k=>$v)
        $id = $k;
        
      $stmt = $dbh->prepare("DELETE FROM events WHERE id=?");
      $stmt->execute( array( $id ) );
      
      header('Location: manage-events.php'); 
      exit;
    } else if ( isset($_POST['config']) ){
      $id=-1;
      foreach($_POST['config'] as $k=>$v)
        $id = $k;
        
      header('Location: edit-event.php?id='.$k); 
      exit;
    }
 
    header('Location: manage-events.php');
    exit;
  }
  ////
?>



<?php
  
  $stmt = $dbh->prepare('SELECT events.id, forms.title, due_date, created_at, (SELECT count(student_id)+count(student2_id) FROM registrations WHERE event_id=events.id) as registrants, starts_at, numrecital FROM events,forms WHERE events.form_id=forms.id AND archived=0 ORDER BY due_date DESC,created_at DESC');
  
  if(!$stmt->execute()){
    //die(print_r($stmt,true));
    die('<div class="err">Could not connect to the database</div>');
  }
  
  if(isset($_GET['updated']))
    echo '<p class="notice">Event updated!</p>';
    
    
?>

<?php

  echo '
  <h2>Manage Events</h2>';
  
  if( $stmt->rowCount()<=0 ){
    die('<p class="err">There aren\'t any events yet. <a href="new-event.php">Create a new event &#187;</a></p>');
  }
  
  echo '
  <form method="post" action="manage-events.php" name="form">
  <table class="cooltable" cellpadding="5" cellspacing="0" width="100%">
    <tr>
      <!--th width="50">Id</th-->
      <th>Title</th>
      <th>Registrations due date</th>
      <th width="100">Registrants</th>
      <th class="na" width="55">&nbsp;</th>
    </tr>
  '; $i=0;
  while($row = $stmt->fetch()){
    list( $id, $title, $due_date, $created_at, $registrants, $starts_at, $numrecital ) = $row;
    
    $past_due = strtotime($due_date) < time();
    $due_date = date('l, M j Y', strtotime($due_date));
	$due_date2 = date('n/j', strtotime($due_date));
    $created_at = date('n/j', strtotime($created_at));
    $starts_at2 = date('n/j', strtotime($starts_at));
    $classes=(++$i%2==0?'alt':'');
    $title .= ( $numrecital ? " #$numrecital":'' );

    echo '
    <tr class="'.$classes.'">
      <!--td class="small center">'. $id.'</td-->
      <td><small>'.$starts_at2.'</small> '. $title .'</td>
      <td>'.$due_date.' '. ($past_due?' - Registrations closed <input type="submit" name="archive['.$id.']" value="Archive &#187;" />':'') .'</td>
      <td class="center">'.$registrants.'</td>
      <td class="na">
        <input type="image" name="config['.$id.']" title="Edit Event" alt="Edit" src="../images/config.gif"/>
        <input src="../images/delete.gif" alt="Delete" title="Delete" name="delete['.$id.']" onclick="return confirm(\'You will lose any unsaved changes...\nDelete this event?\');" type="image" />
      </td>
    </tr>
    ';

  }
  echo '</table>';
   
  

?>

</form>



</div>
