<?php
/*
 * 浏览记录
 */
include_once ("../include/url_HotelControl.php");//加载酒店URL路径控制

$listHistoryMainHistory="";//显示数据
if($_COOKIE['hotelBrowseHistory']!=null&&$_COOKIE['hotelBrowseHistory']!="")
{
	$cookieHotelBrowse=$_COOKIE['hotelBrowseHistory'];
	if(strpos($cookieHotelBrowse, "|")>0)
	{
		//说明有数据
		$historyArray=explode(",", $cookieHotelBrowse);
		foreach ($historyArray as $v){
			if(strpos($v, "|")>0)
			{
				$id_name_array=explode("|", $v);
				if(count($id_name_array)>0)
				{
					$p2=$_GET["cdate"];
					$p3=$_GET["stb"];
					$p5=$_GET["hname"];
					$p6=$_GET["lzod"];
					$p7=$_GET["hf"];
					$p8=$_GET["oy"];
					$p9=$_GET["pf"];
					$p1=$id_name_array[2].','.$id_name_array[3].','.$id_name_array[0];
				
					$hotelSearchUrl=new HotelUrlControl($p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,"detail");
					$url=$hotelSearchUrl->returnUrl;
					$url=getNewUrl($url,$SiteUrlRewriter);
					$showNames=utf_substr($id_name_array[1],24);

					$listHistoryMainHistory=$listHistoryMainHistory."<li><a href=\"$url\" title=\"$id_name_array[1]\">$showNames</a><a href=\"#\" rel=\"$v\" class=\"delete\">×</a></li>";
				}
			}

		}
			
	}
}
if(strlen($listHistoryMainHistory)>0){
?>
<div class="search_side_box">
<h3>浏览记录</h3>
<ul class="side_list history">
<?php echo $listHistoryMainHistory;?>
</ul>
</div>
<?php }?>
