<?php

$menuJsonData = array (
                  array('name'=>'案例展示',
                        'type'=>'parent',
                        'submenu'=> array(
                                array('type'=>'mo','appStr'=>'@menu/wechat','name'=>'微信'),
                                array('type'=>'mo','appStr'=>'@menu/yixin','name'=>'易信'),
                                array('type'=>'mo','appStr'=>'@menu/feixin','name'=>'飞信'),
                                array('type'=>'mo','appStr'=>'@menu/laiwang','name'=>'来往'),
                                array('type'=>'mo','appStr'=>'@menu/wangxin','name'=>'旺信'),
                        )
                  ),
                  array('name'=>'产品介绍',
                        'type'=>'parent',
                        'submenu'=> array(
                                array('type'=>'mo','appStr'=>'@menu/aboutweixin','name'=>'微信'),
                                array('type'=>'mo','appStr'=>'@menu/aboutfeixin','name'=>'飞信'),
                                array('type'=>'mo','appStr'=>'@menu/aboutyixin','name'=>'易信'),
                        )
                  ),
                  array('name'=>'帮助',
                        'type'=>'parent',
                        'submenu'=> array(
                                array('type'=>'mo','appStr'=>'@menu/aboutus','name'=>'关于我们'),
                                array('type'=>'mo','appStr'=>'@menu/cominfo','name'=>'公司介绍'),
                                array('type'=>'mo','appStr'=>'@menu/sitecom','name'=>'网站招商'),
                                array('type'=>'mo','appStr'=>'@menu/sitecop','name'=>'网站合作'),
                        )
                  )
);

