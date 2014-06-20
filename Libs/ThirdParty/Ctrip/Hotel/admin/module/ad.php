<?php 

//验证是否登录
if (!$session->contain('admin')){
	exit("访问受限.......");
}
 
?>
  
<table cellspacing="0" cellpadding="0" class="mod_table">
				<thead>
					<tr>
					<th style="width:30px;">Order</th>
						<th style="width:80px;">位置</th>
						<th style="width:90px;">链接名称</th>
						<th style="">链接地址</th>
						<th style="width:70px;">类型</th>
						<th style="width:80px;">添加时间</th>
						<th style="width:40px;">操作</th>
					</tr>
				</thead>
				<tbody>
				<?php 	
					$pagesize=10;
					$pagenums;
					$currentPage=empty($_GET['page'])?1:$_GET['page'];
					$totalItems;			 				
					//print_r($siteAdArray);
					if (!empty($siteAdArray)){	
						$col=0;		
						$totalItems=count($siteAdArray);
						 
						$pageNum=ceil($totalItems/$pagesize);
						if ($currentPage>$pageNum)
							$currentPage=$pageNum;
						$offset=($currentPage-1)*$pagesize;
						$tempArray=array_slice($siteAdArray, $offset,$pagesize,true);
						foreach ($tempArray as $k=>$v){
							$col++;
							?>
						<tr class="<?php echo $col%2==0?'double':'';?>">
						<td><?php echo $v[0];?></td>
						<td><?php echo $v[2];?></td>
						<td><?php echo $v[3];?></td>
						<td><?php echo $v[4];?></td>
						<td><?php echo showAdType($v[5]);?></td>
						<td><?php echo $v[6]?></td>
						<td><a href="#" onclick="editAd(<?php echo $k;?>)">修改</a></td>
					</tr>
				<?php 
						}
					}
				?>
					 
				</tbody>
			</table>
			 	<?php
	if ($totalItems>$pagesize){
		$pager=new SubPages($pagesize, $totalItems, $currentPage, 7, 'manage.php?m=ad', 4);
	}
	?>
 
			 
			<!-- 蒙板begin -->

	<div class="mask" style="margin:20px 0 30px 50px; display:none;width:600px;">
		<h3>修改信息</h3>
		<table cellspacing="0" cellpadding="0" class="mask_type1" style="">  <!-- 选中“上传图片”，类名变为“mask_type1”；选中“Google百度等广告代码”，类名变为“mask_type2”； -->
			<tbody>
				<tr class="mask_top">
					<th>位置：</th>
					<td colspan="2"><label id="adposition"></label></td>
				</tr>
				<tr>
					<th>排序：</th>
					<td colspan="2"><input type="hidden" id="adkey" value=""/> <input type="text" value="" id="adorderid" class="input_text" /><span>(排序号越大越排在前面)</span></td>
				</tr>
				<tr>
					<th>类型：</th>
					<td colspan="3"><label><input type="radio" value="0" name="adtype" />文字链接</label><label><input type="radio" value="1" name="adtype" />外部图片链接</label><label><input type="radio" value="2" name="adtype" />外部的JS代码</label><label><input type="radio" value="-1" name="adtype" />禁用</label></td>
				</tr>
				<tr class="type1">
					<th>链接地址标签：</th>
					<td colspan="2"><input type="text" value="" readonly="readonly" id="adlinklable" class="input_text" /><span style="font-size:12px;">(不可修改)</span></td>
				</tr>
				<tr>
					<th>链接名称：</th>
					<td colspan="2"><input type="text" value="" id="adlinkname" class="input_text" /></td>
				</tr>
				<tr>
					<th>链接地址：</th>
					<td colspan="2"><br/><input type="text" value="" id="adlinkurl" class="input_text" /><br/><span style="font-size:12px;">(必须以http://或https://开头)</span></td>
				</tr>
				<tr class="type1">
					<th>外部资源：</th>
					<td colspan="2"><textarea id="adsrc"    ></textarea> </td>
				</tr>
				 
				<tr class="mask_bottom">
					<th></th>
					<td colspan="2"><input type="button" value="提  交" class="btn_orange ok"   /><input type="button" value="返  回" class="btn_blue cancel" /></td>
				</tr>
			</tbody>
		</table>
		<a href="#" class="close cancel">×</a>
	</div>
	<!-- 蒙板end -->
	