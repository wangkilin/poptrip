<?php

/* PHP SDK
 * @version 2.0.0
 * @author magicsky0@163.com
 * @copyright © 2014, Ctrip Corporation. All rights reserved.
 */

class D_HotelSubRoomList
{
	public $open_api = '/Hotel/D_HotelSubRoomList.asmx';
	
public $city_id;
	public $hotel_list = '';  // such as: 100643639,100643640
	public $check_in;
	public $check_out;
	public $page_size = 20;
	public $page_number = 1;
	public $star_list = '5,4,3,2,1,0,-1';
	public $order_name = 'Star';
	public $order_type = 'DESC';
	public $price_type = 'FG';
	
	public function __construct( $open_api, $args )
	{
		$this->city_id = $args['city_id'];
		$this->check_in = $args['check_in'];
		$this->check_out = $args['check_out'];
		if( array_key_exists('hotel_list', $args) && isset($args['hotel_list']) ) 
		{
			$this->hotel_list = $args['hotel_list'];
		}
		if( array_key_exists('page_size', $args) && isset($args['page_size']) ) 
		{
			$this->page_size = $args['page_size'];
		}
		if( array_key_exists('page_number', $args) && isset($args['page_number']) ) 
		{
			$this->page_number = $args['page_number'];
		}
		if( array_key_exists('star_list', $args) && isset($args['star_list']) ) 
		{
			$this->star_list = $args['star_list'];
		}
		if( array_key_exists('order_name', $args) && isset($args['order_name']) ) 
		{
			$this->order_name = $args['order_name'];
		}
		if( array_key_exists('order_type', $args) && isset($args['order_type']) ) 
		{
			$this->order_type = $args['order_type'];
		}
		if( array_key_exists('price_type', $args) && isset($args['price_type']) ) 
		{
			$this->price_type = $args['price_type'];
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
			.'<DomesticHotelListRequest>'
			.'<CityID>'.$this->city_id.'</CityID>'
			.'<HotelList>'.$this->hotel_list.'</HotelList>'
			.'<CheckInDate>'.$this->check_in.'</CheckInDate>'
			.'<CheckOutDate>'.$this->check_out.'</CheckOutDate>'
			.'<PageSize>'.$this->page_size.'</PageSize>'
			.'<PageNumber>'.$this->page_number.'</PageNumber>'
			.'<StarList>'.$this->star_list.'</StarList>'
			.'<OrderName>'.$this->order_name.'</OrderName>'
			.'<OrderType>'.$this->order_type.'</OrderType>'
			.'<PriceType>'.$this->price_type.'</PriceType>'
			.'</DomesticHotelListRequest>'
			.'</Request>';
		
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
