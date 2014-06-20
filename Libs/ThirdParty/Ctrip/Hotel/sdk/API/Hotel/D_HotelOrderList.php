<?php
/**
 * 请求D_HotelOrderList的服务（酒店订单列表）
 */
class get_D_HotelOrderList{
	/**
	 * 入住人姓名
	 */
	var $CheckInName="";
	/**
	 * 订单号列表（用“,”隔开），必须填写
	 */
	var $OrderIDs="";
	/**
	 * 入店时间，必须填写
	 */
	var $CheckInDate="";
	/**
	 * 离店时间，必须填写
	 */
	var $CheckOutDate="";
	/**
	 * 订单范围（0-全国酒店；1-国内酒店；2-海外酒店）
	 */
	var $OrderRange=0;
	/**
	 * 订单状态（0-全部订单；1-未提交；2-处理中；3-已完成）
	 */
	var $OrderStatus=2;
	/**
	 * 预订方式（0-全部方式；1-网上预订；2-电话预订）
	 */
	var $Reservation=0;
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
		$RequestType="D_HotelOrderList";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);
		$CheckInNames="";
		if($this->CheckInName!=""){
			$CheckInNames=<<<BEGIN
<CheckInName>$this->CheckInName</CheckInName>
BEGIN;
		}
		$OrderIDss=$this->getOrderIDs($this->OrderIDs);//构建订单的ID
	    
		$CheckInDates="";
		if($this->CheckInDate!=""){
			$CheckInDates=<<<BEGIN
<CheckInDate>$this->CheckInDate</CheckInDate>
BEGIN;
		}
		
		$CheckOutDates="";
		if($this->CheckOutDate!=""){
			$CheckOutDates=<<<BEGIN
<CheckOutDate>$this->CheckOutDate</CheckOutDate>
BEGIN;
		}
		$OrderRanges="";
		if($this->OrderRange!=null){
			$OrderRanges=<<<BEGIN
<OrderRange>$this->OrderRange</OrderRange>
BEGIN;
		}
		$OrderStatuss="";
		if($this->OrderStatus!=null){
			$OrderStatuss=<<<BEGIN
<OrderStatus>$this->OrderStatus</OrderStatus>
BEGIN;
		}
		$Reservations="";
		if($this->Reservation!=null){
			$Reservations=<<<BEGIN
<Reservation>$this->Reservation</Reservation>
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
<DomesticHotelOrderListRequest>$CheckInNames$OrderIDss$CheckInDates$CheckOutDates$OrderRanges$OrderStatuss$Reservations$UserIDs$UserIPs</DomesticHotelOrderListRequest>
</Request>
BEGIN;
		return  $paravalues;
	}
	/**
	 *
	 * 将包含多个订单号的字符串做成符合要求的XML（分隔符为“,”）
	 * @param $orderid
	 */
	private  function getOrderIDs($orderid)
	{
		$coutw="";
		if($orderid!=null&&$orderid!=""&&strpos($orderid,','))
		{
			$coutw="<OrderList>";
			$orderid=str_replace(",","</OrderID></DomesticHotelOrderRequest><DomesticHotelOrderRequest><OrderID>",$orderid);
			$coutw="<OrderList><DomesticHotelOrderRequest><OrderID>".$orderid."</OrderID></DomesticHotelOrderRequest></OrderList>";
		}
		else 
		{
			if(strlen($orderid)>0)
			{
				$coutw="<OrderList><DomesticHotelOrderRequest><OrderID>$orderid</OrderID></DomesticHotelOrderRequest></OrderList>";
			}
		}
		return $coutw;
	}
	/**
	 *
	 * 调用查询酒店订单列表的接口，获取到酒店的订单数据
	 */
	function main(){
		try{
	 	$requestXML=$this->getRequestXML();
	 	$commonRequestDo=new commonRequest();//常用数据请求
	 	$commonRequestDo->requestURL=D_HotelOrderList_Url;
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