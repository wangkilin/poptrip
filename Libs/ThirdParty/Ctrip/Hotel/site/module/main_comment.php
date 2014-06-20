<?php
/**
 * 处理酒店所有评论逻辑
 */
class page_hotelComment{
 private $hotelID=0;//酒店的ID
 private $pageSize=10;//每页多少数据
 private $pageNo=1;//当前第几页
 private $responseXML;//返回的XML
 /**
  * @var 符合条件的结果总数
  */
 var $recordCount=0;
 /**
  * @var 总的页数
  */
 var $totalPageCount=0;
 /**
  *
  * @var 调用酒店带分页功能的酒店点评接口
  * @param int  $hotelID 酒店ID
  * @param int $pageSize 每页的大小
  * @param int $pageNo 当前第几页
  * @param int $cssType 加载数据的样式类
  */
 function __construct($hotelID,$pageSize,$pageNo,$cssType)
 {
  if($hotelID!=null&&$hotelID!="")
  {
   //进行酒店点评数据的查询
   $this->hotelID=$hotelID;
   $this->pageSize=$pageSize;
   $this->pageNo=$pageNo;
   $this->getCommentList();//调用评论数据加载
   if($cssType==1){
    $this->pageList_1();//加载样式一，输出
   }
  }
 }
 /**
  *
  * @var 调用评论数据加载
  */
 private  function getCommentList()
 {
  $getHotelCommentList=new get_D_HotelCommentWithPage();
  $getHotelCommentList->HotelID=$this->hotelID;
  $getHotelCommentList->PageNo=$this->pageNo;
  $getHotelCommentList->PageSize=$this->pageSize;
  $getHotelCommentList->main();
   //print_r($getHotelCommentList->ResponseXML);
  $this->responseXML=$getHotelCommentList->ResponseXML;
 }
 /**
  * @var 加载第一种样式的酒店点评列表
  */
 private function pageList_1()
 {
  $returnCommentXML=$this->responseXML;
  if($returnCommentXML!=null)
  {
   $this->recordCount=$returnCommentXML->DomesticHotelCommentPageList->RecordCount;//总的条数
   $pageCount=$this->recordCount/($this->pageSize==0?1:$this->pageSize);
   if($this->recordCount%$this->pageSize>0){$pageCount=$pageCount+1;}
   //$pageCount 取整
   $this->totalPageCount=(int)$pageCount;//总的页数
   $HotelCommentItems=$returnCommentXML->DomesticHotelCommentPageList->HotelCommentItems;
   $dlOne="";
   $i=0;
   $dlClass="";//样式
   if ($HotelCommentItems!=null){
   foreach($HotelCommentItems->DomesticHotelCommentPage as $commentData)
   {
    if($i==0){
     $dlClass="class=\"border_none\"";
    }else{
     $dlClass="";
    }
    $newUID=$commentData->UID;
    $newUIDShow=utf_substr($newUID,4)."****";
    $writingDate=explode("T",$commentData->WritingDate);//$commentData->WritingDate
    $widthValue=100*($commentData->Rating)/5;//设施设备评分
    
    $dlOne.=<<<BEGIN
<dl $dlClass><dt>$newUIDShow &nbsp;&nbsp;总评：<span class="progress_bar"><div style="width:$widthValue%;"></div></span>&nbsp;$commentData->Rating /5.0</dt>
<dd><a href="javascript:;" title="$commentData->Content"><span>$commentData->Content</span></a>$writingDate[0]</dd>
</dl>
BEGIN;
    $i+=1;
    }
   }
   echo "<div class=\"content\">".$dlOne."</div>";
  }
 }
 /**
  *
  * @var 析构函数，当类不在使用的时候调用，该函数用来释放资源。
  */
 function __destruct(){
  unset($hotelID);
  unset($pageSize);
  unset($pageNo);
  unset($responseXML);
  unset($recordCount);
  unset($totalPageCount);
 }
}
