<?php
class CommendText
{
    public $commend;
    public $commend_type;

    //设置命令类型
    public function setCmdType($cmd_type)
    {
        $this->$commend_type=$cmd_type;
    }

    //添加命令信息
    public function addValue($cmd_key,$cmd_value)
    {
        $this->commend.='<args key="'.$cmd_key.'" value="'.get_utf8_string($cmd_value).'"/>';
    }

    //获取编码后的命令信息
    public function getCommend()
    {
        $com='<Cmd type="'.$this->$commend_type.'">'.$this->commend.'</Cmd>';
        return $this->commend;
    }

    //将字符转化成utf8格式
    public function get_utf8_string($content)
    {
        $encoding = mb_detect_encoding($content, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
        return  mb_convert_encoding($content, 'utf-8', $encoding);
    }
}
?>
