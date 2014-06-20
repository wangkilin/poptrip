<?php
/**
 * 请求A_SetRegister的服务
 */
class get_A_SetRegister{
	 
	/**
	 * IP-必须填写
	 */
	var $IP="";
	/**
	 * 安装时间-必须填写
	 */
	var $SetupDatetime="";
	
	/**
	 * AID-必须填写
	 */
	var $AllianceID="";
	
	/**
	 * SID-必须填写
	 */
	var $SID="";
	
	/**
	 * KEY-必须填写
	 */
	var $KEYS="";
	
	
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
		if($this->AllianceID!="")$AllianceID=$this->AllianceID;
		if($this->SID!="")$SID=$this->SID;
		if($this->KEY!="")$KEYS=$this->KEY;
		$RequestType="A_SetRegister";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);

		$IPs="";
		if($this->IP!=""){
			$IPs=<<<BEGIN
<IP>$this->IP</IP>
BEGIN;
		}
		$SetupDatetimes="";
		if($this->SetupDatetime!=""){
			$SetupDatetimes=<<<BEGIN
<SetupDatetime>$this->SetupDatetime</SetupDatetime>
BEGIN;
		}
		
		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
<Header $headerRight/>
<SetRegisterRequest>$IPs$SetupDatetimes</SetRegisterRequest>
</Request>
BEGIN;

			//echo $paravalues;die;
		return  $paravalues;
		
		
	
		
	}
	 
	/**
	 *
	 * 开始组测
	 */
	function main(){
		try{
	 	$requestXML=$this->getRequestXML();
	 	$commonRequestDo=new commonRequest();//常用数据请求
	 	$commonRequestDo->requestURL=A_SetRegister_Url;
	 	$commonRequestDo->requestXML=$requestXML;
	 	$commonRequestDo->requestType=System_RequestType;//取config中的配置
	 	$commonRequestDo->doRequest();
	 	
	 	//print_r($commonRequestDo);die;
	 	
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