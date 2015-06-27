<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action
{
    protected $dbModel = null;

    public function _initialize ()
    {
        $this->dbModel = D('Ebook');
    }
    public function index()
    {
        var_dump($this->dbModel);
        $this->display('Index');
    }
}