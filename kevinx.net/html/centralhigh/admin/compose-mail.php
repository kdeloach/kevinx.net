<?php
	require 'include/admin-header.php';
	require ROOT.PATH_CMS.'include/auth.php';


if(!empty($_POST)){
  $p=$_POST;
  $p['content']=addslashes($p['content']);
  $p['subject']=addslashes($p['subject']);
  $groups='';
  if(!empty($p['groups'])){
    foreach($p['groups'] as $id=>$val){
      if($val==true)$groups.="$id, ";
    }
  }

  $now=time();
  if(isset($p['id'])){
    $sql->query("UPDATE `mailing_list_email` SET subject='$p[subject]',body='$p[content]',recipients='$groups',lastmod='$now' WHERE id='$p[id]'");
    $id=$p['id'];
  }else{
    $sql->query("INSERT INTO mailing_list_email(id,created,recipients,subject,body,lastmod) VALUES('',$now,'$groups','$p[subject]','$p[content]',$now)");
    $id=$sql->insert_id();
  }

  header('Location: manage-mail.php?success=cre');
  exit;
}else if(empty($_POST)){
//Get Groups
$sql->query("SELECT `id`,`grp` FROM `mailing_list_groups` ORDER BY `grp`");
$groups=array();
while($tmp=$sql->fetch_assoc())
  $groups[]=$tmp;


if(!empty($_GET['mid'])){
  if(is_numeric($_GET['mid']))
    $sql->query("SELECT `id`,`subject`,`body`,`recipients`,`created` FROM `mailing_list_email` where id='$_GET[mid]'");
  if($edit=$sql->fetch_assoc()){
    $t=true;
  }
}


$td="<td style=\"text-align:right;\" valign=\"top\">";

$esubj=(isset($t))?$edit['subject']:'';
$ebody=(isset($t))?preg_replace("/\n|\r/",'<br />',addslashes($edit['body'])):'';
$ehide=(isset($t))?"<input type=\"hidden\" name=\"created\" value=\"$edit[created]\" /><input type=\"hidden\" name=\"id\" value=\"$edit[id]\" />":'';
$erecip=(isset($t))?explode(', ',$edit['recipients']):array();


echo "<form method=\"POST\" action=\"compose-mail.php\" onsubmit=\"return submitForm();\">\n";
echo "$ehide\n";
echo "  <table border=\"0\" cellpadding=\"3\">\n";
echo "    <tr>\n";
echo "      $td Groups:</td>";
echo "  <td>";


foreach($groups as $tmp){
  $chckd=(isset($t))?inGroup($tmp['id'],$erecip):'';
  echo "      <label for=\"$tmp[id]\">$tmp[grp]</label><input type=\"checkbox\" name=\"groups[$tmp[id]]\" id=\"$tmp[id]\" $chckd/><br />\n";
}


echo "      </td>";
echo "    </tr><tr>\n";
echo "      $td Subject:</td>\n";
echo "      <td><input type=\"text\" name=\"subject\" value=\"$esubj\" maxlength=\"50\" /></td>\n";
echo "    </tr><tr>\n";
echo "      $td Body:</td>\n";
echo "      <td><script type=\"text/javascript\">\n";
echo "        <!--\n";
echo "          writeRichText('content', '$ebody', 400, 200, true, false);\n";
echo "        //-->\n";
echo "        </script></td>\n";
echo "    </tr><tr>\n";
echo "      <td></td><td align=\"right\">";
if(isset($t))
  echo "        <input type=\"button\" value=\"New\" onClick=\"window.location='compose-mail.php'\" />";
echo "        <input type=\"submit\" value=\"Save\" />";
echo "      </td>\n";
echo "    </tr>\n";
echo "  </table>";

}


//footer
require 'include/admin-footer.php';

//function//
function inGroup($id,$groups){
  foreach($groups as $g)
    if($g==$id)
      return 'CHECKED ';
  return '';
}

?>
