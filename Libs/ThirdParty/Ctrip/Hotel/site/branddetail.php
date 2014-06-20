<?php
/**
 * 处理品牌在指定城市中的详细门店分布  city=2,上海&brand=35
 */
include_once ('../SDK.config.php');//配置文件加载--必须加载这个文件
include_once ("../include/siteAd.php");//加载广告的处理逻辑
include_once ("../appData/site.config.php");//加载整站系统的配置文件
include_once (ABSPATH.'sdk/API/Hotel/D_HotelList.php');//加载D_HotelSearch这个接口的封装类
include_once (ABSPATH.'site/module/List_hotelSearch.php');//加载搜索的主逻辑
include_once(ABSPATH."include/urlRewrite.php");//加载URL伪静态处理
include_once (ABSPATH."include/url_HotelControl.php");//加载酒店URL路径控制
$index_jsurl = $UnionSite_domainName."/site/js/hotelSearch.js";
$position_jsurl = $UnionSite_domainName."/site/js/position.js";
$top_jsurl = $UnionSite_domainName."/site/js/topsearch.js";
$index_cssurl=$UnionSite_domainName."/site/css/styles.css";//本页面的CSS样式地址
$brand=$_GET['brand'];//获取到品牌的ID
$brandCnName=$_GET['brandcnname'];//当前页面上的品牌的中文名称

//酒店热门城市详细门店分布（仅获取数据实现功能）
$CityID=$_GET['city']?$_GET['city']:$SiteDefaultCityID.",".$SiteDefaultCityName;//获取城市的ID，默认是上海
$CityArray=explode(",",$CityID);
$cityIDValue="";//城市的ID
$cityName="";//城市的名称
if(count($CityArray)>0)
{
	$cityIDValue=$CityArray[0];
	$cityName=$CityArray[1];
}

$HotelBrand=$_GET['brand']?$_GET['brand']:"25";//默认35

if(strlen($CityID)>0 && $HotelBrand)
{
	$List_hotelSearch=new List_hotelSearch($CityID,'100',$HotelBrand,'','2');

}

//加载页面关键字,引入main_keywords.php页面
include_once 'module/main_keywords.php';
$searchArr=array('{sitename}','{city}','{brand}');
$replaceArr=array("$UnionSite_Name","$cityName","$brandCnName");
//$kw=autoLoadKeywords('index.php');//自动加载页面关键字
$kw=autoLoadKeywords('branddetail.php',$searchArr,$replaceArr);
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
<div class="bd bd_brand basefix">
<div class="path_bar"><?php 
//构造搜索页的副导航
echo getSubTitleNavigation("branddetail",$brand.",".$brandCnName); 
?></div>
<?php 
$isShowMoreSearchCondition =false;//控制搜索器：是否显示星级+价格+品牌等筛选条件，true 表示筛选
$searcherButtonClass="searcher";//控制搜索器：控制搜索按钮的样式及名称--searcher[btn_orange,搜 索];researcher[btn_mid,重新搜索]
include_once 'module/main_hotelSearch_term.php';//加载搜索页面的条件选择器 ?>
<div class="main"><div class="brand_title"><img width="40" height="40" src="http://pic.c-ctrip.com/common/pic_alpha.gif" style="background:url('http://pic.ctrip.com/hotels110127/brandimage/<?php echo $brand;?>.jpg ') no-repeat;"
	alt="<?php  echo $brandCnName;?>" /><h2><?php  echo $cityName.$brandCnName;?>预订</h2><span>（共<?php echo  $List_hotelSearch->returnXML->HotelList->TotalItems? $List_hotelSearch->returnXML->HotelList->TotalItems:0;?>家）</span>
</div>
<div class="box_blue basefix">
<h3><?php  echo $cityName.$brandCnName;?>详细门店分布</h3>
<?php $List_hotelSearch->isSiteUrlRewriter=$SiteUrlRewriter;//设置伪静态的参数
  echo $List_hotelSearch->BrandHotelListHtml;//返回符合条件的所有的酒店列表HTML?>
</div></div><?php
include ('module/brand_logic.php');	
if ($brand){
	$similarBrands=getSimilarBrands($brand);
	if (!empty($similarBrands)){//显示同类其他酒店品牌
		include_once("module/brand_right.php");
	}	
}?></div>
<!-- bd end -->
<?php include_once 'module/foot.php';//加载底部控制文件 ?>
 <script type="text/javascript"
	src="http://webresource.ctrip.com/code/cquery/cQuery_110421.js"></script>
<script type="text/javascript" src="<?php echo $position_jsurl?>"></script>
<script type="text/javascript" src="<?php echo $top_jsurl?>"></script>
<script type="text/javascript" src="<?php echo $index_jsurl?>"></script>
<script type="text/javascript" src="<?php echo $UnionSite_domainName?>/site/js/CtripSelfDefined.js"></script>
</body>
</html>




