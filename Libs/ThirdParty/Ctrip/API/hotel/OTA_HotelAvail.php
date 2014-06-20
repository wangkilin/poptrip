<?php

/* PHP SDK
 * @version 2.0.0
 * @author magicsky0@163.com
 * @copyright © 2014, Ctrip Corporation. All rights reserved.
 */

class OTA_HotelAvail
{
	public $open_api = '/Hotel/OTA_HotelAvail.asmx';
	
	public $hotel_code;
	public $time_stmp;
	public $rateplan_code;
	public $start_stmp;
	public $end_stmp;
	public $arrival_stmp;
	public $quantity; // 房间数量
	public $count; // 客人数量
	public $per_room = 'true'; // 客人数量是否对应每间房，false表示所有房间加起来一共住这么多客人
	
	public function __construct( $open_api, $args )
	{
		$this->hotel_code = $args['hotel_code'];
		$this->rateplan_code = $args['rateplan_code'];
		$this->quantity = $args['quantity'];
		$this->count = $args['count'];
		if( array_key_exists('per_room', $args) && $args['per_room'] )
		{
			$this->per_room = $args['per_room'];
		}
		// DATE_ATOM为原子钟格式：2014-02-26T00:00:00.000+08:00;
		$this->time_stmp = date(DATE_ATOM); 
		$this->start_stmp = date( DATE_ATOM, strtotime($args['start_stmp']) );
		$this->end_stmp = date( DATE_ATOM, strtotime($args['end_stmp']) );
		$this->arrival_stmp = date( DATE_ATOM, strtotime($args['arrival_stmp']) );

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
					.'<ns:OTA_HotelAvailRQ TimeStamp="'.$this->time_stmp.'" Version="1.0">'
						.'<ns:AvailRequestSegments>'
							.'<ns:AvailRequestSegment>'
								.'<ns:HotelSearchCriteria>'
								.'<ns:Criterion>'
									.'<ns:HotelRef HotelCode="'.$this->hotel_code.'"/>'
									.'<ns:StayDateRange Start="'.$this->start_stmp.'" End="'.$this->end_stmp.'"/>'
									.'<ns:RatePlanCandidates>'
										.'<ns:RatePlanCandidate RatePlanCode="'.$this->rateplan_code.'"/>'
									.'</ns:RatePlanCandidates>'
									.'<ns:RoomStayCandidates>'
										.'<ns:RoomStayCandidate Quantity="'.$this->quantity.'">'
											.'<ns:GuestCounts IsPerRoom="'.$this->per_room.'">'
												.'<ns:GuestCount Count="'.$this->count.'"/>'
											.'</ns:GuestCounts>'
										.'</ns:RoomStayCandidate>'
									.'</ns:RoomStayCandidates>'
									.'<ns:TPA_Extensions>'
										.'<ns:LateArrivalTime>'.$this->arrival_stmp.'</ns:LateArrivalTime>'
									.'</ns:TPA_Extensions>'
								.'</ns:Criterion>'
								.'</ns:HotelSearchCriteria>'
							.'</ns:AvailRequestSegment>'
						.'</ns:AvailRequestSegments>'
					.'</ns:OTA_HotelAvailRQ>'
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
