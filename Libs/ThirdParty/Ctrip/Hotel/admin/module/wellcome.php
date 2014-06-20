<?php
ob_start();
//验证是否登录
if (!$session->contain('admin')){
	exit("访问受限.......");
}

$aid=$_GET['aid'];

 
?>
 
<div class="welcome"><?php echo $session->get("admin");?>  你好，欢迎来到<?php echo $UnionSite_Name;?>后台管理界面。</div>
<?php 	if (empty($aid)){?>
<div class="notice_box">
	<h3>系统更新公告</h3>
	<?php 
	include ("update.php");
	
	?>
	
</div>

<?php } ?>


<div class="notice_box">
	<h3>联盟公告</h3>
	<?php 
		
		if (empty($aid)){
		?><!-- 显示公告列表 -->
			<ul class="notice_content">
			<?php 
				$pageSize=10;
				$pageIndex=empty($_GET['page'])?1:$_GET['page'];
				$al=Announcement::getAnnouncementList($pageIndex, $pageSize);
				$totalItems=$al['num'];	
				 			
				if ($totalItems>0){	
					//echo	print_r($al['list']);
					foreach ($al['list'] as $v){		
			?>
				<li>
				<p><a   href="?aid=<?php echo $v['aid'];?>" title="<?php echo $v['title'];?>"><?php echo $v['title'];?></a></p>
				<?php echo $v['createtime'];?></li>		 
			
			<?php 
						}
				}
				?>	
		</ul><?php
	if ($totalItems>$pageSize){
		$pager=new SubPages($pageSize, $totalItems, $pageIndex, 7, 'manage.php?m=notice', 4);
	}
	?>		 
			
		<?php 	
		}else{//显示公告详情
			$notice=Announcement::getAnnouncementDetail($aid);
			?>
			<div class="notice_cnt">
			<h4 class="notice_title"><?php echo $notice['title'];?></h4>
			<p><?php echo html_entity_decode($notice['content']);?></p>
			<div class="back"><a href="#" onclick="javascript:history.back();">返回</a></div>
			</div>			 
			<?php 
		}
	?>
	
	
	
</div>

<?php ob_end_flush();?>
 
