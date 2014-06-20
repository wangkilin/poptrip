<?php
if($_POST)
{	
    require_once("../../API/CtripUnion.php");
    $cu = new CU('hotel','D_HotelCreditCard');
    $rt = $cu->D_HotelCreditCard($_POST,'array');
    
    echo "<meta charset='utf-8' />";
    print_r($rt);
}
else
{
    require "temp.html";
}