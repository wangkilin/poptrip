

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
			<th style="width: 48px;"></th>
			<th style="width: 70px;">链接名称</th>
			<th  >链接地址</th>
			<th style="width: 50px;">状态</th>
			<th style="width: 90px;">类型</th>
			<th style="width: 30px;">排序</th>
			<th style="width: 60px;">添加时间</th>
			<th style="width:60px;">操作</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$pagesize=10;
	
	$currentPage=empty($_GET['page'])?1:$_GET['page'];
	$totalItems;
	//print_r($siteFriendLinkArray);
	if (!empty($siteFriendLinkArray)){
		$col=0;
		$totalItems=count($siteFriendLinkArray);
		$pageNum=ceil($totalItems/$pagesize);
		if ($currentPage>$pageNum)
			$currentPage=$pageNum;
		$offset=($currentPage-1)*$pagesize;
		 
		$tempArray=array_slice($siteFriendLinkArray, $offset,$pagesize,true);
		foreach ($tempArray as $k=>$v){
			$col++;
			?>

		<tr class="<?php echo $col%2==0?'double':'';?>">
			<td> <input type="checkbox" name="checkbox" 
				value="<?php echo $k;?>" /></td>
			<td><?php echo $v[1];?></td>
			<td><?php echo $v[2];?></td>
			<td><?php echo showFLStatue($v[3]);?></td>
			<td><?php echo showFLType($v[4]);?></td>
			<td><?php echo $v[0];?></td>
			<td><?php echo $v[5];?></td>
			<td><a href="#" onclick="editFL(<?php echo $k;?>)">修改</a>&nbsp;<a
			href='#'	onclick="deleteFL(<?php echo $k;?>)">删除</a></td>
		</tr>
		<?php
		}
	}
	?>

		<tr class="mod_table_bottom">
			<td><label onclick="select()"><input type="checkbox" name=""   
				id="checkAll" />全选</label></td>
			<td><input type="button" value="删除所选" class="btn_delete"
				onclick="batchDeleteFL()" /></td>
			<td colspan="6"></td>
		</tr>
	</tbody>
</table>
<div class="btn_box"><input type="button" value="新  增" class="btn_blue"
	onclick="addFl()" /></div>

	<?php
	if ($totalItems>$pagesize){
		$pager=new SubPages($pagesize, $totalItems, $currentPage, 7, 'manage.php?m=flink', 4);
	}
	?>
 
<!-- 蒙板begin -->
<div class="mask friend_mask" style="display: none;">
<h3>修改信息</h3>
<table cellspacing="0" cellpadding="0" class="mask_type3">
	<!-- 选中“上传图片”，类名变为“mask_type1”；选中“Google百度等广告代码”，类名变为“mask_type2”；选中“Google百度等广告代码”，类名变为“mask_type3”； -->
	<tbody>
		<tr class="mask_top">
			<th style="width: 100px;">类型：</th>
			<td colspan="3"><input type="hidden" id="key" value="" /><label><input
				type="radio" value="0" name="type" />文字链接</label><label><input
				type="radio" value="1" name="type" />外部图片链接</label></td>
		</tr>
		<tr>
			<th>链接名称：</th>
			<td colspan="3"><input type="text" value="" id="linkname"
				class="input_text" /></td>
		</tr>
		<tr>
			<th>链接地址：</th>
			<td colspan="3"><input type="text" value="" id="linkurl"
				class="input_text" /> <span>(必须以http://或https://开头 )</span></td>
				 
		</tr>


		<tr>
			<th>资源地址：</th>
			<td colspan="3"><input type="text" value="" id="src"
				class="input_text" /><span class="note type2">规格尺寸：88*88pixel</span></td>
		</tr>
		<tr>
			<th>排序：</th>
			<td colspan="3"><input type="text" value="" id="orderid"
				class="input_text" style="width: 40px;" /></td>
		</tr>
		<tr>
			<th style="width: 100px; vertical-align: top;">状态：</th>
			<td><label><input type="radio" name="statue" value="1" />有效期内有效</label><label><input
				type="radio" name="statue" value="2" />永久</label><label><input
				type="radio" name="statue" value="0" />停止</label>
			<div class="date" style="display:none"><!-- 只有选中“正常”是才显示 --> 有效日期：<input type="text"
				id="alivedate" value=" " /> <span></span></div>
			</td>
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
<p class="delete">你确认要删除选中的友情链接吗？</p>
<div class="btn_box"><input type="button" value="确  认"  
	class="btn_orange ok" /><input type="button" value="取  消" class="btn_blue cancel" />
</div>
<a href="#" class="close cancel">×</a></div>
<!-- 蒙板end -->
