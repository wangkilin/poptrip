<?php
//验证是否登录
if (!$session->contain('admin')){
	exit("访问受限.......");
}
?>
<link rel="stylesheet" href="styles/datePicker.css" />

<script language="javascript" type="text/javascript">
	function checkdel(file,op){
		var _pwd = $('#pwd').val();
		if( _pwd =="")
		{
		   alert("请填写数据库链接密码！");   
		   $('#pwd').focus();
		   return(false);
		}
		if(op=='revert'){
			if(confirm('还原之前请确保您的数据库已提前备份！否则可能导致数据丢失！')!=true){
				return(false);
	
			}	
		}
		$.post("ajaxmanage.php",
				{m:op,file:file,dbpwd:_pwd},
				function(data){
					if(data=='delok'){
						alert(file+"数据库删除成功");history.go(0);
					}else{
						alert(data);
					}	
		});	
		
	}	
</script>

<?php if (!empty($filelists)){?>
<table cellspacing="0" cellpadding="0" class="mod_table">
	<thead>
		<tr>
			<th style="width: 48px;"></th>
			<th style="width: 70px;">数据库备份列表</th>
			<th style="width:60px;">操作</th>
		</tr>
	</thead>
	<form  method="post">
	<tbody>
	<?php
		rsort($filelists);
		foreach ($filelists as $v){
	?>
		<tr >
			<td> <input type="radio"  checked name="dbbackup" value="<?php echo $v;?>"  /></td>
			<td><?php echo $v?></td>
			<td>
			<a href="javascript:void(0)" onclick="checkdel('<?php echo urlencode($v);?>','revert')">还原</a>
			<a href="javascript:void(0)" onclick="checkdel('<?php echo urlencode($v);?>','del')">删除</a>
			</td>
			
		</tr>
		<?php
		}
	?>
	<tr >
			<td>数据库链接密码</td>
			<td><input type='password' id='pwd' style="height: 25px; width:90px" name='pwd'>(请输入您安装数据时候的密码)</td>
			<td></td>
		</tr>
	</tbody>
</table>
<div class="btn_box"><input type="submit" value="下     载" class="btn_blue"/></div>
	 <?php 	}else{
	echo '没有可以还原的版本，请先备份';
	}?>


 
