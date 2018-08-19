<?php
$blacklist=array();

for($i=0;$i<=8;$i++)
	$blacklist[]=chr($i);
$blacklist[]=chr(11);
$blacklist[]=chr(12);
for($i=14;$i<=31;$i++)
	$blacklist[]=chr($i);
for($i=127;$i<=255;$i++)
	$blacklist[]=chr($i);

return $blacklist;

?>
