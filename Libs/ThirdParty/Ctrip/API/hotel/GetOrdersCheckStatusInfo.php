<?php

/* PHP SDK
 * @version 2.0.0
 * @author magicsky0@163.com
 * @copyright © 2014, Ctrip Corporation. All rights reserved.
 */

class GetOrdersCheckStatusInfo
{
	public $open_api = '/Hotel/GetOrdersCheckStatusInfo.asmx';
	
	public $order_ids; // such as: 100643639,100643640
	
	public function __construct( $open_api, $args )
	{
		$this->order_ids = explode(',',$args['order_ids']);
		
		$this->open_api = $open_api.$this->open_api; // TODO:检测open api，如果不合法则覆盖重写
	}
	
	/**
	 * 构造请求xml字符串
	 * @param int $uid
	 * @param int $sid
	 * @param string $stmp
	 * @param string $sign
	 * @param stirng $type
	 */
	public function request_xml( $uid, $sid, $stmp, $sign, $type )
	{
		$request_xml = 
			'<?xml version="1.0" encoding="utf-8"?>'
			.'<Request>'
			.'<Header AllianceID="'.$uid.'" SID="'.$sid.'" TimeStamp="'.$stmp.'" RequestType="'.$type.'" Signature="'.$sign.'"/>'
			.'<GetOrdersCheckStatusInfoRequest>'
				.'<OrderIds>%s</OrderIds>'
			.'</GetOrdersCheckStatusInfoRequest>'
			.'</Request>';
		$orderIds = '';
		foreach($this->order_ids as $v)	
		{
			$orderIds .= "<int>$v</int>";
		}	
		$request_xml = sprintf($request_xml,$orderIds);
		
		// 需要将此处的xml嵌入到外层xml中，故需要将其转义
		$request_xml = str_replace("<",@"&lt;",$request_xml);
		$request_xml = str_replace(">",@"&gt;",$request_xml);
		
		return $request_xml;
	}
	
	public function respond_xml( $string )
	{
		// 将内层xmll中转义的符号恢复
		$string = str_replace("&lt;","<",$string);
		$string = str_replace("&gt;",">",$string);

		return simplexml_load_string($string);	
	}
}
