<?php

/* PHP SDK
 * @version 2.0.0
 * @author magicsky0@163.com
 * @copyright © 2014, Ctrip Corporation. All rights reserved.
 */

class OTA_HotelRatePlan
{
	public $open_api = '/Hotel/OTA_HotelRatePlan.asmx';
	
	public $hotel_code = array(); // 两种格式：621为hote_code下所有rate_plan房型,621/178646为何otel_code下指定rate_plan的房型
	public $date_start;
	public $date_end;
	public $time_stmp;
	public $only_ind = 'true'; // 只读取可用价格计划, true/false 能预订/已激活但可能不能预订
	// 注释：有些价格计划（子房型）仅用来对某些渠道开放预订，对普通终端用户不可用
	public $display_ind = 'false'; // 限制类型是否查询预付计划，如果为true则此价格计划（子房型）对普通终端用户不可用
		
	public function __construct( $open_api, $args )
	{
		$this->hotel_code = explode(',',$args['hotel_code']);
		$this->date_start = $args['date_start'];
		$this->date_end = $args['date_end'];
		$this->time_stmp = date(DATE_ATOM); // 原子钟格式：2014-02-26T00:00:00.000+08:00;

		if( !$this->date_start || !$this->date_end )
		{
			trigger_error('起止时间不能为空',E_USER_ERROR);
		}
		
		if( array_key_exists('only_ind', $args) && $args['only_ind'] )
		{
			$this->only_ind = $args['only_ind'];
		}
		if( array_key_exists('display_ind', $args) && $args['display_ind'] )
		{
			$this->display_ind = $args['display_ind'];
		}
		
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
			.'<HotelRequest>'
				.'<RequestBody xmlns:ns="http://www.opentravel.org/OTA/2003/05" '
				.'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
				.'xmlns:xsd="http://www.w3.org/2001/XMLSchema">'
					.'<ns:OTA_HotelRatePlanRQ TimeStamp="'.$this->time_stmp.'" Version="1.0">'
						.'<ns:RatePlans>%s</ns:RatePlans>'
					.'</ns:OTA_HotelRatePlanRQ>'
				.'</RequestBody>'
			.'</HotelRequest>'
			.'</Request>';
			
		$nsRatePlans = '';
		foreach($this->hotel_code as $v)
		{
			$tmp = explode('/',$v);
			if( count($tmp) == 2 )
			{
				list($hotel_code,$rateplan_code) = $tmp;
			}
			else 
			{
				$hotel_code = $tmp[0];
				$rateplan_code = '';
			}
			 
			if( $rateplan_code )
			{
				$rateplan_code = 'RatePlanCode="'.$rateplan_code.'"';
			}
			$tmp =	'<ns:RatePlan>'
						.'<ns:DateRange Start="'.$this->date_start.'" End="'.$this->date_end.'"/>'
						.'<ns:RatePlanCandidates>'
							.'<ns:RatePlanCandidate AvailRatesOnlyInd="'.$this->only_ind.'" '.$rateplan_code.'>'
								.'<ns:HotelRefs><ns:HotelRef HotelCode="'.$hotel_code.'"/></ns:HotelRefs>'
							.'</ns:RatePlanCandidate>'
						.'</ns:RatePlanCandidates>'
						.'<ns:TPA_Extensions RestrictedDisplayIndicator="'.$this->display_ind.'"/>'
					.'</ns:RatePlan>';
			$nsRatePlans .= $tmp;
		}
		$request_xml = sprintf($request_xml,$nsRatePlans);
		
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
