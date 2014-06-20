<?php
/**
 *
 * 酒店搜索
 * @author liuw2
 *
 */
class set_OTA_HotelSearch{
	/**
	 * 城市ID
	 */
	var $HotelCityCode="";
	/**
	 * 酒店名称
	 */
	var $HotelName="";
	/**
	 * 评分者--可空
	 */
	var $Provider="";
	/**
	 * 分数或级别--可空
	 */
	var $Rating="";
	

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
		if($this->Provider){
					$Providers=<<<BEGIN
					<Award Provider="$this->Provider" Rating="$this->Rating"/>
BEGIN;
		}
		
		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
  <Header $headerRight/>
  <HotelRequest>
    <RequestBody xmlns:ns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    	
		<ns:OTA_HotelSearchRQ Version="0.0" PrimaryLangID="zh" xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelSearchRQ.xsd" xmlns="http://www.opentravel.org/OTA/2003/05">
			<ns:Criteria>
				<ns:Criterion>
					<ns:HotelRef HotelCityCode="$this->HotelCityCode" HotelName="$this->HotelName"/>
					$Providers
				</ns:Criterion>
			</ns:Criteria>
		</ns:OTA_HotelSearchRQ>
    
		   
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
		 	$commonRequestDo->requestURL=OTA_HotelSearch_Url;
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
 