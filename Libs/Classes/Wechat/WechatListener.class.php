<?php

require_once(dirname(__FILE__) . '/WechatListenerAbstract.class.php');
require_once(dirname(__FILE__) . '/WechatResponse.class.php');
require_once(dirname(__FILE__) . '/WechatMusicResponse.class.php');
require_once(dirname(__FILE__) . '/WechatNewsResponse.class.php');
require_once(dirname(__FILE__) . '/WechatNewsArtical.class.php');
require_once(dirname(__FILE__) . '/WechatVideoResponse.class.php');
require_once(dirname(__FILE__) . '/WechatTextResponse.class.php');
require_once(dirname(__FILE__) . '/WechatVoiceResponse.class.php');

/**
 *
*/
class WechatListener extends WechatListenerAbstract
{
    protected $handler = array();

    public function __construct($token)
    {
        parent::__construct($token);
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
                case 'text':
                case 'image':
                case 'link':
                case 'location':
                case 'subscribe':
                case 'unsubscribe':
                case 'click':
                case 'voice':
                case 'video':
                case 'audio':
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
        $WechatResponseInstance = null;
        if(isset($this->handler[$msgType])) {
            $WechatResponseInstance = call_user_func($this->handler[$msgType], $this);
            $WechatResponseInstance = ($WechatResponseInstance Instanceof WechatResponse) ? $WechatResponseInstance : null;
        }

        return $WechatResponseInstance;
    }

    /**
     * reply message while user subscribe
     *
     * @return void
     */
    protected function processSubscribe()
    {
        if($WechatResponseInstance = $this->callCallback('subscribe')) {
            echo $WechatResponseInstance;
        } else {
            $this->replyText('Welcome subscribe our service!');
        }
    }

    /**
     * unsubscribe service
     *
     * @return void
     */
    protected function processUnsubscribe()
    {
        if($WechatResponseInstance = $this->callCallback('unsubscribe')) {
            echo $WechatResponseInstance;
        } else {
            $this->replyText('Wish see you again later!');
        }
    }

    /**
     *
     *
     * @return void
     */
    protected function processText()
    {
        if($WechatResponseInstance = $this->callCallback('text')) {
            echo $WechatResponseInstance;
        } else {
            $this->replyText('We got you sent message: ' . $this->getRequest('content'));
        }
    }

    /**
     *
     *
     * @return void
     */
    protected function processImage()
    {
        if($WechatResponseInstance = $this->callCallback('image')) {
            echo $WechatResponseInstance;
        } else {

            $items = array(
                    new WechatNewsArticle('图片消息一', '图片描述信息一', $this->getRequest('picurl'), $this->getRequest('picurl')),
                    new WechatNewsArticle('图片消息二', '图片描述信息二', $this->getRequest('picurl'), $this->getRequest('picurl')),
            );

            $this->replyNews($items);
        }
    }

    /**
     *
     *
     * @return void
     */
    protected function processLocation()
    {
        if($WechatResponseInstance = $this->callCallback('location')) {
            echo $WechatResponseInstance;
        } else {
            $this->replyText('收到了位置消息：' . $this->getRequest('location_x') . ',' . $this->getRequest('location_y'));
        }
    }

    /**
     *
     *
     * @return void
     */
    protected function processLink()
    {
        if($WechatResponseInstance = $this->callCallback('link')) {
            echo $WechatResponseInstance;
        } else {
            $this->replyText('收到了链接：' . $this->getRequest('url'));
        }
    }

    protected function processClick()
    {
        if($WechatResponseInstance = $this->callCallback('click')) {
            echo $WechatResponseInstance;
        } else {
            $this->replyText('收到点击菜单通知：' . $this->getRequest('eventkey'));
        }
    }

    protected function processVideo()
    {
        if($WechatResponseInstance = $this->callCallback('video')) {
            echo $WechatResponseInstance;
        } else {
            $this->replyText('收到了视频消息。thumb：' . $this->getRequest('ThumbMediaId').' id:'.$this->getRequest('MsgId'));
        }
    }

    protected function processVoice()
    {
        if($WechatResponseInstance = $this->callCallback('voice')) {
            echo $WechatResponseInstance;
        } else {
            $returnMessage = '收到了语音消息。mediaId：' . $this->getRequest('MediaId')
                                           .' id:'.$this->getRequest('MsgId')
                                           .' format:'.$this->getRequest('Format');

            if($this->getRequest('Recognition')) {
                $returnMessage .= ' 语音识别：' . $this->getRequest('Recognition');

            }
            $this->replyText($returnMessage);
        }
    }

    /**
     *
     *
     * @return void
     */
    protected function processUndefine()
    {
        if($WechatResponseInstance = $this->callCallback('undefine')) {
            echo $WechatResponseInstance;
        } else {
            $this->replyText('收到了未知类型消息：' . $this->getRequest('msgtype'));
        }
    }

}


/* EOF */