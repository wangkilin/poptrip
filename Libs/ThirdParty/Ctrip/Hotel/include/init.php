<?php
//The directory in which your application specific resources are located.
require('common.inc.php');

//生成数据库操作实例
$db = new DB($cfg_dbhost, $cfg_dbuser, $cfg_dbpwd, $cfg_dbname, $cfg_dbcharset, $cfg_dbprefix);
unset($cfg_dbhost, $cfg_dbuser, $cfg_dbpwd, $cfg_dbname, $cfg_dbcharset, $cfg_dbprefix);


//下面的可以放在要用的目录里 比如special/common 里设置
$options = array(
    'template_dir' => APP_ROOT . $TmacConfig['Template']['template'] . DIRECTORY_SEPARATOR . $TmacConfig['Template']['template_dir'], //设置模板目录
    'cache_dir' => VAR_ROOT . $TmacConfig['Template']['cache_dir'] , //指定缓存文件存放目录
    'auto_update' => $TmacConfig['Template']['auto_update'], //当模板文件有改动时重新生成缓存 [关闭该项会快一些]
    'cache_lifetime' => $TmacConfig['Template']['cache_lifetime'], //缓存生命周期(分钟)，为 0 表示永久 [设置为 0 会快一些]
    'suffix' => $TmacConfig['Template']['suffix'],
    'cache_open' => $TmacConfig['Template']['cache_open'] //是否开启缓存，程序调试时使用
);
$template = Tpl::getInstance(); //使用单件模式实例化模板类
$template->setOptions($options); //设置模板参数
/**
 * include template(ADMIN.'/category');
 */
?>