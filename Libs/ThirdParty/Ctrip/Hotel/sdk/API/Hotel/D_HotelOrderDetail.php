<?php
/**
 * 请求D_HotelOrderDetail的服务(酒店订单详细)
 */
class get_D_HotelOrderDetail{
	 
	/**
	 * 订单号-必须填写
	 */
	var $OrderID="";
	/**
	 * 获取到UID对应的携程账户ID（通过接口获取）这个通过静态数据中的Allianceid_Uid，请求接口获取
	 */
	var $UserID="";//这个通过静态数据中的Allianceid_Uid，请求接口获取
	/**
	 * 当前用户的IP(这个数据直接获取本地的IP)
	 */
	var $UserIP="";//这个数据直接获取本地的IP
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
		$RequestType="D_HotelOrderDetail";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);

		$OrderIDss="";
		if($this->OrderID!=""){
			$OrderIDss=<<<BEGIN
<OrderID>$this->OrderID</OrderID>
BEGIN;
		}
		$UserIDs="";
		if($this->UserID!=""){
			$UserIDs=<<<BEGIN
<UserID>$this->UserID</UserID>
BEGIN;
		}
		$UserIPs="";
		if($this->UserIP!=""){
			$UserIPs=<<<BEGIN
<UserIP>$this->UserIP</UserIP>
BEGIN;
		}
		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
<Header $headerRight/>
<DomesticHotelOrderDetailRequest>$OrderIDss$UserIDs$UserIPs</DomesticHotelOrderDetailRequest>
</Request>
BEGIN;
		return  $paravalues;
	}
	 
	/**
	 *
	 * 调用查询酒店订单列表的接口，获取到酒店的订单数据
	 */
	function main(){
		try{
	 	$requestXML=$this->getRequestXML();
	 	$commonRequestDo=new commonRequest();//常用数据请求
	 	$commonRequestDo->requestURL=D_HotelOrderDetail_Url;
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