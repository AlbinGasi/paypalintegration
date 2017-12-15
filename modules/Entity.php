<?php

namespace modules;
abstract class Entity
{
	private static $_db;
	public static function Init(){
		self::$_db = Connection::getInstance();
	}

	public function userStatus($assoc=false,$publisher=true){
		if($assoc === false && $publisher === true){
			return [4,250,350,351];
		}else if($publisher === false){
			return [250,350,351];
		}
	}

	public function get_user_by_id($id,$return){
		$stmt = self::$_db->prepare("SELECT * FROM ".TP."users WHERE user_id = :id LIMIT 1");
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		$res = $stmt->fetch(\PDO::FETCH_ASSOC);
		if($return == "none"){
			return $res;
		}else{
			return $res[$return];
		}
	}

	public function is_admin(){
		$user_id = (isset($_SESSION[ID]['user_id'])) ? $_SESSION[ID]['user_id'] : -122;
		$stmt = self::$_db->prepare("SELECT user_status FROM ".TP."users WHERE user_id = :id LIMIT 1");
		$stmt->bindParam(':id', $user_id);
		$stmt->execute();
		$res = $stmt->fetch(\PDO::FETCH_ASSOC);

		if($res['user_status'] == 351 || $res['user_status'] == 350){
			return true;
		}else{
			return false;
		}
	}

	public function is_moderator(){
		$user_id = (isset($_SESSION[ID]['user_id'])) ? $_SESSION[ID]['user_id'] : -122;
		$stmt = self::$_db->prepare("SELECT user_status FROM ".TP."users WHERE user_id = :id LIMIT 1");
		$stmt->bindParam(':id', $user_id);
		$stmt->execute();

		$res = $stmt->fetch(\PDO::FETCH_ASSOC);
		if($res['user_status'] == 250 || $res['user_status'] == 350 || $res['user_status'] == 351){
			return true;
		}else{
			return false;
		}
	}

	public function is_premium(){
		$user_id = (isset($_SESSION[ID]['user_id'])) ? $_SESSION[ID]['user_id'] : -122;
		$stmt = self::$_db->prepare("SELECT user_status FROM ".TP."users WHERE user_id = :id LIMIT 1");
		$stmt->bindParam(':id', $user_id);
		$stmt->execute();

		$status = 0;
		$res = $stmt->fetch(\PDO::FETCH_ASSOC);
		if($res['user_status'] == 4){
			$stmt2 = self::$_db->prepare("SELECT * FROM ".TP."users_membership WHERE user_id = :id LIMIT 1");
			$stmt2->bindParam(':id', $user_id);
			$stmt2->execute();

			if($stmt2->rowCount() == 1){
				$userMembership = $stmt2->fetch(\PDO::FETCH_ASSOC);
				$currentDuration = strtotime($userMembership['duration']);
				$currentDate = strtotime(date("Y-m-d H:i:s"));

				if($currentDate < $currentDuration){
					$status = 1;
				}else{
					$this->updateUser(['set'=>'user_status','valueset'=>2,'where'=>'user_id','id'=>$user_id]);
				}
			}
		}else if ($res['user_status'] == 250 || $res['user_status'] == 350 || $res['user_status'] == 351) {
			$status = 1;
		}

		if($status){
			return true;
		}else{
			return false;
		}
	}

}
Entity::Init();
?>