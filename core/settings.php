<?php
define('LOADED', true);
define('MODULES', 'modules');
define('CLASSES', 'classes');
define('CORE', 'core');
define('TEMPLATES', 'templates');
define('VIEWS', 'public_html');
define('AJAX', 'public_html/ajax');
define('SECTIONS', TEMPLATES . '/sections');

require_once( 'system-lang.php' );
require_once( 'functions.php' );

define('ABSPATH', 'http://www.example.com/paypalintegration'); // without slash on the end
define('ID', 'sJK4r45dgjd'); // example jKsf431fksj (do not use only numbers!)

/* Database info */
define('HOST','');
define('DBNAME','');
define('USER','');
define('PASSWORD','');
define('TP',''); // table prefix

spl_autoload_register(function($modelName){
	$modelName = str_replace("\\","/",$modelName);
	if( file_exists($modelName .'.php')) {
		require_once $modelName .'.php';
	}
});
$users = new modules\Users();
require_once ('menu.php');
require_once ('submenu.php');