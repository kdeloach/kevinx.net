<?php

  $q = isset($_GET['q']) ? $_GET['q'] : '';
  $a = isset($_GET['a']) && in_array($_GET['a'], array('fnames','lnames')) ? $_GET['a'] : 'fnames';
 
  $file=file("../bin/$a.txt");
  foreach($file as $name){
    if(preg_match("/^$q/i", $name)>0)
      echo "$name\n";
  }
  
?>