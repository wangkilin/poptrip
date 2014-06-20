<?php
/**
 *
 * 通过接口取消用户的订单
 * @author cltang
 *
 */
class set_OTA_OrderCancel{
	/**
	 * 外部用户的用户名，必须填写
	 */
	var $UID="";
	/**
	 * 订单号码
	 */
	var $OrderId="";
	/**
	 * 订单取消的原因
	 */
	var $ReasonText="";
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
		$RequestType="OTA_Cancel";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);
		$gettime=getDateYMD('-')."T00:00:00.000+08:00";
		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
  <Header $headerRight/>
  <HotelRequest>
    <RequestBody xmlns:ns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
      <ns:OTA_CancelRQ Version="1.0" TimeStamp="$gettime">
       <ns:UniqueID ID="$AllianceID" Type="28"/>
<ns:UniqueID ID="$SID" Type="503"/>
<ns:UniqueID ID="$this->UID" Type="1"/>
<ns:UniqueID ID="$this->OrderId" Type="501"/>
        <ns:Reasons>
         <ns:Reason Type="506">$this->ReasonText</ns:Reason>
        </ns:Reasons>
      </ns:OTA_CancelRQ>
    </RequestBody>
  </HotelRequest>
</Request>
BEGIN;
		return  $paravalues;
	}
	/**
	 *
	 * 调用取消订单的OTA接口
	 */
	function main(){
		try{
			$requestXML=$this->getRequestXML();
			$commonRequestDo=new commonRequest();//常用数据请求
		 	$commonRequestDo->requestURL=OTA_OrderCancel_Url;
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
 