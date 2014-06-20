<?php
require_once(dirname(__FILE__) . "/inc/config.inc.php");
require_once(dirname(__FILE__) . "/class/feinnochat.class.php");
require_once(dirname(__FILE__) . "/class/encryptinterface.class.php");
require_once(dirname(__FILE__) . "/class/message.class.php");
require_once(dirname(__FILE__) . "/class/bigint.class.php");

$token = file_get_contents('inc/token.txt');
$w = new FeinnoChat(CLIENTID,SECRET,$token,ENCRYPT,DEBUG);

//主动下发一条短信

/*
* 注意：测试此功能需要申请相应的开发者权限
*/

$chat['ppid']   ='4010152381';  //公众帐号的号码 如：40100000
$chat['useruri']='13811998433';  //用户的手机号或唯一标识
$chat['msgtype']='PublicPlatformMobileSms';   //根据用户手机号进行短信的上行
$w->request=$chat;
$sent_str='这是一条开发者的短信。';
$sendinfo=$w->sendMessage($sent_str);
echo $sendinfo;
?>