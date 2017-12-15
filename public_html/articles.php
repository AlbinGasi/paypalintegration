<?php
define('ARTICLES', true);
$articles = new modules\Articles;

if(isset($_GET['c'])){
	if(file_exists(VIEWS . '/' . $_GET['sel'].  '/' . $_GET['c'] . '.php')){
		include_once VIEWS . '/' . $_GET['sel'].  '/' . $_GET['c'] . '.php';
	}else{
		include_once VIEWS . '/404.php';
	}
}else{
	allArticles();
}