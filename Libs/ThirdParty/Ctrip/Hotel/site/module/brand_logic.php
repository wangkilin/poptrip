<?php
/**
 * 品牌逻辑相关处理方法
 */

/**
 * 酒店城市分布
 *
 * @param $brand 品牌
 * @param $brandCNName 品牌中文名
 * @return $city['inner'],$city['out']
 */
function hotelCityDistribution($brand,$brandCNName,$response){
	$citys=$response->DomesticGetBrandCityReponse->CityDetailList->DomesticBrandCityDetail;
	if ($citys){
		//归类海外酒店、国内酒店
		foreach ($citys as $v){
			if ($v->Country==1){//国内
				$innerCitys[]=array('City'=>$v->City,'CityName'=>$v->CityName);
			}else{//
				$outCitys[]=array('City'=>$v->City,'CityName'=>$v->CityName);
			}
			$count+=$v->BrandHotelCount;
		}
		$city['inner']=$innerCitys;
		$city['out']=$outCitys;
		$city['count']=$count;
		return $city;
		/*//显示酒店城市分布
		$html='';
		foreach ($innerCitys as $v){
			$html.="<span><a href='".getNewUrl("brandDetail.php?brand=$brand&brandcnname=$brandCNName&city=".$v['City'].",".$v['CityName'],$SiteUrlRewriter)."'>".$v['CityName'].$brandCNName."酒店预定</a></span>";
		}
		$html.="<br/>";
		foreach ($outCitys as $v){
			$html.="<span><a href='".getNewUrl("brandDetail.php?brand=$brand&brandcnname=$brandCNName&city=".$v['City'].",".$v['CityName'],$SiteUrlRewriter)."'>".$v['CityName'].$brandCNName."酒店预定</a></span>";
		}
		echo $html;*/
	}
	return null;
}
/**
 * 获取指定品牌信息
 *  @param int $brand
 *  @return $brandEntity
 */
function getBrandInfo($brand){
	if (file_exists('../appData/brandDescription.xml')){
		$xml=simplexml_load_file('../appData/brandDescription.xml');
		$brandlist=$xml->BrandInfo;
		foreach ($brandlist as $v){
			if($v->Brand==$brand){
				$brandEntity=array('Brand'=>$v->Brand,'BrandName'=>$v->BrandName,'BrandCNName'=>$v->BrandCNName,'BrandLevel'=>$v->BrandLevel,'SeoFlag'=>$v->SeoFlag,'GroupName'=>$v->GroupName,'Description'=>$v->Description);
				return $brandEntity;
			}
		}
	}
	return null;
}
/**
 * 获取同类其他品牌信息
 *   @param $brand
 *   @return $similarBrands
 */
function getSimilarBrands($brand){
	$b=getBrandInfo($brand);
	if ($b){
		if (file_exists('../appData/brandDescription.xml')){
			$xml=simplexml_load_file('../appData/brandDescription.xml');
			$brandlist=$xml->BrandInfo;
			foreach ($brandlist as $v){
				if ($b['BrandLevel']==(int)$v->BrandLevel&&$brand!=$v->Brand&&(int)$v->SeoFlag==1){
					$similarBrands[]=array('Brand'=>$v->Brand,'BrandName'=>$v->BrandName,'BrandCNName'=>$v->BrandCNName);
				}
			}
		}
	}
	return $similarBrands;
}

/**
 * 获取所有品牌中的部分Brand
 * Enter description here ...
 * @param $num
 */
function getBrandList($num){
if (file_exists('../appData/brandDescription.xml')){
		$xml=simplexml_load_file('../appData/brandDescription.xml');
		$brandlist=$xml->BrandInfo;
		$i=0;
		foreach ($brandlist as $v){
			 if ($i>=$num) 
			 	break;			  
			 if ($i==0){
			 	$list[$i]=array('Brand'=>$v->Brand,'BrandCNName'=>$v->BrandCNName,'BrandName'=>$v->BrandName,'MgrGroup'=>$v->MgrGroup,'GroupName'=>$v->GroupName);			 	
				 $i++;
			 }else{
			 	if ($ist[$i-1]['MgrGroup']!=(int)$v->MgrGroup){
			 		$list[$i]=array('Brand'=>$v->Brand,'BrandCNName'=>$v->BrandCNName,'BrandName'=>$v->BrandName,'MgrGroup'=>$v->MgrGroup,'GroupName'=>$v->GroupName);			 	
			 		 $i++;
			 	}
			 }		 
		}
		return $list;
	}
	return null;
}

?>