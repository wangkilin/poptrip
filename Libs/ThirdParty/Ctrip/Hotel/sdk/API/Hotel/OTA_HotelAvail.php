<?php
/**
 *
 * 酒店可订性检查接口
 * @author liuw2
 *
 */
class set_OTA_HotelAvail{
	/**
	 * 客人数量，必须填写
	 */
	var $GuestCount="";
	/**
	 * 客人数量是否对应每间房 -可为空
	 */
	var $IsPerRoom="";
	/**
	 * 房间数量
	 */
	var $RoomCount="";
	/**
	 * 最晚入住时间
	 */
	var $LastCheckInTime="";
	/**
	 * 离店时间
	 */
	var $CheckOutTime="";
	/**
	 * 入住时间
	 */
	var $CheckInTime="";
	/**
	 * 子房型ID
	 */
	var $HotelRoomCode="";
	/**
	 *  酒店ID
	 */
	var $HotelCode="";
	/**
	 *  时间戳
	 */
	var $TimeStamp="";
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
		$RequestType="OTA_HotelAvail";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);
		$gettime=getDateYMD('-')."T00:00:00.000+08:00";
		if($this->IsPerRoom){
			$IsPerRooms=<<<BEGIN
			IsPerRoom="$this->IsPerRoom"
BEGIN;

		}
		
		
		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
  <Header $headerRight/>
  <HotelRequest>
    <RequestBody xmlns:ns="http://www.opentravel.org/OTA/2003/05" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    	<ns:OTA_HotelAvailRQ Version="1.0" TimeStamp="$gettime">
				<ns:AvailRequestSegments>
					<ns:AvailRequestSegment>
						<ns:HotelSearchCriteria>
							<ns:Criterion>
								<ns:HotelRef HotelCode="$this->HotelCode"/>
								<ns:StayDateRange Start="$this->CheckInTime" End="$this->CheckOutTime"/>
								<ns:RatePlanCandidates>
									<ns:RatePlanCandidate RatePlanCode="$this->HotelRoomCode"/>
								</ns:RatePlanCandidates>
								<ns:RoomStayCandidates>
									<ns:RoomStayCandidate Quantity="$this->RoomCount">
	            						<ns:GuestCounts  $IsPerRooms>
	            							<ns:GuestCount Count="$this->GuestCount"/>
										</ns:GuestCounts>
									</ns:RoomStayCandidate>
								</ns:RoomStayCandidates>
								<ns:TPA_Extensions>
									<ns:LateArrivalTime>$this->LastCheckInTime</ns:LateArrivalTime>
								</ns:TPA_Extensions>
							</ns:Criterion>
						</ns:HotelSearchCriteria>
					</ns:AvailRequestSegment>
				</ns:AvailRequestSegments>
			</ns:OTA_HotelAvailRQ>
     </RequestBody>
  </HotelRequest>
</Request>
BEGIN;
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
		 	$commonRequestDo->requestURL=OTA_HotelAvail_Url;
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
 