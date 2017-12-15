<?php

namespace modules;
class Users extends \modules\Entity
{
	private static $_db;
	public static function Init(){
		self::$_db = Connection::getInstance();
	}

	public function catchUserStatus($status){
		$return = 0;
		switch ($status) {
			case "premium":
				$return = 4;
				break;
			case "administrator":
				$return = 350;
				break;
			case "moderator":
				$return = 250;
				break;
		}
		return $return;
	}

	public function editUser($first_name,$last_name,$born,$email,$security_code){
		$status = false;
		$user_id = (isset($_SESSION[ID]['user_id'])) ? $_SESSION[ID]['user_id'] : -1;
		$stmt = self::$_db->prepare("UPDATE ".TP."users SET first_name=?,last_name=?,born=?,email=?,security_code=? WHERE user_id = ? LIMIT 1");
		$stmt->bindValue(1, $first_name);
		$stmt->bindValue(2, $last_name);
		$stmt->bindValue(3, $born);
		$stmt->bindValue(4, $email);
		$stmt->bindValue(5, $security_code);
		$stmt->bindValue(6, $user_id);
		if($stmt->execute()) $status = true;
		return $status;
	}

	public function updateUserMembership($payment_name,$paid,$currency){
		$user_status = $this->catchUserStatus("premium");
		$user_id = (isset($_SESSION[ID]['user_id'])) ? $_SESSION[ID]['user_id'] : -1;
		$stmt = self::$_db->prepare("UPDATE ".TP."users SET user_status = :user_status WHERE user_id = :id LIMIT 1");
		$stmt->bindParam(':user_status', $user_status);
		$stmt->bindParam(':id', $user_id);
		$stmt->execute();
		$last_id = self::$_db->lastInsertId();
		$payments = new Payments;
		$payments->insertIntoPayments($payment_name,$paid,$currency);
	}

	public function usersMembershipDuration($duration){
		$dateTo = date("Y-m-d H:i:s", strtotime("+".$duration." month"));
		$user_id = (isset($_SESSION[ID]['user_id'])) ? $_SESSION[ID]['user_id'] : -1;
		$currentDateFull = date("Y-m-d H:i:s");
		$stmt = self::$_db->prepare("SELECT * FROM ".TP."users_membership WHERE user_id = :id LIMIT 1");
		$stmt->bindParam(':id', $user_id);
		$stmt->execute();

		if($stmt->rowCount() == 1){
			$userMembership = $stmt->fetch(\PDO::FETCH_ASSOC);
			$currentDuration = strtotime($userMembership['duration']);
			$currentDate = strtotime(date("Y-m-d H:i:s"));

			if($currentDate > $currentDuration){
				$stmt2 = self::$_db->prepare("UPDATE ".TP."users_membership SET duration = :dateTo, dateupdate = :dateupdate WHERE user_id = :id");
				$stmt2->bindParam(':dateTo', $dateTo);
				$stmt2->bindParam(':dateupdate', $currentDateFull);
				$stmt2->bindParam(':id', $user_id);
				$stmt2->execute();
			}else{
				$ft1 = explode(" ",$userMembership['duration'])[0];
				$ft2 = date("H:i:s");
				$ft = strtotime($ft1 . " " . $ft2);

				$dateTo = date("Y-m-d H:i:s", strtotime("+".$duration." month",$ft));
				$stmt2 = self::$_db->prepare("UPDATE ".TP."users_membership SET duration = :dateTo, dateupdate = :dateupdate WHERE user_id = :id");
				$stmt2->bindParam(':dateTo', $dateTo);
				$stmt2->bindParam(':dateupdate', $currentDateFull);
				$stmt2->bindParam(':id', $user_id);
				$stmt2->execute();
			}
		}else{
			$stmt2 = self::$_db->prepare("INSERT INTO ".TP."users_membership (user_id,duration) VALUES (?,?)");
			$stmt2->bindValue(1, $user_id);
			$stmt2->bindValue(2, $dateTo);
			$stmt2->execute();
		}
	}

	public function membershipExpires(){
		$user_id = (isset($_SESSION[ID]['user_id'])) ? $_SESSION[ID]['user_id'] : -1;
		$stmt = self::$_db->prepare("SELECT duration, dateupdate FROM ".TP."users INNER JOIN ".TP."users_membership ON ".TP."users.user_id=".TP."users_membership.user_id WHERE ".TP."users.user_id = :id");
		$stmt->bindParam(':id', $user_id);
		$stmt->execute();
		$res = $stmt->fetch(\PDO::FETCH_ASSOC);
		return $res;
	}

	public function randomKey(){
		$random = mt_rand(99999,999999);
		$txt = [
			"t34Hcxgst345tzx",
			"astqwe434e3er",
			"fffsada343sdsf",
			"hsadadf3244sgfgh",
			"4sadag34343df",
			"trsdas451dgh43",
			"dfsf7856sdqf3",
			"35dfgddfg56sf",
			"Js45435fgfsdf3",
			"Oisdasd3sadasd434",
			"2wfdasdfsdf3f3D",
			"SS23sgfdasdas3d",
			"sjhxvdsasdhtsa",
			"2yxyf4345x2SSD",
			"gasfsfs45gyxc32",
			"33yxwsdfsdf45yKkds"
		];
		$rand = rand(0,count($txt)-1);
		$num1 = mt_rand(10000,50000);
		$num2 = mt_rand(700000,1000000);
		$uniqid = uniqid();
		$generalRandomKey = $random . $txt[$rand] . $num1 . $num2 . $uniqid;
		return md5($generalRandomKey);
	}

	public function updateUser($data){
		if($data['set'] == "loginhash" || $data['set'] == "password"){
			$data['valueset'] = md5($data['valueset']);
		}
		$stmt = self::$_db->prepare("UPDATE ".TP."users SET ".$data['set']."=:valueset WHERE ".$data['where']." = :id LIMIT 1");
		$stmt->bindParam(':valueset', $data['valueset']);
		$stmt->bindParam(':id', $data['id']);
		$stmt->execute();
	}

	public function userLogin($username,$password){
		$password = md5($password);
		$stmt = self::$_db->prepare("SELECT * FROM ".TP."users WHERE username = :username AND password = :password LIMIT 1");
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':password', $password);
		$stmt->execute();
		$user = $stmt->fetch(\PDO::FETCH_ASSOC);

		if($stmt->rowCount() == 1 && ($user['user_status'] != 1 || $user['user_status'] != 0)){
			$this->updateUser(['where'=>'user_id','id'=>$user['user_id'],'valueset'=>md5($this->randomKey()),'set'=>'loginhash']);
			$_SESSION[ID]['hash'] = $this->get_user_by_id($user['user_id'],'loginhash');
			$_SESSION[ID]['user_id'] = $user['user_id'];
			return true;
		}else{
			return false;
		}
	}

	public function is_loggedin(){
		$loggedIn = false;
		$user_id = (isset($_SESSION[ID]['user_id'])) ? $_SESSION[ID]['user_id'] : -1;
		$loginhash = $this->get_user_by_id($user_id,'loginhash');

		if(isset($_SESSION[ID]['hash'])){
			if($_SESSION[ID]['hash'] == $loginhash){
				$loggedIn = true;
			}
		}
		return $loggedIn;
	}
}
Users::Init();
?>