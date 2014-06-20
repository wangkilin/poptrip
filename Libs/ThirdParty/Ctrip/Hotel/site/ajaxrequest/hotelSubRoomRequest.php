<?php
/**
 * 根据酒店的 hotelID,获取酒店子房型
 */
include_once ('../../SDK.config.php');//配置文件加载--必须加载这个文件
header("Content-type: text/html; charset=utf-8"); 
include_once (ABSPATH."sdk/API/Hotel/D_HotelDescription.php");//加载D_HotelSearch这个接口的封装类
include_once (ABSPATH."sdk/API/Hotel/D_HotelSubRoomList.php");//加载D_HotelSearch这个接口的封装类
include_once (ABSPATH."site/module/main_D_hotelSearch.php");//加载搜索的主逻辑

$SiteUrlRewriter='0';
include_once(ABSPATH."include/urlRewrite.php");//加载URL伪静态处理

//http://127.0.0.1:1200/site/ajaxrequest/hotelSubRoomRequest.php?hid=669,653,698&CheckInDate=2013-05-23&CheckOutDate=2013-05-25&city=2,%E4%B8%8A%E6%B5%B7
$cdate=$_GET["CheckInDate"].",".$_GET["CheckOutDate"];
$cdate=empty($_GET["CheckInDate"])?getDateYMD("-").",".getDateYMD_addDay("-",$HotelSearchDayNums):$cdate;
if(empty($_GET['hid']))die('酒店ID 不允许为空!');

//获取符合条件的酒店列表
$mainHotelSearch=new page_D_hotelSearch();
$mainHotelSearch->SiteHotelDefaultImageUrlHotelSearch=$SiteHotelDefaultImageUrl;//定义酒店列表中，默认的图片地址
$mainHotelSearch->isSiteUrlRewriter=$SiteUrlRewriter;//设置本系统是否要做伪静态
$mainHotelSearch->thisUnionSite_domainName=$UnionSite_domainName;//设置系统的域名
$mainHotelSearch->cdate=$cdate;
$mainHotelSearch->HotelList=$_GET['hid'];
$mainHotelSearch->PageSize=$SiteHotelSearch_pagesize;
$mainHotelSearch->getRequsetParameter();//获取参数

$mainHotelSearch->RequestType="get_D_HotelDescription";
$mainHotelSearch->getHotelListResponseXML_URL();//调用酒店简介的接口

$mainHotelSearch->RequestType="get_D_HotelSubRoomList";
$mainHotelSearch->getHotelListResponseXML_URL();//调用酒店子房的接口

$hotelSubRooms=array();
if($mainHotelSearch->hotelSubRooms){
	foreach($mainHotelSearch->hotelSubRooms  as $k=> $v){
		$hotelSubRooms[$k]['hotelSubRooms']=$v;
		if(!empty($mainHotelSearch->hotelDescriptions[$k])){
			$hotelSubRooms[$k]['hotelDescriptions']=$mainHotelSearch->hotelDescriptions[$k];	
		}
	}
	
}
echo json_encode($hotelSubRooms);
die;


?>
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
<?php 
		foreach($hotelRoomsList as $k=> $v){
?>	
		    <tr <?php echo $v['trclass']?>>
				<td style="padding-left: 20px;">
				<a href="<?php echo $v['hotelDetailSubRoomUrl']?>|<?php echo $v['tdID']?>" title="<?php echo $v['RoomName']?>" class="room_pic">
				<?php echo  utf_substr($v['RoomName'],32)?></a>
				</td>
				<td><?php echo  $v['getBedTypeName']?></td>
				<td><?php echo  $v['getBreakFastNames']?></td>
				<td><?php echo  $v['getWireInfo']?></td>
				<td><dfn><?php echo $v['getCurrencyName'].$v['getAvaeragePrices']?></dfn></td>
				<td style="text-align: right;"><?php echo $v['guaranteeHtml']?>
				<?php echo $v['bookingClickHtml']?>
				</td>
			</tr>
<?php }
		if(count($hotelRoomsList)>3){
?>		<p class="hotel_toggle"><a href="#" class="toggle_down toggle_roomtype">所有房型(<?php echo count($hotelRoomsList) ?>)</a></p>

<?php }?>
				</tbody>
				</table>	
		
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
				
						