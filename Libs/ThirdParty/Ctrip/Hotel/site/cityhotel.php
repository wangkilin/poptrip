<?php
/**
 * 处理城市酒店的页面
 */
include_once("../Common/toolExt.php");
include_once ("../Common/getDate.php");//加载日期构造函数
include_once ("../include/siteAd.php");//加载广告的处理逻辑
include_once ("../appData/site.config.php");//加载整站系统的配置文件
include_once("../include/urlRewrite.php");//加载URL伪静态处理
include_once ("../include/indexConfig.php");//加载首页的控制文件

$index_cityID=setDefaultCityID($SiteDefaultCityID);//当前页面上默认的展示城市
$index_cssurl=$UnionSite_domainName."/site/css/styles.css";//本页面的CSS样式地址
$position_jsurl = $UnionSite_domainName."/site/js/position.js";
$top_jsurl = $UnionSite_domainName."/site/js/topsearch.js";
$citylist_jsurl = $UnionSite_domainName."/site/js/citylist.js";

//城市酒店中，点击城市后，直接到酒店的搜索页面上
$hotelSearchUrl=$UnionSite_domainName."/site/hotelsearch.php?city={cityId},{cityName}&cdate={checkInDate},{checkOutDate}&stb=;&price=&hname=&lzod=,-,-,&hf=&oy=Recommend,DESC&pf=1,".$SiteHotelSearch_pagesize;


//加载页面关键字,引入main_keywords.php页面
include_once 'module/main_keywords.php';
$searchArr=array('{sitename}');
$replaceArr=array("$UnionSite_Name");
$kw=autoLoadKeywords('cityhotel.php',$searchArr,$replaceArr);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cn">
<head>
<meta http-equiv=Content-Type content="text/html; charset=<?php  echo $SiteCharset;?>"/>
<title><?php echo  $kw['title'];?></title>
<meta name="keywords" content="<?php echo $kw['keywords'];?>" />
<meta name="description" content="<?php echo  $kw['description'];?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $index_cssurl?>" />

<?php if($UnionSite_Css){?>
<link rel="stylesheet" type="text/css" href="<?php echo $UnionSite_domainName.$UnionSite_Css?>" />

<?php }?>
</head>
<body>
<?php include_once 'module/header.php';//加载头部文件 ?>
<?php include_once 'module/bar_navigation.php';//加载导航文件 
 echo getMainNavigation();//加载默认的导航
?>
<!-- bd begin -->
<div class="bd bd_city">
<div class="path_bar">
<?php 
//构造搜索页的副导航
echo getSubTitleNavigation("cityhotel","");
?></div>
<?php 
$isShowMoreSearchCondition =false;//控制搜索器：是否显示星级+价格+品牌等筛选条件，true 表示筛选
$searcherButtonClass="searcher";//控制搜索器：控制搜索按钮的样式及名称--searcher[btn_orange,搜 索];researcher[btn_mid,重新搜索]
include_once 'module/main_hotelSearch_term.php';//加载搜索页面的条件选择器 ?>
<div class="city_box city_box_blue" id="hot_city_list"></div>
<div class="city_box city_box_gray"><span>拼音检索：</span><a href="#">ABCD</a><a
	href="#">EFGHIJ</a><a href="#">KLMN</a><a href="#">PQRSTW</a><a href="#">XYZ</a></div>
<ul class="city_sort basefix" id="sub_city_list">
</ul>
</div>
<!-- bd end -->
<?php include_once 'module/foot.php';//加载底部控制文件 ?>
<script type="text/javascript" src="http://webresource.ctrip.com/code/cquery/cQuery_110421.js"></script>
<script type="text/javascript" src="<?php echo $position_jsurl?>"></script>
<script type="text/javascript" src="<?php echo $top_jsurl?>"></script>
<script type="text/javascript" src="<?php echo $citylist_jsurl?>"></script>
<script type="text/javascript" src="<?php echo $UnionSite_domainName?>/site/js/CtripSelfDefined.js"></script>
</body>
</html>
