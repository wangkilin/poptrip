<?php

define('APP_NAME', 'app');
define('APP_PATH', './app/');
define('APP_DEBUG', true);

define('MY_CLASS_PATH', dirname(__FILE__).'/Libs/Classes/');

//define('TMPL_PATH', '/Tpl/');
header('Content-Type:text/html; charset=utf-8');
include('./Libs/ThinkPHP/ThinkPHP.php');

/* EOF */