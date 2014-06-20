<?php
/**
  * yixin php test

������ID��
10297
��������Կ��
480595657e182b1a8655e37822bff266


  */

//define
define("DEBUG", true);
define("CLIENTID", "10297");
define("SECRET", "TokenForKilinPublicPlatform");
//define("ENCRYPT", "480595657e182b1a8655e37822bff266");
define("ENCRYPT", "");

require_once('./Feixin/FeixinAbstract.class.php');
require_once('./Feixin/FeixinResponseAbstract.class.php');
require_once('./Feixin/FeixinListener.class.php');
require_once('./Feixin/FeixinMessage.class.php');
require_once('./Feixin/FeixinRequester.class.php');

require_once('./Feixin/bigint.class.php');
require_once('./Feixin/encryptinterface.class.php');
require_once('./Feixin/mcryptinterface.class.php');

require_once('./Feixin/MyFeixinHandler.class.php');
/*
$_POST = array
(
    'clientid' => 10297,
    'timestamp' => 1394420479,
    'nonce' => 330653,
    'sign' => '201A38FB9B4059889CA3B7B8285842EE4F201FEF',
    'message' =>'<?xml version="1.0" encoding="utf-8"?><Data version="1.0"><ppid>4010152381</ppid><useruri>203097554</useruri><msgtype>PublicPlatformMsg</msgtype><content><![CDATA[SMS test sms...!]]></content><callid>de0fc659-d59b-cf51-3a83-1d9f3ec75592</callid><cseqvalue>177349</cseqvalue><msgid>GZ10110118750364</msgid><clienttype>Android</clienttype><packageid>13</packageid><usertype>CMCC/1:L8</usertype><nickname>王再新</nickname></Data>'
);
/**/
$myHandler = new TestHandler();
$handlerCallbackList = array('clickMenu' => array($myHandler, 'handleClickMenu'),
                             'message'   => array($myHandler, 'handleMessage'));
$feixinObj = new FeixinListener(CLIENTID, SECRET, '', ENCRYPT);
$feixinObj->setHandler($handlerCallbackList)->listen();


/* EOF */