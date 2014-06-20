<?php
require_once(dirname(__FILE__) . '/mcryptinterface.class.php');

abstract class FeixinAbstract
{
    public $debug = false;

    protected $clientId = null;
    protected $keyStr = null;
    protected $errorDesc = '';
    protected $tokenInfo = array('token'=>'','expireTime'=>0);

    protected $errorList = array(200 => '操作成功',
                                 400 => '操作失败',
                                 414 => '参数不正确',
                                 415 => 'Hash异常',
                                 416 => 'token异常',
                                 418 => 'xml解析失败',
                                 421 => '用户飞信状态变更',
                                 422 => '400号不正确',
                                 423 => 'token生成次数超限',
                                 500 => '未知系统错误'
                           );

    public function setDebug ($debug)
    {
        $this->debug = (bool) $debug;

        return $this;
    }

    public function setTokenInfo ($tokenInfo)
    {
        if(isset($tokenInfo['expireTime'], $tokenInfo['access_token'])) {
            $this->tokenInfo = array('token'=>$tokenInfo['access_token'],
                                     'expireTime'=>$tokenInfo['expireTime']);
        } elseif(isset($tokenInfo['access_token'], $tokenInfo['expires_in'])){
            $tokenInfo['expireTime'] = time() + $tokenInfo['expires_in'] -100;
            $this->tokenInfo = array('token'=>$tokenInfo['access_token'],
                                     'expireTime'=>$tokenInfo['expireTime']);
        }

        return $this;
    }

    public function getTokenInfo ()
    {
        return $this->tokenInfo;
    }

    public function getToken ()
    {
        if($this->tokenInfo['expireTime']>=time()) {
            $token = $this->tokenInfo['token'];
        } else {
            $token = null;
        }

        return $token;
    }


    public function getError ()
    {
        return $this->errorDesc;
    }


    public function getSignature ($timestamp, $nonce)
    {
        $params = array();
        $codes = "clientid" . $this->clientId;
        $params["keystr"] = $this->keyStr;
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

    public static function parseMessageData ($message, $encryptString)
    {
        if($encryptString!=''){
            $m=new McryptInterface();
            $infoArray=array();
            $infoArray=$m->InfoDecrypt($message, $encryptString);
            $message=base64_decode($infoArray["EncodeStr"]);
        }
        $message = stripcslashes($message);
        $xml = (array)simplexml_load_string($message, 'SimpleXMLElement', LIBXML_NOCDATA);


        // change key into lowercase
        return array_change_key_case($xml, CASE_LOWER);
    }


}
