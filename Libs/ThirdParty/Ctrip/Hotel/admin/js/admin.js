/**
 * admin.js  
 * 后台管理各模块处理js
 * 
 * **/

/**
 * common function
 * */

// 判断输入是否是一个数字--(数字包含小数)-- 
function isnumber(str) 
{ 
return !isNaN(str); 
} 


// 判断输入是否是一个整数 
function isint(str) 
{ 
var result=str.match(/^(-|\+)?\d+$/); 
if(result==null) return false; 
return true; 
} 
// 判断输入是否是有效的长日期格式 - "YYYY-MM-DD HH:MM:SS" || "YYYY/MM/DD HH:MM:SS" 
function isdatetime(str) 
{ 
var result=str.match(/^(\d{4})(-|\/)(\d{1,2})\2(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/); 
if(result==null) return false; 
var d= new Date(result[1], result[3]-1, result[4], result[5], result[6], result[7]); 
return (d.getFullYear()==result[1]&&(d.getMonth()+1)==result[3]&&d.getDate()==result[4]&&d.getHours()==result[5]&&d.getMinutes()==result[6]&&d.getSeconds()==result[7]); 
} 


// 检查是否为 YYYY-MM-DD || YYYY/MM/DD 的日期格式 
function isdate(str){ 
var result=str.match(/^(\d{4})(-|\/)(\d{1,2})\2(\d{1,2})$/); 
if(result==null) return false; 
var d=new Date(result[1], result[3]-1, result[4]); 
return (d.getFullYear()==result[1] && d.getMonth()+1==result[3] && d.getDate()==result[4]); 
} 

//自定义消息弹出框
String.prototype.Trim = function()    
{    
return this.replace(/(^\s*)|(\s*$)/g, "");    
};   
String.prototype.LTrim = function()    
{    
return this.replace(/(^\s*)/g, "");    
} ; 
String.prototype.RTrim = function()    
{    
return this.replace(/(\s*$)/g, "");    
} ;

function messageBox(title,content){
	 
	$('body').append("<div id='bgmark' style='filter: alpha(opacity=50); -moz-opacity: 0.5; opacity: 0.5;background-color: #000; width: 100%; height: 100%; z-index: 9999; position: fixed;left: 0; top: 0;  overflow: hidden;'></div><div id='msgbox' class='mask' style='width:400px;padding-bottom:20px;background-color:#FFFFFF;'><h3></h3><p style='margin-top:20px;text-align:center;font-size:14px;'></p><div class='btn_box'><input type='button' value='确  认' class='btn_orange' /></div><a href='#' class='close cancel'>×</a></div>");
		
	var self=$('#msgbox');
	var sh = self.height();
	var sw = self.width();
	self.css({'position':'absolute','top':'50%','left':'50%','z-index':'10000'});
	self.css({'margin-top':-(sh/2),'margin-left':-(sw/2)});
	self.show();	
	//填充消息内容 
	self.find('h3').html(title);
	self.find('p').html(content);	
	var mark=$('#bgmark');//遮罩层
	 
	//关闭按钮
	var dismiss = self.find('.cancel');
	dismiss.off('click');
	dismiss.bind('click',function(){				 
		self.fadeOut();
		mark.remove();
		self.remove();
	});		
	self.find('input[type=button]').bind('click',function(){
		self.fadeOut();
		mark.remove();
		self.remove();
	});
	
	var dragbar = self.find('h3');
	dragbar.off('mouseenter');
	var mouseDown = false;
	var mouseDownX = -1;
	var mouseDownY = -1;
	var selfx = self.offset().left;
	var selfy = self.offset().top;
	var gapx = 0;
	var gapy = 0;
	
	dragbar.bind('mouseenter',function(){
		var db = $(this);
		db.css('cursor','pointer');
		$(document).mousedown(function(event){
			mouseDown = true;
			mouseDownX = event.pageX;
			mouseDownY = event.pageY;
			gapx = mouseDownX - selfx;
			gapy = mouseDownY - selfy;
			return false;
		});
		$(document).mousemove(function(event){
			if(mouseDown){
				self.css('margin','0');
				mouseDownX = event.pageX;
				mouseDownY = event.pageY;
				selfy = mouseDownY - gapy;
				selfx = mouseDownX - gapx;
				self.css({top:selfy,left:selfx});
			}
		});
		
		$(document).mouseup(function(event){
			mouseDown = false;
			db.css('cursor','default');
		});
	});
	
}	 
//分页验证函数
 function pagerSubmit(){
		page=$("div.page_value input[name=pager]").val();
		if(page.Trim()==""){
			alert('页码请不能为空');
			return false;
		}
		if(!isint(page)){
			//messageBox('系统提示','页码不能够为空');
			alert('请输入正确的页码！');
			return false;
		}else{
			window.location.href= $("div.page_value input[type=hidden]").val()+"&page="+page;			 
		}
	}

 //全选/反选
 function select(){
		var checked=$("#checkAll").attr("checked");
		if(checked=='checked'){
			$("input[name=checkbox]").attr("checked",true );	
		}
		else{
			$("input[name=checkbox]").attr("checked",false );	
		}
	}
/**
 * ad.php 所需js function
 * */

// 编辑指定key的广告信息
function editAd(adkey){
	
	$.post("ajaxmanage.php",
			{m:'ad',action:'get',key:adkey},
			function(data){				
				showEditDialog();
				var json = eval('['+data+']');	
				$("#adorderid").val(json[0][0]);
				$("#adlinklable").val(json[0][1]);							
				$("#adposition").text(json[0][2]);
				$("#adlinkname").val(json[0][3]);
				$("#adlinkurl").val(json[0][4]);
				$("input[name=adtype][value="+json[0][5]+"]").attr("checked","checked");
				$("#adsrc").val(json[0][7]);		 
				$("#adkey").val(adkey);				 
			});
	
}
 
// 更新广告信息 
function updateAd(){
	_adorderid=$("#adorderid").val();
	_adlinklable=$("#adlinklable").val();							
	_adposition=$("#adposition").text();
	_adlinkname=$("#adlinkname").val();
	_adlinkurl=$("#adlinkurl").val();
	_adtype=$("input[name=adtype]:checked").val();
	_adsrc=$("#adsrc").val();		 
	_adkey=$("#adkey").val();	
	
	if(!/^-?\d+$/.test(_adorderid)){
		//messageBox('系统提示','排序必须为整数！');	
		alert('排序必须为整数!');
		return false;
	 }
	 if(_adlinkname.Trim()==""){
		 alert("广告名不能为空！");
		 return false;
	 }
	// if(_adlinkurl.Trim()==""){
	//	 alert("链接地址不能为空！");
	//	 return false;
	// }
	 if(_adlinkurl.Trim()!=""){
		 if(!/^http[s]?:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/.test(_adlinkurl.Trim())){
				alert("链接地址必须为URL，如：http://ctrip.com; 并且以http://或https://开头");
				return false;
		 }
	 }
	 if(_adtype!=0&&_adtype!=-1&&_adsrc.Trim()==""){
		 alert("外部资源不能为空！");
		 return false;
	 }
	 $.post("ajaxmanage.php",
			 {	m:'ad',
		 		action:'update',
		 		key:_adkey,
		 		orderId:_adorderid,
		 		linkLable:_adlinklable,
		 		linkName:_adlinkname,
		 		linkPosition:_adposition,
		 		linkUrl:_adlinkurl,
		 		type:_adtype,
		 		src:_adsrc
		 	 },
		 	 function(data){
		 		var json = eval('['+data+']');	
		 		//customAlert(json[0]['msg']);
				alert(json[0]['msg']);
				window.location.reload();
			 });
}

function showEditDialog(){
	$('.mask').vmodal({title:'修改信息',
					   width:450 ,					  
					   mask:$('#blackmask'),
					   btnOkEvent:function(){updateAd();}
	   				  }
	   				);
}


/**
 * friendlink.php 所需js function
 * */
$(document).ready(function(){
	$("input[name=statue]").click(function(index){
			if($(this).val()=='1'){
			$('.date').fadeIn();
			}else{
				$('.date').hide();
				$("#alivedate").val('');
	 	 	}
	});
	
});
//编辑友情链接
function editFL(flId){ 
	
	$('.friend_mask').vmodal({title:'修改信息',
		   width:450 ,
		   mask:$('#blackmask'),
		   btnOkEvent:function(){updateFL();}
		  }
		);
   
	 $.post("ajaxmanage.php",
				{m:'friendlink',action:'get',key:flId},
				function(data){				
				 
					var json = eval('['+data+']');	
					 
					$("#orderid").val(json[0][0]);
					$("#linkname").val(json[0][1]);							
					$("#linkurl").val(json[0][2]);					 
					$("input[name=statue][value="+json[0][3]+"]").attr("checked","checked");
					$('.date').hide();
					if(json[0][3]=='1'){
						$('.date').show();
					}
					$("input[name=type][value="+json[0][4]+"]").attr("checked","checked");
					// alert(json[0][6]);
					$("#alivedate").val(json[0][6]);	
					$("#src").val(json[0][7]);		 
					$("#key").val(flId);				 
				});

		
}
// 新增友情链接 
function addFl(){
	$('.friend_mask').vmodal({title:'新增友情链接',
		   width:450 ,
		   mask:$('#blackmask'),
		   btnOkEvent:function(){updateFL();}
		  }
		);
	
	    $("#orderid").val('100');
		$("#linkname").val('');							
		$("#linkurl").val('http://');					 
		$("input[name=statue][value=2]").attr("checked","checked");
		$("input[name=type][value=1]").attr("checked","checked");
		// alert(json[0][6]);
		$("#alivedate").val('');	
		$("#src").val('');		 
		$("#key").val("addnew");
}

//提交更新操作
function updateFL(){
 	_orderid=$("#orderid").val();
	_linkname=$("#linkname").val();							
	_linkurl=$("#linkurl").val();					 
	_statue=$("input[name=statue]:checked").val();
	_type=$("input[name=type]:checked").val();	 
	_alivedate=$("#alivedate").val();	
	_src=$("#src").val();		 
	_key=$("#key").val();  

	if(_linkname.Trim()==""){
		//messageBox('系统提示','链接名不能为空！');
		alert("链接名不能为空！");
		$("#linkname").focus();
		return false;
		}
	//if(_linkurl.Trim()==""){
		//messageBox('系统提示','链接地址不能为空！');
	//	alert("链接地址不能为空！");
	//	$("#linkurl").focus();
	//	return false;
	//	}
	if(_linkurl.Trim()!=""){
		if(!/^http[s]?:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/.test(_linkurl.Trim())){
			alert("链接地址必须为URL，如：http://ctrip.com; 并且以http://或https://开头");
			return false;
		}
	}
	if(_type==1&&_src.Trim()==""){
		//messageBox('系统提示','资源地址不能为空！');
		alert("资源地址不能为空！");
		$("#src").focus();
		return false;
		}
	if(_statue==1&&_alivedate.Trim()==""){
		//messageBox('系统提示','有效日期不能为空！');
		alert("有效日期不能为空！");
		$("#alivedate").focus();
		return false;
		}
	if(_statue==1&&!/^(\d{1,4})(-)(\d{1,2})\2(\d{1,2})$/.test(_alivedate)){
		alert("日期格式不正确，格式必须为yyyy-mm-dd,如：2012-09-25");
		return false;
	}
	 if(!/^-?\d+$/.test(_orderid)){
		//messageBox('系统提示','排序必须为整数！');
		alert("排序必须为整数！");
		return false;
	 }
	$.post("ajaxmanage.php",
			 {	m:'friendlink',
		 		action:'update',
		 		key:_key,
		 		orderid:_orderid,
		 		linkname:_linkname,
		 		linkurl:_linkurl,
		 		statue:_statue,
		 		type:_type,
		 		alivedate:_alivedate,
		 		src:_src
		 	 },
		 	 function(data){			 	 
		 		var json = eval('['+data+']');	
		 		if(json[0]['rs']=='0'){
		 			alert("保存失败，"+json[0]['msg']);
		 		}else{
		 			window.location.reload();
		 		}	 
				
			 });
	
}
//删除友情链接
function deleteFL(flId){
	$('.mask_delete').vmodal({title:'删除友情链接',
		   width:450 ,	
		   mask:$('#blackmask'),
		   btnOkEvent:function(){
				$.post("ajaxmanage.php",
							{m:'friendlink',action:'delete',key:flId},
							function (data){
								var json = eval('['+data+']');	
								if(json[0]['rs']=='0'){
									//messageBox('系统提示',"删除失败，"+json[0]['msg']);
									alert("删除失败，"+json[0]['msg']);
								}else{
									window.location.reload();
								}
							});
				
			}
		  }
		);
	//$(".mask_delete").show();
 
	
}
//批量删除友情链接
function batchDeleteFL(){
	keys="";
	$("input[name=checkbox]:checked").each(function(index){
		keys+=$(this).val()+",";
	});
	if(keys==""){
		alert("请先选择需要删除的选项！");
		return false;
	}
	//alert(keys);//return;
	$('.mask_delete').vmodal({title:'删除友情链接',
		   width:450 ,
		   mask:$('#blackmask'),
		   btnOkEvent:function(){
					$.post("ajaxmanage.php",
							{m:'friendlink',action:'delete',key:keys},
							function (data){
								var json = eval('['+data+']');	
								if(json[0]['rs']=='0'){
									//messageBox('系统提示',"删除失败，"+json[0]['msg']);
									alert("删除失败，"+json[0]['msg']);
								}else{
									window.location.reload();
								}
							});
				}
			});
}




/**
 * keyword.php 所需js function
 * */
//编辑关键字
function editKeyword(_id){

	$('.mask').vmodal({title:'管理信息',
		   width:450 ,
		   mask:$('#blackmask'),
		   btnOkEvent:function(){updateKeyword();}
		});
	
	$.post("ajaxmanage.php",
			{m:'keyword',action:'get',id:_id},
			function(data){
				var json = eval('['+data+']');

				$("#keywordID").val(_id);
				$("#pagename").val(json[0]['pagename']);
				$("#page").val(json[0]['page']);
				$("#title").val(json[0]['title']);
				$("#keywords").val(json[0]['keywords']);
				$("#description").val(json[0]['description']);
				$("#rule").val(json[0]['rule']);

				 
			});
}
//提交更新关键字
function updateKeyword(){
	_id=$("#keywordID").val();
	_pagename=$("#pagename").val();
	_page=$("#page").val();
	_title=$("#title").val();
	_keywords=$("#keywords").val();
	_description=$("#description").val();
	_rule=$("#rule").val();
	
	if(_pagename.Trim()==""){
		//messageBox('系统提示','页面名不能为空！');
		alert("页面名不能为空！");
		$("#pagename").focus();
		return false;
	}
	if(_pagename.length>100){
		//messageBox('系统提示','页面名不能为空！');
		alert("页面名不能超过100个字符！");
		$("#pagename").focus();
		return false;
	}
	if(_page.Trim()==""){
		//messageBox('系统提示','页面索引不能为空！');
		alert("页面索引不能为空！");
		$("#page").focus();
		return false;
	}
	if(_page.length>200){		 
		alert("页面索引不能超过200个字符！");
		$("#page").focus();
		return false;
	}
	if(_title.Trim()==""){
		//messageBox('系统提示','标题规则不能为空！');
		alert("标题规则不能为空！");
		$("#title").focus();
		return false;
	}
	if(_title.length>200){
		//messageBox('系统提示','标题规则不能为空！');
		alert("标题规则不能超过200个字符！");
		$("#title").focus();
		return false;
	}
	if(_keywords.Trim()==""){
		//messageBox('系统提示','关键字规则不能为空！');
		alert("关键字规则不能为空！");
		$("#keywords").focus();
		return false;
	}
	if(_keywords.length>200){
		//messageBox('系统提示','关键字规则不能为空！');
		alert("关键字规则不能超过200个字符！");
		$("#keywords").focus();
		return false;
	}
	if(_description.length>500){
		alert("页面描述不能超过500个字符！");
		$("#description").focus();
		return false;
	}
	if(_rule.Trim()==""){
		//messageBox('系统提示','规则不能为空！');
		alert("规则不能为空！");
		$("#rule").focus();
		return false;
	}
	if(_rule.length>500){
		//messageBox('系统提示','规则不能为空！');
		alert("规则不能超过500个字符！");
		$("#rule").focus();
		return false;
	}
	$.post("ajaxmanage.php",
			{	m:'keyword',
				action:'update',
				id:_id,
				pagename:_pagename,
				page:_page,
				title:_title,
				keywords:_keywords,
				description:_description,
				rule:_rule
			},
			function(data){
				 //alert(data);				 
				window.location.reload();				 
			});
}


/**
 * permission.php 所需js function
 * */
//新增用户
function adduser(){
		$('.permission_mask').vmodal({title:'新增用户',
		   width:450 ,
		   mask:$('#blackmask'),
		   btnOkEvent:function(){saveuserinfo('add');}
		});
		$("#username").attr("readonly",false);
	$("#username").val("");
	$("#password").val("");
	$(".password").html('密&nbsp;&nbsp;码：');
}
//修改用户信息
function edituser(_username){
	$('.permission_mask').vmodal({title:'修改密码',
 		   width:450 ,
 		  mask:$('#blackmask'),
 		   btnOkEvent:function(){saveuserinfo('update');}
 		});
	$("#username").attr("readonly",true);
	$("#username").val(_username);
	$(".password").html('新密码：');
	
}
//保存用户信息，saveType：'add'/'update' 
function saveuserinfo(saveType){		 
	_username=$("#username").val();
	_password=$("#password").val();
	if(_username.Trim()==""){
		//messageBox('系统提示','用户名不能为空！');
		alert("用户名不能为空！");
		$("#username").focus();
		return false;
	}
	if(_username.length>20){
		alert("用户名长度不能超过20字符！");
		$("#username").focus();
		return false;
	}
	if(_password.Trim()==""){
		//messageBox('系统提示','密码不能为空！');
		alert("密码不能为空字符！");
		$("#password").focus();
		return false;
	}
	if(_password.length>20){
		alert("密码长度不能超过20字符");
		$("#password").focus();
		return false;
	}
	$.post("ajaxmanage.php",
			{	m:'permission',
				action:saveType,
				username:_username,
				password:_password
			},
			function(data){	
				//alert(data);			 
				var json = eval('['+data+']');	
				if(json[0]['rs']=='0'){
					alert(json[0]['msg']);
				}
				else{
					//alert(json[0]['msg']);
					window.location.reload();
				}	
				
			});
	 
	
}
//删除用户
function deleteuser(_userids){
	$('.mask_delete').vmodal({title:'删除用户',
		   width:450 ,	
		   mask:$('#blackmask'),
		   btnOkEvent:function(){
				$.post("ajaxmanage.php",
						{	m:'permission',
							action:'delete',
							ids:_userids					 
						},
						function(data){				 
							var json = eval('['+data+']');	
							if(json[0]['rs']=='0'){
								//messageBox('系统提示',json[0]['msg']);	
								alert(json[0]['msg']);
								//window.location.reload();
							}
							else{
								//messageBox('系统提示',json[0]['msg']);
								//alert(json[0]['msg']);
								window.location.reload();
							}	
							
						});
			}
		});
}
//批量删除用户
function batchDeleteUser(){
	var ids="";
	$("input[name=checkbox]:checked").each(function(i){
		ids+=$(this).val()+',';
	});
	if(ids==""){
		alert("请先选择需要删除的选项！");
		return false;
	}
	//alert(ids);
	deleteuser(ids);
}


/**
 * siteset.php 所需js function
 * */
//提交前js验证
function checkSiteInfo(){
	 
	if($("input[name=uname]").val().length>300){
		alert("联盟用户名太长，不能超过300个字符");
		$("input[name=uname]").focus();
		return false;
	}
	if($("input[name=key]").val().length>300){
		alert("联盟站点Key太长，不能超过300个字符");
		$("input[name=key]").focus();
		return false;
	}
	if($("input[name=sitename]").val().length>300){
		alert("网站名称太长，不能超过300个字符");
		$("input[name=sitename]").focus();
		return false;
	}
	shortname=$("input[name=shortname]").val();
	if(shortname.Trim()==''){
		alert("网站简称不能为空！");
		$("input[name=shortname]").focus();
		return false;
	}
	if(shortname.length>10){
		alert("网站简称长度不要超过10个字符");
		$("input[name=shortname]").focus();
		return false;
	}
	if($("input[name=sitedomain]").val().length>300){
		alert("网站域名太长，不能超过300个字符");
		$("input[name=sitedomain]").focus();
		return false;
	}
	 
	//alert($("textarea[name=copyright]").val().length);
	if($("textarea[name=copyright]").val().length>300){
		alert("网站CopyRight太长，不能超过300个字符");
		$("textarea[name=copyright]").focus();
		return false;
	}
	if($("textarea[name=icp]").val().length>300){
		alert("网站ICP太长，不能超过300个字符");
		$("textarea[name=icp]").focus();
		return false;
	}
	if($("textarea[name=logo]").val().length>300){
		alert("网站Logo地址太长，不能超过300个字符");
		$("textarea[name=logo]").focus();
		return false;
	}
	if($("textarea[name=defaultimageurl]").val().length>300){
		alert("酒店列表默认图片地址，不能超过300个字符");
		$("textarea[name=defaultimageurl]").focus();
		return false;
	}
	pagesize=$("input[name=pagesize]").val();
	if(!(isint(pagesize)&&pagesize>0)){
		alert("酒店搜索页每页显示的条数必须为大于0的整数");
		$("input[name=pagesize]").focus();
		return false;
	}	
	historynum=$("input[name=historynum]").val();
	if(!(isint(historynum)&&historynum>0)){
		alert("酒店最多显示的浏览记录必须为大于0的整数");
		$("input[name=historynum]").focus();
		return false;
	}	
	commentlistnum=$("input[name=commentlistnum]").val();
	if(!(isint(commentlistnum)&&commentlistnum>0)){
		alert("酒店点评页面评论总数必须为大于0的整数");
		$("input[name=commentlistnum]").focus();
		return false;
	}	
	commentindexnum=$("input[name=commentindexnum]").val();
	if(!(isint(commentindexnum)&&commentindexnum>0)){
		alert("首页酒店评论数必须为大于0的整数");
		$("input[name=commentindexnum]").focus();
		return false;
	}	

	newopentime=$("input[name=newopentime]").val();
	if(!(isint(newopentime)&&newopentime>0)){
		alert("最新开业起始时间必须为大于0的整数");
		$("input[name=newopentime]").focus();
		return false;
	}	
	newcontracttime=$("input[name=newcontracttime]").val();
	if(!(isint(newcontracttime)&&newcontracttime>0)){
		alert("最新加盟起始时间必须为大于0的整数");
		$("input[name=newcontracttime]").focus();
		return false;
	}
	charset=$("input[name=charset]").val();
	if(charset.Trim()==""){
		alert("页面编码格式不能为空！");
		$("input[name=charset]").focus();
		return false;
	}
	HotelSearchDayNums=$("input[name=HotelSearchDayNums]").val();
	if(!(isint(HotelSearchDayNums)&&HotelSearchDayNums>0 && HotelSearchDayNums<29)){
		alert("酒店搜索默认截止日期必须为大于0且小于29的整数");
		$("input[name=HotelSearchDayNums]").focus();
		return false;
	}
	
	
	return true;
}

/**
 * policy.php 所需js
 */
//编辑挂牌
function editPolicy(policyId){
		
	$('.friend_mask').vmodal({title:'修改信息',
		   width:450 ,
		   mask:$('#blackmask'),
		   btnOkEvent:function(){updatePolicy();}
		  }
		);
   
	 $.post("ajaxmanage.php",
				{m:'policy',action:'get',key:policyId},
				function(data){				
				 
					var json = eval('['+data+']');	
					 
					$("#orderid").val(json[0][0]);
					$("#policyname").val(json[0][1]);							
					$("#linkurl").val(json[0][2]);					 
					$("#imageurl").val(json[0][3]);		 
					$("#key").val(policyId);				 
				});

		
}
// 新增友情链接 
function addPolicy(){
	$('.friend_mask').vmodal({title:'新增挂牌',
		   width:450 ,
		   mask:$('#blackmask'),
		   btnOkEvent:function(){updatePolicy();}
		  }
		);	
	    $("#orderid").val('100');
		$("#policyname").val('');							
		$("#linkurl").val('http://');				 
	 
		$("#imageurl").val('');		 
		$("#key").val("addnew");
}

//提交更新操作
function updatePolicy(){
 	_orderid=$("#orderid").val();
	_policyname=$("#policyname").val();							
	_linkurl=$("#linkurl").val();				 
 
	_src=$("#imageurl").val();		 
	_key=$("#key").val();  

	if(_policyname.Trim()==""){
		//messageBox('系统提示','链接名不能为空！');
		alert("挂牌名不能为空！");
		$("#policyname").focus();
		return false;
		}
	if(_src.Trim()==""){
		//messageBox('系统提示','资源地址不能为空！');
		alert("图片地址不能为空！");
		$("#src").focus();
		return false;
		}
	if(_linkurl.Trim()==""){
		//messageBox('系统提示','链接地址不能为空！');
		alert("链接地址不能为空！");
		$("#linkurl").focus();
		return false;
		}
	if(!/^http[s]?:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/.test(_linkurl.Trim())){
		alert("链接地址必须为URL，如：http://ctrip.com; 并且以http://或https://开头");
		return false;
	}	 
	 if(!/^-?\d+$/.test(_orderid)){
		//messageBox('系统提示','排序必须为整数！');
		alert("排序必须为整数！");
		return false;
	 }
	$.post("ajaxmanage.php",
			 {	m:'policy',
		 		action:'update',
		 		key:_key,
		 		orderid:_orderid,
		 		policyname:_policyname,
		 		linkurl:_linkurl,
		 		 
		 		src:_src
		 	 },
		 	 function(data){			 	 
		 		var json = eval('['+data+']');	
		 		if(json[0]['rs']=='0'){
		 			alert("保存失败，"+json[0]['msg']);
		 		}else{
		 			window.location.reload();
		 		}	 
				
			 });
	
}
//删除友情链接
function deletePolicy(policyId){
	$('.mask_delete').vmodal({title:'删除挂牌链接',
		   width:450 ,	
		   mask:$('#blackmask'),
		   btnOkEvent:function(){
				$.post("ajaxmanage.php",
							{m:'policy',action:'delete',key:policyId},
							function (data){
								var json = eval('['+data+']');	
								if(json[0]['rs']=='0'){
									//messageBox('系统提示',"删除失败，"+json[0]['msg']);
									alert("删除失败，"+json[0]['msg']);
								}else{
									window.location.reload();
								}
							});
				
			}
		  }
		);
	//$(".mask_delete").show();
 
	
}
 