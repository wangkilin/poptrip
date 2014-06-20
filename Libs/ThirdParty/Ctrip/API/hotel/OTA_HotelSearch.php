<?php

/* PHP SDK
 * @version 2.0.0
 * @author magicsky0@163.com
 * @copyright © 2014, Ctrip Corporation. All rights reserved.
 */

class OTA_HotelSearch
{
	public $open_api = '/Hotel/OTA_HotelSearch.asmx';
	
	// 酒店信息查询条件：查询属性中至少有一条查询条件 @var $hotel_city_code $area_id $hotel_name
	public $city_code = ''; 	// 城市ID
	public $area_id = ''; 			// 行政区ID
	public $hotel_name = ''; 		// 酒店名称(模糊查询)
	
	public $indicator = "true"; 		// 国内酒店：true/false 可预订/已激活; 海外酒店：true/false 过滤booking和agoda/不过滤
	public $position_type = ''; 		// 坐标类型,参见ListCode(PTC),501Mapbar 坐标,502Google 坐标
	public $provider = 'HotelStarRate'; // 评分者,HotelStarRate(酒店星级),CtripStarRate(携程星级),CtripRecommendRate(携程评分)
	public $rating = ''; 				// 评分分数或级别
	
	public function __construct( $open_api, $args )
	{
		if( array_key_exists('indicator',$args) && $args['indicator'])
		{
			$this->indicator = $args['indicator'];
		}		
		if( array_key_exists('city_code',$args) && $args['city_code'])
		{
			$this->city_code = $args['city_code'];
		}
		if( array_key_exists('area_id',$args) && $args['area_id'])
		{
			$this->area_id = $args['area_id'];
		}
		if( array_key_exists('hotel_name',$args) && $args['hotel_name'])
		{
			$this->hotel_name = $args['hotel_name'];
		}
		if( !$this->city_code && !$this->area_id && !$this->hotel_name )
		{
			trigger_error('城市ID,行政区ID和酒店名称不可都为空',E_USER_ERROR);
		}
		if( array_key_exists('position_type',$args) && $args['position_type'] )
		{
			$this->position_type = $args['position_type'];
		}	
		if( array_key_exists('provider',$args) && $args['provider'])
		{
			$this->provider = $args['provider'];
		}
		if( array_key_exists('rating',$args) && $args['rating'])
		{
			$this->rating = $args['rating'];
		}
		if( !$this->provider && !$this->rating )
		{
			trigger_error('评分标准和评分级别不可都为空',E_USER_ERROR);
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
					.'<ns:OTA_HotelSearchRQ Version="1.0" PrimaryLangID="zh" '
					.'xsi:schemaLocation="http://www.opentravel.org/OTA/2003/05 OTA_HotelSearchRQ.xsd" '
					.'xmlns="http://www.opentravel.org/OTA/2003/05">'
						.'<ns:Criteria AvailableOnlyIndicator="'.$this->indicator.'">'
							.'<ns:Criterion>'
								.'<ns:HotelRef %s />'
								.'%s'
								.'<ns:Award %s/>'
							.'</ns:Criterion>'
						.'</ns:Criteria>'
					.'</ns:OTA_HotelSearchRQ>'
				.'</RequestBody>'
			.'</HotelRequest>'
			.'</Request>';
		
		// 酒店信息查询条件
		$city_code = $this->city_code ? (' HotelCityCode="'.$this->city_code.'"') : '';
		$area_id = $this->area_id ? (' AreaID="'.$this->area_id.'"') : '';
		$hotel_name = $this->hotel_name ? (' HotelName="'.$this->hotel_name.'"') : '';
		$nsHotelRef = $city_code.$area_id.$hotel_name;
		
		// 酒店坐标类型
		$position_type = !empty($this->position_type)?'<ns:Position PositionTypeCode="'.$this->position_type.'"/>':'';
				
		// 酒店等级查询条件
		$nsAward = ($this->provider&&$this->rating) ? ('Provider="'.$this->provider.'" Rating="'.$this->rating.'"') : '';
		
		// 模板替换
		$request_xml = sprintf($request_xml,$nsHotelRef,$position_type,$nsAward);
		
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
