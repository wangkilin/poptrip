<?php
/**
 * 热门城市地标分类
 */

include_once ('../../SDK.config.php');//配置文件加载--必须加载这个文件
include_once(ABSPATH."include/urlRewrite.php");//加载URL伪静态处理
include_once (ABSPATH."include/url_HotelControl.php");//加载酒店URL路径控制
//示例：http://127.0.0.1:8888/site/ajaxrequest/hotCityLandMarkRequest.php?city=2,上海&getNums=24
$_GET['defaultcityid']=$_GET['defaultcityid']?$_GET['defaultcityid']:$_GET['city'];
$cityID=$_GET['defaultcityid']?$_GET['defaultcityid']:$SiteDefaultCityID.",".$SiteDefaultCityName;//获取城市的ID，默认是上海
$getNums=$getNums?$getNums:$_GET['getNums'];

$getNums=$getNums?$getNums:"0";//
//酒店入住时间 均为默认
$CheckInDate=getDateYMD('-');
$CheckOutDate=getDateYMD_addDay('-',$HotelSearchDayNums);
$p1=$cityID;
$p2="$CheckInDate,$CheckOutDate";

$hotelSearchUrl=new HotelUrlControl($p1, $p2, "", "", "", "...", "", "", "1,5", "list");
$searchUrl=$hotelSearchUrl->returnUrl;
$searchUrl=getNewUrl($searchUrl,$SiteUrlRewriter);




//行政区、商业区、景点
include_once (ABSPATH.'sdk/API/Hotel/SearchLocationZoneCityLandmark.php');//加载SearchLocationZoneCityLandmark这个接口的封装类
include_once (ABSPATH.'site/module/main_CityLandMarkRequest.php');//加载处理逻辑

$cityID=urldecode($cityID);
$CityArr=explode(',', $cityID);
$cityIdValue=$CityArr['0'];// 当前城市的ID

$CityLandMarkRequest=new get_CityLandMarkRequest($cityIdValue,'1,2,3','1,2,3');
$hotCityLandMarkXML=$CityLandMarkRequest->responseXML;
$SearchLocationZoneCityLandmarkResponseXML=$hotCityLandMarkXML->SearchLocationZoneCityLandmarkResponse;

//行政区
$LocationName=array();
if(!empty($SearchLocationZoneCityLandmarkResponseXML->LocationDetails->LocationDetail)){
	$i=0;
	foreach ($SearchLocationZoneCityLandmarkResponseXML->LocationDetails->LocationDetail as  $v ){
		//判断是否有数量限制
		if($getNums>0){
			$i++;
			if($i>$getNums){
				break;
			}
		}
		$p3=$v->Location.",".$v->LocationName."-,-,";
		//$p3=urlencode($v->Location.",".$v->LocationName."-,-,");
		$url=str_replace("...",$p3,$searchUrl);
		$LocationName[]=$v->LocationName."@".$url;
		
	}
}
//商业区
$ZoneName=array();
if(!empty($SearchLocationZoneCityLandmarkResponseXML->ZoneDetails->ZoneDetail)){
	$i=0;
	foreach ($SearchLocationZoneCityLandmarkResponseXML->ZoneDetails->ZoneDetail as  $v ){
		if($getNums>0){
			$i++;
			if($i>$getNums){
				break;
			}
		}
		$p3=",-".$v->Zone.",".$v->ZoneName."-,";
		$url=str_replace("...",$p3,$searchUrl);
		$ZoneName[]=$v->ZoneName."@".$url;
	}
}

//景点
$LandmarkName=array();
if(!empty($SearchLocationZoneCityLandmarkResponseXML->CityLandmarkAttractionsDetails->CityLandmarkDetail)){
	$i=0;
	foreach ($SearchLocationZoneCityLandmarkResponseXML->CityLandmarkAttractionsDetails->CityLandmarkDetail as  $v ){
		if($getNums>0){
			$i++;
			if($i>$getNums){
				break;
			}
		}
		$p3=",-,-".$v->Longitude.":".$v->Latitude.",".$v->LandmarkName;
		$url=str_replace("...",$p3,$searchUrl);
		$LandmarkName[]=$v->LandmarkName."@".$url;
	}
}
$moreLinkUrl=getNewUrl($UnionSite_domainName."/site/citylandmark.php?defaultcityid=".$cityID."&getNums=0",$SiteUrlRewriter);
if($pageType=='1'){
?>

<ul class="side_list border_dashed">
	<li><strong>景点</strong>		<a href="<?php echo $moreLinkUrl;?>#Landmark_place" class="more">更多</a>	</li>
	<?php 
	if($LandmarkName){	
		foreach ($LandmarkName as $value) {
			$detailArr=explode('@', $value);
	?>
	<li>	<a href="<?php echo $detailArr[1];?>" title="<?php echo $detailArr[0];?>"><?php echo utf_substr($detailArr[0],28);?></a></li>
	<?php }
	}?>
</ul>

<ul class="side_list border_dashed">
	<li><strong>行政区</strong>		<a href="<?php echo $moreLinkUrl;?>#Location_place" class="more">更多</a></li>
	<?php 
	if($LocationName){
		foreach ($LocationName as $value) {
			$detailArr=explode('@', $value);
	?>
		<li><a href="<?php echo $detailArr[1];?>" title="<?php echo $detailArr[0];?>"><?php echo $detailArr[0];?></a></li>
	<?php }
	}?>
</ul>
<ul class="side_list">
	<li><strong>商业区</strong>	<a href="<?php echo $moreLinkUrl;?>#Zone_place" class="more">更多</a>	</li>
	<?php 
	if($ZoneName){
		foreach ($ZoneName as $value) {
			$detailArr=explode('@', $value);
	?>
	
	<li><a href="<?php echo $detailArr[1];?>" title="<?php echo $detailArr[0];?>"><?php echo $detailArr[0];?></a></li>
	<?php	}
	}?>
	

</ul>

<?php }else{?>

<div class="content content_blue" id="Landmark_place">
					<h3>景点</h3>
					<p class="place">
					<?php 
					if($LandmarkName){
						foreach ($LandmarkName as $value) {
							$detailArr=explode('@', $value);
					?>
						<a href="<?php echo $detailArr[1];?>" title="<?php echo $detailArr[0];?>">
						<?php echo utf_substr($detailArr[0],16);?></a>
					<?php	}
						}?>
					</p>
					<?php if($getNums>0){?>		<a href="<?php echo $moreLinkUrl;?>#Landmark_place" class="more">更多</a>	<?php	}?>
				</div>


				<div class="content content_blue" id="Location_place">
					<h3>行政区</h3>
					<p class="place">
					<?php if($LocationName){
						foreach ($LocationName as $value) {
							$detailArr=explode('@', $value);
					?>
						<a href="<?php echo $detailArr[1];?>" title="<?php echo $detailArr[0];?>"><?php echo utf_substr($detailArr[0],16);?></a>
					<?php	}
						}?>
					</p>
					<?php if($getNums>0){?>		<a href="<?php echo $moreLinkUrl;?>#Location_place" class="more">更多</a>	<?php	}?>
				</div>
				
				
				<div class="content content_gray border_none" id="Zone_place">
					<h3>商业区</h3>
					<p class="place">
					<?php if($ZoneName){
						foreach ($ZoneName as $value) {
							$detailArr=explode('@', $value);
					?>
						<a href="<?php echo $detailArr[1];?>" title="<?php echo $detailArr[0];?>"><?php echo utf_substr($detailArr[0],16);?></a>
					<?php	}
						}?>
					</p>
					<?php if($getNums>0){?>		<a href="<?php echo $moreLinkUrl;?>#Zone_place" class="more">更多</a>	<?php	}?>
				</div>
				
			<?php if($getNums==0){?>	<div class="more"><a href="#" class="togglelist">选择其他城市</a><ul id="toggle_hot"  class="city_list_popup" style="display:none;"></ul></div><?php }?>

<?php }?>

