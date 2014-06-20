<?php
class FeinnoChat
{
    public $token;
    public $secret;
    public $clientid;
    public $request = array();

    protected $encrypt;
    protected $debug = true;
    protected $host = '221.176.30.209';
    protected $port ='80';
    protected $file = '/op/';

    //初始化
    public function __construct($clientid,$secret,$token,$encrypt='',$debug = false)
    {
        $this->clientid=$clientid;
        $this->secret = $secret;
        $this->debug = $debug;
        $this->token = $token;
        $this->encrypt = $encrypt;
    }

    //保存token  可根据实际情况进行修改
    public function setToken($token)
    {
        file_put_contents("inc/token.txt",$token);
        return $this->token=$token;
    }

    //服务验证
    public function valid()
    {
        $echoStr = $_POST["echostr"];
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    //获取构建后的消息内容
    public function getMessage($message,$cmd='')
    {
        $textTpl = <<<EOF
            <?xml version="1.0" encoding="utf-8"?>
            <Data version="1.0">
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
EOF;
        $msg_array['PPID']      =$this->request['ppid'];
        $msg_array['UserURI']   =$this->request['useruri'];
        //$msg_array['MsgType']   =$this->request['msgtype'];
        if($this->request['msgtype']=='PublicPlatformMobile' or $this->request['msgtype']=='PublicPlatformMobileSms'){
            $msg_array['MsgType']   =$this->request['msgtype'];
        }else{
            $msg_array['MsgType']   ='PublicPlatformMsg';//强制转换为聊天会话消息
        }
        $msg_array['Content']   =$message;
        $msg_array['CallID']    =$this->request['callid'];
        $msg_array['CseqValue'] =$this->request['cseqvalue'];
        $msg_array['MsgID']     =$this->request['msgid'];
        $msg_array['ClientType']=$this->request['clienttype'];
        $msg_array['PackageID'] =$this->request['packageid'];
        $msg_array['UserType']  =$this->request['usertype'];
        $textTpl=str_replace("||commend_str||",$cmd,$textTpl);
        $message=vsprintf($textTpl,$msg_array);
        if($this->encrypt!=''){
            $rand=mt_rand(100000000,999999999);
            $now_time=gmmktime();
            $past_time=$now_time+600;
            $message=base64_encode($message);
            $d=array($rand,$now_time,$past_time,1,2,3,4,'127.0.0.1',0,strlen($message),$message,'6','aaaa','2');
            $e=new EncryptInterface();
            $message=$e->InfoEncrypt($d,$this->encrypt);
        }
        return $message;
    }

    //接受并解析飞信公众平台发送的消息
    public function receiveMessage()
    {
        if ($this->debug){
            file_put_contents("log/request_log.txt",json_encode($_POST)."\r\n",FILE_APPEND);
        }
        if(empty($_POST['clientid']) || !$this->checkSignature()){
            exit("bad request");
        }
        if($this->encrypt!=''){
            $m=new McryptInterface();
            $infoArray=array();
            $infoArray=$m->InfoDecrypt($_POST['message'],$this->encrypt);
            $_POST['message']=base64_decode($infoArray["EncodeStr"]);
        }
        $_POST['message']=stripcslashes($_POST['message']);
        $this->request = (array)simplexml_load_string($_POST['message'], 'SimpleXMLElement', LIBXML_NOCDATA);
        return $this->request;
    }

    //校验飞信公众平台发送信息的有效性
    private function checkSignature()
    {
        $args = array("sign", "timestamp", "nonce");
        foreach ($args as $arg){
            if (!isset($_POST[$arg])){
                return false;
            }
        }
        $sign=$this->createSign($_POST['timestamp'],$_POST['nonce']);
        if( $sign == $_POST["sign"] ){
            return true;
        }else{
            return false;
        }
    }

    //向飞信公众平台发送消息
    public function sendMessage($message,$message_type='message')
    {
        $data['clientid']=$this->clientid;
        $data['timestamp']=time();
        $data['nonce']=rand(10000,99999);
        $data['token']=$this->token;
        $data[$message_type]=$this->getMessage($message);

        $url = 'http://'.$this->host.':'.$this->port.'/'.$this->file.'get.php';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);// POST数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);// 把post的变量加上
        $output = curl_exec($ch);
        if ($output === FALSE) {//调试使用
            echo "cURL Error: " . curl_error($ch);
        }
        curl_close($ch);
        if ($this->debug){
            file_put_contents("log/get_log.txt",  json_encode($data).'Res:'.$output."\r\n",FILE_APPEND);
        }
        return $output;
    }

    //向飞信公众平台申请token
    public function getToken()
    {
        $data['clientid']=$this->clientid;
        $data['timestamp']=time();
        $data['nonce']=rand(10000,99999);
        $data['sign']=$this->createSign($data['timestamp'],$data['nonce']);
        $url = 'http://'.$this->host.':'.$this->port.'/'.$this->file.'gettoken.php';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);// POST数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);// 把post的变量加上
        $output = curl_exec($ch);
        if ($output === FALSE) {//调试使用
            echo "cURL Error: " . curl_error($ch);
        }
        curl_close($ch);
        if ($this->debug){
            file_put_contents("log/gettoken_log.txt",json_encode($data).'Res:'.$output."\r\n",FILE_APPEND);
        }
        return $output;
    }

    //生成校验飞信公众平台的加密字符串
    public function createSign($timestamp,$nonce)
    {
        $codes = "clientid" . $this->clientid;
        $params["keystr"] = $this->secret;
        $params["timestamp"] = $timestamp;
        $params["nonce"] = $nonce;
        ksort($params);
        while (list ($key, $val) = each($params)) {
            $key = strtolower($key);
            $codes .= ($key . $val);
        }
        $sign = strtoupper(sha1($codes));

        return $sign;
    }
}
?>
