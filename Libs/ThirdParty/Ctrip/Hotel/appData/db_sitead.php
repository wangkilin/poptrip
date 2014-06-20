<?php
/** 
 * 定义系统中的广告的数据
 */ 
// 排序|链接地址标签|连接位置名称|链接名称|连接地址|类型|添加时间|外部资源 
// 类型：-1 禁用；0-文字链接；1-外部图片链接;2-外部的JS代码（使用“外部图片链接地址”显示） 
// 备注：在写入JS或者外部链接代码时，请请“"”转变为“\"” 

$siteAdArray=array(  
 array('6','index_foot','首页-底部','dfdsfdd','http://ctrip.c','-1','2012-11-14',''),
 array('5','index_foot','首页-底部','携程酒店预订','http://ctrip.com','1','2012-11-14','images/guanggao1.jpg'),
 array('4','index_foot','首页-底部','携程','https://ctrip.com','-1','2012-11-14','images/guanggao.jpg'),
 array('1','index_header','首页-头部','携程联盟','http://u.ctrip.com','1','2012-11-14','images/guanggao.jpg')
);

?>