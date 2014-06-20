<?php
/**
 * 根据所得酒店ID字符串，获取酒店列表数据（详细页的周边酒店，首页排行榜）
 */
class List_hotelSearch{
	private $cityID="";//城市ID以及城市名称
	private $pagesize=10;//每页多少数据
	private $List="";//酒店查询条件
	private $Type1='DESC';//酒店排序
	private $Type=1;//酒店查询条件类别（1=>多个酒店ID的查询；2=>酒店的品牌ID；3=>首页推荐酒店）
	
	/**
	 *@var 设置返回的酒店热门城市详细门店HTML
	 */
	var $BrandHotelListHtml="";
	
	/**
	 *@var 设置返回的推荐酒店HTML
	 */
	var $responseHotelListHtml="";
	
	/**
	 * @var 符合条件酒店数组
	 */
	var $HotelListArr=array();
	/**
	 * @var 符合条件的酒店总数
	 */
	var $responseTotalNum=0;
	/**
	 * 
	 * @var 调用酒店带分页功能的酒店点评接口
	 * @param int  $hotelID 酒店ID
	 * @param int $pageSize 每页的大小
	 * @param int $pageNo 当前第几页
	 * @param int $cssType 加载数据的样式类
	 */
	
	function __construct($cityID,$pagesize,$List,$Type1,$Type)
	{
		if($cityID!=null&&$cityID!="")
		{
		
		$p1=urldecode($cityID);
		//获取城市ID和城市名称
		if(strpos($p1,",")>=0){
			$arrayP1=explode(",",$p1);
			$this->cityid=$arrayP1[0];
			$this->CityName=$arrayP1[1];	
		}
		
		//获取入店时间和离店时间；这里默认今天跟站点默认时间		
		global $HotelSearchDayNums;
		$this->CheckInDate=getDateYMD('-');
		$this->CheckOutDate=getDateYMD_addDay('-',$HotelSearchDayNums);
		
		//网站全局变量是否正则重写
		global $SiteUrlRewriter;
		$this->isSiteUrlRewriter=$SiteUrlRewriter;
		
		//获取酒店数量
		$this->PageSize=$pagesize;
		$this->Type1=$Type1;
		
		//酒店查询条件类别（1=>多个酒店ID的查询；2=>酒店的品牌ID；3=>首页推荐酒店）
		$this->Type=$Type;
		
		if($this->Type=='1'){
			$this->HotelList=$List;		
		}else if($this->Type=='2') {
			$this->HotelBrand=$List;
		}else{
			//根据酒店排序取数据
			$this->OrderName=$List;
			$this->OrderType=$this->Type1;
		}					
		$this->getHotelListResponseXML_URL();
		}
	}
	
	
	

	/**
	 *
	 * @var 获取酒店列表搜索的返回数据【URL传值模式】
	 */
	function getHotelListResponseXML_URL()
	{
		$D_HotelSearch=new get_D_HotelList();
		
		$D_HotelSearch->CheckInDate=$this->CheckInDate;	
		$D_HotelSearch->CheckOutDate=$this->CheckOutDate;
		$D_HotelSearch->CityID=$this->cityid;
		$D_HotelSearch->PageNumber="1";   //默认第一页
		$D_HotelSearch->PageSize=$this->PageSize;  

		if($this->Type=='1'){
			$D_HotelSearch->HotelList=$this->HotelList;
		}else if($this->Type=='2'){
			$D_HotelSearch->HotelBrand=$this->HotelBrand;
		}else{
			$D_HotelSearch->OrderName=$this->OrderName;
			$D_HotelSearch->OrderType=$this->OrderType;
		}
		$D_HotelSearch->main();
		$this->returnXML=$D_HotelSearch->ResponseXML;//返回的数据是一个XML
		$this->setResponse();//设置返回的数据
		if($this->Type=='1'){
			$this->getHotelList();//返回酒店数组（数组）
		}elseif($this->Type=='2'){
			$this->setBrandHotelList();//酒店热门城市详细门店HTML
		}else{
			$this->setSearchHotelList();//设置推荐酒店列表HTML
		}
		
	}
	/**
	 *
	 * @var 设置返回的数据
	 * @param 酒店列表的XML $returnXML
	 */
	private function setResponse()
	{
		$returnXML=$this->returnXML;
		if(!empty($returnXML) && !empty($returnXML->DomesticHotelList->TotalItems))
		{
			$this->responseTotalNum=$returnXML->DomesticHotelList->TotalItems;//总数
		}
	}
	
/**
	 *@var 返回数组，存放酒店数据
	 */
	private function getHotelList()
	{
		$returnXML=$this->returnXML;
		if(!empty($returnXML)&&$returnXML->HotelList->HotelDataList->DomesticHotelListDataForList!=null){
			foreach ($returnXML->HotelList->HotelDataList->DomesticHotelListDataForList as $k=>  $v )
			{ 
				
				$i=(string)$v->HotelID;	
				$HotelListArr[$i]['HotelID']=(string)$v->HotelID;	
				$HotelListArr[$i]['HotelName']=(string)$v->HotelName;	
				$HotelListArr[$i]['MinPrice']=(string)isDouble($v->MinPrice);//酒店起价
				if($v->Rating>0){
					$Rating=round("$v->Rating","1");//点评分
				}else{
					$Rating="";
				}
				$HotelListArr[$i]['Rating']=$Rating;
				$HotelListArr[$i]['HotelPic']=removePicWaterMark((string)$v->HotelPic550URL);//酒店图片
				global  $SiteHotelDefaultImageUrl;
				global $UnionSite_domainName;
				if(empty($HotelListArr[$i]['HotelPic']))
				{
					//设置默认的图片
					$HotelListArr[$i]['HotelPic']=$UnionSite_domainName.$SiteHotelDefaultImageUrl;
				}
			
				
				$HotelListArr[$i]['ZoneName']=(string)$v->ZoneName;	
				$HotelListArr[$i]['CurrencyMinPrice']=(string)currencyTransition($v->CurrencyMinPrice);//币种
				$CustomerEval=$v->CustomerEval;//砖石级别
			
				$p1=$this->cityid.",".$this->CityName.",".$i;
				$p2="$this->CheckInDate,$this->CheckOutDate";
				//$p1=urlencode($this->cityid.",".$this->CityName.",".$i);
				
				//构造酒店详细页URL
				$getHotelDetailUrl=new HotelUrlControl($p1, $p2, "", "", "", "", "", "", "", "detail");
				$url=$getHotelDetailUrl->returnUrl;
				$hotelDetailUrl=getNewUrl($url,$this->isSiteUrlRewriter);//构造酒店详细的URL地址
				
				$HotelListArr[$i]['url']=$hotelDetailUrl;
				$HotelListArr[$i]['CustomerEvalLevel']=(string)$v->CustomerEval;	

				$HotelListArr[$i]['Star']=(string)$v->Star;	
				$HotelListArr[$i]['Rstar']=(string)$v->Rstar;	
				
				//酒店坐标
				$HotelListArr[$i]['lon']=(string)$v->HotelMap->LON;	
				$HotelListArr[$i]['lat']=(string)$v->HotelMap->LAT;	
			}
			$this->HotelListArr=$HotelListArr;
		}
	}
	
	/**
	 *@var 设置返回的酒店热门城市详细门店HTML
	 */
	private function setBrandHotelList()
	{
		$returnXML=$this->returnXML;
		$hotelbox="";//放置返回的酒店数据
		$hotelboxList="";
		if(!empty($returnXML)&&$returnXML->HotelList->HotelDataList->DomesticHotelListDataForList!=null){
	
			foreach ($returnXML->HotelList->HotelDataList->DomesticHotelListDataForList as $k=>  $v )
			{ 	
				$hotelName=$v->HotelName;//酒店的名称
				$hotelID=$v->HotelID;//酒店的ID
				$ZoneName=$v->ZoneName;//商业区1名称
				$Address=$v->Address;//地址
				$MinPrice=isDouble($v->MinPrice);//酒店的起价
				$showRating="(".$v->NoVoters."人点评)";
				$CurrencyMinPrice=currencyTransition($v->CurrencyMinPrice);//币种

				$p1=$this->cityid.",".$this->CityName.",".$hotelID;
				$p2="$this->CheckInDate,$this->CheckOutDate";
				
				
				//构造酒店详细页URL
				$getHotelDetailUrl=new HotelUrlControl($p1, $p2, "", "", "", "", "", "", "", "detail");
				$url=$getHotelDetailUrl->returnUrl;
				$hotelDetailUrl=getNewUrl($url,$this->isSiteUrlRewriter);//构造酒店详细的URL地址
				
				
				//$bedType=$this->getBedType($v);//加载子房型的数据
				
				//if($bedType=='1')$bedTypeName="双人入住";
				//else $bedTypeName="单人入住";
				$subHotelName=utf_substr($hotelName,32);
				$hotelbox=<<<BEGIN
						<dl class="brand_hotel_detail">
					<dt><a href="$hotelDetailUrl">$subHotelName</a></dt>
					<dd><span>超豪华客房$showRating</span><dfn>&yen;$MinPrice</dfn></dd>
					<dd>地址：$ZoneName$Address</dd>
				</dl>
					
BEGIN;
					$hotelboxList=$hotelboxList.$hotelbox;
	
			}
			$this->BrandHotelListHtml=$hotelboxList;
		}
	}
	
	/**
	 *@var 设置返回推荐酒店列表数据
	 */
	private function setSearchHotelList()
	{
		$returnXML=$this->returnXML;
		$hotelbox="";//放置返回的酒店数据
		$hotelboxList="";
		if(!empty($returnXML)&&$returnXML->HotelList->HotelDataList->DomesticHotelListDataForList!=null){
	
			$i=0;
			foreach ($returnXML->HotelList->HotelDataList->DomesticHotelListDataForList as $k=>  $v )
			{ 
				$i++;
				$briefInfo=$v->Brief;//小图标上显示提示内容
				$image550URL=removePicWaterMark((string)$v->HotelPic550URL);//酒店的主图
				
				global  $SiteHotelDefaultImageUrl;
				global $UnionSite_domainName;
				if(empty($image550URL))
				{
					//设置默认的图片
					$image550URL=$UnionSite_domainName.$SiteHotelDefaultImageUrl;
				}
				
				
				$hotelName=$v->HotelName;//酒店的名称
				$hotelID=$v->HotelID;//酒店的ID
				$CustomerEval=$v->CustomerEval;//砖石级别
		
				
				$Star=$v->Star;//国家星级平定
				$Rstar=$v->Rstar;//携程星级
				
				$StarInfo=get_star_info($Star,$Rstar);//
				$showTitle=$StarInfo['0'];
				$CustomerEvalName=$StarInfo['1'];
				$LocationName=$v->LocationName;//行政区名称

				$MinPrice=isDouble($v->MinPrice);//酒店的起价
				$CurrencyMinPrice=currencyTransition($v->CurrencyMinPrice);//币种
				if($v->Rating>0){
					$Rating=round("$v->Rating","1");//点评分
				}else{
					$Rating="";
				}

				$p1=$this->cityid.",".$this->CityName.",".$hotelID;
				$p2="$this->CheckInDate,$this->CheckOutDate";
				$p8="$this->OrderName,$this->OrderType";
				
				//构造酒店详细页URL
				$getHotelDetailUrl=new HotelUrlControl($p1, $p2, "", "", "", "", "", $p8, "", "detail");
				$url=$getHotelDetailUrl->returnUrl;
				$hotelDetailUrl=getNewUrl($url,$this->isSiteUrlRewriter);//构造酒店详细的URL地址
				
				
			//前四条带图片酒店
				if($i<5){
					$subHotelName=utf_substr($hotelName,26);
					$hotelbox=<<<BEGIN
						<li class="basefix">
							<a href="$hotelDetailUrl" title="$hotelName" class="hotel_pic">
							<img width="95" height="70" style="background: url('http://pic.c-ctrip.com/hotels110127/brand_logo.jpg') no-repeat scroll 0 1px transparent;display: block;" src="$image550URL" alt="$hotelName" /></a>
							<div class="basefix">
								<p><a href="$hotelDetailUrl" title="$hotelName" class="hotel_name">$subHotelName</a></p>
								<p class="basefix"><span title="$showTitle" class="$CustomerEvalName"></span><span class="mark">$Rating</span></p>
								<p><dfn class="price"><dfn>$CurrencyMinPrice</dfn><span>$MinPrice</span>起</dfn></p>
							</div>
						</li>
BEGIN;
					$hotelboxList2=$hotelboxList2.$hotelbox;
		
				}
					
			//后面六条文字的酒店		
			if($i>4&&$i<11){
				$subHotelName=utf_substr($hotelName,25);
				$hotelbox1=<<<BEGIN
					<li>
						<p class="basefix"><a href="$hotelDetailUrl" title="$hotelName" class="hotel_name">
						[$LocationName]$subHotelName</a><dfn class="price"><dfn>$CurrencyMinPrice</dfn><span>$MinPrice</span>起</dfn></p>
						<p class="basefix"><span class="mark">$Rating分</span>
						<span title="$showTitle" class="$CustomerEvalName"></span></p>
					</li>
BEGIN;

				$hotelboxList1=$hotelboxList1.$hotelbox1;
			
			}
		
		$hotelboxList=	
			"<div class=\"content content_blue\">
					<ul class=\"recommend_hotel basefix\">".$hotelboxList2."	</ul>
			 </div>
			 <div class=\"content content_gray\">
					<ul class=\"other_hotel basefix\">".$hotelboxList1."	</ul>
			 </div>";
		
			}
			
			$this->responseHotelListHtml=$hotelboxList;
			
		}
	}
	
	/**
	 *
	 * @var 获取显示子房型的数据
	 * @param XML $subroomXML
	 */
	private function getBedType($subroomXML)
	{
		
		$hotelSubrooomHtml="";
		$bedType=0;//判断是子房中是否有双人床，有为1，没有0
		
		
		if(!empty($subroomXML))
		{
			$BaseRoomList=$subroomXML->BaseRoomList;//子房型数据
			$SubRoomCnt = 0;//房型计数器;控制子房型的显示（最多显示3个）
			$SubRoomTotalNum=0;//子房型总数
			
			foreach($BaseRoomList->DomesticHotelBaseRoomForList as $HotelBaseRoom)
			{
				$BaseRoomSub=$HotelBaseRoom->SubRooms;
				
				foreach($BaseRoomSub->DomesticHotelBaseSubRoomForList as $subRooms)
				{
					$getBedTypeName=getBedTypeNameIndex($subRooms->BedType);//获取床型
					if(strstr($getBedTypeName,"双"))$bedType=1;
					
				}
			}
		}
		return $bedType;

	}
	
	/**
	 * 
	 * @var 析构函数，当类不在使用的时候调用，该函数用来释放资源。
	 */
     
	function __destruct(){
		unset($cityID);
		unset($pagesize);
		unset($List);
		unset($Type1);
		unset($Type);
		unset($BrandHotelListHtml);
		unset($responseHotelListHtml);
		unset($HotelListArr);
		unset($responseTotalNum);
	}
	
	

}