<?php
/**
 * 本页面处理hotelSearch.php页面接受到的查询条件，并支持重新搜索
 * 在hotelSearch.php页面上有$mainHotelSearch=new page_hotelSearch()的定义，并且有酒店列表查询，本页面直接获取数据就可以了
 */
//控制参数
$searchHotelPageSize=$SiteHotelSearch_pagesize;//设置查询时的酒店返回条数在site.config.php中定义
$textIsHidden="display:none";//display:none/display: --坐标等条件的放置区域是否显示--方便调试
//$isShowMoreSearchCondition;控制搜索器：是否显示星级+价格+品牌等筛选条件，true 表示筛选（加载页面中控制）
//$searcherButtonClass;控制搜索器：控制搜索按钮的样式及名称--searcher[btn_orange,搜 索];researcher[btn_mid,重新搜索](加载页面中控制)
//控制参数
$repostSearchClass="btn_mid";
$repostSearchName="重新搜索";
if($searcherButtonClass=="searcher")
{
	$repostSearchClass="btn_mid";
    $repostSearchName="搜 索";
}
?>
<script>
/**
 * 在后期做JS的控制时，做将此处的JS统一到site/js下面----目前已经整理到JS中了
 */
 function doSearchMain_commit(urlhost)
{
	//统一的酒店搜素界面是 site/hotelSearch.php
	//获取本页面的数据；要做一个过滤器，去除掉“/”，会影响URL伪静态
	var cityid=document.main_hotelsearchRquest.main_Search_CityID.value;//城市ID
	var cityname=document.main_hotelsearchRquest.main_Search_CityName.value;//城市名称
	var checkindate=document.main_hotelsearchRquest.main_Search_CheckInDate.value;//入店时间
	var checkoutdate=document.main_hotelsearchRquest.main_Search_CheckOutDate.value;//离店时间
	var star=document.main_hotelsearchRquest.main_Search_Star.value;//星级
	var price=document.main_hotelsearchRquest.main_Search_Price.value;//价格区间
	var hotelname=document.main_hotelsearchRquest.main_Search_HotelName.value;//酒店名称-模糊查询
	var locationZone=document.main_hotelsearchRquest.main_Search_Location_Zone.value;//区域和商业区
	var hotelfacility=document.main_hotelsearchRquest.main_Search_hotelfacility.value;//酒店设施
	var ordername=document.main_hotelsearchRquest.main_Search_ordername.value;//排序的名称
	var ordertype=document.main_hotelsearchRquest.main_Search_ordertype.value;//排序的类型
	var pagenumber="1";//encodeURIComponent(document.main_hotelsearchRquest.main_Search_pagenumber.value);//第几页
	var pagesize=document.main_hotelsearchRquest.main_Search_pagesize.value;//每页多少数据
    var hotelbrand=document.main_hotelsearchRquest.main_Search_hotelbrand.value;//酒店的品牌
	
    var url=urlhost+"/site/hotelsearch.php?city="+cityid+","+cityname+"&cdate="+checkindate+","+checkoutdate+"&stb="+star+";"+hotelbrand+"&price="+price+"&hname="+hotelname+"&lzo="+locationZone+"&hf="+hotelfacility+"&oy="+ordername+","+ordertype+"&pf="+pagenumber+","+pagesize;
  //// var url=urlhost+"/site/hotelSearch/CityID_"+cityid+"/CheckInDate_"+checkindate+"/CheckOutDate_"+checkoutdate+"
  //// Star_"+star+"/Price_"+price+"/HotelName_"+hotelname+"/locationZone_"+locationZone+"/CityName_"+cityname+"/hotelbrand_/hotelfacility_/ordername_Recommend/ordertype_DESC/pagenumber_1/pagesize_5.html";

  window.open(url,"_self");//初始过去的地址，都是用.PHP的地址，接收的页面会做转换的
  }
  /**
  * 酒店详细中，调整入店时间和离店时间后，重新调用查询接口，到本页面来
  */
  function dohotailDetailResearch(urlhost)
  {
  var cityid=document.main_hotelsearchRquest.main_Search_CityID.value;//城市ID
  var cityname=document.main_hotelsearchRquest.main_Search_CityName.value;//城市名称
  var hotailid=document.main_hotelsearchRquest.main_Search_hotailid.value;//酒店的ID
  var star=document.main_hotelsearchRquest.main_Search_Star.value;//星级
  var price=document.main_hotelsearchRquest.main_Search_Price.value;//价格区间
  var hotelname=document.main_hotelsearchRquest.main_Search_HotelName.value;//酒店名称-模糊查询
  var locationZone=document.main_hotelsearchRquest.main_Search_Location_Zone.value;//区域和商业区
  var hotelfacility=document.main_hotelsearchRquest.main_Search_hotelfacility.value;//酒店设施
  var ordername=document.main_hotelsearchRquest.main_Search_ordername.value;//排序的名称
  var ordertype=document.main_hotelsearchRquest.main_Search_ordertype.value;//排序的类型
  var pagenumber=document.main_hotelsearchRquest.main_Search_pagenumber.value;//第几页
  var pagesize=document.main_hotelsearchRquest.main_Search_pagesize.value;//每页多少数据
  var hotelbrand=document.main_hotelsearchRquest.main_Search_hotelbrand.value;//酒店的品牌
  var checkindate=document.getElementById("hotailDetailCheckindate").value;//入店时间
  var checkoutdate=document.getElementById("hotailDetailCheckoutdate").value;//离店时间

  //var url=urlhost+"/site/hoteldetail.php?city="+cityid+","+cityname+","+hotailid;
  //var _postUrl = urlhost+"/site/hoteldetail.php?city="+cityid+","+cityname+","+hotailid;
  var _postUrl=$('#postDataUrl').value();
  var _postParams = checkindate+','+checkoutdate; //"cdate="+checkindate+","+checkoutdate+"&stb="+star+";"+hotelbrand+"&price="+price+"&hname="+hotelname+"&lzod="+locationZone+"&hf="+hotelfacility+"&oy="+ordername+","+ordertype+"&pf="+pagenumber+","+pagesize;
  CtripSelfPassParams("POST", _postUrl, _postParams, "_self");
  //window.open(url,"_self");//初始过去的地址，都是用.PHP的地址，接收的页面会做转换的
  }
  /**
  * 当index页面上的城市切换时，要做页面的跳转
  */
  function doDefaultCityChanage(urlhost)
  {
  var cityid=document.hotelsearchRquest.barSearch_CityID.value;
  var url=urlhost+"/site/index.php?defaultcityid="+cityid;
  window.open(url,"_self");
  }
</script>
<?php 
//预设数据
$this_CityID=$mainHotelSearch->cityid;
$this_CityName=$mainHotelSearch->CityName;
if($this_CityID==""||$this_CityID==null)
{
	$this_CityID=$SiteDefaultCityID;
	$this_CityName=$SiteDefaultCityName;
}

$this_CheckInDate=($mainHotelSearch->CheckInDate==""||$mainHotelSearch->CheckInDate==null?getDateYMD("-"):$mainHotelSearch->CheckInDate);
$this_CheckOutDate=($mainHotelSearch->CheckOutDate==""||$mainHotelSearch->CheckOutDate==null?getDateYMD_addDay("-",$HotelSearchDayNums):$mainHotelSearch->CheckOutDate);
$this_PageNumber=($mainHotelSearch->PageNumber==""||$mainHotelSearch->PageNumber==null?1:$mainHotelSearch->PageNumber);
$this_PageSize=($mainHotelSearch->PageSize==""||$mainHotelSearch->PageSize==null?$SiteHotelSearch_pagesize:$mainHotelSearch->PageSize);
$this_OrderName=($mainHotelSearch->OrderName==""||$mainHotelSearch->OrderName==null?"Recommend":$mainHotelSearch->OrderName);
$this_OrderType=($mainHotelSearch->OrderType==""||$mainHotelSearch->OrderType==null?"DESC":$mainHotelSearch->OrderType);
?>
<form id="main_hotelsearchRquest" name="main_hotelsearchRquest"
	action="" method="post">
<div class="search_box basefix">
<p class="input_box">入住城市 <dfn>*</dfn><input id="main_Search_CityName"
	name="main_Search_CityName" type="text"
	value="<?php echo $this_CityName;?>"
	class="input_text input_105" /></p>
<p class="input_box">入住日期 <dfn>*</dfn><input
	id="main_Search_CheckInDate" name="main_Search_CheckInDate" type="text"
	value="<?php echo $this_CheckInDate;?>"
	class="input_text input_77" /> 至<input id="main_Search_CheckOutDate"
	name="main_Search_CheckOutDate" type="text"
	value="<?php echo $this_CheckOutDate?>"
	class="input_text input_77" /></p>
<p class="input_box">酒店位置 <input id="main_Search_Location_Zone"
	name="main_Search_Location_Zone" type="text"
	value="<?php echo $mainHotelSearch->locationZoneDistrictShowText;?>"
	class="input_text input_105" /></p>
<p class="input_box">酒店名称 <input id="main_Search_HotelName"
	name="main_Search_HotelName" type="text"
	value="<?php echo $mainHotelSearch->HotelName;?>"
	class="input_text input_87" /></p>

<!-- 以下是隐藏的查询条件，可以用JS控制里面的值 --> 
<div style="<?php echo $textIsHidden?>">
    CITYID<input id="main_Search_CityID"
	name="main_Search_CityID" type="text"
	value="<?php echo $this_CityID;?>" /> PRICE<input
	id="main_Search_Price" name="main_Search_Price"
	type="text"
	value="<?php echo $mainHotelSearch->Price;?>" /> STAR<input
	id="main_Search_Star" name="main_Search_Star"
	type="text"
	value="<?php echo $mainHotelSearch->Star;?>" />PAGESIZE<input
	id="main_Search_pagesize" name="main_Search_pagesize"
	type="text"
	value="<?php echo $this_PageSize;?>" /> HOTELFACILITY<input
	id="main_Search_hotelfacility" name="main_Search_hotelfacility"
	type="text"
	value="<?php echo $mainHotelSearch->HotelFacility;?>" /> HOTELBRAND <input
	id="main_Search_hotelbrand" name="main_Search_hotelbrand"
	type="text"
	value="<?php echo $mainHotelSearch->hotelbrand;?>" /> ORDERNAME<input
	id="main_Search_ordername" name="main_Search_ordername"
	type="text"
	value="<?php echo $this_OrderName;?>" /> ORDERTYPE<input
	id="main_Search_ordertype" name="main_Search_ordertype"
	type="text"
	value="<?php echo $this_OrderType;?>" /> PAGENUMBER<input
	id="main_Search_pagenumber" name="main_Search_pagenumber"
	type="text"
	value="<?php echo $this_PageNumber;?>" />hotailid<input
	id="main_Search_hotailid" name="main_Search_hotailid"
	type="text"
	value="<?php echo $mainHotelSearch->hotelID;?>" />商业区行政区景点坐标<input
	id="barSearch_Location_Zone" name="barSearch_Location_Zone"
	type="text"
	value="<?php echo $mainHotelSearch->locationZone;?>" /> 
	<input id="barSearch_LZId" value="<?php echo $mainHotelSearch->district;?>" type="text" name="barSearch_LZId"/>
	<input id="barSearch_Location_Type" value="<?php echo $mainHotelSearch->districtName;?>" type="text" name="barSearch_Location_Type"/>
	</div>
	<!-- 以上是隐藏的查询条件，可以用JS控制里面的值 -->
	<div class="btn_box float_left"><input type="button"
	value="<?php echo $repostSearchName?>" class="<?php echo $repostSearchClass?>" id="repostSearch"/></div>
</div>


<input type="hidden" name="postDataSearchUrl" id="postDataSearchUrl" value="<?php echo getNewUrl($UnionSite_domainName."/site/hotelsearch.php?city=cityvalue&stb=stbvalue&hname=hnamevalue&lzod=lzodvalue&hf=hfvalue&pf=1,".$SiteHotelSearch_pagesize,$SiteUrlRewriter) ?>">


<?php 
//判断是否显示以下筛选条件
if($isShowMoreSearchCondition==true)
{
?>
<div class="search_cate">
<p id="cate_number"></p>
<!-- 以下的标签进行选择时，先将上面对应的input中的值进行修改，然后触发doSearchMain_commit事件就可以了 --> <!-- 当页面加载后，要根据上面 -->
<dl class="cate_item basefix"  id="starRanger">
	<dt>星级：</dt>
	<dd class="basefix"><!-- 更改类名cate_all,cate_all_current改变状态 --> <a
		href="#" class="cate_all_current clearFilter" title="star">全部</a>
	<ul class="cate_list basefix">
		<!-- 增加类名current改变状态 -->
		<li><a href="#2">二星及以下/经济型</a></li>
		<li><a href="#3">三星级/舒适型</a></li>
		<li><a href="#4">四星级/高档型</a></li>
		<li><a href="#5">五星级/豪华型</a></li>
	</ul>
	</dd>
</dl>
<dl class="cate_item basefix" id="priceRanger">
	<dt>价格：</dt>
	<dd class="basefix"><a href="#" class="cate_all_current clearFilter" title="price">全部</a>
	<ul class="cate_list basefix" class="starFilter">
		<li><a href="#0-149"><dfn>&yen;150</dfn>以下</a></li>
		<li><a href="#150-300"><dfn>&yen;150-300</dfn></a></li>
		<li><a href="#301-450"><dfn>&yen;301-450</dfn></a></li>
		<li><a href="#451-600"><dfn>&yen;451-600</dfn></a></li>
		<li><a href="#600-9999999"><dfn>&yen;600</dfn>以上</a></li>
	</ul>
	</dd>
</dl>
<dl class="cate_item basefix" id="facPanger">
	<dt>设施：</dt>
	<dd class="basefix"><a href="#" class="cate_all_current clearFilter" title="facility">全部</a>
	<ul class="cate_list basefix">
		<li><a href="#BroadNet">宽带</a></li>
		<li><a href="#AirportShuttle">机场接送</a></li>
		<li><a href="#Fitnesscenter">健身中心</a></li>
		<li><a href="#Swimmingpool">游泳池</a></li>
		<li><a href="#Park">停车场</a></li>
		<li><a href="#AirCondition">空调</a></li>
		<li><a href="#Bar_Lounge">酒吧</a></li>
		<li><a href="#Business_center">有商业中心</a></li>
		<li><a href="#Golf">高尔夫</a></li>
		<li><a href="#Poker_Room">棋牌室</a></li>
	</ul>
	</dd>
</dl>
<dl class="cate_item basefix" id="brandRanger">
	<dt>品牌：</dt>
	<dd class="basefix"><a href="#" class="cate_all_current clearFilter" title="brand">全部</a>
	<ul class="cate_list basefix">
		<li><a href="#星程">星程</a></li>
		<li><a href="#如家">如家</a></li>
		<li><a href="#汉庭">汉庭</a></li>
		<li><a href="#莫泰">莫泰</a></li>
		<li><a href="#7天">7天</a></li>
		<li><a href="#锦江之星">锦江之星</a></li>
		<li><a href="#速8">速8</a></li>
		<li><a href="#万豪">万豪</a></li>
		<li><a href="#香格里拉">香格里拉</a></li>
		<li><a href="#希尔顿">希尔顿</a></li>
		<li><a href="#喜达屋">喜达屋</a></li>
		<li><a href="#雅高">雅高</a></li>
		<li><a href="#洲际">洲际</a></li>
		<li><a href="#凯悦">凯悦</a></li>
		<li><a href="#豪生">豪生</a></li>
		<li><a href="#华美达">华美达</a></li>
		<li><a href="#戴斯">戴斯</a></li>
		<li><a href="#锦江">锦江</a></li>
		<li><a href="#首旅建国">首旅建国</a></li>
		<li><a href="#维景">维景</a></li>
		<li><a href="#君澜">君澜</a></li>
		<li><a href="#粤海">粤海</a></li>
		<li><a href="#开元">开元</a></li>
		<li><a href="#星河湾">星河湾</a></li>
		<li><a href="#书香">书香</a></li>
		<li><a href="#布丁">布丁</a></li>
	</ul>
	</dd>
</dl>
</div>
<?php 
}
?>
</form>
<?php 
/**
 * 加载商品详细页中的重新搜索控件
 */
function showHotelDetailReSearch($mainHotelSearch,$UnionSite_domainName)
{
	$coutw=<<<BEGIN
	<p>入住日期<input type="text" id="hotailDetailCheckindate" name="hotailDetailCheckindate" value="$mainHotelSearch->CheckInDate" /></p>
    <p>退房日期<input type="text" id="hotailDetailCheckoutdate" name="hotailDetailCheckoutdate" value="$mainHotelSearch->CheckOutDate" /></p>
    <input type="button" value="修改" onclick="dohotailDetailResearch('$UnionSite_domainName')" class="btn_m_gray" />
BEGIN;
    return  $coutw;
}
?>
