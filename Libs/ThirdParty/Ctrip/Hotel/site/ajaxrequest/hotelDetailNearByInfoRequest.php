<?php
//酒店周边交通信息
include_once ('../../SDK.config.php');//配置文件加载--必须加载这个文件
include_once (ABSPATH."site/module/main_D_hotelSearch.php");//加载搜索的主逻辑
include_once (ABSPATH.'sdk/API/Hotel/D_HotelPlaceInfoList.php');//加载D_HotelDetail_PlaceInfoList这个接口的封装类
//include_once (ABSPATH.'sdk/API/Hotel/D_HotelDetail_Detail.php');//加载D_HotelDetail_PlaceInfoList这个接口的封装类


if(empty($_GET['hotelid']))die('酒店ID 不允许为空!');
//http://127.0.0.1:1200/site/ajaxrequest/hotelDetailNearByInfoRequest.php?cityid=2&hotelid=669
$cdate=getDateYMD("-").",".getDateYMD_addDay("-",$HotelSearchDayNums);


//获取符合条件的酒店详细数据
$mainHotelSearch=new page_D_hotelSearch();
$mainHotelSearch->cdate=$cdate;
$mainHotelSearch->cityid=empty($_GET['cityid'])?$SiteDefaultCityID:$_GET['cityid'];
$mainHotelSearch->hotelID=$_GET['hotelid'];


$mainHotelSearch->getHotelDetailResponseXML_URL();
$hotelDetailInfo=array();
//$hotelDetailInfo['hotelDetail']=$mainHotelSearch->hotelDetail;
$hotelDetailInfo['hotelPlaceInfo']=$mainHotelSearch->hotelPlaceInfo;

//print_r($hotelDetailInfo);die;

echo json_encode($hotelDetailInfo);

?>

