<?php

/* PHP SDK
 * @version 2.0.0
 * @author magicsky0@163.com
 * @copyright © 2014, Ctrip Corporation. All rights reserved.
 */

class OTA_HotelDescriptiveInfo
{
	public $open_api = '/Hotel/OTA_HotelDescriptiveInfo.asmx';
	
	public $hotel_code = array();
	public $position_type = 502;
	public $hotel_info = 'true';
	public $facility_info = 'true';
	public $area_info_att = 'true';
	public $area_info_rec = 'true';
	public $contact_info = 'true';
	public $multimedia = 'true';
	
	public function __construct( $open_api, $args )
	{
		$this->hotel_code = explode(',',$args['hotel_code']);
		if( array_key_exists('position_type', $args) && $args['position_type'] )
		{
			$this->position_type = $args['position_type'];
		}
		if( array_key_exists('hotel_info', $args) && $args['hotel_info'] )
		{
			$this->hotel_info = $args['hotel_info'];
		}
		if( array_key_exists('facility_info', $args) && $args['facility_info'] )
		{
			$this->facility_info = $args['facility_info'];
		}
		if( array_key_exists('area_info_att', $args) && $args['area_info_att'] )
		{
			$this->area_info_att = $args['area_info_att'];
		}
		if( array_key_exists('area_info_rec', $args) && $args['area_info_rec'] )
		{
			$this->area_info_rec = $args['area_info_rec'];
		}
		if( array_key_exists('contact_info', $args) && $args['contact_info'] )
		{
			$this->contact_info = $args['contact_info'];
		}
		if( array_key_exists('multimedia', $args) && $args['multimedia'] )
		{
			$this->multimedia = $args['multimedia'];
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
				.'<OTA_HotelDescriptiveInfoRQ Version="1.0" '
				.'xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelDescriptiveInfoRQ.xsd" '
				.'xmlns="http://www.opentravel.org/OTA/2003/05" '
				.'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">'
					.'<HotelDescriptiveInfos>%s</HotelDescriptiveInfos>'
				.'</OTA_HotelDescriptiveInfoRQ>'
			.'</Request>';

		$HotelDescriptiveInfo = '';
		foreach($this->hotel_code as $v)
		{
			$tmp =	'<HotelDescriptiveInfo HotelCode="'.$v.'" PositionTypeCode="'.$this->position_type.'">'
			 			.'<HotelInfo SendData="'.$this->hotel_info.'"/>'
			 			.'<FacilityInfo SendGuestRooms="'.$this->facility_info.'"/>'
			 			.'<AreaInfo SendAttractions="'.$this->area_info_att.'" SendRecreations="'.$this->area_info_rec.'"/>'
						.'<ContactInfo SendData="'.$this->contact_info.'"/>'
						.'<MultimediaObjects SendData="'.$this->multimedia.'"/>'
					.'</HotelDescriptiveInfo>';
			$HotelDescriptiveInfo .= $tmp;
		}
		$request_xml = sprintf($request_xml,$HotelDescriptiveInfo);
		
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
