<?php
/**
 * 请求D_GetBrandCityRequest的服务(品牌的城市分布 )
 */
class get_D_GetBrandCityRequest{
	 
	/**
	 * BrandID-必须填写
	 */
	var $BrandID="";
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
		$RequestType="D_GetBrandCityRequest";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);

		$BrandIDs="";
		if($this->BrandID!=""){
			$BrandIDs=<<<BEGIN
			<BrandID>$this->BrandID</BrandID>
BEGIN;
		}
		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
<Header $headerRight/>
<DomesticGetBrandCityRequest>$BrandIDs</DomesticGetBrandCityRequest>
</Request>
BEGIN;
		return  $paravalues;
	}
	 
	/**
	 *
	 * 调用查询品牌的城市分布的接口，获取到品牌的城市分布数据
	 */
	function main(){
		try{
	 	$requestXML=$this->getRequestXML();
	 	$commonRequestDo=new commonRequest();//常用数据请求
	 	$commonRequestDo->requestURL=D_GetBrandCityRequest_Url;
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