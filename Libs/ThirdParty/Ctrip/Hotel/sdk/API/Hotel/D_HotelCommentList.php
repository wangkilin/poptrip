<?php
/**
 *
 * 获取指定酒店的携程评论
 * @author cltang
 *
 */
class get_D_HotelComment{
	/**
	 * 酒店的ID，必须填写
	 */
	var $HotelID="";
	/**
	 * 年限[默认为1年]
	 */
	var $EffectYear="1";
	/**
	 * 年限[默认为1年]本接口只提供酒店的评价结果查询
	 */
	var $RequestType="H";
	/**
	 *返回体
	 */
	var $ResponseXML="";
	/**
	 * 构造请求体
	 */
	private  function getRequestXML()
	{
		/*
		 * 从config.php中获取系统的联盟信息(只读)
		 */
		$AllianceID=Allianceid;
		$SID=Sid;
		$KEYS=SiteKey;
		$RequestType="D_HotelCommentList";
		//构造权限头部
		$headerRight=getRightString($AllianceID,$SID,$KEYS,$RequestType);
		$hotelIDs="";
		if($this->HotelID!=""){
			$hotelIDs=<<<BEGIN
<HotelID>$this->HotelID</HotelID>
BEGIN;
		}
		$effectYears="";
		if($this->EffectYear!=""){
			$effectYears=<<<BEGIN
<EffectYear>$this->EffectYear</EffectYear>
BEGIN;
		}
		$RequestType2="";
		if($this->RequestType!=""){
			$RequestType2=<<<BEGIN
<RequestType>$this->RequestType</RequestType>
BEGIN;
		}

		$paravalues=<<<BEGIN
<?xml version="1.0"?>
<Request>
<Header $headerRight/>
<DomesticHotelCommentRequest>$hotelIDs$effectYears$RequestType2</DomesticHotelCommentRequest>
</Request>
BEGIN;
		return  $paravalues;
	}
	/**
	 *
	 * 调用直接查询酒店评价的接口，获取到酒店评价的数据
	 */
	function main(){
		try{
			$requestXML=$this->getRequestXML();
			$commonRequestDo=new commonRequest();//常用数据请求
		 	$commonRequestDo->requestURL=D_HotelCommentList_Url;
		 	$commonRequestDo->requestXML=$requestXML;
		 	$commonRequestDo->requestType=System_RequestType;//取config中的配置
		 	$commonRequestDo->doRequest();
	 		$this->ResponseXML=getXMLFromReturnString($commonRequestDo->responseXML);
		}
		catch(Exception $e)
		{
			$this->ResponseXML=null;
		}
	}
	/**
	 *
	 * 获取指定hotelID的所有的评论内容列表
	 * @param $hotelID
	 */
	function getHotelIDXMLResponse($hotelID)
	{
		$DomesticHotelCommentList=null;
		if($hotelID!=null&&$hotelID!="")
		{
			$this->HotelID=$hotelID;
			$this->main();
			$returnXML=$this->ResponseXML;
			//echo $returnXML;
			$DomesticHotelCommentList=getXMLFromReturnString($returnXML)->DomesticHotelCommentList;
		}
		return $DomesticHotelCommentList;
	}

	/**
	 *
	 * 为酒店列表页面提供评论的显示功能
	 * @param $hotelID--酒店的ID，$limitNum--限制的条数
	 */
	private  function getHotelCommentIdentityTxt()
	{
		$IdentityTxt=<<<BEGIN
		           商务出差：comment_biz
			 带小孩出游：comment_kid
			夫妻/情侣--夫妇、情侣出游：comment_love
			和朋友出游：comment_friend
			独自出游：comment_single
			和家人出游：comment_family
			 代人预订：comment_book
			 其他：comment_other
BEGIN;

		return $IdentityTxt;
	}
	/**
	 *
	 * 将指定的评论数据打印出来
	 * @param unknown_type $returnCommentXML
	 */
	function getHotelCommentList($returnCommentXML)
	{
		$nums=0;
		foreach($returnCommentXML->DomesticHotelComment as $commentData)
		{
			if($nums<10)//先打印出来10条
			{
				echo  $commentData->Content."<br/>";
				$nums=$nums+1;
			}
			else {
				echo "...........";
				break;
			}

		}
	}
	/**
	 *
	 * @var 为酒店详细页面提供评论的列表显示功能
	 * @param $hotelID--酒店的ID，$limitNum--限制的条数（每页显示多少条）
	 */
	public  function getHotelCommentList_Limit($hotelID,$limitNum)
	{
		$commentHTML="";//以HTML形式输出的讨论内容
		//调用成功接口，返回数据结合，现在处理这些数据
		$DomesticHotelCommentList=$this->getXMLResponse($hotelID);//获取指定hotelID的所有的评论内容列表
		if($DomesticHotelCommentList!=null&&$DomesticHotelCommentList!="")
		{

			$commentDataList=$DomesticHotelCommentList->DomesticHotelCommentDataList;
			if($commentDataList!=null&&$commentDataList!="")
			{
				$commentHTML="<ul class=\"hotel_comment\">";
				$intIndex=0;//循环的标记
				foreach($commentDataList->DomesticHotelComment as $commentData)
				{
					$IdentityTxt=$commentData->IdentityTxt;//出行的类型
					$Contents=$commentData->Content;
					$Contents=htmlTransition($Contents);
					/*商务出差：comment_biz
					 带小孩出游：comment_kid
					 夫妻/情侣：comment_love
					 和朋友出游：comment_friend
					 独自出游：comment_single
					 和家人出游：comment_family
					 代人预订：comment_book
					 其他：comment_other*/
					$comment_bizName="comment_biz";
					if($IdentityTxt=="商务出差")
					{
						$comment_bizName="comment_biz";
					}
					else if($IdentityTxt=="带小孩出游")
					{
						$comment_bizName="comment_kid";
					}
					else if($IdentityTxt=="夫妻/情侣出游"||$IdentityTxt=="夫妇、情侣出游")
					{
						$comment_bizName="comment_love";
						$IdentityTxt="夫妻/情侣";
					}
					else if($IdentityTxt=="和朋友出游")
					{
						$comment_bizName="comment_friend";
					}
					else if($IdentityTxt=="独自出游")
					{
						$comment_bizName="comment_single";
					}
					else if($IdentityTxt=="和家人出游")
					{
						$comment_bizName="comment_family";
					}
					else if($IdentityTxt=="代人预订")
					{
						$comment_bizName="comment_book";
					}
					else if($IdentityTxt=="其他")
					{
						$comment_bizName="comment_other";
					}
					if($limitNum<>0)
					{
						if($intIndex<$limitNum){

							$comment_date=date("Y-m-d",strtotime($commentData->WritingDate));//格式化日期
							/*<div class="comment_user">
							 <img title="$commentData->NickName" alt="" width="34" height="34" src="http://tp2.sinaimg.cn/1774860981/50/5628576199/0" />
							 </div>*/
							$contains=<<<BEGIN
<li id="liCommentList-$hotelID-$limitNum-$intIndex">
<div class="$comment_bizName">$IdentityTxt</div>
<div class="comment_content">
<div class="h_comment_title">
<a>$commentData->CommentSubject</a><span class="h_comment_user">$commentData->NickName</span>&nbsp;$comment_date
</div>
<p><a style="cursor: pointer" name="showDetail" data="divTips-liCommentList-$hotelID-$limitNum-$intIndex">$Contents</a></p>
</div>
</li>
<div id="divTips-liCommentList-$hotelID-$limitNum-$intIndex" name="div_blogDetail" style="display: none; z-index: 99;" class="mask_comment">
	<h3>详细</h3>
<div><br/></div>
<div class="$comment_bizName">$IdentityTxt</div>
<div class="comment_content">
<div class="h_comment_title">
<a>$commentData->CommentSubject</a><span class="h_comment_user">$commentData->NickName</span>&nbsp;$comment_date
</div>
<div>$Contents<br/><br/></div>
</div>
</div>
BEGIN;
							$commentHTML=$commentHTML.$contains;
							$intIndex=$intIndex+1;
						}
						else
						{
							break;
						}
					}
					else if($limitNum==0)//显示全部的评论（需要做分页类型的控制）
					{
						$comment_date=date("Y-m-d",strtotime($commentData->WritingDate));//格式化日期
						$contains=<<<BEGIN
<li id="liCommentList-$hotelID-$limitNum-$intIndex">
<div class="$comment_bizName">$IdentityTxt</div>
<div class="comment_content">
<div class="h_comment_title">
<a>$commentData->CommentSubject</a><span class="h_comment_user">$commentData->NickName</span>&nbsp;$comment_date
</div>
<div>$Contents</div>
</div>
</li>
BEGIN;
						$commentHTML=$commentHTML.$contains;
						$intIndex=$intIndex+1;
					}
					else {
						break;
					}
				}
				$commentHTML = $commentHTML."</ul>";
				$commentHTML = $commentHTML."<input id=\"hdLiCommentListCount-$hotelID-$limitNum\" type=\"hidden\" value=\"$intIndex\"/>";
				$commentHTML = $commentHTML."<input id=\"hdCommentListHotelID\" type=\"hidden\" value=\"$hotelID\"/>";
			}
		}
		else
		{
			$commentHTML = "<div class=\"comment_no\">没有更多酒店点评。</div>";
		}
		return $commentHTML;
	}

}
?>