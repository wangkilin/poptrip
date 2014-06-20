<?php
include_once '../appData/db_keyword.php';
include_once ('../appData/site.config.php');

/**
 * autoLoadKeywords自动加载页面关键字 ，使用前需引入/appData/db_keyword.php文件
 * @param 字符串 $pageIndex页面索引（页面的名字）
 * @param 数组 $searchArray 搜索匹配数组，默认为：array('{sitename}','{city}')
 * @param 数组 $replaceArray 匹配替换数组，默认为：array("携程酒店预订","上海")
 */
function autoLoadKeywords($pageIndex,$searchArray=array('{sitename}','{city}'),$replaceArray=array("携程酒店预订","上海")){
	global $keywordsArray;
	if (array_key_exists($pageIndex, $keywordsArray)){
		$keywords_array=$keywordsArray[$pageIndex];	
	} 
	$keywords_array = str_replace($searchArray, $replaceArray, $keywords_array);
	return $keywords_array;
}

?>