<?php
/**
 * 定义系统中的超级连接的逻辑
 */
//排序0|连接名称1|连接地址2|状态3|类型4|添加时间5|有效期时间6|外部图片链接地址7
//状态：0-暂停使用；1-使用中；2-永久有效（使用中要判断“有效期时间”）
//类型：0-文字链接；1-外部图片链接（使用“外部图片链接地址”显示）
class friendlink{
	/**
	 *
	 * 获取到全部的友情链接数组数据
	 * @var 多维数组
	 */
	var $siteFriendLinkArray=null;
	/**
	 *
	 * 返回符合要求的HTML
	 * @var html
	 */
	var $responseHtml=null;
	/**
	 *
	 * 获取到图片链接的友情链接
	 */
	function getImageLinks()
	{
		$coutw="";
		if($this->siteFriendLinkArray!=null){
			//如果友情链接的数组不为空，则进行下面的操作
			foreach ($this->siteFriendLinkArray as $v){
				if(is_array($v))
				{
					if($v[4]=="1")//表示是图片链接
					{
						$isUse=false;//是否可以使用
						if($v[3]=="2")
						{
							$isUse=true;
						}
						else if($v[3]=="1"&&date("Y-m-d",strtotime($v[6]))>=date("Y-m-d",time()))
						{
						 $isUse=true;
						}
						else {
							$isUse=false;
						}
						$v7=htmlDecode($v[7]);
						if($isUse==true)
						{
						  	if($v[2])
							$coutw=$coutw."<a href=\"$v[2]\" target=\"_blank\"><img width=\"86\" height=\"30\" src=\"$v7\" alt=\"$v[1]\" />$v[1]</a>";
							else $coutw=$coutw."<a href=\"javascript:void(0);;\" ><img width=\"86\" height=\"30\" src=\"$v7\" alt=\"$v[1]\" />$v[1]</a>";
						}
					}
				}
			}
		}
		 $this->responseHtml=$coutw;
	}
	/**
	 * 
	 * 获取到文字链接
	 */
	function getWordsLinks()
	{
		$coutw="";
		if($this->siteFriendLinkArray!=null){
			//如果友情链接的数组不为空，则进行下面的操作
			foreach ($this->siteFriendLinkArray as $v){
				if(is_array($v))
				{
					if($v[4]=="0")//表示是图片链接
					{
						$isUse=false;//是否可以使用
						if($v[3]=="2")
						{
							$isUse=true;
						}
						else if($v[3]=="1"&&date("Y-m-d",strtotime($v[6]))>=date("Y-m-d",time()))
						{
						 $isUse=true;
						}
						else {
							$isUse=false;
						}
						if($isUse==true)
						{

							if($v[2])
							$coutw=$coutw."<a href=\"$v[2]\" target=\"_blank\">$v[1]</a>";
							else $coutw=$coutw."<a href=\"javascript:void(0);;\" >$v[1]</a>";
						}
					}
				}
			}
		}
		 $this->responseHtml=$coutw;
	}
}
?>