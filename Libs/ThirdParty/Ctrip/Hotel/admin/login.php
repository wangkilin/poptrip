<?php 
error_reporting(0);

	 if (!defined(WEBROOT)){
	 	define(WEBROOT, preg_replace("/admin/", '', dirname(__FILE__)));
	 }	 
	 include_once (WEBROOT."appData/site.config.php");
	 $lockfile=WEBROOT.'appData/install.lock';
	if (!file_exists($lockfile)){//检测是系统是否安装，如果没安装跳转到安装向导页面
		$url=$UnionSite_domainName."/install";
		 
		echo "<script language='javascript' type='text/javascript'>";  
   		echo "window.location.href='$url'"; 
        echo "</script>";
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 	
	<title>后台管理-用户登录</title>	
	<link rel="stylesheet" href="styles/styles.css" />
	<script type="text/javascript">
		function check(){
				
				var username=document.getElementById('username').value;
			 
				if(username.replace(/(^\s*)|(\s*$)/g, "")==''){
					alert("用户名不能为空！");
					return false;
				}
				var password=document.getElementById('password').value;
				if(password==''){
					alert("密码不能为空！");
					return false;
				}
				var code=document.getElementById('code').value;
				if(code==''){
					alert("验证码不能为空！");
					return false;
				}
			}
	</script>
</head>
<?php  include_once ('inc/login.inc.php');?>
<body>
	<div class="wrapper">
		<h1><?php echo $UnionSite_Name;?></h1>
		<form action="login.php" method="post" onsubmit="return check();">
		<div class="login">
			<h3>LOGIN</h3>
			<div class="login_box" style="position:relative">
				<p><span>用户名：</span><input name="username" id="username" type="text" value="" /></p>
				<p><span>密&nbsp;&nbsp;码：</span><input name="password" id="password" type="password" value="" /></p>
				<p><span>验证码： </span><input name="code" id="code" type="text" style="width:102px;" value="" /> 
				<img id="vdimgck" align="absmiddle" onClick="this.src=this.src+'?'" style="cursor: pointer;" alt="看不清？点击更换" src="../admin/code.php"/>
				</p>
				
				<input type="hidden" name="validate" value="validate" />
				
				<input type="submit" value="登录" class="login_btn"   />
				<div style="display:inline-block;left:250px;top:185px;position:absolute" title="如果您的密码忘记，可以使用您的联盟KEY作为密码登录！">忘记密码</div>
				
				
			</div>
		</div>
		</form>
		<div class="ft">
			Copyright&copy; 2012-2013, ctrip.com.All rights reserved. 
		</div>
	</div>
</body>
</html>
