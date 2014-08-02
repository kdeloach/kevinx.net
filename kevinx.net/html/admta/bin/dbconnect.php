<?php

require_once '/var/www/kevinx.net/local_settings.php';

  try{
    $dbh = new PDO('mysql:host=localhost;dbname=kevinxn_musicteachers', DB_USER, DB_PASSWORD);
  } catch (PDOException $e) {
    die('PDO Error: '.$e->getMessage()); 
  }

?>