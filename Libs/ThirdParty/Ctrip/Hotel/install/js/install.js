/**
 * 安装数据库js脚本
 * */

// 安装入口
function DoInstall()
{

	var _dbhost = $('#dbhost').val();
	var _dbuser = $('#dbuser').val();
	var _dbpwd = $('#dbpwd').val();	
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
		 
	_dbhost= $('#dbhost').val();
	_dbuser = $('#dbuser').val();
	_dbpwd = $('#dbpwd').val();
	_dbname  = $('#dbname').val();
	_dbprefix = $('#dbprefix').val();	
	_dblang = $('#dblang_utf8').val();	
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
	_dbhost= $('#dbhost').val();
	_dbuser = $('#dbuser').val();
	_dbpwd = $('#dbpwd').val();
	_dbname  = $('#dbname').val();
	_dbprefix = $('#dbprefix').val();	
	_dblang = $('#dblang_utf8').val();	
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
					goToDone();			
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
