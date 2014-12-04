<?php
define('DS', DIRECTORY_SEPARATOR);
define('WEB_ROOT_PATH', dirname(__FILE__).DS);
define('APP_NAME', 'app');
define('APP_PATH', '../app/');
//define('APP_PATH', './app/');
define('APP_DEBUG', true);
define('RUNTIME_PATH', '../Temp/');
define('CONF_PATH', '../Config/');
define('COMMON_PATH', '../Common/');

define('MY_CLASS_PATH', WEB_ROOT_PATH.'../Classes/');

//define('TMPL_PATH', '/Tpl/');
header('Content-Type:text/html; charset=utf-8');
include(WEB_ROOT_PATH.'../ThinkPHP/ThinkPHP.php');

/* EOF */
