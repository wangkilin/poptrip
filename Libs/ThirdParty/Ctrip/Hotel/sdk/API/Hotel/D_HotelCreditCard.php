<?php
/**
 *
 * 获取酒店的详细信息（信用卡担保）（此接口为D_HotelDetail拆分接口，请求体与D_HotelDetail一致）
 * @author cltang
 *
 */
class get_D_HotelCreditCard{
	/**
	 * 城市的ID，必须填写
	 */
	var $CityID="";
	/**
	 * 入住时间，必须填写
	 */
	var $CheckInDate="";
	/**
	 * 离店时间，必须填写
	 */
	var $CheckOutDate="";
	/**
	 * 酒店的ID
	 */
	var $HotelID="";
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
		$RequestType="D_HotelCreditCard";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);
		$city="";
		if($this->CityID!=""){
			$city=<<<BEGIN
<CityID>$this->CityID</CityID>
BEGIN;
		}
		$checkIn="";
		if($this->CheckInDate!=""){
			$checkIn=<<<BEGIN
<CheckInDate>$this->CheckInDate</CheckInDate>
BEGIN;
		}
		$checkOut="";
		if($this->CheckOutDate!=""){
			$checkOut=<<<BEGIN
<CheckOutDate>$this->CheckOutDate</CheckOutDate>
BEGIN;
		}
		$hotelIDs="";
		if($this->HotelID!=""){
			$hotelIDs=<<<BEGIN
<HotelID>$this->HotelID</HotelID>
BEGIN;
		}

		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
<Header $headerRight/>
<DomesticHotelProductDetailRequest>$city$checkIn$checkOut$hotelIDs</DomesticHotelProductDetailRequest>
</Request>
BEGIN;
		return  $paravalues;
	}
	/**
	 *
	 * 调用直接查询酒店详情的接口，获取到酒店的数据
	 */
	function main(){
		try{
	 	$requestXML=$this->getRequestXML();
	 	$commonRequestDo=new commonRequest();//常用数据请求
	 	$commonRequestDo->requestURL=D_HotelCreditCard_Url;
	 	$commonRequestDo->requestXML=$requestXML;
	 	$commonRequestDo->requestType=System_RequestType;//取config中的配置
	 	$commonRequestDo->doRequest();
	 	$returnXML=$commonRequestDo->responseXML;
	 	$this->ResponseXML=getXMLFromReturnString($returnXML);//$returnXML->RequestResult;
		}
		catch(Exception $e)
		{
			$this->ResponseXML=null;
		}
	}
	
}