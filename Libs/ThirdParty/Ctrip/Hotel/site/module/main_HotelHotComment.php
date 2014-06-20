<?php
/**
 * 处理最新热门酒店点评逻辑
 */
class get_HotelHotComment{
	private $HotelIDs="";//酒店的ID
	
	/**
	 * @var 返回的XML
	 */
	var $responseXML="";//
	
	/**
	 * 
	 * @var 调用最新热门酒店点评接口
	 * @param string $HotelIDs 酒店ID
	 */
	function __construct($HotelIDs)
	{
		if($HotelIDs!=null&&$HotelIDs!="")
		{
		if(strpos($HotelIDs,",")>=0){
			$HotelArr=explode(",",$HotelIDs);
			foreach($HotelArr as $val){
				$Hotelss=<<<BEGIN
<HotelID>$val</HotelID>
BEGIN;
			$Hotel=$Hotel.$Hotelss;
			}
			
		}
			$this->HotelIDs=$Hotel;
			//进行酒店点评关键字的查询
			$this->getHotelHotComment();//调用周边数据加载
			
		}
	}
	/**
	 * 
	 * @var 调用最新热门酒店点评数据加载
	 */
   private 	function getHotelHotComment()
	{
		$getHotelHotCommentList=new get_D_HotelHotComment();
		$getHotelHotCommentList->HotelIDs=$this->HotelIDs;
		$getHotelHotCommentList->main();
		$this->responseXML=$getHotelHotCommentList->ResponseXML;
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