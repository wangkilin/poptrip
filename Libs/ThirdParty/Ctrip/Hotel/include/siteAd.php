<?php
/**
 * 定义系统中的广告逻辑
 */
include_once ("../Common/toolExt.php");//加载常用函数
include_once ("../appData/db_sitead.php");//加载广告的数据
//排序0|链接地址标签1|连接位置名称2|链接名称3|连接地址4|类型5|添加时间6|外部资源7
//类型：0-文字链接；1-外部图片链接;2-外部的JS代码（使用“外部图片链接地址”显示）
//备注：在写入JS或者外部链接代码时，请请“"”转变为"\""
class siteAd{
	/**
	 *
	 * 获取到全部的广告数组数据
	 * @var 多维数组
	 */
	var $siteAdArray=null;
	/**
	 *
	 * 返回符合要求的HTML
	 * @var html
	 */
	var $responseHtml=null;
	/**
	 *
	 * 获取到指定标签的广告（index_header--首页头部广告；index_foot--首页底部广告）
	 */
	function getAdLinks($requestType)
	{
		$coutw="";
		if($this->siteAdArray!=null){
			//排序
			//如果友情链接的数组不为空，则进行下面的操作
			foreach ($this->siteAdArray as $v){
				if(is_array($v))
				{
					if($v[1]==$requestType)//找指定标签的广告
					{
						//文字广告
						if($v[5]=="0")
						{
							if($v[4])$coutw=$coutw."<a href=\"$v[4]\" target=\"_blank\">$v[3]</a>";
							else $coutw=$coutw."<a href=\"javascript:void(0);;\" >$v[3]</a>";
						}
						//图片广告
						else if($v[5]=="1")
						{
						 	$v7=htmlDecode($v[7]);
							if($v[4]) $coutw=$coutw."<a href=\"$v[4]\" target=\"_blank\"><img  src=\"$v7\" alt=\"$v[3]\" /></a>";
							else $coutw=$coutw."<a href=\"javascript:void(0);;\" ><img  src=\"$v7\" alt=\"$v[3]\" /></a>";
						
						}
						//外部的JS代码
						else if($v[5]=="2")
						{
							$coutw=$coutw.htmlDecode($v[7]);//html_entity_decode($v['ConfigValue'],ENT_QUOTES ,'UTF-8')
						}
						//暂停的广告信息
						else
						{
							$coutw=$coutw;
						}
					}
				}
			}
			$coutw=str_replace("\\","",$coutw);//如果放置是JS 获取HTML，会包含\"，必须替换掉
		}
		 $this->responseHtml=$coutw;
	}
}
?>