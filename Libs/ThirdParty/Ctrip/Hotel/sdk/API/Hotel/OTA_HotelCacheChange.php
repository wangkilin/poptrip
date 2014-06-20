<?php
/**
 *
 * 酒店的价格缓存变化
 * @author liuw2
 *
 */
class set_OTA_HotelCacheChange{
	/**
	 * 缓存最后刷新时间
	 */
	var $CacheFromTimestamp="";
	/**
	 * 城市ID
	 */
	var $HotelCityCode="";
	
	/**
	 * 酒店ID
	 */
	var $HotelCode="";

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
		$RequestType="OTA_HotelCacheChange";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);
		
		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
  <Header $headerRight/>
  <HotelRequest>
    <RequestBody xmlns:ns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    	
		<ns:OTA_HotelCacheChangeRQ Version="1.0">
              <ns:CacheSearchCriteria CacheFromTimestamp="$this->CacheFromTimestamp">
                     <ns:CacheSearchCriterion HotelCityCode="$this->HotelCityCode" HotelCode="$this->HotelCode"/>
              </ns:CacheSearchCriteria>
        </ns:OTA_HotelCacheChangeRQ>
		   
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
		 	$commonRequestDo->requestURL=OTA_HotelCacheChange_Url;
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
 