<?php

  require_once '/var/www/kevinx.net/local_settings.php';

  $config['server'] = 'localhost';
  $config['user'] = DB_USER;
  $config['pass'] = DB_PASSWORD;
  $config['database'] = 'kevinxn_campconnect';

  $config['path']='/var/www/kevinx.net/html/campconnect';
  $config['url']= BASE_URL . '/campconnect';
  
  // time before you are logged out (in seconds)
  $config['cookie_expiration']=3600;
  
  $config['upload_path']='/var/www/kevinx.net/html/campconnect/upload';
  $config['upload_url']=BASE_URL . '/campconnect/upload';
  
?>