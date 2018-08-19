<?php

    require ROOT.PATH_INCLUDE . 'class.mysql.php';
    
	$sql = new Mysql;
	$sql->connect('localhost', 'root', 'root');
	$sql->select_db('centralhigh');
	
?>
