<?php

$menuJsonData = array (
    'button' => array (
                  array('name'=>'案例展示',
                        'sub_button'=> array(
                                array('type'=>'click','key'=>'show_wechat','name'=>'公众平台'),
                                array('type'=>'click','key'=>'show_site','name'=>'网站开发'),
                                //array('type'=>'click','key'=>'feixin','name'=>''),
                                //array('type'=>'click','key'=>'laiwang','name'=>'来往'),
                                //array('type'=>'click','key'=>'wangxin','name'=>'旺信'),
                        )
                  ),
                  array('name'=>'产品领域',
                        'sub_button'=> array(
                                array('type'=>'click','key'=>'prod_wechat','name'=>'公众平台'),
                                array('type'=>'click','key'=>'prod_software','name'=>'软件研发'),
                                array('type'=>'click','key'=>'prod_trip','name'=>'旅游网站'),
                                array('type'=>'click','key'=>'prod_edu','name'=>'教育平台'),
                                array('type'=>'click','key'=>'prod_book','name'=>'自助chm/pdf手册'),
                        )
                  ),
                  array('name'=>'关注金福',
                        'sub_button'=> array(
                                array('type'=>'click','key'=>'about_us','name'=>'关于我们'),
                                array('type'=>'click','key'=>'about_business','name'=>'网站招商'),
                                array('type'=>'click','key'=>'about_co','name'=>'网站合作'),
                                array('type'=>'click','key'=>'about_contact','name'=>'联系我们'),
                                array('type'=>'view','url'=>'http://www.devboy.cn','name'=>'开发语言手册'),
                        )
                  ),

        )
);

