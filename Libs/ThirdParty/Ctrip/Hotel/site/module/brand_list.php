<?php
//品牌的城市分布
include_once ('../SDK.config.php');//配置文件加载--必须加载这个文件
include_once (ABSPATH.'sdk/API/Hotel/D_HotelBrandList.php');//加载D_HotelBrandList这个接口的封装类
include_once (ABSPATH.'site/module/main_HotelBrandList.php');//加载城市品牌逻辑
//调用品牌的城市分布接口
$HotelBrandList=new get_HotelBrandRequest($SiteDefaultCityID);
$xml=$HotelBrandList->responseXML;

//需要过滤的品牌酒店
$filtBrands=array('21','22','23','24','35','42','118','119','120','121','122','123','132');

$brandImageUrl="http://pic.ctrip.com/hotels110127/brandimage/";
$hbes=$xml->GetHotelBrandResponse->HotelBrandEntityList->HotelBrandEntity;
if (!empty($hbes)){
	$groupIds[]=0;
	$brandList;
	foreach ($hbes as $v){		
		$groupId=(int)$v->MgrGroup ;
		//echo $groupId."<br/><br/>";	
		$BrandId=(string)$v->Brand;
		if(in_array($BrandId,$filtBrands)){
			 continue;
		}
		
		if (in_array($groupId, $groupIds)){			 
			$brandList[$groupId]['Brands'][]=array('Brand'=>$BrandId,'BrandName'=>(string)$v->BrandName,'BrandCNName'=>(string)$v->BrandCNName,'Pinyin'=>(string)$v->Pinyin,'Image'=>$brandImageUrl.(string)$v->Brand.'a.jpg');			
		}else{
			$groupIds[]=$groupId;
			 
			$brandList[$groupId]=array('GroupName'=>(string)$v->GroupName);
			$brandList[$groupId]['Brands'][]=array('Brand'=>$BrandId,'BrandName'=>(string)$v->BrandName,'BrandCNName'=>(string)$v->BrandCNName,'Pinyin'=>(string)$v->Pinyin,'Image'=>$brandImageUrl.(string)$v->Brand.'a.jpg');		
		}
	} 
	
	foreach ($brandList as $v){		
		
	?>
	<div class="brand_list">
			<h3><?php echo $v['GroupName'];?></h3>
			<ul class="basefix">
			<?php
			$first=true;
			 foreach ($v['Brands'] as $brand){
			 	$brandUrlInfo=getNewUrl($UnionSite_domainName."/site/brandinfo.php?brand=".$brand['Brand']."&brandcnname=".$brand['BrandCNName'],$SiteUrlRewriter);//到品牌的详细页面，品牌的分布
			?>
				<li class="<?php echo $first?'margin_0':'';?>">
					<a style="background: url('http://pic.c-ctrip.com/hotels110127/brand_logo.jpg') no-repeat scroll 0 1px transparent;display: block;" href="<?php echo $brandUrlInfo; ?>" title="<?php echo $brand['BrandCNName'];?>" class="brand_pic"><img width="80" height="80"src="http://pic.c-ctrip.com/common/pic_alpha.gif" style="background:url('<?php  echo $brand['Image']; ?>') no-repeat;" alt="<?php echo $brand['BrandCNName'];?>" /></a>
					<a href="<?php echo $brandUrlInfo; ?>" title="<?php echo $brand['BrandCNName'];?>"><?php echo $brand['BrandCNName'];?></a>
				</li>
			<?php 
				$first=false;
			}
			?>
			</ul>
		</div>
	
	<?php 
	}
}
?>
 