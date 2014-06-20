<?php
/**
 * 请求D_HotelHotSale的服务(酒店热卖 )
 */
class get_D_HotelHotSale{
	 
	/**
	 * CityID-必须填写
	 */
	var $City="";
	/**
	 * 热卖类型-必须填写（D、今日热卖；W、上周热卖）
	 */
	var $SumType="D";
	/**
	 * 热卖数据-（当SumType=“D”的时候  processDate才起作用 ）
	 */
	var $ProcessDate="";
	/**
	 * 搜索数量-必须填写
	 */
	var $SearchNumber="50";
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
		$RequestType="D_HotelHotSale";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);

		$Citys="";
		if($this->City!=""){
			$Citys=<<<BEGIN
<City>$this->City</City>
BEGIN;
		}
		$SumTypes="";
		if($this->SumType!=""){
			$SumTypes=<<<BEGIN
<SumType>$this->SumType</SumType>
BEGIN;
		}
		$ProcessDates="";
		if($this->ProcessDate!=""){
			$ProcessDates=<<<BEGIN
<ProcessDate>$this->ProcessDate</ProcessDate>
BEGIN;
		}
		$SearchNumbers="";
		if($this->SearchNumber!=""){
			$SearchNumbers=<<<BEGIN
<SearchNumber>$this->SearchNumber</SearchNumber>
BEGIN;
		}
		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
<Header $headerRight/>
<SearchHotSaleHotelRequest>$Citys$SumTypes$ProcessDates$SearchNumbers</SearchHotSaleHotelRequest>
</Request>
BEGIN;
		return  $paravalues;
	}
	 
	/**
	 *
	 * 调用查询酒店热卖的接口，获取酒店热卖数据
	 */
	function main(){
		try{
	 	$requestXML=$this->getRequestXML();
	 	$commonRequestDo=new commonRequest();//常用数据请求
	 	$commonRequestDo->requestURL=D_HotelHotSale_Url;
	 	$commonRequestDo->requestXML=$requestXML;
	 	$commonRequestDo->requestType=System_RequestType;//取config中的配置
	 	$commonRequestDo->doRequest();
	 	$returnXML=$commonRequestDo->responseXML;

	 	// echo json_encode($returnXML);//校验请求数据-临时用
	 	//调用Common/RequestDomXml.php中函数解析返回的XML
	 	$this->ResponseXML=getXMLFromReturnString($returnXML);
	 	if(empty($this->ResponseXML->SearchHotSaleHotelResponse)){
	 		
	 		//如果昨日热卖没有数据，那么时间点选为前日
	 		$this->ProcessDate=getDateYMD_addDay('-','-2');	
	 		$requestXML=$this->getRequestXML();
		 	$commonRequestDo=new commonRequest();//常用数据请求
		 	$commonRequestDo->requestURL=D_HotelHotSale_Url;
		 	$commonRequestDo->requestXML=$requestXML;
		 	$commonRequestDo->requestType=System_RequestType;//取config中的配置
		 	$commonRequestDo->doRequest();
		 	$returnXML=$commonRequestDo->responseXML;
	 		$this->ResponseXML=getXMLFromReturnString($returnXML);
	 	}
	 	
		}
		catch(Exception $e)
		{
			$this->ResponseXML=null;
		}
	}
}
?>