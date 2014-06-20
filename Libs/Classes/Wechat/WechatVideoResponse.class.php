<?php

/**
 * 用于回复的音乐消息类型
 */
class WechatVideoResponse extends WechatResponse {

    protected $title;
    protected $description;
    protected $mediaId;

    protected $msgContent = "
<xml>
  %s
  <Video>
    <MediaId><![CDATA[%]]></MediaId>
    <Title><![CDATA[%]]></Title>
    <Description><![CDATA[%]]></Description>
  </Video>
</xml>
";

    public function __construct($toUserName, $fromUserName, $mediaId, $title, $description)
    {
        $msgType = parent::MSG_TYPE_VIDEO;
        parent::__construct($toUserName, $fromUserName, $msgType);
        $this->title = $title;
        $this->description = $description;
        $this->mediaId = $mediaId;
    }

    public function __toString() {
        return sprintf($this->msgContent,
                $this->getMsgHeader(),
                $this->mediaId,
                $this->title,
                $this->description
        );
    }

}

/* EOF */