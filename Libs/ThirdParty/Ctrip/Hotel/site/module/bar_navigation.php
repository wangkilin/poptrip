<?php
/**
 * 系统的导航（主导航，副导航）
 */

/**
 * @var 系统的主导航
 * 根据当前页面的地址，处理头部导航
 */
function getMainNavigation()
{
$isSelectClass="class=\"current\"";//表示选中的页卡
$isSelect_index="";
$isSelect_brand="";
$isSelect_cityhotel="";
$isSelect_hotelcomment="";
$isSelect_citylandmark="";

$isSelect_recentURL=$_SERVER["REQUEST_URI"];
if(strpos($isSelect_recentURL,"index")>0||strpos($isSelect_recentURL,"hotelSearch")>0||strpos($isSelect_recentURL,"hoteldetail")>0){ $isSelect_index=$isSelectClass;}
if(strpos($isSelect_recentURL,"brand")>0){ $isSelect_brand=$isSelectClass;}
if(strpos($isSelect_recentURL,"cityhotel")>0){ $isSelect_cityhotel=$isSelectClass;}
if(strpos($isSelect_recentURL,"hotelcomment")>0){ $isSelect_hotelcomment=$isSelectClass;}
if(strpos($isSelect_recentURL,"citylandmark")>0){ $isSelect_citylandmark=$isSelectClass;}

global $UnionSite_domainName,$SiteUrlRewriter,$SiteDefaultCityName,$SiteDefaultCityID;
$defaultCityValue="?defaultcityid=".$SiteDefaultCityID.",".$SiteDefaultCityName;//定义默认的城市ID,NAME
$indexUrl=getNewUrl($UnionSite_domainName."/site/index.php".$defaultCityValue,$SiteUrlRewriter);
$brandUrl=getNewUrl($UnionSite_domainName."/site/brand.php".$defaultCityValue,$SiteUrlRewriter);
$cityhotelUrl=getNewUrl($UnionSite_domainName."/site/cityhotel.php".$defaultCityValue,$SiteUrlRewriter);
$hotelcommentUrl=getNewUrl($UnionSite_domainName."/site/hotelcomment.php".$defaultCityValue,$SiteUrlRewriter);
$citylandmarkUrl=getNewUrl($UnionSite_domainName."/site/citylandmark.php".$defaultCityValue,$SiteUrlRewriter);

$coutw=<<<BEGIN
<div class="nav">
		<div class="nav_box"> 
			<!-- 增加类名current改变状态 -->
			<a href="$indexUrl" title="首页" $isSelect_index >首页<span class="tri"></span></a>
			<a href="$brandUrl" title="品牌酒店"   $isSelect_brand;>品牌酒店<span class="tri"></span></a>
			<a href="$cityhotelUrl" title="城市酒店"  $isSelect_cityhotel;>城市酒店<span class="tri"></span></a>
			<a href="$hotelcommentUrl" title="酒店点评" $isSelect_hotelcomment;>酒店点评<span class="tri"></span></a>
			<a href="$citylandmarkUrl" title="城市地标" $isSelect_citylandmark;>城市地标<span class="tri"></span></a>
		</div>
	</div>
<!-- 以上为主目录导航 -->
BEGIN;
return  $coutw;
}

/**
 * 
 * @var 系统的副导航:$navigationType=hotelSearch(酒店搜索页面);
 * @var $navigationType=hotelDetail(酒店详情页面);
 * $tempInfo=需要在链接上显示的信息（例如城市名称）
 * 根据不同的页面统一管理副导航
 */
function getSubTitleNavigation($navigationType,$tempInfo)
{
	global $UnionSite_domainName,$SiteUrlRewriter;
	$coutw="";//返回的值
	$indexUrl=getNewUrl($UnionSite_domainName."/site/index.php",$SiteUrlRewriter);//获取首页的地址
	$index="<a href='$indexUrl' target='_self'>首页</a>";//首页的链接
	$domestichotel="<a href='$indexUrl' target='_self'>国内酒店</a>";//国内酒店
	$brandUrl=getNewUrl($UnionSite_domainName."/site/brand.php",$SiteUrlRewriter);//获取品牌列表的地址
	$brandHref="<a href='$brandUrl' target='_self'>品牌酒店列表</a>";//品牌酒店

	if($navigationType=="hotelsearch")
	{
		$coutw=$index."&gt;".$domestichotel."&gt;".$tempInfo;
	}
	else if($navigationType=="hoteldetail")
	{
		$coutw=$index."&gt;".$domestichotel."&gt;".$tempInfo;
	}
	else if($navigationType=="brand")
	{
	    $coutw=$index."&gt;".$domestichotel."&gt;品牌酒店列表";
	}
    else if($navigationType=="brandinfo")
	{
	    $coutw=$index."&gt;".$domestichotel."&gt;".$brandHref."&gt;".$tempInfo;
	}
	else if($navigationType=="branddetail")
	{
		$brandDetailUrl="";//保存品牌详细页面的URL地址
		$brandDetailNames="";//品牌的名称
		$brandDetailHref="";
		if(strpos($tempInfo,",")>0)
		{
			$arrays=explode(",",$tempInfo);//品牌的ID,品牌的名称
			$brandDetailUrl=getNewUrl($UnionSite_domainName."/site/brandinfo.php?brand=".$arrays[0]."&brandcnname=".$arrays[1],$SiteUrlRewriter);//获取品牌列表的地址";
		     $brandDetailNames=$arrays[1];
		     $brandDetailHref="<a href='$brandDetailUrl' target='_self'>".$brandDetailNames."</a>";//品牌详细页面的URL地址
		}
		 $coutw=$index."&gt;".$domestichotel."&gt;".$brandHref."&gt;".$brandDetailHref;
	}
	else if($navigationType=="cityhotel")
	{
		$coutw=$index."&gt;".$domestichotel."&gt;"."全国城市酒店";
	}
	else if($navigationType=="hotelcomment")
	{
		$coutw=$index."&gt;".$domestichotel."&gt;"."酒店点评";
	}
	else if($navigationType=="citylandmark")
	{
		$coutw=$index."&gt;".$domestichotel."&gt;"."城市地标";
	}
	else if($navigationType=="hotelhotlist")
	{
		$coutw=$index."&gt;".$domestichotel."&gt;"."排行榜";
	}else if ($navigationType=="order"){
		$coutw=$index."&gt;"."订单查询";
	}
	
   return $coutw;
}
?>	
	