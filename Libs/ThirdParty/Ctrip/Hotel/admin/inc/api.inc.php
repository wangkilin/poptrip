<?php
//整站前台所用到的API接口
$hotelApiArray=array('SearchLocationZoneCityLandmark'=>'城市地标',
						'D_HotelHotComment'=>'城市热门酒店点评',
						'D_HotelCommentListWithPage'=>'酒店点评',
						'D_HotelHotSale'=>'城市热门酒店',
						'D_GetBrandCityRequest'=>'品牌酒店',
						'D_HotelBrandList'=>'城市品牌分布',
						'D_NewBookingHotel'=>'最新预定',
						'D_SearchNewOpenHotel'=>'最新开业',
						'D_ContractHotel'=>'最新加盟',
						'D_HotelList'=>'酒店列表简（Search拆分）',
						'D_HotelDescription'=>'酒店描述列表（Search拆分）',
						'D_HotelSubRoomList'=>'酒店子房型（Search拆分）',
						'D_HotelPlaceInfoList'=>'酒店详情周边交通（Detail拆分）',
						'D_HotelSearch'=>'酒店列表',
						
						'D_HotelDetail'=>'酒店详情',
						'D_HotelCommentKey'=>'酒店点评关键字',
						'D_HotelNearbyInfo'=>'酒店周边信息'
);


//PHP与新接口类名不一致
$PHP_APIFunction=array('D_HotelCommentListWithPage'=>'D_SearchHotelCommentWithPage',
					   	'D_HotelHotSale'=>'D_HotelHotSaleList',
						'D_HotelBrandList'=>'D_HotelGetBrand',
						'D_SearchNewOpenHotel'=>'D_NewOpenHotel'
);




/*未用到的拆分接口
 * 
 * 
 * 'D_HotelDetail_Detail'=>'酒店详情基本信息',
'D_HotelDetail_Facility'=>'酒店详情周边设施',
'D_HotelDetail_HotelPicList'=>'酒店图片',
'D_HotelIdList'=>'酒店ID列表',
'D_HotelDetail_CreditCard'=>'酒店详情信用卡信息',
*/


require_once (WEBROOT.'SDK.config.php');//加载SDK配置文件
require_once (WEBROOT.'/sdk/API/Custom/IPSMonitorDataFlowForToday.php');

$IPSMonitorDataFlowForTodayList=new get_IPSMonitorDataFlowForToday();
$IPSMonitorDataFlowForTodayList->main();
$responseXML=$IPSMonitorDataFlowForTodayList->ResponseXML;


$IPSDataFlowForToday=array();
$i=1;
if($responseXML->IPSMonitorDataFlowForTodayResponse->AllianceIPSMonitorDataFlowForTodayList->AllianceIPSMonitorDataFlowForToday){
	foreach ($responseXML->IPSMonitorDataFlowForTodayResponse->AllianceIPSMonitorDataFlowForTodayList->AllianceIPSMonitorDataFlowForToday  as $v){
		$IPSDataFlowForToday[(string)$v->APIName]['SumDataFlow']=$IPSDataFlowForToday[(string)$v->APIName]['SumDataFlow']+(string)$v->SumDataFlow;//相同APIName累加
	//	$IPSDataFlowForToday[$i][APIName]=(string)$v->APIName;
		//$IPSDataFlowForToday[$i][ISGzip]=(string)$v->ISGzip;
		$i++;
	}
}else{
	$is_AllDataFlow=1;//只有总接口流量的情况
}

function checkAPI($api){
	$AllianceID=Allianceid;
	$SID=Sid;
	$KEYS=SiteKey;
	$RequestType=$api;
	$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);
	
	//构造请求体，请求数据为虚假信息
	$requestXML=<<<BEGIN
<?xml version="1.0"?>
<Request>
<Header $headerRight/>
errorrequest
</Request>
BEGIN;

	global $ServiceUrlCtripOpenAPI;
	$commonRequestDo=new commonRequest();//常用数据请求
 	$commonRequestDo->requestURL=$ServiceUrlCtripOpenAPI.'/hotel/'.$api.'.asmx';
 	$commonRequestDo->requestXML=$requestXML;
 	$commonRequestDo->requestType=System_RequestType;//取config中的配置
 	$commonRequestDo->doRequest();
 	$returnXML=$commonRequestDo->responseXML;
 	
	$dom=new DOMDocument('1.0','UTF-8');
	$dom->loadXML(trim($returnXML));
	$xml = simplexml_import_dom($dom);
 	return $xml->Header;
	
}
