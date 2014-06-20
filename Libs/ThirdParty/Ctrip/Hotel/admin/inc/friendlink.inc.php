<?php

//加载系统中的友情链接数据
require_once (WEBROOT.'appData/db_friendlink.php');

require_once (WEBROOT.'Common/toolExt.php');
/**
 *
 * 转换友情类型为文字描述  0-文字链接；1-外部图片链接; （使用“外部图片链接地址”显示）
 * @param $type
 */
function showFLType($type)
{
	if ($type==0)
	return  "文字链接";
	elseif ($type==1)
	return "外部图片链接";
}
/**
 *
 * 转换友情状态为文字描述  0-暂停使用；1-使用中;2-永久有效; （使用“外部图片链接地址”显示）
 * @param $type
 */
function showFLStatue($statue)
{
	if ($statue==0)
	return  " 停止";
	elseif ($statue==1)
	return "使用中";
	elseif($statue==2)
	return "永久";
}

/**
 *
 * 友情链接实体 ...
 * @author wulx
 *
 */
class friendLink{
	/**
	 * 0 排序号 ...
	 * @var unknown_type
	 */
	var $orderId;
	/**
	 * 1 连接名称
	 * @var unknown_type
	 */
	var $linkName;
	/**
	 * 2 连接地址
	 * @var unknown_type
	 */
	var $linkUrl;
	/**
	 * 3 状态
	 * 0-暂停使用；1-使用中；2-永久有效
	 * @var unknown_type
	 */
	var $statue;
	/**
	 * 4 类型
	 * 0-文字链接；1-外部图片链接
	 * @var unknown_type
	 */
	var $type;
	/**
	 * 5 添加时间
	 * @var unknown_type
	 */
	var $updateDate;
	/**
	 * 6 有效期时间.
	 * @var unknown_type
	 */
	var $aliveDate;
	/**
	 * 7 外部图片链接地址
	 * @var unknown_type
	 */
	var $srcUrl;
	/**
	 * 索引
	 * @var unknown_type
	 */
	var $key=null;

	function __construct(){}
	/**
	 * 更新当前的友情链接信息
	 * (更新之前必须指定$key,否则更新失败)
	 * @return $rs['rs']更新code，$rs['msg']更新结果msg
	 */
	function update(){
		global $siteFriendLinkArray;
		$rs;
		if (!array_key_exists($this->key, $siteFriendLinkArray)){
			$rs['rs']='0';
			$rs['msg']='更新的索引不在友情链接数组中';
		}else{
			//更新数组$siteFriendLinkArray
			$siteFriendLinkArray[$this->key][0]=htmlEncode( $this->orderId);
			$siteFriendLinkArray[$this->key][1]=htmlEncode( $this->linkName);
			$siteFriendLinkArray[$this->key][2]=htmlEncode( $this->linkUrl);
			$siteFriendLinkArray[$this->key][3]=htmlEncode( $this->statue);
			$siteFriendLinkArray[$this->key][4]=htmlEncode( $this->type);
			$siteFriendLinkArray[$this->key][5]=htmlEncode( $this->updateDate);
			$siteFriendLinkArray[$this->key][6]=htmlEncode( $this->aliveDate);
			$siteFriendLinkArray[$this->key][7]=htmlEncode( $this->srcUrl);

			//排序
			$this->sort($siteFriendLinkArray);
			//保存修改信息
			$info=$this->savedb_friendlink_php($siteFriendLinkArray);
			$rs['rs']=$info['rs'];
			$rs['msg']=$info['msg'];
		}
		if ($rs['rs']){
			$rs['msg']=' 修改成功';
		}
		return $rs;

	}
	/**
	 * 添加当前友情链接实体到数组中
	 * @return $rs['rs']添加code，$rs['msg']添加结果msg
	 */
	function add(){
		global $siteFriendLinkArray;
		$rs;
		//添加当前友情链接到数组中
		$siteFriendLinkArray[]=array($this->orderId,htmlEncode($this->linkName),htmlEncode($this->linkUrl),htmlEncode($this->statue),htmlEncode($this->type),$this->updateDate,$this->aliveDate,htmlEncode($this->srcUrl));
		//排序
		self::sort($siteFriendLinkArray);
		//保存
		$info=$this->savedb_friendlink_php($siteFriendLinkArray);
		$rs['rs']=$info['rs'];
		$rs['msg']=$info['msg'];
		if ($rs['rs']){
			$rs['msg']=' 保存成功';
		}
		return $rs;
	}

	/**
	 * 删除指定索引的友情链接
	 * @param  $indexsstr 索引组合以“，”分割，如1,2,3,4
	 * @return $rs['rs']删除code，$rs['msg']删除结果msg
	 *
	 */
	static 	function batchDelete($indexsstr){
		global $siteFriendLinkArray;
		$rs;
		$indexsstr=trim($indexsstr,',');
		$indexArr=explode(",", $indexsstr);
		if (!empty($indexArr)){
			 foreach ($siteFriendLinkArray as $k=>$v){		 	 
			 	if (!in_array($k, $indexArr)){
			 		$temp[]=$v;
			 	}
			 }
			 /* echo "<br/>删除<br/>";
			  print_r($temp);
			  exit();*/
		  
			$siteFriendLinkArray=$temp;
			//排序
			self::sort($siteFriendLinkArray);
			//保存
			$info=self::savedb_friendlink_php($siteFriendLinkArray);
			$rs['rs']=$info['rs'];
			$rs['msg']=$info['msg'];
				
			if ($rs['rs']){
				$rs['msg']=' 删除成功';
			}
			return $rs;
		}
	}

	/**
	 * 删除指定索引的友情链接
	 * @return $rs['rs']删除code，$rs['msg']删除结果msg
	 *
	 */
	static 	function delete($index){
		global $siteFriendLinkArray;
		$rs;
		if (!array_key_exists($index, $siteFriendLinkArray)){
			$rs['rs']='0';
			$rs['msg']='要删除的索引不在友情链接数组中';
		}else {
			/*echo "<br/>删除前<br/>";
			 print_r($siteFriendLinkArray);*/

			foreach ($siteFriendLinkArray as $k=>$v){
				if ($k!=$index){
					$temp[]=$v;
				}
			}
			$siteFriendLinkArray=$temp;
			//排序
			self::sort($siteFriendLinkArray);

			//保存
			$info=self::savedb_friendlink_php($siteFriendLinkArray);
			$rs['rs']=$info['rs'];
			$rs['msg']=$info['msg'];

		}
		if ($rs['rs']){
			$rs['msg']=' 删除成功';
		}
		return $rs;
	}
	/**
	 *对友情链接数组排序
	 *先按statue  	SORT_DESC
	 *再按orderId	SORT_DESC
	 *
	 * @param $siteFriendLinkArray
	 */
	static  function sort(&$siteFriendLinkArray){
		if ($siteFriendLinkArray){
			/*echo "<br/>排序前<br/>";
			 print_r($siteFriendLinkArray);*/
	
			//排序，按orderid对$siteFriendLinkArray排序
			foreach ($siteFriendLinkArray as $k=>$v){
				$order[$k]=$v[0];
				$statue[$k]=$v[3];
			}
			array_multisort($statue,SORT_DESC,$order,SORT_DESC,$siteFriendLinkArray);
			/*echo "<br/>排序后<br/>";
			 print_r($siteFriendLinkArray);*/
		}
	}

	private function savedb_friendlink_php($siteFriendLinkArray){

		$content = "<?" . "php\n";
		$content.="/** \n";
		$content.=" * 定义系统中的友情连接的数据\n";
		$content.=" */ \n";
		$content.="//排序|连接名称|连接地址|状态|类型|添加时间|有效期时间|外部图片链接地址\n";
		$content.="//状态：0-暂停使用；1-使用中；2-永久有效（使用中要判断“有效期时间”） \n";
		$content.="//类型：0-文字链接；1-外部图片链接（使用“外部图片链接地址”显示） \n\n";

		//拼接$siteAdArray数组字符串
		$siteFriendLinkArrayStr="\$siteFriendLinkArray=array( ";
		if($siteFriendLinkArray){
			foreach ($siteFriendLinkArray as $v){
				$siteFriendLinkArrayStr.="\n array('$v[0]','$v[1]','$v[2]','$v[3]','$v[4]','$v[5]','$v[6]','$v[7]'),";
	
			}
			$siteFriendLinkArrayStr=rtrim($siteFriendLinkArrayStr,',');
		}
		$siteFriendLinkArrayStr.="\n);";

		$content.=$siteFriendLinkArrayStr;
		//$content.="";
		$content .= "\n\n?>";

		//$db_friendlink="../../appData/db_friendlink.php";
		$db_friendlink=	WEBROOT.'appData/db_friendlink.php';

		// 确定db_friendlink.php文件存在并且可写
		if (is_writable($db_friendlink)) {
			//打开文件
			if (!$fh = fopen($db_friendlink, 'wb+')) {
				$msg = "不能打开文件". $db_friendlink;
				$rs = '0';
			}
			// 写入内容
			elseif (fwrite($fh, $content) === FALSE) {
				$msg = "不能写入到文件 ".$db_friendlink;
				$rs = '0';
			} else {
				$msg = "配置文件".$db_friendlink."保存成功";
				$rs = '1';
				fclose($fh);
			}
		} else {
			$msg = "配置文件". $db_friendlink ."不可写,请赋其可写权限";
			$rs = '0';
		}
		$write_info['msg'] = $msg;
		$write_info['rs'] = $rs;
		return $write_info;
		exit();
	}
}

//test
//friendLink::delete(0);
/*$fl=new friendLink();
 $fl->orderId=1;
 $fl->statue=3;
 $fl->linkName="wulaixiang1113";
 print_r($fl->add());*/
//$fl->sort($siteFriendLinkArray);
//friendLink::batchDelete("0,1,2,8,9");
?>