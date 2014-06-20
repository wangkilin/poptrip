<?php
//require_once ('../../appData/db_sitead.php');
//header("Content-Type:text/html;charset=utf-8");
//加载系统中的广告数据
require_once (WEBROOT.'appData/db_sitead.php');
require_once (WEBROOT.'Common/toolExt.php');
/**
 * 广告实体
 * */
class Ad{
	/**
	 * 0  排序序号
	 * */
	var $orderId;
	/**
	 * 1  链接地址标签
	 * */
	var $linkLable;
	/**
	 * 2  连接位置名称
	 * */
	var $linkPosition;
	/**
	 *3  链接名称
	 * */
	var $linkName;
	/**
	 * 4  连接地址
	 * */
	var $linkUrl;
	/**
	 * 5  类型：-1 禁用；0-文字链接；1-外部图片链接;2-外部的JS代码（使用“外部图片链接地址”显示）
	 * */
	var $type;
	/**
	 * 6  更新时间
	 * */
	var $updateDate;
	/**
	 * 7  外部资源
	 * */
	var $src;
	/**
	 *
	 * 广告在广告数组中的索引
	 * @var 索引
	 */
	var  $key=null;

	function __construct(){}

	/**
	 * 更新当前的广告信息
	 *(更新之前必须指定$key,否则更新失败)
	 * @return $updateInfo['rs']更新code，$updateInfo['msg']更新结果msg
	 * */
	function update(){
		global  $siteAdArray;
		$updateInfo;
		// echo $this->key;
		if (!array_key_exists($this->key, $siteAdArray)) //如果在广告数组中不存在的key则不更新
		{
			$updateInfo['rs']='0';
			$updateInfo['msg']='更新的索引不在广告数组中';
		}else{
			//更新数组$siteAdArray
			$siteAdArray[$this->key][0]=htmlEncode($this->orderId);
			$siteAdArray[$this->key][1]=htmlEncode($this->linkLable);
			$siteAdArray[$this->key][2]=htmlEncode($this->linkPosition);
			$siteAdArray[$this->key][3]=htmlEncode($this->linkName);
			$siteAdArray[$this->key][4]=htmlEncode($this->linkUrl);
			$siteAdArray[$this->key][5]=htmlEncode($this->type);
			$siteAdArray[$this->key][6]=htmlEncode($this->updateDate);
			$siteAdArray[$this->key][7]=htmlEncode($this->src);
				
			self::sort($siteAdArray);//排序

			//保存到db_sitead.php文件中
			$info=$this->savedb_sitead_php($siteAdArray);
			$updateInfo['rs']=$info['rs'];
			$updateInfo['msg']=$info['msg'];
			 
		}
		return $updateInfo;
	}
	/**
	 * 对广告数组排序，按orderId SORT_DESC排序
	 *  
	 * @param $siteAdArray
	 */
 static	function sort(&$siteAdArray){
 		if ($siteAdArray){
			//按orderid对$siteAdArray排序
			foreach ($siteAdArray as $k=>$v){
				$order[$k]=$v[0];
			}
			array_multisort($order,SORT_DESC,$siteAdArray);
 		}
	}


	/**
	 * find($index)
	 * 根据索引查找对应的广告信息
	 * @param $index 广告在广告数组中的index
	 * @return bool 找到返回true,否则返回false
	 * */
	function find($index){
		global  $siteAdArray;
		if (!empty($siteAdArray)){
			if (array_key_exists($index, $siteAdArray))
			{
				$this->orderId=$siteAdArray[$index][0];
				$this->linkLable=$siteAdArray[$index][1];
				$this->linkPosition=$siteAdArray[$index][2];
				$this->linkName=$siteAdArray[$index][3];
				$this->linkUrl=$siteAdArray[$index][4];
				$this->type=$siteAdArray[$index][5];
				$this->updateDate=$siteAdArray[$index][6];
				$this->src=$siteAdArray[$index][7];

					
				return true;
			}
			else{
				return false;
			}
		}
	}

	private function savedb_sitead_php($siteAdArray){
		$content = "<?" . "php\n";
		$content.="/** \n";
		$content.=" * 定义系统中的广告的数据\n";
		$content.=" */ \n";
		$content.="// 排序|链接地址标签|连接位置名称|链接名称|连接地址|类型|添加时间|外部资源 \n";
		$content.="// 类型：-1 禁用；0-文字链接；1-外部图片链接;2-外部的JS代码（使用“外部图片链接地址”显示） \n";
		$content.="// 备注：在写入JS或者外部链接代码时，请请“\"”转变为“\\\"” \n\n";

		//拼接$siteAdArray数组字符串
		$siteAdArrayStr="\$siteAdArray=array(  ";
		foreach ($siteAdArray as $v){
			$siteAdArrayStr.="\n array('$v[0]','$v[1]','$v[2]','$v[3]','$v[4]','$v[5]','$v[6]','$v[7]'),";

		}
		$siteAdArrayStr=rtrim($siteAdArrayStr,',');
		$siteAdArrayStr.="\n);";

		$content.=$siteAdArrayStr;
		//$content.="";
		$content .= "\n\n?>";

		//$db_sitead="../../appData/db_sitead.php";
		$db_sitead=	WEBROOT.'appData/db_sitead.php';

		// 确定db_sitead.php文件存在并且可写
		if (is_writable($db_sitead)) {
			//打开文件
			if (!$fh = fopen($db_sitead, 'wb+')) {
				$msg = "不能打开文件". $db_sitead;
				$rs = '0';
			}
			// 写入内容
			elseif (fwrite($fh, $content) === FALSE) {
				$msg = "不能写入到文件". $db_sitead;
				$rs = '0';
			} else {
				$msg = "修改成功";
				$rs = '1';
				fclose($fh);
			}
		} else {
			$msg = "配置文件 ".$db_sitead ."不可写,请赋其可写权限";
			$rs = '0';
		}
		$write_info['msg'] = $msg;
		$write_info['rs'] = $rs;
		return $write_info;
		exit();
	}
}
/**
 *
 * 转换广告类型为文字描述  0-文字链接；1-外部图片链接;2-外部的JS代码（使用“外部图片链接地址”显示）
 * @param $type
 */
function showAdType($type)
{
	if ($type==0)
		return  "文字链接";
	elseif ($type==1)
		return  "外部图片链接";
	elseif ($type==2)
		return  "外部JS代码";
	elseif ($type==-1)
		return "禁用";
}
/*$ad=new Ad();
 $ad->find(0);
 $ad->orderId=4;
 $ad->key=1;
 print_r($ad->update());*/
//print_r($ad);


?>