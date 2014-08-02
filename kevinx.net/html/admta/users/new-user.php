<?php include '../header.php'; $title='New User'; ?>
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
  if(!empty($_POST)){  hp(); }
  function hp(){
    global $error,$dbh;
  
    if(empty($_POST['user']))
      $error['user'][]='You need a valid Login ID';
    if(empty($_POST['password']))
      $error['password'][]='You need a valid Password';
      
    if(!empty($error)){
      debug('$_POST -> '.print_r($_POST,true));
      return;
    }
      
    $stmt = $dbh->prepare("INSERT INTO users (user,name,email,pass,admin,teacher_no,lname) VALUES(?,?,?,?,?,?,?)");

    if(!$stmt->execute(array( $_POST['user'], $_POST['name'], $_POST['email'], $_POST['password'], isset($_POST['admin'])?1:0, empty($_POST['teacher_no'])?null:$_POST['teacher_no'], $_POST['lname'] ) )){
      debug('errorInfo -> '.print_r($stmt->errorInfo(),true));
      debug('$_POST -> '.print_r($_POST,true));
      $error['user'][]='This Login ID is taken.  Please use something else.'; 
    } else {
      
      header('Location: new-user.php?success'); 
      exit;
    }
  }
  // end

  if(isset($_GET['success']))
    echo '<p class="notice">New user created successfully!</p>';
?>

<h2>New User</h2>

<form method="post" action="new-user.php" class="form">

  
  <p><label for="user">Login ID:</label> 
     <input type="text" id="user" name="user" <?php v2('user')?> />
  </p> <?php e('user') ?>
  
  <p><label for="name">First name:</label> 
     <input type="text" id="name" name="name" <?php v2('name')?>/>
  </p>
  <p><label for="lname">Last name:</label> 
     <input type="text" id="lname" name="lname" <?php v2('lname')?>/>
  </p>
  
  <p><label for="email">E-mail: <em class="small gray">(Optional)</em></label> 
     <input type="text" id="email" name="email" <?php v2('email')?>/>
  </p>
  
  <p><label for="teacher_no">Teacher #: <em class="small gray">(Optional)</em></label> 
     <input type="text" id="teacher_no" name="teacher_no" <?php v2('teacher_no')?>/>
  </p>
  
  <p><label for="password">Password:</label> 
     <input type="text" id="password" name="password" <?php v2('password')?>/> <input type="button" value="Generate Password" onclick="genpw(document.forms[0].password)" />
  </p> <?php e('password') ?>
  
  <?php if(isroot()): ?>
    <p><label for="admin">Administrator?</label> 
     <input type="checkbox" id="admin" name="admin" /> 
  </p>
  <?php endif; ?>
  
  <p><label for="">&nbsp;</label>
     <input type="submit" name="submit" value="Create User" class="bigbtn" /> 
  </p>
  
</form>


</div>
