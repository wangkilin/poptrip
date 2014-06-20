<?php
$bookingcityUrl=$UnionSite_domainName."/site/hotelsearch.php?city={0},{1}&pf=1,".$SiteHotelSearch_pagesize;//定义链接的原始地址
$bookingcityUrl=getNewUrl($bookingcityUrl,$SiteUrlRewriter);//加载伪静态的判断函数


/**
 * 
 * 替换城市的ID和城市的名称
 * @param $search_P1  被替换值1
 * @param $search_P2  被替换值2
 * @param $replace_P1 替换值1
 * @param $replace_P2 替换值2
 * @param $subjectString  要替换的值
 */
function this_replaceStr($search_P1,$search_P2,$replace_P1,$replace_P2,$subjectString){
	$coutw=str_replace($search_P1,$replace_P1,$subjectString);
	$coutw=str_replace($search_P2,$replace_P2,$coutw);
	return $coutw;
}
$cityIDArray=array("1","2","3","4","58","59","17","32","30","12","28","14","10","6","7","477","375","43","25","451","144","206","559","13","34","223","31","105","258","33","37","23","278","376","158","213","871","380","42","491","19","251","22","536","15","38","91","428","5");
$cityNameArray=array("北京酒店预订","上海酒店预订","天津酒店预订","重庆酒店预订","香港酒店预订","澳门酒店预订","杭州酒店预订","广州酒店预订","深圳酒店预订","南京酒店预订","成都酒店预订","苏州酒店预订","西安酒店预订","大连酒店预订","青岛酒店预订","武汉酒店预订","宁波酒店预订","三亚酒店预订","厦门酒店预订","沈阳酒店预订","济南酒店预订","长沙酒店预订","郑州酒店预订","无锡酒店预订","昆明酒店预订","东莞酒店预订","珠海酒店预订","太原酒店预订","福州酒店预订","桂林酒店预订","丽江酒店预订","黄山酒店预订","合肥酒店预订","南昌酒店预订","长春酒店预订","常州酒店预订","阳朔酒店预订","南宁酒店预订","海口酒店预订","温州酒店预订","舟山酒店预订","佛山酒店预订","绍兴酒店预订","义乌酒店预订","扬州酒店预订","贵阳酒店预订","九寨沟酒店预订","石家庄酒店","哈尔滨酒店预订");
$coutwHtml="";
for($i=0;$i<count($cityIDArray);$i++)
{
	$cityName=this_replaceStr("预订","酒店","","",$cityNameArray[$i]);
	$newStr=this_replaceStr("{0}","{1}",$cityIDArray[$i],$cityName,$bookingcityUrl);//新的地址
	
	
	$coutwHtml=$coutwHtml."<li><a href='$newStr' title='预订$cityName 酒店'>$cityNameArray[$i]</a></li>";
}
echo $coutwHtml;
?>