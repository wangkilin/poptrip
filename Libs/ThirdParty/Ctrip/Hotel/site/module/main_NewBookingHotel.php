<?php
/**
 * 处理最新预定逻辑
 */
class get_NewBookingHotel{
	private $CityID="";//城市ID
	private $LastHour="48";//时间
	private $CurPage="";//请求的页码
	private $PageCount="";//请求条数
	
	/**
	 * @var 返回的XML
	 */
	var $responseXML="";//
	
	/**
	 * 
	 * @var 调用最新预定接口
	 * @param int $CityID 城市ID
	 * @param string $LastHour 时间
	 * @param int $CurPage 请求的页码
	 * @param int $PageCount 请求条数
	 */
	function __construct($CityID,$LastHour,$CurPage,$PageCount)
	{
		if($CityID!=null&&$CityID!="")
		{
			$this->CityID=$CityID;
		}
		if($LastHour!=null&&$LastHour!="")
		{
			$this->LastHour=$LastHour;
		}
		if($CurPage!=null&&$CurPage!="")
		{
			$this->CurPage=$CurPage;
		}
		if($PageCount!=null&&$PageCount!="")
		{
			$this->PageCount=$PageCount;
		}
		
		
		
		//进行最新预定的查询
		$this->getNewBookingHotel();//调用最新预定数据加载
	}
	/**
	 * 
	 * @var 调用最新预定数据加载
	 */
   private 	function getNewBookingHotel()
	{
		$getNewOpenHotelList=new get_D_NewBookingHotel();
		$getNewOpenHotelList->CityID=$this->CityID;
		$getNewOpenHotelList->LastHour=$this->LastHour;
		$getNewOpenHotelList->CurPage=$this->CurPage;
		$getNewOpenHotelList->PageCount=$this->PageCount;
		$getNewOpenHotelList->main();
		$this->responseXML=$getNewOpenHotelList->ResponseXML;
	}
	
	/**
	 * 
	 * @var 析构函数，当类不在使用的时候调用，该函数用来释放资源。
	 */
     function __destruct(){
		unset($CityID);
		unset($LastHour);
		unset($CurPage);
		unset($PageCount);
		unset($responseXML);
	}
}