<?php
/**
 * 处理城市品牌逻辑
 */
class get_HotelBrandRequest{
	private $CityID="";//
	
	/**
	 * @var 返回的XML
	 */
	var $responseXML="";//
	
	/**
	 * 
	 * @var 调用城市品牌分布接口  
	 * @param int $BrandID 品牌ID
	 */
	function __construct($CityID)
	{
		if($CityID!=null&&$CityID!="")
		{
			$this->CityID=$CityID;
		}
		//进行城市品牌的查询
		$this->getHotelBrandList();
	}
	/**
	 * 
	 * @var 调用城市品牌分布数据加载
	 */
   private 	function getHotelBrandList()
	{
		$getBrandCityRequestList=new get_D_HotelBrandList();
		$getBrandCityRequestList->CityID=$this->CityID;
		$getBrandCityRequestList->main();
		$this->responseXML=$getBrandCityRequestList->ResponseXML;
	}
	
	/**
	 * 
	 * @var 析构函数，当类不在使用的时候调用，该函数用来释放资源。
	 */
     function __destruct(){
		unset($CityID);
		unset($responseXML);
	}
}