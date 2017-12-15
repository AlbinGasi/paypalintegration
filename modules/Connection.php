<?php
namespace modules;
class Connection
{
	private static $_instance = null;

	public static function getInstance() {

		if(self::$_instance === null){
			try{
			self::$_instance = new \PDO('mysql:host='.HOST.';dbname='.DBNAME . ';charset=utf8',USER, PASSWORD);
		} catch (PDOException $e) {
			die('Conntection failed: ' . $e->getMessage());
		}
			return self::$_instance;
		}
		return self::$_instance;
	}
}


?>