<?php

/* PHP SDK
 * @version 2.0.0
 * @author magicsky0@163.com
 * @copyright © 2014, Ctrip Corporation. All rights reserved.
 */

class D_SearchDepositAgentAccountDetail
{
	public $open_api = '/Hotel/D_SearchDepositAgentAccountDetail.asmx';
	
	public $order_ids;
	public $s_arrival; // 入住开始时间
	public $e_arrival; // 入住开始时间
	public $s_input_time; // 创建开始时间
	public $e_input_time; // 创建结束时间
	
	public function __construct( $open_api, $args )
	{
		$this->order_ids = explode(',',$args['order_ids']);
		$this->s_arrival = $args['s_arrival'];
		$this->e_arrival = $args['e_arrival'];
		$this->s_input_time = $args['s_input_time'];
		$this->e_input_time = $args['e_input_time'];
		
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
			.'<Header AllianceID="'.$uid.'" SID="'.$sid.'" TimeStamp="'.$stmp.'" RequestType="'.$type.'" Signature="'.$sign.'" />'
			.'<SearchDepositAgentAccountDetailRequest>'
				.'<OrderIds>%s</OrderIds>'
				.'<SArrival>'.$this->s_input_time.'</SArrival>'
				.'<EArrival>'.$this->e_arrival.'</EArrival>'
				.'<SInputTime>'.$this->s_input_time.'</SInputTime>'
				.'<EInputTime>'.$this->e_input_time.'</EInputTime>'
				.'<DuctType>noshow</DuctType>'
				.'<IsEffected>T</IsEffected>'
			.'</SearchDepositAgentAccountDetailRequest>'
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
