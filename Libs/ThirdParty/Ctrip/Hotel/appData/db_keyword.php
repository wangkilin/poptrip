<?php 
/**  
* $keywordsArray 网站关键字数组 ，索引为页面名 
* 如：'index.php'=>array('title'=>'首页','keywords'=>'酒店预订','description'=>'携程酒店预订'),  
* @var 二维数组 
 */ 
$keywordsArray=array( 
'index.php'=>array('title'=>'酒店预订_{sitename}','keywords'=>'酒店预订','description'=>'{sitename}为您提供酒店预订,宾馆预定等服务'),
'hotelsearch.php'=>array('title'=>'{city}{position}{brand}{star}{price}{hotelname}酒店预订_{city}{position}{brand}{star}{price}{hotelname}宾馆预订_{city}{position}{brand}{star}{price}{hotelname}旅馆住宿_{city}_{sitename}','keywords'=>'{city}{position}{brand}{star}{price}{hotelname}酒店预订,{city}{position}{brand}{star}{price}{hotelname}酒店价格查询,{city}酒店,{sitename}','description'=>'{sitename}为您提供最权威的{city}{position}{brand}{star}{price}{hotelname}酒店预订,价格查询，为您出行提供优质的{city}{position}{brand}{star}{price}{hotelname}宾馆预订和{city}{position}{brand}{star}{price}{hotelname}旅馆住宿等服务'),
'brand.php'=>array('title'=>'全国品牌酒店预订_全国连锁酒店预订_{sitename}','keywords'=>'国际品牌酒店，国内品牌酒店，品牌酒店，品牌酒店预订，品牌酒店查询，海外品牌酒店，酒店品牌','description'=>'{sitename}为您提供品牌酒店，连锁酒店,快捷酒店等酒店信息,经济型连锁酒店,豪华型快捷酒店共300多个品牌。'),
'hoteldetail.php'=>array('title'=>'{hotelname}免费预订_点评_怎么样_地图_{sitename}','keywords'=>'{hotelname}预订，{hotelname}价格，{hotelname}信息，{city}酒店预订，{sitename}','description'=>'{hotelname}酒店详情,{sitename}为您提供最好的,最权威的{hotelname}预定, {hotelname}宾馆查询, {hotelname}酒店'),
'brandinfo.php'=>array('title'=>'连锁酒店_{brand}连锁酒店查询_{brand}连锁酒店预订_{brand}酒店预订网_{sitename}','keywords'=>'{brand}酒店，{brand}酒店预订，{brand}酒店价格，连锁酒店，{brand}连锁酒店查询','description'=>'{sitename}为您提供最好,最权威的连锁酒店,{brand}连锁酒店查询,{brand}连锁酒店预订, {brand}酒店预订网。'),
'branddetail.php'=>array('title'=>'{city}{brand}酒店预订,价格查询_{city}{brand}宾馆住宿信息_{sitename}','keywords'=>'{city}{brand}酒店预订,{city}{brand}酒店价格查询,{city}{brand}宾馆住宿,{sitename}','description'=>'{sitename}为您提供{city}{brand}酒店预订,价格查询，为您出行提供优质的{city}{brand}宾馆住宿预订服务和{city}{brand}宾馆住宿信息'),
'cityhotel.php'=>array('title'=>'全国特价酒店预订_全国酒店城市大全_{sitename}','keywords'=>'酒店,酒店分布,国内酒店','description'=>'{sitename}为您提供国内酒店预订,国内宾馆预定等服务。'),
'hotelcomment.php'=>array('title'=>'{city}酒店点评_{city}哪个酒店好？_{city}酒店怎么样？ {sitename}','keywords'=>'酒店预订,酒店点评, {city}哪个酒店好','description'=>'{sitename}为您提供最权威,最新的酒店点评数据,国内酒店预订,国内宾馆预定等服务。'),
'citylandmark.php'=>array('title'=>'{city}酒店地图导航_{sitename}','keywords'=>'{city}酒店地图，酒店地图导航，{sitename}','description'=>'{sitename}为您提供{city}酒店地图，为您出行提供优质的{city}酒店导航服务，便捷查找全部{city}酒店信息。'),
'hotelhotlist.php'=>array('title'=>'{city}{star}酒店排行榜_{sitename}','keywords'=>'酒店排行榜，{city}酒店排行榜，{city}{star}酒店排行榜，{sitename}','description'=>'{sitename}为您提供{city}各酒店的排行信息，让您了解各酒店的热评信息，为您出行选择合适的酒店提供参考。')
)

?>