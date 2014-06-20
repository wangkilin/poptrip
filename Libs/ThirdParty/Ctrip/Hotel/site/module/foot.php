<?php
/**
 * 处理站点底部的逻辑
 */
 require '../appData/db_sitepolicy.php'; 
?>
<div class="ft">
<div class="policewrap">
<?php 
	if ($sitePolicyArray){
		foreach ($sitePolicyArray as $v){
			?>
			<a title="<?php echo htmlDecode($v[1]);?>" target="blank" href="<?php echo htmlDecode($v[2]);?>" ><img src="<?php echo htmlDecode($v[3]);?>"/></a>
			<?php 
		}
	}
?>
</div>
<p><?php echo  htmlDecode($UnionCopyRight);?></p>
<p><?php echo htmlDecode($UnionICP);?></p>


<input type="hidden" id="siteUrl" value="<?php echo $baseUlr?>" />

</div>
<div class="hotel_search">
<h2>酒店搜索<a href="<?php echo getNewUrl($UnionSite_domainName."/site/brand.php?defaultcityid=".$SiteDefaultCityID.",".$SiteDefaultCityName,$SiteUrlRewriter);?>">查看酒店品牌</a></h2>
<ul class="hotel_list basefix">
<?php require '../appData/db_hotbookingcity.php';?>
</ul>
</div>
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cdiv id='cnzz_stat_icon_30075850'%3E%3C/div%3E%3Cscript src='" + cnzz_protocol + "w.cnzz.com/c.php%3Fid%3D30075850' type='text/javascript'%3E%3C/script%3E"));</script>
