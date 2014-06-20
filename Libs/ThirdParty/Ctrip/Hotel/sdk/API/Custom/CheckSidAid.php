<?php
/**
 *
 * 通过接口检验联盟用户输入的SID,AllianceId,key是否合法
 * @author cltang
 *
 */
class checkAllianceInfo{
	/**
	 * 用户的SID，必须填写
	 */
	var $SID="";
	/**
	 * 用户的AllianceId，必须填写
	 */
	var $AllianceId="";
	/**
	 * 用户的key，必须填写
	 */
	var $key="";
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
		$AllianceID=$this->AllianceId;
		$SID=$this->SID;
		$KEYS=$this->key;
		$RequestType="OTA_Ping";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);
		$gettime=getDateYMD('-')."T00:00:00.000+08:00";
		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
  <Header $headerRight/>
  <HotelRequest>
    <RequestBody xmlns:ns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
      <ns:OTA_PingRQ>
<ns:EchoData>checkAllianceId</ns:EchoData>
</ns:OTA_PingRQ>
    </RequestBody>
  </HotelRequest>
</Request>
BEGIN;
		return  $paravalues;
	}
	/**
	 *
	 * 调用OTA测试接口
	 */
	function main(){
		try{
			$requestXML=$this->getRequestXML();
			// echo $requestXML;
			$commonRequestDo=new commonRequest();//常用数据请求
	 	$commonRequestDo->requestURL=OTA_Ping_Url;
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
