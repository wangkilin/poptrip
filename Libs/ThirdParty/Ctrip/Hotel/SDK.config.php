<?php
error_reporting(E_ERROR);

//定义本系统的相对路径根部
if(!defined(ABSPATH)) {
	define('ABSPATH',dirname(__FILE__).'/');
}
$isSiteConfigPHP=false;
if(file_exists(ABSPATH."appData/site.config.php"))
{
	$isSiteConfigPHP=true;
    include_once (ABSPATH."appData/site.config.php");
}
if($isSiteConfigPHP&&$SiteAllianceid!=""&&$SiteSid!=""&&$SiteSiteKey!=""&&$SiteAllianceid_Uid!="")
{
	//site.config.php中配置联盟信息后，启用站点的配置信息
	define('Allianceid',$SiteAllianceid);
	define('Sid',$SiteSid);
	define('SiteKey',$SiteSiteKey);
	define('Allianceid_Uid',$SiteAllianceid_Uid);
	define('UnionSite_ShortName',$UnionSite_ShortName);//定义网站发短信通知时的简称
}
else{
}

date_default_timezone_set('PRC');//设置时区

ini_set('max_execution_time', '0');//设置不超时

//放置生成环境或者测试环境的域名
$ServiceUrlCtripOpenAPI="http://openapi.ctrip.com";
//$ServiceUrlCtripOpenAPI="http://openapi.testu.sh.ctriptravel.com";

//定义酒店直接查询接口的URL
if(!defined(D_HotelSearch_Url)) {
	define('D_HotelSearch_Url',$ServiceUrlCtripOpenAPI.'/hotel/D_HotelSearch.asmx');
}
//定义酒店详细查询接口的URL
if(!defined(D_HotelDetail_Url)) {
	define('D_HotelDetail_Url',$ServiceUrlCtripOpenAPI.'/hotel/D_HotelDetail.asmx');
}
//定义酒店评价接口的URL
if(!defined(D_HotelCommentList_Url)) {
	define('D_HotelCommentList_Url',$ServiceUrlCtripOpenAPI.'/hotel/D_HotelCommentList.asmx');
}
//定义酒店评价接口的URL-带有分页功能
if(!defined(D_HotelCommentListPage_Url)) {
	define('D_HotelCommentListPage_Url',$ServiceUrlCtripOpenAPI.'/hotel/D_HotelCommentListWithPage.asmx');
}

//定义酒店团购接口的URL
if(!defined(GroupProductList_Url)) {
	define('GroupProductList_Url',$ServiceUrlCtripOpenAPI.'/tuan/GroupProductList.asmx');
}
//定义酒店团购详细接口的URL
if(!defined(GroupProductInfo_Url)) {
	define('GroupProductInfo_Url',$ServiceUrlCtripOpenAPI.'/tuan/GroupProductInfo.asmx');
}
//定义酒店订单列表的URL
if(!defined(D_HotelOrderList_Url)) {
	define('D_HotelOrderList_Url',$ServiceUrlCtripOpenAPI.'/hotel/D_HotelOrderList.asmx');
}
//定义酒店订单详细的URL
if(!defined(D_HotelOrderDetail_Url)) {
	define('D_HotelOrderDetail_Url',$ServiceUrlCtripOpenAPI.'/hotel/D_HotelOrderDetail.asmx');
}
//定义获取“检查并生成外部UserUniqueID”的URL
if(!defined(OTA_UserUniqueID_Url)) {
	define('OTA_UserUniqueID_Url',$ServiceUrlCtripOpenAPI.'/hotel/OTA_UserUniqueID.asmx');
}

//定义获取订单取消的URL
if(!defined(OTA_OrderCancel_Url)) {
	define('OTA_OrderCancel_Url',$ServiceUrlCtripOpenAPI.'/hotel/OTA_Cancel.asmx');
}

//定义国内机票查询接口的URL
if(!defined(OTA_FlightSearch_Url)) {
	define('OTA_FlightSearch_Url',$ServiceUrlCtripOpenAPI.'/Flight/DomesticFlight/OTA_FlightSearch.asmx');
}
//定义测试接口地址
if(!defined(OTA_Ping_Url)) {
	define('OTA_Ping_Url',$ServiceUrlCtripOpenAPI.'/Hotel/OTA_Ping.asmx');
}
//定义酒店周边信息接口地址
if(!defined(D_HotelNearbyInfo_Url)) {
	define('D_HotelNearbyInfo_Url',$ServiceUrlCtripOpenAPI.'/Hotel/D_HotelNearbyInfo.asmx');
}
//定义酒店点评关键字接口地址
if(!defined(D_HotelCommentKey_Url)) {
	define('D_HotelCommentKey_Url',$ServiceUrlCtripOpenAPI.'/Hotel/D_HotelCommentKey.asmx');
}
//定义最新热门酒店点评接口地址
if(!defined(D_HotelHotComment_Url)) {
	define('D_HotelHotComment_Url',$ServiceUrlCtripOpenAPI.'/Hotel/D_HotelHotComment.asmx');
}
//定义品牌的城市分布接口地址
if(!defined(D_GetBrandCityRequest_Url)) {
	define('D_GetBrandCityRequest_Url',$ServiceUrlCtripOpenAPI.'/Hotel/D_GetBrandCityRequest.asmx');
}

//定义最新开业接口地址
if(!defined(D_SearchNewOpenHotel_Url)) {
	define('D_SearchNewOpenHotel_Url',$ServiceUrlCtripOpenAPI.'/Hotel/D_SearchNewOpenHotel.asmx');
}
//定义最新预订接口地址
if(!defined(D_NewBookingHotel_Url)) {
	define('D_NewBookingHotel_Url',$ServiceUrlCtripOpenAPI.'/Hotel/D_NewBookingHotel.asmx');
}
//定义最新加盟接口地址
if(!defined(D_ContractHotel_Url)) {
	define('D_ContractHotel_Url',$ServiceUrlCtripOpenAPI.'/Hotel/D_ContractHotel.asmx');
}
//定义酒店品牌接口地址
if(!defined(D_HotelBrandList_Url)) {
	define('D_HotelBrandList_Url',$ServiceUrlCtripOpenAPI.'/Hotel/D_HotelBrandList.asmx');
}
//定义联盟公告列表地址
if(!defined(A_GetAnnouncementList_Url)) {
	define('A_GetAnnouncementList_Url',$ServiceUrlCtripOpenAPI.'/Hotel/A_GetAnnouncementList.asmx');
}
//定义联盟公告内容地址
if(!defined(A_GetAnnouncement_Url)) {
	define('A_GetAnnouncement_Url',$ServiceUrlCtripOpenAPI.'/Hotel/A_GetAnnouncement.asmx');
}
//定义酒店热卖地址
if(!defined(D_HotelHotSale_Url)) {
	define('D_HotelHotSale_Url',$ServiceUrlCtripOpenAPI.'/Hotel/D_HotelHotSale.asmx');
}
//定义行政区、商业区、景点地址
if(!defined(SearchLocationZoneCityLandmark_Url)) {
	define('SearchLocationZoneCityLandmark_Url',$ServiceUrlCtripOpenAPI.'/Hotel/SearchLocationZoneCityLandmark.asmx');
}
//站点安装的反馈
if(!defined(A_SetRegister_Url)) {
	define('A_SetRegister_Url',$ServiceUrlCtripOpenAPI.'/Custom/A_SetRegister.asmx');
}
//酒店可订性检查接口
if(!defined(OTA_HotelAvail_Url)) {
	define('OTA_HotelAvail_Url',$ServiceUrlCtripOpenAPI.'/Hotel/OTA_HotelAvail.asmx');
}
//酒店可订性检查接口
if(!defined(OTA_HotelRes_Url)) {
	define('OTA_HotelRes_Url',$ServiceUrlCtripOpenAPI.'/Hotel/OTA_HotelRes.asmx');
}
//酒店的静态信息接口
if(!defined(OTA_HotelDescriptiveInfo_Url)) {
	define('OTA_HotelDescriptiveInfo_Url',$ServiceUrlCtripOpenAPI.'/Hotel/OTA_HotelDescriptiveInfo.asmx');
}
//酒店的价格计划接口
if(!defined(OTA_HotelRatePlan_Url)) {
	define('OTA_HotelRatePlan_Url',$ServiceUrlCtripOpenAPI.'/Hotel/OTA_HotelRatePlan.asmx');
}
//酒店的价格缓存变化
if(!defined(OTA_HotelCacheChange_Url)) {
	define('OTA_HotelCacheChange_Url',$ServiceUrlCtripOpenAPI.'/Hotel/OTA_HotelCacheChange.asmx');
}
//酒店搜索
if(!defined(OTA_HotelSearch_Url)) {
	define('OTA_HotelSearch_Url',$ServiceUrlCtripOpenAPI.'/Hotel/OTA_HotelSearch.asmx');
}

//酒店列表
if(!defined(D_HotelList_Url)) {
	define('D_HotelList_Url',$ServiceUrlCtripOpenAPI.'/Hotel/D_HotelList.asmx');
}


//酒店房型list
if(!defined(D_HotelSubRoomList_Url)) {
	define('D_HotelSubRoomList_Url',$ServiceUrlCtripOpenAPI.'/Hotel/D_HotelSubRoomList.asmx');
}

//酒店描述
if(!defined(D_HotelDescription_Url)) {
	define('D_HotelDescription_Url',$ServiceUrlCtripOpenAPI.'/Hotel/D_HotelDescription.asmx');
}

//酒店ID List
if(!defined(D_HotelSearch_IDList_Url)) {
	define('D_HotelSearch_IDList_Url',$ServiceUrlCtripOpenAPI.'/Hotel/D_HotelSearch_IDList.asmx');
}

//定义酒店信用卡详细查询接口的URL
if(!defined(D_HotelCreditCard_Url)) {
	define('D_HotelCreditCard_Url',$ServiceUrlCtripOpenAPI.'/hotel/D_HotelCreditCard.asmx');
}

//定义酒店基本信息详细查询接口的URL
if(!defined(D_HotelDetailList_Main_Url)) {
	define('D_HotelDetailList_Main_Url',$ServiceUrlCtripOpenAPI.'/hotel/D_HotelDetailList_Main.asmx');
}

//定义酒店设施查询接口的URL
if(!defined(D_HotelFacility_Url)) {
	define('D_HotelFacility_Url',$ServiceUrlCtripOpenAPI.'/hotel/D_HotelFacility.asmx');
}

//定义酒店图片详细查询接口的URL
if(!defined(D_HotelPicList_Url)) {
	define('D_HotelPicList_Url',$ServiceUrlCtripOpenAPI.'/hotel/D_HotelPicList.asmx');
}

//定义酒店交通信息接口的URL
if(!defined(D_HotelPlaceInfoList_Url)) {
	define('D_HotelPlaceInfoList_Url',$ServiceUrlCtripOpenAPI.'/hotel/D_HotelPlaceInfoList.asmx');
}

//流量查询接口的URL
if(!defined(IPSMonitorDataFlowForToday_Url)) {
	define('IPSMonitorDataFlowForToday_Url',$ServiceUrlCtripOpenAPI.'/Custom/IPSMonitorDataFlowForToday.asmx');
}


//定义本系统的对于API2.0采用的请求模式：httpRequest/soap(如果PHP的服务器上没有开启支持SOAP的功能，则用httpRequest)
if(!defined(System_RequestType)) {
	define('System_RequestType','httpRequest');//soap  httpRequest
}

//定义首页团购获取距离今天多少天内的产品
if(!defined(TuanEndDate_Distance)) {
	define('TuanEndDate_Distance','7');
}

//添加分销权限控制类
include_once (ABSPATH.'Common/rightControl.php');
//添加请求控制类（http请求还是soap请求）
include_once (ABSPATH.'Common/commonRequestData.php');
//工具类
include_once (ABSPATH.'Common/toolExt.php');
//http请求模式类
include_once (ABSPATH.'Common/httpRequestData.php');
//soap请求模式类
include_once (ABSPATH.'Common/soapData.php');
//http请求的类
include_once (ABSPATH.'Common/HttpRequest.php');
//工具类
include_once (ABSPATH.'Common/getDate.php');
//解析酒店API2.0返回的字符串为XML
include_once (ABSPATH.'Common/RequestDomXml.php');
?>