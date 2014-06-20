<?php
/**
 * 请求D_NewBookingHotel的服务(最新预定)
 */
class get_D_NewBookingHotel{
	/**
	 * 城市ID-必须填写
	 */
	var $CityID="";
	/**
	 * 预定时间周期-必须填写
	 */
	var $LastHour="";
	/**
	 * 请求的页码，必须填写
	 */
	var $CurPage="";
	/**
	 *  请求条数，必须填写
	 */
	var $PageCount="";
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
		$RequestType="D_NewBookingHotel";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);
		if($this->CityID!=""){
			$CityIDs=<<<BEGIN
<CityID>$this->CityID</CityID>
BEGIN;
		}
		$LastHours="";
		if($this->LastHour!=""){
			$LastHours=<<<BEGIN
<LastHour>$this->LastHour</LastHour>
BEGIN;
		}
		$CurPages="";
		if($this->CurPage!=""){
			$CurPages=<<<BEGIN
<CurPage>$this->CurPage</CurPage>
BEGIN;
		}
		$PageCounts="";
		if($this->PageCount!=""){
			$PageCounts=<<<BEGIN
<PageCount>$this->PageCount</PageCount>
BEGIN;
		}
		
		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
<Header $headerRight/>
<DomesticNewBookingHotelRequest>$CityIDs$LastHours$CurPages$PageCounts</DomesticNewBookingHotelRequest>
</Request>
BEGIN;
		return  $paravalues;
	}
	 
	/**
	 *
	 * 调用最新预定接口，获取到最新预定酒店数据
	 */
	function main(){
		try{
	 	$requestXML=$this->getRequestXML();
	 	$commonRequestDo=new commonRequest();//常用数据请求
	 	$commonRequestDo->requestURL=D_NewBookingHotel_Url;
	 	$commonRequestDo->requestXML=$requestXML;
	 	$commonRequestDo->requestType=System_RequestType;//取config中的配置
	 	$commonRequestDo->doRequest();
	 	$returnXML=$commonRequestDo->responseXML;
	 	//调用Common/RequestDomXml.php中函数解析返回的XML
	 	$this->ResponseXML=getXMLFromReturnString($returnXML);
		}
		catch(Exception $e)
		{
			$this->ResponseXML=null;
		}
	}
}
?>