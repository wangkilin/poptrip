<?php
/** 
 * 定义系统中的友情连接的数据
 */ 
//排序|连接名称|连接地址|状态|类型|添加时间|有效期时间|外部图片链接地址
//状态：0-暂停使用；1-使用中；2-永久有效（使用中要判断“有效期时间”） 
//类型：0-文字链接；1-外部图片链接（使用“外部图片链接地址”显示） 

$siteFriendLinkArray=array( 
 array('100','携程旅游网','http://www.ctrip.com','2','1','2012-11-20','','images/ctrip.jpg'),
 array('85','松果网','http://www.songguo.com/','2','1','2012-11-20','','images/songguo.jpg'),
 array('84','新浪旅游','http://travel.sina.com.cn/','2','1','2012-11-20','','images/xinlang.jpg'),
 array('82','铁友网','http://tieyou.com/','2','1','2012-11-20','','images/tieyou.jpg'),
 array('81','LINKTECH','http://www.linktech.cn/','2','1','2012-11-20','','images/linktech.jpg'),
 array('80','鸿鹄逸游','http://www.hhtravel.com/index.html?br=ctrip','2','1','2012-11-20','','images/honghu.jpg'),
 array('3','携程网站联盟','http://u.ctrip.com/union','2','0','2012-11-14','',''),
 array('5','携程旅行网','http://www.ctrip.com','1','0','2012-11-14','2012-11-30',''),
 array('4','驴评网','http://www.lvping.com','1','0','2012-11-14','2020-08-17',''),
 array('1','松果网','http://www.songguo.com','1','0','2012-11-14','2020-08-17','')
);

?>