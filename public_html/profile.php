<?php
define('PROFILE', true);
$user = $users->get_user_by_id($_SESSION[ID]['user_id'],'none');
$transactions = new modules\Payments;

if(isset($_GET['c'])){
	if(file_exists(VIEWS . '/' . $_GET['sel'].  '/' . $_GET['c'] . '.php')){
		include_once VIEWS . '/' . $_GET['sel'].  '/' . $_GET['c'] . '.php';
	}else{
		include_once VIEWS . '/404.php';
	}
}else{
	include_once VIEWS . '/' . $_GET['sel'].  '/home.php';
}
?>
