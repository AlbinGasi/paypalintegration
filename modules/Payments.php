<?php

namespace modules;
class Payments extends \modules\Entity
{
	private static $_db;
	public static function Init(){
		self::$_db = Connection::getInstance();
	}

	public function getAllTransactions(){
		$stmt = self::$_db->prepare("SELECT CONCAT(username, ' (', first_name, ' ', last_name, ')') as full_name, payment_name, date, paid, currency FROM ".TP."payments INNER JOIN ".TP."users ON ".TP."users.user_id= ".TP."payments.user_id;");
		$stmt->execute();
		$res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return $res;
	}

	public function insertIntoPayments($payment_name,$paid,$currency){
		$user_id = (isset($_SESSION[ID]['user_id'])) ? $_SESSION[ID]['user_id'] : -1;
		$stmt2 = self::$_db->prepare("INSERT INTO ".TP."payments (payment_name,user_id,paid,currency) VALUES (?,?,?,?)");
		$stmt2->bindValue(1, $payment_name);
		$stmt2->bindValue(2, $user_id);
		$stmt2->bindValue(3, $paid);
		$stmt2->bindValue(4, $currency);
		$stmt2->execute();
	}

}
Payments::Init();
?>