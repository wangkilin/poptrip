<?php
//首次验证，验证过以后可以删掉
if (isset($_POST['echostr'])){
    echo $_POST['echostr'];
    exit();
}

//获取飞信公众平台发送的用户聊天上行消息
$_POST['message']=stripcslashes($_POST['message']);
$chat = (array)simplexml_load_string($_POST['message'], 'SimpleXMLElement', LIBXML_NOCDATA);

//根据不同消息类型发下不同的消息内容
switch ($chat['msgtype']){
    case 'OpenPublicPlatform':
      $sent_str='开启新会话。';
      break;
    case 'ClosePublicPlatform':
      $sent_str='关闭会话消息';
      break;
    default:
      $sent_str='普通会话消息';
}

//显示发下消息
echo getMessage($sent_str,$chat);



//构建消息格式
function getMessage($message,$chat){
      $textTpl = <<<EOF
<?xml version="1.0" encoding="utf-8"?>
<Data version="1.0">
    <ppid><![CDATA[%s]]></ppid>
    <useruri><![CDATA[%s]]></useruri>
    <msgtype><![CDATA[%s]]></msgtype>
    <content><![CDATA[%s]]></content>
    <callid><![CDATA[%s]]></callid>
    <cseqvalue><![CDATA[%s]]></cseqvalue>
    <msgid><![CDATA[%s]]></msgid>
    <clienttype><![CDATA[%s]]></clienttype>
    <packageid><![CDATA[%s]]></packageid>
    <usertype><![CDATA[%s]]></usertype>
</Data>
EOF;
      $msg_array['PPID']      =$chat['ppid'];
      $msg_array['UserURI']   =$chat['useruri'];
      $msg_array['MsgType']   ='PublicPlatformMsg';
      $msg_array['Content']   =$message;
      $msg_array['CallID']    =$chat['callid'];
      $msg_array['CseqValue'] =$chat['cseqvalue'];
      $msg_array['MsgID']     =$chat['msgid'];
      $msg_array['ClientType']=$chat['clienttype'];
      $msg_array['PackageID'] =$chat['packageid'];
      $msg_array['UserType']  =$chat['usertype'];
      $message=vsprintf($textTpl,$msg_array);
      return $message;
}
?>