<?php
include_once (WEBROOT.'include/db.class.php');
include_once (WEBROOT.'Common/toolExt.php');



/**
 * sysConfig系统配置类
 * 系统配置参数实体
 * @author wulx
 *
 */
class sysConfig{

	private $tablename;
	private $db;
	/**
	 * $UnionSite_Name
	 * 网站名称
	 * @var string
	 */
	var $UnionSite_Name;
	/**
	 * $UnionSite_ShortName
	 * 网站简称
	 * @var string
	 */
	var $UnionSite_ShortName;
	/**
	 * 网站域名地址
	 * @var string
	 */
	var $UnionSite_domainName;
	/**
	 * 网站底部版权说明
	 * @var string
	 */
	var $UnionCopyRight;
	/**
	 *
	 * @var string
	 */
	var $UnionICP;
	/**
	 * 联盟的ID
	 * @var string
	 */
	var $SiteAllianceid;
	/**
	 * 联盟站点的ID
	 * @var string
	 */
	var $SiteSid;
	/**
	 * Key
	 *  @var string
	 */
	var $SiteSiteKey;
	/**
	 * 联盟用户的用户名
	 * @var string
	 */
	var $SiteAllianceid_Uid;
	/**
	 * 本系统的LOGO
	 * @var string
	 */
	var $SiteLogImageUrl;
	/**
	 * 首页上默认显示的城市ID[1-北京；2-上海；3-天津；4-重庆]
	 * @var
	 */
	var $SiteDefaultCityID;
	/**
	 * 系统是否要进行URL伪静态[1-是，0-否]
	 * @var
	 */
	var $SiteUrlRewriter;
	/**
	 * 酒店搜索页面上，一次显示酒店的条数
	 * @var
	 */
	var $SiteHotelSearch_pagesize;
	/**
	 * 最多显示多少个历史浏览记录
	 * @var string
	 */
	var $SiteHotelBrowserListTotalNums;
	/**
	 * 酒店点评页面上评论总数
	 * 
	 * @var int
	 */
	var $HotHotelCommentNumsList;
	/**
	 * 首页酒店评论数
	 * @var int
	 */
	var $HotHotelCommentNumsIndex;
	/**
	 * 酒店列表默认图片路径
	 *  @var unknown_type
	 */
	var $SiteHotelDefaultImageUrl;
	/**
	 * 页面编码格式
	 * 
	 * @var string
	 */
	var $SiteCharset;
/**
 * 设置最新开业起始时间
 *  
 * @var unknown_type
 */
	var $HotelNewOpenTime;
	/**
	 * 设置最新加盟起始时间
	 *  
	 * @var unknown_type
	 */
	var $HotelNewContractTime;
	/**
	 * 酒店搜索默认截止日期
	 * @var int
	 */
	var $HotelSearchDayNums;
	
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
		$this->SiteAllianceid=$rs['SiteAllianceid'][0];
		$this->SiteAllianceid_Uid=$rs['SiteAllianceid_Uid'][0];
		$this->SiteDefaultCityID=$rs['SiteDefaultCityID'][0];
		$this->SiteHotelBrowserListTotalNums=$rs['SiteHotelBrowserListTotalNums'][0];
		$this->SiteHotelSearch_pagesize=$rs['SiteHotelSearch_pagesize'][0];

		$this->SiteLogImageUrl=$rs['SiteLogImageUrl'][0];
		$this->SiteSid=$rs['SiteSid'][0];
		$this->SiteSiteKey=$rs['SiteSiteKey'][0];
		$this->SiteUrlRewriter=$rs['SiteUrlRewriter'][0];
		$this->SiteHotelDefaultImageUrl=$rs['SiteHotelDefaultImageUrl'][0];
		$this->UnionCopyRight=$rs['UnionCopyRight'][0];
		$this->UnionICP=$rs['UnionICP'][0];
		$this->UnionSite_domainName=$rs['UnionSite_domainName'][0];		 
		$this->UnionSite_Name=$rs['UnionSite_Name'][0];
		$this->UnionSite_ShortName=$rs['UnionSite_ShortName'][0];
		$this->HotelNewContractTime=$rs['HotelNewContractTime'][0];
		$this->HotelNewOpenTime=$rs['HotelNewOpenTime'][0];
		$this->HotHotelCommentNumsIndex=$rs['HotHotelCommentNumsIndex'][0];
		$this->HotHotelCommentNumsList=$rs['HotHotelCommentNumsList'][0];
		$this->SiteCharset=$rs['SiteCharset'][0];
		$this->HotelSearchDayNums=$rs['HotelSearchDayNums'][0];
		$this->Booking_State=$rs['Booking_State'][0];
		$this->MapKey=$rs['MapKey'][0];

	}
	/**
	 * save()
	 * 保存系统配置参数
	 */
	function save(){
		$vars=get_object_vars($this);
		$privateVars=array('db','tablename');
		foreach ($vars as $name=>$value){
			if (in_array($name, $privateVars)){
				continue;
			}
			
			$is_exist=$this->db->getOne("select ConfigName from $this->tablename where  ConfigName='$name'  ");
			if($is_exist){
				$sql="update $this->tablename set ConfigValue=\"".htmlEncode($value)."\" where ConfigName='$name'";
			}else {
				echo "<script>alert('".$this->tablename."数据表中无".$name."数据,请重新安装站点或者去数据还原中还原table_data.sql文件,负责无法程序无法正常执行');history.go(-1);</script>";
				die;
				//$sql="insert into $this->tablename set ConfigValue=\"".htmlEncode($value)."\" , ConfigName='$name',Type='string',ConfigInfo='请重新安装ctrip_siteconfig表中数据',Level='1',OrderBy='1',State='1'";
			}
			$rs =$this->db->query($sql);
			//$this->db->autoExecute($this->tablename, $info, 'UPDATE', $where);
			if (!$rs){
				$msg.=$name.",";
			}				
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
