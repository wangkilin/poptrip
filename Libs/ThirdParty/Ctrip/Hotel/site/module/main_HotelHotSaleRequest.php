<?php
/**
 * 处理酒店热卖逻辑
 */
class get_HotSaleHotelRequest{
	private $City="";//城市ID
	private $SumType="D";//热卖类型-必须填写（D、今日热卖；W、上周热卖）
	private $ProcessDate="";//热卖数据-（当SumType=“D”的时候  processDate才起作用 ）
	private $SearchNumber="";//请求条数
	
	/**
	 * @var 返回的XML
	 */
	var $responseXML="";//
	
	/**
	 * 
	 * @var 调用酒店热卖接口
	 * @param int $City 城市ID
	 * @param string $SumType 
	 * @param string $ProcessDate 
	 * @param int $SearchNumber 请求条数
	 */
	function __construct($City,$SumType,$SearchNumber)
	{
		if($City!=null&&$City!="")
		{
			$this->City=$City;
		}
		if($SumType!=null&&$SumType!="")
		{
			$this->SumType=$SumType;
			if($SumType=='D')
			$this->ProcessDate=getDateYMD_addDay('-','-1');
			
		}
		if($SearchNumber!=null&&$SearchNumber!="")
		{
			$this->SearchNumber=$SearchNumber;
		}
		
		//进行酒店热卖的查询
		$this->getHotSaleHotelList();//调用酒店热卖数据加载
	}
	/**
	 * 
	 * @var 调用酒店热卖数据加载
	 */
   private 	function getHotSaleHotelList()
	{
		$HotSaleHotelList=new get_D_HotelHotSale();
		$HotSaleHotelList->City=$this->City;
		$HotSaleHotelList->SumType=$this->SumType;
		$HotSaleHotelList->ProcessDate=$this->ProcessDate;
		$HotSaleHotelList->SearchNumber=$this->SearchNumber;
		$HotSaleHotelList->main();
		$this->responseXML=$HotSaleHotelList->ResponseXML;
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