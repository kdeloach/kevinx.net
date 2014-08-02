<?php include '../header.php'; $title='Register Students'; ?>
<?php include '../auth.php' ?>
<?php include '../bin/prices.php' ?>

<link type="text/css" rel="styleSheet" href="../datepicker/css/datepicker.css" />
<script type="text/javascript" src="../datepicker/js/datepicker.js"></script>

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
    
    if(isset($_POST['regclose'])){
    	if(!strtotime($_POST['regclose'])){
    		die('no');
    		$error['general'][]='Invalid date!';
    		return;
    	}
    	
    	// save registration date
    	$fp=fopen(PATH.'bin/regclose.txt','w');
    	fwrite($fp, "".strtotime($_POST['regclose']));
    	fclose($fp);
    	
        header('Location: register-students.php'); 
        exit;
    }
    
    if(isset($_POST['delete'])){
      
      // This loop should only run once
      $id=-1;
      foreach($_POST['delete'] as $k=>$v)
        $id = $k;
        
      $stmt = $dbh->prepare("update students set teacher_id=null WHERE id=?");
      $stmt->execute( array( $id ) );
      
      header('Location: register-students.php'); 
      exit;
    }
  
    if(empty($_POST['fname']))
      $error['general'][]='You need to fill out First Name';
    if(empty($_POST['lname']))
      $error['general'][]='You need to fill out Last Name';
      
    $birthdate = strtotime($_POST['birthdate']);
    if(!$birthdate || $birthdate>=time() )
		$error['general'][]='Invalid birthdate (mm/dd/YYYY format required)';
      
    if(!empty($error)){
      debug('$_POST -> '.print_r($_POST,true));
      return;
    }
      
    $teacher_id = id();
    $hs = isset($_POST['hs'])?1:0;
    $fe = isset($_POST['fe'])?1:0;
    $se = isset($_POST['se'])?1:0;
        
    $stmt = $dbh->prepare("INSERT INTO students (fname,lname,grade,home_school,info,teacher_id,birthdate,lengthstudy,fallenrollment,springenrollment) VALUES(?,?,?,?,?,?,FROM_UNIXTIME(?),?,?,?)");

    if(!$stmt->execute(array( $_POST['fname'], $_POST['lname'], $_POST['grade'], $hs, $_POST['info'], $teacher_id, $birthdate, $_POST['lengthstudy'], $fe, $se  ) )){
      debug('errorInfo -> '.print_r($stmt->errorInfo(),true));
      debug('$_POST -> '.print_r($_POST,true));
      $error['general'][]='An error has occured'; 
    } else {
      
      header('Location: register-students.php?success=1&g='.$_POST['grade']); 
      exit;
    }
  }
  // end


// Get registration close date
$regclose = file_get_contents(PATH.'bin/regclose.txt');
$regclose_str = date('n/j/Y', $regclose);


  // Get current students for autocomplete
  $stmt = $dbh->prepare("SELECT id,fname,lname,grade,fallenrollment as fe,springenrollment as se FROM students WHERE teacher_id=? ORDER BY FIELD(grade,'K','1','2','3','4','5','6','7','8','9','10','11','12','A'),lname,fname");
  
  $students=array();
  if($stmt->execute( array( id() ) )){
    while($row=$stmt->fetch())
      $students[]=$row;  
  }
  
  if(isset($_GET['success']))
    echo '<p class="notice">New student created successfully!</p>';
    
    if($_POST) extract($_POST);
?>

<? if(isadmin()): ?>
<div style="float:right;margin:3px;border:1px solid #555;background:#eee;padding:5px;">
	<h3>Admin only settings:</h3>
	<form method="post" action="register-students.php">
		<label style="float:left" for="reg">Registrations close on this date:<br/>(MM/DD/YYYY format)</label>
		<input type="text" name="regclose" value="<?=$regclose_str?>" id="reg" /> 
		<input type="submit" value="Save" />
	</form>
</div>
<? endif; ?>

<h2>Register Students</h2>

<?
	if( time() > $regclose ){
		$error['general'][]='Registrations are now closed';
		e('general');
		exit;
	} else {
		$timeleft = floor( ( $regclose-time() ) /60/60/24 );
		
		if($timeleft<=0){
			$s='';
			$str = '<strong>today</strong>';
		} else{
			$s = $timeleft!=1 ? 's' : '';
			$str = "in <strong>$timeleft</strong> day$s";
		}
		
		echo "<p>Registrations close $str!</p>";
	}
?>

<p>NOTE: You can press the <tt>TAB</tt> key to skip between fields without using the mouse. Press <tt>SHIFT </tt>and<tt> TAB</tt> together to go back to the previous field.</p>

 <?php e('general') ?>

<form method="post" action="register-students.php" class="float-left clear-left" name="form">

  <table class="cooltable" cellpadding="5" cellspacing="0">
    <tr>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Grade</th>
      <th>Home Schooled?</th>
      <th>Enrollment</th>
      <th class="na">&nbsp</th>
    </tr>
    
    <tr>
      <td class="center"><input type="text" name="fname" id="fname" maxlength="20" class="bigbtn" autocomplete="off" value="<?=@$fname?>" /></td>
      <td class="center"><input type="text" name="lname" id="lname" maxlength="20" class="bigbtn" autocomplete="off" value="<?=@$lname?>" /></td>
      <td  class="center">
        <select name="grade"  class="bigbtn" style="padding:0;">
        <?php $arr = array('K'=>'K',1=>1, 2=>2, 3=>3, 4=>4, 5=>5, 6=>6, 7=>7, 8=>8, 9=>9, 10=>10, 11=>11,12=>12, 'A'=>'Adult');
          foreach ($arr as $k=>$v){
              echo "<option value='$k' ".(isset($_GET['g'])&&$_GET['g']==$k?'selected="selected"':'').">$v</option>\n";
            } ?>
        </select>
      </td>
      <td class="center"><label for="hs" class="bigbtn">Yes</label> <input type="checkbox" name="hs" id="hs" /> </td>
      <td>
      	<input type="checkbox" name="fe" id="fe" checked="checked" /> <label for="fe">Fall</label> 
      	<input type="checkbox" name="se" id="se" /> <label for="se">Spring</label>

      </td>
      <td class="na">&nbsp;</td>
</tr></table>

  <table class="cooltable" cellpadding="5" cellspacing="0">
    <tr>
      <th>Birth Date</th>
      <th>Length of Study</th>
      <th>Information</th>
      <th class="na">&nbsp</th>
    </tr>
    <tr>
      <td valign="top">
      	
    <p><input type="text" name="birthdate" class="bigbtn ac_input" value="<?=@$birthdate?>" /></p>
    <p>Example input:<br>1/13/1995<br>Jan 13 1995</p>

      </td>
      <td valign="top">
      	
    <p><input type="text" name="lengthstudy"  value="<?=@$lengthstudy?>" class="bigbtn ac_input" /></p>

      </td>
      <td class="center"><textarea name="info" rows="7" cols="25"><?=@$info?></textarea></td>
      <td class="na"> 
        <input type="submit" name="submit" value="Register Student" class="bigbtn" style="background:lightgreen;border-style:solid;border-color:#000"/> &nbsp; <input type="reset" value="Reset" style="font-size:11px;background:red;color:white;border-style:solid;border-color:#000" />
      </td>
    </tr>
   </table>
    <hr />
<table><tr><td valign="top">   

<p><?php  $n=count($students);$s=$n>1?'s':''; echo "<strong>$n</strong> student$s registered"; ?></p>

    <table class="cooltable" cellpadding="5" cellspacing="0">  
        <tr>
      <th>First Name</th>
      <th>Last Name</th>
      <th width="">Grade</th>
      <th class="na">&nbsp</th>
    </tr>
    <?php
    
      $i=0; $t_112_fall=0; $t_ka_fall=0; $t_112_spring=0; $t_ka_spring=0;
      $arr_fname=array(); $arr_lname=array();
      foreach($students as $row){
        list($id,$fname,$lname,$grade,$fe,$se)=$row;
        
        if(is_numeric($grade)){
        	if($fe) $t_112_fall++;
        	if($se) $t_112_spring++;
        }
        else {
        	if($fe) $t_ka_fall++;
        	if($se) $t_ka_spring++;
        }
          
        if($grade=='A')$grade='Adult';
        
        $arr_fname[]=addslashes($fname);
        $arr_lname[]=$lname;
        
        echo "
        <tr class='". ($i++%2==0?'alt':'') ."'>
          <td>$fname</td>
          <td>$lname</td>
          <td class='center'>$grade</td>
          <td class='na'><input src=\"../images/delete.gif\" alt=\"Delete\" title=\"Delete\" name=\"delete[$id]\" onclick=\"return confirm('Delete this student?');\" type=\"image\" /></td>
          <td class='na' style='border-left-width:0'>&nbsp;</td>
        
        </tr>
        
        
        ";
      
      }
    
    ?>
    
    
    
    </table>
    
</td><td valign="top">

	<p><a href='<?=URL.'pdf/SA_EnrollmentForm_'.id().'.pdf' ?>'>Download SA Enrollment Form</a> <img src='../images/small_pdf.gif' alt='PDF' /></p>
    
    <table border="0"><tr><td>
    
    <table class="cooltable" cellpadding="5" cellspacing="0">
      <tr>
        <th colspan="4">Fall Enrollment</th>
        <th class="na">&nbsp;</th>
      </tr>
      <tr> 
         <td>Total Gr. 1-12</td> 
         <td><?php echo $t_112_fall ?></td>
         <td><tt>x</tt> $<?=number_format($prices['fall']['grades_1_12'],2)?> = </td>
         <td>$<?php $t=$t_112_fall*$prices['fall']['grades_1_12']; echo number_format($t_112_fall*$prices['fall']['grades_1_12'],2) ?></td>
         <td class="na">&nbsp;</td>
      </tr>
      <tr>
        <td>Total K &amp; Adult</td>
        <td><?php echo $t_ka_fall ?></td>
        <td><tt>x</tt> $<?=number_format($prices['fall']['grades_k_adult'],2)?> = </td>
        <td>$<?php $t+=$t_ka_fall*$prices['fall']['grades_k_adult']; echo number_format($t_ka_fall*$prices['fall']['grades_k_adult'],2) ?></td>
        <td class="na">&nbsp;</td>
      </tr>
      <!--tr>
        <td colspan="3">Teacher Dues</td>
        <td>$<?=number_format($prices['fall']['teacher_dues'],2)?></td>
        <td class="na">&nbsp;</td>
      </tr-->
      <tr>
        <td class="right bold" colspan="3">TOTAL </td>
        <td>$<?php echo number_format($t+$prices['fall']['teacher_dues'],2) ?></td>
        <td class="na">&nbsp;</td>
      </tr>
    </table>
    
    
    </td><td>
    
    <table class="cooltable" cellpadding="5" cellspacing="0">
      <tr>
        <th colspan="4">Spring Enrollment</th>
        <th class="na">&nbsp;</th>
      </tr>
      <tr> 
         <td>Total Gr. 1-12</td> 
         <td><?php echo $t_112_spring ?></td>
         <td><tt>x</tt> $<?=number_format($prices['spring']['grades_1_12'],2)?> = </td>
         <td>$<?php $t=$t_112_spring*$prices['spring']['grades_1_12']; echo number_format($t_112_spring*$prices['spring']['grades_1_12'],2) ?></td>
         <td class="na">&nbsp;</td>
      </tr>
      <tr>
        <td>Total K &amp; Adult</td>
        <td><?php echo $t_ka_spring ?></td>
        <td><tt>x</tt> $<?=number_format($prices['spring']['grades_k_adult'],2)?> = </td>
        <td>$<?php $t+=$t_ka_spring*$prices['spring']['grades_k_adult']; echo number_format($t_ka_spring*$prices['spring']['grades_k_adult'],2) ?></td>
        <td class="na">&nbsp;</td>
      </tr>
      <!--tr>
        <td colspan="3">Teacher Dues</td>
        <td>$<?=number_format($prices['spring']['teacher_dues'],2)?></td>
        <td class="na">&nbsp;</td>
      </tr-->
      <tr>
        <td class="right bold" colspan="3">TOTAL </td>
        <td>$<?php echo number_format($t+$prices['spring']['teacher_dues'],2) ?></td>
        <td class="na">&nbsp;</td>
      </tr>
    </table>
     
    
    </td></tr></table>

    
</td></tr></table>
  
</form>



</div>

<script type="text/javascript">

$(document).ready(function() {

  
	$('#fname').autocomplete(
		'_ajaxnamesuggest.php',
		{
			delay:10,
			minChars:1,
			onItemSelect:function(){ $('#fname').focus() },
			matchSubset:1,
			autoFill:true,
			maxItemsToShow:10,
			extraParams: { a:'fnames' }
		}
	).focus();
	
	$('#lname').autocomplete(
		'_ajaxnamesuggest.php',
		{
			delay:10,
			minChars:1,
			onItemSelect:function(){ $('#lname').focus() },
			matchSubset:1,
			autoFill:true,
			maxItemsToShow:10,
			extraParams: { a:'lnames' }
		}
	)
	
});
</script>