<?php
require_once(dirname(__FILE__) . '/encryptinterface.class.php');

require_once(dirname(__FILE__) . '/FeixinResponseAbstract.class.php');

class FeixinMessage extends FeixinResponseAbstract
{
    protected $request = null;

    protected $commandText = '';

    protected $content = '';

    protected $messageTpl = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
            <Data version=\"1.0\">
                ||commend_str||
            <Msg>
                <PPID><![CDATA[%s]]></PPID>
                <UserURI><![CDATA[%s]]></UserURI>
                <MsgType><![CDATA[%s]]></MsgType>
                <Content><![CDATA[%s]]></Content>
                <CallID><![CDATA[%s]]></CallID>
                <CseqValue><![CDATA[%s]]></CseqValue>
                <MsgID><![CDATA[%s]]></MsgID>
                <ClientType><![CDATA[%s]]></ClientType>
                <PackageID><![CDATA[%s]]></PackageID>
                <UserType><![CDATA[%s]]></UserType>
            </Msg>
            </Data>
    ";

    public function __construct ($content, $requestInfo=array(), $encryptString='')
    {
        $this->content = $content;
        $this->encryptString = $encryptString;
        $this->request = $requestInfo;
    }

    public function loadFromString ($message)
    {
        $this->request = FeixinAbstract::parseMessageData($_POST['mesasge'], $this->encryptString);
    }

    public function __toString ()
    {
        $messageParams['PPID']      = $this->request['ppid'];
        $messageParams['UserURI']   = $this->request['useruri'];
        //$messageParams['MsgType']   =$this->request['msgtype'];
        if($this->request['msgtype']=='PublicPlatformMobile' or $this->request['msgtype']=='PublicPlatformMobileSms'){
            $messageParams['MsgType']   = $this->request['msgtype'];
        }else{
            $messageParams['MsgType']   = 'PublicPlatformMsg';//强制转换为聊天会话消息
        }
        $messageParams['Content']   = $this->content;
        $messageParams['CallID']    = $this->request['callid'];
        $messageParams['CseqValue'] = $this->request['cseqvalue'];
        $messageParams['MsgID']     = $this->request['msgid'];
        $messageParams['ClientType']= $this->request['clienttype'];
        $messageParams['PackageID'] = $this->request['packageid'];
        $messageParams['UserType']  = $this->request['usertype'];
        $message=vsprintf($this->messageTpl, $messageParams);
        $message=str_replace("||commend_str||", $this->commandText, $message);
        if($this->encryptString!=''){
            $rand=mt_rand(100000000,999999999);
            $now_time=gmmktime();
            $past_time=$now_time+600;
            $message=base64_encode($message);
            $d=array($rand,$now_time,$past_time,1,2,3,4,'127.0.0.1',0,strlen($message),$message,'6','aaaa','2');
            $e=new EncryptInterface();
            $message=$e->InfoEncrypt($d,$this->encryptString);
        }

        return $message;
    }



}
