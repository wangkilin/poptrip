<?php
/**
 * 处理cookie的异步删除
 */
include_once ("../../Common/browseHistoryClass.php");//加载浏览记录的方法
include_once ("../../appData/site.config.php");//加载整站系统的配置文件
/**
 *
 * @var 根据指定的KEY值，删除浏览的酒店记录
 * @param $keys
 * http://127.0.0.1:8888/site/ajaxrequest/browseHistoryRequest.php?bkeys=61565|上海嘉福悦国际大酒店
 */
$hotelDelBrowseKeyes=$_GET["bkeys"];
if($hotelDelBrowseKeyes!=""&&$hotelDelBrowseKeyes!=null)
{
	return hotelBrowseDelete($hotelDelBrowseKeyes);
	
}
/**
 * 
 * 调用cookie重新赋值方法
 * @param $keys
 */
function hotelBrowseDelete($keys)
{
	$browseHistory=new browse_history_class();
	return $browseHistory->deleteListHotel("hotelBrowseHistory", $keys, $SiteHotelBrowserListTotalNums);
}
?>