<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action
{
    public function _initialize ()
    {
    }

    public function index()
    {
        $a = D('Vote');
        var_dump($a);
        $this->display('Index');
    }

    public function vote ()
    {

    }
}