<?php
require_once(dirname(__FILE__) . "/inc/config.inc.php");
require_once(dirname(__FILE__) . "/class/feinnochat.class.php");

$token = file_get_contents('inc/token.txt');
$w = new FeinnoChat(CLIENTID,SECRET,$token,ENCRYPT,DEBUG);

//主动下发一条消息

/*
* 注意：测试此功能需要申请相应的开发者权限
*/

$chat['ppid']   ='';  //公众帐号的号码 如：40100000
$chat['useruri']='';  //用户的手机号 如：13900000000
$chat['msgtype']='PublicPlatformMobile';   //根据用户手机号进行消息的上行
$w->request=$chat;
$sent_str='这是一条开发者根据手机号主动上行的消息。';
$sendinfo=$w->sendMessage($sent_str);
echo $sendinfo;
?>