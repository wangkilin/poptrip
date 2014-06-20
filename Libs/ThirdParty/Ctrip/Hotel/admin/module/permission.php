<?php 

//验证是否登录
if (!$session->contain('admin')){
	exit("访问受限.......");
}
 
?> 
 
<table cellspacing="0" cellpadding="0" class="mod_table">
				<thead>
					<tr>
						<th style="width:50px;"></th>
						<th style="width:300px;">用户名</th>
						 
						<th style="width:80px;">操作</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php 
				$pagesize=10;
				$pagenums;
				$currentPage=empty($_GET['page'])?1:$_GET['page'];
				$totalItems;
					
				$p=new permission();
				$users=$p->getAllUser();
				if (isset($users)){
					$totalItems=count($users);
				 
					$pageNum=ceil($totalItems/$pagesize);
					if ($currentPage>$pageNum)
						$currentPage=$pageNum;
					$offset=($currentPage-1)*$pagesize;
					$tempArray=array_slice($users, $offset,$pagesize,true);
					$count=0;
					foreach ($tempArray as $v){
						$count++;
						?>
					<tr class="<?php echo $count%2==0?'double':'';?>">
						<td style="width:50px;"><input type="checkbox" name="checkbox" value="<?php echo $v['ID'];?>" /></td>
						<td><?php echo $v['UserName'];?></td>				 
						<td><a href="#" onclick="edituser('<?php echo $v['UserName'];?>')" >修改</a> <a href="#" onclick="deleteuser('<?php echo $v['ID'];?>')">删除</a></td>
					<td></td>
					</tr>
						<?php 
					}
				}
				?>
					 
					<tr class="mod_table_bottom">
						<td style="width:50px;"><label onclick="select()"><input type="checkbox" name=""  id="checkAll"/>全选</label></td>
						<td><input type="button" onclick="batchDeleteUser()" value="删除所选" class="btn_delete" /></td>
						<td ></td>
						<td></td>
					</tr>
				</tbody>
			</table>			
			<div class="btn_box">
				<input type="button" value="新  增" onclick="adduser()" class="btn_blue" />
			</div>
			<?php 
			if ($totalItems>$pagesize){
				$pager=new SubPages($pagesize, $totalItems, $currentPage, 7, 'manage.php?m=permission', 4);
			}
			?>
			<!-- 蒙板begin -->
	<div class="mask permission_mask" style="display:none;width:550px;">
		<h3>修改密码</h3>
		<table cellspacing="0" cellpadding="0">
			<tbody>
				<tr class="mask_top">
					<th>用户名：</th>
					<td><input type="text"   id="username" class="input_text" /></td>
					 <td>用户名长度不能超过20个字符</td>
				</tr>
				 
				<tr>
					<th class="password">新密码：</th>
					<td><input type="text" value="" id="password" class="input_text" /></td>
					 <td>密码长度不能超过20个字符</td>
				</tr>
				 
				<tr class="mask_bottom">
					<th></th>
					<td ><input type="button" value="提  交" class="btn_orange ok" /><input type="button" value="返  回" class="btn_blue cancel" /></td>
				<td></td>
				</tr>
			</tbody>
		</table>
		<a href="#" class="close cancel">×</a>
	</div>
	
	
<div class="mask mask_delete" style="display:none;">
		<h3></h3>
		<p class="delete">你确认要删除选中的管理员吗？</p>
		<div class="btn_box">
			<input type="button" value="确  认" class="btn_orange ok" /><input type="button" value="取  消" class="btn_blue cancel" />
		</div>
		<a href="#" class="close cancel">×</a>
	</div>
	 
	