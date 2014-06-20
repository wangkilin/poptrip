<?php
/**
 * 首页最新评论列表
 */

include_once ('../SDK.config.php');//配置文件加载--必须加载这个文件
include_once (ABSPATH."include/url_HotelControl.php");//加载酒店URL路径控制

$cityID=$_GET['defaultcityid']?$_GET['defaultcityid']:$SiteDefaultCityID.",".$SiteDefaultCityName;//获取城市的ID，默认是上海
$CityArr=explode(',', $cityID);
$cityName=$CityArr['1'];// 当前城市的名称
$cityIdValue=$CityArr['0']?$CityArr['0']:$SiteDefaultCityID;// 当前城市ID
$page=$_GET['page']?$_GET['page']:'1';// 当前页数

if($pageType=='1')$HotelNums=$HotHotelCommentNumsList>'20'?'20':$HotHotelCommentNumsList;
else $HotelNums=$HotHotelCommentNumsIndex>'20'?'20':$HotHotelCommentNumsIndex;

$pagesize=10;
$numsStart=$pagesize*($page-1);
$numsEnd=$pagesize*$page;

//获取热门酒店ID
include_once (ABSPATH.'sdk/API/Hotel/D_HotelHotSale.php');//加载D_HotelHotSale这个接口的封装类
include_once (ABSPATH.'site/module/main_HotelHotSaleRequest.php');//加载酒店热卖处理逻辑
//调用品牌的城市分布接口
$hotHotelListList=new get_HotSaleHotelRequest($cityIdValue,'D',$HotelNums);
$hotHotelListXML=$hotHotelListList->responseXML;


if(!empty($hotHotelListXML->SearchHotSaleHotelResponse->SearchHotSaleHotelList->SearchHotSaleHotel)){
	$i=0;
	foreach ($hotHotelListXML->SearchHotSaleHotelResponse->SearchHotSaleHotelList->SearchHotSaleHotel as  $v ){
		$hotelId=$v->HotelID;
		if($pageType=='1'){
			if($i>=$numsStart && $i<$numsEnd)
			$HotelList=$HotelList?$HotelList.",".$hotelId:$hotelId;	
		}else{
			$HotelList=$HotelList?$HotelList.",".$hotelId:$hotelId;	
		}
		$i++;
	}
}

//调用店的点评关键字接口

if($HotelList){
	//最新热门酒店点评  
	include_once (ABSPATH.'sdk/API/Hotel/D_HotelHotComment.php');//加载D_HotelHotComment这个接口的封装类
	include_once (ABSPATH.'site/module/main_HotelHotComment.php');//加载最新热门酒店点评 处理逻辑
	$HotelCommentKey=new get_HotelHotComment($HotelList);
	$TopHotelCommentXML=$HotelCommentKey->responseXML;
	$TopHotelComment=array();
	$i=0;
	if(!empty($TopHotelCommentXML->DomesticHotelHotComment->HotCommentList->DomesticHotCommentInfoEntity)){
		foreach ($TopHotelCommentXML->DomesticHotelHotComment->HotCommentList->DomesticHotCommentInfoEntity as  $v ){	
		
			$TopHotelComment[$i]['UpdateTime']=(string)$v->UpdateTime;
			$TopHotelComment[$i]['WritingDate']=(string)$v->WritingDate;
			$TopHotelComment[$i]['Content']=(string)$v->Content;	
			$TopHotelComment[$i]['Title']=(string)$v->Title;	
			$TopHotelComment[$i]['HotelID']=(string)$v->HotelID;	
			$i++;
		}
	}
	
	
	//默认酒店入住以及离开时间
	$CheckInDate=getDateYMD('-');
	$CheckOutDate=getDateYMD_addDay('-',$HotelSearchDayNums);
	$p2=urldecode("$CheckInDate,$CheckOutDate");
}

//评论总数应该为酒店总数
$TopHotelCommentNums=count($hotHotelListXML->SearchHotSaleHotelResponse->SearchHotSaleHotelList->SearchHotSaleHotel);

?>
<div class="<?php if($pageType=='1')echo "comment_box box_blue";else echo"box_blue"; ?>">
				<h2 ><?php if($pageType=='1')echo $cityName;else echo"最新"; ?>酒店点评</h2>
				<div class="content">
				
			<?php 
				if(!empty($TopHotelComment)){
					$i=0;
					foreach ($TopHotelComment as $v){ 
						$i++;
						$titleArr=explode('＂', $v['Title']);
						$HotelName=$titleArr['1']?$titleArr['1']:$v['Title'];
						$time=$v['UpdateTime']?$v['UpdateTime']:$v['WritingDate'];
						$p1=$cityID.",".$v['HotelID'];
						//构造酒店详细页URL
						$getHotelDetailUrl=new HotelUrlControl($p1, $p2, "", "", "", "", "", "", "", "detail");
						$url=$getHotelDetailUrl->returnUrl;
						$hotelDetailUrl=getNewUrl($url,$SiteUrlRewriter);//构造酒店详细的URL地址
						
			?>
					<dl <?php if($i=='1' && $pageType=='1'){?>class="border_none"<?php }?>>
						<dt><a href="<?php echo $hotelDetailUrl;?>" title="<?php  echo $HotelName;?>"><?php echo $HotelName;?></a></dt>
						<dd><SPAN title="<?php echo $v['Content']?>"><?php echo $v['Content']?></SPAN><?php echo utf_substr(str_replace('T',' ',$time),33)?></dd>
					</dl>
			<?php }}?>	
		
	<?php if($pageType=='1'){?>	
	<div class="page_ctrl basefix">
		<?php 
		if(!empty($TopHotelCommentNums))
		{
			//加载底部的分页控件
			include_once ('module/main_hotelSearch.php');//加载搜索的主逻辑
			include_once ("../include/SubPages.php");//加载分页类
			$pageUrl=$UnionSite_domainName."/site/hotelcomment.php?defaultcityid=".$cityID."&page=...";
			$pageUrl=getNewUrl($pageUrl,$SiteUrlRewriter);
			$subPages4=new SubPages(10,$TopHotelCommentNums,$page,5,$pageUrl,4);
			
		?>
		<div class="page_value"><span>到</span> <input class="input_text"
			type="text" id="inputPageNums" name="inputPageNums"
			value="<?php echo $page;?>"> <span>页</span> <input
			class="submit" type="button" name=""
			onclick="doInputPageNumChanage('<?php echo $pageUrl;?>',<?php echo $subPages4->pageNums;?>)"
			value="确定">
		</div>
		<script>
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
				window.open(url,"_self");
			}
		</script>
	<?php }?>	
	</div>				
	<?php }?>			
			<br>
</div>

	<?php if($pageType=='1'){?>
		<div class="more"><a href="#" class="togglelist">选择其他城市</a><ul id="toggle_hot" class="city_list_popup" style="display:none;"></ul></div></div>
	<?php }else{?>
		<a href="<?php echo getNewUrl($UnionSite_domainName."/site/hotelcomment.php?defaultcityid=".$cityID,$SiteUrlRewriter)?>" class="more">更多</a><?php }?>
</div>