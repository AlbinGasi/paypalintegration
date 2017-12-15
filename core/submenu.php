<?php
if(!defined('LOADED')) die('ERROR');
$SUBMENU = [];

if(isset($_GET['sel']) && $_GET['sel'] == "articles"){
	$SUBMENU['ADD_ARTICLES'] = [
		'url' => 'index.php?sel=articles&c=add_new',
		'name' => 'Add new',
		'title' => 'Add new',
		'class' => ''
	];
	$SUBMENU['ALL_ARTICLES'] = [
		'url' => 'index.php?sel=articles&c=all',
		'name' => 'All articles',
		'title' => 'All articles',
		'class' => ''
	];
	$SUBMENU['MY_ARTICLES'] = [
		'url'   => 'index.php?sel=articles&c=myarticles',
		'name'  => 'My articles',
		'title' => 'My articles',
		'class' => ''
	];
		$SUBMENU['LIST_ARTICLES'] = [
			'url' => 'index.php?sel=articles&c=listofall',
			'name' => 'List of all',
			'title' => 'List of all',
			'class' => ''
		];
}
if(isset($_GET['sel']) && $_GET['sel'] == "profile") {
	if($users->is_admin()){
		$SUBMENU['TRANSACTIONS'] = [
			'url' => 'index.php?sel=profile&c=transactions',
			'name' => 'Transactions',
			'title' => 'Transactions',
			'class' => ''
		];
	}
}