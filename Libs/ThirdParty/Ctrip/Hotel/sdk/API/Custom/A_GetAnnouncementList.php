<?php
/**
 * 请求A_GetAnnouncementList的服务(联盟公告 )
 */
class get_A_GetAnnouncementList{
	 
	/**
	 * 请求的页码-必须填写
	 */
	var $PageIndex="";
	/**
	 * 请求条数-必须填写
	 */
	var $PageSize="";
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
		$RequestType="A_GetAnnouncementList";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);

		$PageIndexs="";
		if($this->PageIndex!=""){
			$PageIndexs=<<<BEGIN
<PageIndex>$this->PageIndex</PageIndex>
BEGIN;
		}
		$PageSizes="";
		if($this->PageSize!=""){
			$PageSizes=<<<BEGIN
<PageSize>$this->PageSize</PageSize>
BEGIN;
		}
		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
<Header $headerRight/>
<GetAnnouncementListRequest>$PageIndexs$PageSizes</GetAnnouncementListRequest>
</Request>
BEGIN;
		return  $paravalues;
	}
	 
	/**
	 *
	 * 调用联盟公告的接口，获取联盟公告数据
	 */
	function main(){
		try{
	 	$requestXML=$this->getRequestXML();
	 	//echo '<br/>'.htmlentities($requestXML).'<br/><br/>';
	 	$commonRequestDo=new commonRequest();//常用数据请求
	 	$commonRequestDo->requestURL=A_GetAnnouncementList_Url;
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