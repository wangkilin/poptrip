<?php
/**
 * 系统首页
 */

include_once ("../Common/toolExt.php");//加载常用函数
include_once ("../appData/site.config.php");//加载整站系统的配置文件
include_once ("../include/siteAd.php");//加载广告的处理逻辑
include_once ("../include/indexConfig.php");//加载首页的控制文件

$index_cityID=setDefaultCityID($SiteDefaultCityID);//当前页面上默认的展示城市
if(empty($_GET['defaultcityid'])){
	$defaultcityid301=$SiteDefaultCityID.",".$SiteDefaultCityName;
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: index.php?defaultcityid=".$defaultcityid301);
	exit(); 
}

include_once("../include/urlRewrite.php");//加载URL伪静态处理
$index_cssurl=$UnionSite_domainName."/site/css/styles.css";//本页面的CSS样式地址
$index_jsurl = $UnionSite_domainName."/site/js/main.js";
$position_jsurl = $UnionSite_domainName."/site/js/position.js";

//加载页面关键字,引入main_keywords.php页面
include_once 'module/main_keywords.php';
$searchArr=array('{sitename}');
$replaceArr=array("$UnionSite_Name");
$kw=autoLoadKeywords('index.php',$searchArr,$replaceArr);
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
 echo  getMainNavigation();//加载默认的导航?>
<!-- bd begin -->
<div class="bd bd_index basefix"><!-- main bengin -->
<div class="main">
<?php include_once 'module/bar_hotcitylandmark.php';//加载热门城市地标分类文件 ?>
<?php include_once 'module/bar_recommendedhotels.php';//加载推荐酒店文件 ?> 
<?php include_once 'module/bar_hotelcomment.php';//加载酒店最新评论文件 ?>
</div>
<!-- main end --> <!-- side begin -->
<div class="side">
<?php include_once 'module/bar_search.php';//加载酒店的搜索模板文件 ?>
<?php include_once 'module/bar_tophotal.php';//加载酒店排行榜文件 ?> 
<?php include_once 'module/bar_brandhotels.php';//加载品牌酒店文件 ?>
</div>
</div>
<!-- bd end -->
<div class="ad_ft basefix">
<?php
//显示底部广告（头部广告在module/header.php中处理）
$siteAdRequest->getAdLinks("index_foot");
echo $siteAdRequest->responseHtml;?>
</div>
<?php include_once 'module/friendlink.php';//加载友情链接文件 ?>
<?php include_once 'module/foot.php';//加载底部控制文件 ?>
<input type="hidden" name="postDataSearchUrl" id="postDataSearchUrl" value="<?php echo getNewUrl($UnionSite_domainName."/site/hotelsearch.php?city=cityvalue&stb=stbvalue&hname=hnamevalue&lzod=lzodvalue&hf=hfvalue&pf=1,".$SiteHotelSearch_pagesize,$SiteUrlRewriter) ?>">
<script type="text/javascript" src="http://webresource.ctrip.com/code/cquery/cQuery_110421.js"></script>
<script type="text/javascript" src="<?php echo $position_jsurl?>"></script>
<script type="text/javascript" src="<?php echo $index_jsurl?>"></script>
<script type="text/javascript" src="<?php echo $UnionSite_domainName?>/site/js/CtripSelfDefined.js"></script>

</body>
</html>