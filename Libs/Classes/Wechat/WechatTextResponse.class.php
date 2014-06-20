<?php
/**
 * 用于回复的文本消息类型
 */
class WechatTextResponse extends WechatResponse {

    protected $content;

    protected $msgContent = "
<xml>
  %s
  <Content><![CDATA[%s]]></Content>
</xml>
";

    public function __construct($toUserName, $fromUserName, $content)
    {
        $msgType = parent::MSG_TYPE_TEXT;
        parent::__construct($toUserName, $fromUserName, $msgType);
        $this->content = $content;
    }

    public function __toString()
    {
        return sprintf($this->msgContent, $this->getMsgHeader(), $this->content);
    }

}
/* EOF */