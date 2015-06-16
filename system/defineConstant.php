<?php

session_start();

//Constantes pour les inclusions PHP
define("DS", DIRECTORY_SEPARATOR);
define("ROOT", dirname(dirname(__FILE__)));
//define("SYSTEM_ROOT", dirname(__FILE__));
define("APP_ROOT", ROOT.DS.'app');
define("MODEL_ROOT", APP_ROOT.DS.'model');
define("VIEW_ROOT",APP_ROOT.DS.'view');
define("CONTROLLER_ROOT", APP_ROOT.DS.'controller');
define("LIB_ROOT", ROOT.DS.'lib');
define("TEXT_ROOT", ROOT.DS.'text');
define("UTIL_ROOT", ROOT.DS.'util');

// Constantes pour les inclusions HTML/CSS
if (dirname($_SERVER['SCRIPT_NAME']) == DS)
	$sub_dir = '';
else
	$sub_dir = dirname($_SERVER['SCRIPT_NAME']);

define('BASE_URL', 'http://'.$_SERVER['HTTP_HOST'].$sub_dir);    // $_SERVER['HTTP_HOST'] = localhost et sub_dir TER_MVC
//TODO
define('VIEW_WEBROOT', '/app'.'/'.'view');