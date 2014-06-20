<?php
require_once(dirname(__FILE__) . '/FeixinAbstract.class.php');

/**
 * Feixin public platform abstract class
 */
class FeixinListener extends FeixinAbstract {

    /**
     * @var boolean
     */
    public $debug = false;

    protected $token = null;

    protected $encrypt = '';

    /**
     * @var array
     */
    private $request;

    /**
     * @param string $token
     */
    public function __construct($clientId, $keyStr, $token='', $encrypt='')
    {
        $this->clientId = $clientId;
        $this->keyStr = $keyStr;
        $this->token = $token;
        $this->encrypt = $encrypt;

        if (isset($_POST['clientid'], $_POST['timestamp'], $_POST['nonce'], $_POST['sign'])) {
            if(! $this->checkSignature()) {
                trigger_error('Failed to check signature. GET parameters:' . str_replace("\n",'',print_r($_GET, true)), E_USER_NOTICE);
                exit;
            }
            if(isset($_POST['echostr'])) {
                exit($_POST['echostr']);
            }
        }

        $this->parseRequestData();
    }

    protected function parseRequestData ()
    {
        if(isset($_POST['message'])) {
            $this->request = parent::parseMessageData($_POST['message'], $this->encrypt);
        }
    }


    /**
     * check signature
     *
     * @param  string $token
     * @return boolean
     */
    private function checkSignature()
    {

        $signature = $_POST['sign'];
        $timestamp = $_POST['timestamp'];
        $nonce = $_POST['nonce'];

        $result = $this->getSignature($timestamp, $nonce) == $signature;

        return $result;
    }

    /**
     * Get request parameters
     *
     * @param  string $paramName
     *
     * @return mixed
     */
    public function getRequest($paramName = null)
    {
        if (is_null($paramName)) {
            return $this->request;
        }

        $paramName = strtolower(strval($paramName));

        if (isset($this->request[$paramName])) {
            return $this->request[$paramName];
        }

        return NULL;
    }

    /**
     * Set handler for response message process.
     * Each handler will accept one parameter: array(it is this->getRequest())
     * After procession, handler is required to return an array(msgType, msgInfo)
     *
     * @param unknown_type $handlerCallbackList
     *
     * @return object $this
     */
    public function setHandler($handlerCallbackList)
    {
        settype($handlerCallbackList, 'array');
        foreach ($handlerCallbackList as $_key=>$_value) {
            $_key = strtolower($_key);
            switch($_key) {
                case 'message':
                case 'subscribe':
                case 'unsubscribe':
                case 'clickmenu':
                    break;
                default:
                    $_key = 'undefine';
                    break;
            }

            if(is_callable($_value)) {
                $this->handler[$_key] = $_value;
            } else {
                $callback = is_array($_value)&&is_object($_value[0]) ? (get_class($_value[0]).'::'.$_value[1]) : strval($_value);
                trigger_error('A wrong handler callback is set for Wechat response.['.$key.']['.$callback.']', E_USER_NOTICE);
            }
        }

        return $this;
    }

    protected function callCallback($msgType)
    {
        $FeixinResponseInstrance = null;
        $msgType = strtolower($msgType);
        if(isset($this->handler[$msgType])) {
            $FeixinResponseInstrance = call_user_func($this->handler[$msgType], $this);
            $FeixinResponseInstrance = ($FeixinResponseInstrance Instanceof FeixinResponseAbstract) ? $FeixinResponseInstrance : null;
        }

        return $FeixinResponseInstrance;
    }

    protected function replyText ($text)
    {
        echo (new FeixinMessage($text, $this->request, $this->encrypt));
    }


    /**
     * reply message while user subscribe
     *
     * @return void
     */
    protected function processSubscribe()
    {
        if($FeixinResponseInstrance = $this->callCallback('subscribe')) {
            echo $FeixinResponseInstrance;
        } else {
            $this->replyText('Welcome subscribe our service!');
        }
    }

    /**
     *
     *
     * @return void
     */
    protected function processUnsubscribe()
    {
    }

    /**
     *
     *
     * @return void
     */
    protected function processMessage()
    {
        if($this->getRequest('callid')==99) {
            $this->processClickMenu();
        } else if($FeixinResponseInstrance = $this->callCallback('message')) {
            echo $FeixinResponseInstrance;
        } else if($this->getRequest('content')!=''){
            /*
            if(substr($this->getRequest('content'), 0, 3)=='SMS') {
                $requesterObj = new FeixinRequester($this->clientId, $this->keyStr);
                $requestInfo = array('useruri'=>'203097554',
                                     'msgtype'=>'PublicPlatformMsg',
                                     'ppid'   =>'4010152381');
                $requesterObj->sendMessage('You just sent:' . $this->getRequest('content'), $requestInfo);
            }*/
            $this->replyText('We got you sent message: ' . $this->getRequest('content'));
        }
    }

    protected function processClickMenu()
    {
        if($FeixinResponseInstrance = $this->callCallback('clickMenu')) {
            echo $FeixinResponseInstrance;
        } else {
            $this->replyText('You just clicked menu: ' . $this->getRequest('content'));
        }
    }

    /**
     *
     *
     * @return void
     */
    protected function processUndefine()
    {
        trigger_error('Failed to precess response. POST parameters:' . str_replace("\n",'',$_POST['message']), E_USER_WARNING);
    }

    /**
     *
     *
     * @return void
     */
    public function listen()
    {
        $msgType = strtolower($this->getRequest('msgtype'));
        switch ($msgType) {
            case 'openpublicplatform': //'开启新会话。';
                $this->processMessage();
                break;

            case 'openpublicplatformmsg': //'普通会话消息';
                $this->processMessage();
                break;

            case 'publicplatformmsg': // '普通会话消息';
                $this->processMessage();
                break;

            case 'closepublicplatform': //'关闭会话消息';
                $this->processClose();
                break;

            case 'openuseraddfx': //'新用户关注';
                $this->processSubscribe();
                break;

            case 'openuserdelfx': //'用户取消关注';
                $this->processUnsubscribe();
                break;

            default: //'未知消息类型';
                $this->processUndefine();
                break;

        }
    }

    public function processEvent ()
    {
        switch (strtolower($this->getRequest('event'))) {
            case 'subscribe':
                $this->processSubscribe();
                break;

            case 'unsubscribe':
                $this->processUnsubscribe();
                break;

            case 'click': // click menu
                $this->processClick();
                break;

            default:
                $this->processUndefine();
                break;
        }
    }

}

/* EOF */