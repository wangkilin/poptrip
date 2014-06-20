<?php
if($_POST)
{	
    require_once("../../API/CtripUnion.php");
    $cu = new CU('hotel','OTA_HotelSearch');
    $rt = $cu->OTA_HotelSearch($_POST,'array');
    
    echo "<meta charset='utf-8'/>";
    print_r($rt);
}
else
{
    require "temp.html";
}