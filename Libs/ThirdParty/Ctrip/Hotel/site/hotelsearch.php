<?php
/*
 * 负责系统常规酒店的搜索功能
 */
header("Content-type: text/html; charset=utf-8"); 
include_once ("../Common/browseHistoryClass.php");//加载浏览记录的方法
include_once ("../include/siteAd.php");//加载广告的处理逻辑
include_once ("../appData/site.config.php");
include_once ("../include/urlRewrite.php");//加载URL伪静态处理
include_once ("../SDK.config.php");//配置文件加载--必须加载这个文件
include_once ("../include/SubPages.php");//加载分页类
include_once ("../include/url_HotelControl.php");//加载酒店URL路径控制
include_once (ABSPATH."Common/toolExt.php");

include_once (ABSPATH."sdk/API/Hotel/D_HotelList.php");//加载D_HotelSearch这个接口的封装类
include_once ("module/main_D_hotelSearch.php");//加载搜索的主逻辑



//设置当前页面的参数
$index_cssurl=$UnionSite_domainName."/site/css/styles.css";//本页面的CSS样式地址
$index_jsurl = $UnionSite_domainName."/site/js/hotelSearch.js";
$position_jsurl = $UnionSite_domainName."/site/js/position.js";
$top_jsurl = $UnionSite_domainName."/site/js/topsearch.js";

$postDatas=explode('&', $_POST['passvalue']);


//获取时间
$postCdates=explode('=',$postDatas['0']);
$cdate=empty($postCdates["1"])?getDateYMD("-").",".getDateYMD_addDay("-",$HotelSearchDayNums):$postCdates["1"];;

//获取价格区间
$postPrices=explode('=',$postDatas['3']);
$price=$postPrices['1'];

//获取排序
$postOys=explode('=',$postDatas['7']);
$oy=$postOys['1'];




//获取符合条件的酒店列表
$mainHotelSearch=new page_D_hotelSearch();
$mainHotelSearch->SiteHotelDefaultImageUrlHotelSearch=$SiteHotelDefaultImageUrl;//定义酒店列表中，默认的图片地址
$mainHotelSearch->isSiteUrlRewriter=$SiteUrlRewriter;//设置本系统是否要做伪静态
$mainHotelSearch->thisUnionSite_domainName=$UnionSite_domainName;//设置系统的域名
$mainHotelSearch->orderShowUrl=$UnionSite_domainName."/site/orderdetail.php";//设置订单的查询页面地址
$mainHotelSearch->cdate=$cdate;
$mainHotelSearch->price=$price;
$mainHotelSearch->oy=$oy;
$mainHotelSearch->RequestType="get_D_HotelList";


$mainHotelSearch->getRequsetParameter();//获取参数

$city=$mainHotelSearch->CityName;//选择的城市名称
$star=getStarInfo($mainHotelSearch->Star);//获取酒店星级
$position=analysisHotelPosition($mainHotelSearch->locationZone);//酒店位置
$hotelName=$mainHotelSearch->HotelName;//获取酒店名
$pricestr=$mainHotelSearch->Price;//获取价格范围
$price='';
$temp=explode('-', $pricestr);
if (count($temp)==2){
	if ($temp[0]==0)
		$price=$temp[1].'元以下';
	else if($temp[1]==9999999)
		$price=$temp[0].'元起';
	else 
		$price=$temp[0].'元至'.$temp[1].'元';	 
} 
$brand=$mainHotelSearch->hotelbrand;//获取酒店品牌
//加载页面关键字,引入main_keywords.php页面
include_once 'module/main_keywords.php';
$searchArr=array('{sitename}','{city}','{position}','{star}','{hotelname}','{price}','{brand}');
$replaceArr=array("$UnionSite_Name","$city","$position","$star","$hotelName","$price","$brand");
$kw=autoLoadKeywords('hotelsearch.php',$searchArr,$replaceArr);


?>
<?php 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cn">
<head>
<meta http-equiv=Content-Type content="text/html; charset=<?php  echo $SiteCharset;?>"/>
<title><?php echo  $kw['title'];?></title>
<meta name="keywords" content="<?php echo $kw['keywords'];?>" />
<meta name="description" content="<?php echo  $kw['description'];?>" />

<link rel="stylesheet" type="text/css"
	href="<?php echo $index_cssurl;?>" />
	
<?php if($UnionSite_Css){?>
<link rel="stylesheet" type="text/css" href="<?php echo $UnionSite_domainName.$UnionSite_Css?>" />

<?php }?>	
	
<script>
/**
 * 当index页面上的城市切换时，要做页面的跳转
 */
function doInputPageNumChanage(urlhost,totalPageNum)
{
	var inputPageNums=document.getElementById("inputPageNums").value;
	//要对输入的值做数字校验.如果输入的是非法的字符，则页面不做跳转，将输入的数据直接替换成原本设置的页码
	//输入值从1开始 
	if(isNaN(inputPageNums) ||  inputPageNums<1 ){
		alert("请输入大于0的整数");
		return false;	
	}
	if(totalPageNum<inputPageNums)
	{
		inputPageNums=totalPageNum;
	}
	var url=urlhost.replace("...",inputPageNums);
	CtripSelfPassParams('POST',  url,$('#postData').value(), '_self')
	//window.open(url,"_self");
}
</script>
</head>
<body>
<?php include_once 'module/header.php';//加载头部文件 ?>
<?php include_once 'module/bar_navigation.php';//加载导航文件
echo getMainNavigation();//加载默认的导航
?>
<!-- bd begin -->
<div class="bd bd_search_result">
<div class="path_bar"><?php 
//构造搜索页的副导航
if (!empty($mainHotelSearch->hotelbrand)||!empty($hotelName)||!empty($position)||!empty($star)||!empty($mainHotelSearch->HotelFacility)||!empty($price)){	
	//构造URL地址--列表的地址
	$hotelSearchUrl=new HotelUrlControl($mainHotelSearch->cityid.",".$mainHotelSearch->CityName, $mainHotelSearch->CheckInDate.",".$mainHotelSearch->CheckOutDate,";", "", "", '', '', $mainHotelSearch->OrderName.",".$mainHotelSearch->OrderType, "1,".$mainHotelSearch->PageSize, "list");
	//echo $hotelSearchUrl->returnUrl;
	$tempInfo="<a href='".getNewUrl($hotelSearchUrl->returnUrl,$SiteUrlRewriter)."'>".$city."酒店</a>";
	if ((!empty($position)||!empty($star))&&(!empty($mainHotelSearch->hotelbrand)||!empty($hotelName)||!empty($mainHotelSearch->HotelFacility)||!empty($price))){
		$hotelSearchUrl=new HotelUrlControl($mainHotelSearch->cityid.",".$mainHotelSearch->CityName, $mainHotelSearch->CheckInDate.",".$mainHotelSearch->CheckOutDate,$mainHotelSearch->Star."", "", "", $mainHotelSearch->locationZone, '', $mainHotelSearch->OrderName.",".$mainHotelSearch->OrderType, "1,".$mainHotelSearch->PageSize, "list");
		$tempInfo.="&gt;<a href='".getNewUrl($hotelSearchUrl->returnUrl,$SiteUrlRewriter)."'>".$position.$star."酒店</a>&gt;".$mainHotelSearch->hotelbrand." ".cnHotelFacility($mainHotelSearch->HotelFacility).$price."酒店";
	}else{
		if (empty($position)&&empty($star)){
			$tempInfo.="&gt;".$mainHotelSearch->hotelbrand." ".cnHotelFacility($mainHotelSearch->HotelFacility).$price."酒店";
		}else{
		$tempInfo.="&gt;".$position.$star."酒店";
		}
	}

}
else{
	$tempInfo=$city."酒店";
}


	 
echo getSubTitleNavigation("hotelsearch",$tempInfo);
?></div>
<?php
//搜索器的加载
$isShowMoreSearchCondition =true;//控制搜索器：是否显示星级+价格+品牌等筛选条件，true 表示筛选
$searcherButtonClass="researcher";//控制搜索器：控制搜索按钮的样式及名称--searcher[btn_orange,搜 索];researcher[btn_mid,重新搜索]
include_once 'module/main_hotelSearch_term.php';//加载搜索页面的条件选择器

//构造URL地址--列表的地址
$hotelSearchUrl=new HotelUrlControl($mainHotelSearch->cityid.",".$mainHotelSearch->CityName, $mainHotelSearch->CheckInDate.",".$mainHotelSearch->CheckOutDate, $mainHotelSearch->Star.";".$mainHotelSearch->hotelbrand, $mainHotelSearch->Price, $mainHotelSearch->HotelName, $mainHotelSearch->locationZone, $mainHotelSearch->HotelFacility, $mainHotelSearch->OrderName.",".$mainHotelSearch->OrderType, "...,".$mainHotelSearch->PageSize, "list");
$pageUrl=$hotelSearchUrl->returnUrl; 
$pageUrl=getNewUrl($pageUrl,$SiteUrlRewriter);


$orderTypeArr=explode(',',$oy);
$orderType=$orderTypeArr['0'];

?>
<div class="search_content basefix">
<div class="search_main">
<div class="tab basefix" id="sortHolder"><!-- 增加类名current改变状态;更改span的类名改变箭头的样式:tab_tri,tab_tri_up,tab_tri_down -->
<!--OrderType-- DESC 降序,ASC 升序 --> <a href="#Recommend"
	style="padding: 0 16px;" class="suggest <?php if($orderType=='Recommend')echo "current";?>">网站推荐</a> <a
	href="#Star" class="Star <?php if($orderType=='Star')echo "current";?>">星级排序<span class="tab_tri_up"></span></a> <a
	href="#MinPrice" class="MinPrice <?php if($orderType=='MinPrice')echo "current";?>">价格排序<span class="tab_tri_up"></span></a> <a
	href="#HRatingOverall" class="HRatingOverall <?php if($orderType=='HRatingOverall')echo "current";?>">评价排序<span
	class="tab_tri_down"></span></a></div>
<p class="search_main_page"><?php 
//加载  “上一页 8/57下一页 ”样式的分页控件
ob_flush(); //缓存输出，先将上面部分输出
flush();
$mainHotelSearch->getHotelListResponseXML_URL();//调用酒店列表的接口
$subPages5=new SubPages($mainHotelSearch->PageSize,$mainHotelSearch->responseTotalNum,$mainHotelSearch->PageNumber,5,$pageUrl,7);
?></p>
<div  id='ss'></div>

<input type='hidden' id="hotelNums" value="<?php echo $mainHotelSearch->responseTotalNum;?>" /> 

<script type="text/javascript">
document.getElementById("cate_number").innerHTML="<span class='cate_number'>"+document.getElementById("hotelNums").value +"</span>家酒店" ;
</script>

<ul class="search_result_list">
	<!-- 1.js 要处理mod_jmpinfo_page 这个功能，和大系统一致 -->
<?php
 echo $mainHotelSearch->responseHotelListHtml;//返回符合条件的所有的酒店列表HTML
 
?>
</ul>

<div class="basefix"><!-- 更改a标签的类名来改变状态 -->
<div class="page_ctrl basefix"><?php 
//加载底部的分页控件
if(!empty($mainHotelSearch->responseTotalNum) && $mainHotelSearch->responseTotalNum!='0')
{
	$subPages4=new SubPages($mainHotelSearch->PageSize,$mainHotelSearch->responseTotalNum,$mainHotelSearch->PageNumber,5,$pageUrl,8);
	?>
<div class="page_value"><span>到</span> <input class="input_text"
	type="text" id="inputPageNums" name="inputPageNums"
	value="<?php echo $mainHotelSearch->PageNumber;?>"> <span>页</span> <input
	class="submit" type="button" name=""
	onclick="doInputPageNumChanage('<?php echo $pageUrl;?>',<?php echo $subPages4->pageNums;?>)"
	value="确定"></div>
	<?php
}
?></div>

<input  type="hidden" name="hotelIdList" id="hotelIdList" value="<?php echo $mainHotelSearch->hotelIdList?>">
</div>


</div>
<div class="search_side">
<?php include_once 'module/main_todaySale.php';//加载今日热卖?>
<?php include_once 'module/main_history.php';//加载历史浏览?> 
<?php include_once 'module/main_landmarks.php';//加载地标信息?>
</div>
</div>
</div>
<div id="mask" style="display:none;"></div>
<div id="iframeLoader" style="display:none;"><div id="iframecontent"></div><a href="#" id="closeLoader">关闭</a></div>
<!-- bd end -->
<div class="base_pop mask_box_shadow" id="pop_map" style="width:640px;height:auto;display:none;">
    <div class="pop_hd"><a class="delete" id="delMap" href="javascript:void(0);">×</a><h3>地图/交通</h3></div>
    <div class="pop_bd" id="innermap" style="height:450px;width:640px; padding:0; overflow:hidden;"></div>
    <!-- 周边交通 -->
    <div id="pop_traffic_info" class="pop_bd">
	    <div class="map_traffic_t"><span>交通信息</span></div>
	    <table width="100%" cellspacing="0" cellpadding="0" class="detail_extralist detail_extralist2"><tbody></tbody></table>
	    <div id="pop_traffic_load" style="padding:10px;background-color:rgb(255, 255, 255);">
            <img src="http://pic.c-ctrip.com/common/loading_50.gif">
        </div>
    </div>
</div>
<?php include_once 'module/foot.php';//加载底部控制文件 ?>



<input type="hidden" name="postData" id="postData" value="<?php echo $_POST['passvalue']?>">


<script type="text/javascript"
	src="http://webresource.ctrip.com/code/cquery/cQuery_110421.js"></script>
	<?php if($MapKey){?>
<script type="text/javascript" src="http://open.mapbar.com/apis/maps/free?<?php echo $MapKey?>"></script>
<?php }?>
<script type="text/javascript" src="<?php echo $UnionSite_domainName?>/site/js/json.js"></script>
<script type="text/javascript" src="<?php echo $position_jsurl?>"></script>
<script type="text/javascript" src="<?php echo $top_jsurl?>"></script>
<script type="text/javascript" src="<?php echo $index_jsurl?>"></script>
 <script type="text/javascript" src="<?php echo $UnionSite_domainName?>/site/js/CtripSelfDefined.js"></script>

</body>
</html>
