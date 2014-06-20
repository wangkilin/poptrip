<?php
/**
 * 酒店排行榜-今日热卖+本周热卖+最新开业+最新预订+最新加盟
 */
include_once ("../include/siteAd.php");//加载广告的处理逻辑
include_once ("../appData/site.config.php");//加载整站系统的配置文件
include_once("../include/urlRewrite.php");//加载URL伪静态处理
include_once ("../include/indexConfig.php");//加载首页的控制文件

$index_cityID=setDefaultCityID($SiteDefaultCityID);//当前页面上默认的展示城市
$index_cssurl=$UnionSite_domainName."/site/css/styles.css";//本页面的CSS样式地址
include_once ('../SDK.config.php');//配置文件加载--必须加载这个文件
include_once (ABSPATH."include/url_HotelControl.php");//加载酒店URL路径控制
include_once (ABSPATH."Common/toolExt.php");


if($_POST['passvalue']){
	$postDataLists=explode("php?", $_POST['passvalue']);
	$postDatas=explode('&', $postDataLists['1']);
	
	//$citys=explode('=',$postDatas['0']);
//	$city=$citys['1'];
	
	$SumTypes=explode('=',$postDatas['1']);
	$SumType=$SumTypes['1'];
	
	$ops=explode('=',$postDatas['2']);
	$op=$ops['1'];
	
	$starlevels=explode('=',$postDatas['3']);
	$starlevel=$starlevels['1'];
	
	$newtypes=explode('=',$postDatas['4']);
	$newtype=$newtypes['1'];
	
	$pages=explode('=',$postDatas['5']);
	$page=$pages['1'];
	
}
$city=$_GET['city'];


$cityID=$city?$city:$SiteDefaultCityID.",".$SiteDefaultCityName;//获取城市的ID，默认是当前城市
$op=$op?$op:"one";//获取页面类别，one=>初始页面 more=>更多页面
$newtype=$newtype?$newtype:"";//获取数据类别
$SumType=$SumType?$SumType:"D";//获取热卖酒店数据，默认D为今日
$starlevel=$starlevel?$starlevel:"";//获取酒店星级，默认全部
$page=$page?$page:"1";//分页，默认第一页
$CityArr=explode(',', $cityID);
$cityIdValue=$CityArr['0'];// 当前城市的ID
$cityName=$CityArr['1'];//当前城市名
$star=getStarInfo($starlevel);//当前酒店星级

if(!is_numeric($page) || $page<1){
	echo "<script>alert('请输入大于0的整数');history.go(-1);</script>";
	die;
}

//酒店热卖
include_once (ABSPATH.'sdk/API/Hotel/D_HotelHotSale.php');//加载D_HotelHotSale这个接口的封装类
include_once (ABSPATH.'site/module/main_HotelHotSaleRequest.php');//加载酒店热卖处理逻辑

//调用品牌的城市分布接口
$hotHotelListList=new get_HotSaleHotelRequest($cityIdValue,$SumType,'50');
$hotHotelListXML=$hotHotelListList->responseXML;
if(!empty($hotHotelListXML->SearchHotSaleHotelResponse->SearchHotSaleHotelList->SearchHotSaleHotel)){
	$HotelStar=array();//存放酒店ID以及其对应的星级
	foreach ($hotHotelListXML->SearchHotSaleHotelResponse->SearchHotSaleHotelList->SearchHotSaleHotel as  $v ){
		if($starlevel){
			if($starlevel=='2'){
				if($v->Star<=$starlevel){
					$hotelId=$v->HotelID;
					$HotHotelList=$HotHotelList?$HotHotelList.",".$hotelId:$hotelId;
				}
			}else{
				if($v->Star==$starlevel){
					$hotelId=$v->HotelID;
					$HotHotelList=$HotHotelList?$HotHotelList.",".$hotelId:$hotelId;
				}
			}	
		}else{
			$hotelId=$v->HotelID;
			$HotHotelList=$HotHotelList?$HotHotelList.",".$hotelId:$hotelId;
		}
		
	}
}
include_once (ABSPATH.'sdk/API/Hotel/D_HotelList.php');//加载D_HotelSearch这个接口的封装类
include_once (ABSPATH.'site/module/List_hotelSearch.php');//加载搜索的主逻辑



if(strlen($cityID)>0 &&$HotHotelList)
{
	//此处取15条数据，防止后面酒店数据不够
	$List_hotelSearch=new List_hotelSearch($cityID,'15',$HotHotelList,'3','1');
	$HotHotelListArr=array();//酒店数据的数组
	$HotHotelListArr=$List_hotelSearch->HotelListArr;
}




//加载页面关键字,引入main_keywords.php页面
include_once 'module/main_keywords.php';
$searchArr=array('{sitename}','{city}','{star}');
$replaceArr=array("$UnionSite_Name","$cityName","$star");
$kw=autoLoadKeywords('hotelhotlist.php',$searchArr,$replaceArr);
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
	<div class="bd hot_sale basefix">
		<div class="path_bar">
<?php 
//构造搜索页的副导航
echo getSubTitleNavigation("hotelhotlist","");
?>
		</div>
		<h2 style="position:relative;z-index:3000;"><?php echo $cityName;?>酒店排行榜<a href="#" class="togglelist">选择其他城市</a>
					<!-- 浮出层 begin -->
					<ul style="left:190px;top:25px;display:none" id="switchCity" class="city_list_popup">
						<li><a class="cityname" href="#" title="北京|1">北京</a></li> 
						<li><a class="cityname" href="#" title="上海|2">上海</a></li> 
						<li><a class="cityname" href="#" title="广州|32">广州</a></li> 
						<li><a class="cityname" href="#" title="深圳|30">深圳</a></li> 
						<li><a class="cityname" href="#" title="西安|10">西安</a></li> 
						<li><a class="cityname" href="#" title="南京|12">南京</a></li> 
						<li><a class="cityname" href="#" title="成都|28">成都</a></li> 
						<li><a class="cityname" href="#" title="武汉|477">武汉</a></li>
						<li><a class="cityname" href="#" title="天津|3">天津</a></li>
						<li><a class="cityname" href="#" title="重庆|4">重庆</a></li>
					</ul>
					<!-- 浮出层 end -->
		</h2>
		<div class="tab basefix">
			<!-- 增加类名current改变状态 -->
			<a href="javascript:;"  rel="<?php echo getHotelHotListUrl($cityID,"D",$op,"",$newtype,$page)?>" onclick="CtripSelfPassParams('POST', $('#postDataUrl').value(), this.rel, '_self')"  <?php if($SumType=='D'){?> class="current"<?php }?>>今日热卖</a>
			<a href="javascript:;" rel="<?php echo getHotelHotListUrl($cityID,"W",$op,"",$newtype,$page)?>"  onclick="CtripSelfPassParams('POST', $('#postDataUrl').value(), this.rel, '_self')" <?php if($SumType=='W'){?> class="current"<?php }?>>本周热卖</a>
		</div>
		<div class="hot_sale_content">
			<div class="tab_star basefix">
				<!-- 增加类名current改变状态 -->
				<a href="javascript:;" rel="<?php echo getHotelHotListUrl($cityID,$SumType,$op,"",$newtype,$page)?>" onclick="CtripSelfPassParams('POST', $('#postDataUrl').value(), this.rel, '_self')" <?php if(empty($starlevel)){?>class="current"<?php }?>>全部</a>
				<a href="javascript:;" rel="<?php echo getHotelHotListUrl($cityID,$SumType,$op,"5",$newtype,$page)?>" onclick="CtripSelfPassParams('POST', $('#postDataUrl').value(), this.rel, '_self')" <?php if($starlevel=='5'){?>class="current"<?php }?>>五星</a>
				<a href="javascript:;" rel="<?php echo getHotelHotListUrl($cityID,$SumType,$op,"4",$newtype,$page)?>" onclick="CtripSelfPassParams('POST', $('#postDataUrl').value(), this.rel, '_self')" <?php if($starlevel=='4'){?>class="current"<?php }?>>四星</a>
				<a href="javascript:;" rel="<?php echo getHotelHotListUrl($cityID,$SumType,$op,"3",$newtype,$page)?>" onclick="CtripSelfPassParams('POST', $('#postDataUrl').value(), this.rel, '_self')" <?php if($starlevel=='3'){?>class="current"<?php }?>>三星</a>
				<a href="javascript:;" rel="<?php echo getHotelHotListUrl($cityID,$SumType,$op,"2",$newtype,$page)?>" onclick="CtripSelfPassParams('POST', $('#postDataUrl').value(), this.rel, '_self')" <?php if($starlevel=='2'){?>class="current"<?php }?>>二星及以下</a>
			</div>
			<div class="basefix">
			
			<?php  
				$i=0;
				if($HotHotelListArr){
					foreach($HotHotelListArr as $v){
						$HotelID=$v['HotelID'];
						$i++;
						if($i>9){
							break;
						}
						$StarInfo=get_star_info($v['Star'],$v['Rstar']);//
						$showTitle=$StarInfo['0'];
						$CustomerEvalName=$StarInfo['1'];
						
						
			?>
				<dl class="hotel_detail basefix">
					<dd class="hotel_pic"><span class="hot_sale_tri hot_sale_tri_<?php if($i<4)echo "orange";else echo "blue"; ?>"><?php echo $i;?></span>
					<a href="<?php echo $v['url'];?>" title="<?php echo $v['HotelName'];?>">
					<img width="100" height="75" style="background: url('http://pic.c-ctrip.com/hotels110127/brand_logo.jpg') no-repeat scroll 0 1px transparent;display: block;" src="<?php echo $v['HotelPic'];?>" alt="<?php echo $v['HotelName'];?>" /></a></dd>
					<dt><h3><a href="<?php echo $v['url'];?>" title="<?php echo $v['HotelName'];?>"><?php echo utf_substr($v['HotelName'],16);?></a></h3></dt>
					<dd class="basefix">
					<span  title="<?php echo $showTitle;?>" class="<?php echo $CustomerEvalName;?>"></span>
					
					<dfn><?php echo $v['CurrencyMinPrice']?><span><?php echo $v['MinPrice']?></span></dfn>起</dd>
					<dd><p><?php echo $v['ZoneName'];?></p></dd>
				</dl>
				
			<?php }}?>	
				
			</div>
		</div>
		
		<?php   
		ob_flush(); //缓存输出，先将上面部分输出
		flush();
		include_once 'ajaxrequest/hotelRandkRequest.php';?>
		
	</div>
	<!-- bd end -->
<?php include_once 'module/foot.php';//加载底部控制文件 ?>

<input type="hidden" name="postDataUrl" id="postDataUrl" value="<?php echo getNewUrl($UnionSite_domainName."/site/hotelhotlist.php?city=".$cityID,$SiteUrlRewriter) ?>">


<script type="text/javascript" src="http://webresource.ctrip.com/code/cquery/cQuery_110421.js"></script>
<script>
$.ready(function(){
	var toggleCitylist = $('.togglelist');
	toggleCitylist.bind('click',function(e){
		if($('#switchCity').css('display') == 'none'){
			$('#switchCity').css('display','block');
		} else {
			$('#switchCity').css('display','none');
		}
		e.preventDefault();
	})
	$('#switchCity li a').bind('click',function(e){
		var name = this.title.split('|')[0];
		var id = this.title.split('|')[1];
		var url = "<?php echo $UnionSite_domainName;?>/site/hotelhotlist.php?city="+id+","+decodeURI(name);//+"&SumType=D&op=&starlevel=&newtype=&page=";
		window.location.href = url;
		e.preventDefault();
	})
	$(document).bind('click',function(e){
		var tar = e.target;
		if(tar.className != 'togglelist'){
			if(tar.className != "cityOption"){
				$('#switchCity').css('display','none');
			}	
		}

	})
})
</script>

<script type="text/javascript" src="<?php echo $UnionSite_domainName?>/site/js/CtripSelfDefined.js"></script>


</body>
</html>