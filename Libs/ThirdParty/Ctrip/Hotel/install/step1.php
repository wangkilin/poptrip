<?php 
require_once ("common.php");
require_once( WEBROOT.'SDK.config.php');
require_once (WEBROOT.'Common/Session.php');
$session=new Session(); 
//辅助安装系统需要提前定义
if(($ac=='freeInstall')){
	if (!$session->contain('apikey'))redirect('freeInstall.php');
}



//获取PHP版本信息
$phpv = phpversion();
//获取操作系统信息
$sp_os = @getenv('OS');
$sp_gd = gdversion();
$sp_server = $_SERVER['SERVER_SOFTWARE'];

//判断服务器类型，只有APACHE 可以开启伪静态
$sp_server_apache = strtolower($sp_server);
$is_apache = count(explode('apache',$sp_server_apache))>1?'1':'0';


$sp_host = (empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_HOST'] : $_SERVER['REMOTE_ADDR']);
$sp_name = $_SERVER['SERVER_NAME'];
$sp_max_execution_time = ini_get('max_execution_time');

$sp_allow_url_fopen = (ini_get('allow_url_fopen') ? "<span class='orange'>[√]On</span> " : "<span class='red'>[×]Off</span> ");
$sp_safe_mode = (ini_get('safe_mode') ? "<span class='red'>[×]Off</span>" :  "<span class='orange'>[√]On</span>");
$sp_gd = ($sp_gd > 0 ?  "<span class='orange'>[√]On</span>" : "<span class='red'>[×]Off</span>");
$sp_mysql = (function_exists('mysql_connect') ? "<span class='orange'>[√]On</span>" : "<span class='red'>[×]Off</span>");
$sp_gzip = count(explode('gzip',$_SERVER['HTTP_ACCEPT_ENCODING']))>1?"<span class='orange'>[√]On</span> " : "<span class='red'>[×]Off</span> ";
$sp_gzip_off = count(explode('gzip',$_SERVER['HTTP_ACCEPT_ENCODING']))>1?"" : "1";
if ($sp_mysql == "<span class='red'>[×]Off</span>") {
	$sp_mysql_err = true;
} 
else {
	$sp_mysql_err = false;
}

$sp_testdirs = array(
	 '', 
 	 'admin/*',         
   	 'include/*',
     'install/*',
	 'Common/*',
 	 'sdk/*',  
  	 'site/*',       
	 'appData/*'			        
	);
	
?>
	<?php if(($ac!='freeInstall')){?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cn">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<title>安装向导-1</title>
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
	
	<ul class="step1">
		<li class="current">系统检测</li>
	
	
		<li>数据库安装</li>
		<li>网站设置 </li>
		
	
		<li>完成安装</li>
	</ul>
	<!-- 进度条end -->
<?php }?>	
	<div class="bd">
		<div class="guide_box">
			<h3>服务器信息</h3>
			<table cellspacing="0" cellpadding="0" class="guide_table">
				<thead>
					<tr>
						<th style="width:250px;">参数</th>
						<th style=" ">值</th>
						 
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>服务器域名</th>
					<td><?php echo $sp_name; ?></td>
						 
					</tr>
					<tr>
						<th>服务器操作系统</th>
						<td><?php echo $sp_os; ?></td>
						 
					</tr>
					<tr>
						<th>服务器引擎</th>
						<td><?php echo $sp_server; ?><?php if($is_apache!='1'){?> (只有apache可以开启伪静态) <?php }?></td>
						 
					</tr>
					<tr>
						<th>PHP版本</th>
						<td><?php echo $phpv; ?></td>
						 
					</tr>
					<tr>
						<th>系统安装目录</th>
						<td><?php echo WEBROOT; ?></td>
						 
					</tr>
				</tbody>
			</table>
			<h3>系统环境监测<span>系统环境要求必须满足以下所有条件，否则系统或者系统部分功能将无法使用。</span></h3>
			<table cellspacing="0" cellpadding="0" class="guide_table">
				<thead>
					<tr>
						<th style="width:250px;">需开启的变量或函数</th>
						<th style="width:80px;">状态</th>
						<th>实际状态与建议</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>allow_url_fopen</th>
						<td>on</td>
						<td><?php echo $sp_allow_url_fopen; ?><span class="gray">(不符合要求将导致远程资料本地化等功能无法应用)</span></td>
					</tr>
					<tr>
						<th>safe_mode</th>
						<td>off</td>
						<td><?php echo $sp_safe_mode; ?><span class="gray">(本系统不支持在非win主机的安全模式下运行)</span></td>
					</tr>
					<tr>
						<th>GD 支持</th>
						<td>on</td>
						<td><?php echo $sp_gd; ?><span class="gray">(不支持将导致与图片相关的大多数功能无法使用或引发警告)</span></td>
					</tr>
					<tr>
						<th>MySQL 支持</th>
						<td>on</td>
						<td><?php echo $sp_mysql; ?><span class="gray">(不支持无法使用本系统)</span></td>
					</tr>
					<tr>
						<th>gzip 支持</th>
						<td>on</td>
						<td><?php echo $sp_gzip; ?><span class="gray">(不支持将导致流量变大<?php if($sp_gzip_off=='1'){?>，无法获取数据将HttpRequest.php的17行注释<?php }?>)</span></td>
					</tr>
				</tbody>
			</table>
			<h3>目录权限监测<span>系统环境要求必须满足以下所有条件，否则系统或者系统部分功能将无法使用。</span></h3>
			<table cellspacing="0" cellpadding="0" class="guide_table">
				<thead>
					<tr>
						<th style="width:300px;">目录名</th>
						<th style="width:200px;">读取权限</th>
						<th>写入权限</th>
					</tr>
				</thead>
				<?php
			foreach($sp_testdirs as $d)
			{
			?>
			<tbody>
			<tr>
					<th><?php echo empty($d) ? '根目录' : $d; ?></th>
					<?php
      		$fulld = WEBROOT.str_replace('/*','',$d);      		 
      		//echo $fulld." <br/>   ";
      		$rsta = (is_readable($fulld) ? "<span class='orange'>[√]读</span>" : "<span class='orange'>[×]读</span>");
	    		$wsta = (TestWrite($fulld) ? "<span class='orange'>[√]写</span>" : "<span class='orange'>[×]写</span>");
	    		echo "<td>$rsta</td><td>$wsta</td>\r\n";
      ?>
			</tr>
			</tbody>
			<?php
			}
			?>			
							</table>
			<?php 
		//		$url=($ac=='freeInstall')?'stepfreeInstall.php':'step2.php';
			 if(($ac!='freeInstall')){?>				
							
			<div class="btn_box">
				<input type="button" value="下一步"   onclick=" window.location.href='step2.php'" class="btn_orange" />
			</div>
			<?php }?>
			
		</div>	  
		 	</div>
	
			<?php 	 if(($ac!='freeInstall')){?>		
	
	<!-- 尾部begin -->
	<div class="ft">
		Copyright &copy; 2012-2013, ctrip.com.All rights reserved.
	</div>
	<!-- 尾部end -->
	
</body>
</html>
			<?php }?>