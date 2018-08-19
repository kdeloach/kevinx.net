<?php

  include 'include/constants.php';
  include 'include/dbconnect.php';
  
  if($_POST){
    $image=$_FILES['image'];
    $name=addslashes($_POST['name']);
    $email=addslashes($_POST['email']);
    $ext=explode('.',$image['name']);
    $ext=addslashes($ext[sizeof($ext)-1]);
    $tmp_name=$image['tmp_name'];
    $type=addslashes($image['type']);
    $blurb=addslashes($_POST['blurb']);
    $ip=addslashes($_SERVER['REMOTE_ADDR']);
    if(preg_match('/^image\//',$type)&&preg_match('/(gif|jpg|jpeg|png)/i',$ext)){
      $sql->query("INSERT INTO nd_gallery(`id`,`type`,`blurb`,`ext`,`name`,`email`,`ip`) VALUES('','$type','$blurb','$ext','$name','$email','$ip')");
      $id=$sql->insert_id();
      $target="/var/www/kevinx.net/html/centralhigh/images/gallery/$id";
      if(!move_uploaded_file($tmp_name,$target)){
        $sql->query("DELETE FROM nd_gallery WHERE id='$id'");
      }else{
        header("Location: $_SERVER[PHP_SELF]?success");
        exit;
      }
    }
    header("Location: $_SERVER[PHP_SELF]?error");
  }else if(isset($_GET['success'])){
    echo "Thankyou, your image is awaiting approval.";
  }else if(isset($_GET['error'])){
    echo "Sorry, there is a problem with the file you've supplied.";
  }else{
?>
  <script language="javascript">
    function checkForm(){
      bad=false;
      if(!document.imageloader.email.value.match(/^[-\w.]+@([A-z0-9][-A-z0-9]+\.)+[A-z]{2,4}$/)){
        document.imageloader.email.style.background='#f00';
        alert('Invalid email address');
        bad=true;
      }
      if(document.imageloader.name.value.replace(' ','').length<3){
        document.imageloader.name.style.background='#f00';
        alert('Name?');
        bad=true;
      }
      return (bad)?false:true;
    }
  </script>

  <form name="imageloader" enctype="multipart/form-data" method="post" onSubmit="return checkForm()">
    Name:<input type="text" name="name" onChange="this.style.background=#fff;"/><br />
    E-mail:<input type="text" name="email" onChange="this.style.background=#fff" /><br />
    Image:<input type="file" name="image" /><br />
    Description:<input type="text" name="blurb" />
    <input type="submit" name="upload" value="Upload" />
  </form>
<?php
  }
?>
