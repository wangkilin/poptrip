<?php
/**
 * 酒店排行榜1
 */

//若需要AJAX调用，则放开
/*
include_once ('../SDK.config.php');//配置文件加载--必须加载这个文件
include_once (ABSPATH.'sdk/API/Hotel/D_HotelSearch.php');//加载D_HotelSearch这个接口的封装类
include_once (ABSPATH.'site/module/List_hotelSearch.php');//加载搜索的主逻辑
include_once(ABSPATH."include/urlRewrite.php");//加载URL伪静态处理
include_once (ABSPATH."include/url_HotelControl.php");//加载酒店URL路径控制




//示例：http://127.0.0.1:8888/site/ajaxrequest/hotelRandkRequest.php?city=2,上海&op=one&newtype=open
$cityID=$_GET['city']?$_GET['city']:$SiteDefaultCityID.",".$SiteDefaultCityName;//获取城市的ID，默认是上海
$op=$_GET['op']?$_GET['op']:"one";//获取页面类别，one=>初始页面 more=>更多页面
$newtype=$_GET['newtype']?$_GET['newtype']:"open";//获取数据类别，默认更多最新开业
$page=$_GET['page']?$_GET['page']:"1";//
$CityArr=explode(',', $cityID);
$cityIdValue=$CityArr['0'];// 当前城市的ID
*/
//默认入住以及离店时间
$CheckInDate=getDateYMD('-');
$CheckOutDate=getDateYMD_addDay('-',$HotelSearchDayNums);
$p2=urldecode("$CheckInDate,$CheckOutDate");


//最新预订
include_once (ABSPATH.'sdk/API/Hotel/D_NewBookingHotel.php');//加载D_NewBookingHotel这个接口的封装类
include_once (ABSPATH.'site/module/main_NewBookingHotel.php');//加载搜索的主逻辑
// 最新开业
include_once (ABSPATH.'sdk/API/Hotel/D_SearchNewOpenHotel.php');//加载D_SearchNewOpenHotel这个接口的封装类
include_once (ABSPATH.'site/module/main_SearchNewOpenHotel.php');//加载搜索的主逻辑
//最新加盟
include_once (ABSPATH.'sdk/API/Hotel/D_ContractHotel.php');//加载D_ContractHotel这个接口的封装类
include_once (ABSPATH.'site/module/main_ContractHotel.php');//加载搜索的主逻辑

if($op=='one'){
	//最新开业数据
	$NewOpenHotel=new get_NewOpenHotel($HotelNewOpenTime,$cityIdValue);
	$HotelNewOpenArr=$NewOpenHotel->responseXML;
	if(!empty($HotelNewOpenArr->DomesticNewOpenHotelResponse->NewOpenHotelList->DomesticNewOpenHotelDetail)){
		$i=0;
		$OpenYearArr=array();//酒店ID以及对应的最新开业时间
		foreach ($HotelNewOpenArr->DomesticNewOpenHotelResponse->NewOpenHotelList->DomesticNewOpenHotelDetail as  $v ){
			$i++;
			if($i>10){//取前10条数据，防止接口取出酒店数据不足5条
				break;
			}
			$hotelId=$v->HotelID;
			$OpenYear=utf_substr(str_replace('T',' ',$v->OpenYear),10);
			$HotelNewOpenList=$HotelNewOpenList?$HotelNewOpenList.",".$hotelId:$hotelId;
			$OpenYearArr["$hotelId"]=$OpenYear;
		}
	}
	//调用最新预订接口
	$NewBookingHotel=new get_NewBookingHotel($cityIdValue,'148',$page,'10');
	$HotelNewBookingArr=$NewBookingHotel->responseXML;
	//最新预定
	if(!empty($HotelNewBookingArr->DomesticNewBookingHotelResponse->NewBookingHotel->DomesticNewBookingHotelDetail)){
		$DifTimeArr=array();//酒店ID以及对应的预定时差
		foreach ($HotelNewBookingArr->DomesticNewBookingHotelResponse->NewBookingHotel->DomesticNewBookingHotelDetail as  $v ){
			$hotelId=$v->HotelID;
			$DifTime=DifTime(time()-strtotime($v->LatestBookTime));
			$HotelNewBookingList=$HotelNewBookingList?$HotelNewBookingList.",".$hotelId:$hotelId;
			$DifTimeArr["$hotelId"]=$DifTime;
		}
	}
	//最新加盟
	$ContractHotel=new get_ContractHotel($cityIdValue,$page,'10',$HotelNewContractTime);
	$HotelNewContractArr=$ContractHotel->responseXML;
	
	if(!empty($HotelNewContractArr->DomesticContractHotelResponse->ContractDetails->DomesticContractDetail)){
		foreach ($HotelNewContractArr->DomesticContractHotelResponse->ContractDetails->DomesticContractDetail as  $v ){
			$hotelId=$v->HotelID;
			$HotelNewContractList=$HotelNewContractList?$HotelNewContractList.",".$hotelId:$hotelId;
		}
	}
	$pagesize='30';
	//酒店ID字符串
	$HotelList=$HotelNewOpenList.",".$HotelNewBookingList.",".$HotelNewContractList;
}else{
	
	if($newtype=='open'){
		//最新开业数据
		$NewOpenHotel=new get_NewOpenHotel($HotelNewOpenTime,$cityIdValue);
		$HotelNewOpenArr=$NewOpenHotel->responseXML;
		if(!empty($HotelNewOpenArr->DomesticNewOpenHotelResponse->NewOpenHotelList->DomesticNewOpenHotelDetail)){
			$i=0;
			$HotelDataArr=array();//酒店ID以及对应的最新开业时间
			foreach ($HotelNewOpenArr->DomesticNewOpenHotelResponse->NewOpenHotelList->DomesticNewOpenHotelDetail as  $v ){
				$i++;
				if($i>12){//取前12条数据
					break;
				}
				$hotelId=$v->HotelID;
				$OpenYear=utf_substr(str_replace('T',' ',$v->OpenYear),10);
				$HotelDataArr["$hotelId"]=$OpenYear;
				$HotelList=$HotelList?$HotelList.",".$hotelId:$hotelId;
			}
		}
	}elseif($newtype=='booking'){
		//调用最新预订接口
		$NewBookingHotel=new get_NewBookingHotel($cityIdValue,'148',$page,'12');
		$HotelNewBookingArr=$NewBookingHotel->responseXML;
		//最新预定
		$HotelTotalNums=$HotelNewBookingArr->DomesticNewBookingHotelResponse->AllCount;
		if(!empty($HotelNewBookingArr->DomesticNewBookingHotelResponse->NewBookingHotel->DomesticNewBookingHotelDetail)){
			$HotelDataArr=array();//酒店ID以及对应的预定时差
			foreach ($HotelNewBookingArr->DomesticNewBookingHotelResponse->NewBookingHotel->DomesticNewBookingHotelDetail as  $v ){
				$hotelId=$v->HotelID;
				$DifTime=DifTime(time()-strtotime($v->LatestBookTime));
				$HotelList=$HotelList?$HotelList.",".$hotelId:$hotelId;
				$HotelDataArr["$hotelId"]=$DifTime;
			}
		}
		
	}elseif($newtype=='contract'){
		//最新加盟
		$ContractHotel=new get_ContractHotel($cityIdValue,$page,'12',$HotelNewContractTime);
		$HotelNewContractArr=$ContractHotel->responseXML;
		$HotelTotalNums=$HotelNewContractArr->DomesticContractHotelResponse->AllCount;
		if(!empty($HotelNewContractArr->DomesticContractHotelResponse->ContractDetails->DomesticContractDetail)){
			foreach ($HotelNewContractArr->DomesticContractHotelResponse->ContractDetails->DomesticContractDetail as  $v ){
				$hotelId=$v->HotelID;
				$HotelList=$HotelList?$HotelList.",".$hotelId:$hotelId;
			}
		}
	}
	$pagesize='12';
	$pageUrl=getHotelHotListUrl($cityID,$SumType,"more",$starlevel,$newtype,"...");
}
if($cityID && strlen($HotelList)>0)
{
	$List_hotelSearch=new List_hotelSearch($cityID,$pagesize,$HotelList,'3','1');
	$HotelListArr=array();//酒店数据的数组
	$HotelListArr=$List_hotelSearch->HotelListArr;
}
?>
<?php if($op=='one'){?>	
	<div class="hot_sale_box box_blue basefix">
			<h3 class="hot_sale_title">最新开业</h3>
		<?php 
		 	if($OpenYearArr){
		 		$i=0;
				foreach($OpenYearArr as $k=> $v){
			 		$HotelID=$k;
					if($i>4){//取前5条数据
						break;
					}
			 		if($HotelListArr[$HotelID]['HotelName']){
			 			$i++;
		?>	
			<dl class="hotel_detail basefix">
				<dd class="hotel_pic"><span class="hot_sale_tri hot_sale_tri_blue"><?php echo $i;?></span>
				<a href="<?php echo $HotelListArr[$HotelID]['url'];?>" title="<?php echo $HotelListArr[$HotelID]['HotelName'];?>"><img width="100" height="75" style="background: url('http://pic.c-ctrip.com/hotels110127/brand_logo.jpg') no-repeat scroll 0 1px transparent;display: block;" src="<?php echo $HotelListArr[$HotelID]['HotelPic'];?>" alt="<?php echo $HotelListArr[$HotelID]['HotelName'];?>" /></a></dd>
				<dt><h3><a href="<?php echo $HotelListArr[$HotelID]['url'];?>" title="<?php echo $HotelListArr[$HotelID]['HotelName'];?>"><?php echo utf_substr($HotelListArr[$HotelID]['HotelName'],30);?></a></h3></dt>
				<dd class="basefix"><dfn><?php echo $HotelListArr[$HotelID]['CurrencyMinPrice'];?><span><?php echo $HotelListArr[$HotelID]['MinPrice'];?></span></dfn>起</dd>
				<dd><p><?php echo $HotelListArr[$HotelID]['ZoneName'];?></p>
				<p><?php echo $v;?>开业</p></dd>
			</dl>
		<?php }}}?>	
			<a href="javascript:;" rel="<?php echo getHotelHotListUrl($cityID,$SumType,"more",$starlevel,"open",$page)?>" onclick="CtripSelfPassParams('POST', $('#postDataUrl').value(), this.rel, '_self')" class="more">更多</a>
		</div>
		<div class="hot_sale_box box_blue basefix">
			<h3 class="hot_sale_title">最新预定</h3>
		<?php 
		 	if($DifTimeArr){
		 		$i=0;
				foreach($DifTimeArr as $k=> $v){
			 		$HotelID=$k;
					if($i>4){//取前5条数据
						break;
					}
			 		if($HotelListArr[$HotelID]['HotelName']){
			 			$i++;
			 			$StarInfo=get_star_info($HotelListArr[$HotelID]['Star'],$HotelListArr[$HotelID]['Rstar']);//
						$showTitle=$StarInfo['0'];
						$CustomerEvalName=$StarInfo['1'];
			 			
			 			
			 			
		?>		
			<dl class="hotel_detail basefix">
				<dd class="hotel_pic"><span class="hot_sale_tri hot_sale_tri_blue"><?php echo $i;?></span>
				<a href="<?php echo $HotelListArr[$HotelID]['url'];?>" title="<?php echo $HotelListArr[$HotelID]['HotelName'];?>"><img width="100" height="75" style="background: url('http://pic.c-ctrip.com/hotels110127/brand_logo.jpg') no-repeat scroll 0 1px transparent;display: block;" src="<?php echo $HotelListArr[$HotelID]['HotelPic'];?>" alt="<?php echo $HotelListArr[$HotelID]['HotelName'];?>" /></a></dd>
				<dt><h3><a href="<?php echo $HotelListArr[$HotelID]['url'];?>" title="<?php echo $HotelListArr[$HotelID]['HotelName'];?>"><?php echo utf_substr($HotelListArr[$HotelID]['HotelName'],30);?></a></h3></dt>
				<dd class="basefix">
				<span  title="<?php echo $showTitle;?>" class="<?php echo $CustomerEvalName;?>"></span><dfn><?php echo $HotelListArr[$HotelID]['CurrencyMinPrice'];?><span><?php echo $HotelListArr[$HotelID]['MinPrice'];?></span></dfn>起</dd>
				<dd><p><?php echo $HotelListArr[$HotelID]['ZoneName'];?></p>
				<p class="history"><?php echo $v;?>前有人预定</p></dd>
			</dl>
			<?php }}}?>		
			<a href="javascript:;" rel="<?php echo getHotelHotListUrl($cityID,$SumType,"more",$starlevel,"booking",$page)?>" onclick="CtripSelfPassParams('POST', $('#postDataUrl').value(), this.rel, '_self')" class="more">更多</a>
		</div>
		<div class="hot_sale_box new_join box_blue margin_none basefix">
			<h3 class="hot_sale_title">最新加盟</h3>
			<?php 
			 	$HotelIdArr=explode(',',$HotelNewContractList);
			 	if($HotelIdArr){
			 		$i=0;
					foreach($HotelIdArr as $v){
				 		$HotelID=$v;
						if($i>4){//取前5条数据
							break;
						}
				 		if($HotelListArr[$HotelID]['HotelName']){
				 			$i++;
			?>		
			<dl class="hotel_detail basefix">
				<dd class="hotel_pic"><span class="hot_sale_tri hot_sale_tri_blue"><?php echo $i;?></span>
				<a href="<?php echo $HotelListArr[$HotelID]['url'];?>" title="<?php echo $HotelListArr[$HotelID]['HotelName'];?>"><img width="100" height="75" style="background: url('http://pic.c-ctrip.com/hotels110127/brand_logo.jpg') no-repeat scroll 0 1px transparent;display: block;" src="<?php echo $HotelListArr[$HotelID]['HotelPic'];?>" alt="<?php echo $HotelListArr[$HotelID]['HotelName'];?>" /></a></dd>
				<dt><h3><a href="<?php echo $HotelListArr[$HotelID]['url'];?>" title="<?php echo $HotelListArr[$HotelID]['HotelName'];?>"><?php echo utf_substr($HotelListArr[$HotelID]['HotelName'],30);?></a></h3></dt>
				<dd class="basefix"><dfn><?php echo $HotelListArr[$HotelID]['CurrencyMinPrice'];?><span><?php echo $HotelListArr[$HotelID]['MinPrice'];?></span></dfn>起</dd>
				<dd><p><?php echo $HotelListArr[$HotelID]['ZoneName'];?></p>
				</dd>
			</dl>
			<?php }}}?>	
			<a href="javascript:;" rel="<?php echo getHotelHotListUrl($cityID,$SumType,"more",$starlevel,"contract",$page)?>" onclick="CtripSelfPassParams('POST', $('#postDataUrl').value(), this.rel, '_self')" class="more">更多</a>
		</div>

<?php }else{?>
	<div class="box_blue basefix">
			<h3 class="hot_sale_title">最新<?php 	if($newtype=='open')echo "开业";elseif($newtype=='booking')echo "预定";else echo "加盟";?></h3>
			<div class="hot_sale_new basefix">
			<?php 
		 	$HotelIdArr=explode(',',$HotelList);
		 	if($HotelIdArr){
		 		$i=0;
				foreach($HotelIdArr as $v){
			 		$HotelID=$v;
			 		if($HotelListArr[$HotelID]['HotelName']){
			 			$i++;
			 			$StarInfo=get_star_info($HotelListArr[$HotelID]['Star'],$HotelListArr[$HotelID]['Rstar']);//
						$showTitle=$StarInfo['0'];
						$CustomerEvalName=$StarInfo['1'];
			 			
			 			
		?>	
				<dl class="hotel_detail basefix">
				<dd class="hotel_pic"><span class="hot_sale_tri hot_sale_tri_blue"><?php echo $i;?></span>
				<a href="<?php echo $HotelListArr[$HotelID]['url'];?>" title="<?php echo $HotelListArr[$HotelID]['HotelName'];?>"><img width="100" height="75" style="background: url('http://pic.c-ctrip.com/hotels110127/brand_logo.jpg') no-repeat scroll 0 1px transparent;display: block;" src="<?php echo $HotelListArr[$HotelID]['HotelPic'];?>" alt="<?php echo $HotelListArr[$HotelID]['HotelName'];?>" /></a></dd>
				<dt><h3><a href="<?php echo $HotelListArr[$HotelID]['url'];?>" title="<?php echo $HotelListArr[$HotelID]['HotelName'];?>"><?php echo utf_substr($HotelListArr[$HotelID]['HotelName'],30);?></a></h3></dt>
				<dd class="basefix"><?php if($newtype=='booking'){?>
				<span  title="<?php echo $showTitle;?>" class="<?php echo $CustomerEvalName;?>"></span>
				<?php }?>
				<dfn><?php echo $HotelListArr[$HotelID]['CurrencyMinPrice'];?><span><?php echo $HotelListArr[$HotelID]['MinPrice'];?></span></dfn>起</dd>
				<dd><p><?php echo $HotelListArr[$HotelID]['ZoneName'];?></p>
				<p <?php if($newtype=='booking'){?> class="history"<?php }?>>
				<?php echo $HotelDataArr["$HotelID"];?><?php if($newtype=='open'){?>开业<?php }else if($newtype=='booking'){?>前有人预定
			   <?php }?></p></dd>
			</dl>
		<?php }}}?>	
			</div>
			<!-- 更改a标签的类名来改变状态 -->
			
			<?php if($newtype=='booking'||$newtype=='contract'){?>
			<div class="page_ctrl basefix">
	<?php 
	if($HotelTotalNums)
	{
		//加载底部的分页控件
		include_once ('module/main_hotelSearch.php');//加载搜索的主逻辑
		include_once ("../include/SubPages.php");//加载分页类
		//echo $pageUrl."ss";
		$subPages4=new SubPages('12',$HotelTotalNums,$page,5,$pageUrl,6);
	?>
<div class="page_value"><span>到</span> <input class="input_text"
	type="text" id="inputPageNums" name="inputPageNums"
	value="<?php echo $page;?>"> <span>页</span> <input
	class="submit" type="button" name=""
	onclick="doInputPageNumChanage('<?php echo $pageUrl;?>',<?php echo $subPages4->pageNums;?>)"
	value="确定"></div>
	<?php
}
?>
			</div>
			
		<?php }?>	
			
			<a href="javascript:;" rel="<?php echo getHotelHotListUrl($cityID,$SumType,"one",$starlevel,"","")?>" onclick="CtripSelfPassParams('POST', $('#postDataUrl').value(), this.rel, '_self')" class="more">返回</a>
		</div>
	</div>

<script>
/**
 * 当index页面上的城市切换时，要做页面的跳转
 */
function doInputPageNumChanage(urlhost,totalPageNum)
{
	var inputPageNums=document.getElementById("inputPageNums").value;
	//要对输入的值做数字校验.如果输入的是非法的字符，则页面不做跳转，将输入的数据直接替换成原本设置的页码
	//输入值从1开始 
	if(isNaN(inputPageNums) ||  inputPageNums<1){
		alert("请输入大于0的整数");
		return false;	
	}
	if(totalPageNum<inputPageNums)
	{
		inputPageNums=totalPageNum;
	}
	var url=urlhost.replace("...",inputPageNums);

	CtripSelfPassParams('POST', $('#postDataUrl').value(), url, '_self')

	
	//window.open(url,"_self");
}
</script>


<?php }?>


	