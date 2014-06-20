<?php
/**
 *@var 获取指定酒店的评论--带有分页功能的酒店点评接口
 */
class get_D_HotelCommentWithPage{
	/**
	 * @var 酒店的ID，必须填写
	 * @var 数字
	 */
	var $HotelID="";
	/**
	 * @var 每页显示多少条数据
	 * @var 数字
	 */
	var $PageSize=10;
	/**
	 * @var 当前显示第几页
	 * @var 数字
	 */
	var $PageNo=1;
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
		$RequestType="D_HotelCommentListWithPage";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);
		$hotelIDs="";
		if($this->HotelID!=""){
			$hotelIDs=<<<BEGIN
<HotelID>$this->HotelID</HotelID>
BEGIN;
		}
		$PageSizes="";
		if($this->PageSize!=""){
			$PageSizes=<<<BEGIN
<PageSize>$this->PageSize</PageSize>
BEGIN;
		}
		$PageNos="";
		if($this->PageNo!=""){
			$PageNos=<<<BEGIN
<PageNo>$this->PageNo</PageNo>
BEGIN;
		}

		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
<Header $headerRight/>
<DomesticSearchHotelCommentRequest>$hotelIDs$PageSizes$PageNos</DomesticSearchHotelCommentRequest>
</Request>
BEGIN;
		return  $paravalues;
	}
	/**
	 *
	 *@var 调用直接查询酒店评价的接口(带有分页功能)，获取到酒店评价的数据
	 */
	function main(){
		try{
			$requestXML=$this->getRequestXML();
			$commonRequestDo=new commonRequest();//常用数据请求
	 	    $commonRequestDo->requestURL=D_HotelCommentListPage_Url;
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