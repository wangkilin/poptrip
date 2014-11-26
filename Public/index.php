<?php
define('DS', DIRECTORY_SEPARATOR);
define('WEB_ROOT_PATH', dirname(__FILE__).DS);
define('APP_NAME', 'Apps');
$appName = isset($_REQUEST['app']) ? strtolower($_REQUEST['app']) : null;
switch ($appName) {
    case 'poptrip':
        define('APP_PATH', '../Apps/PopTrip/');
        define('APP_SITE_NAME', 'PopTrip');
        break;

    case 'edulong':
        define('APP_PATH', '../Apps/EduLong/');
        define('APP_SITE_NAME', 'EduLong');
        break;

    default:
        define('APP_PATH', '../Apps/PopNic/');
        define('APP_SITE_NAME', 'PopNic');
        break;
}
define('TMPL_PATH', APP_PATH.'Tpl/');
define('APP_THEME', APP_SITE_NAME);
//define('APP_PATH', './app/');
define('APP_DEBUG', true);
define('RUNTIME_PATH', '../Temp/' . APP_SITE_NAME . '/');
define('CONF_PATH', '../Config/'.APP_SITE_NAME.DS);
define('COMMON_PATH', '../Common/');

define('MY_CLASS_PATH', WEB_ROOT_PATH.'../Libs/Classes/');

//define('TMPL_PATH', '/Tpl/');
header('Content-Type:text/html; charset=utf-8');
include(WEB_ROOT_PATH.'../Libs/ThinkPHP/ThinkPHP.php');

/* EOF */
