<?php

/* PHP SDK
 * @version 2.0.0
 * @author magicsky0@163.com
 * @copyright © 2014, Ctrip Corporation. All rights reserved.
 */

class OTA_HotelRes
{
	public $open_api = '/Hotel/OTA_HotelRes.asmx';
	
	public $hotel_code;
	public $rateplan_code;
	public $time_stmp;
	public $unique_id; 	// 联盟用户在携程的uniqueid
		
	public $number_of_units = 1; 	// 预订房间数
	public $arrival_time = '10:00:00+08:00'; 	// 入住人最早到店时间: e.g. 10:00:00+08:00
	public $late_arrival_time = ''; 			// 入住人最早最晚时间: e.g. 10:00:00+08:00
	
	public $surname = array(); 		// 入住人姓名列表	
	public $contact_type = 'tel'; 	// 确认联系人方式:sms/email/fax/non 手机短消息/电邮/传真/无需确认
	public $contact_name;
	public $phone_number;
	public $phone_tech_type = 1;
	public $contact_email;
	
	public $start_stmp;
	public $end_stmp;
	public $count; 					// 客人数量
	public $per_room = 'false'; 	// 客人数量是否对应每间房，false表示所有房间加起来一共住这么多客人	
	public $special_text = ''; 		// 客人特殊要求
	
	public $amount_before_tax; 			// 税前订单总价
	public $amount_currency = 'CNY'; 	// 货币单位
	
	public $guarante_code = FALSE; 			// 担保标记:1:峰时担保，2全额担保，3超时担保，4一律担保，5手机担保
	public $guarantee_type = 'CC/DC/Voucher'; // 信用卡担保类型（暂时只支持此类型）
	public $card_type; 			// 磁卡类型，参考CodeList(CCT)
	public $card_number; 		// 信用卡号
	public $series_code; 		// 串号信用卡背后的3位数字
	public $effective_date; 	// 生效日期
	public $expire_date; 		// 失效日期
	public $card_holder_name; 	// 持卡人姓名
	public $card_holder_idcard; // 持卡人身份证
		
	public $guarante_amount; 			// 担保金额
	public $guarante_currency = 'CNY'; 	// 货币单位
	
	public $cancel_penalty_amount; 				// 取消罚金金额
	public $cancel_penalty_currency = 'CNY'; 	// 取消罚金单位
	public $cancel_penalty_start;
	public $cancel_penalty_end;
	
	public function __construct( $open_api, $args )
	{
		$this->hotel_code = $args['hotel_code'];
		$this->rateplan_code = $args['rateplan_code'];
		$this->unique_id = $args['unique_id']; 
		$this->time_stmp = date(DATE_ATOM);
		if( array_key_exists('guarante_code', $args) && $args['guarante_code'] )
		{
			$this->guarante_code = $args['guarante_code'];
		}
		
		// ----------------------------------------------------------------------------
		if( array_key_exists('number_of_units', $args) && $args['number_of_units'] )
		{
			$this->number_of_units = $args['number_of_units'];
		}
		if( array_key_exists('arrival_time', $args) && $args['arrival_time'] )
		{
			// 获取原子钟时间T后面数据
			list($t,$this->arrival_time) = explode( 'T', date(DATE_ATOM, strtotime($args['arrival_time'])) );
		}
		
		// ----------------------------------------------------------------------------
		$this->surname = explode(',',$args['surname']);
		if( array_key_exists('contact_type', $args) && $args['contact_type'] )
		{
			$this->contact_type = $args['contact_type'];
		} 
		$this->contact_name = $args['contact_name']; 
		$this->phone_number = $args['phone_number']; 
		if( array_key_exists('phone_tech_type', $args) && $args['phone_tech_type'] )
		{
			$this->phone_tech_type = $args['phone_tech_type'];
		}
		$this->contact_email = $args['contact_email'];
		if( array_key_exists('per_room', $args) && $args['per_room'] )
		{
			$this->per_room = $args['per_room'];
		} 
		$this->start_stmp = date( DATE_ATOM, strtotime($args['start_stmp']) );
		$this->end_stmp = date( DATE_ATOM, strtotime($args['end_stmp']) );
		$this->late_arrival_time = date( DATE_ATOM, strtotime($args['late_arrival_time']) );
		$this->count = $args['count'];
		if( array_key_exists('special_text', $args) && $args['special_text'] )
		{
			$this->special_text = $args['special_text'];
		}
	
		// ----------------------------------------------------------------------------
		$this->amount_before_tax = $args['amount_before_tax']; 
		if( array_key_exists('amount_currency', $args) && $args['amount_currency'] )
		{
			$this->amount_currency = $args['amount_currency'];
		}

		// ----------------------------------------------------------------------------
		// guarante_code为1，2，3，4时为现付担保，必须填写信用卡信息和担保信息
		if( $this->guarante_code >= 1 && $this->guarante_code <= 4 )
		{
			// 读取json授权文件
			$token = json_decode(file_get_contents(CU_TOKEN_PATH),TRUE);
			$key = substr($token['key'],-8);

			$this->card_type = des_encode($args['card_type'],$key); 
			$this->card_number = des_encode($args['card_number'],$key); 
			$this->series_code = des_encode($args['series_code'],$key); 
			$this->effective_date = des_encode($args['effective_date'],$key); 
			$this->expire_date = des_encode($args['expire_date'],$key); 
			$this->card_holder_name = des_encode($args['card_holder_name'],$key); 
			$this->card_holder_idcard = des_encode($args['card_holder_idcard'],$key); 
			
			$this->guarante_amount = $args['guarante_amount']; 
			$this->cancel_penalty_amount = $this->guarante_amount*$this->count; 
			if( array_key_exists('guarante_currency', $args) && $args['guarante_currency'] )
			{
				$this->cancel_penalty_currency = $this->guarante_currency = $args['guarante_currency'];
			}
			$this->cancel_penalty_start = date( DATE_ATOM, strtotime($args['cancel_penalty_start']) );
			$this->cancel_penalty_end = date( DATE_ATOM, strtotime($args['cancel_penalty_end']) );					
		}

		$this->open_api = $open_api.$this->open_api;
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
					.'<ns:OTA_HotelResRQ TimeStamp="'.$this->time_stmp.'" Version="1.0">'
						.'<ns:UniqueID Type="504" ID="100000"/>'
						.'<ns:UniqueID Type="28" ID="'.$uid.'"/>'
						.'<ns:UniqueID Type="503" ID="'.$sid.'"/>'
						.'<ns:UniqueID Type="1" ID="'.$this->unique_id.'"/>'
						.'<ns:HotelReservations>'
							.'<ns:HotelReservation>'
								.'<ns:RoomStays>'
									.'<ns:RoomStay>'
										.'<ns:RoomTypes><ns:RoomType NumberOfUnits="'.$this->number_of_units.'"/></ns:RoomTypes>'
										.'<ns:RatePlans><ns:RatePlan RatePlanCode="'.$this->rateplan_code.'"/></ns:RatePlans>'
										.'<ns:BasicPropertyInfo HotelCode="'.$this->hotel_code.'"/>'
									.'</ns:RoomStay>'
								.'</ns:RoomStays>'
								.'<ns:ResGuests>'
									.'<ns:ResGuest ArrivalTime="'.$this->arrival_time.'">'
										.'<ns:Profiles>'
											.'<ns:ProfileInfo>'
												.'<ns:Profile>'
												.'<ns:Customer>'
													.'%s' // $surname
													.'<ns:ContactPerson ContactType="'.$this->contact_type.'">'
														.'<ns:PersonName>'
															.'<ns:Surname>'.$this->contact_name.'</ns:Surname>'
														.'</ns:PersonName>'
														.'<ns:Telephone PhoneNumber="'.$this->phone_number.'" '
														.'PhoneTechType="'.$this->phone_tech_type.'"/>'
														.'<ns:Email>'.$this->contact_email.'</ns:Email>'
													.'</ns:ContactPerson>'
												.'</ns:Customer>'
												.'</ns:Profile>'
											.'</ns:ProfileInfo>'
										.'</ns:Profiles>'
										.'<ns:TPA_Extensions>'
											.'<ns:LateArrivalTime>'.$this->late_arrival_time.'</ns:LateArrivalTime>'
										.'</ns:TPA_Extensions>'
									.'</ns:ResGuest>'
								.'</ns:ResGuests>'
								.'<ns:ResGlobalInfo>'
									.'<ns:GuestCounts IsPerRoom="'.$this->per_room.'">'
										.'<ns:GuestCount Count="'.$this->count.'"/>'
									.'</ns:GuestCounts>'
									.'<ns:TimeSpan Start="'.$this->start_stmp.'" End="'.$this->end_stmp.'"/>'
									.'<ns:SpecialRequests>'
										.'<ns:SpecialRequest><ns:Text>'.$this->special_text.'</ns:Text></ns:SpecialRequest>'
									.'</ns:SpecialRequests>'
									.'%s' // $guarante
								.'</ns:ResGlobalInfo>'
							.'</ns:HotelReservation>'
						.'</ns:HotelReservations>'
					.'</ns:OTA_HotelResRQ>'
				.'</RequestBody>'
			.'</HotelRequest>'
			.'</Request>';

		$surname = '';
		foreach($this->surname as $v)
		{
			$surname .= '<ns:PersonName><ns:Surname>'.$v.'</ns:Surname></ns:PersonName>';
		}
		
		// Notice: 根据酒店的担保政策决定DepositPayments和CancelPenalties字段必需
		$guarante = '';
		if( $this->guarante_code >= 1 && $this->guarante_code <= 4 ) 
		{
			$guarante = 
				'<ns:DepositPayments>'
					.'<ns:GuaranteePayment GuaranteeType="'.$this->guarantee_type.'">'
						.'<ns:AcceptedPayments>'
							.'<ns:AcceptedPayment>'
								.'<ns:PaymentCard CardType="'.$this->card_type.'" '
								.'CardNumber="'.$this->card_number.'" '
								.'SeriesCode="'.$this->series_code.'" '
								.'EffectiveDate="'.$this->effective_date.'" '
								.'ExpireDate="'.$this->expire_date.'">'
									.'<ns:CardHolderName>'.$this->card_holder_name.'</ns:CardHolderName>'
									.'<ns:CardHolderIDCard>'.$this->card_holder_idcard.'</ns:CardHolderIDCard>'
								.'</ns:PaymentCard>'
							.'</ns:AcceptedPayment>'
						.'</ns:AcceptedPayments>'
						.'<ns:AmountPercent Amount="'.$this->guarante_amount.'" '
						.'CurrencyCode="'.$this->guarante_currency.'"/>'
					.'</ns:GuaranteePayment>'
				.'</ns:DepositPayments>'
				.'<ns:Total AmountBeforeTax="'.$this->amount_before_tax.'" '
				.'CurrencyCode="'.$this->amount_currency.'"/>'
				.'<ns:CancelPenalties>'
					.'<ns:CancelPenalty Start="'.$this->cancel_penalty_start.'" End="'.$this->cancel_penalty_end.'">'
						.'<ns:AmountPercent Amount="'.$this->cancel_penalty_amount.'" '
						.'CurrencyCode="'.$this->cancel_penalty_currency.'"/>'
					.'</ns:CancelPenalty>'
				.'</ns:CancelPenalties>';
		}
		else 
		{
			$guarante = '<ns:Total AmountBeforeTax="'.$this->amount_before_tax.'" '
						.'CurrencyCode="'.$this->guarante_currency.'"/>';
		}
		
		$request_xml = sprintf($request_xml,$surname,$guarante);	
		
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
