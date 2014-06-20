<?php 
header("Content-type: text/html; charset=utf-8"); 
require_once ("common.php");

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cn">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=gb2312" />
	<title>安装向导</title>
	<link rel="stylesheet" href="styles/styles.css" />
</head>
<body>

	<!-- 顶部工具条begin -->
	<div class="toolkit">
		<div class="toolkit_inner">
		<!-- <a href="###" class="help">后台操作帮助</a><a href="###" class="exit">退出</a> -->	
		</div>
	</div>
	<!-- 顶部工具条end -->
	
	<!-- 头部begin -->
	<div class="hd">
		<h1>携程分销联盟系统</h1>
		<p>安装向导</p>
	</div>
	<!-- 头部end -->
	
	<div class="bd">
		<p class="guide_title">欢迎您使用携程分销联盟系统。您将开始安装程序。<br />请在以下安装方式选择：</p>
		<div class="setup_way">
			<div class="setup_step">
				<a href="step1.php">按步骤安装<br /><span>Setup by steps</span></a>
				<dl class="setup_box">
					<dt>按步骤安装：</dt>
					<dd>连接mysql</dd>
					<dd>网站设置</dd>
					<dd>设置登录信息</dd>
					<dd>确认信息</dd>
				</dl>
			</div>
			<div class="setup_aux">
				<a href="freeInstall.php">辅助安装<br /><span>Auxiliary setup</span></a>
				<dl class="setup_box">
					<dt>辅助安装：</dt>
					<dd>验证联盟信息</dd>
					<dd>服务器参数校验</dd>
					<dd>设置站点信息</dd>
					<dd>综合安装</dd> 

				</dl>
			</div>
		</div>
	</div>
	
	<!-- 尾部begin -->
	<div class="ft">
		Copyright &copy; 2012-2013, ctrip.com.All rights reserved.
	</div>
	<!-- 尾部end -->
	
</body>
</html>