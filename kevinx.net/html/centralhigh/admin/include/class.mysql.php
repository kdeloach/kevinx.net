<?php

// MySQL Class
// September 26, 2005 04:41:51 PM - added $last_query var for debug purposes; it's a string of the last query executed
// September 26, 2005 10:37:08 PM - added insert_id function
// November 02, 2005 03:01:37 PM - no longer stores query resources... defeates purpose of class

class Mysql
{
	var $db;
	var $host;
	var $user;
	var $pass;
	
	var $lnk;
	var $result;
	var $last_query;
	var $query_count;
	
	function Mysql()
	{
		$this->query_count = 0;
	}
	function connect($host, $user, $pass)
	{
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		
		$this->lnk = mysql_connect($this->host, $this->user, $this->pass) or die(mysql_error());
		
		return $this->lnk;
	}
	function pconnect($host, $user, $pass)
	{
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		
		$this->lnk = mysql_pconnect($this->host, $this->user, $this->pass) or die(mysql_error());
		
		return $this->lnk;
	}
	function list_tables($db=null)
	{
		if(empty($db))
			$db = $this->db;
			
		return $this->query("SHOW TABLES FROM $db");
	}
	function select_db($db)
	{
		$this->db = $db;
		
		return $this->query("USE $db");
	}
	function query($str,$lnk=null)
	{
		if(!isset($lnk))
			$lnk = $this->lnk;
		
		$this->last_query = $str;
		$this->query_count++;
		
		$this->result = mysql_query($str, $lnk) or die(mysql_error());
		
		return $this->result;
	}
	function num_rows($res=null)
	{
		if(!isset($res))
			$res = $this->result;
			
		return mysql_num_rows($res);
	}
	function insert_id($res=null)
	{
		/*if(!isset($res))
			$res = $this->result;
			
		return mysql_insert_id($res);*/
		return mysql_insert_id();
	}
	function fetch_array($res=null)
	{
		if(!isset($res))
			$res = $this->result;
			
		return mysql_fetch_array($res);
	}
	function fetch_assoc($res=null)
	{
		if(!isset($res))
			$res = $this->result;
			
		return mysql_fetch_assoc($res);
	}
	function escape_string($string)
	{
		return mysql_real_escape_string($string);
	}
}

?>
