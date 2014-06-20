<?php
/**
 * 请求D_HotelSearch_IDList的服务(请求体与D_HotelSearch  一样，根据不同的接口返回不同的数据)
 */
class get_D_HotelSearch_IDList{
	/**
	 * @var城市的ID，必须填写
	 */
	var $CityID="";
	/**
	 * @var入住时间，必须填写
	 */
	var $CheckInDate="";
	/**
	 * @var离店时间，必须填写
	 */
	var $CheckOutDate="";
	/**
	 *@var 酒店的名称-供模糊查询
	 */
	var $HotelName="";
	/**
	 *@var 请求条数，最大100 ，必须填写
	 */
	var $PageSize="";
	/**
	 * @var请求的页码，必须填写
	 */
	var $PageNumber="";
	/**
	 * @var酒店星级列表
	 */
	var $StarList="";
	/**
	 *@var 酒店的品牌ID
	 */
	var $HotelBrand="";
	/**
	 * @var排序字段,默认携程推荐
	 */
	var $OrderName="";
	/**
	 * @var升降顺序，ASC/DESC
	 */
	var $OrderType="";
	/**
	 *@var最低价
	 */
	var $LowPrice="";
	/**
	 *@var最高价格
	 */
	var $HighPrice="";
	/**
	 *@var行政区
	 */
	var $Location="";
	/**
	 *@var商业区
	 */
	var $Zone="";
	/**
	 *@var景区
	 */
	var $District="";
	/**
	 *
	 *@var 酒店的设施（AirportShuttle，BroadNet...格式）
	 */
	var $HotelFacility="";
	/**
	 *
	 * @var 支持多个酒店ID的查询（123,342,4425）
	 */
	var $HotelList="";
	/**
	 *
	 * @var 支付类型：预付-PP，现付-FG
	 * @var string
	 */
	var $PriceType="FG";
	/**
	 * @var 点选的纬度
	 * @var double
	 */
	var $DotX=0;
	/**
	 * @var 点选的经度
	 * @var double
	 */
	var $DotY=0;
	/**
	 * @var 点选的半径
	 * @var double
	 */
	var $Radius=0;
	/**
	 *@var返回体
	 */
	var $ResponseXML="";

	/**
	 *@var 构造请求体
	 */
	private  function getRequestXML()
	{
		/*
		 * 从config.php中获取系统的联盟信息(只读)
		 */
		$AllianceID=Allianceid;
		$SID=Sid;
		$KEYS=SiteKey;
		$RequestType="D_HotelSearch_IDList";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);
		$city="";
		if($this->CityID!=""){
			$city=<<<BEGIN
<CityID>$this->CityID</CityID>
BEGIN;
		}
		//构造坐标的查询条件
		$DotXs="";
		if($this->DotX!=0){
			$DotXs=<<<BEGIN
<DotX>$this->DotX</DotX>
BEGIN;
		}
		$DotYs="";
		if($this->DotY!=0){
			$DotYs=<<<BEGIN
<DotY>$this->DotY</DotY>
BEGIN;
			}
				$Radiuss="";
				if($this->Radius!=0){
					$Radiuss=<<<BEGIN
<Radius>$this->Radius</Radius>
BEGIN;
				}
				$HotelMaps="";//坐标的请求节点
				if($DotXs!=""&&$DotYs!=""&&$Radiuss!="")
				{
					$HotelMaps="<HotelMap>$DotXs$DotYs$Radiuss</HotelMap>";
				}
				
				//构造坐标的查询条件
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
				$hotelNames="";
				if($this->HotelName!=""){
					$hotelNames=<<<BEGIN
<HotelName>$this->HotelName</HotelName>
BEGIN;
				}
				$PriceTypes="";
				if($this->PriceType!=""){
					$PriceTypes=<<<BEGIN
<PriceType>$this->PriceType</PriceType>
BEGIN;
				}


				$pagesizes="";
				if($this->PageSize!=""){
					$pagesizes=<<<BEGIN
<PageSize>$this->PageSize</PageSize>
BEGIN;
				}
				$pagenumbers="";
				if($this->PageNumber!=""){
					$pagenumbers=<<<BEGIN
<PageNumber>$this->PageNumber</PageNumber>
BEGIN;
				}
				$HotelLists="";
				if($this->HotelList!=""){
					$HotelLists=<<<BEGIN
<HotelList>$this->HotelList</HotelList>
BEGIN;
				}
				$starlists="";
				if($this->StarList!=""){
					$starlists=<<<BEGIN
<StarList>$this->StarList</StarList>
BEGIN;
				}
				//用酒店的品牌作为关键字，提供给酒店名称，做模糊查询，实现一个品牌名称，查询出多个子品牌的数据
				$hotelbrands="";
				if($this->HotelBrand!=""){
					$hotelbrands=<<<BEGIN
<HotelBrand>$this->HotelBrand</HotelBrand>
BEGIN;
				}
				$ordernames="";
				if($this->OrderName!=""){
					$ordernames=<<<BEGIN
<OrderName>$this->OrderName</OrderName>
BEGIN;
				}
				$ordertypes="";
				if($this->OrderType!=""){
					$ordertypes=<<<BEGIN
<OrderType>$this->OrderType</OrderType>
BEGIN;
				}
				$lowprices="";
				if($this->LowPrice!=""){
					$lowprices=<<<BEGIN
<LowPrice>$this->LowPrice</LowPrice>
BEGIN;
				}
				$highprices="";
				if($this->HighPrice!=""){
					$highprices=<<<BEGIN
<HighPrice>$this->HighPrice</HighPrice>
BEGIN;
				}
				$locations="";
				if($this->Location!=""){
					$locations=<<<BEGIN
<Location>$this->Location</Location>
BEGIN;
				}
				$zones="";
				if($this->Zone!=""){
					$zones=<<<BEGIN
<Zone>$this->Zone</Zone>
BEGIN;
				}
				$Districts="";
				if($this->District!=""){
					$Districts=<<<BEGIN
<District>$this->District</District>
BEGIN;
				}

				$HotelFacilitys="";
				if($this->HotelFacility!="")
				{
					if(strpos($this->HotelFacility,",")>0)
					{
						//如果有多个则要切割
						$arrayFacility=explode(",",$this->HotelFacility);
						for($i=0;$i<count($arrayFacility);$i++)
						{
							if($arrayFacility[$i]!=""&&$arrayFacility[$i]!=null)
							{
								$HotelFacilitys=$HotelFacilitys."<".$arrayFacility[$i].">T</".$arrayFacility[$i].">";
							}
						}
					}
					else
					{
						$HotelFacilitys="<".$this->HotelFacility.">T</".$this->HotelFacility.">";
					}
					
					if($HotelFacilitys!="")//如果有设备，则前后加上设备标签
					{
					  $HotelFacilitys="<HotelFacility>".$HotelFacilitys."</HotelFacility>";
					}
				}

				$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
<Header $headerRight/>
<DomesticHotelListRequest>$city$checkIn$checkOut$hotelNames$pagesizes$pagenumbers$starlists$hotelbrands$ordernames$ordertypes$lowprices$highprices$locations$zones$Districts$HotelFacilitys$HotelLists$PriceTypes$HotelMaps</DomesticHotelListRequest>
</Request>
BEGIN;

				return  $paravalues;
			}
			/**
			 *
			 * 调用直接查询酒店列表的接口，获取到酒店的数据
			 */
			function main(){
				try{
					$requestXML=$this->getRequestXML();
					$commonRequestDo=new commonRequest();//常用数据请求
					$commonRequestDo->requestURL=D_HotelSearch_IDList_Url;
					$commonRequestDo->requestXML=$requestXML;
					$commonRequestDo->requestType=System_RequestType;//取config中的配置
					$commonRequestDo->doRequest();
					$returnXML=$commonRequestDo->responseXML;
					
					//调用Common/RequestDomXml.php中函数解析返回的XML
					$this->ResponseXML=getXMLFromReturnString($returnXML);
				}
				catch(Exception $e)
				{
					$this->ResponseXML=null;
				}
			}
		
		}
		?>