<?php
/**
 * 请求GroupProductListRequest的服务
 */
class get_GroupProductList{
	/**
	 * 城市，必须填写
	 */
	var $City="";
	/**
	 * 开始时间，必须填写
	 */
	var $BeginDate="";
	/**
	 * 结束时间，必须填写
	 */
	var $EndDate="";
	/**
	 * 关键字-供模糊查询
	 */
	var $KeyWords="";
	/**
	 * 请求条数，最大500 ，必须填写
	 */
	var $Topcount="";
	/**
	 * 排序方式：0 携程推荐;1 折扣从高到低;2  折扣从低到高;3 价格从高到低;4 价格从低到高;5 销量从高到低;6 销量从低到高;7 星级从高到低;8 星级从低到高;9 产品即将开团;10 产品即将到期
	 */
	var $SortType="";
	/**
	 *最低价
	 */
	var $Lowprice="";
	/**
	 *最高价格
	 */
	var $Upperprice="";
	/**
	 *返回体
	 */
	var $ResponseXML="";
	/**
	 * 
	 * 直接获取到权限url(非必填，提供获取)
	 */
	var $headerRight_url;
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
		$RequestType="GroupProductList";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);
		$this->headerRight_url=getRightStringURL($AllianceID,$SID,$KEYS,$RequestType);
		$citys="";
		if($this->City!=""){
			$citys=<<<BEGIN
<City>$this->City</City>
BEGIN;
		}
		$BeginDates="";
		if($this->BeginDate!=""){
			$BeginDates=<<<BEGIN
<BeginDate>$this->BeginDate</BeginDate>
BEGIN;
		}
		$EndDates="";
		if($this->EndDate!=""){
			$EndDates=<<<BEGIN
<EndDate>$this->EndDate</EndDate>
BEGIN;
		}
		$KeyWordss="";
		if($this->KeyWords!=""){
			$KeyWordss=<<<BEGIN
<KeyWords>$this->KeyWords</KeyWords>
BEGIN;
		}
		$Lowprices="";
		if($this->Lowprice!=""){
			$Lowprices=<<<BEGIN
<Lowprice>$this->Lowprice</Lowprice>
BEGIN;
		}
		$Upperprices="";
		if($this->Upperprice!=""){
			$Upperprices=<<<BEGIN
<Upperprice>$this->Upperprice</Upperprice>
BEGIN;
		}
		$Topcounts="";
		if($this->Topcount!=""){
			$Topcounts=<<<BEGIN
<Topcount>$this->Topcount</Topcount>
BEGIN;
		}
		$SortTypes="";
		if($this->SortType!=""){
			$SortTypes=<<<BEGIN
<SortType>$this->SortType</SortType>
BEGIN;
		}
		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
<Header $headerRight/>
<GroupProductListRequest>$citys$BeginDates$EndDates$KeyWordss$Lowprices$Upperprices$Topcounts$SortTypes<ProductType>1</ProductType><Rank>0</Rank></GroupProductListRequest>
</Request>
BEGIN;
		return  $paravalues;
	}
	/**
	 *
	 * 调用酒店团购列表的接口，获取到酒店团购的数据(数据中包括非酒店的团购数据，要做ItemType="酒店"的过滤)
	 */
	function main(){
		try{
	 	$requestXML=$this->getRequestXML();
	  //echo $requestXML; //返回的数据中，我们只取 酒店的团购
	 	$commonRequestDo=new commonRequest();//常用数据请求
	 	$commonRequestDo->requestURL=GroupProductList_Url;
	 	$commonRequestDo->requestXML=$requestXML;
	 	$commonRequestDo->requestType=System_RequestType;//取config中的配置
	 	$commonRequestDo->doRequest();
	 	$this->ResponseXML=getXMLFromReturnString($commonRequestDo->responseXML);
		}
		catch(Exception $e)
		{
			$this->ResponseXML=null;
		}
	}
}
?>