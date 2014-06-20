<?php
/**
 * 品牌酒店
 */
include_once ("../Common/toolExt.php");//加载常用函数
include_once ("../Common/getDate.php");//加载日期构造函数
include_once ("../include/siteAd.php");//加载广告的处理逻辑
include_once ("../appData/site.config.php");
include_once("../include/urlRewrite.php");//加载URL伪静态处理
$index_jsurl = $UnionSite_domainName."/site/js/hotelSearch.js";
$position_jsurl = $UnionSite_domainName."/site/js/position.js";
$top_jsurl = $UnionSite_domainName."/site/js/topsearch.js";
$index_cssurl=$UnionSite_domainName."/site/css/styles.css";//本页面的CSS样式地址


//加载页面关键字,引入main_keywords.php页面
include_once 'module/main_keywords.php';
$searchArr=array('{sitename}');
$replaceArr=array("$UnionSite_Name");
$kw=autoLoadKeywords('brand.php',$searchArr,$replaceArr);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cn">
<head>
<meta http-equiv=Content-Type content="text/html; charset=<?php  echo $SiteCharset;?>"/>
<title><?php echo  $kw['title'];?></title>
<meta name="keywords" content="<?php echo $kw['keywords'];?>" />
<meta name="description" content="<?php echo  $kw['description'];?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $index_cssurl;?>" />
<?php if($UnionSite_Css){?>
<link rel="stylesheet" type="text/css" href="<?php echo $UnionSite_domainName.$UnionSite_Css?>" />

<?php }?>

</head>
<body>
<?php 
include_once 'module/header.php';//加载头部文件 ?>
<?php include_once 'module/bar_navigation.php';//加载导航文件
echo getMainNavigation();//加载默认的导航
?>
<!-- bd begin -->
<div class="bd bd_brand">
<div class="path_bar"><?php 
//构造搜索页的副导航
echo getSubTitleNavigation("brand","");
?></div>
<?php 
$isShowMoreSearchCondition =false;//控制搜索器：是否显示星级+价格+品牌等筛选条件，true 表示筛选
$searcherButtonClass="searcher";//控制搜索器：控制搜索按钮的样式及名称--searcher[btn_orange,搜 索];researcher[btn_mid,重新搜索]
include_once 'module/main_hotelSearch_term.php';//加载搜索页面的条件选择器 ?>
<?php include_once 'module/brand_list.php';//加载品牌酒店列表?></div>
<!-- bd end -->

<?php include_once 'module/foot.php';//加载底部控制文件 ?>
<script type="text/javascript"
	src="http://webresource.ctrip.com/code/cquery/cQuery_110421.js"></script>
<script type="text/javascript" src="<?php echo $position_jsurl?>"></script>
<script type="text/javascript" src="<?php echo $top_jsurl?>"></script>
<script type="text/javascript" src="<?php echo $UnionSite_domainName?>/site/js/CtripSelfDefined.js"></script>
</body>
</html>
