<?php
if(!defined('LOADED')) die('ERROR');
if(isset($_SESSION[ID])){
	unset($_SESSION[ID]);
	header("Location: ".ABSPATH . '/index.php');
} else{
	header("Location: ".ABSPATH . '/index.php');
}