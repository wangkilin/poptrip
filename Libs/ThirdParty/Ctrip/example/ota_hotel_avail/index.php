<?php
if($_POST)
{	
    require_once("../../API/CtripUnion.php");
    $cu = new CU('hotel','OTA_HotelAvail');
    // 第二个参数为返回类型参数，支持string，json，xml，array，object，缺省默认执行对应方法中的respond_xml
    $rt = $cu->OTA_HotelAvail($_POST,'array');
    
    echo "<meta charset='utf-8' />";
    print_r($rt);
}
else
{
    require "temp.html";
}