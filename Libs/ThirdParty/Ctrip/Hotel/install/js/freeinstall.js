/**
 * 安装数据库js脚本
 * */

// 安装入口
function DoInstall()
{
	if(check()){
	var _dbhost = $('#dbhost').val();
	var _dbuser = $('#dbuser').val();
	var _dbpwd = $('#dbpwd').val();
	if(!_dbpwd) _dbpwd = '';
	$.post("install.php",
			{action:"chkdname",dbhost:_dbhost,dbuser:_dbuser,dbpwd:_dbpwd},
			function(data){
				
				if(data == '0'){
					alert('数据库账号密码信息错误!');
					$('#dbpwd').focus();
					return false;
				} else {
					if($('#dbprefix').val() == ''){
						alert('请填写数据库表前缀!');
						$('#dbprefix').focus();
						return false;
					}	
					popshow();	
				}
				
			});
	}
}


function popshow(){
	var title = '程序安装进度监视器';
	 
	var strHtml = '<img src="images/loading.gif"><div class="install_title" style="padding:10px;font-size:15px;">由于网站数据库正在安装,请不要刷新耐心等侍一小会儿! 如果失败请重试</div><div id="install_info"style="padding:10px;font-size:13px" ></div>';
	var pop=new Popup({ contentType:2,isReloadOnClose:false,width:500,height:320});
	pop.setContent("contentHtml",strHtml);
	pop.setContent("title",title);
	pop.build();
	pop.show();
	$('#install_info').html('程序已开始运行安装中,请稍候......<br>');	
	createConfigFile();
}

//创建配置文件
function createConfigFile(){
		 
	var _dbhost= $('#dbhost').val();
	var _dbuser = $('#dbuser').val();
	var _dbpwd = $('#dbpwd').val();
	if(!_dbpwd) _dbpwd = '';
	var _dbname  = $('#dbname').val();
	var _dbprefix = $('#dbprefix').val();	
	var _dblang = $('#dblang_utf8').val();	
	$.post("install.php",
			{	action:"createConfigFile",
				dbhost:_dbhost,
				dbuser:_dbuser,				
				dbpwd:_dbpwd,
				dbname:_dbname,
				dbprefix:_dbprefix,
				dblang:_dblang
				},
			function(data){
			 
				var json = eval('['+data+']');		
				var rs = json[0]['rs'];
				var info = json[0]['info']; 		
				if(rs == '1'){
					$('#install_info').html(info+'......<font color="green">成功</font><br>'+$('#install_info').html());		
					createDatabase();	
				} else {
					$('#install_info').html(info+'......<font color="red">失败!</font><br>'+$('#install_info').html());		
				}
				
			});	 
	
}

//初始化数据库
function createDatabase(){
	var _dbhost= $('#dbhost').val();
	var _dbuser = $('#dbuser').val();
	var _dbpwd = $('#dbpwd').val();
	if(!_dbpwd) _dbpwd = '';
	var _dbname  = $('#dbname').val();
	var _dbprefix = $('#dbprefix').val();	
	var _dblang = $('#dblang_utf8').val();	
	$.post("install.php",
			{	action:"createDatabase",
				dbhost:_dbhost,
				dbuser:_dbuser,
				dbname:_dbname,
				dbpwd:_dbpwd,
				dbprefix:_dbprefix,
				dblang:_dblang
				},
			function(data){
					console.log(data);
					var json = eval('['+data+']');		
					var rs = json[0]['rs'];
					if(rs == '1'){
						$('#install_info').html(json[0]['info']+'......<font color="green">成功</font><br>'+$('#install_info').html());		
						installBaseData();	//写入初始数据库
					} else {
						$('#install_info').html(json[0]['info']+'......<font color="red">失败</font><br>'+$('#install_info').html());		
					}
			});	 
		  
}

//写入初始数据
function installBaseData(){
	
	$.post("install.php",
			{	action:"installBaseData"},
			function(data){
				//$('#install_info').html(data);
					var json = eval('['+data+']');		
					//var rs = json[0]['rs'];
					var startgo = json[0]['startgo'];
					//var bakfiles = json[0]['bakfiles'];
					if(startgo == 0){
						$('#install_info').html(json[0]['msg']+'......<font color="red">失败</font><br>'+$('#install_info').html());		
					} else {
						$('#install_info').html(json[0]['msg']+'......<font color="green">成功</font><br>'+$('#install_info').html());		
					   // alert(startgo+json[0]['msg']);
						installBaseData_table();
					}
			});	 	  
	 
}

//写数据第二步写表数据
function installBaseData_table(){
	
	$.post("install.php",
			{	action:"installBaseData_table"			
				},
			function(data){
					/*$('#install_info').html(data);*/
				var json = eval('['+data+']');
				var startgo = json[0]['startgo'];
				if(startgo == 0){
					$('#install_info').html('数据库安装......<font color="red">失败</font><br/>'+json[0]['msg']+$('#install_info').html());		
				} else {
					$('#install_info').html('数据库安装......<font color="green">成功</font><br/>'+json[0]['msg']+$('#install_info').html());	
					save();			
					return false;
				}
			});	 	
	 
}

//安装完成，跳转下一步
function goToDone() {
    window.setTimeout(function () {
        location.href = "step3.php";
    }, 3000);
}


function check(){

	var _dbhost = $('#dbhost').val();
	var _dbuser = $('#dbuser').val();
	var _dbpwd = $('#dbpwd').val();	
	if(!_dbpwd) _dbpwd = '';
	
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