<?php
/**
 * 根据酒店的 hotelID+RoomID,获取酒店详细信息：建筑面积，楼层，床宽等信息
 */
include_once ('../../SDK.config.php');//配置文件加载--必须加载这个文件
include_once (ABSPATH."sdk/API/Hotel/D_HotelSubRoomList.php");//加载D_HotelSearch这个接口的封装类
include_once (ABSPATH."site/module/main_D_hotelSearch.php");//加载搜索的主逻辑
//http://127.0.0.1:1200/site/ajaxrequest/subroomInfoRequest.php?hid=669&rid=18162&CheckInDate=2013-05-23&CheckOutDate=2013-05-25&cityid=2
$requestRoomID=$_GET["rid"];//子房型的ID
$cdate=$_GET["CheckInDate"].",".$_GET["CheckOutDate"];
$cdate=empty($cdate)?getDateYMD("-").",".getDateYMD_addDay("-",$HotelSearchDayNums):$cdate;
$_GET["city"]=$_GET["cityid"];//城市ID


//获取符合条件的酒店列表
$mainHotelSearch=new page_D_hotelSearch();
$mainHotelSearch->SiteHotelDefaultImageUrlHotelSearch=$SiteHotelDefaultImageUrl;//定义酒店列表中，默认的图片地址
$mainHotelSearch->cdate=$cdate;
$mainHotelSearch->HotelList=$_GET['hid'];
$mainHotelSearch->getRequsetParameter();//获取参数

echo $mainHotelSearch->getHotelRoomsResponseXML_URL($requestRoomID);//调用酒店子房的接口

//die;

//旧接口信息

include_once (ABSPATH."sdk/API/Hotel/D_HotelSearch.php");//加载D_HotelSearch这个接口的封装类
include_once (ABSPATH."site/module/main_hotelSearch.php");//加载搜索的主逻辑
if(!empty($requestHotelID)&&!empty($requestRoomID))
{
	$mainHotelSearch=new page_hotelSearch();
	$mainHotelSearch->CheckInDate=$CheckInDate;
	$mainHotelSearch->CheckOutDate=$CheckOutDate;
	$mainHotelSearch->cityid=$cityid;
	$mainHotelSearch->hotelID=$requestHotelID;
	$mainHotelSearch->setParameterType="set";//直接赋值
	$mainHotelSearch->getHotelDetailResponseXML();//获取酒店的详细数据
    echo $mainHotelSearch->getHotelSubRoomMoreInfoByRoomId($requestRoomID);//获取到指定房型的数据
}

?>