<?php include '../header.php';?>
<?php include '../auth.php';

if(isadmin())
  header('Location: manage-users.php');
else
  header('Location: profile.php');
exit;

?>


