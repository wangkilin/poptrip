<?php
if($_POST)
{	
    require_once("../../API/CtripUnion.php");
    $cu = new CU('hotel','OTA_Cancel');
    $rt = $cu->OTA_Cancel($_POST);
    
    echo "<meta charset='utf-8' />";
    var_dump($rt);
}
else
{
    require "temp.html";
}