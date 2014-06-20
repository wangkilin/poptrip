<?php
/**
 * 获取对应城市的推荐酒店
 */

include_once ('../../SDK.config.php');//配置文件加载--必须加载这个文件
include_once (ABSPATH.'sdk/API/Hotel/D_HotelList.php');//加载D_HotelSearch这个接口的封装类
include_once (ABSPATH.'site/module/List_hotelSearch.php');//加载搜索的主逻辑
include_once(ABSPATH."include/urlRewrite.php");//加载URL伪静态处理
include_once (ABSPATH."include/url_HotelControl.php");//加载酒店URL路径控制

//示例：http://127.0.0.1:8888/site/ajaxrequest/hotelRecommendRequest.php?city=2,上海
?>
<?php

$cityID=$_GET['city']?$_GET['city']:$SiteDefaultCityID.",".$SiteDefaultCityName;//获取城市的ID，默认是上海

if(strlen($cityID)>0)
{
	$List_hotelSearch=new List_hotelSearch($cityID,'10','Recommend','DESC','3');

 	echo $List_hotelSearch->responseHotelListHtml;//返回符合条件的所有的酒店列表HTML
}
else
{
	echo "有什么想告诉大家！";
}
?>