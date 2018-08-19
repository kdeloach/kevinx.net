<?php
//exit;
include 'include/constants.php';
include 'include/dbconnect.php';
//include 'include/auth.php';
  if(!empty($_POST['olddate'])&&!empty($_POST['newdate'])){
    //echo "document.write('$_POST[olddate]  $_POST[newdate]');\n";
    if($_POST['olddate']>strtotime('2006-01-01')){
      //echo "document.write('stage2');\n";
      if(preg_match('/^20[0-9]{2}-[01][0-9]-[0-3][0-9]$/',$_POST['newdate'])){
        //echo "document.write('stage3');";
        if($newdate=strtotime($_POST['newdate'])){
          //echo "document.write('insert');\n";
          $sql->query("SELECT unix FROM daily_bulletins WHERE unix='$_POST[olddate]'");
          if($tmp=$sql->fetch_assoc()){
            if($tmp[unix]!=$newdate)
              $sql->query("DELETE FROM daily_bulletins WHERE unix='$newdate'");
            $sql->query("UPDATE daily_bulletins SET unix='$newdate',day='$_POST[newdate]' WHERE unix='$tmp[unix]'");
          }
        }
      }
    }
  }
?>
<table id="highlight" width="75%">
<tr><th width="100">Date (Click to change)</th><th>Content</th></tr>
<?php
$current=date('Y-m-%',mktime());

$sql->query("SELECT * FROM daily_bulletins WHERE day LIKE '$current' ORDER BY day DESC");
while($tmp=$sql->fetch_assoc()){
  $sample=str_replace('<br />','',substr($tmp['content'],0,125).'...');
  echo "      <tr><td><div id=\"$tmp[unix]\" onClick=\"edit($tmp[unix])\">$tmp[day]</div></td>";
  echo "        <td>$sample...</td></tr>\n";
}
?>
</table>
