<?php

/**
 * 用于回复的音乐消息类型
 */
class WechatMusicResponse extends WechatResponse {

    protected $title;
    protected $description;
    protected $musicUrl;
    protected $hqMusicUrl;

    protected $msgContent = "
<xml>
  %s
  <Music>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
    <MusicUrl><![CDATA[%s]]></MusicUrl>
    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
  </Music>
</xml>
";

    public function __construct($toUserName, $fromUserName, $title, $description, $musicUrl, $hqMusicUrl)
    {
        $msgType = parent::MSG_TYPE_MUSIC;
        parent::__construct($toUserName, $fromUserName, $msgType);
        $this->title = $title;
        $this->description = $description;
        $this->musicUrl = $musicUrl;
        $this->hqMusicUrl = $hqMusicUrl;
    }

    public function __toString() {
        return sprintf($this->msgContent,
                $this->getMsgHeader(),
                $this->title,
                $this->description,
                $this->musicUrl,
                $this->hqMusicUrl
        );
    }

}

/* EOF */