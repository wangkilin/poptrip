<?php
/**
 * 订单查询
 */
include_once ("../include/siteAd.php");//加载广告的处理逻辑
include_once ("../appData/site.config.php");
include_once("../include/urlRewrite.php");//加载URL伪静态处理

include_once ('../SDK.config.php');//配置文件加载--必须加载这个文件
include_once (ABSPATH.'sdk/API/Hotel/D_HotelOrderList.php');//加载D_HotelOrderList这个接口的封装类
include_once (ABSPATH.'sdk/API/Hotel/OTA_UserUniqueID.php');//加载OTA_UserUniqueID这个接口的封装类

include_once ("../include/url_HotelControl.php");//加载酒店URL路径控制
$index_cssurl=$UnionSite_domainName."/site/css/styles.css";//本页面的CSS样式地址
$index_jsurl=$UnionSite_domainName."/site/js/order.js";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cn">
<head>
<meta http-equiv=Content-Type content="text/html; charset=<?php  echo $SiteCharset;?>"/>
<title><?php echo '订单查询_'.$UnionSite_Name;?></title>
<link rel="stylesheet" type="text/css" href="<?php echo $index_cssurl?>" />

<?php if($UnionSite_Css){?>
<link rel="stylesheet" type="text/css" href="<?php echo $UnionSite_domainName.$UnionSite_Css?>" />

<?php }?>

</head>
<body>
	<?php include_once 'module/header.php';//加载头部文件  
	?>
	<?php include_once 'module/bar_navigation.php';//加载导航文件 
	  echo	getMainNavigation();//加载默认的导航
	?>
	<!-- bd begin -->
	<div class="bd bd_inquire">
		<div class="path_bar">
			 
	<?php //构造搜索页的副导航
echo getSubTitleNavigation("order","");
?>
		</div>
		<div class="tip_box">
			<span class="i"></span>
			<p>系统自动保留您近一年的订单。</p>
		</div>
		<div class="search_result_list">
			<div class="search_result_box">
			<?php 
					$checkinname=trim($_GET['checkinname']);
					$checkindate=$_GET['checkindate']?$_GET['checkindate']:date('Y-m-01', strtotime(date('Y-m-d'))); 
					$checkoutdate=$_GET['checkoutdate']?$_GET['checkoutdate']:date('Y-m-d', mktime(0,0,0,date('n'),date('t'),date('Y')));
					$uid=$_GET['telephone'];
					$orderstatue=$_GET['orderstatue']?$_GET['orderstatue']:0;
					?>
				<form action="<?php echo $UnionSite_domainName;?>/site/order.php" id="orderform" method="get">
					
					<div class="hotel_date">					
					<p>入住人 <dfn>*</dfn>
						<input type="text" id="username" value="<?php echo $checkinname;?>" name="checkinname" class="input_text input_98"/></p>
					<p>&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;手机号 <dfn>*</dfn>
						<input type="text" id="cellphone" value="<?php echo $uid;?>" name="telephone" class="input_text input_87"/></p>
					<p>&nbsp; &nbsp; &nbsp; <!--酒店名称<input type="text" value="" name class="input_text input_98">--></p>
					<p>入住日期 <dfn>*</dfn>
						<input type="text" id="checkInDate" value="<?php echo $checkindate;?>" name="checkindate" class="input_text input_65"/>
						至<input type="text" id="checkOutDate" value="<?php echo $checkoutdate;?>"  name="checkoutdate" class="input_text input_65"/></p>
					&nbsp; &nbsp; &nbsp; &nbsp; 
						<input type="submit" value="搜 索" class="btn_orange" id="searchlist"/>
					</div>				
						
					<?php							
							if(!empty($checkinname)&&!empty($uid)){
							//获取到当前的联盟用户的用户ID对应的携程UID
							$OTA_User=new get_OTA_UserUniqueID();
							$OTA_User->UID=$uid;//Allianceid_Uid;//在config.php中定义
							$OTA_User->TelNo=$uid;
							$returnUID=$OTA_User->getUniqueUID();
							//echo $returnUID;
							//构造请求
							$D_hotelOrderList=new get_D_HotelOrderList();
							$D_hotelOrderList->CheckInDate=$checkindate;
							$D_hotelOrderList->CheckInName=$checkinname;
							$D_hotelOrderList->CheckOutDate=$checkoutdate;
							$D_hotelOrderList->OrderIDs=""; 
							$D_hotelOrderList->OrderRange=0;
							$D_hotelOrderList->OrderStatus=$orderstatue;
							$D_hotelOrderList->Reservation=0;
							$D_hotelOrderList->UserID=$returnUID;
							$D_hotelOrderList->UserIP=GetIP();
							$D_hotelOrderList->main();
							$returnXML=$D_hotelOrderList->ResponseXML;
							
							 $backurl=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
							 $backurl= urlencode( $backurl);
							$returnXMLDataForList=$returnXML ->DomesticHotelOrderListForList->OrderDetailList;
							if(!empty($returnXMLDataForList)){
							?>
							<table cellspacing="0" cellpadding="0" class="room_list">
							<thead>
								<tr>
									<th style="width: 110px;">订单号</th>
									<th style="width: 260px;">酒店名称</th>
									<th style="width: 100px;">金额</th>
									<th style="width: 100px;">入住日期</th>
									<th style="width: 100px;">离店日期</th>
									<th><select name="orderstatue" id="orderstatue" >
										<option value="0" <?php echo $orderstatue==0?'selected="selected"':'';?>>全部订单</option>
										<option value="1" <?php echo $orderstatue==1?'selected="selected"':'';?>>未提交</option>
										<option value="2" <?php echo $orderstatue==2?'selected="selected"':'';?>>处理中</option>
										<option value="3" <?php echo $orderstatue==3?'selected="selected"':'';?>>已完成</option>
									</select></th>
								</tr>
							</thead>
							<tbody>
							<?php
								
								$orderList=$returnXMLDataForList->DomesticHotelOrderDetailForList;				
								//print_r($orderList);								 
								foreach ($orderList as $v){		//遍历显示订单列表	
									$orderID=''.$v->OrderId;
									$newUrl=$UnionSite_domainName."/site/orderdetail.php?orderid=".$orderID."&checkinname=".$checkinname."&telephone=".$uid."&orderstatue=".$orderstatue."&checkindate=".$checkindate."&checkoutdate=".$checkoutdate;					 						 
									$newUrl=getNewUrl($newUrl,$SiteUrlRewriter);//做伪静态
									
									$city=$v->CityID.','.$v->CityName.','.$v->HotelID;
									$hname=$v->HotelName;
									$cdate=date('Y-m-d',strtotime($v->CheckInDate)).','.date('Y-m-d',strtotime($v->CheckOutDate)) ;									
									$hotelSearchUrl=new HotelUrlControl($city,$cdate,'','',$hname,'','','','1,50','detail');
									$hotelHref=$hotelSearchUrl->returnUrl;
									$hotelHref=getNewUrl($hotelHref,$SiteUrlRewriter)
									//echo json_encode($v);
							?>
								<tr>

									<td><a href="<?php echo $newUrl;?>" target="_self"><?php echo $v->OrderId;?></a></td>
									<td><a href="<?php echo $hotelHref;?>" target="_blank"><?php echo $v->HotelName;?></a></td>
									<td><dfn><?php echo $v->PriceShowInfo;?></dfn></td>
									<td><?php echo date('Y-m-d H:i ',strtotime($v->CheckInDate)) ;?></td>
									<td><?php echo date('Y-m-d H:i ',strtotime($v->CheckOutDate))  ;?></td>
									<td><?php  echo $v->OrderStatus;?></td>
								</tr>
							<?php  
								}?>
								</tbody>
						</table>	
								<?php 								
							}else{
							echo "<div style='padding:20px 0px;text-align:center;'>很抱歉，没有查到符合条件的订单！</div>";
						}
						}
						?>
				 </form>	
			</div>
		</div>
	</div>
<!-- bd end -->
	<?php include_once 'module/foot.php';//加载底部控制文件 ?>
	<script type="text/javascript" src="http://webresource.ctrip.com/code/cquery/cQuery_110421.js"></script>
	<script type="text/javascript" src="<?php echo $index_jsurl?>"></script>
	<script type="text/javascript" src="<?php echo $UnionSite_domainName?>/site/js/CtripSelfDefined.js"></script>
</body>
</html>
