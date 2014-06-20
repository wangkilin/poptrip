<div class="side">
<div class="box_blue">
<h4>其他同类酒店品牌</h4>
<ul class="side_list">
	<?php 
$temp=array_slice($similarBrands, 0,10);
foreach ($temp as $v){
	?>
	<li><a
		href="<?php echo getNewUrl($UnionSite_domainName.'/site/brandinfo.php?brand='.$v['Brand'].'&brandcnname='.$v['BrandCNName'],$SiteUrlRewriter);?>"
		title="<?php echo $v['BrandCNName'];?>"><?php echo $v['BrandName']?></a></li>
		<?php
}
?>
</ul>
</div>
</div>