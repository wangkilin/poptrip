<?php
/**
 * 处理酒店点评关键字逻辑
 */
class get_HotelCommentKey{
	private $HotelIDs="";//酒店的ID
	
	/**
	 * @var 返回的XML
	 */
	var $responseXML="";//
	
	/**
	 * 
	 * @var 调用酒店点评关键字接口  
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
			$this->getCommentKey();//调用周边数据加载
			
		}
	}
	/**
	 * 
	 * @var 调用酒店点评关键字数据加载
	 */
   private 	function getCommentKey()
	{
		$getHotelCommentKeyList=new get_D_HotelCommentKey();
		$getHotelCommentKeyList->HotelIDs=$this->HotelIDs;
		$getHotelCommentKeyList->main();
		$this->responseXML=$getHotelCommentKeyList->ResponseXML;
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