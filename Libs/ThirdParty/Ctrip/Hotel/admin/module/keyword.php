<?php 
//验证是否登录
if (!$session->contain('admin')){
	exit("访问受限.......");
}
 
?>
<table cellspacing="0" cellpadding="0" class="mod_table">
				<thead>
					<tr>
						<th style="width:30px;">编号</th>
						<th style=" ">PageName页面名</th>
						<th style="width:180px;">Page索引名称</th>
						<!--<th style="width:80px;">Title<br/>页面标题</th>
						<th style="width:80px;">Keywords<br/>页面关键字</th>
						<th  >Description<br/>页面描述</th>
						<th style="width:80px;">Rule<br/>规则说明</th>
						--><th style="width:100px;">Times更新时间</th>						
						<th style="width:50px;">操作</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					$pagesize=10;
					$pagenums;
					$currentPage=empty($_GET['page'])?1:$_GET['page'];
					$totalItems;
				
					$kw=new keyword();
					$keywords=$kw->loadAll(); 
					
					$totalItems=count($keywords);
				 
					$pageNum=ceil($totalItems/$pagesize);
					if ($currentPage>$pageNum)
						$currentPage=$pageNum;
					$offset=($currentPage-1)*$pagesize;
					$tempArray=array_slice($keywords, $offset,$pagesize,true);
					
					$count=0;
					foreach ($tempArray as $v){
						$count++;
						?>
					<tr class="<?php echo $count%2==0?'double':'';?>">	
						<td style="text-align:center;"><?php echo $count;?></td>				 
						<td><?php echo $v['PageName'];?></td>
						<td><?php echo $v['Page'];?></td>
						<!--<td><?php echo $v['Title'];?></td>
						<td><?php echo $v['Keywords'];?></td>						 
						<td><?php echo $v['Description'];?></td>					
						<td><?php echo $v['Rule'];?></td>
						--><td><?php echo $v['Times'];?></td>
						<td><a href="#" onclick="editKeyword(<?php echo $v['ID'];?>)">管理</a></td>
					</tr>	
						
						<?php						
					}
				?>		 
				</tbody>
			</table>
			<?php 
			if ($totalItems>$pagesize){
				$pager=new SubPages($pagesize, $totalItems, $currentPage, 7, 'manage.php?m=keyword', 4);
			}
			?>
			
			<!-- 蒙板begin -->
	<div class="mask" style="margin:20px 0 30px 50px; display:none;width:570px;">
		<h3>管理信息</h3>
		<table cellspacing="0" cellpadding="0">
			<tbody>
				<tr class="mask_top">
					<th>PageName：<br/>页面名  <input type="hidden" value="" id="keywordID"/>  </th>
					<td colspan=""><input type="text" value="" id="pagename" class="input_text" /></td>
					<td>(必须小于100个字符)</td>
				</tr>
				<tr>
					<th>Page：<br/>索引名称</th>
					<td colspan=""><input type="text" value="" id="page" class="input_text" /></td>
					<td>(必须小于200个字符)</td>
				</tr>
				<tr>
					<th style="vertical-align:top;">Title：<br/>页面标题</th>
					<td colspan=""><textarea id="title"></textarea></td>
					<td>(必须小于200个字符)</td>
				</tr>
				<tr>
					<th style="vertical-align:top;">Keywords：<br/>页面关键字</th>
					<td colspan=""><textarea id="keywords"></textarea></td>
					<td>(必须小于200个字符)</td>
				</tr>
				<tr>
					<th style="vertical-align:top;">Description ：<br/>页面描述</th>
					<td colspan=""><textarea id="description"></textarea> </td>
					<td>(必须小于500个字符)</td>
				</tr>
				<tr>
					<th style="vertical-align:top;">Rule ：<br/>规则说明</th>
					<td colspan=""><textarea id="rule"></textarea> </td>
					<td>(必须小于500个字符)</td>
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