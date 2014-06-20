<?php
/**
 * 处理hotelSearch.php和hoteldetail.php中的所有的逻辑
 */
class page_hotelSearch{
	/**
	 *
	 * @var 城市ID
	 * @var 字符串
	 */
	var $cityid="";
	/**
	*
	* @var 城市名称
	* @var 字符串
	*/
	var $CityName="";
	/**
	*
	* @var 入住日期2012-08-01
	* @var 字符串
	*/
	var $CheckInDate="";
	/**
	*
	* @var 离店日期2012-08-05
	* @var 字符串
	*/
	var $CheckOutDate="";
	/**
	*
	* @var 酒店星级
	* @var 字符串
	*/
	var $Star="";
	/**
	*
	* @var 价格区间
	* @var 字符串
	*/
	var $Price="";
	/**
	*
	* @var 酒店名称关键字
	* @var 字符串
	*/
	var $HotelName="";
	/**
	*
	* @var 酒店详细中，酒店的ID
	* @var 字符串
	*/
	var $HotelDetailId="";
	/**
	*
	* @var 酒店位置（街道/地标/建筑物）-要处理成location 和 zone和景区district
	* @var 字符串
	*/
	var $locationZone="";
	/**
	*
	* @var 酒店位置（街道/地标/建筑物）在页面上显示的名称
	* @var 字符串
	*/
	var $locationZoneDistrictShowText="";
	/**
	 *
	 * @var 酒店的品牌--本系统中传递品牌的名称，提供给hotelname用
	 * @var 字符串
	 */
	var $hotelbrand="";
	/**
	*
	* @var 排序的名称
	* @var 字符串
	*/
	var $OrderName="";
	/**
	*
	* @var 排序的类型 DESC.ASC
	* @var 字符串
	*/
	var $OrderType="";
	/**
	*
	* @var 当前第几页
	* @var 数字型
	*/
	var $PageNumber=1;
	/**
	*
	* @var 每页显示多少数据
	* @var 数字型
	*/
	var $PageSize=10;
	/**
	*
	* @var 酒店设施
	* @var 字符串
	*/
	var $HotelFacility="";
	/**
	*
	* @var 返回的数据
	* @var 字符串
	*/
	var $returnXML="";
	/**
	*
	* @var 最低价格
	* @var 数字型
	*/
	var $lowPrice=0;
	/**
	*
	* @var 最高价格
	* @var 数字型
	*/
	var $highPrice=9999999;
	/**
	*
	* @var 行政区
	* @var 字符串
	*/
	var $location="";
	/**
	*
	* @var 行政区名称
	* @var 字符串
	*/
	var $locationName="";
	/**
	*
	* @var 商业区
	* @var 字符串
	*/
	var $zone="";
	/**
	*
	* @var 商业区名称
	* @var 字符串
	*/
	var $zoneName="";
	/**
	* @var 景点坐标（32.00:34.00）用“:”隔开经纬度
	* @var 字符串
	*/
	var $district="";
	/**
	 * @var 景点名称
	 * @var 字符串
	 */
	var $districtName="";
	/**
	 *
	 * @var 返回的酒店列表HTML数据
	 * @var 字符串
	 */
	var $responseHotelListHtml="";
	/**
	*
	* @var 外部设置，是否要做URL伪静态
	* @var 字符串
	*/
	var $isSiteUrlRewriter="";
	/**
	*
	* @var 外部设置，本系统的域名
	* @var 字符串
	*/
	var $thisUnionSite_domainName="";
	/**
	* @var 酒店列表中，默认的酒店图片地址
	*/
	var $SiteHotelDefaultImageUrlHotelSearch="";
	/**
	 *
	 * @var 酒店详情中获取的酒店的ID
	 * @var 字符串
	 */
	var $hotelID="";
	/**
	 *
	 * @var 酒店详情中获取的酒店的名称
	 * @var 字符串
	 */
	var $hotelDetailName="";
	/**
	 *
	 * @var 酒店详情中获取的商业区
	 * @var 字符串
	 */
	var $hotelDetailZone="";
	/**
	*
	* @var 返回：符合条件的酒店总数
	* @var 数字型
	*/
	var $responseTotalNum=0;
	/**
	 * @var 酒店详细中，酒店名称地址照片一块的HTML
	 * @varHTML
	 */
	var $hotelDetail_TitleAddress="";
	/**
	 *
	 * @var 单个酒店的起价
	 * @var 数字型
	 */
	var $hotelDetailPrice=0;
	/**
	 *
	 * @var 单个酒店的起价的货币单位
	 * @var 字符串
	 */
	var $hotelDetailCurrencyMinPrice="";
	/**
	 *
	 * @var 单个酒店的评分信息
	 * @var 字符串
	 */
	var $hotelDetailJudge="";
	/**
	 *
	 * @var 单个酒店的卫生+服务+环境+设施评分
	 * @var 字符串
	 */
	var $hotelDetailPointBasefix="";
	/**
	 *
	 * @var 单个酒店的图片总数
	 * @var 字符串
	 */
	var $hotelDetailTotalImageNum="";
	/**
	 *
	 * @var 单个酒店的子房型列表
	 * @var 字符串
	 */
	var $hotelDetailSubRoomList="";
	/**
	 *
	 * @var 单个酒店的酒店简介
	 * @var 字符串
	 */
	var $hotelDetailHotelDesc="";
	/**
	 *
	 * @var 单个酒店的支持信用卡图标
	 * @var 字符串
	 */
	var $hotelDetailCreditCardInfoList="";
	/**
	 *
	 * @var 单个酒店的宾馆服务项目
	 * @var 字符串
	 */
	var $hotelDetailFacilityAndHotelListType1="";
	/**
	 *
	 * @var 单个酒店的宾馆餐饮设施
	 * @var 字符串
	 */
	var $hotelDetailFacilityAndHotelListType2="";
	/**
	 *
	 * @var 单个酒店的宾馆娱乐与健身设施
	 * @var 字符串
	 */
	var $hotelDetailFacilityAndHotelListType3="";
	/**
	 *
	 * @var 单个酒店的客房设施和服务
	 * @var 字符串
	 */
	var $hotelDetailFacilityAndHotelListType4="";
	/**
	 *
	 * @var 单个酒店的自助早餐的价格
	 * @var 字符串
	 */
	var $hotelDetailDiy_Breakfast="";
	/**
	 *
	 * @var 单个酒店的基本信息
	 * @var 字符串
	 */
	var $hotelDetailBaseInfoShow="";
	/**
	 *
	 * @var 单个酒店的周边信息
	 * @var 字符串
	 */
	var $hotelDetailSurroundings="";
	/**
	 *
	 * @var 单个酒店的酒店详细中，地标信息-火车站
	 * @var 字符串
	 */
	var $hotelDetailPlaceInfoList1="";
	/**
	 *
	 * @var 单个酒店的酒店详细中，地标信息-机场
	 * @var 字符串
	 */
	var $hotelDetailPlaceInfoList2="";
	/**
	 *
	 * @var 单个酒店的酒店详细中，地标信息-路名/地标建筑
	 * @var 字符串
	 */
	var $hotelDetailPlaceInfoList3="";
	/**
	 *
	 * @var 单个酒店的酒店详细中，地标信息-市中心
	 * @var 字符串
	 */
	var $hotelDetailPlaceInfoList4="";
	/**
	 *
	 * @var 单个酒店的酒店详细中，地图信息
	 * @var 字符串
	 */
	var $hotelDetailHotelMapPicUrl="";
	/**
	 *
	 * @var 单个酒店的酒店详细中，图片滚动显示
	 * @var 字符串
	 */
	var $hotelDetailImageListShow="";
	/**
	 *
	 * @var 单个酒店的酒店详细中,酒店客户点评中，头部概要点评
	 * @var 字符串
	 */
	var $hotelDetailCommentGeneral="";

	/**
	 *
	 * @var 设置参数的类型：$setParameterType=set,直接给参数赋值，$setParameterType=“” or $setParameterType=get 通过URL传值赋值
	 * @var unknown_type
	 */
	var $setParameterType="";
	/**
	 * @var 预订的链接地址:{hotelId},{roomId},{cityId},{checkInDate},{checkOutDate},{roomName}
	 * @var 字符串
	 */
	private $orderRoomUrl="http://u.ctrip.com/hotelcspsit/Redirect.html?hotelId={hotelId}&roomId={roomId}&cityId={cityId}&checkInDate={checkInDate}&checkOutDate={checkOutDate}";
	//private $orderRoomUrl="http://u.testu.sh.ctriptravel.com/hotelcspsit/Redirect.html?hotelId={hotelId}&roomId={roomId}&cityId={cityId}&checkInDate={checkInDate}&checkOutDate={checkOutDate}";
    /**
     * 
     * @var 订单查询的地址
     * @var 字符串
     */
	var $orderShowUrl="";
	 /**
     * 
     * @var 时间区间
     * @var 字符串
     */
	var $cdate="";
	 /**
     * 
     * @var 排序
     * @var 字符串
     */
	var $oy="";
	
	/**
	 *
	 * @var 设置请求的参数[从URL传值中获取]
	 */
	private function setRequsetParameter()
	{
		// var url=urlhost+"/site/hotelSearch.php?city="+cityid+","+cityname+"&cdate="+checkindate+","+checkoutdate+"&
		// stb="+star+";&price="+price+"&hname="+hotelname+"&lzod="+locationZone+"&hf=&oy=Recommend,DESC&pf=1,5";
		//先获取到URL传值过来
		//#site/hotelsearch.php 中 city=[cityid,cityname];cdate=[checkindate,checkoutdate];lzod=[行政区ID,行政区名称-商业区ID,商业区名称-景点坐标,景点名称];oy=[ordername,ordertype];pf=[pagenumber,pagesize];--用","隔开
		//#site/hotelsearch.php 中 stb=[star;hotelbrand] --用;隔开

		$p1=$_GET["city"];
		$p2=$this->cdate;
		$p3=$_GET["stb"];
		$p4=$this->price;
		$p5=$_GET["hname"];
		$p6=$_GET["lzod"];//行政区ID,行政区名称-商业区ID,商业区名称-景点坐标,景点名称
		$p7=$_GET["hf"];
		$p8=$this->oy;
		$p9=$_GET["pf"];

		//获取城市ID和城市名称
		if(strpos($p1,",")>=0){
			$arrayP1=explode(",",$p1);
			$this->cityid=$arrayP1[0];
			$this->CityName=$arrayP1[1];
			if(count($arrayP1)>2)
			{
				$this->hotelID=$arrayP1[2];
			}
		}
		//获取入店时间和离店时间
		if(strpos($p2,",")>=0){
			$arrayP2=explode(",",$p2);
			$this->CheckInDate=date("Y-m-d",strtotime($arrayP2[0]));
			$this->CheckOutDate=date("Y-m-d",strtotime($arrayP2[1]));
		}
		//获取星级和品牌
		if(strpos($p3,";")>=0){
			$arrayP3=explode(";",$p3);
			$this->Star=$arrayP3[0];
			$this->hotelbrand=$arrayP3[1];
		}
		$this->Price=$p4;
		$this->HotelName=trim($p5);
		$this->locationZone=$p6;
		$this->HotelFacility=$p7;//酒店设备是由设备名称用“，”拼接出来的
		
		if($this->hotelbrand!="")
		{
			//如果酒店的品牌不为空，则赋值给hotelname
			$this->HotelName=$this->hotelbrand;
		}
		
		
		
	//	echo $this->HotelFacility;die;
		
		//获取排序字段及排序类型
		if(strpos($p8,",")>=0){
			$arrayP8=explode(",",$p8);
			$this->OrderName=$arrayP8[0];
			$this->OrderType=$arrayP8[1];
		}
		//获取每页多少条记录，第几页
		if(strpos($p9,",")>=0){
			$arrayP9=explode(",",$p9);
			
			$this->PageNumber=$arrayP9[0];
			$this->PageSize=$arrayP9[1];
		}

		if($this->Price!=""&&strpos($this->Price,"-")>0)
		{
			$arrayPrice=explode("-",$this->Price);
			$this->lowPrice=$arrayPrice[0];
			$this->highPrice=$arrayPrice[1];
		}
		if($this->locationZone!=""&&strpos($this->locationZone,"-")>0)
		{
			$arrayLZ=explode("-",$this->locationZone);
			$locationArray=explode(",",$arrayLZ[0]);//行政区
			$zoneArray=explode(",",$arrayLZ[1]);//商业区
			$districtArray=explode(",",$arrayLZ[2]);//景区->改成景点

			$this->location=$locationArray[0];
			$this->locationName=$locationArray[1];
			$this->zone=$zoneArray[0];
			$this->zoneName=$zoneArray[1];
			$this->district=$districtArray[0];
			$this->districtName=$districtArray[1];

			//判断要显示那个名称
			if($arrayLZ[0]!=",")
			{
				$this->locationZoneDistrictShowText=$this->locationName;//行政区的名称
			}
			else if($arrayLZ[1]!=",")
			{
				$this->locationZoneDistrictShowText=$this->zoneName;//商业区的名称
			}
			else if($arrayLZ[2]!=",")
			{
				$this->locationZoneDistrictShowText=$this->districtName;//景区的名称
			}
			else{
				$this->locationZoneDistrictShowText="";//不显示名称
			}
		}
	}
	/**
	 *
	 * @var 获取酒店列表搜索的返回数据【URL传值模式】
	 */
	
	function getRequsetParameter()
	{
		$this->setRequsetParameter();
	}	
	
	function getHotelListResponseXML_URL()
	{
		//$this->setRequsetParameter();
		$D_HotelSearch=new get_D_HotelSearch();
		$D_HotelSearch->CheckInDate=$this->CheckInDate;//获取今天
		$D_HotelSearch->CheckOutDate=$this->CheckOutDate;//"2012-08-04";
		$D_HotelSearch->CityID=$this->cityid;
		$D_HotelSearch->PageNumber=$this->PageNumber;
		$D_HotelSearch->PageSize=$this->PageSize;
		$D_HotelSearch->HighPrice=$this->highPrice;
		$D_HotelSearch->LowPrice=$this->lowPrice;
		/*
		//$D_HotelSearch->HotelBrand=$this->hotelbrand;
		if($this->hotelbrand!="")
		{
			//如果酒店的品牌不为空，则赋值给hotelname
			$D_HotelSearch->HotelName=$this->hotelbrand;
			$this->HotelName=$this->hotelbrand;
		}else{
			$D_HotelSearch->HotelName=$this->HotelName;
		}
		*/
		$D_HotelSearch->HotelName=$this->HotelName;
		$D_HotelSearch->Location=$this->location;
		$D_HotelSearch->OrderName=$this->OrderName;
		$D_HotelSearch->OrderType=$this->OrderType;
		
		$D_HotelSearch->StarList=$this->Star;
     	if($this->Star=="2"){
		    $D_HotelSearch->StarList="2,1,0";//如果酒店的星级是2级，则取2级及以下
	    }
		$D_HotelSearch->Zone=$this->zone;
		//整站中支持景点的查询方式（用景点的坐标来处理）
		if($this->district!=""&&strpos($this->district,":")>0)
		{
			$lonLanArray=explode(":",$this->district);//坐标
			$D_HotelSearch->DotY=$lonLanArray[0];//经度
			$D_HotelSearch->DotX=$lonLanArray[1];//纬度
			$D_HotelSearch->Radius=5;//点选半径取5公里
		}
		//$D_HotelSearch->District=$this->district;
		//整站中支持景点的查询方式（用景点的坐标来处理）
		$D_HotelSearch->HotelFacility=$this->HotelFacility;
		$D_HotelSearch->main();
		$this->returnXML=$D_HotelSearch->ResponseXML;//返回的数据是一个XML

		$this->setResponse();//设置返回的数据
		$this->setSearchHotelList();//设置返回的酒店列表HTML
	}
	/**
	 *
	 *@var  获取酒店详细搜索的返回数据【URL传值模式】
	 */
	function getHotelDetailResponseXML_URL()
	{
		$this->getHotelDetailResponseXML();//获取酒店详细搜索的返回数据【URL传值模式】--仅仅获取返回数据，不做逻辑处理
		$this->setHotelDetail();//返回酒店详细的数据
	}
	/**
	 *
	 * @var  获取酒店详细搜索的返回数据【URL传值模式】--仅仅获取返回数据，不做逻辑处理
	 */
	public 	function getHotelDetailResponseXML()
	{
		if($this->setParameterType=="set")
		{}
		else{
			$this->setRequsetParameter();
		}
		$D_HotelDetail=new get_D_HotelDetail();
		$D_HotelDetail->CheckInDate=$this->CheckInDate;//获取今天
		$D_HotelDetail->CheckOutDate=$this->CheckOutDate;//"2012-08-04";
		$D_HotelDetail->CityID=$this->cityid;
		$D_HotelDetail->HotelID=$this->hotelID;
		$D_HotelDetail->main();
		
		
		$this->returnXML=$D_HotelDetail->ResponseXML;//返回的数据是一个XML
	}
	/**
	 *
	 * @var 设置返回的数据
	 * @param 酒店列表的XML $returnXML
	 */
	private function setResponse()
	{
		$returnXML=$this->returnXML;
		if(!empty($returnXML))
		{
						if($returnXML->DomesticHotelList->TotalItems!=""){
			$this->responseTotalNum=$returnXML->DomesticHotelList->TotalItems;//总数
						}
		}
	}
	/**
	 *@var 设置返回的酒店列表数据
	 */
	private function setSearchHotelList()
	{
		$returnXML=$this->returnXML;
		$hotelbox="";//放置返回的酒店数据
		$hotelboxList="";
		if(!empty($returnXML)&&$returnXML->DomesticHotelList->HotelDataList->DomesticHotelDataForList!=null){
			foreach ($returnXML->DomesticHotelList->HotelDataList->DomesticHotelDataForList as $v)
			{
				$briefInfo=$v->Brief;//小图标上显示提示内容
				$image550URL=removePicWaterMark((string)$v->HotelPic550URL);//酒店的主图
				if(empty($image550URL))
				{
					//设置默认的图片
					$image550URL=$this->SiteHotelDefaultImageUrlHotelSearch;
				}
				$hotelName=$v->HotelName;//酒店的名称
				$hotelID=$v->HotelID;//酒店的ID
				$CustomerEval=$v->CustomerEval;//砖石级别
				/*$CustomerEvalName="";//保存砖石级别的名称
				if(strpos($CustomerEval,".")>0)//有小数点
				{
					$arrayCE=explode(".",$CustomerEval);
					if($arrayCE[1]>0)
					{
						$customerleve=$arrayCE[0]+1;
						$CustomerEvalName="half_diamond0".$customerleve;
					}
					else {
						$CustomerEvalName="diamond0".$arrayCE[0];
					}
				}
				else {
					$CustomerEvalName="diamond0".$CustomerEval;
				}
				
				*/
				$Star=$v->Star;//国家星级平定
				$Rstar=$v->Rstar;//携程星级
				$StarInfo=get_star_info($Star,$Rstar);//
				$showTitle=$StarInfo['0'];
				$CustomerEvalName=$StarInfo['1'];
				
				
				$LocationName=$v->LocationName;//行政区名称
				$ZoneName=$v->ZoneName;//商业区1名称
				$ZoneName2=$v->ZoneName2;//商业区2名称
				$Address=$v->Address;//地址
				$RoadCross=$v->RoadCross;//交叉路口
				$zonespit="";
				if($ZoneName2!=""){$zonespit="&nbsp;";}
				$crossspit="";
				if($RoadCross!=""){$crossspit="&nbsp;";}
				
				if(strlen($ZoneName)=='0'  && strlen($ZoneName2)=='0'  && strlen($RoadCross)=='0' )
				$showAddress=$LocationName.$Address;
				else
				$showAddress=$LocationName.$Address."(".$ZoneName.$zonespit.$ZoneName2.$crossspit.$RoadCross.")";//构造地址
				$MinPrice=round((string)$v->MinPrice);//酒店的起价
				$CurrencyMinPrice=currencyTransition($v->CurrencyMinPrice,1);//币种
				$Rating=round("$v->Rating","1");//点评分
				if($Rating=="-1"){
					$Rating="0";
				}
				$NoVoters=$v->NoVoters;//点评数
				
				
				//**评论的地址需要做统一管理
				$hotelcommentUrl=getNewUrl($this->thisUnionSite_domainName."/site/hotelcomment.php?hotelid=".$hotelID,$this->isSiteUrlRewriter);//构造酒店评论的URL地址
				//构造酒店详细页URL
				$p1=$_GET["city"].",".$hotelID;
				$p3=$_GET["stb"];
				$p5=$_GET["hname"];
				$p6=$_GET["lzod"];
				$p7=$_GET["hf"];
				$p8=$_GET["oy"];
				$p9=$_GET["pf"];
				
				//获取酒店详细页面的URL
				$getHotelDetailUrl=new HotelUrlControl($p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, "detail");
				$url=$getHotelDetailUrl->returnUrl;
			
				//构造酒店详细页URL
				$hotelDetailUrl=getNewUrl($url,$this->isSiteUrlRewriter);
				
				$subroominfo=$this->getSubRoomInfo($v);//加载子房型的数据
				//判断酒店的评分
				$showRating="暂无点评";//显示评分的信息
				if($Rating!="0")
				{
					$showRating="<span>$Rating</span>/5分($NoVoters 人点评)";
				}

				$hotelNameTemp=utf_substr($v->HotelName,50);//做字段长度
				$briefInfoShowValue=strlen($briefInfo);//"";//显示鼠标放上后的效果图
				if(strlen($briefInfo)>2){
					$briefInfoShowValue="<span data-role=\"jmp\" title=\"$briefInfo\" class=\"icon_desc_text\"></span>";
				}
				else{
					$briefInfoShowValue="";
				}
				
				$hotelbox=<<<BEGIN
   	<li class="search_result_box">
	<div class="result_info basefix">
	<a class="float_left" onclick="CtripSelfPassParams('POST',this.rel , $('#main_Search_CheckInDate').value()+','+$('#main_Search_CheckOutDate').value(), '_blank')" rel="$hotelDetailUrl" href="javascript:;"   >
	<img width="100" height="75"  src="$image550URL" alt="$hotelName" style="background: url('http://pic.c-ctrip.com/hotels110127/brand_logo.jpg') no-repeat scroll 0 1px transparent;display: block;" />
	</a>
	<div class="result_detail">
	<h3>
	<a onclick="CtripSelfPassParams('POST',this.rel , $('#main_Search_CheckInDate').value()+','+$('#main_Search_CheckOutDate').value(), '_blank')" rel="$hotelDetailUrl" href="javascript:;" title="$v->HotelName"  >$hotelNameTemp</a> 
	$briefInfoShowValue<!-- 切换类名改变钻石样式：diamond01-diamond06;half_diamond01-half_diamond06 -->

	<span title="$showTitle" class="$CustomerEvalName"></span></h3>
	<p>地址：$showAddress</p>
	<p>$showRating</p>
	</div>
	<span class="low_price"><dfn>$CurrencyMinPrice</dfn><span>$MinPrice</span> 起</span></div>
	<table cellspacing="0" cellpadding="0" class="room_list">
		<thead>
			<tr>
				<th style="width: 210px; padding-left: 40px;">房型</th>
				<th style="width: 80px;">床型</th>
				<th style="width: 80px;">早餐</th>
				<th style="width: 80px;">宽带</th>
				<th style="width: 110px;">房价(含服务费)</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		$subroominfo
		
		
	</li>
BEGIN;

		$hotelboxList=$hotelboxList.$hotelbox;
	//	echo $hotelboxList;die;
			}
			$this->responseHotelListHtml=$hotelboxList;
		}
		else
		{
			$this->responseHotelListHtml=<<<BEGIN
			<li class="search_result_box">
	<div class="result_info basefix">
	<div class="result_detail">
	很抱歉，暂时无法找到符合您要求的酒店。<br/>您可以试试更改搜索条件重新搜索，或改订其他酒店。
	</div>
	</li>	
BEGIN;
		}
	}
	/**
	 *
	 * @var 获取显示子房型的数据
	 * @param XML $subroomXML
	 */
	private function getSubRoomInfo($subroomXML)
	{
		$hotelSubrooomHtml="";

		$aidSid="sid=".Sid."&Allianceid=".Allianceid;//联盟信息
		global $Booking_State;
		global $baseUlr;//防止跨域
		if(!empty($subroomXML)&&!empty($subroomXML->BaseRoomList))
		{
		// echo $subroomXML->BaseRoomList->DomesticHotelBaseRoomForList[0]->SubRooms->DomesticHotelBaseSubRoomForList[0]->RoomName;
			$BaseRoomList=$subroomXML->BaseRoomList;//子房型数据
		 
			$SubRoomCnt = 0;//房型计数器;控制子房型的显示（最多显示3个）
			$SubRoomTotalNum=0;//子房型总数
			foreach($BaseRoomList->DomesticHotelBaseRoomForList as $HotelBaseRoom)
			{
				$BaseRoomSub=$HotelBaseRoom->SubRooms;
				$Arrival="";//达到时间
				$CalculateType="";//送券计算方式:固定值，卖价百分比
				$CalculateValue="";//送券计算方式:固定值，卖价百分比 卷的价值
				$Departure="";//离店日期
				$EndDate="";//活动结束日期
				$StartDate="";//活动开始日期
				$TicketType="";//送券类型(限额抵用券 = L,非限额抵用券 = U,机渡非限额抵用券 = F,酒店游票任我住 = R,酒店游票任我游 = T,酒店游票任我行 = S,需要消费券的返现 = C,不需要消费券的返现 =D)
				$GiftsName="";//活动名称

				
				
				foreach($BaseRoomSub->DomesticHotelBaseSubRoomForList as $subRooms)
				{
				//	echo $subRooms->RoomName;
					
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
					

				
				
					$SubRoomCnt = $SubRoomCnt + 1;
					$getGifs=getGifValue($StartDate,$EndDate,$CalculateValue);//获取返劵等信息
					$getBedTypeName=getBedTypeNameIndex($subRooms->BedType);//获取床型
					$getBreakFastNames=getBreakFastName($subRooms->HasBreakfast);//获取到早餐信息
					$getWireInfo=getWireName($subRooms->HasWirelessBroadnet,$subRooms->HasWiredBroadnet);//获取网络使用规则
					$getCurrencyName=currencyTransition($subRooms->Currency,1);//获取到币种
					
					$getAvaeragePrices=round((string)$subRooms->AveragePrice);//获取房价
						
					
					//前3条数据直接显示，后面的数据要做隐藏
					$trClass="";
					if($SubRoomTotalNum<=2)
					{
						$trClass="";
					}
					else
					{
						$trClass="style='display:none'";
					}
					$RoomStatus=$subRooms->RoomStatus;//房态信息
					$BookStatus=$subRooms->BookStatus;//状态标识(可订状态)
					//配置预订按钮的名称和样式
					$buttonName="";
					$buttonClass="";

					//$buttonUrl=$this->orderRoomUrl."&source=pc&allianceId=$this->AID&sid=$this->Sid&secretKey=$this->SKey";//构造预订的URL
					$buttonUrl=$this->orderRoomUrl."&source=pc&allianceId=".Allianceid."&sid=".Sid."&secretKey=".SiteKey."&sitename=".UnionSite_ShortName;//构造预订的URL
					$buttonUrl = str_replace("{hotelId}",$subroomXML->HotelID,$buttonUrl);
					$buttonUrl = str_replace("{roomId}",$subRooms->RoomID,$buttonUrl);
					$buttonUrl = str_replace("{checkInDate}",$this->CheckInDate,$buttonUrl);
					$buttonUrl = str_replace("{checkOutDate}",$this->CheckOutDate,$buttonUrl);
					$buttonUrl = str_replace("{cityId}",$this->cityid,$buttonUrl);
					
					if($BookStatus=="Good"&&$RoomStatus!="N")
					{
						$buttonName="预订";
						$buttonClass="btn_m_blue2";
						
						$buttonUrl=$baseUlr."/site/hotelbooking.php?ifream=".urlencode($buttonUrl);
						if($Booking_State=='1'){
							$buttonUrl="http://u.ctrip.com/union/CtripRedirect.aspx?TypeID=60&CheckInDate=".$this->CheckInDate."&CheckOutDate=".$this->CheckOutDate."&".$aidSid."&HotelID=".$subroomXML->HotelID;
						}
						$buttonHtml="<a href=\"$buttonUrl\"  class=\"$buttonClass\" target=\"_blank\">$buttonName</a>";
					}
					else {
						$buttonName="查看";
						$buttonClass="btn_m_light";
						$buttonUrl="";
						$buttonHtml="<input type=\"button\"  value=\"$buttonName\" class=\"$buttonClass\" />";
					}
					
					
					//if($Booking_State=='1'){
					//	$getGifsHtml="<span class=\"icon_refund\">$getGifs</span>";//返现的信息
					
				//	}
					
					$PayType=$subRooms->PayType;//预付现付标记 现付：FG  预付：PP
					$IsGuarantee=$subRooms->IsGuarantee;//是否担保
					$guaranteeHtml="";//担保
					$guaranteeTitleName="";//担保类型的名称

					
					$isChaoShiDanbao="";//是否是超时担保类型
					
					$isMobileGuarantee="F";//是否是手机担保，手机担保不做显示
					//判断担保的类型
					//$danbao="";
					if(!empty($subRooms->RoolDInfoEntiy->DomesticHotelRoomDInfoEntity))
					{
						$guaranteeTypeXML=$subRooms->RoolDInfoEntiy->DomesticHotelRoomDInfoEntity;
						$GuaranteeType=$guaranteeTypeXML->GuaranteeType;//担保.例如信用卡担保，手机担保
						$Userlimited=$guaranteeTypeXML->Userlimited;//用户限制
						$Allneedguarantee=$guaranteeTypeXML->Allneedguarantee;//担保	T未全额担保
						//$danbao=$GuaranteeType."|".$Userlimited."|".$Allneedguarantee;

						//担保判断
						//信用卡全额
						if($Allneedguarantee=="T")
						{$guaranteeTitleName="房量紧张需提供信用卡全额担保";}
						if($Allneedguarantee!="T"&&$Userlimited=="3"&&$GuaranteeType=="C")
						{$guaranteeTitleName="房量紧张需提供信用卡一律担保";}
						if($Allneedguarantee!="T"&&$Userlimited!="3"&&$Allneedguarantee=="B")
						{$guaranteeTitleName="房量紧张需提供信用卡峰时担保";}
						if($Allneedguarantee!="T"&&$Userlimited=="2"&&$Allneedguarantee!="B"&&$GuaranteeType=="C")
						{$guaranteeTitleName="房量紧张需提供信用卡超时担保";$isChaoShiDanbao="T";}
						if($Allneedguarantee!="T"&&$Userlimited=="2"&&$Allneedguarantee!="B"&&$GuaranteeType=="M")
						{$guaranteeTitleName="房量紧张需提供手机超时担保";$isMobileGuarantee="T";}
						if($Allneedguarantee!="T"&&$Userlimited=="3"&&$GuaranteeType=="M")
						{$guaranteeTitleName="房量紧张需提供手机一律担保";$isMobileGuarantee="T";}
					}
					//title="房量紧张需提供信用卡或积分担保
					if($IsGuarantee=="T"&&$isChaoShiDanbao!="T")  //	if($IsGuarantee=="T")
					{
						//超时担保在可订性检查中处理，不在列表中体现
						$guaranteeHtml="<span class=\"icon_vouch\" title=\"$guaranteeTitleName\"></span>";
					}
				
					//下面是一个子房间
					//http://127.0.0.1:8888/site/ajaxrequest/subroomInfoRequest.php?hid=671&rid=30540&CheckInDate=2012-09-03&CheckOutDate=2012-09-05&cityid=2
					//$hotelDetailSubRoomUrl=$this->thisUnionSite_domainName."/site/ajaxrequest/subroomInfoRequest.php?hid=".$subroomXML->HotelID."&rid=".$subRooms->RoomID."&CheckInDate=".$this->CheckInDate."&CheckOutDate=".$this->CheckOutDate."&cityid=".$this->cityid;
					$hotelDetailSubRoomUrl=$baseUlr."/site/ajaxrequest/subroomInfoRequest.php?hid=".$subroomXML->HotelID."&rid=".$subRooms->RoomID."&CheckInDate=".$this->CheckInDate."&CheckOutDate=".$this->CheckOutDate."&cityid=".$this->cityid;
					
					$tdID="td_".$subroomXML->HotelID."_".$subRooms->RoomID;//显示详细的控件名称
					
					$subRoomsRoomNameTemp=utf_substr($subRooms->RoomName,32);//做字段长度
					
				
					$subroomTd=<<<BEGIN
			    <tr $trClass>
				<td style="padding-left: 20px;">
				<a href="$hotelDetailSubRoomUrl|$tdID" title="$subRooms->RoomName" class="room_pic">$subRoomsRoomNameTemp</a>$getGifsHtml</td>
				<td>$getBedTypeName</td>
				<td>$getBreakFastNames</td>
				<td>$getWireInfo</td>
				<td><dfn>$getCurrencyName$getAvaeragePrices</dfn></td>
				<td style="text-align: right;">$guaranteeHtml
				
				$buttonHtml
				
				</td>
				</tr>
				<!--以下是没有房型的详细数据，用hotelID+RoomID 调用D_HotelDetail来实现显示 -->
				<tr style="display:none"><td colspan='6' id="$tdID" name="$tdID"></td></tr>
BEGIN;
				

					if($isMobileGuarantee=="F")//手机担保不做显示
					{
					$hotelSubrooomHtml=$hotelSubrooomHtml.$subroomTd;
					$SubRoomTotalNum=$SubRoomTotalNum+1;
					}
				}
			}
			if($hotelSubrooomHtml=="")$hotelSubrooomHtml="<tr><td colspan='6'>没有符合条件的房型 </td></tr>";
			
			$hotelSubrooomHtml=$hotelSubrooomHtml."</tbody></table>";
			if($SubRoomTotalNum>3)
			{
				$more=<<<BEGIN
				<p class="hotel_toggle"><a href="#" class="toggle_down toggle_roomtype">所有房型($SubRoomTotalNum)</a></p>
BEGIN;
				$hotelSubrooomHtml=$hotelSubrooomHtml.$more;
			}
		}
		if($hotelSubrooomHtml=="")
		{
		   $hotelSubrooomHtml="</table>";//如果没有数据的时候，将闭包起来
		}
		return $hotelSubrooomHtml;
	}

	/**
	 * @var 返回酒店详细的数据
	 * @var HTML
	 */
	private function setHotelDetail()
	{
		$this->hotelDetail_TitleAddress=$this->hotelTitleAddress();//酒店名称地址照片一块的HTML
		$this->hotelDetailSubRoomList=$this->hotelDetailSubRoomList($this->returnXML->DomesticHotelProductDetail->BaseRoomList);
		$this->hotelDetailCreditCardInfoList=$this->getCreditCardInfoList($this->returnXML->DomesticHotelProductDetail->CreditCardInfoList);
		$this->getFacilityAndHotelList($this->returnXML->DomesticHotelProductDetail->FacilityAndHotelList);//加载设施
		$this->getPlaceInfoList($this->returnXML->DomesticHotelProductDetail->PlaceInfoList);//加载地标
	}

	/**
	 * @var 获取标题+地址+图片的html
	 */
	private function hotelTitleAddress()
	{
		$v=$this->returnXML->DomesticHotelProductDetail;//酒店信息体
		$hotelName=$v->HotelName;//酒店的名称
		$this->hotelDetailName=$hotelName;//酒店的名称
		$this->HotelDetailId=$v->HotelID;//酒店的ID
		$HotelNameEn=$v->HotelNameEn;//酒店的英文名称
		$CustomerEval=$v->CustomerEval;//砖石级别
		$CustomerEvalName="";//保存砖石级别的名称
		$ProvinceName=$v->ProvinceName;//省级单位名称
		$LocationName=$v->LocationName;//行政区名称
		$ZoneName=$v->ZoneName;//商业区1名称
		$ZoneName2=$v->ZoneName2;//商业区2名称
		$Address=$v->Address;//地址
		$RoadCross=$v->RoadCross;//交叉路口
		$zonespit="";
		if($ZoneName2!=""){$zonespit="&nbsp;";}
		$crossspit="";
		if($RoadCross!=""){$crossspit="&nbsp;";}
		$showAddress=$ProvinceName."&nbsp;".$LocationName.$Address."(".$ZoneName.$zonespit.$ZoneName2.$crossspit.$RoadCross.")";//构造地址
		$this->hotelDetailPrice=$v->MinPrice;//单个酒店的起价
		$this->hotelDetailCurrencyMinPrice=$v->CurrencyMinPrice;//单个酒店的价格货币单位
		$NoVoters=$v->NoVoters;//携程点评数
		$Rating=round("$v->Rating","1");//点评分
		if($Rating=="-1"){
			$Rating="0";
		}
		$this->showAddress=$LocationName.$Address."(".$ZoneName.$ZoneName2.$RoadCross.")";
		$this->NoVoters=$NoVoters;
		$this->Rating=$Rating;
		
		$this->lon=$v->HotelMap->LON;
		$this->lat=$v->HotelMap->LAT;
		
		$showRating="暂无点评";//显示评分的信息
		$RatingService=1;//酒店服务评分
		$RatingServiceShow="";
		$RatingRoom=1;//房间卫生评分
		$RatingRoomShow="";
		$RatingCostBenefit=1;//设施设备评分
		$RatingCostBenefitShow="";
		$RatingAtmosphere=1;//周边环境评分
		$RatingAtmosphereShow="";
		
		global $MapKey;
		if($MapKey)
		$MapHtml="<span><a href='#' class='viewMap' id='viewMap' style='font-size:13px;'>查看地图</a></span>";
		
		
		if($Rating!="0")
		{
			$showRating="<a  class=\"mark\">$Rating</a>/5分&nbsp;&nbsp;$NoVoters 人点评";
			$RatingService=100*($v->RatingService)/5;//酒店服务评分
			$RatingRoom=100*($v->RatingRoom)/5;//房间卫生评分
			$RatingCostBenefit=100*($v->RatingCostBenefit)/5;//设施设备评分
			$RatingAtmosphere=100*($v->RatingAtmosphere)/5;//周边环境评分
			
			$RatingServiceShow=strAddTrad($v->RatingService,3,".0");
			$RatingRoomShow=strAddTrad($v->RatingRoom,3,".0");
			$RatingCostBenefitShow=strAddTrad($v->RatingCostBenefit,3,".0");
			$RatingAtmosphereShow=strAddTrad($v->RatingAtmosphere,3,".0");
		}
			

		$this->hotelDetailJudge=$showRating;//"<a href=\"#\" class=\"mark\">$Rating</a>/5分&nbsp;&nbsp;<a href=\"#\">$NoVoters 人点评</a>";
		$this->hotelDetailPointBasefix=<<<BEGIN
		<li>卫生<span class="progress_bar"><div style="width:$RatingRoom%;"></div></span>$RatingRoomShow</li>
		<li>设施<span class="progress_bar"><div style="width:$RatingCostBenefit%;"></div></span>$RatingCostBenefitShow</li>
		<li>服务<span class="progress_bar"><div style="width:$RatingService%;"></div></span>$RatingServiceShow</li>
		<li>环境<span class="progress_bar"><div style="width:$RatingAtmosphere%;"></div></span>$RatingAtmosphereShow</li>
BEGIN;

		//设置酒店客户点评列表中的数据
		$novotersShow="<strong>$NoVoters</strong>位成功入住客户发表了点评";
		$showRating2="<a href=\"#\">$Rating</a>/5分";
		if($Rating=="0")
		{
		   $novotersShow="";
		   $showRating2=$showRating;
		}
		$this->hotelDetailCommentGeneral=<<<BEGIN
							<div class="comment_mark basefix">
								<span class="mark">$showRating2</span>
								<ul class="hotel_point basefix">
								$this->hotelDetailPointBasefix
								</ul>
							</div>
							<div class="comment_num">
							$novotersShow
							</div>
BEGIN;
								//设置酒店客户点评列表中的数据

								$this->hotelDetailHotelDesc=htmlTransition($v->HotelDesc);//酒店的简介
								$this->hotelDetailZone=$v->Zone;//酒店详细中的商业区
								$this->hotelDetailDiy_Breakfast=$v->Diy_breakfast>0?$v->BreakfastCurrency.$v->Diy_breakfast:"";//自助早餐的价格
								$this->hotelDetailBaseInfoShow="<li>".getYearFromDate($v->OpenYear)."年开业</li><li>".$v->Roomquantity."间房</li>";//酒店的基本信息，目前只有酒店房间总数
								$this->hotelDetailSurroundings=$v->Surroundings;//周边信息
								$this->hotelDetailHotelMapPicUrl=$v->HotelMapPicUrl."/272/135.jpg";//酒店的地图
								/* <RatingService>4.1155063291139244</RatingService> 酒店服务评分
								 <RatingRoom>4.231012658227848</RatingRoom>房间卫生评分
								 <RatingCostBenefit>3.85126582278481</RatingCostBenefit>设施设备评分
								 <RatingAtmosphere>4.1534810126582276</RatingAtmosphere>周边环境评分
							
								
								
								if(strpos($CustomerEval,".")>0)//有小数点
								{
									$arrayCE=explode(".",$CustomerEval);
									if($arrayCE[1]>0)
									{
										$customerleve=$arrayCE[0]+1;
										$CustomerEvalName="half_diamond0".$customerleve;
									}
									else {
										$CustomerEvalName="diamond0".$arrayCE[0];
									}
								}
								else {
									$CustomerEvalName="diamond0".$CustomerEval;
								}	 */
								
								
								$Star=$v->Star;//国家星级平定
								$Rstar=$v->Rstar;//携程星级
								
								$StarInfo=get_star_info($Star,$Rstar);//
								
								$showTitle=$StarInfo['0'];
								$CustomerEvalName=$StarInfo['1'];
								
								
								
								
								//构造图片
								$imglist=$this->getHotelPicList($v->HotelEffectPicList);
								
								$imgTotalNum=0;//图片总数
								$imageListLi="";//显示图片的列表
								$firstImageShow="";//第一个图片显示信息
								$firstImageListShow="";//滚动显示的第一个图片显示信息
								$firstImageListShowAlt="";//滚动显示的第一个图片的标题
								$imageListLi2="";//滚动显示的图片列表
								if(!empty($imglist)&&strlen($imglist)>0)
								{
									$arrayImgs=explode("|", $imglist);
									
									if(count($arrayImgs)>0)
									{
										$imgTotalNum=$arrayImgs[0]-1;//图片数(第一张图不用取)
										for($i=2;$i<count($arrayImgs);$i++)//count($arrayImgs)
										{
											$images=$arrayImgs[$i];
											if($images&&strpos($images,",")>0)
											{
												$imagesArray=explode(",", $images);
												$bigImage=removePicWaterMark($imagesArray[0]);//大图片
												$smallImage=$imagesArray[1];//小图片
												$imageAlt=$imagesArray[2];//图片的标题
												if($i==2)//做第一张图的
												{
													$firstImageShow="<img style=\"background: url('http://pic.c-ctrip.com/hotels110127/brand_logo.jpg') no-repeat scroll 0 1px transparent;display: block;\" width=\"320\" height=\"240\" alt=\"$imageAlt\" src=\"$bigImage\">";
													$firstImageListShow="<img width=\"550\" height=\"412\" alt=\"$imageAlt\" src=\"$bigImage\">";
													$firstImageListShowAlt=$imageAlt;
												}
												if($i<11)
												{
													$imageListLi=$imageListLi."<li><a href=\"#jumpHotel\"><img width=\"88\" height=\"66\" alt=\"$imageAlt\" src=\"$smallImage\"></a></li>";
												}
												$imageListLi2=$imageListLi2."<a href=\"$bigImage\"><img width=\"80\" height=\"60\" alt=\"$imageAlt\" src=\"$smallImage\"></a>";
											}
										}



									}
								}
								$this->hotelDetailTotalImageNum=$imgTotalNum;//设置当前酒店的图片总数
								$coutw=<<<BEGIN
    	<div class="detail_info_left">
				<!-- 切换类名改变钻石样式：diamond01-diamond06;half_diamond01-half_diamond06 -->
				<h2>$hotelName<span>$HotelNameEn</span><span title="$showTitle" class="$CustomerEvalName"></span>
				
				</h2>
				<p>$showAddress$MapHtml</p>
				<div class="detail_slide basefix">
					<div class="slide_pic">
					$firstImageShow
					</div>
					<ul class="slide_list basefix">
					$imageListLi
						<li class="all_pic"><a href="#jumpHotel" class="show_all">查看所有图片($imgTotalNum 张)</a></li>
					</ul>
				</div>
			</div>
BEGIN;
					$imageShowList=<<<BEGIN
          <div class="search_result_box basefix">
						<div class="result_hotel_pic">
							<a href="#">酒店图片</a>
						</div>
						<div class="big_pic">
						$firstImageListShow
						</div>
						<p class="pic_description basefix"><strong>$hotelName<span id="imagealt">$firstImageListShowAlt</span></strong><span><b>1</b>/$imgTotalNum 张</span></p>
						<!-- 更改a标签的类名来改变状态 -->
						<div class="pic_scroll">
							<a class="page_up_disable" href="#" id="prev_pic"></a>
							<a class="page_down" href="#" id="next_pic"></a>
							<div class="scroll_list basefix">
							<div id="scroll_container">
							$imageListLi2
							</div>
							</div>
						</div>
					</div>
BEGIN;
							$this->hotelDetailImageListShow=$imageShowList;//给滚动播放的图片赋值
							return $coutw;

	}
	/**
	 *
	 * @var 获取到酒店的所有的图片- 最后以"总数|pic1Big,pic1Small|pic2Big,pic2Small|..."格式输出
	 * @param 图片节点
	 */
	private function getHotelPicList($PicList)
	{
		
		$PicCount="";//最后以"总数|pic1Big,pic1Small|pic2Big,pic2Small|..."格式输出
		$countPic=0;//统计图片数量
		if($PicList&&$PicList->DomesticHotelEffectPic[0])
		{
			foreach ($PicList->DomesticHotelEffectPic as $effectPic)
			{
				$countPic=$countPic+1;
				$PicCount=$PicCount."|".$effectPic->HotelPic550URL.",".$effectPic->HotelPic78URL.",".$effectPic->PicTitle ;
			}
			$PicCount=$countPic.$PicCount;
		}
		return $PicCount;
	}

	/**
	 *
	 * @var 根据酒店的基本信息，返回酒店的子房型数据，包括”建筑面积+楼层+...“等
	 */
	private function hotelDetailSubRoomList($subroomXML)
	{  
		$hotelSubrooomHtml="";
		if(!empty($subroomXML))
		{
			$BaseRoomList=$subroomXML;//子房型数据
			$SubRoomCnt = 0;//房型计数器;控制子房型的显示（最多显示3个）
			$SubRoomTotalNum=0;//子房型总数
			global $Booking_State;
			global $baseUlr;
			$aidSid="sid=".Sid."&Allianceid=".Allianceid;//联盟信息
			foreach($BaseRoomList->DomesticHotelBaseRoom as $HotelBaseRoom)
			{

				$BaseRoomSub=$HotelBaseRoom->SubRooms;
				$Arrival="";//达到时间
				$CalculateType="";//送券计算方式:固定值，卖价百分比
				$CalculateValue="";//送券计算方式:固定值，卖价百分比 卷的价值
				$Departure="";//离店日期
				$EndDate="";//活动结束日期
				$StartDate="";//活动开始日期
				$TicketType="";//送券类型(限额抵用券 = L,非限额抵用券 = U,机渡非限额抵用券 = F,酒店游票任我住 = R,酒店游票任我游 = T,酒店游票任我行 = S,需要消费券的返现 = C,不需要消费券的返现 =D)
				$GiftsName="";//活动名称
				
				foreach($BaseRoomSub->DomesticHotelBaseSubRoom as $subRooms)
				{
					
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
					//<span class="icon_refund">$getGifs</span>//返现的信息现在不呈现

					$SubRoomCnt = $SubRoomCnt + 1;
					$getGifs=getGifValue($StartDate,$EndDate,$CalculateValue);//获取返劵等信息
					$getBedTypeName=getBedTypeNameIndex($subRooms->BedType);//获取床型
					$getBreakFastNames=getBreakFastName($subRooms->HasBreakfast);//获取到早餐信息
					$getWireInfo=getWireName($subRooms->HasWirelessBroadnet,$subRooms->HasWiredBroadnet);//获取网络使用规则
					$getCurrencyName=currencyTransition($subRooms->Currency);//获取到币种
					$getAvaeragePrices=round((string)$subRooms->AveragePrice);//获取房价
					
					//if($Booking_State=='1')
					//$getGifsHtml="<span class=\"icon_refund\">$getGifs</span>";//返现的信息
					
										
					//前3条数据直接显示，后面的数据要做隐藏
					$trClass="";
		
					$RoomStatus=$subRooms->HotelRoomInfoList->DomesticHotelRoomInfo->RoomStatus;//房态信息
					$BookStatus=$subRooms->BookStatus;//状态标识(可订状态)
						
					//配置预订按钮的名称和样式
					$buttonName="";
					$buttonClass="";

					$buttonUrl=$this->orderRoomUrl."&source=pc&allianceId=".Allianceid."&sid=".Sid."&secretKey=".SiteKey."&sitename=".UnionSite_ShortName;//构造预订的URL
					$buttonUrl = str_replace("{hotelId}",$this->HotelDetailId,$buttonUrl);
					$buttonUrl = str_replace("{roomId}",$subRooms->RoomID,$buttonUrl);
					$buttonUrl = str_replace("{checkInDate}",$this->CheckInDate,$buttonUrl);
					$buttonUrl = str_replace("{checkOutDate}",$this->CheckOutDate,$buttonUrl);
					$buttonUrl = str_replace("{cityId}",$this->cityid,$buttonUrl);
						
	
					if($BookStatus=="G"&&$RoomStatus!="N")
					{
						$buttonName="预订";
						$buttonClass="btn_m_blue2";
						$buttonUrl=$baseUlr."/site/hotelbooking.php?ifream=".urlencode($buttonUrl);
						if($Booking_State=='1'){
							$buttonUrl="http://u.ctrip.com/union/CtripRedirect.aspx?TypeID=60&CheckInDate=".$this->CheckInDate."&CheckOutDate=".$this->CheckOutDate."&".$aidSid."&HotelID=".$this->HotelDetailId;
						}
						$buttonHtml="<a href=\"$buttonUrl\"  class=\"$buttonClass\" target=\"_blank\">$buttonName</a>";
					}
					else {
						$buttonName="查看";
						$buttonClass="btn_m_light";
						$buttonUrl="";
						$buttonHtml="<input type=\"button\"  value=\"$buttonName\" class=\"$buttonClass\" />";
					}
					
					$PayType=$subRooms->HotelRoomInfoList->DomesticHotelRoomInfo->PayType;//预付现付标记 现付：FG  预付：PP
					$IsGuarantee=$subRooms->IsGuarantee;//是否担保
					$guaranteeHtml="";//担保
					$guaranteeTitleName="";//担保类型的名称
					
					$isChaoShiDanbao="";//是否是超时担保类型

					$isMobileGuarantee="F";//是否是手机担保，手机担保不做显示
					//判断担保的类型
					if($subRooms->RoolDInfoEntiy->DomesticHotelRoomDInfoEntity)
					{
						$guaranteeTypeXML=$subRooms->RoolDInfoEntiy->DomesticHotelRoomDInfoEntity;
						$GuaranteeType=$guaranteeTypeXML->GuaranteeType;//担保.例如信用卡担保，手机担保
						$Userlimited=$guaranteeTypeXML->Userlimited;//用户限制
						$Allneedguarantee=$guaranteeTypeXML->Allneedguarantee;//担保	T未全额担保
							
						//担保判断
						//信用卡全额
						
						if($Allneedguarantee=="T")
						{$guaranteeTitleName="房量紧张需提供信用卡全额担保";}
						if($Allneedguarantee!="T"&&$Userlimited=="3"&&$GuaranteeType=="C")
						{$guaranteeTitleName="房量紧张需提供信用卡一律担保";}
						if($Allneedguarantee!="T"&&$Userlimited!="3"&&$Allneedguarantee=="B")
						{$guaranteeTitleName="房量紧张需提供信用卡峰时担保";}
						if($Allneedguarantee!="T"&&$Userlimited=="2"&&$Allneedguarantee!="B"&&$GuaranteeType=="C")
						{$guaranteeTitleName="房量紧张需提供信用卡超时担保";$isChaoShiDanbao="T";}
						if($Allneedguarantee!="T"&&$Userlimited=="2"&&$Allneedguarantee!="B"&&$GuaranteeType=="M")
						{$guaranteeTitleName="房量紧张需提供手机超时担保";$isMobileGuarantee="T";}
						if($Allneedguarantee!="T"&&$Userlimited=="3"&&$GuaranteeType=="M")
						{$guaranteeTitleName="房量紧张需提供手机一律担保";$isMobileGuarantee="T";}
					}
					//title="房量紧张需提供信用卡或积分担保
					if($IsGuarantee=="T"&&$isChaoShiDanbao!="T")
					{
						//超时担保的时效性比较强，不在列表中作判断，下单时，可定性检查会处理掉
						$guaranteeHtml="<span class=\"icon_vouch\" title=\"$guaranteeTitleName\"></span>";
					}
					//下面是一个子房间 	
					
					$tdID="td_".$subroomXML->HotelID."_".$subRooms->RoomID;//显示详细的控件名称
					
					$subroomTd=<<<BEGIN
			    <tr $trClass class='hotelList'>
				<td style="padding-left: 20px;">
		<a href="#" class="room_pic" rel="$tdID" title="$subRooms->RoomName">
			$subRooms->RoomName</a>$getGifsHtml</td>
				<td>$getBedTypeName</td>
				<td>$getBreakFastNames</td>
				<td>$getWireInfo</td>
				<td><dfn>$getCurrencyName$getAvaeragePrices</dfn></td>
				<td style="text-align: right;">$guaranteeHtml
			$buttonHtml
				
				</td>
				</tr>
				<!--以下是没有房型的详细数据，用hotelID+RoomID 调用D_HotelDetail来实现显示 -->
BEGIN;


					if($PayType=="FG"&&$isMobileGuarantee=="F")//做现付的过滤,手机担保不做显示
					{
						$hotelSubrooomHtml=$hotelSubrooomHtml.$subroomTd."<tr style='display:none;' class='hotelDetail' id=\"$tdID\" name=\"$tdID\"><td colspan='6'>".$this->getHotelSubRoomMoreInfo($subRooms)."</td></tr>";
						$SubRoomTotalNum=$SubRoomTotalNum+1;
					}
				 
				}
			}
			if($hotelSubrooomHtml=="")$hotelSubrooomHtml="<tr><td colspan='6'>没有符合条件的房型 </td></tr>";
			$hotelSubrooomHtml=$hotelSubrooomHtml."</table>";
		}
		if($hotelSubrooomHtml=="")
		{
			$hotelSubrooomHtml=" </table>";
		}
		return $hotelSubrooomHtml;
	}
	/**
	 *
	 * @var 返回符合条件的子房型详细数据
	 * @param $roomid
	 */
	public function getHotelSubRoomMoreInfoByRoomId($roomid)
	{
		$coutw="";
		$responseHotelDetailXML=$this->returnXML;
		//echo json_encode($responseHotelDetailXML);
		if($responseHotelDetailXML->DomesticHotelProductDetail->BaseRoomList)
		{
			$subRoomList=$responseHotelDetailXML->DomesticHotelProductDetail->BaseRoomList;
			foreach ($subRoomList->DomesticHotelBaseRoom as $v)
			{
				if($v)
				{
					foreach($v->SubRooms->DomesticHotelBaseSubRoom as $u)
					{
						//echo json_encode($u);
						if($u->RoomID==$roomid)
						{
							$coutw=$this->getHotelSubRoomMoreInfo($u);
						}
					}
				}
			}
		}
		return $coutw;
	}
	/**
	 *
	 * @var 返回酒店详细信息：建筑面积，楼层，床宽等信息
	 */
	private function getHotelSubRoomMoreInfo($subRooms)
	{
		$bedwidth="";//床宽
		if($subRooms->HasKingBed=="T"&&$subRooms->KingBedWidth!="")
		{
			$bedwidth="大床".$subRooms->KingBedWidth."米";
		}
		if($subRooms->HasTwinBed=="T"&&$subRooms->TwinBedWidth!="")
		{
			$bedwidth="双床".$subRooms->TwinBedWidth."米";
		}
		$isaddbed="";//可加床
		if($subRooms->AddBed=="T")
		{
			if(isDouble($subRooms->AddBedFee)>0)
			$isaddbed="可加床:".currencyTransition("RMB").isDouble($subRooms->AddBedFee);
		}
		else
		{
			$isaddbed="不可加床";
		}
		$hasRoomInNoSmokeArea="";//无烟房
		if($subRooms->HasRoomInNonSmokeArea=="T")
		{
			$hasRoomInNoSmokeArea="该房型可安排无烟楼层";
		}
		if($subRooms->HasNonSmokeRoom=="T")
		{
			$hasRoomInNoSmokeArea="该房型有无烟房";
		}
		if($subRooms->HasSmokeCleanRoom=="T")
		{
		 $hasRoomInNoSmokeArea="该房可无烟处理";
		}
		//处理图片
		$subroomPic_url="";//房间的图片
		$subroomPic="";//房间的图片显示
		
		if(!empty($subRooms->RoomEffectPicList))
		{
			foreach($subRooms->RoomEffectPicList->DomesticHotelEffectPic as $v){
				$subroomPic_url=removePicWaterMark((string)$v->HotelPic175URL);//图片地址
				$PicTitle=$v->PicTitle;//图片标题
				$subroomPic .="<img width=\"100\" height=\"75\" src=\"$subroomPic_url\" alt=\"$PicTitle\" />&nbsp;&nbsp;";
			}
			
			//if($subRooms->RoomEffectPicList->DomesticHotelEffectPic[0])
			//{
			//	$subroomPic_url=$subRooms->RoomEffectPicList->DomesticHotelEffectPic[0]->HotelPic175URL;//图片地址
			//	$PicTitle=$subRooms->RoomEffectPicList->DomesticHotelEffectPic[0]->PicTitle;//图片标题
			//	$subroomPic="<img width=\"100\" height=\"75\" src=\"$subroomPic_url\" alt=\"$PicTitle\" />";
			
			//}
		
		}
		if(!empty($subRooms->RoomDsc)) $RoomDsc="<td colspan=\"4\">$subRooms->RoomDsc </td>";
		if(!empty($subroomPic)) $subroomPics="<div class=\"room_detail_pic\">$subroomPic</div>";
		
		$coutw=<<<BEGIN
		<div class="room_detail basefix" style="display: ;"><!-- 收缩样式切换 -->
$subroomPics
<table cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td style="width: 190px;">建筑面积：$subRooms->RoomArea 平方米</td>
			<td style="width: 130px;">楼层：$subRooms->FloorRange 层</td>
			<td style="width: 130px;">床宽：$bedwidth </td>
			<td>$isaddbed</td>
		</tr>
		<tr>
			<td>无烟房：$hasRoomInNoSmokeArea </td>
			<td>可入住人数：$subRooms->Person 人</td>
			<td colspan="2">宽带：$subRooms->BroadnetFeeDetail</td>
		</tr>
		<tr>
			$RoomDsc
		</tr>
	</tbody>
</table>
<a href="#" class="hide">隐藏</a></div>
</div>
BEGIN;
		return  $coutw;
	}
	/**
	 * @var 显示酒店支持的银行卡、信用卡图标
	 */
	private function getCreditCardInfoList($CreditCardInfoList)
	{
		$coutw="";
		$textCoutw="";//文字的显示
		$imageUrl="http://pic.c-ctrip.com/hotels/logo_";//logo的地址
		/* Master = 6  、Visa=7、 Amex =8，DinersClub=9 ，Jcb=10， UnionPay=14 其余的只显示文字就可以了*/
		$cardArray=array('0','1','2','3','4','5','master01.gif','visa01.gif','amex01.gif','dinersClub01.gif','jcb01.gif','11','12','13','unionPay01.gif','15');
		if($CreditCardInfoList)
		{
			foreach ($CreditCardInfoList->DomesticHotelCreditCard as $v)
			{
				$imaType=intval($v->Creditcardtype);
				$imaShow="";
				if(strpos($cardArray[$imaType],'.')>0)
				{
					//对于有图片的，显示图片，否则就只显示文字
					$img=$imageUrl.$cardArray[$imaType];//拼接图片地址
					$imaShow="<li><img src=\"$img\" title=\"$v->CreditcardName\" /></li>";
				}
				else {
					$textCoutw=$textCoutw.$v->CreditcardName."&nbsp;&nbsp;";
				}
				$coutw.= $imaShow;
			}
		}
		if(strlen($textCoutw)>2){
			$coutw=$coutw."<li><p style='white-space:normal;'>".$textCoutw."</p></li>";
		}
		return $coutw;
	}
	/**
	 *
	 * @var 显示酒店的 客房设施+餐饮设施+酒店服务
	 * @param  XML $FacilityAndHotelList
	 */
	private function getFacilityAndHotelList($FacilityAndHotelList)
	{
		$FType1="";//宾馆服务项目
		$FType2="";//宾馆餐饮设施
		$FType3="";//宾馆娱乐与健身设施
		$FType4="";//客房设施和服务

		if ($FacilityAndHotelList){
			foreach ($FacilityAndHotelList->DomesticHotelFacilityAndHotel as $v)
			{
				if($v->FType=="1"){$FType1=$FType1."<li>".$v->FacilityName."</li>";}
				if($v->FType=="2"){$FType2=$FType2."<li>".$v->FacilityName."</li>";}
				if($v->FType=="3"){$FType3=$FType3."<li>".$v->FacilityName."</li>";}
				if($v->FType=="4"){$FType4=$FType4."<li>".$v->FacilityName."</li>";}
			}
			$this->hotelDetailFacilityAndHotelListType1=$FType1;
			$this->hotelDetailFacilityAndHotelListType2=$FType2;
			$this->hotelDetailFacilityAndHotelListType3=$FType3;
			$this->hotelDetailFacilityAndHotelListType4=$FType4;
		}
	}
	/**
	 *
	 * @var 显示酒店详细中，地标信息
	 * @param XML $PlaceInfoList
	 */
	private function getPlaceInfoList($PlaceInfoList)
	{
		if ($PlaceInfoList){
			$i=1;
			foreach ($PlaceInfoList->DomesticHotelPlaceInfo as $v)
			{
				if($v->TypeName=="火车站"){
					$TypeName='train';
				}
				if($v->TypeName=="机场"){$TypeName='airport';}
				if($v->TypeName=="路名/地标建筑"){$TypeName='road';}
				if($v->TypeName=="市中心"){$TypeName='center';}
				$hotelPlaceInfo[$TypeName][$i]['PlaceName']=(string)$v->PlaceName;
				$hotelPlaceInfo[$TypeName][$i]['Distance']=(string)$v->Distance;
				$hotelPlaceInfo[$TypeName][$i]['ArrivalWay']=(string)$v->ArrivalWay;
				$i++;
			}
		}
		$this->hotelPlaceInfo=$hotelPlaceInfo;
	}
	
}