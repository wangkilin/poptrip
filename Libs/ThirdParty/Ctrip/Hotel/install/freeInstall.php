<?php
require_once ("common.php");
define("WEBROOT", preg_replace("/install/", '', dirname(__FILE__)));
require_once( WEBROOT.'SDK.config.php');
require_once (WEBROOT.'sdk/API/Custom/CheckSidAid.php');
require_once (WEBROOT.'Common/Session.php');
require_once('install.inc.php');
$session=new Session(); 
$url="stepfreeInstall.php";
header("Content-type: text/html; charset=utf-8"); 

//检验是否成功登陆
//if ( $session->contain('apikey'))redirect($url);

if($_GET['ac']=='freelogin'){
	    $agentID = trim($_POST['aid']);
	    $sid = trim($_POST['sid']);
	    $apiKey = trim($_POST['key']);
	    $code = trim($_POST['code']);
	    
		if ($code!=$_SESSION[check_auth]) {
	       echo "<script>alert('验证码不正确');history.go(-1);</script>";
			die;
	    }
		     
	    //1. 验证联盟ID
	    if (!checkAgent($agentID, $sid,$apiKey)) {
	         echo "<script>alert('联盟推广ID或API Key不正确！');history.go(-1);</script>";
			die;
	    }else{
	    	$session->set("apikey", $apiKey); 	
	    	$session->set("aid", $agentID); 
	    	$session->set("sid", $sid); 
	    			 
			echo "<script language='javascript' type='text/javascript'>";  
   			echo "window.location.href='$url'"; 
    		echo "</script>";
	    	
	    }
	   
}

$lockfile=WEBROOT.'appData/install.lock';
if (file_exists($lockfile)){	
	$url=$UnionSite_domainName."/admin";
		 
	echo "<script language='javascript' type='text/javascript'>";  
   	echo "window.location.href='$url'"; 
    echo "</script>";
}else{

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 	
	<title>辅助安装登录</title>	
	<link rel="stylesheet" href="styles/styles.css" />
	<script type="text/javascript">
		function check(){
				
				var aid=document.getElementById('aid').value;
			 
				if(aid.replace(/(^\s*)|(\s*$)/g, "")==''){
					alert("联盟   ID不能为空！");
					return false;
				}
				var sid=document.getElementById('sid').value;
				if(sid==''){
					alert("站点   ID不能为空！");
					return false;
				}
				var key=document.getElementById('key').value;
				if(key==''){
					alert("联盟KEY不能为空！");
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
<body>
	<div class="wrapper">
		<h1>辅助安装登录</h1>
		<form action="?ac=freelogin" method="post" onsubmit="return check();">
		<div class="login">
			<h3>LOGIN</h3>
			<div class="login_box" style="position:relative">
				<p><span>联盟   ID：</span><input name="aid" id="aid" type="text" value="" /></p>
				<p><span>站点   ID：</span><input name="sid" id="sid" type="text" value="" /></p>
				<p><span>联盟KEY：</span><input name="key" id="key" type="text" value="" /></p>
				<p><span>验证码：</span><input name="code" id="code" type="text" style="width:103px;" value="" /> 
				<img id="vdimgck" align="absmiddle" onClick="this.src=this.src+'?'" style="cursor: pointer;" alt="看不清？点击更换" src="../admin/code.php"/>
				</p>
				
				<input type="hidden" name="validate" value="validate" />
				<input type="submit" value="登录" class="login_btn"   />
			</div>
			<div>
			如果你还没有联盟KEY ，请到<a href='http://u.ctrip.com' target='_blank'>携程分销联盟站点</a>申请你的联盟KEY
			</div>
			
		</div>
		</form>
		<div class="ft">
			Copyright&copy; 2012-2013, ctrip.com.All rights reserved. 
		</div>
	</div>
</body>
</html>
<?php }?>