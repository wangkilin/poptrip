<?php
/**
 * 此页面做系统首页，负责跳转等
 */
include_once ("../appData/site.config.php");
include_once("../include/urlRewrite.php");//加载URL伪静态处理
?>
<!DOCTYPE html>
<html>
<head>
<meta/>
<title><?php echo $UnionSite_Name;?></title>
</head>
<body>
<?php
include_once 'module/header.php';//加载头部文件
?>
<div>
<a href="<?php echo getNewUrl("DEMO/demo_D_hotelSearch.php");?>"  target="_blank">酒店列表DEMO[D_HotelSearch.php]</a>
<br>
<a href="DEMO/demo_D_hotelOrderList.php"  target="_blank">酒店订单列表DEMO[D_HotelOrderList.php]</a>
<br>
<a href="DEMO/demo_GroupProductList.php"  target="_blank">酒店团购列表DEMO[GroupProductList.php]</a>
</div>

<?php
include_once 'module/foot.php';//加载底部文件
?>
</body>
</html>