<?php
/**
 * 处理品牌的城市分布逻辑
 */
class get_BrandCityRequest{
	private $BrandID="";//品牌的ID
	
	/**
	 * @var 返回的XML
	 */
	var $responseXML="";//
	
	/**
	 * 
	 * @var 调用品牌的城市分布接口  
	 * @param int $BrandID 品牌ID
	 */
	function __construct($BrandID)
	{
		if($BrandID!=null&&$BrandID!="")
		{
			$this->BrandID=$BrandID;
			//进行品牌的城市的查询
			$this->getBrandCityRequest();
			
		}
	}
	/**
	 * 
	 * @var 调用品牌的城市分布数据加载
	 */
   private 	function getBrandCityRequest()
	{
		$getBrandCityRequestList=new get_D_GetBrandCityRequest();
		$getBrandCityRequestList->BrandID=$this->BrandID;
		$getBrandCityRequestList->main();
		$this->responseXML=$getBrandCityRequestList->ResponseXML;
	}
	
	/**
	 * 
	 * @var 析构函数，当类不在使用的时候调用，该函数用来释放资源。
	 */
     function __destruct(){
		unset($BrandID);
		unset($responseXML);
	}
}