<?php

class TestHandler
{
    public function __construct()
    {

    }

    public function handleClickMenu(FeixinAbstract $request)
    {
        $menuId = $request->getRequest('content');
        $subscriberId = $request->getRequest('UserURI');

        $msg = 'We got you clicking menu info. You just clicked: ' . $menuId;

        $FeixinMessageInstance = new FeixinMessage($msg, $request->getRequest());

        return $FeixinMessageInstance;
    }

    public function handleMessage(FeixinAbstract $request)
    {
        $message = $request->getRequest('content');
        $subscriberId = $request->getRequest('UserURI');

        $msgPool = array('猪，发啥内容呢？',
                '猪，没看明白你发的啥呢？再发一次呗。',
                '猪，你是说："' . $message . '" ?',
                '猪，啥都别说了。 好好长肉吧！',
                '大肥猪，好好长肉。 不要乱发消息！',
                '猪啊，你好可爱哦！',
                '猪， 你说我怎么这么稀罕你呢！',
                '猪，加油！');
        $msgId = rand(0, 7);
        $msg = $msgPool[$msgId];

        $FeixinMessageInstance = new FeixinMessage($msg, $request->getRequest());

        return $FeixinMessageInstance;
    }

    public function handleSubscribe (FeixinAbstract $request)
    {
    }

}