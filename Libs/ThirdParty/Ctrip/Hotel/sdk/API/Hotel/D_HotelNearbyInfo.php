<?php
/**
 * 请求D_HotelNearbyInfo的服务(酒店周边信息)
 */
class get_D_HotelNearbyInfo{
	 
	/**
	 * 酒店ID-必须填写
	 */
	var $Hotel="";
	/**
	 * 距离酒店距离
	 */
	var $Distance="";
	/**
	 * 酒店数量
	 */
	var $HotelNums="";
	/**
	 * 周边信息
	 */
	var $IsHotPlace="";
	/**
	 *返回体
	 */
	var $ResponseXML="";

	/**
	 * 构造请求体
	 */
	private  function getRequestXML()
	{
		/*
		 * 从config.php中获取系统的联盟信息(只读)
		 */
		$AllianceID=Allianceid;
		$SID=Sid;
		$KEYS=SiteKey;
		$RequestType="D_HotelNearbyInfo";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);

		$Hotelss="";
		if($this->Hotel!=""){
			$Hotelss=<<<BEGIN
<HotelID>$this->Hotel</HotelID>
BEGIN;
		}
		$Distances="";
		if($this->Distance!=""){
			$Distances=<<<BEGIN
<Distance>$this->Distance</Distance>
BEGIN;
		}
		$HotelNums="";
		if($this->HotelNums!=""){
			$HotelNums=<<<BEGIN
<HotelNums>$this->HotelNums</HotelNums>
BEGIN;
		}
		$IsHotPlaces="";
		if($this->IsHotPlace!=""){
			$IsHotPlaces=<<<BEGIN
<IsHotPlace>$this->IsHotPlace</IsHotPlace>
BEGIN;
		}
		
		
		
		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
<Header $headerRight/>
<DomesticHotelNearbyInfoRequest>$Hotelss$Distances$HotelNums$IsHotPlaces</DomesticHotelNearbyInfoRequest>
</Request>
BEGIN;
		
		return  $paravalues;
	}
	 
	/**
	 *
	 * 调用酒店的周边设施及周边酒店的接口，获取到酒酒店的周边设施及周边酒店
	 */
	function main(){
		try{
	 	$requestXML=$this->getRequestXML();
	 	
	 	$commonRequestDo=new commonRequest();//常用数据请求
	 	$commonRequestDo->requestURL=D_HotelNearbyInfo_Url;
	 	$commonRequestDo->requestXML=$requestXML;
	 	$commonRequestDo->requestType=System_RequestType;//取config中的配置
	 	$commonRequestDo->doRequest();
	 	$returnXML=$commonRequestDo->responseXML;
	 	// echo json_encode($returnXML);//校验请求数据-临时用
	 	//调用Common/RequestDomXml.php中函数解析返回的XML
	 	$this->ResponseXML=getXMLFromReturnString($returnXML);
	 	 	//print_r($this->ResponseXML);
		}
		catch(Exception $e)
		{
			$this->ResponseXML=null;
		}
	}
}
?>