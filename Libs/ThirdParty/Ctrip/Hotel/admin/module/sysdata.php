<?php
//验证是否登录
if (!$session->contain('admin')){
	exit("访问受限.......");
}

?>
<link rel="stylesheet" href="styles/datePicker.css" />
<table cellspacing="0" cellpadding="0" class="mod_table">
	<thead>
		<tr>
			<th style="width: 48px;"></th>
			<th style="width: 70px;">表名</th>
			<th style="width:60px;">记录数</th>
		</tr>
	</thead>
	<form  method="post">
	<tbody>
	<?php
	if (!empty($tables)){
		$col=0;
		foreach ($tables as $value){
			foreach ($value as $v){
	
			?>

		<tr >
			<td> <input type="checkbox" name="checkbox[]" 
				value="<?php echo $v;?>" checked /></td>
			<td><?php echo $v?></td>
			<td><?php echo $db->getOne("select count(*) from $v");?></td>
			
		</tr>
		<?php
			}
		}
	}
	?>

	</tbody>
</table>
<br>
<div>数据库链接密码：<input type='password' style="height: 25px; width:90px" name='pwd'>(请输入您安装数据库时候的密码)<br>
</div>
<div class="btn_box"><input type="submit" value="备    份" class="btn_blue"
	 /></div>


 
