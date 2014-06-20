<?php
/**
 * 处理最新加盟逻辑
 */
class get_ContractHotel{
	private $ContractDateStart="";//加盟周期的开始时间
	private $ContractDateEnd="";//加盟周期的结束时间
	private $CityID="";//城市ID
	private $CurPage="";//请求的页码
	private $PageCount="";//请求条数
	
	/**
	 * @var 返回的XML
	 */
	var $responseXML="";//
	
	/**
	 * 
	 * @var 调用最新加盟接口
	 * @param string $ContractDateStart 起始时间
	 * @param string $ContractDateEnd 终止时间
	 * @param int $CityID 城市ID
	 * @param int $CurPage 请求的页码
	 * @param int $PageCount 请求条数
	 */
	function __construct($CityID,$CurPage,$PageCount,$HotelNewContractTime)
	{
		if($CityID!=null&&$CityID!="")
		{
			$this->CityID=$CityID;
		}
		if($CurPage!=null&&$CurPage!="")
		{
			$this->CurPage=$CurPage;
		}
		
		if($PageCount!=null&&$PageCount!="")
		{
			$this->PageCount=$PageCount;
		}
		$ContractDateStart=getDateYMD_addDay('-','-'.$HotelNewContractTime);
		$ContractDateEnd=getDateYMD('-');
		$this->ContractDateStart=$ContractDateStart;
		$this->ContractDateEnd=$ContractDateEnd;
		
		
		
		
		
		//进行最新加盟的查询
		$this->getContractHotel();//调用最新加盟数据加载
	}
	/**
	 * 
	 * @var 调用最新加盟数据加载
	 */
   private 	function getContractHotel()
	{
		$getContractHotelList=new get_D_ContractHotel();
		$getContractHotelList->CityID=$this->CityID;
		$getContractHotelList->CurPage=$this->CurPage;
		$getContractHotelList->PageCount=$this->PageCount;
		$getContractHotelList->ContractDateStart=$this->ContractDateStart;
		$getContractHotelList->ContractDateEnd=$this->ContractDateEnd;
		$getContractHotelList->main();
		$this->responseXML=$getContractHotelList->ResponseXML;
	}
	
	/**
	 * 
	 * @var 析构函数，当类不在使用的时候调用，该函数用来释放资源。
	 */
     function __destruct(){
		unset($HotelIDs);
		unset($responseXML);
	}
}