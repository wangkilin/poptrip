<?php
/**
 *处理显示酒店的详细信息
 */
ob_start();
header("Content-type: text/html; charset=utf-8"); 
include_once '../Common/browseHistoryClass.php';//加载浏览记录的方法
include_once ("../include/siteAd.php");//加载广告的处理逻辑
include_once ("../appData/site.config.php");//加载整站系统的配置文件
include_once("../include/urlRewrite.php");//加载URL伪静态处理
include_once ('../SDK.config.php');//配置文件加载--必须加载这个文件
include_once (ABSPATH.'sdk/API/Hotel/D_HotelDetail.php');//加载D_HotelSearch这个接口的封装类
include_once ('module/main_hotelSearch.php');//加载搜索的主逻辑
include_once (ABSPATH."include/url_HotelControl.php");//加载酒店URL路径控制
$index_cssurl=$UnionSite_domainName."/site/css/styles.css";//本页面的CSS样式地址
$index_jsurl = $UnionSite_domainName."/site/js/hotelDetail.js";
$position_jsurl = $UnionSite_domainName."/site/js/position.js";
$top_jsurl = $UnionSite_domainName."/site/js/topsearch.js";

include_once (ABSPATH.'sdk/API/Hotel/D_HotelList.php');//加载D_HotelSearch_List这个接口的封装类


$cdate=empty($_POST['passvalue'])?getDateYMD("-").",".getDateYMD_addDay("-",$HotelSearchDayNums):$_POST['passvalue'];


//获取符合条件的酒店详细数据
$mainHotelSearch=new page_hotelSearch();
$mainHotelSearch->isSiteUrlRewriter=$SiteUrlRewriter;//设置本系统是否要做伪静态
$mainHotelSearch->thisUnionSite_domainName=$UnionSite_domainName;//设置系统的域名
$mainHotelSearch->orderShowUrl=$UnionSite_domainName."/site/orderdetail.php";//设置订单的查询页面地址
$mainHotelSearch->cdate=$cdate;
$mainHotelSearch->getHotelDetailResponseXML_URL();//调用获取酒店详细搜索的返回数据【URL传值模式】
//加载页面关键字,引入main_keywords.php页面
include_once 'module/main_keywords.php';
$searchArr=array('{sitename}','{hotelname}','{city}','{address}','{rating}','{novoters}','{des}');
$des=strip_tags($mainHotelSearch->hotelDetailHotelDesc);//酒店描述 去除HTML标签
$replaceArr=array("$UnionSite_Name","$mainHotelSearch->hotelDetailName","$mainHotelSearch->CityName","$mainHotelSearch->showAddress","$mainHotelSearch->Rating","$mainHotelSearch->NoVoters","$des");
$kw=autoLoadKeywords('hoteldetail.php',$searchArr,$replaceArr);


$cityID=$_GET['city']?$_GET['city']:$SiteDefaultCityID.",".$SiteDefaultCityName;//获取城市的ID
$p1=urldecode($cityID);
if(strpos($p1,",")>=0){
	$arrayP1=explode(",",$p1);
	if(count($arrayP1)>2)
	{
		$hotelID=$arrayP1[2];
	}
}


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<body><?php include_once 'module/header.php';//加载头部文件

/*处理历史浏览记录的问题*/
if($mainHotelSearch->hotelDetailName){
	$historyControl=new browse_history_class();
	$values=$mainHotelSearch->hotelID."|".$mainHotelSearch->hotelDetailName."|".$mainHotelSearch->cityid."|".$mainHotelSearch->CityName;//新的值
	$historyControl->setListHotel("hotelBrowseHistory", $values, $SiteHotelBrowserListTotalNums);
}
//酒店的点评关键字
include_once (ABSPATH.'sdk/API/Hotel/D_HotelCommentKey.php');//加载D_HotelCommentKey这个接口的封装类
include_once (ABSPATH.'site/module/main_HotelCommentKey.php');//加载酒店点评关键字处理逻辑
//调用店的点评关键字接口
$HotelCommentKey=new get_HotelCommentKey($hotelID);
$KeywordXML=$HotelCommentKey->responseXML;

//酒店的点评关键字
if(!empty($KeywordXML->DomesticHotelCommentStaticInfos->HotelCommentStaticInfo->DomesticHotelCommentStaticInfo->KeyWordLists->DomesticHotelCommentKeyWordEntity)){
	$Keywords="";
	foreach ($KeywordXML->DomesticHotelCommentStaticInfos->HotelCommentStaticInfo->DomesticHotelCommentStaticInfo->KeyWordLists->DomesticHotelCommentKeyWordEntity as  $v ){
		if($Keywords) $Keywords=$Keywords."&nbsp;".$v->Keyword;
		else $Keywords=$v->Keyword;
	
	}
}

?>
<?php include_once 'module/bar_navigation.php';//加载导航文件 
echo	getMainNavigation();//加载默认的导航
?>
<!-- bd begin -->
<div class="bd bd_detail">


<div class="path_bar">
<?php 
//构造搜索页的副导航
//构造URL地址--列表的地址
 
$position=analysisHotelPosition($mainHotelSearch->locationZone);//酒店位置
$hotelSearchUrl=new HotelUrlControl($mainHotelSearch->cityid.",".$mainHotelSearch->CityName, $mainHotelSearch->CheckInDate.",".$mainHotelSearch->CheckOutDate,";", "", "", '', '', $mainHotelSearch->OrderName.",".$mainHotelSearch->OrderType, "1,".$SiteHotelSearch_pagesize, "list");
//echo $hotelSearchUrl->returnUrl;
$tempInfo="<a href='".getNewUrl($hotelSearchUrl->returnUrl,$SiteUrlRewriter)."'>".$mainHotelSearch->CityName."酒店</a>";
if (!empty($position)){	 
	$hotelSearchUrl=new HotelUrlControl($mainHotelSearch->cityid.",".$mainHotelSearch->CityName, $mainHotelSearch->CheckInDate.",".$mainHotelSearch->CheckOutDate,$mainHotelSearch->Star."", "", "", $mainHotelSearch->locationZone, '', $mainHotelSearch->OrderName.",".$mainHotelSearch->OrderType, "1,".$mainHotelSearch->PageSize, "list");
	$tempInfo.="&gt;<a href='".getNewUrl($hotelSearchUrl->returnUrl,$SiteUrlRewriter)."'>".$position.$star."酒店</a>";
}
$tempInfo.="&gt;".$mainHotelSearch->hotelDetailName;
 
echo getSubTitleNavigation("hoteldetail",$tempInfo);
?>
</div>
<?php
$isShowMoreSearchCondition =false;//是否显示星级+价格+品牌等筛选条件，false 表示不筛选
include_once 'module/main_hotelSearch_term.php';//加载搜索页面的条件选择器
?>

<?php  if($mainHotelSearch->hotelDetailName){ ?>

<div class="detail_info basefix"><?php  echo $mainHotelSearch->hotelDetail_TitleAddress?>
<div class="detail_info_right">
<div class="detail_info_price basefix"><a href="#jumpHotel" class="btn_blue" id="book_now">立即预订&gt;</a>
<p class="low_price"><dfn><?php echo currencyTransition($mainHotelSearch->hotelDetailCurrencyMinPrice,1);?></dfn>
<span><?php echo  round((string)$mainHotelSearch->hotelDetailPrice);?></span>
起</p>
</div>
<div class="detail_info_comment">
<div class="hotel_judge"><?php echo $mainHotelSearch->hotelDetailJudge;?>
</div>
<ul class="hotel_point basefix">
	<!-- 更改width来改变进度条 -->
<?php echo $mainHotelSearch->hotelDetailPointBasefix;?>
</ul>
<div class="latest_comment">
<span  title="<?php echo $Keywords;?>">
	<?php if(strlen($Keywords)>2){ echo utf_substr($Keywords,28)."...";}else{echo "暂无";}?></span>
<b></b></div>
</div>
</div>
</div>
<?php }?>



<div class="search_content basefix"><!-- search_main -->

<?php  if($mainHotelSearch->hotelDetailName){ ?>
<div class="search_main" name="jumpHotel" id="jumpHotel">
<div class="tab basefix"><a href="#" class="current" id="hotelBook">客户预定</a> <a
	href="#">客户点评</a> <a href="#" id="hotelPiclist">酒店图片<span>(<?php echo $mainHotelSearch->hotelDetailTotalImageNum?>张)</span></a>
</div>
<div class="search_result_list">
<div class="search_result_box">
<div class="hotel_date basefix">
<!-- 重新搜索子房型的列表 -->
<?php echo showHotelDetailReSearch($mainHotelSearch,$UnionSite_domainName);?>
</div>
<table cellspacing="0" cellpadding="0" class="room_list">
	<thead>
		<tr>
			<th style="width: 210px; padding-left: 40px;">房型</th>
			<th style="width: 80px;">床型</th>
			<th style="width: 80px;">早餐</th>
			<th style="width: 80px;">宽带</th>
			<th style="width: 110px;">房价(含服务费)</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php echo $mainHotelSearch->hotelDetailSubRoomList;?>
	</tbody>
</table>
</div>
<!-- 放置酒店的点评 -->
<div class="search_result_box basefix">
  <div class="result_comment basefix">
	<?php echo $mainHotelSearch->hotelDetailCommentGeneral;?>
	<p class="float_left" title="<?php echo $Keywords;?>"><?php if(strlen($Keywords)>2){ echo utf_substr($Keywords,50)."...";}else{echo "暂无";}?></p>
	</div>
	<div id="hotel_comment">
		<img src="<?php echo $UnionSite_domainName;?>/site/images/loading.gif" style="margin:20px 0px 20px 200px"/>
		<br/>
	</div>
	<br/>
	<div id="commentControl">
		<a href="#" id="preCommentList" style="margin-right:10px;">上一页</a>
		<span id="commentCurrent"></span> / <span id="commentTotal"></span> 页
		<a href="#" id="nextCommentList" style="margin-left:10px;">下一页</a>
	</div>
</div>
<!-- 放置酒店的点评 -->

<!-- 放置酒店的图片，带有切换功能 -->
<?php echo $mainHotelSearch->hotelDetailImageListShow;?>
</div>
<div class="box_blue">
<h3>酒店简介</h3>
<div class="hotel_intro">
<?php echo $mainHotelSearch->hotelDetailHotelDesc;?>
</div>
<table cellspacing="0" cellpadding="0" class="detail_extralist">
	<tbody>
		<tr style="display:<?php if($mainHotelSearch->hotelDetailSurroundings==""||$mainHotelSearch->hotelDetailSurroundings==null){echo "none";}?>">
			<th class="border_none">周边环境</th>
			<td class="border_none">
			<?php echo $mainHotelSearch->hotelDetailSurroundings;?>
	<!-- 	
<?php echo $mainHotelSearch->hotelDetailSurroundings;if($mainHotelSearch->hotelDetailZone!="-1"&&$mainHotelSearch->hotelDetailZone!=""){?><a target='_blank' href="http://www.ctrip.com/Merchant/MerchantSearchList.asp?city=<?php echo $mainHotelSearch->cityid?>&hotelzone=<?php echo $mainHotelSearch->hotelDetailZone?>">周边打折</a><?php }?>
 -->	
</td>
		</tr>
		<tr style="display:<?php if($mainHotelSearch->hotelDetailBaseInfoShow==""||$mainHotelSearch->hotelDetailBaseInfoShow==null){echo "none";}?>">
			<th>基本信息</th>
			<td>
			<ul class="basefix">
			<?php echo $mainHotelSearch->hotelDetailBaseInfoShow;?>
			</ul>
			</td>
		</tr>
		<tr style="display:<?php if($mainHotelSearch->hotelDetailDiy_Breakfast==""||$mainHotelSearch->hotelDetailDiy_Breakfast==null){echo "none";}?>">
			<th>附加选择</th>
			<td>自助早餐价<span><?php echo $mainHotelSearch->hotelDetailDiy_Breakfast;?></span></td>
		</tr>
			<tr style="display:<?php if($mainHotelSearch->hotelDetailFacilityAndHotelListType1==""||$mainHotelSearch->hotelDetailFacilityAndHotelListType1==null){echo "none";}?>">
			<th>酒店服务</th>
			<td>
			<ul class="basefix">
				<?php echo $mainHotelSearch->hotelDetailFacilityAndHotelListType1;?>
			</ul>
			</td>
		</tr>
			<tr style="display:<?php if($mainHotelSearch->hotelDetailFacilityAndHotelListType2==""||$mainHotelSearch->hotelDetailFacilityAndHotelListType2==null){echo "none";}?>">
			<th>餐饮设施</th>
			<td>
			<ul class="basefix">
			<?php echo $mainHotelSearch->hotelDetailFacilityAndHotelListType2;?>
			</ul>
			</td>
		</tr>
		 	<tr style="display:<?php if($mainHotelSearch->hotelDetailFacilityAndHotelListType3==""||$mainHotelSearch->hotelDetailFacilityAndHotelListType3==null){echo "none";}?>">
			<th>娱乐健身</th>
			<td>
			<ul class="basefix">
			<?php echo $mainHotelSearch->hotelDetailFacilityAndHotelListType3;?>
			</ul>
			</td>
		</tr>
			 	<tr style="display:<?php if($mainHotelSearch->hotelDetailFacilityAndHotelListType4==""||$mainHotelSearch->hotelDetailFacilityAndHotelListType4==null){echo "none";}?>">
			<th>客房设施</th>
			<td>
			<ul class="basefix">
			<?php echo $mainHotelSearch->hotelDetailFacilityAndHotelListType4;?>
			</ul>
			</td>
		</tr>
				 	<tr style="display:<?php if($mainHotelSearch->hotelDetailCreditCardInfoList==""||$mainHotelSearch->hotelDetailCreditCardInfoList==null){echo "none";}?>">
			<th>接受信用卡</th>
			<td>
			<ul class="basefix">
				<?php echo $mainHotelSearch->hotelDetailCreditCardInfoList;?>
			</ul>
			</td>
		</tr>
	</tbody>
</table>
</div>
<div class="box_blue">
<div class="transtab"><a href="#" class="current">交通信息</a> <a href="#">周边设施</a>

<?php if($MapKey){?>
<span class="viewMap" style="font-size: 13px; float: right; display: block; margin-right: 10px; color: rgb(2, 114, 196); cursor: pointer;">查看地图</span>
<?php }?>
</div>
<?php
//获取酒店周边信息
include_once (ABSPATH.'sdk/API/Hotel/D_HotelNearbyInfo.php');//加载D_HotelNearbyInfo这个接口的封装类
include_once (ABSPATH.'site/module/main_HotelNearbyInfo.php');//加载酒店周边信息处理逻辑

//调用酒店周边信息接口
$HotelNearbyInfo=new get_HotelNearbyInfo($hotelID,'5','6','F');
$HotelNearbyXML=$HotelNearbyInfo->responseXML;
$HotelNearbyFacilityEntityList=array();
$hotelDetailMaps=array();//地图周边信息
$nearFacilityArray=array('1'=>'restaurant','2'=>'shopping','3'=>'entertain','102'=>'scenic','201'=>'subwaystation');

if(!empty($HotelNearbyXML->DomesticHotelNearbyInfoResponse->HotelNearbyFacilityEntityList->DomesticHotelNearbyFacilityEntity)){
	$i=0;
	foreach ($HotelNearbyXML->DomesticHotelNearbyInfoResponse->HotelNearbyFacilityEntityList->DomesticHotelNearbyFacilityEntity as  $v ){	
		$HotelNearbyFacilityEntityList[$i][(string)$v->Type]=(string)$v->FacilityName;	
		$i++;
		$nearFacilityValue=$nearFacilityArray[(string)$v->Type];
		if(!empty($nearFacilityValue)){
			$hotelDetailMaps[$nearFacilityValue][(string)$v->HotelNearbyFacilityID][name]=(string)$v->FacilityName;
			$hotelDetailMaps[$nearFacilityValue][(string)$v->HotelNearbyFacilityID][distance]=(string)$v->Distance;
			$hotelDetailMaps[$nearFacilityValue][(string)$v->HotelNearbyFacilityID]['position'][lat]=(string)$v->LAT;
			$hotelDetailMaps[$nearFacilityValue][(string)$v->HotelNearbyFacilityID]['position'][lon]=(string)$v->LON;
		}
	}
}
foreach($HotelNearbyFacilityEntityList as $v){
	foreach ($v as $key=>$value){
		if($NearbyFacilityEntityList[$key])$NearbyFacilityEntityList[$key] =$NearbyFacilityEntityList[$key]."&nbsp;&nbsp;".$value;
		else $NearbyFacilityEntityList[$key] =$value;
	}
}

?>
<div class="trans_info basefix">
<table>
	<tbody>
		<tr>
			<th>市中心</th>
			<td>
			<?php 
			if($mainHotelSearch->hotelPlaceInfo['center']){
				foreach ($mainHotelSearch->hotelPlaceInfo['center'] as $v){
					echo "<ul class='trans_info_detail_main'><li class='col1'>".$v['PlaceName']."</li><li class='col2'>距离酒店".$v['Distance']."公里</li><li class='col3'>".$v['ArrivalWay']."</li></ul>";
				}
			}	
			?>
		
			</td>
		</tr>
		<tr>
			<th>机场</th>
			<td>
			<?php 
			if($mainHotelSearch->hotelPlaceInfo['airport']){
				foreach ($mainHotelSearch->hotelPlaceInfo['airport'] as $v){
					echo "<ul class='trans_info_detail_main'><li class='col1'>".$v['PlaceName']."</li><li class='col2'>距离酒店".$v['Distance']."公里</li><li class='col3'>".$v['ArrivalWay']."</li></ul>";
				}
			}	
			?>
			</td>
		</tr>
		<tr>
			<th>火车站</th>
			<td>
			<?php 
			if($mainHotelSearch->hotelPlaceInfo['train']){
				foreach ($mainHotelSearch->hotelPlaceInfo['train'] as $v){
					echo "<ul class='trans_info_detail_main'><li class='col1'>".$v['PlaceName']."</li><li class='col2'>距离酒店".$v['Distance']."公里</li><li class='col3'>".$v['ArrivalWay']."</li></ul>";
				}	
			}
			?>
			</td>
		</tr>
	</tbody>
</table>

</div>

<!-- 周边设施 -->
<div class="trans_info basefix" style="display:none;">
<table>
	<tbody>
			<tr style="display:<?php if(empty($NearbyFacilityEntityList[1])){echo "none";}?>">
			<th>餐饮</th>
			<td>
			<?php echo $NearbyFacilityEntityList[1];?>
			</td>
		</tr>
				<tr style="display:<?php if(empty($NearbyFacilityEntityList[2])){echo "none";}?>">
			<th>购物</th>
			<td>
			<?php echo $NearbyFacilityEntityList[2];?>
			</td>
		</tr>
		<tr style="display:<?php if(empty($NearbyFacilityEntityList[3])){echo "none";}?>">
			<th>娱乐</th>
			<td>
			<?php echo $NearbyFacilityEntityList[3];?>
			</td>
		</tr>
			<tr style="display:<?php if(empty($NearbyFacilityEntityList[101])){echo "none";}?>">
			<th>大学</th>
			<td>
		<?php echo $NearbyFacilityEntityList[101];?>
			</td>
		</tr>
			<tr style="display:<?php if(empty($NearbyFacilityEntityList[102])){echo "none";}?>">
			<th>景点</th>
			<td>
			<?php echo $NearbyFacilityEntityList[102];?>
			</td>
		</tr>
			<tr style="display:<?php if(empty($NearbyFacilityEntityList[201])){echo "none";}?>">
			<th>地铁</th>
			<td>
		<?php echo $NearbyFacilityEntityList[201];?>
			</td>
		</tr>
	</tbody>
</table>

</div>



</div>


<?php 
//获取周边酒店信息
if(!empty($HotelNearbyXML->DomesticHotelNearbyInfoResponse->HotelToHotelEntityList->DomesticHotelToHotelEntity)){
	$DistanceArr=array();
	foreach ($HotelNearbyXML->DomesticHotelNearbyInfoResponse->HotelToHotelEntityList->DomesticHotelToHotelEntity as  $v ){
		$hotelId=$v->HotelIdTo;
		$Distance=(string)$v->Distance;
		$hotelidlist=$hotelidlist?$hotelidlist.",".$hotelId:$hotelId;
		$DistanceArr["$hotelId"]=$Distance;
	}
}
$pagesize=count($HotelNearbyXML->DomesticHotelNearbyInfoResponse->HotelNearbyFacilityEntityList->DomesticHotelNearbyFacilityEntity );

include_once (ABSPATH.'site/module/List_hotelSearch.php');//加载搜索的主逻辑
$pagesize=$pagesize>6?'6':$pagesize;
if($hotelidlist){
	$List_hotelSearch=new List_hotelSearch($cityID,$pagesize,$hotelidlist,'3','1');
	$HotelListArr=array();//酒店数据的数组
	$HotelListArr=$List_hotelSearch->HotelListArr;
}




if($MapKey){
	//构造地图JOSN信息
	if($HotelListArr){
		foreach ($HotelListArr as $k =>$value){
			$hotelDetailMaps['nearbyHotel'][$value['HotelID']]['name']=$value['HotelName'];
			$hotelDetailMaps['nearbyHotel'][$value['HotelID']]['distance']=$DistanceArr[$value['HotelID']];
			$hotelDetailMaps['nearbyHotel'][$value['HotelID']]['position']['lat']=$value['lat'];
			$hotelDetailMaps['nearbyHotel'][$value['HotelID']]['position']['lon']=$value['lon'];
			
		}	
		
	}
	$jsonHotelDetailMaps=json_encode($hotelDetailMaps);


$hotelDetailMapsHtml=<<<BEGIN
	<script type="text/javascript">
	var mapMessageConfig = {
        temp: ['全程', '分钟', '约', '站', '换乘', '次'],
        license: 'GS（2010）1049号',
        distance: '实际距离为',
        noInfo: '暂无交通信息数据',
        traffic: ['公交', '驾车', '交换起始位置', '起：', '终：', '上行|下行']
    };

	var addressUrlConfig = {
        visitCount: '/domestic/tool/AjaxHotelVisitCount.aspx?hotelid=$hotelID',
        imgMapUrl: '',
        mapIframe: 'mapiframe.php',
        trafficinfo: '$baseUlr/site/ajaxrequest/AjaxMapHotelTrafficinfo.php?hotelid=$hotelID&type=piloting',
        trafficline: '$baseUlr/site/ajaxrequest/AjaxMapHotelTrafficinfo.php?typetraffic=$1&hotelid=$hotelID&placeid=$2&type=trafficline',
        roomlink:"/hotel-rtm$1/$hotelID.html",
        order: '/DomesticBook/InputNewOrder.aspx',  //下订单链接
        delayOrder: '/DomesticBook/InputDelayOrder.aspx', //下订单链接 
        lvPingRecomand:"/Domestic/Tool/AjaxLvPingDemandNew.aspx?hotelid=$hotelID",
        ajaxRoomList: '/Domestic/tool/AjaxHotelRoomListForDetail.aspx?MasterHotelID=0&hotel=$hotelID'
    };
	var hotelDomesticConfig = { 
		hasLogin: true,
		isLocalhost: 0, 
		webResourceReleaseNo: '20130502', 
		popMapFlag: 1, 
		hotel: { 
		id: "$hotelID", 
		name: "$mainHotelSearch->hotelDetailName", 
		position: "$mainHotelSearch->lon|$mainHotelSearch->lat", 
		vacationsDP:true 
		}, 
		query: { 
		cityId:'$mainHotelSearch->cityid', 
		checkIn:'$mainHotelSearch->CheckInDate', 
		checkOut:'$mainHotelSearch->CheckOutDate' 
		}, 
		EDM:'', 
		nearFacility:$jsonHotelDetailMaps
	};
	</script>
BEGIN;

echo $hotelDetailMapsHtml; 
}

?>



<div class="box_blue">
<h3><?php echo $mainHotelSearch->hotelDetailName;?>周边酒店</h3>

<ul class="surround_hotel basefix">	

<?php 

	if($HotelListArr){
		$i=0;
		foreach ($HotelListArr as $k =>$value){
			$Distance=$DistanceArr[$value['HotelID']];
			$i++	;
			$StarInfo=get_star_info($value['Star'],$value['Rstar']);//
			$showTitle=$StarInfo['0'];
			$CustomerEvalName=$StarInfo['1'];
			
?>				
	<li class="<?php if($i==1||$i=='2' || $i=='3')echo "border_none";?>">
		<a href="<?php echo $value['url'];?>" class="surround_pic">
		<img src="<?php echo $value['HotelPic'];?>" style="background: url('http://pic.c-ctrip.com/hotels110127/brand_logo.jpg') no-repeat scroll 0 1px transparent;display: block;" height="75" width="100"></a>
		<div></div>
		<span class="surround_distance">距<?php echo round($Distance,2);?> km</span>
		<p class="name"><a href="<?php echo $value['url'];?>" title="<?php echo $value['HotelName'];?>"><?php echo utf_substr($value['HotelName'],18);?></a></p>
		<p class="mark"><span  title="<?php echo $showTitle;?>" class="<?php echo $CustomerEvalName;?>"></span><?php echo $value['Rating'];?></p>
		<p class="price"><dfn><?php echo $value['CurrencyMinPrice'];?></dfn><span><?php echo $value['MinPrice'];?></span>起</p>
	
	</li>	
<?php 
		}		
	}?>		
					
</ul>
</div>
</div>
<?php }else{?>
很抱歉，暂时无法找到符合您要求的酒店。
您可以试试更改搜索条件重新搜索，或改订其他酒店。

<?php }?>
<!-- search_side -->
<div class="search_side">
<?php include_once 'module/main_history.php';//加载历史浏览?>
<?php include_once 'module/main_landmarks.php';//加载地标信息?>
</div>

</div>
<div id="mask" style="display:none;"></div>
<div id="iframeLoader" style="display:none;"><div id="iframecontent"></div><a href="#" id="closeLoader">关闭</a></div>
<div class="map_pop" id="popMap" style="display:none;">
<a id="delMap" href="javascript:void(0);" class="delete">×</a>
<div class="map_box">
	<div class="map_side">
		<div id="transInfoBox" class="trans_info_box trans_info_hidden">
			<a id="btnTraffic" href="javascript:void(0);" class="toogle">路线</a>
            <div id="trafficDetail" class="trans_info_content"></div>
		</div>			
		<div id="mapIconFilter" class="ico_filter">
			<a href="javascript:void(0);" title="附近酒店" class="hotel"></a>
			<a href="javascript:void(0);" title="附近地铁站" class="train"></a>
			<a href="javascript:void(0);" title="附近景点" class="sight"></a>
			<a href="javascript:void(0);" title="附近餐饮" class="restaurant"></a>
			<a href="javascript:void(0);" title="附近购物" class="shopping"></a>
			<a href="javascript:void(0);" title="附近娱乐" class="entertainment"></a>
		</div>
	</div>
	<div class="map_content" id="mapContent"></div>
</div>
</div>
<!-- bd end -->
<?php include_once 'module/foot.php';//加载底部控制文件 ?>

<input type="hidden" name="postDataUrl" id="postDataUrl" value="<?php echo getNewUrl($UnionSite_domainName."/site/hoteldetail.php?city=".$cityID,$SiteUrlRewriter) ?>">

<script type="text/javascript" src="http://webresource.ctrip.com/code/cquery/cQuery_110421.js"></script>

<?php if($MapKey && $mainHotelSearch->hotelDetailName){?>
<script type="text/javascript" src="<?php echo $UnionSite_domainName?>/site/js/map.pop.js"></script>

<?php }?>
<script type="text/javascript" src="<?php echo $position_jsurl?>"></script>
<script type="text/javascript" src="<?php echo $top_jsurl?>"></script>
<script type="text/javascript" src="<?php echo $index_jsurl?>"></script>
<script type="text/javascript" src="<?php echo $UnionSite_domainName?>/site/js/CtripSelfDefined.js"></script>

</body>
</html>

<?php

ob_end_flush();

?>


