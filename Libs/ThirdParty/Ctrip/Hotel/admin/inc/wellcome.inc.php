<?php
require_once (WEBROOT.'SDK.config.php');//加载SDK配置文件
require_once (WEBROOT.'sdk/API/Custom/A_GetAnnouncementList.php');//加载获取公告列表接口
require_once (WEBROOT.'sdk/API/Custom/A_GetAnnouncement.php');//加载获取公告列表接口

/**
 * Announcement
 * 公告处理类
 * @author wulx
 *
 */
class Announcement{
	
	function __construct(){}
	/**
	 * getAnnouncementList($pageIndex,$pageSize)
	 * 获取公告列表
	 *  
	 * @param $pageIndex 当前页码
	 * @param $pageSize  每页大小
	 * @return Arrary  
	 */
	static 	function getAnnouncementList($pageIndex,$pageSize){
		$announcementList=new get_A_GetAnnouncementList();
		$announcementList->PageIndex=$pageIndex;
		$announcementList->PageSize=$pageSize;
		$announcementList->main();
		$responseXml=$announcementList->ResponseXML;
		//echo json_encode($responseXml);
 	
		$al=$responseXml->GetAnnouncementListResponse->AnnouncementDetailList->AnnouncementDetail;
		//echo json_encode($al);
		$rs['num']=0;		
		if (!empty($al)){
			$rs['num']=$responseXml->GetAnnouncementListResponse->RowCount;
			foreach ($al as $v){			 
				$list[]=array('title'=>$v->Title,'createtime'=>$v->CreateTime,'aid'=>$v->AID);
			}
			$rs['list']=$list;
		}		 
		return $rs;
	}
	/**
	 * getAnnouncementDetail($aid)
	 * 根据AID获取公告详情
	 *  
	 * @param $aid 公告id
	 * @return array    $announcement['title']公告标题
	 * 					$announcement['content']公告内容
	 * 					$announcement['aid']公告aid
	 */
	static 	function getAnnouncementDetail($aid) {
		$getAnnouncement=new get_A_GetAnnouncement();
		$getAnnouncement->AID=$aid;
		$getAnnouncement->main();
		$responseXml=$getAnnouncement->ResponseXML;
		//echo json_encode($responseXml);	
 
		$notice=$responseXml->GetAnnouncementResponse;
		if (!empty($notice)){
			$announcement['title']=$notice->Title;
			$announcement['content']=$notice->Content;
			$announcement['aid']=$notice->AID;
		}		
		return $announcement;
	}
	
}
?>