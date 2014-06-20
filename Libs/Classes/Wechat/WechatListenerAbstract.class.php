<?php
require_once(dirname(__FILE__) . '/WechatAbstract.class.php');

/**
 * WeChat public platform abstract class
 */
abstract class WechatListenerAbstract extends WechatAbstract {

    /**
     * @var boolean
     */
    public $debug = false;

    /**
     * @var array
     */
    private $request;

    /**
     * @param string $token
     */
    public function __construct($token)
    {
        $this->checkSignature($token);
        //set_error_handler(array(&$this, 'errorHandler'));
        if(!isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
            trigger_error('Access denied!', E_USER_ERROR);
            if($this->debug) {
                trigger_error('', E_USER_WARNING);
            }
            exit;
        }
        //echo $GLOBALS['HTTP_RAW_POST_DATA'];
        $this->parseRequestData($GLOBALS['HTTP_RAW_POST_DATA']);
    }

    protected function parseRequestData ($requestString)
    {
        error_log($requestString, 3, 'testLog.php');
        // parse XML string
        $xml = (array) simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA'], 'SimpleXMLElement', LIBXML_NOCDATA);

        // change key into lowercase
        $this->request = array_change_key_case($xml, CASE_LOWER);
    }


    /**
     * check signature
     *
     * @param  string $token
     * @return boolean
     */
    private function checkSignature($token)
    {
        if(! isset($_GET['echostr'])) {
            return;
        }

        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];

        if ($this->getSignature($token, $timestamp, $nonce) == $signature) {
            exit($_GET['echostr']);
        } else {
            trigger_error('Failed to check signature. GET parameters:' . str_replace("\n",'',print_r($_GET, true)), E_USER_NOTICE);
            exit;
        }
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
     *
     *
     * @return void
     */
    protected function processSubscribe()
    {
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
    protected function processClick()
    {
    }

    /**
     *
     *
     * @return void
     */
    protected function processText()
    {
    }

    /**
     *
     *
     * @return void
     */
    protected function processImage()
    {
    }

    /**
     *
     *
     * @return void
     */
    protected function processVideo()
    {
    }

    /**
     *
     *
     * @return void
     */
    protected function processVoice()
    {
    }

    /**
     *
     *
     * @return void
     */
    protected function processLocation()
    {
    }

    /**
     *
     *
     * @return void
     */
    protected function processLink()
    {
    }

    /**
     *
     *
     * @return void
     */
    protected function processUndefine()
    {
        trigger_error('Failed to precess response. POST parameters:' . str_replace("\n",'',$GLOBALS['HTTP_RAW_POST_DATA']), E_USER_WARNING);
    }

    /**
     *
     *
     * @param  string  $content
     * @return void
     */
    protected function replyText($content)
    {
        echo (new WechatTextResponse($this->getRequest('fromusername'), $this->getRequest('tousername'), $content));
    }

    /**
     *
     *
     * @param  string  $title
     * @param  string  $description
     * @param  string  $musicUrl
     * @param  string  $hqMusicUrl
     * @return void
     */
    protected function replyMusic($title, $description, $musicUrl, $hqMusicUrl)
    {
        echo (new WechatMusicResponse($this->getRequest('fromusername'), $this->getRequest('tousername'), $title, $description, $musicUrl, $hqMusicUrl));
    }

    /**
     *
     * @param  array   $items
     * @return void
     */
    protected function replyNews($items)
    {
        echo (new WechatNewsResponse($this->getRequest('fromusername'), $this->getRequest('tousername'), $items));
    }

    /**
     *
     *
     * @return void
     */
    public function listen()
    {
        $msgType = $this->getRequest('msgtype');
        $this->checkAndCallMethod($msgType);
    }

    protected function checkAndCallMethod ($methodKeyword)
    {
        $method = 'process'.ucfirst(strtolower($methodKeyword));
        if(method_exists($this, $method)){
            $this->$method();
        } else {
            $this->processUndefine();
        }
    }


    public function processEvent ()
    {
        $event = $this->getRequest('event');
        $this->checkAndCallMethod($event);
    }


    /**
     * @param  int $level
     * @param  string $msg
     * @param  string $file
     * @param  int $line
     * @return void
     */
    protected function errorHandler($level, $msg, $file, $line)
    {
        if ( ! $this->debug) {
            return;
        }

        $error_type = array(
                // E_ERROR             => 'Error',
                E_WARNING           => 'Warning',
                // E_PARSE             => 'Parse Error',
                E_NOTICE            => 'Notice',
                // E_CORE_ERROR        => 'Core Error',
                // E_CORE_WARNING      => 'Core Warning',
                // E_COMPILE_ERROR     => 'Compile Error',
                // E_COMPILE_WARNING   => 'Compile Warning',
                E_USER_ERROR        => 'User Error',
                E_USER_WARNING      => 'User Warning',
                E_USER_NOTICE       => 'User Notice',
                E_STRICT            => 'Strict',
                E_RECOVERABLE_ERROR => 'Recoverable Error',
                E_DEPRECATED        => 'Deprecated',
                E_USER_DEPRECATED   => 'User Deprecated',
        );

        $template = <<<ERR
PHP report error！

%s: %s
File: %s
Line: %s
ERR;

        $this->responseText(sprintf($template,
                $error_type[$level],
                $msg,
                $file,
                $line
        ));
    }

}

/* EOF */