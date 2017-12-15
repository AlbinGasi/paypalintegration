<?php
session_start();
require_once('core/settings.php');

include_once SECTIONS . '/header.php';
include_once CORE . '/jshelper.php';
if($users->is_loggedin()){
	if(isset($_GET['sel'])) {
		$sel =  strip_tags(htmlentities( trim($_GET['sel'] ) ));
		if(file_exists(VIEWS . '/' . $sel. '.php')){
			include_once SECTIONS . '/body.php';
			include_once VIEWS . '/' . $sel. '.php';
			include_once SECTIONS . '/body-end.php';
		}else{
			include_once VIEWS . '/404.php';
		}
	}else{
		include_once SECTIONS . '/body.php';
		include_once VIEWS . '/home.php';
		include_once SECTIONS . '/body-end.php';
	}
}else{
	include_once VIEWS . '/login.php';
}
include_once SECTIONS . '/footer.php';