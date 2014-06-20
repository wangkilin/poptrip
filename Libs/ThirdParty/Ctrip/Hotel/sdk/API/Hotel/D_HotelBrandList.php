<?php
/**
 * 请求D_HotelBrandList的服务(酒店品牌 )
 */
class get_D_HotelBrandList{
	 
	/**
	 * CityID-必须填写
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
		$RequestType="D_HotelBrandList";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);

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
<GetHotelBrandRequest>$CityIDs</GetHotelBrandRequest>
</Request>
BEGIN;
		return  $paravalues;
	}
	 
	/**
	 *
	 * 调用查询酒店品牌的接口，获取酒店品牌数据
	 */
	function main(){
		try{
	 	$requestXML=$this->getRequestXML();
	 	$commonRequestDo=new commonRequest();//常用数据请求
	 	$commonRequestDo->requestURL=D_HotelBrandList_Url;
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