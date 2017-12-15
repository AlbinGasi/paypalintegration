<?php

namespace modules;
class Articles extends \modules\Entity
{
	private static $_db;
	public static function Init(){
		self::$_db = Connection::getInstance();
	}

	public function get($table_name){
		$stmt = self::$_db->prepare("SELECT * FROM ".TP . $table_name);
		$stmt->execute();
		$res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return $res;
	}

	public function getArticlesByUser(){
		$user_id = (isset($_SESSION[ID]['user_id'])) ? $_SESSION[ID]['user_id'] : -1;
		$stmt = self::$_db->prepare("SELECT article_id, name, price, val, date, image, category_name FROM ".TP."users INNER JOIN ".TP."articles ON ".TP."users.user_id= ".TP."articles.user_id INNER JOIN ".TP."category ON ".TP."category.category_id= ".TP."articles.category WHERE ".TP."users.user_id=:id");
		$stmt->bindParam(':id', $user_id);
		$stmt->execute();
		$res = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		if($stmt->rowCount() > 0 ){
			return $res;
		}else{
			return false;
		}
	}

	public function getItem($table_name,$id){
		$stmt = self::$_db->prepare("SELECT * FROM " . TP . $table_name . " WHERE ".substr_replace($table_name,"",-1) . "_id = ?");
		$stmt->bindValue(1,$id);
		$stmt->execute();
		$res = $stmt->fetch(\PDO::FETCH_ASSOC);
		if($stmt->rowCount() > 0){
			return $res;
		}else{
			return false;
		}
	}

	public function isPublisher($article_id){
		$user_id = (isset($_SESSION[ID]['user_id'])) ? $_SESSION[ID]['user_id'] : -1;
		$userStatus = $this->get_user_by_id($user_id,'user_status');
		$stmt = self::$_db->prepare("SELECT user_id FROM ".TP."articles WHERE article_id = ?");
		$stmt->bindValue(1,$article_id);
		$stmt->execute();
		$res = $stmt->fetch(\PDO::FETCH_ASSOC);
		$status = false;
		$allowed = $this->userStatus(false, false);
		if($res['user_id'] == $user_id){
			$status = true;
		}else if (in_array($userStatus,$allowed)) {
			$status = true;
		}
		return $status;
	}

	public function addArticles($article_name,$price,$val,$image,$category){
		$user_id = (isset($_SESSION[ID]['user_id'])) ? $_SESSION[ID]['user_id'] : -1;
		$status = false;
		$stmt = self::$_db->prepare("INSERT INTO ".TP."articles (name,price,val,image,category,user_id) VALUES (?,?,?,?,?,?)");
		$stmt->bindValue(1,$article_name);
		$stmt->bindValue(2,$price);
		$stmt->bindValue(3,$val);
		$stmt->bindValue(4,$image);
		$stmt->bindValue(5,$category);
		$stmt->bindValue(6,$user_id);
		if($stmt->execute()) $status = true;
		$last_id = self::$_db->lastInsertId();

		if($status){
			$stmt2 = self::$_db->prepare("INSERT INTO ".TP."article_category (category_id,article_id) VALUES (?,?)");
			$stmt2->bindValue(1,$category);
			$stmt2->bindValue(2,$last_id);
			if($stmt2->execute()){
				$status = true;
			}else{
				$status = false;
			}
		}
		if($status){
			return true;
		}else{
			return false;
		}
	}

	public function getCategoryById($id){
		$stmt = self::$_db->prepare("SELECT * FROM ".TP."category WHERE category_id = :id");
		$stmt->bindParam(':id', $id);
		$stmt->execute();
		$res = $stmt->fetch(\PDO::FETCH_ASSOC);
		return $res['category_name'];
	}

	public function deleteArticle($id){
		$status = 0;
		$stmt = self::$_db->prepare("DELETE FROM ".TP."articles WHERE article_id = ?");
		$stmt->bindValue(1,$id);
		if($stmt->execute()) $status = 1;

		$stmt2 = self::$_db->prepare("DELETE FROM ".TP."article_category WHERE article_id = ?");
		$stmt2->bindValue(1,$id);
		if($stmt2->execute()){
			$status = 1;
		}else{
			$status = 0;
		}

		if($status){
			return true;
		}else{
			return false;
		}
	}

	public function updateArticles($article_name,$price,$val,$image,$category,$article_id){
		$user_id = (isset($_SESSION[ID]['user_id'])) ? $_SESSION[ID]['user_id'] : -1;
		$status = false;
		$stmt = self::$_db->prepare("UPDATE ".TP."articles SET name=?,price=?,val=?,image=?,category=? WHERE article_id=?");
		$stmt->bindValue(1,$article_name);
		$stmt->bindValue(2,$price);
		$stmt->bindValue(3,$val);
		$stmt->bindValue(4,$image);
		$stmt->bindValue(5,$category);
		$stmt->bindValue(6,$article_id);
		if($stmt->execute()) $status = true;

		if($status){
			$stmt2 = self::$_db->prepare("UPDATE ".TP."article_category SET category_id=?,article_id=? WHERE article_id=?");
			$stmt2->bindValue(1,$category);
			$stmt2->bindValue(2,$article_id);
			$stmt2->bindValue(3,$article_id);
			if($stmt2->execute()){
				$status = true;
			}else{
				$status = false;
			}
		}
		if($status){
			return true;
		}else{
			return false;
		}
	}

}
Articles::Init();
?>