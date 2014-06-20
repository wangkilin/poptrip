<?php
require_once('./Http/Curl.class.php');
require_once('./Widget/ConvertFormat.class.php');
require_once('./menuList.php');

$target = 'yixin'; // yixin or wechat

define('WECHAT_APP_ID', 'wxd25e67dfa7b2dd59');
define('WECHAT_APP_SECRET', 'd91611978da6c8b67f41a9945c0599ae');
define('YIXIN_APP_ID', 'd54e53621b514858b4ebbc149fcd95e8');
define('YIXIN_APP_SECRET', '48ed9e6a9fc544479e608f578d25b1be');

$wechatGetTokenUrl = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.WECHAT_APP_ID.'&secret='. WECHAT_APP_SECRET;

$yixinGetTokenUrl = 'https://api.yixin.im/cgi-bin/token?grant_type=client_credential&appid='.YIXIN_APP_ID.'&secret='.YIXIN_APP_SECRET;

//echo file_get_contents($getTokenUrl);exit;
$method='get';
$dataToSend='';
$headers=array();
$options=array();

$getTokenUrl = $target=='wechat' ? $wechatGetTokenUrl : $yixinGetTokenUrl;

$socketClass = new Curl($getTokenUrl, $method, $dataToSend, $headers, $options);

$json = $socketClass->execute();
$result = json_decode($json,true);
if(isset($result['errmsg'])) {
    echo "Error: code(".$result['errcode'].") message(".$result['errmsg'].")";
    exit;
} else {
    $token = $result['access_token'];
}

$wechatSetMenuUrl = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $token;
$yixinSetMenuUrl = 'https://api.yixin.im/cgi-bin/menu/create?access_token='.$token;
$setMenuUrl = $target=='wechat' ? $wechatSetMenuUrl : $yixinSetMenuUrl;

echo $socketClass->setUrl($setMenuUrl)->setDataToSend(ConvertFormat::json_encode($menuJsonData))->execute();