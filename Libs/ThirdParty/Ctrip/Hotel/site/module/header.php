<?php
//定义本系统的相对路径根部 
define("WEBROOT", preg_replace("/site/", '', dirname(dirname(__FILE__))));
$lockfile=WEBROOT.'appData/install.lock';

if (!file_exists($lockfile)){//检测是系统是否安装，如果没安装跳转到安装向导页面
	echo "<script language='javascript' type='text/javascript'>";  
    echo "window.location.href='".$baseUlr."/install'"; 
    echo "</script>";  
}

/**
 * 处理站点头部的数据
 */
$siteAdRequest=new siteAd();//加载广告
$siteAdRequest->siteAdArray=$siteAdArray;
$siteAdRequest->getAdLinks("index_header");
 
$orderQueryUrl=getNewUrl($UnionSite_domainName."/site/order.php",$SiteUrlRewriter);//getNewUrl('order.php',$SiteUrlRewriter);
 
?>
 
<div class="hd"><a href="index.php"><img src="<?php echo $SiteLogImageUrl;?>" /></a>
<div class="hd_ad"><?php echo $siteAdRequest->responseHtml;?></div>
<?php   if($Booking_State=='0'){?>
<div class="login"><a href="<?php echo $orderQueryUrl;?>">订单查询</a></div>
<?php }?>


</div>
<?php 
$defaultcityidFromUrl="";//默认城市的参数
$heardTopUrl="";//系统当前的URL地址
$defaultcityidFromUrl=getDefaultCityFromUrl();
$getHeaderUrl=getHeaderUrl();
/**
 * 
 * @var 从URL中获取到默认城市的数据
 */
function getDefaultCityFromUrl()
{
          $defaultcityidFromUrl=$_GET["defaultcityid"];
          if($defaultcityidFromUrl==""||$defaultcityidFromUrl==null){
          $defaultcityidFromUrl=$_SET["defaultcityid"];
          }
          return  $defaultcityidFromUrl;
}
/**
 * 
 * @var 获取当前的URL地址的域名加端口部分
 */
function getHeaderUrl()
{
          return getHostAndPort();
}
    /*
         * 获取到域名及端口号
         */
   function getHostAndPort()
        {
                $port=$_SERVER['SERVER_PORT'];
                if($port=="80")
                {
                        $port="";
                }
                else
                {
                        $port=":".$port;
                }
                return "http://".$_SERVER['SERVER_NAME'].$port.$_SERVER["SCRIPT_NAME"];
        }
?>
<div style="display:none">
<input id="heardTopDefaultCity"
        name="heardTopDefaultCity" type="text"
        value="<?php echo $defaultcityidFromUrl;?>"/>
        <input id="heardTopUrl"
        name="heardTopUrl" type="text"
        value="<?php echo $getHeaderUrl;?>"/>
        </div>
