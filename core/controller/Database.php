<?php
class Database {
	public static $db;
	public static $con;
	function Database(){
		$this->user="root";$this->pass="1q2w3e4r";$this->host="3.87.217.60";$this->ddbb="inventiolite";
	}

	function connect(){
		$con = new mysqli($this->host,$this->user,$this->pass,$this->ddbb);
		$con->query("set sql_mode=''");
		return $con;
	}

	public static function getCon(){
		if(self::$con==null && self::$db==null){
			self::$db = new Database();
			self::$con = self::$db->connect();
		}
		return self::$con;
	}
	
}
?>
