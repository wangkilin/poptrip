<?php
 /**
  * 后台主页面
  */
error_reporting(E_ERROR);

 if (!defined(WEBROOT)){
	 	define(WEBROOT, preg_replace("/admin/", '', dirname(__FILE__)));
	 }
require_once (WEBROOT."appData/site.config.php");//加载网站配置文件
include_once (WEBROOT.'admin/inc/utility.php');//加载工具类
include_once (WEBROOT.'Common/toolExt.php');//加载工具类
require_once (WEBROOT.'admin/inc/SubPages.php');//加载分页类，配合admin.js中pagerSubmi使用
require_once (WEBROOT.'Common/Session.php');//加载Session处理类
ini_set('max_execution_time', '0');

$session=new Session();
if ($_GET['m']=='loginout'){//注销操作	 
 	$session->remove("admin");  
}

//验证是否登录 
if (!$session->contain('admin')){
	redirect('login.php');
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cn">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
<title><?php echo $UnionSite_Name;?>-后台管理</title>
<link rel="stylesheet" href="styles/styles.css" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery.vmodal.js"></script>
<script type="text/javascript" src="js/admin.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	//折叠导航栏
	$("div.side dl >dt").click(function(){
		    if($(this).hasClass("unfold"))
		    {
				$(this).removeClass("unfold");
				$(this).addClass("fold");
				$(this).nextAll().hide();
			}
			 else if($(this).hasClass("fold")){
				 $(this).removeClass("fold");
				 $(this).addClass("unfold");
				 $(this).nextAll().show();
			}
		});
	
});
//js动态切换菜单
function switchMenu(id){	
	 $("#"+id).addClass("current");
}

</script>
</head>
<body>

<!-- 顶部工具条begin -->
<div class="toolkit">
<div class="toolkit_inner"><a href="manage.php?m=help" class="help">后台操作帮助</a><a
	href="manage.php?m=loginout" class="exit">退出</a></div>
</div>
<!-- 顶部工具条end -->

<!-- 头部begin -->
<div class="hd">
<h1><a href="manage.php"  ><?php echo $UnionSite_Name;?> </a></h1>
</div>
<!-- 头部end -->

<div class="bd"><!-- 侧栏begin -->
<div class="side">
<dl>
	<dt class="unfold"><a href="#"><span></span>辅助功能</a></dt>
	<!-- unfold为展开状态，fold为收缩状态 -->
	<dd id="ad"><a href="manage.php?m=ad">广告管理</a></dd>
	<dd id="flink"><a href="manage.php?m=flink">友情链接</a></dd>
	<dd id="policy"><a href="manage.php?m=policy">挂牌管理</a></dd>
	<dd id="api"><a href="manage.php?m=api">站点健康状态查询</a></dd>
</dl>
<dl>
	<dt class="unfold"><a href="#"><span></span>站点设置</a></dt>
	<!-- unfold为展开状态，为收缩状态 -->
	<dd id="sset"><a href="manage.php?m=sset">网站设置</a></dd>
	<dd id="keyword"><a href="manage.php?m=keyword">网页关键字管理</a></dd>
	<dd id="permission"><a href="manage.php?m=permission">权限管理</a></dd>
	<dd id="topical"><a href="manage.php?m=topical">主题管理</a></dd>
	<dd id="sysdata"><a href="manage.php?m=sysdata">数据库备份</a></dd>
	<dd id="revert"><a href="manage.php?m=revert">数据库还原</a></dd>
	<dd id="sqlquery"><a href="manage.php?m=sqlquery">执行命令行</a></dd>
	
	
</dl>
</div>
<!-- 侧栏end -->
<div class="main"><?php 
//菜单模块切换
$module=$_GET['m'];
if (empty($module)) //
{
	include ('inc/wellcome.inc.php');
	include ('module/wellcome.php');
}
else{
	switch ($module){
		case 'ad':		//加载广告管理模块	
			include ("inc/ad.inc.php");
			include ('module/ad.php');
			registerScript("switchMenu('$module')");
			break;
		case 'flink':	//加载友情链接管理模块
			include ("inc/friendlink.inc.php");
			include ('module/friendlink.php');
			registerScript("switchMenu('$module')");
			break;
		case 'policy':	//加载网站挂牌管理模块
			include ("inc/policy.inc.php");
			include ('module/policy.php');
			registerScript("switchMenu('$module')");
			break;
		case 'sset':	//加载网站设置模块
			include ("inc/siteset.inc.php");
			include ('module/siteset.php');
			registerScript("switchMenu('$module')");
			break;
		case 'keyword':	//加载关键字管理模块
			include ("inc/keyword.inc.php");
			include ('module/keyword.php');
			registerScript("switchMenu('$module')");
			break;
		case 'permission':	//加载权限管理模块
			include ('inc/permission.inc.php');
			include ('module/permission.php');
			registerScript("switchMenu('$module')");
			break;
		case 'topical':	//加载主题管理模块
			include ('inc/topical.inc.php');
			include ('module/topical.php');
			registerScript("switchMenu('$module')");
			break;	
		case 'sysdata':	//数据库备份
			include ('inc/sysdata.inc.php');
			include ('module/sysdata.php');
			registerScript("switchMenu('$module')");
			break;	
		case 'revert':	//数据库还原
			include ('inc/revert.inc.php');
			include ('module/revert.php');
			registerScript("switchMenu('$module')");
			break;	
		case 'sqlquery':	//命令行
			//include ('inc/sqlquery.inc.php');
			include ('module/sqlquery.php');
			registerScript("switchMenu('$module')");
			break;

		case 'api':	//站点健康状态
			include ('inc/api.inc.php');
			include ('module/api.php');
			registerScript("switchMenu('$module')");
			break;
			
		case 'help':	//加载帮助模块
			include ('module/help.php');
			break;
		default:	//欢迎页、公告模块
			include ('inc/wellcome.inc.php');
			include ('module/wellcome.php');
			break;
	}
}

?>
<div id="blackmask"></div>
</div>
</div>
<!-- 尾部begin -->
<div class="ft">Copyright &copy; 2012-2013, ctrip.com.All rights
reserved.</div>
<!-- 尾部end -->

</body>
</html>
