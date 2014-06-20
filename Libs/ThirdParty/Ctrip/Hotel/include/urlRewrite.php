<?php
/**
 * 本页面负责全站的URL伪静态及跳转
 * 2012-08-15 携程 Daniel
 */
include_once("urlRewrite.class.php");

if($_GET){
	foreach($_GET as $k=> $v){
		//URL 不是UTF8格式，转化
		if(is_utf8($v) ==false)
		$_GET[$k]=iconv("gbk","utf-8",$v);//urldecode($v);
	}
}



//$isSiteUrlRewriter=$SiteUrlRewriter;//appData/site.config.php 中配置
if($SiteUrlRewriter=="1")
{
	$url=new url;
	$url->pdurl($url->getUrlAll());
	$url->ParseUrl();//还原URL中的数据，可以直接获取URL的传值
	
	
}

//判断是否有二级目录
$SecDirs=explode('/site/',$_SERVER['PHP_SELF']);
$baseUlr=empty($SecDirs['0'])?"http://".$_SERVER['HTTP_HOST']:"http://".$_SERVER['HTTP_HOST'].$SecDirs['0'];


/**
 *
 * 将原始的URL转换为新的URL（伪静态后的URL）
 * @param $oldUrl
 */
function getNewUrl($oldUrl,$isSiteUrlRewriter='1')
{
	$url=new url;
	if($isSiteUrlRewriter=="1")
	{
		return  $url->MakeUrlHtml($oldUrl);
	}
	else {
		return $oldUrl;
	}
}

function getHotelHotListUrl($cityID,$SumType,$op,$starlevel,$newtype,$page){
	global $UnionSite_domainName;
	$url=$UnionSite_domainName."/site/hotelhotlist.php?city=".$cityID."&SumType=".$SumType."&op=".$op."&starlevel=".$starlevel."&newtype=".$newtype."&page=".$page;	
	//global  $SiteUrlRewriter;
	//$url=getNewUrl($url,$SiteUrlRewriter);
	return $url;
	
}


function is_utf8($str){
	if (preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$str) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$str) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$str) == true){
		return true;
	}
	else{
		return false;
	}
}





?>