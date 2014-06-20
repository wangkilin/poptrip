<?php
/**
 *
 * 酒店的静态信息接口
 * @author liuw2
 *
 */
class set_OTA_HotelDescriptiveInfo{
	/**
	 * 酒店详细描述信息请求列表
	 */
	var $HotelDescriptiveInfos="";

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
		$RequestType="OTA_HotelDescriptiveInfo";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);
		
		
		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
  <Header $headerRight/>
  <HotelRequest>
    <RequestBody xmlns:ns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    	
		   <OTA_HotelDescriptiveInfoRQ Version="1.0" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelDescriptiveInfoRQ.xsd" xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">                      
		    	 <HotelDescriptiveInfos>
			          	$this->HotelDescriptiveInfos
		          </HotelDescriptiveInfos>
		   </OTA_HotelDescriptiveInfoRQ>
     </RequestBody>
  </HotelRequest>
</Request>
BEGIN;
		return  $paravalues;
	}

	/**
	 *
	 * 调用接口
	 */
	function main(){
		try{
			$requestXML=$this->getRequestXML();
			$commonRequestDo=new commonRequest();//常用数据请求
		 	$commonRequestDo->requestURL=OTA_HotelDescriptiveInfo_Url;
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
 