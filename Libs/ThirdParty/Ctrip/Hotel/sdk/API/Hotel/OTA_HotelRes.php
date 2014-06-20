<?php
/**
 *
 * 订单生成
 * @author liuw2
 *
 */
class set_OTA_HotelRes{
	/**
	 * 订几间房 -不可为空
	 */
	var $NumberOfUnits="";
	/**
	 * 价格计划代码-不可为空
	 */
	var $RatePlanCode="";
	/**
	 *  酒店ID-不可为空
	 */
	var $HotelCode="";
	/**
	 * 入住人最早到店时间
	 */
	var $ArrivalTime="";
	/**
	 * 最晚入住时间
	 */
	var $LateArrivalTime="";
	/**
	 * 入住人姓名
	 */
	var $PersonName="";
	/**
	 * 联系人方式
	 */
	var $ContactType="";
	/**
	 * 联系人姓
	 */
	var $ContactPersonSurname="";
	
	/**
	 *  联系电话
	 */
	var $PhoneNumber="";
	/**
	 * 电话类型
	 */
	var $PhoneTechType="";
	/**
	 *  Email
	 */
	var $Email="";
	/**
	 * 客人数量是否对应每间房
	 */
	var $IsPerRoom="";
	/**
	 *  客人数量
	 */
	var $GuestCount="";
	/**
	 *  入住开始时间
	 */
	var $StartTimeSpan="";
	/**
	 *  入住结束时间
	 */
	var $EndTimeSpan="";
	/**
	 * 特殊请求
	 */
	var $SpecialRequest="";
	/**
	 *  磁卡类型
	 */
	var $CardType="";
	/**
	 *  信用卡号
	 */
	var $CarNumber="";
	/**
	 *  串号
	 */
	var $SeriesCode="";
	/**
	 *  生效日期
	 */
	var $EffectiveDate="";
	

	/**
	 *  持卡人姓名
	 */
	var $CardHolderName="";
	/**
	 *  持卡人身份证号
	 */
	var $CardHolderIDCard="";
	
	
	/**
	 *  担保制度支付类型，例如信用卡担保、押金担保等。
	 */
	var $GuaranteeType="";
	/**
	 *  接收到的担保金额
	 */
	var $AmountPercent="";
	/**
	 *  税后订单总价
	 */
	var $AmountBeforeTax="";
	/**
	 *  货币单位
	 */
	var $CurrencyCode="";
	/**
	 *  开始时间
	 */
	var $CancelPenaltyStart="";
	/**
	 *  结束时间
	 */
	var $CancelPenaltyEnd="";
	/**
	 *  取消罚金
	 */
	var $AmountPercentAmount="";
	
	/**
	 *返回体
	 */
	var $ResponseXML="";
	/**
	 * 构造请求体
	 */
	private  function getRequestXML()
	{
		/*
		 * 从config.php中获取系统的联盟信息(只读)
		 */
		$AllianceID=Allianceid;
		$SID=Sid;
		$KEYS=SiteKey;
		$RequestType="OTA_HotelRes";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);
		$gettime=getDateYMD('-')."T00:00:00.000+08:00";
		
		//担保类型，现在只有信用卡担保
		if($this->GuaranteeType=='CC/DC/Voucher'){
			$DepositPayments=<<<BEGIN
				<ns:DepositPayments>
					<ns:GuaranteePayment GuaranteeType="$this->GuaranteeType">
						<ns:AcceptedPayments>
							<ns:AcceptedPayment>
								<ns:PaymentCard CardType="$this->CardType" CardNumber="$this->CardNumber" SeriesCode="$this->SeriesCode" EffectiveDate="$this->EffectiveDate" ExpireDate="$this->ExpireDate" >
									<ns:CardHolderName>$this->CardHolderName</ns:CardHolderName>
									<ns:CardHolderIDCard>$this->CardHolderIDCard</ns:CardHolderIDCard>
								</ns:PaymentCard>
							</ns:AcceptedPayment>
						</ns:AcceptedPayments>
						<ns:AmountPercent Amount="$this->AmountPercent"/>
					</ns:GuaranteePayment>
				</ns:DepositPayments>
BEGIN;

		}
		
		
	if($this->Email){
			$Emails=<<<BEGIN
				<ns:Email>$this->Email</ns:Email>
BEGIN;
		}
		
		
		if(empty($this->PhoneTechType))$this->PhoneTechType='1';
		if(empty($this->IsPerRoom))$this->IsPerRoom='true';
		if(empty($this->CurrencyCode))$this->CurrencyCode='CNY';
		
		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
  <Header $headerRight/>
  
  
<HotelRequest>
		<RequestBody xmlns:ns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
			<ns:OTA_HotelResRQ TimeStamp="$gettime" Version="1.0">
				
			$this->UniqueID
				
				
				<ns:HotelReservations>
					<ns:HotelReservation>
						<ns:RoomStays>
							<ns:RoomStay>
								<ns:RoomTypes>
									<ns:RoomType NumberOfUnits="$this->NumberOfUnits"/>
								</ns:RoomTypes>
								<ns:RatePlans>
									<ns:RatePlan RatePlanCode ="$this->RatePlanCode"/>
								</ns:RatePlans>
								<ns:BasicPropertyInfo HotelCode="$this->HotelCode"/>
							</ns:RoomStay>
						</ns:RoomStays>
						<ns:ResGuests>
							<ns:ResGuest ArrivalTime="$this->ArrivalTime">
								<ns:Profiles>
									<ns:ProfileInfo>
										<ns:Profile>
											<ns:Customer>
												<ns:PersonName>
													<ns:Surname>$this->PersonName</ns:Surname>
												</ns:PersonName>
												<ns:ContactPerson ContactType="$this->ContactType">
													<ns:PersonName>
														<ns:Surname>$this->ContactPersonSurname</ns:Surname>
													</ns:PersonName>
													<ns:Telephone PhoneNumber="$this->PhoneNumber" PhoneTechType="$this->PhoneTechType"/>
													$Emails
												</ns:ContactPerson>
											</ns:Customer>
										</ns:Profile>
									</ns:ProfileInfo>
								</ns:Profiles>
								<ns:TPA_Extensions>
									<ns:LateArrivalTime>$this->LateArrivalTime</ns:LateArrivalTime>
								</ns:TPA_Extensions>
							</ns:ResGuest>
						</ns:ResGuests>
						<ns:ResGlobalInfo>
							<ns:GuestCounts IsPerRoom="$this->IsPerRoom">
								<ns:GuestCount Count="$this->GuestCount"/>
							</ns:GuestCounts>
							<ns:TimeSpan Start="$this->StartTimeSpan" End="$this->EndTimeSpan"/>
							<ns:SpecialRequests>
								<ns:SpecialRequest>
									<ns:Text>$this->SpecialRequest</ns:Text>
								</ns:SpecialRequest>
							</ns:SpecialRequests>
							
							$DepositPayments
							
							<ns:Total AmountBeforeTax="$this->AmountBeforeTax" CurrencyCode="$this->CurrencyCode"/>
							<ns:CancelPenalties>
								<ns:CancelPenalty Start="$this->CancelPenaltyStart" End="$this->CancelPenaltyEnd">
									<ns:AmountPercent Amount="$this->AmountPercentAmount"/>
								</ns:CancelPenalty>
							</ns:CancelPenalties>
						</ns:ResGlobalInfo>
					</ns:HotelReservation>
				</ns:HotelReservations>
			</ns:OTA_HotelResRQ>
		</RequestBody>
	</HotelRequest>
  
  
  
</Request>
BEGIN;
//215    238   186
		return  $paravalues;
	}

	/**
	 *
	 * 调用接口
	 */
	function main(){
		try{
			$requestXML=$this->getRequestXML();
			$commonRequestDo=new commonRequest();//常用数据请求
		 	$commonRequestDo->requestURL=OTA_HotelRes_Url;
		 	$commonRequestDo->requestXML=$requestXML;
		 	$commonRequestDo->requestType=System_RequestType;//取config中的配置
		 	$commonRequestDo->doRequest();
	 	
	 		$this->ResponseXML=getXMLFromReturnString($commonRequestDo->responseXML);
		}
		catch(Exception $e)
		{
			$this->ResponseXML=null;
		}
	}
}
 