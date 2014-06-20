<?php

require_once(dirname(__FILE__) . '/WechatListener.class.php');

class YixinListener extends WechatListener
{

    public function __construct($token)
    {
        parent::__construct($token);
    }


    protected function processAudio()
    {
        if($WechatResponseInstance = $this->callCallback('audio')) {
            echo $WechatResponseInstance;
        } else {
            $this->replyText('收到了语音消息。url：' . $this->getRequest('url')
                                           .' id:'.$this->getRequest('MsgId')
                                           .' format:'.$this->getRequest('mimeType'));
        }
    }
}