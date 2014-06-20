<?php
/**
 *
 * 获取团购酒店的详细信息
 * @author cltang
 *
 */
class get_GroupProductDetail{
	/**
	 * 产品的ID，必须填写
	 */
	var $ProductID="";
	/**
	 *返回体
	 */
	var $ResponseXML="";
	/**
	 * 构造请求体
	 */
	var $ResponseXMLTemp="";
	private  function getRequestXML()
	{
		/*
		 * 从config.php中获取系统的联盟信息(只读)
		 */
		$AllianceID=Allianceid;
		$SID=Sid;
		$KEYS=SiteKey;
		$RequestType="GroupProductInfo";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);
		$ProductIDs="";
		if($this->ProductID!=""){
			$ProductIDs=<<<BEGIN
<ProductID>$this->ProductID</ProductID>
BEGIN;
		}
		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
<Header $headerRight/>
<GroupProductInfoRequest>$ProductIDs</GroupProductInfoRequest>
</Request>
BEGIN;
		return  $paravalues;
	}
	/**
	 *
	 * 调用酒店团购详情的接口，获取到数据
	 */
	function main(){
		try{
	 	$requestXML=$this->getRequestXML();
	 	 
	 	$commonRequestDo=new commonRequest();//常用数据请求
	 	$commonRequestDo->requestURL=GroupProductInfo_Url;
	 	$commonRequestDo->requestXML=$requestXML;
	 	$commonRequestDo->requestType=System_RequestType;//取config中的配置
	 	$commonRequestDo->doRequest();
	 	$returnXML=$commonRequestDo->responseXML;
	 	$this->ResponseXML=getXMLFromReturnString($returnXML);
		}
		catch(Exception $e)
		{
			$this->ResponseXML=null;
		}
	}
}