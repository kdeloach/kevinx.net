<?php

/*
 * db class
 * small class to handle simple database transactions
 * install propel or such if you want object relational mapping
 *
 */

class db {

  var $con;
  var $res;
  
  function db($server, $user, $pass, $database){
    $this->con=mysql_connect($server, $user, $pass);
    mysql_select_db($database, $this->con);
  }
  
  function query($str,$array=false){
    $this->res = mysql_query( $str, $this->con ) ;
    if(!$this->res)
      die(mysql_error() . $str );
    if($array)
      $this->res = $this->toArray( $this->res );
    return $this->res;
  }
  
  function insert($table,$vals){
    foreach($vals as $k=>$v)
      $vals[$k]=mysql_real_escape_string($v);
      
    return $this->query("INSERT INTO $table (".implode(", ", array_keys($vals) ).") VALUES('".implode("', '", $vals )."')");
  }

  function update($table,$vals,$where=''){
    $str=array();
    
    foreach($vals as $k=>$v){
      $str[]= "$k='".mysql_real_escape_string($v)."'";
    }
    $str = implode(' , ', $str);
    
    if(!empty($where))
      $where='WHERE '.$where;
    
    return $this->query("UPDATE $table SET $str $where");
  }
  
  function result($res=null){
    $res2 = isset($res) ? $res : $this->res;
    return mysql_fetch_array($res2);
  }
  
  function toArray($res){
    $arr=array();
    while($row=mysql_fetch_assoc($res)) 
      $arr[]=$row;
    return $arr;
  }
  
  function insert_id(){
    return mysql_insert_id($this->con); 
  }
  function num_rows($res){
    return mysql_num_rows($res); 
  }
  function fetch_row($res){
    return mysql_fetch_row($res);
  }
  function fetch_array($res){
    return mysql_fetch_array($res);
  }
  function fetch_assoc($res){
    return mysql_fetch_assoc($res);
  }
}

?>