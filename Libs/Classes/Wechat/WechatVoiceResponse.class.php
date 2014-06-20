<?php

/**
 * 用于回复的音乐消息类型
 */
class WechatVoiceResponse extends WechatResponse {

    protected $mediaId;

    protected $msgContent = "
<xml>
  %s
  <Voice>
    <MediaId><![CDATA[%s]]></MediaId>
  </Voice>
</xml>
";

    public function __construct($toUserName, $fromUserName, $mediaId)
    {
        $msgType = parent::MSG_TYPE_VOICE;
        parent::__construct($toUserName, $fromUserName, $msgType);
        $this->mediaId = $mediaId;
    }

    public function __toString() {
        return sprintf($this->msgContent,
                $this->getMsgHeader(),
                $this->mediaId
        );
    }

}

/* EOF */