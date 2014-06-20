<?php
require_once ("common.php");
/*if (empty($_SERVER['HTTP_HOST'])) {
	$baseurl = 'http://' . $_SERVER['HTTP_HOST'];
} else {
	$baseurl = "http://" . $_SERVER['SERVER_NAME'];
}*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="cn">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<title>安装向导-2</title>
<link rel="stylesheet" href="styles/styles.css" />
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/dialog.js"></script>
<script language="javascript" type="text/javascript">

//查现在数据库列表
	_isok = 0;
	function sdname()
	{
		var _dbhost = $('#dbhost').val();
		var _dbuser = $('#dbuser').val();
		var _dbpwd = $('#dbpwd').val();		 
		
		if( _dbhost =="")
		{
		   alert("请填写数据库主机！");   
		   $('#dbhost').focus();
		   return(false);
		}
		if( _dbuser == '' )
		{
		   alert("请填写数据库用户名！");   
		   $('#dbuser').focus();
		   return(false);
		}
		/*
		if(_dbpwd == '' )
		{
		   alert("请填写数据库密码！");   
		   $('#dbpwd').focus();
		   return(false);
		}				
		*/
		$.post("install.php",
				{action:"sdname",dbhost:_dbhost,dbuser:_dbuser,dbpwd:_dbpwd},
				function(data){
					var returned = data;
					if( returned == 'errorpwd' )
					{
						alert('连接数据库失败 请检查输入的数据库账号 密码是否正确!');
						return false;
					}
					var obj = eval(returned);
					dbary = obj;
					 
					document.getElementById("js-db-list").length=0;	
					document.getElementById("js-db-list").remove(0);
					var op_one = '共'+obj.length+'个';
					document.getElementById("js-db-list").options.add(new Option(op_one,''));
					for(var i=0;i<obj.length;i++)   
					{   
						var name = obj[i];
						document.getElementById("js-db-list").options.add(new Option(name,name));
					} 
			});		 
		 
	}
	
	//判断数据库是否存在
	function chkdb(a)
	{
		var _dbhost = $('#dbhost').val();
		var _dbuser = $('#dbuser').val();
		var _dbpwd = $('#dbpwd').val();		 
		
		if( _dbhost =="")
		{
		   alert("请填写数据库主机！");   
		   $('#dbhost').focus();
		   return(false);
		}
		if( _dbuser == '' )
		{
		   alert("请填写数据库用户名！");   
		   $('#dbuser').focus();
		   return(false);
		}
		/*
		if(_dbpwd == '' )
		{
		   alert("请填写数据库密码！");   
		   $('#dbpwd').focus();
		   return(false);
		}				
		*/
		$.post("install.php",
				{action:"sdname",dbhost:_dbhost,dbuser:_dbuser,dbpwd:_dbpwd},
				function(data){
					var returned = data;
					if( returned == 'errorpwd' )
					{
						alert('连接数据库失败 请检查输入的数据库账号 密码是否正确!');
						return false;
					}
					var obj = eval(returned);
					dbary = obj;
					var sdbname = a.toLowerCase();
					for(var i=0;i<dbary.length;i++)   
					{   
						if(sdbname == dbary[i])
						{
							alert('这是一个已经存在的数据库,确定要覆盖该数据库吗?');
						}
					} 		 
			});		 
	}
	
	function changedb(a)
	{
		$('#dbname').val(a);
		$('#dbname').focus();
	}
	

	function TestDbPwd()
	{

		
		var _dbhost = $('#dbhost').val();
		var _dbuser = $('#dbuser').val();
		var _dbpwd = $('#dbpwd').val();		
		$.post("install.php",
				{action:"chkdname",dbhost:_dbhost,dbuser:_dbuser,dbpwd:_dbpwd},
				function(data){
					if(data == '1')
					{	
						_isok = '1';						 
						$('#dbpwdsta').text('数据库账号密码信息正确');	
						document.getElementById('dbpwdsta').style.color= '#80AA2D';
					}
					else
					{
						_isok = '0';		
						$('#dbpwdsta').text('数据库账号密码信息错误!');	
						document.getElementById('dbpwdsta').style.color= '#ff0000';
					}
				}
		); 

	}
	
 
</script>
<script type="text/javascript" src="js/install.js"></script>


</head>
<body>

<!-- 头部begin -->
<div class="hd">
<h1><?php echo $UnionSite_Name;?></h1>
<p>安装向导</p>
</div>
<!-- 头部end -->

<!-- 进度条begin -->
<ul class="step2">
	<li>系统检测</li>
	<li class="current">数据库安装</li>
	<li>网站设置</li>
	<li>完成安装</li>
</ul>
<!-- 进度条end -->

<div class="bd">
	<div class="guide_box">	 
			<table>
				<tbody>
					<tr>
						<th>数据库主机：</th>
						<td style="width:253px;"><input name="dbhost" id="dbhost" type="text"
			value="<?php echo $cfg_dbhost;?>" class="input_text" /></td>
						<td><p class="note">一般为localhost:后面是端口号</p></td>
					</tr>
					<tr>
						<th>数据库用户：</th>
						<td colspan="2"><input name="dbuser" id="dbuser" type="text" value="<?php echo $cfg_dbuser;?>"
			class="input_text" /></td>
					</tr>
					
					<tr>
						<th>数据库密码：</th>
						<td ><input name="dbpwd" value=""
			id="dbpwd" type="password" class="input_text" onblur="TestDbPwd()" /></td>
			<td><p class="note">
			<span style='float: left; margin-right: 3px;'></span>
		    <span style='float: left' id='dbpwdsta'></span></p></td>
					</tr>
					<tr>
						<th>数据库名称：</th>
						<td><input name="dbname" id="dbname" type="text"
			value="<?php echo $cfg_dbname; ?>" class="input_text"
			onblur="chkdb(this.value)" /> </td>
						<td><select name="js-db-list" class="select"
			id="js-db-list" onChange="changedb(this.value)">
			<option>已有数据库</option>
		</select> <input onclick="sdname()" name="js-go" type="button" value="搜索" class="btn_mini" /></td>
					</tr>
					<tr>
						<th>表前缀：</th>
						<td><input name="dbprefix" id="dbprefix" type="text"
			value="<?php echo $cfg_dbprefix; ?>" class="input_text" /></td>
						<td><p class="note">如无特殊需要，请不要修改</p></td>
					</tr>
					<tr>
						<th>数据库编码：</th>
						<td><label ><input type="radio" name="dblang" id="dblang_utf8" value="utf8"
			checked="checked" />UTF8</label>		
			</td>
						<td> <p class="note_2">仅对4.1+以上版本的MySql选择</p></td>
					</tr>
				</tbody>
			</table>
			<div class="btn_box">
				<input type="button" value="上一步"	onclick=" window.location.href='step1.php'" class="btn_blue" /><input
	type="button" value="开始安装"  	 class="btn_orange"	onclick="DoInstall();" />
			</div>			 
		</div> 
</div>

<!-- 尾部begin -->
<div class="ft">Copyright &copy; 2012-2013, ctrip.com.All rights
reserved.</div>
<!-- 尾部end -->

</body>
</html>
