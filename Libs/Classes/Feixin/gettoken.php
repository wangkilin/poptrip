<?php
require_once(dirname(__FILE__) . "/inc/config.inc.php");
require_once(dirname(__FILE__) . "/class/feinnochat.class.php");
require_once(dirname(__FILE__) . "/class/commend.class.php");
require_once(dirname(__FILE__) . "/class/message.class.php");
require_once(dirname(__FILE__) . "/class/bigint.class.php");
//require_once(dirname(__FILE__) . "/class/encryptinterface.class.php");
//require_once(dirname(__FILE__) . "/class/mcryptinterface.class.php");

$token = file_get_contents('inc/token.txt');
$w = new FeinnoChat(CLIENTID,SECRET,$token,ENCRYPT,DEBUG);

//获取token，由chat.php接收和处理token
$tokeninfo=$w->getToken(CLIENTID);
echo $tokeninfo;
var_dump(json_decode($tokeninfo));
?>