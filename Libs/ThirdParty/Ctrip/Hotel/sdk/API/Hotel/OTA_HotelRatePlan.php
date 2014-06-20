<?php
/**
 *
 * 酒店的价格计划接口
 * @author liuw2
 *
 */
class set_OTA_HotelRatePlan{
	/**
	 * 入住日期
	 */
	var $Start="";
	/**
	 * 离店日期
	 */
	var $End="";
	
	/**
	 * 价格计划查询条件列表
	 */
	var $RatePlanCandidates="";

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
		$RequestType="OTA_HotelRatePlan";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);
		$gettime=getDateYMD('-')."T00:00:00.000+08:00";
		
		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
  <Header $headerRight/>
  <HotelRequest>
    <RequestBody xmlns:ns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    	
		<ns:OTA_HotelRatePlanRQ TimeStamp="$gettime" Version="1.0">
             <ns:RatePlans>
                 <ns:RatePlan>
                        <ns:DateRange Start="$this->Start" End="$this->End"/>
                         <ns:RatePlanCandidates>
                         		$this->RatePlanCandidates	
                        </ns:RatePlanCandidates>
                    </ns:RatePlan>
               </ns:RatePlans>
         </ns:OTA_HotelRatePlanRQ>
    
		   
		   
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
		 	$commonRequestDo->requestURL=OTA_HotelRatePlan_Url;
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
 