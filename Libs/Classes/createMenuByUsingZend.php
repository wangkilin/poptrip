<?php
require_once('./Widget/ConvertFormat.class.php');
require_once('./menuList.php');
require_once('./Wechat/WechatRequester.class.php');

set_include_path(get_include_path() . PATH_SEPARATOR .
                     realpath(dirname(__FILE__).'/../ThinkPHP/Extend/Vendor/'));
require_once('Zend/Loader/Autoloader.php');
Zend_Loader_Autoloader::getInstance();

$target = 'wechat'; // yixin or wechat

define('PUBLIC_PLATFORM_TOKEN', 'TokenForKilinPublicPlatform');
define('WECHAT_APP_ID', 'wxd25e67dfa7b2dd59');
define('WECHAT_APP_SECRET', 'd91611978da6c8b67f41a9945c0599ae');
define('YIXIN_APP_ID', 'd54e53621b514858b4ebbc149fcd95e8');
define('YIXIN_APP_SECRET', '48ed9e6a9fc544479e608f578d25b1be');
/*
$wechatGetTokenUrl = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.WECHAT_APP_ID.'&secret='. WECHAT_APP_SECRET;

$yixinGetTokenUrl = 'https://api.yixin.im/cgi-bin/token?grant_type=client_credential&appid='.YIXIN_APP_ID.'&secret='.YIXIN_APP_SECRET;

//echo file_get_contents($getTokenUrl);exit;
$getTokenUrl = $target=='wechat' ? $wechatGetTokenUrl : $yixinGetTokenUrl;

$httpRequestConfig = array('ssltransport' => 'tls',
                           'adapter'=>'Zend_Http_Client_Adapter_Curl',
                           'curloptions'=>array(CURLOPT_SSL_VERIFYPEER=>false));
$client = new Zend_Http_Client($getTokenUrl, $httpRequestConfig);
$response = $client->request();

$result = json_decode($response->getBody(),true);
if(isset($result['errmsg'])) {
    echo "Error: code(".$result['errcode'].") message(".$result['errmsg'].")";
    exit;
} else {
    $token = $result['access_token'];
}

$wechatSetMenuUrl = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $token;
$yixinSetMenuUrl = 'https://api.yixin.im/cgi-bin/menu/create?access_token='.$token;
$setMenuUrl = $target=='wechat' ? $wechatSetMenuUrl : $yixinSetMenuUrl;

$client->setUri($setMenuUrl);
echo $client->setRawData(ConvertFormat::json_encode($menuJsonData))
            ->request('POST');

*/
$requesterClient = new WechatRequester(WECHAT_APP_ID, WECHAT_APP_SECRET, PUBLIC_PLATFORM_TOKEN);
$accessToken = $requesterClient->getAccessToken();
$result = $requesterClient->setAccessToken($accessToken)
                          ->setMenu($menuJsonData);

var_dump($requesterClient->getSubscriberList());
var_dump($requesterClient->getSubscriber("oigJdt60R-EfOtb7WYkwZOjK5Fxo"));