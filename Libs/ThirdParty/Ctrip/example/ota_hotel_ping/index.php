<?php

if($_POST)
{	
    require_once("../../API/CtripUnion.php");
    
	$cu = new CU('hotel','OTA_Ping');
	// 第二个参数为返回类型参数，支持string，json，xml，array，object，缺省默认执行对应方法中的respond_xml
	$rt = $cu->OTA_Ping($_POST,'string'); 
	    
	echo("<meta charset='utf-8' />");
	echo($rt);
}
else
{
    require "temp.html";
}