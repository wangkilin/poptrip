<?php

/* PHP SDK
 * @version 2.0.0
 * @author magicsky0@163.com
 * @copyright © 2014, Ctrip Corporation. All rights reserved.
 */

class OTA_HotelCacheChange
{
	public $open_api = '/Hotel/OTA_HotelCacheChange.asmx';
	
	public $hotel_code;
	public $city_code;
	public $time_stmp;
	
	public function __construct( $open_api, $args )
	{
		$this->city_code = $args['city_code'];
		$this->hotel_code = $args['hotel_code'];
		$this->time_stmp = date(DATE_ATOM); // 原子钟格式：2014-02-26T00:00:00.000+08:00;
		
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
					.'<ns:OTA_HotelCacheChangeRQ Version="1.0">'
						.'<ns:CacheSearchCriteria CacheFromTimestamp="'.$this->time_stmp.'">'
							.'<ns:CacheSearchCriterion HotelCityCode="'.$this->city_code.'" %s/>'
						.'</ns:CacheSearchCriteria>'
					.'</ns:OTA_HotelCacheChangeRQ>'
				.'</RequestBody>'
			.'</HotelRequest>'
			.'</Request>';
		if($this->hotel_code) 
		{
			$request_xml = sprintf($request_xml,'HotelCode="'.$this->hotel_code.'"');
		}
		$request_xml = sprintf($request_xml,'');

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
