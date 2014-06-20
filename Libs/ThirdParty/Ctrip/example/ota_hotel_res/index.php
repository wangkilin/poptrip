<?php
$p = isset($_REQUEST['p'])?$_REQUEST['p']:'';

if($_POST)
{	
	echo "<meta charset='utf-8' />";
    require_once("../../API/CtripUnion.php");
        
    $cu_res = new CU('hotel','OTA_HotelRes');	
    $rt_res = $cu_res->OTA_HotelRes($_POST,'array');

   	print_r($rt_res);
}
elseif( $p == 'guarante' )
{
	require 'guarante.html';
}
else
{
    require "temp.html";
}