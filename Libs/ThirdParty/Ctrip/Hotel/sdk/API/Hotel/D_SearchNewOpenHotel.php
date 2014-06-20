<?php
/**
 * 请求D_SearchNewOpenHotel的服务(最新开业)
 */
class get_D_SearchNewOpenHotel{
	 
	/**
	 * 开业周期最早时间-必须填写
	 */
	var $OpenYearDateStart="";
	/**
	 * 开业周期最晚时间-必须填写
	 */
	var $OpenYearDateEnd="";
	/**
	 * 城市ID-必须填写
	 */
	var $CityID="";
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
		$RequestType="D_SearchNewOpenHotel";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);

		$OpenYearDateStarts="";
		if($this->OpenYearDateStart!=""){
			$OpenYearDateStarts=<<<BEGIN
<OpenYearDateStart>$this->OpenYearDateStart</OpenYearDateStart>
BEGIN;
		}
		$OpenYearDateEnds="";
		if($this->OpenYearDateEnd!=""){
			$OpenYearDateEnds=<<<BEGIN
<OpenYearDateEnd>$this->OpenYearDateEnd</OpenYearDateEnd>
BEGIN;
		}
		$CityIDs="";
		if($this->CityID!=""){
			$CityIDs=<<<BEGIN
<CityID>$this->CityID</CityID>
BEGIN;
		}
		
		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
<Header $headerRight/>
<DomesticNewOpenHotelRequest>$OpenYearDateStarts$OpenYearDateEnds$CityIDs</DomesticNewOpenHotelRequest>
</Request>
BEGIN;
		return  $paravalues;
	}
	 
	/**
	 *
	 * 调用最新开业接口，获取到最新开业酒店数据
	 */
	function main(){
		try{
	 	$requestXML=$this->getRequestXML();
	 	$commonRequestDo=new commonRequest();//常用数据请求
	 	$commonRequestDo->requestURL=D_SearchNewOpenHotel_Url;
	 	$commonRequestDo->requestXML=$requestXML;
	 	$commonRequestDo->requestType=System_RequestType;//取config中的配置
	 	$commonRequestDo->doRequest();
	 	$returnXML=$commonRequestDo->responseXML;
	 	
	 	// echo json_encode($returnXML);//校验请求数据-临时用
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