<?php
error_reporting(E_ALL ^ E_NOTICE); 
define("WEBROOT", preg_replace("/install/", '', dirname(__FILE__)));
require_once( WEBROOT.'appData/database.config.php');
require_once( WEBROOT.'appData/site.config.php');

require_once('install.inc.php');

$lockfile=WEBROOT.'appData/install.lock';
if (file_exists($lockfile)){	
	header("Content-type:text/html;charset=utf-8");
	exit("程序已运行安装，如果你确定要重新安装，请先从FTP中删除 install.lock 文件");
	die;
}

?>


