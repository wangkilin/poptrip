<?php 
/**
 * 酒店预定页面
 */
include_once ("../include/siteAd.php");//加载广告的处理逻辑
include_once ("../appData/site.config.php");//加载整站系统的配置文件
include_once ('../SDK.config.php');//配置文件加载--必须加载这个文件
$index_cssurl=$UnionSite_domainName."/site/css/styles.css";//本页面的CSS样式地址

$SiteUrlRewriter='0';
include_once("../include/urlRewrite.php");//加载URL伪静态处理


//加载页面关键字,引入main_keywords.php页面
include_once 'module/main_keywords.php';
$searchArr=array('{sitename}');
$replaceArr=array("$UnionSite_Name");
$kw=autoLoadKeywords('index.php',$searchArr,$replaceArr);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cn">
<head>
<meta http-equiv=Content-Type content="text/html; charset=<?php  echo $SiteCharset;?>"/>
<title><?php echo  $kw['title'];?></title>
<meta name="keywords" content="<?php echo $kw['keywords'];?>" />
<meta name="description" content="<?php echo  $kw['description'];?>" />
<link rel="stylesheet" type="text/css" href="<?php echo $index_cssurl;?>" />
<?php if($UnionSite_Css){?>
<link rel="stylesheet" type="text/css" href="<?php echo $UnionSite_domainName.$UnionSite_Css?>" />

<?php }?>
</head>
<body><?php include_once 'module/header.php';//加载头部文件

?>
<?php include_once 'module/bar_navigation.php';//加载导航文件 
echo	getMainNavigation();//加载默认的导航
?>

<div class="bd bd_detail">

<script type="text/javascript">
function setIframeHeight (iframeHeight){
	var iframe = document.getElementById('iFrame');
	if(iframeHeight){
		iframe.style.height = iframeHeight +"px"
		}
	
}
</script>


<iframe id='iFrame' name='iFrame' scrolling="no" width="900px" height="1200px"
  frameborder="0" src="<?php echo $_GET['ifream']."&httpDomain="?><?php echo urlencode($_SERVER['HTTP_HOST']);?>" ></iframe>
  

<?php include_once 'module/foot.php';//加载底部控制文件 ?>


