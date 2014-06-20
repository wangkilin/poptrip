<?php
/**
 * 系统首页
 */
header("Content-type: text/html; charset=utf-8"); 
include_once ("appData/site.config.php");//加载整站系统的配置文件
$defaultcityid301=$SiteDefaultCityID.",".$SiteDefaultCityName;
header("HTTP/1.1 301 Moved Permanently");
header("Location: site/index.php?defaultcityid=".$defaultcityid301);
exit(); 


?>

