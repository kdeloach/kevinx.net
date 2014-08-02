<?php include '../header.php'; $title='Manage Users'; ?>
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
  if(!empty($_GET)){

    // IE FIX ;x
    // When you press an input type=image IE sends you button_x and button_y 
    // but not the value...
    foreach($_GET as $k=>$v)
      foreach($v as $real_value=>$who_cares)
        $_GET[$k]=$real_value;
    // end fix

    if( isset($_GET['lock']) && $_GET['lock']!=ROOT && isroot()){
      $stmt=$dbh->prepare('UPDATE users SET locked=? WHERE id=?');
      $stmt->execute(array(1,$_GET['lock']));
    } else if( isset($_GET['unlock']) && isroot() ){
      $stmt=$dbh->prepare('UPDATE users SET locked=? WHERE id=?');
      $stmt->execute(array(0,$_GET['unlock']));
    } else if( isset($_GET['delete']) ){
      $stmt=$dbh->prepare('DELETE FROM users WHERE id=?');
      $stmt->execute(array($_GET['delete']));
    } else if ( isset($_GET['config']) ){
      header('Location: profile.php?id='. $_GET['config']); 
      exit;
    }
    
    
    
  
    header('Location: manage-users.php');
    exit;
  }
  ////
?>



<?php
  
  $stmt = $dbh->prepare('SELECT id, user, name, email, pass, locked, last_logged_in, teacher_no, lname FROM users ORDER BY lname');
  
  if(!$stmt->execute()){
    //die(print_r($stmt,true));
    die('<div class="err">Could not connect to the database</div>');
  }
    
?>


<?php
    
  $legend = array( 
    'green'=>'High Activity',
    'orange'=>'Frequent Activity',
    'yellow'=>'Low Activity',
    'gray'=>'No Activity'
   );

  echo '
  <h2>Manage Users</h2>
  <form method="get" action="manage-users.php">
  <table class="cooltable" cellpadding="5" cellspacing="0" width="100%">
    <tr>
      <!--th width="50">Id</th-->
      <th width="80">Teacher #</th>
      <th>User</th>
      <th>Name</th>
      <th>E-Mail</th>
      <th>Password</th>
      <th class="na">&nbsp;</th>
    </tr>
  '; $i=0; 
  while($row = $stmt->fetch()){
    list( $id, $user, $name, $email, $pass, $locked, $last_login_time, $teacher_no, $lname ) = $row;
    
    $locked=$locked==1;
    if(id()==$id || $id==ROOT || $locked) $locked=true;

    $classes=(++$i%2==0?'alt':'');
    $classes.=($locked?' locked':'');
    
    $status=timeaway($last_login_time);
    
    $name = "$name $lname";
    $email = "<a href='mailto:$email'>$email</a>";
    
    echo '
    <tr class="'.$classes.'">
      <!--td class="small center">'. $id.'</td-->
      <td class="small center">'. n($teacher_no) .'</td>
      <td><img src="../images/smallorb-'.$status.'.gif" alt="'.$legend[$status].'" /> '. $user .'</td>
      <td>'. n($name) .'</td>
      <td>'. n($email) .'</td>
      <td class="spoiler">'. ($locked?'******':$pass) .'</td>
      <td class="na" width="90">'. 
      
      ( $locked?
        '<input type="image" src="../images/locked.gif" alt="Locked" title="Locked" name="unlock['.$id.']" />
         <input type="image" src="../images/config-locked.gif" alt="Config" title="Config (Locked)" onclick="return false" />
         <input type="image" src="../images/delete-locked.gif" alt="Delete" title="Delete (Locked)" onclick="return false" />
        '
        : '<input type="image" src="../images/unlocked.gif" alt="Lock" title="Lock" name="lock['.$id.']"  />
           <input type="image" src="../images/config.gif" alt="Edit" title="Edit User" name="config['.$id.']"  />
           <input type="image" src="../images/delete.gif" alt="Delete" title="Delete" name="delete['.$id.']" onclick="return confirm(\'Delete this user?\');" />
        ' )
      
      .'</td>
    </tr>
    ';

  }
  echo '</table></form>';
  

  echo '<ul style="text-align:right;">';
  foreach($legend as $k=>$v){
    echo '<li style="vertical-align:middle;display:inline;margin-right:20px;" class="small"><img src="../images/smallorb-'.$k.'.gif" alt="'.$k.'" /> '.$v.'</li>';
  }
  echo '</ul>';
  
  

?>



</div>
