<?php
require_once(dirname(__FILE__) . "/inc/config.inc.php");
require_once(dirname(__FILE__) . "/class/feinnochat.class.php");
require_once(dirname(__FILE__) . "/class/commend.class.php");
require_once(dirname(__FILE__) . "/class/message.class.php");
//require_once(dirname(__FILE__) . "/class/bigint.class.php");
//require_once(dirname(__FILE__) . "/class/encryptinterface.class.php");
//require_once(dirname(__FILE__) . "/class/mcryptinterface.class.php");

$token = file_get_contents('inc/token.txt');
$w = new FeinnoChat(CLIENTID,SECRET,$token,ENCRYPT,DEBUG);

//首次验证，验证过以后可以删掉
if (isset($_POST['echostr'])){
    echo $_POST['echostr'];
    exit();
}

//处理token逻辑，使用gettoken方法申请token后，在此接收token
if(isset($_POST['token'])){
    $w->setToken($_POST['token']);
    exit('200OK');
}

$chat=$w->receiveMessage();	//获取飞信公众平台发送的用户聊天上行消息

switch ($chat['msgtype']){	//根据不同消息类型发下不同的消息内容
    case 'OpenPublicPlatform':
      $sent_str='开启新会话。';
      break;  
    case 'ClosePublicPlatform':
      $sent_str='关闭会话消息';
      break;
    case 'openUserAddFX':
      $sent_str='新用户关注';
      break;
    case 'openUserDelfx':
      $sent_str='用户取消关注';
      break;
    default:
      $sent_str='普通会话消息';
}
$w->request=$chat;

if($chat['msgtype']!='openUserDelfx'){		//用户取消关注后，不下发消息
	//echo $w->getMessage($sent_str);				//在当前连接返回消息（仅适用于会话消息）
	//exit;
	$sendinfo=$w->sendMessage($sent_str);		//通过接口下发消息（需要token有效）
}

//处理token失效的问题
$sendinfo=json_decode($sendinfo,true);
if($sendinfo['errcode']=='416'){	//token失效重新获取
    $w->getToken(CLIENTID);
}
?>