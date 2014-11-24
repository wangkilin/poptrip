<?php

define('APP_NAME', 'Apps');
$appName = isset($_REQUEST['app']) ? strtolower($_REQUEST['app']) : null;
switch ($appName) {
    case 'poptrip':
        define('APP_PATH', './Apps/PopTrip/');
        define('')
        break;

    case 'edulong':
        define('APP_PATH', './Apps/EduLong/');
        break;

    default:
        define('APP_PATH', './Apps/PopNic/');
        break;
}
//define('APP_PATH', './app/');
define('APP_DEBUG', true);
define('RUNTIME_PATH', './Runtime/' . APP_PATH . '/');
define('CONF_PATH', './Config/');
define('COMMON_PATH', './Common/');

define('MY_CLASS_PATH', dirname(__FILE__).'/Libs/Classes/');

//define('TMPL_PATH', '/Tpl/');
header('Content-Type:text/html; charset=utf-8');
include('./Libs/ThinkPHP/ThinkPHP.php');

/* EOF */
