<?php
if(!defined('LOADED')) die('ERROR');
function scan_dir($dir) {
	$ignored = array('.', '..');
	$files = array();
	foreach (scandir($dir) as $file) {
		if (in_array($file, $ignored)) continue;
		$files[$file] = filemtime($dir . '/' . $file);
	}
	arsort($files);
	$files = array_keys($files);
	return ($files) ? $files : false;
}

function getMac(){
	ob_start();
	system('ipconfig /all');
	$mycom=ob_get_contents();
	ob_clean();
	$findme = "Physical";
	$pmac = strpos($mycom, $findme);
	$mac=substr($mycom,($pmac+36),17);
	return $mac;
}

function getInclude($views,$sel,$cat){
	if(file_exists($views . '/' . $sel.  '/' . $cat . '.php')){
		include_once $views . '/' . $sel.  '/' . $cat . '.php';
	}else{
		include_once $views . '/404.php';
	}
}

function subMenu(){
	global $SUBMENU;
	$_GET['cat'] = (isset($_GET['c']))? $_GET['c'] : "";

	echo "<ul class='sub-menu'>";
    foreach ($SUBMENU as $item => $v) {
	    $class = "";
	    if($_GET['cat'] == substr(explode("&",$v['url'])[1],2)){
		    $class = " current-submenu-item";
	    }
      echo '<li'.$v['class'].'><a class="sub-menu-item'.$class.'" href="'.$v['url'].'" title="'.$v['title'].'">'.$v['name'].'</a></li>';
    }
	echo "</ul>";
}

function uploadImage($action=""){
	$errors = [];
	$target_dir = "output/article_images/";
	$target_file = $target_dir . basename($_FILES["article_image"]["name"]);

	$uploadStatus = 0;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

	$check = getimagesize($_FILES["article_image"]["tmp_name"]);
	if($check !== false) {
		$uploadStatus = 1;
	} else {
		$uploadStatus = 0;
	}
	if ($_FILES["article_image"]["size"] > 1500000) {
		$errors['large'] =  "Sorry, your file is too large.";
		$uploadStatus = 0;
	}
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	   && $imageFileType != "gif" ) {
		$errors['filaType'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadStatus = 0;
	}

	$new_name = "";
	if($uploadStatus == 0){
		return ["status"=>"error",'message'=>$errors];
	}else{
		$new_name = $target_dir.date("dmyHis"). '.'.$imageFileType;
		if(move_uploaded_file($_FILES["article_image"]["tmp_name"], $new_name)) {
			if($action != "") unlink($action);
			return ["status"=>"success",'message'=>$new_name];
		}else{
			$errors['erorupload'] =  "Sorry, there was an error uploading your file.";
			return ["status"=>"error",'message'=>$errors];
		}
	}
}

function allArticles(){
	global $articles;
	echo '<ul class="article-list">';
	foreach ($articles->get('articles') as $article){
		echo "<li>";
		echo "<img src='".$article['image']."'>";
		echo "<p>" . $article['name'] . "</p>";
		echo "<p>" . $article['price'] . " " . $article['val'] . "</p>";
		echo "<p><a href='index.php?sel=articles&c=item&id=".$article['article_id']."'>Details</a></p>";
		echo "</li>";
	}
	echo '</ul>';
}
function dateDiff($date)
{
	$mydate= date("Y-m-d H:i:s");
	$theDiff="";
	$datetime1 = date_create($date);
	$datetime2 = date_create($mydate);
	$interval = date_diff($datetime1, $datetime2);
	//echo $interval->format('%s Seconds %i Minutes %h Hours %d days %m Months %y Year    Ago')."<br>";
	$min=$interval->format('%i');
	$sec=$interval->format('%s');
	$hour=$interval->format('%h');
	$mon=$interval->format('%m');
	$day=$interval->format('%d');
	$year=$interval->format('%y');
	if($interval->format('%i%h%d%m%y')=="00000")
	{
		//echo $interval->format('%i%h%d%m%y')."<br>";
		return $sec." Seconds";

	}

	else if($interval->format('%h%d%m%y')=="0000"){
		return $min." Minutes";
	}


	else if($interval->format('%d%m%y')=="000"){
		return $hour." Hours";
	}


	else if($interval->format('%m%y')=="00"){
		return $day." Days";
	}

	else if($interval->format('%y')=="0"){
		return $mon." Months";
	}

	else{
		return $year." Years";
	}

}
function updateToPremium($important="UPGRADE"){
	$txt = "";
	$txt .= "<form action='membership/checkout.php' method='post'>";
	$txt .= "<select name='period' onchange='priceVal(this.value)'>";
	$txt .= "<option value='1'>1 month</option>";
	$txt .= "<option value='3'>3 month</option>";
	$txt .= "<option value='6'>6 month</option>";
	$txt .= "<option value='12'>1 year</option>";
	$txt .= "</select>";
	$txt .= "<input type='text' id='price-val' name='price' disabled>";
	$txt .= "<input type='submit' class='cfe-list-btn-standard' name='btn_membership' value='".$important."'>";
	$txt .= "</form>";
	return $txt;
}

function currency($currency=""){
	$currencyA = ['USD','EUR'];

	$txt = "";
	$txt .= '<select name="val" class="article-val">';
	foreach ($currencyA as $c){
		if($c == $currency) {
			$txt .= '<option value="'.$c.'" selected>'.$c.'</option>';
		}else{
			$txt .= '<option value="'.$c.'">'.$c.'</option>';
		}
	}
	$txt .= "</select>";
	return $txt;
}

function goBackButton(){
	if(!isset($_SERVER['HTTP_REFERER'])){
		$_SERVER['HTTP_REFERER'] = "index.php";
	}
}