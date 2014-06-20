<?php

function gdversion()
{
	//没启用php.ini函数的情况下如果有GD默认视作2.0以上版本
	if (!function_exists('phpinfo')) {
		if (function_exists('imagecreate'))
		return '2.0';
		else
		return 0;
	}
	else {
		ob_start();
		phpinfo(8);
		$module_info = ob_get_contents();
		ob_end_clean();
		if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", $module_info, $matches)) {
			$gdversion_h = $matches[1];
		} else {
			$gdversion_h = 0;
		}
		return $gdversion_h;
	}
}
/**
 * 测试文件是否可写
 * 
 * @param $d
 */
function TestWrite($d)
{
	$tfile = '_dedet.txt';
	$d = preg_replace('/\/$/', '', $d);
	$fp = @fopen($d . '/' . $tfile, 'w');
	if (!$fp)
	return false;
	else {
		fclose($fp);
		$rs = @unlink($d . '/' . $tfile);
		if ($rs)
		return true;
		else
		return false;
	}
}
/**
 * 获得数据库列表
 *
 * @access  public
 * @param   string      $db_host        主机
 * @param   string      $db_port        端口号
 * @param   string      $db_user        用户名
 * @param   string      $db_pass        密码
 * @return  mixed       成功返回数据库列表组成的数组，失败返回false
 */
function get_db_list($db_host, $db_user, $db_pass)
{
	$databases = array();
	$filter_dbs = array('information_schema', 'mysql');
	$conn = @mysql_connect($db_host, $db_user, $db_pass);

	if ($conn === false) {
		return false;
	}

	$result = mysql_query('SHOW DATABASES', $conn);
	if ($result !== false) {
		while (($row = mysql_fetch_assoc($result)) !== false) {
			if (in_array($row['Database'], $filter_dbs)) {
				continue;
			}
			$databases[] = $row['Database'];
		}
	} else {
		$err->add($_LANG['query_failed']);
		return false;
	}
	@mysql_close($conn);

	return $databases;
}

/**
 * 创建配置文件
 *
 * @access  public
 * @param   string      $db_host        主机
 * @param   string      $db_port        端口号
 * @param   string      $db_user        用户名
 * @param   string      $db_pass        密码
 * @param   string      $db_name        数据库名
 * @param   string      $prefix         数据表前缀
 * @return  boolean     成功返回true，失败返回false
 */
function create_config_file($db_host, $db_user, $db_pass, $db_name, $prefix, $charset)
{
	$content = '<?' . "php\n";
	$content .= "// 数据库主机地址 冒号后面是端口\n";
	$content .= "\$cfg_dbhost   = \"$db_host\";\n\n";
	$content .= "// 数据库连接账户名\n";
	$content .= "\$cfg_dbuser   = \"$db_user\";\n\n";
	$content .= "// 数据库连接密码\n";
	$content .= "\$cfg_dbpwd   = \"$db_pass\";\n\n";
	$content .= "// 数据库名\n";
	$content .= "\$cfg_dbname   = \"$db_name\";\n\n";
	$content .= "// 数据表前缀\n";
	$content .= "\$cfg_dbprefix = \"$prefix\";\n\n";
	$content .= "// SET NAMES 编码\n";
	$content .= "\$cfg_dbcharset    = \"$charset\";\n\n";
	$content .= '?>';

	$dataconfig = WEBROOT . 'appData/database.config.php';
	// 确定database.config.php文件存在并且可写
	if (is_writable($dataconfig)) {
		//打开文件
		if (!$fh = fopen($dataconfig, 'wb+')) {
			$msg = "不能打开文件 $dataconfig";
			$rs = '0';
		}
		// 写入内容
		elseif (fwrite($fh, $content) === FALSE) {
			$msg = "不能写入到文件 $dataconfig";
			$rs = '0';
		} else {
			$msg = "数据库配置文件已成功创建";
			$rs = '1';
			fclose($fh);
		}
	} else {
		$msg = "配置文件 $dataconfig 不可写,请赋其可写权限";
		$rs = '0';
	}
	$write_info['msg'] = $msg;
	$write_info['rs'] = $rs;
	return $write_info;
	exit();
}

/**
 * 创建指定名字的数据库
 *
 * @access  public
 * @param   string      $db_host        主机
 * @param   string      $db_port        端口号
 * @param   string      $db_user        用户名
 * @param   string      $db_pass        密码
 * @param   string      $db_name        数据库名
 * @param   string      $prefix         数据表前缀
 * @return  boolean     成功返回true，失败返回false
 */
function create_database($db_host, $db_user, $db_pass, $db_name, $prefix, $charset)
{
	global $errinfo;
	$conn = @mysql_connect($db_host, $db_user, $db_pass);

	if ($conn === false) {
		$errinfo = '连接 数据库失败，请检查您输入的 数据库帐号 是否正确。';
		return false;
	}

	$mysql_version = mysql_get_server_info($conn);
	keep_right_conn($conn, $mysql_version);
	if (mysql_select_db($db_name, $conn) === false) {
		$sql = $mysql_version >= '4.1' ? "CREATE DATABASE `$db_name` DEFAULT CHARACTER SET utf8": "CREATE DATABASE $db_name";
		if (mysql_query($sql, $conn) === false) {
			$errinfo = '无法创建数据库';
			return false;
		}
	}
	@mysql_close($conn);

	return true;
}

/**
 * 保证进行正确的数据库连接（如字符集设置）
 *
 * @access  public
 * @param   string      $conn                      数据库连接
 * @param   string      $mysql_version        mysql版本号
 * @return  void
 */
function keep_right_conn($conn, $mysql_version='')
{
	if ($mysql_version === '') {
		$mysql_version = mysql_get_server_info($conn);
	}

	if ($mysql_version >= '4.1') {
		mysql_query('SET character_set_connection=utf8 , character_set_results=utf8 , character_set_client=binary', $conn);

		if ($mysql_version > '5.0.1') {
			mysql_query("SET sql_mode=''", $conn);
		}
	}
}
/**
 * 创建数据库表
 * @param string $structfile 表结构sql文件路径
 *
 * @return ArrayObject
 * */
function createDataTableStruct($structfile){
	global $db;
	if (empty($structfile))
	{
		$result['startgo']=0;
		$result['msg']="创建表结构脚本丢失，请检查安装包...";
	}else{
		$tbdata = ''; //初始还原数据
		$fp = fopen($structfile, 'r');
		while (!feof($fp)) {
			$tbdata .= fgets($fp, 1024);
		}
		fclose($fp); //完成对节构表的读取
		$querys = explode(';', $tbdata);
		foreach ($querys as $q) {
			//替换表前缀
			$q = Rpdbprefix($q);

			$db->query(trim($q) . ';') ;
		}
		$result['startgo']=1;
		$result['msg']="完成数据库表的创建，准备写入数据...";
	}
	return $result;
}
/**
 * 插入初始化数据
 * @param string $sqlfile  sql文件路径
 *
 * @return ArrayObject
 * */
function insertInitData($sqlfile){
	global $db;
	if (empty($sqlfile))
	{
		$result['startgo']=0;
		$result['msg']="初始化数据脚本丢失，请检查安装包...";
	}else{
		$tbdata = ''; //初始还原数据
		$fp = fopen($sqlfile, 'r');
		$succcessNum=0;
		$failNum=0;
		$failSql='';
		while (!feof($fp)) {
			$temp=trim(fgets($fp, 5*1024));			 
			if ((strrpos($temp, ';')+1)!=strlen($temp))
				continue;
			//echo "<br/><br/>".$temp."<br/>";
			$q = Rpdbprefix($temp);
			if($db->query($q)){
				$succcessNum++;
			}
			else{
				$failNum++;
				$failSql.=$temp.'<br/>';
			}
			 
		}		 
		fclose($fp); //完成对节构表的读取
		if ($failNum>0){
			$result['startgo']=0;
			$result['msg']="初始化数据结束，成功插入<font color='green'>".$succcessNum."</font>条数据，<font color='red'>".$failNum."</font>条数据插入失败...<br/>插入失败的sql如下：<br/><font color='red'>".$failSql."</font>";
		}else{
			$result['startgo']=1;
			$result['msg']="初始化数据写入完成,成功插入".$succcessNum."条数据...";
		}		
	}
	return $result;
}

/**
 * 还原sql的时候把prefix数据库表前缀也更新了
 * @param <type> $sql
 */
function Rpdbprefix($sql)
{
	$prefix_search = array('INSERT INTO `ctrip_', 'CREATE TABLE `ctrip_', 'DROP TABLE IF EXISTS `ctrip_');

	$prefix_replace = array("INSERT INTO `{$GLOBALS['cfg_dbprefix']}", "CREATE TABLE `{$GLOBALS['cfg_dbprefix']}", "DROP TABLE IF EXISTS `{$GLOBALS['cfg_dbprefix']}");
	 

	$prefix_sql = str_replace($prefix_search, $prefix_replace, $sql);
	// print_r($prefix_sql);
	return $prefix_sql;
}


/**
 * 检测携程酒店分销联盟uid和sid的
 * @param <type> $agentID
 * @param <type> $sid
 * @param		$apiKey
 * @return <type>
 */
function checkAgent($agentID, $sid,$apiKey)
{
	$checker=new checkAllianceInfo();
	$checker->AllianceId=$agentID;
	$checker->SID=$sid;
	$checker->key=$apiKey;

	$checker->main();
	$rh=$checker->ResponseXML->Header;
	if (trim($rh['ResultCode'])=='Success'){
		return true;
	}
	//echo '<br/><br/>'.json_encode($checker->ResponseXML);

	return false;
}
/**
 * 注册反馈
 *
 */
function setRegister($agentID,$sid,$apiKey){
	$register=new get_A_SetRegister();
	$register->IP=GetIP();
	$register->SetupDatetime=date("Y-m-d H:i:s");
	$register->AllianceID=$agentID;
	$register->SID=$sid;
	$register->KEY=$apiKey;
	$register->main();
	//echo $register->IP."  ".$register->SetupDatetime;
	//die;
}
/**
 * 更新后台一些配置文件
 * @global  $db
 * @param <type> $agentID 联盟ID
 * @param <type> $sid	联盟站点的sid
 * @param <type> $apiKey
 * @param <type> $webName	整站的名称
 * @param <type> $domainName	网站域名
 * @param <type> $city	默认显示城市
 * @return <type>
 */
function saveConfig($agentID, $sid, $apiKey, $webName, $domainName,$city,$shortName)
{
	//todo 添加更新配置信息到数据库中
	global $db;
	$info = array(
        'ConfigValue' => $agentID
	);
	$where = "ConfigName = 'SiteAllianceid'";
	$rs = $db->autoExecute('#@__siteconfig', $info, 'UPDATE', $where);

	$info = array(
        'ConfigValue' => $sid
	);
	$where = "ConfigName = 'SiteSid'";
	$rs = $db->autoExecute('#@__siteconfig', $info, 'UPDATE', $where);

	$info = array(
        'ConfigValue' => $apiKey
	);
	$where = "ConfigName = 'SiteSiteKey'";
	$rs = $db->autoExecute('#@__siteconfig', $info, 'UPDATE', $where);

	$info = array(
        'ConfigValue' => $webName
	);
	$where = "ConfigName = 'UnionSite_Name'";
	$rs = $db->autoExecute('#@__siteconfig', $info, 'UPDATE', $where);

	$info = array(
        'ConfigValue' => $domainName
	);
	$where = "ConfigName = 'UnionSite_domainName'";
	$rs = $db->autoExecute('#@__siteconfig', $info, 'UPDATE', $where);

	$info = array(
        'ConfigValue' => $city
	);
	$where = "ConfigName = 'SiteDefaultCityID'";
	$rs = $db->autoExecute('#@__siteconfig', $info, 'UPDATE', $where);
	
	$info = array(
        'ConfigValue' => $shortName
	);
	$where = "ConfigName = 'UnionSite_ShortName'";
	$rs = $db->autoExecute('#@__siteconfig', $info, 'UPDATE', $where);
	if ($rs) {
		return true;
	} else {
		return false;
	}
}

/**
 * 更新账号和密码信息
 * @global <type> $db
 * @param <type> $username
 * @param <type> $password
 * @return <type>
 */
function saveAdmin($username, $password )
{
	global $db;
	$info = array(
        'UserName' => $username,
        'Password' => md5($password)
	);
	$where = "id = 1";
	$rs = $db->autoExecute('#@__managermentuser', $info, 'UPDATE', $where);
	if ($rs) {
		return true;
	} else {
		return false;
	}
}

/**
 * 安装成功写install.lock
 */
function writeInstallLock()
{
	/* 写入安装锁定文件 */
	$fp = @fopen(WEBROOT . 'appData/install.lock', 'wb+');
	$rs['code']=1;
	if (!$fp) {
		$rs['msg']=('打开install.lock文件失败,请检查是否有文件读写权限');
		$rs['code']=0;
	}
	if (!@fwrite($fp, "TRADE SHOP INSTALLED")) {
		$rs['msg']=('写入install.lock文件失败,请检查是否有文件读写权限');
		$rs['code']=0;
	}
	@fclose($fp);
	return $rs;
}

/**
 * 检查是否安装数据库在第三步 写网站配置的时候
 * @global $db $db
 */
function checkdatabase()
{
	global $db;
	$sql = "SELECT * FROM #@__siteconfig LIMIT 0,1";
	$rs = $db->getRow($sql);
	if (!$rs) {
		redirect('请先安装数据库文件!', 'step2.php');
	}
}

function debugAjax($info)
{
	$file_info = var_export($info, true);
	$ok = file_put_contents(TMAC_ROOT . "/file_info.txt", $file_info);
	if ($ok)
	exit('true');
	exit('false');
}

?>