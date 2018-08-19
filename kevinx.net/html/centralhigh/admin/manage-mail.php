<?php 
include 'include/admin-header.php';
include 'include/auth.php';

if(!empty($_GET['mid'])&&!empty($_GET['act'])){

  $id=$_GET['mid'];
  if(is_numeric($id)){

    $act=$_GET['act'];
    if($act=='del'){
      $sql->query("DELETE FROM mailing_list_email WHERE id='$id'");
    }else if($act=='send'){
      $sql->query("SELECT recipients,subject,body FROM mailing_list_email WHERE id='$id' LIMIT 1");
      $tmp=$sql->fetch_assoc();
      $subject=$tmp['subject'];
      $body=$tmp['body'];

      $query='WHERE ';
      $groups=explode(', ',$tmp['recipients']);
      for($i=0;$i<count($groups)-1;$i++){
        $sql->query("SELECT col FROM mailing_list_groups WHERE id='$groups[$i]'");
        $tmp=$sql->fetch_assoc();
        if($i==0){
          $query.="$tmp[col]='1'";
        }else{
          $query.=" OR $tmp[col]='1'";
        }
      }

      $recipients=array();
      $sql->query("SELECT email FROM mailing_list $query");
      while($tmp=$sql->fetch_assoc()){
        $recipients[]=$tmp['email'];
      }

      include '../../include/massmail.php';
      $mailer= new massmail('chs_hs@yahoo.com',$subject,$body);
      $mailer->spam($recipients);
      $now=mktime();
      $sql->query("UPDATE mailing_list_email SET sent='$now,' WHERE id='$id'");
    }else $act='';
  }

  header('Location: manage-mail.php?success='.$act);
  exit;
}

if(isset($_GET['success'])){
  if($_GET['success']=='del'){
    echo dialog('Email deleted',SUCCESS);
  }else if($_GET['success']=='send'){
    echo dialog('Email send',SUCCESS);
  }else if($_GET['success']=='cre'){
    echo dialog('Email saved',SUCCESS);
  }
}

$res=$sql->query('SELECT * FROM mailing_list_email ORDER BY created DESC');

echo "<table id=\"highlight\" width=\"100%\">\n";
echo "<tr><th width=\"10%\">Created</th><th>Subject</th><th width=\"15%\">Recipients</th><th width=\"15%\">Modified</th><th width=\"15%\">Last Sent</th><th width=\"25%\">Actions</th></tr>\n";

while($tmp=$sql->fetch_assoc($res)){
  $created=date('n/j/Y',$tmp['created']);
  $modified=($tmp['lastmod'])?date('n/j/Y H:i',$tmp['lastmod']):'-';
  $sent=($tmp['sent'])?date('n/j/Y H:i',$tmp['sent']):'-';
  $send=($sent=='-')?'Send':'Resend';

  $groups='';
  $query=explode(', ',$tmp['recipients']);
  for($i=0;$i<count($query)-1;$i++){
    $sql->query("SELECT grp FROM mailing_list_groups WHERE id='$query[$i]'");
    $grp=$sql->fetch_assoc();
    if($i==0){
      $groups.="$grp[grp]";
    }else{
      $groups.=", $grp[grp]";
    }
  }
  $cansend=($groups=='')?'DISABLED ':'';
  
  echo "  <tr><td>$created</td><td>$tmp[subject]</td><td>$groups</td><td>$modified</td><td>$sent</td>\n";
  echo "    <td align=\"center\">\n";
  echo "      <input type=\"button\" onClick=\"window.location='compose-mail.php?mid=$tmp[id]'\" value=\"Edit\" />&nbsp;&nbsp;\n";
  echo "      <input type=\"button\" onClick=\"window.location='manage-mail.php?mid=$tmp[id]&act=del'\" value=\"Delete\" />&nbsp;&nbsp;\n";
  echo "      <input type=\"button\" onClick=\"window.location='manage-mail.php?mid=$tmp[id]&act=send'\" value=\"$send\" $cansend />";
  echo "    </td>\n";
  echo "  </tr>";
}

echo "</table>";
?>
<br /><br />
<div align="right"><input type="button" onClick="window.location='compose-mail.php'" value="New" />

<?php
  include 'include/admin-footer.php';
?>
