<?php
require_once(dirname(__FILE__) . '/WechatAbstract.class.php');

class WechatRequester extends WechatAbstract
{
    protected $appId = null;
    protected $appSecret = null;
    protected $token = null;// this token is used to generated signature. it's defined by User
    protected $accessToken = null; // this token is got from remote server.

    protected $urlList = array(
        'GetAccessToken' => 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',
        'SetMenu' => 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=%s',
        'GetMenu' => 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token=%s',
        'GetSubscriber' => 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=%s&openid=%s&lang=zh_CN',
        'GetSubscriberList'=> 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=%s&next_openid=%s'
        );

    public $debug = false;

    public function __construct ($appId, $appSecret, $token)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->token = $token;
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
            case 'GetAccessToken':
                $url = sprintf($this->urlList['GetAccessToken'], $this->appId, $this->appSecret);
                break;

            case 'GetMenu':
            case 'SetMenu':
                $url = sprintf($this->urlList[$requestType], $this->accessToken);
                break;

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

        $url = $this->getRequestUrl('GetAccessToken');
        $client = $this->getHttpClient();
        $response = $client->setUri($url)->request();
        $result = ConvertFormat::json_decode($response->getBody(),true);
        if(isset($result['errmsg'])) {
            $this->errorDesc = "Error: code(".$result['errcode'].") message(".$result['errmsg'].")";
        } else {
            $accessToken = $result['access_token'];
        }

        return $accessToken;
    }

    public function setAccessToken ($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function setMenu ($menuList)
    {
        $result = false;
        $url = $this->getRequestUrl('SetMenu');
        $response = $this->getHttpClient()
                         ->setUri($url)
                         ->setRawData(ConvertFormat::json_encode($menuList))
                         ->request('POST');
        if(200!=$response->getStatus()) {
            return $result;
        }
        $response = ConvertFormat::json_decode($response->getBody(),true);
        if(isset($response['errcode'])) {
            if(0==$response['errcode']) {
                $result = true;
            } else {
                $this->errorDesc = "Error: code(".$response['errcode'].") message(".$response['errmsg'].")";
            }
        }

        return $result;
    }

    public function getMenu ($menuList)
    {
        $result = false;
        $url = $this->getRequestUrl('GetMenu');
        echo $url;
        $response = $this->getHttpClient()
                         ->setUri($url)
                         ->request('GET');
        if(200!=$response->getStatus()) {
            return $result;
        }
        $response = ConvertFormat::json_decode($response->getBody(),true);
        echo print_r($response,true);
        if(isset($response['errcode'])) {
            if(0==$response['errcode']) {
                $result = $response;
            } else {
                $this->errorDesc = "Error: code(".$response['errcode'].") message(".$response['errmsg'].")";
            }
        }

        return $result;
    }

    public function getSubscriber ($openId)
    {
        $url = sprintf($this->getRequestUrl('GetSubscriber'), $this->accessToken, $openId);
        $response = $this->getHttpClient($url)
                         ->request();
        if(200==$response->getStatus()) {
            $response = $response->getBody();
            $subscriberInfo = ConvertFormat::json_decode($response, true);
        } else {
            $subscriberInfo = null;
        }

        return $subscriberInfo;
    }

    public function getSubscriberList ($nextOpenId=null)
    {
        $url = sprintf($this->getRequestUrl('GetSubscriberList'), $this->accessToken, $nextOpenId);
        $response = $this->getHttpClient($url)
                         ->request();
        if(200==$response->getStatus()) {
            $response = $response->getBody();
            $subscriberList = ConvertFormat::json_decode($response, true);
        } else {
            $subscriberList = null;
        }

        return $subscriberList;
    }


}