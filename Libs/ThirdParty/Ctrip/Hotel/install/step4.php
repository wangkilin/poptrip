<?php 
require_once( '../appData/site.config.php');
?>  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cn">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<title>安装向导-4</title>
	<link rel="stylesheet" href="styles/styles.css" />
</head>
<body>

	<!-- 头部begin -->
	<div class="hd">
		<h1><?php echo $UnionSite_Name;?></h1>
		<p>安装向导</p>
	</div>
	<!-- 头部end -->
	
	<!-- 进度条begin -->
	<ul class="step4">
		<li >系统检测</li>
		<li >数据库安装</li>
		<li >网站设置 </li>
		<li class="current">完成安装</li>
	</ul>
	<!-- 进度条end -->
	
	<div class="bd">
	<div class="guide_complete">
			<span class="tickle"></span>
			<p>您已成功安装<?php echo $UnionSite_Name;?>！</p>
			<p><a href='../'>前往首页</a></p>
			<p><a href='../admin/'>前往后台管理</a></p>
		</div>		 
	</div>
	
	<!-- 尾部begin -->
	<div class="ft">
		Copyright &copy; 2012-2013, ctrip.com.All rights reserved.
	</div>
	<!-- 尾部end -->
	
</body>
</html>