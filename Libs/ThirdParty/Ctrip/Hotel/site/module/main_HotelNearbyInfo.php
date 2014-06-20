<?php
/**
 * 处理酒店周边信息逻辑
 */
class get_HotelNearbyInfo{
	private $Hotel=0;//酒店的ID
	private $Distance=5;//周边距离
	private $HotelNums=1;//获取周边酒店数量
	private $IsHotPlace=F;//周边设施
	
	/**
	 * @var 返回的XML
	 */
	var $responseXML="";//
	
	/**
	 * 
	 * @var 调用酒店周边信息接口
	 * @param int $Hotel 酒店ID
	 * @param string $Distance 周边距离
	 * @param int $HotelNums 获取周边酒店数量
	 * @param string $IsHotPlace 周边设施
	 */
	function __construct($Hotel,$Distance,$HotelNums,$IsHotPlace)
	{
		if($Hotel!=null&&$Hotel!="")
		{
			//进行酒店周边信息数据的查询
			$this->Hotel=$Hotel;
			$this->Distance=$Distance;
			$this->HotelNums=$HotelNums;
			$this->IsHotPlace=$IsHotPlace;
			$this->getNearbyInfoList();//调用周边数据加载
			
		}
	}
	/**
	 * 
	 * @var 调用周边数据加载
	 */
   private 	function getNearbyInfoList()
	{
		$getHotelNearbyInfoList=new get_D_HotelNearbyInfo();
		$getHotelNearbyInfoList->Hotel=$this->Hotel;
		$getHotelNearbyInfoList->Distance=$this->Distance;
		$getHotelNearbyInfoList->HotelNums=$this->HotelNums;
		$getHotelNearbyInfoList->IsHotPlace=$this->IsHotPlace;
		$getHotelNearbyInfoList->main();
		$this->responseXML=$getHotelNearbyInfoList->ResponseXML;
	}
	
	/**
	 * 
	 * @var 析构函数，当类不在使用的时候调用，该函数用来释放资源。
	 */
     function __destruct(){
		unset($Hotel);
		unset($Distance);
		unset($HotelNums);
		unset($responseXML);
		unset($IsHotPlace);
	}
}