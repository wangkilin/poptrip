<?php
/**
 * 2012年5月14日 携程 唐春龙 研发中心
 * 通过SOAP调用远程webservice服务（返回一个XML）
 * @param $url 远程服务的地址
 * @param $parameters 远程服务的参数数组
 * @param $funcName 远程服务的函数的名称
 * @param 返回XML
 */
function getDataFromSoap($url,$funcName,$parameters)
{
	//$parameters是服务中函数的变量名与值之间的对应数组
	//调用指定的URL
	$soap=new SoapClient($url);
	try{
		$coutw=$soap->$funcName($parameters);
		return  $coutw;
	}
	catch (SoapFault $fault){
		//发生异常时输出
		return  $fault->faultcode;
	}
}
?>