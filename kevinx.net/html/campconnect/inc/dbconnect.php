<?php

  require $config['path'].'/inc/db.php';

  extract($config);
  $db=new db($server, $user, $pass, $database);

?>