<?php include '../header.php'; $title='Profile'; ?>
<?php include '../auth.php' ?>


<div id="nav">
  <?php include '_nav.php' ?>
</div>

<div id="subnav">
  <?php include '_subnav.php' ?>
</div>

<div id="content">
  
<?php

  $id=$_SESSION['id'];
  if(isset($_REQUEST['id']))
    $id=$_REQUEST['id'];

  if($id!=id() && !isadmin()){
    die('<div class="err">You do not have permission to view this profile</div>');
  }
    
  // HANDLE FORM
  if(!empty($_POST)){ hf(); }
  function hf(){
    global $error, $dbh, $id;
    
    /*
    // I perform this check before a form is even displayed
    // To prevent people from seeing private details of other users
    if($id!=id() && !isadmin()){
      $error['general'][]='You do not have permission to edit this profile';
      return;
    }*/
    
    $admin = (isset($_POST['admin']) ? 1 : 0);
    $user = (isset($_POST['user']) ? $_POST['user'] : $_POST['old_user']);
    $teacher_no = empty($_POST['teacher_no'])?null:$_POST['teacher_no'];
    
    if(isset($_POST['user']) && empty($_POST['user']))
      $error['user'][]='You need a valid Login ID';
    if(empty($_POST['password']))
      $error['password'][]='You need a valid Password';
    if( $_POST['old_admin']!=$admin && !isadmin() )
      $error['admin'][]='Only Administrators can change this';
    if(isset($_POST['user']) && $_POST['user']!=$_POST['old_user'] && !isadmin())
      $error['user'][]='Only Administrators can change this';
      
    if(!empty($error))
      return;

    $stmt = $dbh->prepare("UPDATE users SET user=?, name=?, email=?, pass=?, admin=?, phone=?, address=?, city_zip=?, teacher_no=?, lname=? WHERE id=?"); 
    
    if(!$stmt->execute(array( $user, $_POST['name'], $_POST['email'], $_POST['password'], $admin, $_POST['phone'], $_POST['address'], $_POST['city'], $teacher_no, $_POST['lname'], $_POST['id']  ))){
      debug('errorInfo -> '.print_r($stmt->errorInfo(),true));
      $error['user'][]='Login ID is already in use';
      return;
    }

    if($id==$_SESSION['id']){
      $_SESSION['admin']=$admin;
      $_SESSION['name']=$_POST['name'];
      $_SESSION['teacher_no']=$teacher_no;
    }
    
    header('Location: profile.php?id='.$id.'&success');
    exit;
  }
/// end

  if(isset($_GET['success']))
    echo '<div class="notice" id="">Profile updated successfully!</div>';
    
  $stmt=$dbh->prepare('SELECT user,name,email,pass as password,phone,address,city_zip as city,admin,teacher_no,lname FROM users WHERE id=?');
  
  if(!$stmt->execute(array($id)) || $stmt->rowCount()<=0)
    die('<div class="err">Could not load profile for user</div>');
    
  extract( $stmt->fetch() );

?>


<h2>Profile</h2>

<?php e('general') ?>

<form method="post" action="profile.php" class="form">
<input type="hidden" name="id" value="<?php echo $id;?>" /> 

  <p><label for="user">Login ID:</label> 
  <input type="hidden" name="old_user" value="<?php echo $user?>" />
     <?php if(isadmin()): ?>
     <input type="text" id="user" name="user" <?php v2('user',$user)?>/>
     <?php else: echo $user; endif; ?>
  </p> <?php e('user') ?>
  
  <p><label for="name">First name:</label> 
     <input type="text" id="name" name="name" <?php v2('name',$name)?>/>
  </p>
  
  <p><label for="lname">Last name:</label> 
     <input type="text" id="lname" name="lname" <?php v2('lname',$lname)?>/>
  </p>
  
  <p><label for="email">E-mail:</label> 
     <input type="text" id="email" name="email" <?php v2('email',$email)?>/>
  </p>
  
  <p><label for="teacher_no">Teacher #:</label> 
     <?php if(isadmin()): ?>
      <input type="text" id="teacher_no" name="teacher_no" <?php v2('teacher_no',$teacher_no)?>/>  
     <?php else: echo ($teacher_no?'<input type="hidden" name="teacher_no" value="'.$teacher_no.'" />  '.$teacher_no : '<span class="gray">N/A</span>'); endif; ?>
  </p>
  

  
  <p><label for="phone">Phone:<br/>(including area code)</label> 
     <input type="text" id="phone" name="phone" <?php v2('phone',$phone)?>"/>
  </p>
  
  <p><label for="address">Address:</label> 
     <input type="text" id="address" name="address" <?php v2('address',$address)?>"/>
  </p>
  
  <p><label for="city">City/Zip:</label> 
     <input type="text" id="city" name="city" <?php v2('city',$city)?>"/>
  </p>

  <p><label for="password">Password:</label> 
     <span id="pass1"><a href="#" onclick="document.getElementById('pass1').style.display='none';document.getElementById('pass2').style.display='block';">Change Password</a></span>
     <span id="pass2" style="display:none;">
     <input type="text" id="password" name="password" <?php v2('password',$password)?>/> <input type="button" value="Generate Password" onclick="genpw(document.forms[0].password)" />
     </span>
  </p> <?php e('password') ?>
  
  <p><label for="admin">Administrator?</label> 
  <input type="hidden" name="old_admin" value="<?php echo $admin?>" />
     <?php 
          if(isroot() && $id!=ROOT){ echo '<input type="checkbox" id="admin" name="admin" '.($admin?'checked="checked"':'').'/>'; }  
          else { echo ($admin?'<input type="hidden" name="admin" value="'.$admin.'" />  Yes':'No');  }
     ?>
  </p>  <?php e('admin') ?>
  
  <p><label for="">&nbsp;</label>
     <input type="submit" name="submit" value="Update User" class="bigbtn" /> 
     <input type="reset" name="reset" value="Reset" class="bigbtn" /> 
  </p>
  
</form>

</div>
