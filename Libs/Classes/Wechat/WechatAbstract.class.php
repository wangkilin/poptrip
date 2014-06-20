<?php
abstract class WechatAbstract
{
    public $debug = false;
    protected $errorDesc = '';

    public function setDebug ($debug)
    {
        $this->debug = (bool) $debug;

        return $this;
    }

    public function getError ()
    {
        return $this->errorDesc;
    }


    public function getSignature ($token, $timestamp, $nonce)
    {
        $signatureArray = array($token, $timestamp, $nonce);
        sort($signatureArray);

        $signature =  sha1(implode($signatureArray));

        return $signature;
    }

}
