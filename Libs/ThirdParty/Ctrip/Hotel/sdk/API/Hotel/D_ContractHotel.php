<?php
/**
 * 请求D_ContractHotel的服务(最新加盟)
 */
class get_D_ContractHotel{
	 
	/**
	 * 加盟周期最早时间-必须填写
	 */
	var $ContractDateStart="";
	/**
	 * 加盟周期最晚时间-必须填写
	 */
	var $ContractDateEnd="";
	/**
	 * 城市ID-必须填写
	 */
	var $CityID="";
	/**
	 * 请求的页码，必须填写
	 */
	var $CurPage="";
	/**
	 * 请求条数，必须填写
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
		$RequestType="D_ContractHotel";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);

		$ContractDateStarts="";
		if($this->ContractDateStart!=""){
			$ContractDateStarts=<<<BEGIN
<ContractDateStart>$this->ContractDateStart</ContractDateStart>
BEGIN;
		}
		$ContractDateEnds="";
		if($this->ContractDateEnd!=""){
			$ContractDateEnds=<<<BEGIN
<ContractDateEnd>$this->ContractDateEnd</ContractDateEnd>
BEGIN;
		}
		$CityIDs="";
		if($this->CityID!=""){
			$CityIDs=<<<BEGIN
<CityID>$this->CityID</CityID>
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
<DomesticContractHotelRequest>$ContractDateStarts$ContractDateEnds$CityIDs$CurPages$PageCounts</DomesticContractHotelRequest>
</Request>
BEGIN;
		return  $paravalues;
	}
	 
	/**
	 *
	 * 调用最新加盟接口，获取到最新加盟酒店数据
	 */
	function main(){
		try{
	 	$requestXML=$this->getRequestXML();
	 	$commonRequestDo=new commonRequest();//常用数据请求
	 	$commonRequestDo->requestURL=D_ContractHotel_Url;
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