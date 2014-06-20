<?php
require_once ("common.php");
require_once( WEBROOT.'SDK.config.php');
require_once( WEBROOT.'appData/site.config.php');
require_once (WEBROOT.'admin/inc/common.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cn">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<title>安装向导-3</title>
<link rel="stylesheet" href="styles/styles.css" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#city').val($('#defaultcity').val());
	
});

	function check(){
		if( $('#agentID').val() == '' ){
			   alert("请填写联盟ID！");   
			   $('#agentID').focus();
			   return(false);
		}
		if( $('#sid').val() == '' ){
			   alert("请填写SID！");   
			   $('#sid').focus();
			   return(false);
		}	
		if( $('#apiKey').val() == '' ){
			   alert("请填写API KEY！");   
			   $('#apiKey').focus();
			   return(false);
		}	
		if( $('#webName').val() == '' ){
			   alert("请填写设置网站名称！");   
			   $('#webName').focus();
			   return(false);
		}
		if( $('#shortName').val() == '' ){
			   alert("请填写网站简称！");   
			   $('#shortName').focus();
			   return(false);
		}	
		if( $('#domainName').val() == '' ){
			   alert("请填写网站域名！");   
			   $('#domainName').focus();
			   return(false);
		}	
		if( $('#username').val() == '' ){
			   alert("请填写用户名！");   
			   $('#username').focus();
			   return(false);
		}	
		if( $('#password').val() == '' ){
			   alert("请填写密码！");   
			   $('#password').focus();
			   return(false);
		}			
		return true;
		}

	function save(){

		if(check()){
		var _agentID=$('#agentID').val();
		var _sid=$('#sid').val();
		var _apiKey=$('#apiKey').val();
		var _webName=$('#webName').val();
		var _domainName=$('#domainName').val();
		var _city=$('#city').val();
		var _username=$('#username').val();
		var _password=$('#password').val();
		var _shortName=$('#shortName').val();
		 
		$.post("install.php",
				{	action:"savewebinfo",
					agentID:_agentID,
					sid:_sid,
					apiKey:_apiKey,
					webName:_webName,
					domainName:_domainName,
					city:_city,
					username:_username,
					password:_password,
					shortName:_shortName
					},
				function(data){						
					if(data=='1'){
						window.location.href='step4.php'
					}
					else{
						alert(data);
					}
						
				});
		}
		}
	</script>

</head>
<body>

<!-- 头部begin -->
<div class="hd">
<h1><?php echo $UnionSite_Name;?></h1>
<p>安装向导</p>
</div>
<!-- 头部end -->

<!-- 进度条begin -->
<ul class="step3">
	<li>系统检测</li>
	<li>数据库安装</li>
	<li class="current">网站设置</li>
	<li>完成安装</li>
</ul>
<!-- 进度条end -->

<div class="bd">
<div class="guide_box">
<table>
	<tbody>
		<tr>
			<th>联盟推广ID：</th>
			<td colspan=""><input name="agentID" type="text" class="input_text" value=""
				id="agentID" /></td><td><p class="note">请输入你的联盟ID</p></td>
		</tr>
		<tr>
			<th>SID：</th>
			<td colspan=""><input name="sid" id="sid" type="text" value=""
				class="input_text" /></td><td><p class="note">请输入你的联盟SID</p></td>
		</tr>
		<tr>
			<th>API KEY：</th>
			<td colspan=""><input name="apiKey" id="apiKey" type="text" value=""
				class="input_text" /></td><td><p class="note">请输入你的联盟KEY</p></td>
		</tr>
		<tr>
			<th>设置网站名称</th>
			<td colspan="2"><input name="webName" id="webName" type="text"
				value="<?php echo $UnionSite_Name;?>" class="input_text" /></td>
		</tr>
		<tr>
			<th>设置网站简称</th>
			<td colspan=""><input name="shortName" id="shortName" type="text"
				value="<?php echo $UnionSite_ShortName;?>" class="input_text" /></td>
			<td><p class="note">用于发送短信，标志订单来贵站点，建议在5个汉字内</p></td>
		</tr>
		<tr>
			<th>网站域名：</th>
			<td style="width: 253px;"><input name="domainName" id="domainName"
				type="text" value="http://<?php echo $_SERVER['HTTP_HOST'];?>" class="input_text" /></td>
			<td>
			<p class="note">本地测试填写http://127.0.0.1/</p>
			</td>
		</tr>
		<tr>
			<th>默认城市：</th>
			<td><input type="hidden" id='defaultcity' value="<?php echo $SiteDefaultCityID;?>"/>
			<select name="city" id="city">
				<?php foreach ($defaultCityNameArray as $k =>$v){?>
				<option value='<?php echo $k?>'><?php echo $v?></option>
				<?php }?>
			</select></td>
			<td>
			<p class="note">首页将显示该城市酒店</p>
			</td>
		</tr>
		<tr>
			<th>用户名：</th>
			<td><input name="username" id="username" type="text" value="admin"
				class="input_text" /></td>
			<td>
			<p class="note">网站管理登录用户名，建议修改</p>
			</td>
		</tr>
		<tr>
			<th>密码：</th>
			<td><input name="password" id="password" type="password" value=""
				class="input_text" /></td>
			<td>
			<p class="note">登录密码，建议修改</p>
			</td>
		</tr>
	</tbody>
</table>
<div class="btn_box"><input type="button" value="上一步"
	onclick=" window.location.href='step2.php'"	 class="btn_blue" /> 
<input type="button" value="下一步"  class="btn_orange"	onclick='save();' /></div>
</div>
</div>

<!-- 尾部begin -->
<div class="ft">Copyright &copy; 2012-2013, ctrip.com.All rights
reserved.</div>
<!-- 尾部end -->

</body>
</html>
