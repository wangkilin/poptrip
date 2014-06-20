<?php
/**
 *
 * 获取酒店的详细信息
 * @author cltang
 *
 */
class get_D_HotelDetail{
	/**
	 * 城市的ID，必须填写
	 */
	var $CityID="";
	/**
	 * 入住时间，必须填写
	 */
	var $CheckInDate="";
	/**
	 * 离店时间，必须填写
	 */
	var $CheckOutDate="";
	/**
	 * 酒店的ID
	 */
	var $HotelID="";
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
		$RequestType="D_HotelDetail";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);
		$city="";
		if($this->CityID!=""){
			$city=<<<BEGIN
<CityID>$this->CityID</CityID>
BEGIN;
		}
		$checkIn="";
		if($this->CheckInDate!=""){
			$checkIn=<<<BEGIN
<CheckInDate>$this->CheckInDate</CheckInDate>
BEGIN;
		}
		$checkOut="";
		if($this->CheckOutDate!=""){
			$checkOut=<<<BEGIN
<CheckOutDate>$this->CheckOutDate</CheckOutDate>
BEGIN;
		}
		$hotelIDs="";
		if($this->HotelID!=""){
			$hotelIDs=<<<BEGIN
<HotelID>$this->HotelID</HotelID>
BEGIN;
		}

		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
<Header $headerRight/>
<DomesticHotelProductDetailRequest>$city$checkIn$checkOut$hotelIDs</DomesticHotelProductDetailRequest>
</Request>
BEGIN;
		return  $paravalues;
	}
	/**
	 *
	 * 调用直接查询酒店详情的接口，获取到酒店的数据
	 */
	function main(){
		try{
	 	$requestXML=$this->getRequestXML();
	 	$commonRequestDo=new commonRequest();//常用数据请求
	 	$commonRequestDo->requestURL=D_HotelDetail_Url;
	 	$commonRequestDo->requestXML=$requestXML;
	 	$commonRequestDo->requestType=System_RequestType;//取config中的配置
	 	$commonRequestDo->doRequest();
	 	$returnXML=$commonRequestDo->responseXML;
	 	$this->ResponseXML=getXMLFromReturnString($returnXML);//$returnXML->RequestResult;
		//echo json_encode($this->ResponseXML);
		}
		catch(Exception $e)
		{
			$this->ResponseXML=null;
		}
	}
	/**
	 *
	 * 获取到酒店的子房型的列表
	 */
	function getSubRoomList($hotelDetailInfo)
	{
		$nbspTemp="&nbsp;&nbsp;&nbsp;&nbsp;";
		$HotelBaseRoomList=$hotelDetailInfo->BaseRoomList;//酒店的子房型
		if($HotelBaseRoomList!=null)
		{
			$baseRoomCount = 0;
			foreach ($HotelBaseRoomList->DomesticHotelBaseRoom as $BaseRoom)
			{
				if($baseRoomCount == "0")
				{
					$baseRoomCount = $baseRoomCount + 1;
				}
				if($baseRoomCount>0)
				{

					if(strlen($BaseRoom->RoomName>0))
					{
						echo $BaseRoom->RoomName;
					}
					else
					{
						if($BaseRoom->SubRooms!=null)
						{
							$BaseRoomSubtTemp=$BaseRoom->SubRooms;
						}
						//如果房型的名称为空，则采集子房型的名称
						foreach($BaseRoomSubtTemp->DomesticHotelBaseSubRoom as $subRoomsTemp)
						{
							echo $subRoomsTemp->RoomName;
							break;
						}
					}

					if($BaseRoom->SubRooms!=null)
					{
						$BaseRoomSub=$BaseRoom->SubRooms;
						$Arrival="";//达到时间
						$CalculateType="";//送券计算方式:固定值，卖价百分比
						$CalculateValue="";//送券计算方式:固定值，卖价百分比 卷的价值
						$Departure="";//离店日期
						$EndDate="";//活动结束日期
						$StartDate="";//活动开始日期
						$TicketType="";//送券类型(限额抵用券 = L,非限额抵用券 = U,机渡非限额抵用券 = F,酒店游票任我住 = R,酒店游票任我游 = T,酒店游票任我行 = S,需要消费券的返现 = C,不需要消费券的返现 =D)
						$GiftsName="";//活动名称
						$subRoomsCount = 0;
						foreach($BaseRoomSub->DomesticHotelBaseSubRoom as $subRooms)
						{
							$subRoomsCount = $subRoomsCount + 1;
							$giftslist=$subRooms->RoomTicketGiftsList->DomesticHotelRoomTicketGifts;
							$gifts=$giftslist->RoomTicketGiftsDetailList->DomesticHotelRoomTicketGiftsDetail;
							$GiftsName=$giftslist->RoomTicketGiftsName;
							$Arrival=$gifts->Arrival;
							$CalculateType=$gifts->CalculateType;
							$CalculateValue=$gifts->CalculateValue;
							$Departure=$gifts->Departure;
							$EndDate=$gifts->EndDate;
							$StartDate=$gifts->StartDate;
							$TicketType=$gifts->TicketType;

							echo $subRooms->RoomName;
							echo getGifValue($StartDate,$EndDate,$CalculateValue).$nbspTemp;
							$bedTypeName="";//床型
							if($subRooms->BedType=="")
							{
								if($subRooms->HasSingleBed=="T")
								{
									$bedTypeName="单床";
								}
								else
								{
									$bedTypeName="无床";
								}
							}
							else
							{
								$bedTypeName=getBedTypeName($subRooms->BedType);
							}
							if($bedTypeName=="无床")
							{
								//再找一次DomesticHotelBaseSubRoom 中的床型
								if($subRooms->TwinBed=="T"){
									$bedTypeName="双床";
								}
								else {
									$bedTypeName="大床";
								}
							}
							echo $bedTypeName.$nbspTemp;
							echo getBreakFastName($subRooms->HasBreakfast).$nbspTemp;
							echo  getWireName($subRooms->HasWirelessBroadnet,$subRooms->HasWiredBroadnet).$nbspTemp;
							echo currencyTransition($subRooms->Currency).$nbspTemp;
							echo isDouble($subRooms->AveragePrice).$nbspTemp;
							$subroomPic_url="";//房间的图片
							if($subRooms->RoomEffectPicList!=null&&$subRooms->RoomEffectPicList!="")
							{
								if($subRooms->RoomEffectPicList->DomesticHotelEffectPic[0]!=null)
								{
									$subroomPic_url=$subRooms->RoomEffectPicList->DomesticHotelEffectPic[0]->HotelPic175URL;
									echo $BaseRoom->RoomName.$nbspTemp."<br/>";
									echo "<img width=\"100\" height=\"75\" src=\"$subroomPic_url\" />".$nbspTemp;
								}
							}
							echo "<br/>";
							echo " 建筑面积：".$subRooms->RoomArea."平方米&nbsp;&nbsp;&nbsp;&nbsp;";
							echo "楼层：".$subRooms->FloorRange."层&nbsp;&nbsp;&nbsp;&nbsp;";
							if($subRooms->HasKingBed=="T"&&$subRooms->KingBedWidth!="")
							{
								echo "床宽：大床".$subRooms->KingBedWidth."米&nbsp;&nbsp;&nbsp;&nbsp;";
							}
							if($subRooms->HasTwinBed=="T"&&$subRooms->TwinBedWidth!="")
							{
								echo " 床宽：双床".$subRooms->TwinBedWidth."米&nbsp;&nbsp;&nbsp;&nbsp;";
							}
							if($subRooms->AddBed=="T"){
								echo "可加床：".currencyTransition("RMB");
								echo isDouble($subRooms->AddBedFee)."&nbsp;&nbsp;&nbsp;&nbsp;";
							}
							else
							{
								echo "不可加床：&nbsp;&nbsp;&nbsp;&nbsp;";
							}
							echo "可入住人数:".$subRooms->Person."人<br />";
							if($subRooms->HasRoomInNonSmokeArea=="T")
							{
								echo "无烟房：该房型可安排无烟楼层".$nbspTemp;
							}
							if($subRooms->HasNonSmokeRoom=="T")
							{
								echo "无烟房：该房型有无烟房".$nbspTemp;
							}
							if($subRooms->HasSmokeCleanRoom=="T")
							{
								echo "该房可无烟处理".$nbspTemp;
							}
							echo  "宽带：".getWireName($subRooms->HasWirelessBroadnet,$subRooms->HasWiredBroadnet).$nbspTemp;
							echo $subRooms->RoomDsc;
							echo "<br/>-----------------------------------------------------------------------------<br/>";
							
						}
					}
					$baseRoomCount = $baseRoomCount + 1;
				}
			}
		}
	}
}