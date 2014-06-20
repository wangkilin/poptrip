<?php
if($_POST)
{	
    require_once("../../API/CtripUnion.php");
    $cu = new CU('hotel','OTA_HotelRatePlan');
    $rt = $cu->OTA_HotelRatePlan($_POST,'array');
    
    echo "<meta charset='utf-8' />";
    foreach($rt['HotelResponse']['OTA_HotelRatePlanRS']['RatePlans'] as $value)
    {
    	var_dump($value['RatePlan']);
    	echo '<hr/>';
    }
}
else
{
    require "temp.html";
}