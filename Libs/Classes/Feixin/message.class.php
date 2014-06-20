<?php
/*
$c=new ContentText();
$c->addFont('12','#ff0000','这是用于测试字体的');
$c->addImg('http://img.baidu.com/img/baike/logo-baike.png','百度百科');
$c->addBr();
$c->addFont('14','#000000','这是用于测试字体的');
$c->addA('http://www.baidu.com','百度百科');
echo $c->getContent();
*/
class ContentText
{
    public $content;

    //添加一行文字
    public function addFont($size,$color,$text)
    {
        $this->content.='<font size="'.$size.'" color="'.$color.'">'.$this->get_utf8_string(htmlspecialchars($text)).'</font>';
    }

    //添加一张图片
    public function addImg($src,$alt)
    {
        $this->content.='<img src="'.$src.'"  alt="'.$alt.'" />';
    }

    //添加一个连接
    public function addA($href,$text)
    {
        $this->content.='<a href="'.$href.'">'.$this->get_utf8_string(htmlspecialchars($text)).'</a>';
    }

    //添加一个换行符
    public function addBr()
    {
        $this->content.='<br>';
    }

    //获取格式化后的消息
    public function getContent()
    {
        return $this->content;
    }

    //以数组的形式构建格式化的信息
    public function array2Content($param)
    {
        /*
         *  $c_array['font']['color']="";
            $c_array['font']['face']="";
            $c_array['font']['size']="";
            $c_array['font']['text']="";
            $c_array['a']['href']="";
            $c_array['a']['text']="";
            $c_array['img']['src']="";
            $c_array['img']['alt']="";
            $c_array['br']="1";
         */
        $return_txt='';
        foreach($param as $key=>$val){
            switch ($key){
                case 'font':
                    $return_txt.='<font size="'.$val['size'].'" color="'.$val['color'].'">'.$this->get_utf8_string($val['text']).'</font>';
                    break;
                case 'a':
                    $return_txt.='<a href="'.$val['href'].'">'.$val['text'].'</a>';
                    break;
                case 'img':
                    $return_txt.='<img src="'.$val['src'].'"  alt="'.$val['alt'].'" />';
                    break;
                case 'br':
                    $return_txt.='<br>';
                    break;
            }
        }
        return $return_txt;
    }

    //将字符转化成utf8格式
    public function get_utf8_string($content)
    {
        $encoding = mb_detect_encoding($content, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
        return  mb_convert_encoding($content, 'utf-8', $encoding);
    }
}
?>
