<script language="javascript">
<?php
exit;
include 'include/constants.php';
include 'include/dbconnect.php';
//include 'include/auth.php';
  if(!empty($_GET['olddate'])&&!empty($_GET['newdate'])){
    echo "document.write('$_GET[olddate]  $_GET[newdate]');\n";
    if($_GET['olddate']>strtotime('2006-01-01')){
      echo "document.write('stage2');\n";
      if(preg_match('/^20[0-9]{2}-[01][0-9]-[0-3][0-9]$/',$_GET['newdate'])){
        echo "document.write('stage3');";
        if($newdate=strtotime($_GET['newdate'])){
          echo "document.write('insert');\n";
          $sql->query("SELECT unix FROM daily_bulletins WHERE unix='$_GET[olddate]'");
          if($tmp=$sql->fetch_assoc()){
            if($tmp[unix]!=$newdate)
              $sql->query("DELETE FROM daily_bulletins WHERE unix='$newdate'");
            $sql->query("UPDATE daily_bulletins SET unix='$newdate',day='$_GET[newdate]' WHERE unix='$tmp[unix]'");
            echo "parent.change('$newdate','$_GET[newdate]');\n";
          }
        }
      }
    }
  }
?>
  parent.cancel();
</script>
