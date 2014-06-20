<?php
if($_POST)
{	
    require_once("../../API/CtripUnion.php");
    $cu = new CU('hotel','OTA_HotelDescriptiveInfo');
    $rt = $cu->OTA_HotelDescriptiveInfo($_POST,'string');
    
    echo "<meta charset='utf-8' />";
    echo($rt);
}
else
{
    require "temp.html";
}