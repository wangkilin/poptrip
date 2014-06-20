<?php 
/**
 * 订单详情
 */
include_once ("../include/siteAd.php");//加载广告的处理逻辑
include_once ("../appData/site.config.php");
include_once("../include/urlRewrite.php");//加载URL伪静态处理

include_once ('../SDK.config.php');//配置文件加载--必须加载这个文件
include_once (ABSPATH.'sdk/API/Hotel/D_HotelOrderDetail.php');//加载D_HotelOrderDetail这个接口的封装类
include_once (ABSPATH.'sdk/API/Hotel/OTA_UserUniqueID.php');//加载OTA_UserUniqueID这个接口的封装类
include_once (ABSPATH.'sdk/API/Hotel/OTA_Cancel.php');//加载OTA_Cancel这个接口的封装类

include_once ("../include/url_HotelControl.php");//加载酒店URL路径控制
include_once ('../Common/toolExt.php');

$index_cssurl=$UnionSite_domainName."/site/css/styles.css";//本页面的CSS样式地址

function showStatueBar($status){
	$barHtml='';
	if ($status=='已提交'){
		$barHtml="<li >已提交</li><li class='gray'>确认中</li><li class='gray'>已确认</li><li class='gray'>已付款</li><li class='gray'>已成交</li>";
	}else if($status=='确认中'){
		$barHtml="<li >已提交</li><li  >确认中</li><li class='gray'>已确认</li><li class='gray'>已付款</li><li class='gray'>已成交</li>";
	}else if($status=='已确认'){
		$barHtml="<li >已提交</li><li>确认中</li><li  >已确认</li><li class='gray'> 已付款</li><li class='gray'>已成交</li>";
	}else if($status=='已付款'){
		$barHtml="<li >已提交</li><li>确认中</li><li>已确认</li><li  >已付款</li><li class='gray'>已成交</li>";
	}else if ($status=='已成交'){
		$barHtml="<li >已提交</li><li>确认中</li><li>已确认</li><li  >已付款</li><li >已成交</li>";
	}else if($status=='已取消'){
		$barHtml="<li >已提交</li><li>确认中</li><li>已确认</li><li>未付款</li><li  >已取消</li>";
	}else if ($status=='未提交'){
		$barHtml="<li >未提交</li>";
	}
	echo $barHtml;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cn"> 
<head> 
	<meta http-equiv=Content-Type content="text/html; charset=<?php  echo $SiteCharset;?>"/>
	<title><?php echo '订单详情_'.$UnionSite_Name;?> </title> 
	<link rel="stylesheet" type="text/css" href="<?php echo $index_cssurl?>" /> 
	
	<?php if($UnionSite_Css){?>
<link rel="stylesheet" type="text/css" href="<?php echo $UnionSite_domainName.$UnionSite_Css?>" />

<?php }?>
	</head> 
<body> 
	<?php include_once 'module/header.php';//加载头部文件 ?>
<?php include_once 'module/bar_navigation.php';//加载导航文件
echo	getMainNavigation();//加载默认的导航
?>

<?php 
	$orderid=$_GET['orderid'];
	$checkinname=$_GET['checkinname'];
	$checkindate=$_GET['checkindate']; 
	$checkoutdate=$_GET['checkoutdate']?$_GET['checkoutdate']:date('Y-m-d');
	$uid=$_GET['telephone'];
	$orderstatue=$_GET['orderstatue']?$_GET['orderstatue']:0;
	
	if($_POST['cancle']=='cancle'){//取消订单
		
		//获取到当前的联盟用户的用户ID对应的携程UID
		$OTA_User=new get_OTA_UserUniqueID();
		$OTA_User->UID=$uid; 
		$OTA_User->TelNo=$uid; 
		$returnUID=$OTA_User->getUniqueUID();
		 
		//构造请求
		$OTA_Cancel=new set_OTA_OrderCancel();
		$OTA_Cancel->OrderId=$orderid;
		$OTA_Cancel->ReasonText="不想要了";
		$OTA_Cancel->UID=$returnUID;
		$OTA_Cancel->main();
		$returnXML=$OTA_Cancel->ResponseXML;		 
	 
		$response= json_encode( $returnXML);	
		$msg='';
		if (strstr($response, 'Header')==''){			 
			$msg=$returnXML;			 		 
		}else {						 
			$msg=$returnXML->HotelResponse->OTA_CancelRS->Errors->Error;//"订单取消成功！";
			if (empty($msg))
				$msg="订单取消成功";
		}
		  
	}
	
	if ($orderid){
		//获取到当前的联盟用户的用户ID对应的携程UID
		$OTA_User=new get_OTA_UserUniqueID();
		$OTA_User->UID=$uid; 
		$OTA_User->TelNo=$uid; 
		$returnUID=$OTA_User->getUniqueUID();
		//构造请求
		$D_hotelOrderDetail=new get_D_HotelOrderDetail();
		$D_hotelOrderDetail->OrderID=$orderid;
		$D_hotelOrderDetail->UserID=$returnUID;
		$D_hotelOrderDetail->UserIP=GetIP();
		$D_hotelOrderDetail->main();
		$returnXML=$D_hotelOrderDetail->ResponseXML;
		$v=$returnXML ->DomesticHotelOrderDetail;
	}
?>
	<!-- bd begin --> 
	<div class="bd bd_info"> 
		<div class="path_bar"> 
			<a href="index.php">首页</a> &gt; 订单查询 &gt; 订单详情
		</div> 
		<?php
		 if ($v){
		 	//构造酒店详情url
		 	$orderID=''.$v->OrderId;
			$city=$v->CityID.','.$v->CityName.','.$v->HotelID;		 
			$hname=$v->HotelName;			
			$hotelSearchUrl=new HotelUrlControl($city,'','','',$hname,'','','','1,50','detail');
			$hotelHref=$hotelSearchUrl->returnUrl;
			$hotelHref=getNewUrl($hotelHref,$SiteUrlRewriter);
			
			if (empty($checkinname)){
				$clients=explode(',', $v->ClientName) ;
				$checkinname=$clients[0];
			}
			if (empty($checkindate)){
				$checkindate=date('Y-m-d',strtotime($v->CheckInDate));
				$checkoutdate=date('Y-m-d',strtotime($v->CheckOutDate));
			}
			$backurl="/site/order.php?checkinname=$checkinname&telephone=$uid&checkindate=$checkindate&checkoutdate=$checkoutdate&orderstatue=$orderstatue";
			$newUrl=$UnionSite_domainName.$backurl;
			$newUrl=getNewUrl($newUrl,$SiteUrlRewriter);//做伪静态
		?>		
		<div class="info_progress basefix"> 
			<ul class="basefix"> 
				<!-- 增加类名gray变成灰色状态 --> 				 
				<?php showStatueBar($v->OrderStatus);?>
			</ul> 
		</div> 
		<div class="info_box basefix"> 
			<div class="float_left"> 
				<span>订单号：<?php echo $v->OrderId;?></span><span>预定日期：<?php echo date('Y-m-d',strtotime($v->OrderDate));?></span> 
			</div> 
			<div class="float_right"> 
				<p>总金额：<dfn><?php echo $v->PriceShowInfo;?></dfn><br /></p> 
			</div> 
		</div> 
		<ul class="search_result_list"> 
			<li class="search_result_box"> 
				<h3>入住信息</h3> 
				<div class="hotel_info"> 
					<a target="_blank" href="<?php echo $hotelHref;?>"><?php echo $v->HotelName;?></a> 
					<p><span><?php echo $v->HotelAddr;?></span></p> 
				</div> 
				<p class="bd_info_con">入住日期：<?php echo date('  Y-m-d  ',strtotime($v->CheckInDate));?> 至 <?php echo date('  Y-m-d  ',strtotime($v->CheckOutDate));?><br />入住人：<?php echo $v->ClientName;?><br />房间数：<?php echo $v->Quantity;?>间</p> 
			</li> 
			<li class="search_result_box"> 
				<h3>房间信息</h3> 
				<p class="bd_info_con"><span>房型：<?php echo $v->RoomName;?></span><span>早餐：<?php echo getBreakFastName($v->BreakfastCount)?></span><span>到店时间：<?php echo  date('  H:i  ',strtotime($v->EarlyArrivalTime));?>到<?php echo  date('  H:i  ',strtotime($v->LastArrivalTime));?></span><br />更多需求：<?php echo $v->Remarks;?></p> 
			</li><!-- 
			<li class="search_result_box"> 
				<h3>支付信息</h3> 
				<p class="bd_info_con"><span>支付类型：<?php echo $v->BalanceType;?></span><span>担保情况：12938728395</span></p> 
			</li> 
		 --></ul> 
		<div class="btn_box">
		<form method="post" id="cancleform"> 
		<input type="hidden" value="cancle"  name="cancle" />
			<input type="button" value="订单列表" class="btn_blue" onclick="<?php echo "window.location.href='$newUrl'";?>"/>
			<?php  if($v->OrderStatus!='已取消')	 {?>			
			<input onclick="document.getElementById('cancleform').submit()" type="button" value="取消订单" class="btn_gray" /> 
			<?php }?>
			</form>	
			</div> 
		<?php 
		}else{
			echo '订单不存在....';
		}?>
	</div> 
		<?php //显示提示信息	
			if (!empty($msg)){
				if ($msg=="订单取消成功"){//订单取消成功，弹出提示信息后，跳转至订单查询页面
					echo "<script> alert('$msg');window.location.href='$newUrl'; </script>";
				}else{//订单取消失败，弹出错误信息
					$msg=str_replace('System error:', '', $msg);
					showMsg($msg);
				}
			}
		?>
	<!-- bd end --> 
	<?php include_once 'module/foot.php';//加载底部控制文件 ?>
</body> 

</html>
