<?php
include_once (WEBROOT.'include/db.class.php');
include_once (WEBROOT.'Common/toolExt.php');

/**
 * topicalConfig系统配置类
 * 系统配置参数实体
 * @author liuw2
 *
 */
class topicalConfig{

	private $tablename;
	private $db;
	
	/**
	 * 网站模版主题
	 * @var string
	 */
	var $UnionSite_Css;
	
	function __construct(){

		require (WEBROOT.'appData/database.config.php');

		$this->tablename= $cfg_dbprefix."siteconfig";

		$this->db=new DB($cfg_dbhost, $cfg_dbuser, $cfg_dbpwd, $cfg_dbname, $cfg_dbcharset, $cfg_dbprefix);
	}

	function __destruct(){
		$this->db->close();
	}
	/**
	 * load()
	 * 通过该方法加载系统配置参数，并对sysConfig实体属性赋值
	 */
	function load(){
		$sql="select ConfigName,ConfigValue,ConfigInfo from $this->tablename";
		$rows=$this->db->getAll($sql);
		foreach ($rows as $v){
			$value=htmlDecode($v['ConfigValue']);
			$value=str_replace('\\', '\\\\', $value);//处理转义字符\
			$rs[$v['ConfigName']]=array($value,$v['ConfigInfo']);
		}
		//print_r($rs);
		$this->UnionSite_Css=$rs['UnionSite_Css'][0];
	

	}
	/**
	 * save()
	 * 保存系统配置参数
	 */
	function save(){
		
		//print_r($this);die;
		$vars=get_object_vars($this);
		$privateVars=array('db','tablename');
		foreach ($vars as $name=>$value){
			if (in_array($name, $privateVars)){
				continue;
			}
			//if($value){
				$sql="update $this->tablename set ConfigValue=\"".htmlEncode($value)."\" where ConfigName='$name'";
				$rs =$this->db->query($sql);
				if (!$rs){
					$msg.=$name.",";
				}
			//}				
		}
		if (empty($msg)){
			$return['rs']='1';
			$return['msg']='保存到成功';
		}else {
			$return['rs']='0';
			$return['msg']=$msg.'更新失败';
		}
		return $return;	
		 
	}
	/**
	 *rewriteConfigFile()
	 * 重新生成site.config.php文件
	 */
	/**
	 *rewriteConfigFile()
	 * 重新生成site.config.php文件
	 */
	function rewriteConfigFile(){
		//$UnionCopyRight="版权：携程酒店预订 Copyright &copy; 1999-2012, <a href='#'>http://ctrip.com</a>. All rights reserved.";
		require (WEBROOT.'admin/inc/common.php');
		$sql="select ConfigName,ConfigValue,ConfigInfo,Type from $this->tablename order by ID";
		$rows=$this->db->getAll($sql);
			
		$configstr="<?php \n";
		$configstr.="/**\n";
		$configstr.=" * 该文件定义网站配置信息\n";
		$configstr.=" **/ \n\n";
	
		foreach ($rows as $v){
			if ($v['Type']=='int'){
				//$configstr.="\$".$v['ConfigName']."=".html_entity_decode($v['ConfigValue'],ENT_QUOTES ,'UTF-8').";\t//".$v['ConfigInfo']."\n";
				$configstr.="\$".$v['ConfigName']."=".$v['ConfigValue'].";\t//".$v['ConfigInfo']."\n";
				if ($v['ConfigName']=='SiteDefaultCityID'){//写入默认城市名称
					$city=	$defaultCityNameArray[$v['ConfigValue']];
					$city=empty($city)?'上海':$city;
					$configstr.="\$SiteDefaultCityName=\"$city\";\n";
				}
			}else{
				//$configstr.="\$".$v['ConfigName']."=\"".html_entity_decode($v['ConfigValue'],ENT_QUOTES,'UTF-8' )."\";\t//".$v['ConfigInfo']."\n";
				$configstr.="\$".$v['ConfigName']."=\"".$v['ConfigValue']."\";\t//".$v['ConfigInfo']."\n";
			}
		}
		$configstr.="\n\n?>";

		$siteConfigFile=WEBROOT.'appData/site.config.php';
		if (is_writable($siteConfigFile)) {
			//打开文件
			if (!$fh = fopen($siteConfigFile, 'wb+')) {
				$msg = "不能打开文件". $siteConfigFile;
				$rs = '0';
			}
			// 写入内容
			elseif (fwrite($fh, $configstr) === FALSE) {
				$msg = "不能写入到文件 ".$siteConfigFile;
				$rs = '0';
			} else {
				$msg = "配置文件".$siteConfigFile."保存成功";
				$rs = '1';
				fclose($fh);
			}
		} else {
			$msg = "配置文件". $siteConfigFile ."不可写,请赋其可写权限";
			$rs = '0';
		}
		$write_info['msg'] = $msg;
		$write_info['rs'] = $rs;
		return $write_info;
	}
}

?>
