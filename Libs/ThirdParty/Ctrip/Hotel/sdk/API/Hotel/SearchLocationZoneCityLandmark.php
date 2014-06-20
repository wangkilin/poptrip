<?php
/**
 * 请求SearchLocationZoneCityLandmark的服务(行政区、商业区、景点 )
 */
class get_SearchLocationZoneCityLandmark{
	 
	/**
	 * CityID-必须填写
	 */
	var $City="";
	/**
	 * Type-必须填写（1、行政区；2、商业区；3、景点。（可以组合使用，使用“,”分隔））
	 */
	var $Type="";
	/**
	 * SearchLandmarkType-（默认取值2,3。根据该节点可以查询很多其他数据 ）
	 */
	var $SearchLandmarkType="";
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
		$RequestType="SearchLocationZoneCityLandmark";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);

		//行政区、商业区、景点
		$Citys="";
		if($this->City!=""){
			$Citys=<<<BEGIN
<City>$this->City</City>
BEGIN;
		}
		$Types="";
		if($this->Type!=""){
			$Types=<<<BEGIN
<Type>$this->Type</Type>
BEGIN;
		}
		$SearchLandmarkTypes="";
		if($this->SearchLandmarkType!=""){
			$SearchLandmarkTypes=<<<BEGIN
<SearchLandmarkType>$this->SearchLandmarkType</SearchLandmarkType>
BEGIN;
		}
		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
<Header $headerRight/>
<SearchLocationZoneCityLandmarkRequest>$Citys$Types$SearchLandmarkTypes</SearchLocationZoneCityLandmarkRequest>
</Request>
BEGIN;
		return  $paravalues;
	}
	 
	/**
	 *
	 * 调用行政区、商业区、景点的接口，获取行政区、商业区、景点
	 */
	function main(){
		try{
	 	$requestXML=$this->getRequestXML();
	 	$commonRequestDo=new commonRequest();//常用数据请求
	 	$commonRequestDo->requestURL=SearchLocationZoneCityLandmark_Url;
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