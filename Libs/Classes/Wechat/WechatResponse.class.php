<?php
/**
 * 用于回复的基本消息类型
 */
abstract class WechatResponse
{
    const MSG_TYPE_IMAGE = 'image';
    const MSG_TYPE_TEXT = 'text';
    const MSG_TYPE_MUSIC = 'music';
    const MSG_TYPE_NEWS = 'news';
    const MSG_TYPE_VOICE = 'voice';
    const MSG_TYPE_VIDEO = 'video';

    protected $toUserName;
    protected $fromUserName;
    protected $msgType;

    protected $msgContent;
    protected $msgHeader = "
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[%s]]></MsgType>
";


    public function __construct($toUserName, $fromUserName, $msgType)
    {
      $this->toUserName = $toUserName;
      $this->fromUserName = $fromUserName;
      $this->checkMsgType($msgType);
    }

    protected function checkMsgType($msgType)
    {
        $msgType = strtolower($msgType);
        switch($msgType) {
            case self::MSG_TYPE_IMAGE:
            case self::MSG_TYPE_MUSIC:
            case self::MSG_TYPE_NEWS:
            case self::MSG_TYPE_TEXT:
            case self::MSG_TYPE_VIDEO:
            case self::MSG_TYPE_VOICE:
                $this->msgType = $msgType;
                break;

            default:
                trigger_error('Wrong message type for Wechat response['.$msgType.']', E_USER_ERROR);
                break;
        }
    }

    public function setMsgContent ($msgContent)
    {
        $this->msgContent = $msgContent;

        return $this;
    }

    public function getMsgHeader ()
    {
      $msgBody = sprintf($this->msgHeader, $this->toUserName, $this->fromUserName, time(), $this->msgType);

      return $msgBody;
    }



    abstract public function __toString();

}

/* EOF */