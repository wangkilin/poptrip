<?php

//加载系统中的挂牌数据
require_once (WEBROOT.'appData/db_sitepolicy.php');

require_once (WEBROOT.'Common/toolExt.php');
 

/**
 *
 * 网站挂牌实体 ...
 * @author wulx
 *
 */
class sitePolicy{
	/**
	 * 0 排序号 ...
	 * @var unknown_type
	 */
	var $orderId;
	/**
	 * 1 挂牌名称
	 * @var unknown_type
	 */
	var $policyName;
	/**
	 * 2 连接地址
	 * @var unknown_type
	 */
	var $linkUrl;
	 
	/**
	 * 3 外部图片链接地址
	 * @var unknown_type
	 */
	var $imageUrl;
	/**
	 * 索引
	 * @var unknown_type
	 */
	var $key=null;

	function __construct(){}
	/**
	 * 更新当前的挂牌信息
	 * (更新之前必须指定$key,否则更新失败)
	 * @return $rs['rs']更新code，$rs['msg']更新结果msg
	 */
	function update(){
		global $sitePolicyArray;
		$rs;
		if (!array_key_exists($this->key, $sitePolicyArray)){
			$rs['rs']='0';
			$rs['msg']='更新的索引不在挂牌数组中';
		}else{
			//更新数组$sitePolicyArray
			$sitePolicyArray[$this->key][0]=htmlEncode( $this->orderId);
			$sitePolicyArray[$this->key][1]=htmlEncode( $this->policyName);
			$sitePolicyArray[$this->key][2]=htmlEncode( $this->linkUrl);
			$sitePolicyArray[$this->key][3]=htmlEncode( $this->imageUrl);
			 

			//排序
			$this->sort($sitePolicyArray);
			//保存修改信息
			$info=$this->savedb_sitePolicy_php($sitePolicyArray);
			$rs['rs']=$info['rs'];
			$rs['msg']=$info['msg'];
		}
		if ($rs['rs']){
			$rs['msg']=' 修改成功';
		}
		return $rs;

	}
	/**
	 * 添加当前挂牌实体到数组中
	 * @return $rs['rs']添加code，$rs['msg']添加结果msg
	 */
	function add(){
		global $sitePolicyArray;
		$rs;
		//添加当前友情链接到数组中
		$sitePolicyArray[]=array($this->orderId,htmlEncode($this->policyName),htmlEncode($this->linkUrl),htmlEncode($this->imageUrl));
		//排序
		self::sort($sitePolicyArray);
		//保存
		$info=$this->savedb_sitePolicy_php($sitePolicyArray);
		$rs['rs']=$info['rs'];
		$rs['msg']=$info['msg'];
		if ($rs['rs']){
			$rs['msg']=' 保存成功';
		}
		return $rs;
	}

	 

	/**
	 * 删除指定索引的挂牌
	 * @return $rs['rs']删除code，$rs['msg']删除结果msg
	 *
	 */
	static 	function delete($index){
		global $sitePolicyArray;
		$rs;
		if (!array_key_exists($index, $sitePolicyArray)){
			$rs['rs']='0';
			$rs['msg']='要删除的索引不在友情链接数组中';
		}else {
			/*echo "<br/>删除前<br/>";
			 print_r($sitePolicyArray);*/

			foreach ($sitePolicyArray as $k=>$v){
				if ($k!=$index){
					$temp[]=$v;
				}
			}
			$sitePolicyArray=$temp;
			//排序
			self::sort($sitePolicyArray);

			//保存
			$info=self::savedb_sitePolicy_php($sitePolicyArray);
			$rs['rs']=$info['rs'];
			$rs['msg']=$info['msg'];

		}
		if ($rs['rs']){
			$rs['msg']=' 删除成功';
		}
		return $rs;
	}
	/**
	 *对挂牌数组排序
	 
	 *按orderId	SORT_DESC
	 *
	 * @param $sitePolicyArray
	 */
	static  function sort(&$sitePolicyArray){
		if ($sitePolicyArray){
			/*echo "<br/>排序前<br/>";
			 print_r($sitePolicyArray);*/
	
			//排序，按orderid对$sitePolicyArray排序
			foreach ($sitePolicyArray as $k=>$v){
				$order[$k]=$v[0];			
			}
			array_multisort($order,SORT_DESC,$sitePolicyArray);
			/*echo "<br/>排序后<br/>";
			 print_r($sitePolicyArray);*/
		}
	}

	private function savedb_sitePolicy_php($sitePolicyArray){

		$content = "<?" . "php\n";
		$content.="/** \n";
		$content.=" * 定义站点挂牌信息\n";
		$content.=" */ \n";
		$content.="//排序|挂牌名称|链接地址|图片地址 \n";
		$content.="//序号越大排在越前 \n\n\n";
	 

		//拼接$siteAdArray数组字符串
		$sitePolicyArrayStr="\$sitePolicyArray=array( ";
		if ($sitePolicyArray){
			foreach ($sitePolicyArray as $v){
				$sitePolicyArrayStr.="\n array('$v[0]','$v[1]','$v[2]','$v[3]'),";
			}
			$sitePolicyArrayStr=rtrim($sitePolicyArrayStr,',');
		}
		$sitePolicyArrayStr.="\n);";

		$content.=$sitePolicyArrayStr;
		//$content.="";
		$content .= "\n\n?>";

		//$db_friendlink="../../appData/db_friendlink.php";
		$db_sitepolicy=	WEBROOT.'appData/db_sitepolicy.php';

		// 确定db_friendlink.php文件存在并且可写
		if (is_writable($db_sitepolicy)) {
			//打开文件
			if (!$fh = fopen($db_sitepolicy, 'wb+')) {
				$msg = "不能打开文件". $db_sitepolicy;
				$rs = '0';
			}
			// 写入内容
			elseif (fwrite($fh, $content) === FALSE) {
				$msg = "不能写入到文件 ".$db_sitepolicy;
				$rs = '0';
			} else {
				$msg = "配置文件".$db_sitepolicy."保存成功";
				$rs = '1';
				fclose($fh);
			}
		} else {
			$msg = "配置文件". $db_sitepolicy ."不可写,请赋其可写权限";
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
//$fl->sort($sitePolicyArray);
//friendLink::batchDelete("0,1,2,8,9");
?>