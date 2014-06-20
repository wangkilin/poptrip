<?php
require_once (WEBROOT.'admin/inc/utility.php');
//验证是否登录
if (!$session->contain('admin')){
	exit("访问受限.......");
}


$topicalConfig=new topicalConfig();
if (empty($_POST['save'])){
	$topicalConfig->load();
}else{
	
	$topicalConfig->UnionSite_Css=$_POST['UnionSite_Css'];
	$rs=$topicalConfig->save();
	if ($rs['rs']=='1'){
		$return=$topicalConfig->rewriteConfigFile();
		if ($return['rs']=='1'){
			showMsg("保存成功");
		}
		else{
			 showMsg('保存出错，生成site.config.php出错');
		}
	}else{
		showMsg($rs['msg']);
	}
	 
}

?>


<form method='post'>
<div class="notice_box">
				<h3>主题管理</h3>
				<ul class="theme_select">
					<li>
						<label>
							<img src="images/blue.jpg" alt="" style="background:#CCC;" />
							<input type='radio' name='UnionSite_Css' value=""  <?php if(empty($topicalConfig->UnionSite_Css))echo "checked";?> >蓝色主题
						</label>
						<a href="<?php echo $UnionSite_domainName;?>/admin/images/bigblue.png" target="_blank">预览</a>
					</li>
					<li>
						<label>
							<img src="images/orange.jpg" alt="" style="background:#CCC;" />
						<input type='radio' name='UnionSite_Css' value="/site/css/styles_orange.css" <?php if($topicalConfig->UnionSite_Css=='/site/css/styles_orange.css')echo "checked";?>>桔色主题
						</label>
						<a href="<?php echo $UnionSite_domainName;?>/admin/images/bigorange.png" target="_blank">预览</a>
					</li>
					<li>
						<label>
							<img src="images/red.jpg" alt="" style="background:#CCC;" />
							<input type='radio' name='UnionSite_Css' value="/site/css/styles_red.css" <?php if($topicalConfig->UnionSite_Css=='/site/css/styles_red.css')echo "checked";?>> 红色主题
						</label>
						<a href="<?php echo $UnionSite_domainName;?>/admin/images/bigred.png" target="_blank">预览</a>
					</li>
					
					<li>
						<label>
						<input type="hidden" value="submit" name="save"/>
						<input type="submit" value="提  交" class="btn_orange" />
						</label>
					
					</li>
				
				</ul>
			</div>

</form>

