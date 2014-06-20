<?php
require_once(dirname(__FILE__) . "/inc/config.inc.php");
require_once(dirname(__FILE__) . "/class/feinnochat.class.php");
require_once(dirname(__FILE__) . "/class/commend.class.php");
require_once(dirname(__FILE__) . "/class/message.class.php");
require_once(dirname(__FILE__) . "/class/bigint.class.php");
require_once('../Widget/ConvertFormat.class.php');
require_once('./menuList.php');


require('./FeixinAbstract.class.php');
require('./FeixinRequester.class.php');

set_include_path(get_include_path() . PATH_SEPARATOR .
                     realpath(dirname(__FILE__).'/../../ThinkPHP/Extend/Vendor/'));
require_once('Zend/Loader/Autoloader.php');
Zend_Loader_Autoloader::getInstance();

$feixinRequester = new FeixinRequester(CLIENTID, SECRET);
//$feixinRequester->getAccessToken();
$result = $feixinRequester->setMenu($menuJsonData);
echo $feixinRequester->getError();

exit;


$token = file_get_contents('inc/token.txt');
$w = new FeinnoChat(CLIENTID,SECRET,$token,ENCRYPT,DEBUG);

//获取token，由chat.php接收和处理token
$tokeninfo=$w->getToken(CLIENTID);
$tokenInfo = json_decode($tokeninfo, true);
$token = $tokenInfo['access_token'];

$setMenuUrl = 'http://221.176.30.209/op/menu.php';

$post = array(
            'clientid'=>CLIENTID,
            'token' => $token,
            'action'=> 'update',
            'menu'=>ConvertFormat::json_encode($menuJsonData)
        );

$httpRequestConfig = array('ssltransport' => 'tls',
                           'adapter'=>'Zend_Http_Client_Adapter_Curl',
                           'curloptions'=>array(CURLOPT_SSL_VERIFYPEER=>false));
$client = new Zend_Http_Client($setMenuUrl, $httpRequestConfig);
$client->setUri($setMenuUrl);
echo $client->setParameterPost($post)
            ->request('POST');
