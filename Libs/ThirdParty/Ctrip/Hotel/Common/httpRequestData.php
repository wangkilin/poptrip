<?php
/**
 * 2012年6月28日 携程 唐春龙 研发中心
 * 通过httpRequest调用远程webservice服务（返回一个XML）
 * @param $responseUrl 远程服务的地址
 * @param $requestXML 远程服务的参数请求体XML
 * @param 返回XML
 */
function httpRequestSoapData($responseUrl,$requestXML)
{
	try{
		$myhttp = new myHttpRequest($responseUrl."?WSDL","POST");
		//--相对于API2.0固定
		$r_head=<<<BEGIN
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
<soap:Body>
<Request xmlns="http://ctrip.com/">
<requestXML>
BEGIN;
		//--相对于API2.0固定
		$r_end=<<<BEGIN
</requestXML>
</Request>
</soap:Body>
</soap:Envelope>
BEGIN;

		//返回头--相对于API2.0固定
		$responseHead=<<<begin
<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema"><soap:Body><RequestResponse xmlns="http://ctrip.com/"><RequestResult>
begin;
		//返回尾--相对于API2.0固定
		$responseEnd=<<<begin
</RequestResult></RequestResponse></soap:Body></soap:Envelope>
begin;


		$requestXML=str_replace("<",@"&lt;",$requestXML);
		$requestXML=str_replace(">",@"&gt;",$requestXML);

		$requestXML=$r_head.$requestXML.$r_end;
		$myhttp->open();
		$myhttp->send($requestXML);
	    
		$responseBodys=$myhttp->getResponseBody();//这里有可能有HEARD，要判断一下
		if(strpos($responseBodys,"Content-Type: text/xml; charset=utf-8"))
		{
			$coutw=$myhttp->responseBodyWithoutHeader;
		}
		else{
			$coutw=$responseBodys;
		}

		//$myhttp->responseBodyWithoutHeader;
		//$coutw=$myhttp->responseBodyWithoutHeader;

		$coutw=str_replace($responseHead,"",$coutw);//替换返回头
		$coutw=str_replace($responseEnd,"",$coutw);//替换返回尾
		$coutw=str_replace("&lt;","<",$coutw);//将符号换回来
		$coutw=str_replace("&gt;",">",$coutw);//将符号换回来
			
		// echo $coutw;
	 return $coutw;
	}
	catch (SoapFault $fault){
		return  $fault->faultcode;
	}
}