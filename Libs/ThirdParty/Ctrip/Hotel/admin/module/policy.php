<?php

//验证是否登录
if (!$session->contain('admin')){
	exit("访问受限.......");
}

?>
<script type="text/javascript" src="js/jquery.datePicker.js"></script>
<script type="text/javascript" src="js/date.js"></script>
<link rel="stylesheet" href="styles/datePicker.css" />
<script type="text/javascript"> 
$(function()
        {
		Date.dayNames = ['日', '一', '二', '三', '四', '五', '六'];
Date.abbrDayNames = ['日', '一', '二', '三', '四', '五', '六'];
Date.monthNames = ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'];
Date.abbrMonthNames = ['一', '二', '三', '四', '五', '六', '七', '八', '九', '十', '十一', '十二'];
Date.firstDayOfWeek = 1;
Date.format = 'yyyy-mm-dd';
			$('#alivedate').datePicker({clickInput:true,createButton:false})
        });
 </script>
<table cellspacing="0" cellpadding="0" class="mod_table">
	<thead>
		<tr>			 
			<th style="width: 120px;">挂牌名</th>
			<th  >链接地址</th>
			<th style="width: 100px;">图片地址</th>			 
			<th style="width: 30px;">排序</th>			 
			<th style="width:60px;">操作</th>
		</tr>
	</thead>
	<tbody>
	<?php
	 $pagesize=10;
		$pagenums;
		$currentPage=empty($_GET['page'])?1:$_GET['page'];
		$totalItems;
	//print_r($siteFriendLinkArray);
	if (!empty($sitePolicyArray)){
		$col=0;		
		$totalItems=count($sitePolicyArray);
		 
		$pageNum=ceil($totalItems/$pagesize);
		if ($currentPage>$pageNum)
			$currentPage=$pageNum;
		$offset=($currentPage-1)*$pagesize;
		$tempArray=array_slice($sitePolicyArray, $offset,$pagesize,true);
						
		foreach ($tempArray as $k=>$v){
			$col++;
			?>

		<tr class="<?php echo $col%2==0?'double':'';?>">
			 
			<td><?php echo $v[1];?></td>
			<td><?php echo $v[2];?></td>
			 	<td><?php echo $v[3];?></td>
			<td style="text-align:center"><?php echo $v[0];?></td>
			<td><a href="#" onclick="editPolicy(<?php echo $k;?>)">修改</a>&nbsp;<a
			href='#'	onclick="deletePolicy(<?php echo $k;?>)">删除</a></td>
		</tr>
		<?php
		}
	}
	?>

	 	
	</tbody>
</table>
<div class="btn_box" style="padding-top:10px;"><input type="button" value="新  增" class="btn_blue"
	onclick="addPolicy()" /></div>
 	<?php
	if ($totalItems>$pagesize){
		$pager=new SubPages($pagesize, $totalItems, $currentPage, 7, 'manage.php?m=policy', 4);
	}
	?>
 
<!-- 蒙板begin -->
<div class="mask friend_mask" style="display: none;">
<h3>修改信息</h3>
<table cellspacing="0" cellpadding="0" class="mask_type3">
	<!-- 选中“上传图片”，类名变为“mask_type1”；选中“Google百度等广告代码”，类名变为“mask_type2”；选中“Google百度等广告代码”，类名变为“mask_type3”； -->
	<tbody>
		<tr class="mask_top">
			<th style="width: 100px;">挂牌名称：</th>
			<td colspan="3"><input type="text" value="" id="policyname"
				class="input_text" /> <input type="hidden" id="key" value="" /></td>
		</tr>
		<tr>
			<th>图片地址：</th>
			<td colspan="3"><input type="text" value="" id="imageurl"
				class="input_text" /><span></span></td>
		</tr>
		<tr>
			<th>链接地址：</th>
			<td colspan="3"><input type="text" value="" id="linkurl"
				class="input_text" /> <span>(必须以http://或https://开头 )</span></td>
				 
		</tr>


		 
		<tr>
			<th>排序：</th>
			<td colspan="3"><input type="text" value="100" id="orderid"
				class="input_text" style="width: 40px;" /></td>
		</tr>
		 
		<tr class="mask_bottom">
			<th></th>
			<td colspan="3"><input type="button" value="提  交" class="btn_orange ok"
				  /><input type="button" value="返  回"
				class="btn_blue  cancel" /></td>
		</tr>
	</tbody>
</table>
<a href="#" class="close cancel">×</a></div>

<div class="mask mask_delete" style="display:none  ;">
<h3></h3>
<p class="delete">你确认要删除该网站挂牌吗？</p>
<div class="btn_box"><input type="button" value="确  认"  
	class="btn_orange ok" /><input type="button" value="取  消" class="btn_blue cancel" />
</div>
<a href="#" class="close cancel">×</a></div>
<!-- 蒙板end -->
