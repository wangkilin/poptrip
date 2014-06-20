<?php
/**
 * 处理最新开业逻辑
 */
class get_NewOpenHotel{
	//private $OpenYearDateStart="";//起始时间
	//private $OpenYearDateEnd="";//终止时间
	private $CityID="";//城市ID
	
	/**
	 * @var 返回的XML
	 */
	var $responseXML="";//
	
	/**
	 * 
	 * @var 调用最新开业接口
	 * @param string $OpenYearDateStart 起始时间
	 * @param string $OpenYearDateEnd 终止时间
	 * @param int $CityID 城市ID
	 */
	function __construct($HotelNewOpenTime,$CityID)
	{
		$this->OpenYearDateStart=getDateYMD_addDay('-','-'.$HotelNewOpenTime);
		$this->OpenYearDateEnd=getDateYMD('-');
		
		if($CityID!=null&&$CityID!="")
		{
			$this->CityID=$CityID;
		}
		
		
		
		//进行最新开业的查询
		$this->getNewOpenHotel();//调用最新开业数据加载
	}
	/**
	 * 
	 * @var 调用最新开业数据加载
	 */
   private 	function getNewOpenHotel()
	{
		$getNewOpenHotelList=new get_D_SearchNewOpenHotel();
		$getNewOpenHotelList->OpenYearDateStart=$this->OpenYearDateStart;
		$getNewOpenHotelList->OpenYearDateEnd=$this->OpenYearDateEnd;
		$getNewOpenHotelList->CityID=$this->CityID;
		$getNewOpenHotelList->main();
		$this->responseXML=$getNewOpenHotelList->ResponseXML;
	}
	
	/**
	 * 
	 * @var 析构函数，当类不在使用的时候调用，该函数用来释放资源。
	 */
     function __destruct(){
		unset($OpenYearDateStart);
		unset($OpenYearDateEnd);
		unset($CityID);
		unset($responseXML);
	}
}