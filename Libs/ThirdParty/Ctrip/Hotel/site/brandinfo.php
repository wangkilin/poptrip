<?php
/**
 * 处理品牌的简介，城市的分布
 */
include_once ('../SDK.config.php');//配置文件加载--必须加载这个文件
include_once ("../include/siteAd.php");//加载广告的处理逻辑
include_once ("../appData/site.config.php");//加载整站系统的配置文件
include_once("../include/urlRewrite.php");//加载URL伪静态处理
include_once ("../include/indexConfig.php");//加载首页的控制文件
$index_cityID=setDefaultCityID($SiteDefaultCityID);//当前页面上默认的展示城市
$index_cssurl=$UnionSite_domainName."/site/css/styles.css";//本页面的CSS样式地址
$index_jsurl = $UnionSite_domainName."/site/js/hotelSearch.js";
$position_jsurl = $UnionSite_domainName."/site/js/position.js";
$top_jsurl = $UnionSite_domainName."/site/js/topsearch.js";
$brand=$_GET['brand'];//获取到品牌的ID
$brandCnName=$_GET['brandcnname'];//当前页面上的品牌的中文名称


//品牌的城市分布
include_once (ABSPATH.'sdk/API/Hotel/D_GetBrandCityRequest.php');//加载D_GetBrandCityRequest这个接口的封装类
include_once (ABSPATH.'site/module/main_BrandCityRequest.php');//加载品牌的城市分布处理逻辑
//调用品牌的城市分布接口
$BrandCityRequest=new get_BrandCityRequest($brand);
$BrandCityRequestXML=$BrandCityRequest->responseXML;

//加载页面关键字,引入main_keywords.php页面
include_once 'module/main_keywords.php';
$searchArr=array('{sitename}','{brand}');
$replaceArr=array("$UnionSite_Name","$brandCnName");
//$kw=autoLoadKeywords('index.php');//自动加载页面关键字
$kw=autoLoadKeywords('brandinfo.php',$searchArr,$replaceArr);

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
echo getSubTitleNavigation("brandinfo",$brandCnName);
?></div>
<?php
$isShowMoreSearchCondition =false;//控制搜索器：是否显示星级+价格+品牌等筛选条件，true 表示筛选
$searcherButtonClass="searcher";//控制搜索器：控制搜索按钮的样式及名称--searcher[btn_orange,搜 索];researcher[btn_mid,重新搜索]
include_once 'module/main_hotelSearch_term.php';//加载搜索页面的条件选择器 ?> <?php
include ('module/brand_logic.php');
if ($brand){
	$brandEntity=getBrandInfo($brand);
	//echo print_r($brandEntity);
	if ($brandEntity){
		$brandCnNameVaule=$brandEntity['BrandCNName'];//品牌的名称
		$brandId=$brandEntity['Brand'];//品牌的ID
		?>
<div class="main">
<div class="brand_title"><img width="40" height="40" src="http://pic.c-ctrip.com/common/pic_alpha.gif"
	style="background:url('<?php echo 'http://pic.ctrip.com/hotels110127/brandimage/'.$brandId.'.jpg';?>') no-repeat;"
	alt="<?php echo $brandCnNameVaule;?>" />
<h2><?php echo $brandCnNameVaule; ?></h2>
<?php 	$city=hotelCityDistribution($brand, $brandCnNameVaule,$BrandCityRequestXML);?>
<span><?php 
if($city['count']!=""&&$city['count']!=null)
{ 
echo "（共".$city['count']."家）";
}
?></span></div>
<div class="brand_hotel_intro"><?php echo $brandEntity['Description'];?></div>
		<?php
		//$brandDetailUrl=$UnionSite_domainName."/site/brandDetail.php?brand={brand}&brandcnname={brandcnname}&city={city}";
		//echo $brandDetailUrl;
		if ($city){
			if ($city['inner']){//显示国内酒店分布城市
				?>
<div class="box_blue">
<h3><?php echo $brandCnNameVaule;?>国内城市</h3>
<div class="content">
<p>选择入住城市，可筛选查看该城市国内的所有<?php echo $brandCnNameVaule;?>酒店。</p>
				<?php
				foreach ($city['inner'] as $v){
					echo "<span><a href='".getNewUrl($UnionSite_domainName."/site/branddetail.php?brand=$brand&brandcnname=$brandCnNameVaule&city=".$v['City'].",".$v['CityName'],$SiteUrlRewriter)."'>".$v['CityName'].$brandCnNameVaule."酒店预定</a></span>";
				}
				?></div>
</div>

				<?php
			}
			if ($city['out']){//显示海外酒店分布城市
				?>
<div class="box_blue">
<h3><?php echo $brandCnNameVaule;?>海外城市</h3>
<div class="content">
<p>选择入住城市，可筛选查看该城市海外的所有<?php echo $brandCnNameVaule;?>酒店。</p>
				<?php
				foreach ($city['out'] as $v){
					echo "<span>".utf_substr($v['CityName'], 8).$brandCnNameVaule."酒店</span>";
				}
				?></div>
</div>
				<?php
			}
		}


		?></div>
		<?php
		$similarBrands=getSimilarBrands($brand);
		if (!empty($similarBrands)){//显示同类其他酒店品牌
			include_once("module/brand_right.php");
		}
	}
}
?></div>
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