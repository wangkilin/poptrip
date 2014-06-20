<?php
/**
 * 定义首页品牌酒店
 */

	include ('brand_logic.php');
	$brands=getBrandList(6);
?>
	<div class="box_orange">
				<h3>品牌酒店</h3>
				<a href="<?php echo getNewUrl($UnionSite_domainName."/site/brand.php?defaultcityid=".$SiteDefaultCityID.",".$SiteDefaultCityName,$SiteUrlRewriter)?>" class="more">更多</a>
				<ul class="brand_hotel basefix">
				<?php 
					if ($brands){
						foreach ($brands as $v){
							?>
						<li><a href="<?php echo getNewUrl($UnionSite_domainName."/site/brandinfo.php?brand=".$v['Brand']."&brandcnname=".$v['BrandCNName'],$SiteUrlRewriter);?>" title="<?php echo $v['BrandCNName'];?>"><img width="70" height="50" src="<?php echo 'http://pic.ctrip.com/hotels110127/brandimage/'.$v['Brand'].'a.jpg';?>" alt="<?php echo $v['BrandCNName'];?>" /><br /><?php echo $v['BrandName'];?></a></li>	
							
							<?php 
						}
					}
				?>					 
				</ul>
			</div>