<?php

  session_start();
  
  $ref=isset($_REQUEST['ref']) ? $_REQUEST['ref'] : 'index.php';
  
  // If not logged in, send em to this page 
  if( basename($_SERVER['SCRIPT_NAME']) != basename(__FILE__) 
      && !isset($_SESSION['islogged'])
  ){
    header('Location: '.URL.basename(__FILE__) . '?ref='.$_SERVER['REQUEST_URI'] );
    exit;
  } 
  
  require 'bin/dbconnect.php';
  
  
  ////////////////////////////////////////
  // only show this form if you're on this page
  if( basename($_SERVER['SCRIPT_NAME']) == basename(__FILE__) ){
  

  $error='';
  
  $user=v('username');
  $pass=v('password');
  
  // If form submitted
  if($user && $pass){

    
    
    $stmt = $dbh->prepare('SELECT id,name,admin,teacher_no FROM users WHERE (user=? OR email=?) AND pass=?');

    if( $stmt->execute( array($user,$user,$pass) ) ) {
      
      
      
      while( $row = $stmt->fetch() ){
        $_SESSION['islogged']=true;
        $_SESSION['name']=$row['name'];
        $_SESSION['admin']=$row['admin']==1;
        $_SESSION['id']=$row['id'];
        
        if(!empty($row['teacher_no']))
          $_SESSION['teacher_no']=$row['teacher_no'];
        
        $res=$dbh->prepare("UPDATE users SET last_logged_in=NOW() WHERE id=?");
        $res->execute(array($row['id']));
        
        header('Location: '.$ref);
        exit;        
      }
    } 
    
    $error = '<div class="err">Wrong Login ID or Password.</div>'; 

    
    $dbh = null;
  
  }
  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Restricted Area</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <meta name="author" content="kevin deloach">
  <meta name="description" lang="en" content="">
  <meta name="keywords" lang="en" content="">
  <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body onload="document.forms[0].user.focus()">
<div id="content">

<p style="font-size:24px; line-height:48px;"><img src="images/keys.gif" alt="" /> This is a protected area.  Please login to continue.</p>



<form method="post" action="auth.php" class="login form" style="width:350px;float:left;">
<input type="hidden" name="ref" value="<?php echo htmlspecialchars($_REQUEST['ref']) ?>" />
<label for="user">Login ID:</label><span><input type="text" name="username" id="user" value="<?php echo v('username') ?>" /></span><br/>
<label for="pw">Password:</label><span><input type="password" name="password" id="pw" value="<?php echo v('password') ?>" /></span><br/>
<label></label><span><input type="submit" name="submit" value="Login" class="btn" /></span>
</form>

<?php echo $error ?>

</div>

</body>
</html>

<?php }

// functions

function v($name){
  if(isset($_POST[$name])) 
    return htmlspecialchars($_POST[$name]);
  return false;
}

?>