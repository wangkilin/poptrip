<?php
require_once (WEBROOT.'admin/inc/utility.php');
require_once (WEBROOT.'admin/inc/common.php');
//验证是否登录
if (!$session->contain('admin')){
	exit("访问受限.......");
}

$sysConfig=new sysConfig();
if (empty($_POST['save'])){
	$sysConfig->load();
}else{
	$sysConfig->SiteAllianceid_Uid=$_POST['uname'];
	$sysConfig->SiteAllianceid=$_POST['uid'];
	$sysConfig->SiteSid=$_POST['sid'];
	$sysConfig->SiteSiteKey=$_POST['key'];
	$sysConfig->UnionSite_Name=$_POST['sitename'];
	$sysConfig->UnionSite_domainName=$_POST['sitedomain'];
 
	$sysConfig->SiteHotelDefaultImageUrl=$_POST['defaultimageurl'];
	$sysConfig->SiteDefaultCityID=$_POST['city'];
	$sysConfig->UnionCopyRight=$_POST['copyright'];
	$sysConfig->UnionICP=$_POST['icp'];
	$sysConfig->SiteLogImageUrl=$_POST['logo'];
	$sysConfig->SiteUrlRewriter=$_POST['urlrewrite'];
	$sysConfig->SiteHotelSearch_pagesize=$_POST['pagesize'];
	$sysConfig->SiteHotelBrowserListTotalNums=$_POST['historynum'];
	$sysConfig->HotelNewContractTime=$_POST['newcontracttime'];
	$sysConfig->HotelNewOpenTime=$_POST['newopentime'];
	$sysConfig->HotHotelCommentNumsIndex=$_POST['commentindexnum'];
	$sysConfig->HotHotelCommentNumsList=$_POST['commentlistnum'];
	$sysConfig->SiteCharset=$_POST['charset'];
	$sysConfig->HotelSearchDayNums=$_POST['HotelSearchDayNums'];
	$sysConfig->UnionSite_ShortName=$_POST['shortname'];
	$sysConfig->Booking_State=$_POST['Booking_State'];
	$sysConfig->MapKey=$_POST['MapKey'];
	$rs=$sysConfig->save();
	
	if ($rs['rs']=='1'){
		$return=$sysConfig->rewriteConfigFile();
		if ($return['rs']=='1'){
			//showCustomMsg('保存成功', '保存成功!');
			showMsg("保存成功");
			//print_r($return);
		}
		else{
			//showCustomMsg('保存出错', '生成site.config.php出错....');
			 showMsg('保存出错，生成site.config.php出错');
		}
		
		
	}else{
		//showCustomMsg('保存出错', $rs['msg']);
		showMsg($rs['msg']);
	}
	 
}
//print_r(get_object_vars($sysConfig));
//$sysConfig->UnionCopyRight="版权：携程酒店预订 Copyright &copy; 1999-2012, <a href='#'>http://ctrip.com</a>. All rights reserved.";
//print_r($sysConfig->save());
//print_r($sysConfig->rewriteConfigFile());


//判断服务器类型，只有APACHE 可以开启伪静态
$sp_server = strtolower($_SERVER['SERVER_SOFTWARE']);
$sp_server = count(explode('apache',$sp_server))>1?'1':'0';


?>
<script type="text/javascript">

$(document).ready(function(){
	$('#city').val($('#defaultcity').val());
	$("input[name=urlrewrite][value="+$('#urlrewrite').val()+"]").attr('checked',true);
	$("input[name=Booking_State][value="+$('#Booking_State').val()+"]").attr('checked',true);
});

</script>
<form method='post' onSubmit="return checkSiteInfo();">
<table cellspacing="0" cellpadding="0" class="mod_table2">
	<tbody>
		<tr>
			<th style="width:200px;">联盟用户名：</th>
			<td style="width: 330px;"><input type="text" name="uname"
				value="<?php echo $sysConfig->SiteAllianceid_Uid;?>"
				class="input_text" /></td>
			<td>(必须小于300个字符)</td>
		</tr>
		<tr>
			<th style="width:200px;">联盟ID：</th>
			<td  style="width: 330px;" ><input type="text" name="uid"
				value="<?php echo $sysConfig->SiteAllianceid;?>" class="input_text" /></td>
		<td></td>
		</tr>
		<tr>
			<th  style="width: 200px;">联盟站点ID：</th>
			<td  ><input type="text" name="sid"
				value="<?php echo $sysConfig->SiteSid;?>" class="input_text" /></td>
		<td></td>
		</tr>
		<tr>
			<th  style="width: 200px;">联盟站点Key：</th>	
			<td  ><input type="text" name="key"
				value="<?php echo $sysConfig->SiteSiteKey;?>" class="input_text" /></td>
			<td>(必须小于300个字符)</td>
		</tr>
		<tr>
			<th  style="width: 200px;">网站名称：</th>	
			<td  ><input type="text" name="sitename"
				value="<?php echo $sysConfig->UnionSite_Name;?>" class="input_text" /></td>
		<td>(必须小于300个字符)</td>
		</tr>
		<tr>
			<th  style="width: 200px;">网站简称：</th>	
			<td  ><input type="text" name="shortname"
				value="<?php echo $sysConfig->UnionSite_ShortName;?>" class="input_text" /></td>
		<td>(用于发送短信，标志订单来贵站点，建议在5个汉字内)</td>
		</tr>
		<tr>
			<th  style="width: 200px;">网站主域名：</th>
			<td  ><input type="text" name="sitedomain"
				value="<?php echo $sysConfig->UnionSite_domainName;?>"
				class="input_text" /></td>
		<td>(必须小于300个字符)</td>
		</tr> 
		
		<tr>
			<th  style="width: 200px;">默认显示的城市：</th>
			<td ><input type="hidden" id='defaultcity'
				value="<?php echo $sysConfig->SiteDefaultCityID;?>" /> <select name="city"
				id="city">
				
				<?php foreach ($defaultCityNameArray as $k =>$v){?>
				<option value='<?php echo $k?>'><?php echo $v?></option>
				<?php }?>

			</select></td>
		<td>(修改后当前浏览器可能保存上一个默认城市缓存，大概五分钟后就会正常)</td>
		</tr>
		<tr>
			<th style="width: 200px;vertical-align: top;">网站CopyRight：</th>
			<td  ><textarea name="copyright"><?php echo $sysConfig->UnionCopyRight;?></textarea></td>
		<td>(必须小于300个字符)</td>
		</tr>
		<tr>
			<th style="width: 200px;vertical-align: top;">网站ICP：</th>
			<td  ><textarea name="icp"><?php echo $sysConfig->UnionICP;?></textarea></td>
		<td>(必须小于300个字符)</td>
		</tr>
		<tr>
			<th style="width: 200px;vertical-align: top;">网站Logo地址：</th>
			<td  ><textarea name="logo"><?php echo $sysConfig->SiteLogImageUrl;?></textarea></td>
		<td>(必须小于300个字符)</td>
		</tr>
		<tr>
			<th  style="width: 200px;">酒店列表默认图片地址：</th>
			<td ><textarea   name="defaultimageurl"><?php echo $sysConfig->SiteHotelDefaultImageUrl;?></textarea>
				 </td>
		<td>(必须小于300个字符)</td>
		</tr>
		<tr>
			<th  style="width: 200px;">是否开启伪静态：</th>
			<td  >
			<input type="hidden" id='urlrewrite' value="<?php echo $sysConfig->SiteUrlRewriter;?>"/>
			
			<label><input type="radio" name="urlrewrite" value='1' <?php if($sp_server!='1'){?> disabled <?php }?> />打开</label>
			<label><input type="radio" name="urlrewrite"  value='0'/>关闭</label></td>
		
		
		
		<td>  <?php if($sp_server!='1'){?> (只有apache服务器可以开启伪静态 )<?php }?></td>
		</tr>
		<tr>
			<th  style="width: 200px;">酒店搜索页每页显示的条数：</th>
			<td  ><input type="text" name="pagesize" value="<?php echo $sysConfig->SiteHotelSearch_pagesize;?>"
				class="input_text" /></td>
		<td>(必须为大于 0 的整数)</td>
		</tr>
		<tr>
			<th  style="width: 200px;">酒店最多显示的浏览记录：</th>
			<td  ><input type="text" name="historynum" value="<?php echo $sysConfig->SiteHotelBrowserListTotalNums;?>" class="input_text" /></td>
		<td>(必须为大于 0 的整数)</td>
		</tr>
		<tr>
			<th  style="width: 200px;">酒店点评页面评论总数：</th>
			<td  ><input type="text" name="commentlistnum" value="<?php echo $sysConfig->HotHotelCommentNumsList;?>" class="input_text" /></td>
		<td>(必须为大于 0 的整数)</td>
		</tr>
		<tr>
			<th  style="width: 200px;">首页酒店评论数：</th>
			<td  ><input type="text" name="commentindexnum" value="<?php echo $sysConfig->HotHotelCommentNumsIndex;?>" class="input_text" /></td>
		<td>(必须为大于 0 的整数)</td>
		</tr>
		<tr>
			<th  style="width: 200px;">最新开业起始时间：</th>
			<td  ><input type="text" name="newopentime" value="<?php echo $sysConfig->HotelNewOpenTime;?>" class="input_text" /></td>
		<td>(必须为大于 0 的整数)</td>
		</tr>
		<tr>
			<th  style="width: 200px;">最新加盟起始时间：</th>
			<td  ><input type="text" name="newcontracttime" value="<?php echo $sysConfig->HotelNewContractTime;?>" class="input_text" /></td>
		<td>(必须为大于 0 的整数)</td>
		</tr>
		<tr>
			<th  style="width: 200px;">页面编码格式：</th>
			<td  ><input type="text" name="charset" value="<?php echo $sysConfig->SiteCharset;?>" class="input_text" /></td>
		<td>(必填，如utf-8)</td>
		</tr>
		<tr>
			<th  style="width: 200px;">酒店搜索默认截止日期：</th>
			<td  ><input type="text" name="HotelSearchDayNums" value="<?php echo $sysConfig->HotelSearchDayNums;?>" class="input_text" /></td>
		<td>(必须为大于 0 且小于29的整数)</td>
		</tr>
		<tr>
			<th  style="width: 200px;">是否跳转到携程主站：</th>
			<td  >
			<input type="hidden" id='Booking_State' value="<?php echo $sysConfig->Booking_State;?>"/>
			
			<label><input type="radio" name="Booking_State" value='0'  />不跳转</label>
			<label><input type="radio" name="Booking_State"  value='1'/>跳转</label></td>
		
		<td> </td>
		</tr>
		
		<tr>
			<th  style="width: 200px;">是否开启地图功能：</th>
			<td  >
	<textarea name="MapKey" ><?php echo $sysConfig->MapKey;?></textarea>
		</td>
		<td>(若开启地图功能，请先到<a href='http://open.mapbar.com/API_internet.jsp' target=_blank>图吧</a>申请 API密钥，然后写入框中,格式为f={}&v={}&k={}({请用图吧示例中数据替代}))</td>
		</tr>
		
		<tr>
			<th><input type="hidden" value="submit" name="save"/></th>
			<td colspan="2"><input type="submit" value="提  交" class="btn_orange" />
		</tr>
	</tbody>
</table>
</form>
