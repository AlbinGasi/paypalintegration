<?php
if(!defined('LOADED')) die('ERROR');
$MENU = [
	'HOME' => [
		'url' => 'index.php',
		'name' => 'Home',
		'title' => 'Home page',
		'class' => ''
	],
	'ARTICLES' => [
		'url' => 'index.php?sel=articles',
		'name' => 'Articles',
		'title' => 'See our articles',
		'class' => ''
	],
	'LOGOUT' => [
		'url' => 'index.php?sel=logout',
		'name' => '<i class="fa fa-sign-out fa-2x" aria-hidden="true"></i> ',
		'title' => 'Logout',
		'class' => ' class="cfe-fl-right"'
	],
	'PROFILE' => [
		'url' => 'index.php?sel=profile',
		'name' => '<i class="fa fa-user fa-2x" aria-hidden="true"></i> ',
		'title' => 'Profile',
		'class' => ' class="cfe-fl-right"'
	],
];
