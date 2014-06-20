<?php
extract($_GET);
$url="http://hotels.ctrip.com/Domestic/Tool/AjaxMapHotelTrafficinfo.aspx?";

if($typetraffic){
	$url .="typetraffic=".$typetraffic."&";
	header("Content-type: text/xml; charset=UTF-8"); 
}else{
	header("Content-type: text/html; charset=GBK"); 
}
if($hotelid)
$url .="hotelid=".$hotelid."&";
if($placeid)
$url .="placeid=".$placeid."&";
if($type)
$url .="type=".$type;

$XMLcontents=@file_get_contents($url);
echo $XMLcontents;
