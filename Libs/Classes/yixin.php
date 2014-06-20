<?php
/**
  * yixin php test


AppID
d54e53621b514858b4ebbc149fcd95e8

AppSecret
48ed9e6a9fc544479e608f578d25b1be



���ں�AppID��
01b15e4b7bd7492194704b461feca803
���ں�AppSecret��
5c01203b2b4d432ca3537cab97b74133


  */

//define your token
define("TOKEN", "TokenForKilinPublicPlatform");

set_include_path(get_include_path() . PATH_SEPARATOR .
                     realpath(dirname(__FILE__).'/../ThinkPHP/Extend/Vendor/'));

require_once('Zend/Loader/Autoloader.php');
Zend_Loader_Autoloader::getInstance();
require_once './Wechat/YixinListener.class.php';
require_once('./Wechat/MyWechatHandler.class.php');

$wechat = new YixinListener(TOKEN);
$testHandler = new TestHandler();
$wechat->setHandler(array('text'        => array($testHandler, 'handleText'),
                          'subscribe'   => array($testHandler, 'handleSubscribe'),
                         )
                    )
       ->listen();

/* EOF */