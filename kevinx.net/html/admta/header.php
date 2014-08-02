<?php
error_reporting(E_ALL);
ob_start('templater');

require_once '/var/www/kevinx.net/local_settings.php';

define('URL', BASE_URL . '/admta/');
define('PATH', BASE_PATH . '/html/admta/');

// Root Admin Id in the database
define('ROOT', 1);

function debug($str){
  echo '<script>log("'.addslashes(str_replace("\n",'',$str)).'")</script>';
}

function templater($content){
  global $title, $_debug;
  return '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>'.(isset($title)?$title.' - ':'').'Austin District Music Teachers Association</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  <link href="'.URL.'css/style.css" rel="stylesheet" type="text/css" media="all" />
  <link href="'.URL.'css/jquery.autocomplete.css" rel="stylesheet" type="text/css" media="all" />
  <script type="text/javascript" src="'.URL.'js/jquery-1.2.3.min.js"></script>
  <script type="text/javascript" src="'.URL.'js/jquery.autocomplete.js"></script>
  <script type="text/javascript">
  function log(str){ console.log(str); }
  function genpw(field){
    alpha="abcdefghkmnpqrstuvwxyz" // a few similar looking letters removed
    nums="1234567890"
    pass=""
    
    for(var i=0;i<4;i++){ pass+=alpha.charAt( (Math.random()*alpha.length) ) }
    for(var i=0;i<2;i++){ pass+=nums.charAt( (Math.random()*nums.length) ) }
    
    field.value=pass
  }
  </script>
</head>
<body>

<h1>Austin District Music Teachers Association</h1>
<div style="position:absolute;top:0;right:0;margin:5px 15px;padding:5px;font-size:12px;background:#fff;">You are logged in as '.$_SESSION['name'].'.  <a href="'.URL.'logout.php">Not you?</a></div>

'.$content.'

</body>
</html>';

}

/* prints string if current file is active (for subnavs) */
function a($file, $str='class="active"'){
  if( basename($_SERVER['SCRIPT_NAME'])==$file )
    echo $str;
}

// how much time spent away
// used to determine color of orb...
function timeaway($time){
  $d1=strtotime($time);
  
  // seconds away
  $s=time()-$d1;
  
  $m=$s/60;
  $h=$m/60;
  $day=$h/24;
  $week=$day/7;
  $month=$week/4;
  
  if( $week > 3 )
    return 'gray';
  if( $week > 1 )
    return 'yellow';
  if( $day > 2 )
    return 'orange';
  
  return 'green';
}

// print the error div if an err exists
function e($field){
  global $error;
  
  if(!isset($error) || !isset($error[$field]))
    return 'fail';
  
  $str='';
  foreach($error[$field] as $msg)
    $str.=$msg.'<br/>';
    
  if(!empty($str))
    echo "<div class=\"err\">$str</div>";
}


// if form was submitted but failed, use this to print the value back to the form
function v2($field,$default=''){
  if(isset($_POST[$field])) 
    echo 'value="'.htmlspecialchars($_POST[$field]).'"';
  echo 'value="'.$default.'"';
   
}

function isadmin(){
  return $_SESSION['admin'] ;
}

function adminsonly(){
  if(!isadmin())
    die('<div class="err">This area is for Administrators only</div>'); 
}
function teachersonly(){
  if(!teacher_no())
    die('<div class="err">You must have a Teacher Number to register students.  <a href="../users/profile.php">Enter your Teacher Number &#187;</a></div>'); 
}

function isroot(){
  return $_SESSION['id']==ROOT; 
}

function id(){
  return $_SESSION['id']; 
}

function teacher_no(){
  if(isset($_SESSION['teacher_no'])) 
    return $_SESSION['teacher_no'];
  return false;
}

// this will print blankspace because IE messes up without it
function n($val){
  if($val==null) 
    return '&nbsp;';
  return $val;
}



?>
