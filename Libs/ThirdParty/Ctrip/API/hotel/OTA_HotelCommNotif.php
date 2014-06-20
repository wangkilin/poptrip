<?php

/* PHP SDK
 * @version 2.0.0
 * @author magicsky0@163.com
 * @copyright © 2014, Ctrip Corporation. All rights reserved.
 */

class OTA_HotelCommNotif
{
	public $open_api = '/Hotel/OTA_HotelCommNotif.asmx';
	
	public $time_stmp;
	public $hotel_code;
	public $audit_id; // 结算标识(参看D_GetNoShowOrders 中的FGID)
	public $reservation; // 订单ID(参看D_GetNoShowOrders中的OrderID)
	public $room_type; // 房型编号(参看D_GetNoShowOrders中的Room)
	public $room_inventory; // 房间号
	public $status; // 回传状态(I:入住 N:NoShow)
	public $surname = ''; // 入住人名
	public $description = '';
	
	public function __construct( $open_api, $args )
	{
		$this->hotel_code = $args['hotel_code'];
		$this->audit_id = $args['audit_id'];
		$this->reservation = $args['reservation'];
		$this->room_type = $args['room_type'];
		$this->room_inventory = $args['room_inventory'];
		$this->status = $args['status'];
		if( array_key_exists('surname', $args) && $args['surname'] )
		{
			$this->surname = $args['surname'];
		}
		if( array_key_exists('description', $args) && $args['description'] )
		{
			$this->description = $args['description'];
		}
		$this->time_stmp = date(DATE_ATOM);
		
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
			.'<HotelRequest>'
				.'<RequestBody xmlns:ns="http://www.opentravel.org/OTA/2003/05" '
				.'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
				.'xmlns:xsd="http://www.w3.org/2001/XMLSchema">'
					.'<ns:OTA_HotelCommNotifRQ TimeStamp="'.$this->time_stmp.'" Version="1.0">'
						.'<ns:CommissionEvents>'
							.'<ns:CommissionEvent AuditID="'.$this->audit_id.'" ReservationID="'.$this->reservation.'" '
							.'RoomTypeCode="'.$this->room_type.'" RoomInventoryCode="'.$this->room_inventory.'" '
							.'StatusCode="'.$this->status.'">'
								.'<ns:GuestNames>'
									.'<ns:GuestName>'
										.'<ns:Surname>'.$this->surname.'</ns:Surname>'
									.'</ns:GuestName>'
								.'</ns:GuestNames>'
								.'<ns:HotelReference HotelCode="'.$this->hotel_code.'"/>'
								.'<ns:Description>'.$this->description.'</ns:Description>'
							.'</ns:CommissionEvent>'
						.'</ns:CommissionEvents>'
					.'</ns:OTA_HotelCommNotifRQ>'
				.'</RequestBody>'
			.'</HotelRequest>'
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
