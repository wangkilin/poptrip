<?php
/**
 * 支持对象：酒店API2.0 作者：携程-技术中心-分销联盟-唐春龙 2012-08-02
 * 将接口返回的字符串型的XML转换为XML返回给用户
 * @param $ReturnString 输入的字符串型XML
 */
function getXMLFromReturnString($ReturnString)
{
	$coutw=null;
	//echo json_encode($ReturnString);
	if(json_encode($ReturnString)!="null"&&!empty($ReturnString))
	{
	   //echo  $ReturnString;
		$dom=new DOMDocument('1.0','UTF-8');
		$dom->loadXML(trim($ReturnString));
		$xml = simplexml_import_dom($dom);
		//判断当前的接口是否调用成功
		$hearder=$xml->Header;
		//echo $xml;
		if(!empty($hearder)){
			$resultCode=$hearder["ResultCode"];
			if($resultCode=="Success"){
				//调用成功
				$coutw=$xml;
			}
			else
			{
				$coutw=$xml->Header["ResultMsg"];//null;
			}
		}
	}
	 // echo json_encode($coutw);
	return $coutw;
}
?>