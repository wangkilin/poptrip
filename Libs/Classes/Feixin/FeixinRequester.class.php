<?php
require_once(dirname(__FILE__) . '/FeixinAbstract.class.php');
require_once(dirname(__FILE__) . '/FeixinMessage.class.php');
require_once(dirname(__FILE__) . '/../Widget/ConvertFormat.class.php');

set_include_path(get_include_path() . PATH_SEPARATOR .
realpath(dirname(__FILE__).'/../../ThinkPHP/Extend/Vendor/'));
require_once('Zend/Loader/Autoloader.php');
Zend_Loader_Autoloader::getInstance();

class FeixinRequester extends FeixinAbstract
{

    protected $urlList = array(
        'GetToken'        => 'http://221.176.30.209/op/gettoken.php',
        'SetMenu'         => 'http://221.176.30.209/op/menu.php',
        'SendMessage'     => 'http://221.176.30.209/op/get.php'
        );

    protected $smsSourceTpl = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
            <Data version=\"1.0\">
                <PPID><![CDATA[4010152381]]></PPID>
                <UserURI><![CDATA[203097554]]></UserURI>
                <MsgType><![CDATA[PublicPlatformMobileSms]]></MsgType>
                <Content><![CDATA[content]]></Content>
                <CallID><![CDATA[ac789d7e-146a-b915-e25c-55d552741ce6]]></CallID>
                <CseqValue><![CDATA[604115]]></CseqValue>
                <MsgID><![CDATA[GZ09211922242707]]></MsgID>
                <ClientType><![CDATA[Android]]></ClientType>
                <PackageID><![CDATA[13]]></PackageID>
                <UserType><![CDATA[CMCC/1:L8]]></UserType>
            </Data>
          ";

    public function __construct ($clientId, $keyStr, $tokenInfo=array())
    {
        $this->clientId = $clientId;
        $this->keyStr = $keyStr;
        $this->setTokenInfo($tokenInfo);
    }

    protected function getHttpClient ($url=null)
    {
        if(function_exists('curl_init')) {
            $httpRequestConfig = array('ssltransport' => 'tls',
                                       'adapter'=>'Zend_Http_Client_Adapter_Curl',
                                       'curloptions'=>array(CURLOPT_SSL_VERIFYPEER=>false));
        } else {
            $httpRequestConfig = array('adapter'=>'Zend_Http_Client_Adapter_Socket',);
        }
        $client = new Zend_Http_Client($url, $httpRequestConfig);

        return $client;
    }

    public function getRequestUrl ($requestType)
    {
        $url = null;

        switch($requestType) {
            default :
                if(isset($this->urlList[$requestType])) {
                    $url = $this->urlList[$requestType];
                }
                break;
        }

        return $url;
    }

    public function setRequestUrl ($requestType, $url)
    {
        if(isset($this->urlList[$requestType])) {
            $this->urlList[$requestType] = $url;
        }

        return $this;
    }

    public function getAccessToken ()
    {
        $accessToken = null;

        $url = $this->getRequestUrl('GetToken');
        $client = $this->getHttpClient();
        $timestamp = time();
        $nonce = rand(10000, 99999);
        $sign = $this->getSignature($timestamp, $nonce);
        $post = array('clientid'=>$this->clientId,
                      'timestamp'=>$timestamp,
                      'nonce' => $nonce,
                      'sign' => $sign);
        $response = $client->setUri($url)
                           ->setParameterPost($post)
                           ->request('POST');
        if(200!=$response->getStatus()) {
            return null;
        }
        $result = ConvertFormat::json_decode($response->getBody(),true);
        if(!isset($result['access_token'])) {
            $this->errorDesc = "Error: code(".$result['errcode'].") message(".$result['errmsg'].")";
            $result = null;
        } else {
            $this->setTokenInfo($result);
        }

        return $result;
    }

    public function getToken ()
    {
        $token = parent::getToken();
        if(! $token) {
            $this->getAccessToken();
        }
        $token = parent::getToken();

        return $token;
    }

    public function setMenu ($menuList, $isCreation=false)
    {
        $result = false;
        $url = $this->getRequestUrl('SetMenu');
        $token = $this->getToken();
        if(!$token) {
            return $result;
        }
        $action = $isCreation===false ? 'update' : 'create';
        $post = array('clientid'=>$this->clientId,
                      'token'=>$token,
                      'action'=> $action,
                      'menu'=>ConvertFormat::json_encode($menuList));
        $response = $this->getHttpClient()
                         ->setUri($url)
                         ->setParameterPost($post)
                         ->request('POST');
        if(200!=$response->getStatus()) {
            return $result;
        }
        $response = ConvertFormat::json_decode($response->getBody(),true);
        if(isset($response['resultCode'])) {
            if(200==$response['resultCode']) {
                $result = true;
            } else {
                $this->errorDesc = "Error: code(".$response['resultCode'].") message(".$this->errorList[$response['resultCode']].")";
            }
        }

        return $result;
    }

    public function sendMessage ($content, $requestInfo=array())
    {
        $result = false;
        $url = $this->getRequestUrl('SendMessage');
        $token = $this->getToken();
        if(!$token) {
            return $result;
        }

        //$request = FeixinAbstract::parseMessageData($this->smsSourceTpl, '');
        //$requestInfo = array_merge($request, $requestInfo);
        $messageObj = (string) (new FeixinMessage($content, $requestInfo));
        $timestamp = time();
        $nonce = rand(10000, 99999);
        $post = array('clientid'=>$this->clientId,
                      'timestamp'=>$timestamp,
                      'nonce' => $nonce,
                      'token'=>$token,
                      'message'=>$messageObj);
        $response = $this->getHttpClient()
                         ->setUri($url)
                         ->setParameterPost($post)
                         ->request('POST');
        if(200!=$response->getStatus()) {
            return $result;
        }
        $response = ConvertFormat::json_decode($response->getBody(),true);
        var_dump($response,$post);
        if(isset($response['resultCode'])) {
            if(200==$response['resultCode']) {
                $result = true;
            } else {
                $this->errorDesc = "Error: code(".$response['resultCode'].") message(".$this->errorList[$response['resultCode']].")";
                echo $this->errorDesc;
            }
        }

        return $result;
    }


}