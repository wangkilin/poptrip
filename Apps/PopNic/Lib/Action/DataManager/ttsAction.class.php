<?php
class TtsAction extends Action
{
    public function _initialize()
    {
        header('Content-Type: text/html; charset=utf-8');
    }

    public function index()
    {
        $model = M('scenery');
        $data = $model->field('scenery_desc')->where('scenery_id=1')->select();
        echo $data[0]['scenery_desc'];
    }

}
?>