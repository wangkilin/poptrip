<?php
/**
 * 酒店排行榜数据
 */

include_once ('../../SDK.config.php');//配置文件加载--必须加载这个文件
include_once (ABSPATH.'sdk/API/Hotel/D_HotelList.php');//加载D_HotelSearch这个接口的封装类
include_once (ABSPATH.'site/module/List_hotelSearch.php');//加载搜索的主逻辑
include_once(ABSPATH."include/urlRewrite.php");//加载URL伪静态处理
include_once (ABSPATH."include/url_HotelControl.php");//加载酒店URL路径控制

//示例：http://127.0.0.1:8888/site/ajaxrequest/hotelChartsRequest.php?city=2,上海

$pageType=$pageType?$pageType:"1";//获取页面标记，默认1为首页酒店排行榜，4为搜索页面今日热卖
if($pageType=='4')$pagesize='5';
else $pagesize='10';


//酒店热卖
include_once (ABSPATH.'sdk/API/Hotel/D_HotelHotSale.php');//加载D_HotelHotSale这个接口的封装类
include_once (ABSPATH.'site/module/main_HotelHotSaleRequest.php');//加载酒店热卖处理逻辑

$cityID=$_GET['city']?$_GET['city']:$SiteDefaultCityID.",".$SiteDefaultCityName;//获取城市的ID，默认
$cityID=urldecode($cityID);
$CityArr=explode(',', $cityID);
$cityIdValue=$CityArr['0'];// 当前城市的ID


//调用品牌的城市分布接口
$hotHotelListList=new get_HotSaleHotelRequest($cityIdValue,'D',$pagesize);
$hotHotelListXML=$hotHotelListList->responseXML;

if(!empty($hotHotelListXML->SearchHotSaleHotelResponse->SearchHotSaleHotelList->SearchHotSaleHotel)){
	foreach ($hotHotelListXML->SearchHotSaleHotelResponse->SearchHotSaleHotelList->SearchHotSaleHotel as  $v ){
		$hotelId=$v->HotelID;
		$HotelList=$HotelList?$HotelList.",".$hotelId:$hotelId;
	}
}
if(strlen($cityID)>0 &&$HotelList)
{
	$List_hotelSearch=new List_hotelSearch($cityID,$pagesize,$HotelList,'3','1');
	$HotelListArr=array();//酒店数据的数组
	$HotelListArr=$List_hotelSearch->HotelListArr;
}

if($pageType=='4'){  
	?>
<ul class="daily_hot">
	<?php
if($HotelListArr){
	$i=0;
	foreach ($HotelListArr as $k =>$value){
		$i++;
		$liClass="";
		if($i>=5){$liClass="class='border_none'";}
	?><li <?php echo $liClass;?>><span class="daily_hot_num"><?php echo $i;?></span><h4><a href="<?php echo $value['url'];?>" title="<?php echo $value['HotelName'];?>"><?php echo utf_substr($value['HotelName'],24)?></a></h4><p><?php echo $value['ZoneName']?></p></li>
	<?php }
}	?>
</ul>


 
<?php }else{?>
<ul class="rank_list basefix">					
<?php 
	if($HotelListArr){
		$i=0;
		foreach ($HotelListArr as $value){
			$i++	
?>
	<li class="<?php if($i==1||$i=='2' || $i=='3')echo "rank_top basefix";elseif($i=='4'||$i=='5' || $i=='6' || $i=='7' || $i=='8' || $i=='9')echo "basefix";else echo "border_none basefix";?>">
		<strong><?php echo $i;?></strong>
		<div class="float_left">
			<a href="<?php echo $value['url'];?>" title="<?php echo $value['HotelName'];?>"><?php echo utf_substr($value['HotelName'],34);?></a><br><span><?php echo $value['ZoneName']?></span>
		</div>
	</li>				

	
	<?php }
	}
	?>
</ul>
<div class="btn_box btn_box_rank"><a href="<?php echo getNewUrl($UnionSite_domainName."/site/hotelhotlist.php?city=".$cityID,$SiteUrlRewriter) ?>" title="热卖排行榜">热卖排行榜</a></div>
<?php }
?>
	

	