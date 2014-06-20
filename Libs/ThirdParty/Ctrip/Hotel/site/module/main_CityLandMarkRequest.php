<?php
/**
 * 处理酒店热卖逻辑
 */
class get_CityLandMarkRequest{
	private $City="";//城市ID
	private $Type="";//1、行政区；2、商业区；3、景点。（可以组合使用，使用“,”分隔）
	private $SearchLandmarkType="";//根据该节点可以查询很多其他数据
	
	/**
	 * @var 返回的XML
	 */
	var $responseXML="";//
	
	/**
	 * 
	 * @var 调用酒店热卖接口
	 * @param int $City 城市ID
	 * @param string $Type 
	 * @param string $SearchLandmarkType 
	 */
	function __construct($City,$Type,$SearchLandmarkType)
	{
		if($City!=null&&$City!="")
		{
			$this->City=$City;
		}
		if($Type!=null&&$Type!="")
		{
			$this->Type=$Type;
		}
		if($SearchLandmarkType!=null&&$SearchLandmarkType!="")
		{
			$this->SearchLandmarkType=$SearchLandmarkType;
		}
		//进行查询
		$this->getCityLandMarkList();
	}
	/**
	 * 
	 * @var 调用数据加载
	 */
   private 	function getCityLandMarkList()
	{
		$CityLandMarkList=new get_SearchLocationZoneCityLandmark();
		$CityLandMarkList->City=$this->City;
		$CityLandMarkList->Type=$this->Type;
		$CityLandMarkList->SearchLandmarkType=$this->SearchLandmarkType;
		$CityLandMarkList->main();
		$this->responseXML=$CityLandMarkList->ResponseXML;
	}
	
	/**
	 * 
	 * @var 析构函数，当类不在使用的时候调用，该函数用来释放资源。
	 */
     function __destruct(){
		unset($City);
		unset($Type);
		unset($SearchLandmarkType);
		unset($responseXML);
	}
}