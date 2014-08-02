<?php
/**
 * Wrapper class for SQL, in this case we will use mysqli
 * Author: Michajlo Matijkiw
 * File: class.sql.php
 */

//Required, will be set in ini file?
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD','');
define('DB_DATABASE','');

//Constants
define('SQL_ALL', 1);
define('SQL_SINGLE', 2);
define('SQL_DEFAULT', 3);

class SQL {
    public static $db=null;

    public static function get_connection(){
        if(self::$db=null){
            self::$db=new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE);
        }
        return self::$db;
    }

    public static function query($query, $return_type=SQL_DEFAULT){
        $conn=self::get_connection();
        $res=$conn->query($query);
        $data=array();
        if($return_type == SQL_ALL){
            while($row=$res->fetch_assoc()){
                $data[]=$row;
            }
            return $data;
        }elseif($return_type == SQL_SINGLE){
            return $res->fetch_assoc();
        }else{
            return $res;
        }
    }

    public static function insert_id(){
        $conn=self::get_connection();
        return $conn->insert_id;
    }

    public static function error(){
        $conn=self::get_connection();
        return $conn->error;
    }
}

?>
