<?php
/**
 *
 * @author cltang 2012-6-28 本系统中统一处理数据请求的类：httpRequest请求或者SOAP请求
 *
 */
class commonRequest
{
	//System_RequestType--在config.php文件中配置的请求模式常量
	/**
	 *
	 * @var string  * 请求的服务地址，不带上?WSDL
	 */
	var $requestURL=null;
	/**
	 *
	 * @var string  * 请求的服务类型，是httpRequest/soap
	 */
	var $requestType=null;//在config.php文件中配置的请求模式常量
	/**
	 *
	 * @var string 请求体（如果是SOAP请求，不用带上SOAP头信息）
	 */
	var $requestXML=null;
	/**
	 *
	 * @var string 返回体数据，如果返回结果是空，则返回null
	 */
	var $responseXML=null;

	/**
	 *
	 * 根据不同的请求类型，做SOAP获取HTTP请求
	 */
	public function doRequest(){
		$responseXmlTemp="";//保存临时的返回结果
		if($this->requestType==$this->getHttpTypeName())
		{
			//如果是http请求，则去找httpRequestData.php中的方法
			$responseXmlTemp=httpRequestSoapData($this->requestURL,$this->requestXML);
		}
		else if($this->requestType==$this->getSoapTypeName())
		{
			//echo "2";
			$parameters=array("requestXML"=>$this->requestXML);
			$responseXmlTemp=getDataFromSoap($this->requestURL."?WSDL","Request",$parameters);
			$responseXmlTemp=$responseXmlTemp->RequestResult;
		}
		else {
			//echo "3";
			$responseXmlTemp=null;
		}
		$this->responseXML=$responseXmlTemp;
	}
	/**
	 *
	 * @var string 返回soap的名称
	 */
	public function getSoapTypeName()
	{
		return "soap";
	}
	/**
	 *
	 * @var string 返回httpRequest的名称
	 */
	public function getHttpTypeName()
	{
		return "httpRequest";
	}
}
?>
