<?php
/**
 * 后台安装处理文件
 *  
 */
error_reporting(E_ALL ^ E_NOTICE); 
define("WEBROOT", preg_replace("/install/", '', dirname(__FILE__)));

require_once( WEBROOT.'appData/database.config.php');
require_once( WEBROOT.'appData/site.config.php');

require_once( WEBROOT.'Common/Session.php');
//调用CheckSidAid.php  SDK所需的文件
require_once( WEBROOT.'SDK.config.php');
require_once (WEBROOT.'sdk/API/Custom/CheckSidAid.php');
require_once (WEBROOT."sdk/API/Custom/A_SetRegister.php");

//引用工具类
require_once( WEBROOT.'Common/rightControl.php');
require_once( WEBROOT.'Common/getDate.php');
require_once( WEBROOT.'Common/commonRequestData.php');
require_once( WEBROOT.'Common/RequestDomXml.php');
require_once (WEBROOT."Common/toolExt.php");
//调用site.config.php保存类
require_once( WEBROOT.'admin/inc/siteset.inc.php');

require_once('install.inc.php');
require_once(WEBROOT.'include/db.class.php');

$lockfile=WEBROOT.'appData/install.lock';
if (file_exists($lockfile)){	
	header("Content-type:text/html;charset=utf-8");
	exit("程序已运行安装，如果你确定要重新安装，请先从FTP中删除 install.lock 文件");
	die;
}

$_POST['action'] = empty($_POST['action']) ? '' : $_POST['action'];
$action = empty($_GET['action']) ? $_POST['action'] : $_GET['action'];

 if ((!empty($action) && $action != 'createConfigFile' && $action != 'chkdname' && $action != 'sdname') ) {
    if ( empty($cfg_dbname)) {
        header('Location:step2.php');
        exit();
    } else {
        //生成数据库操作实例
        $db = new DB($cfg_dbhost, $cfg_dbuser, $cfg_dbpwd, $cfg_dbname, $cfg_dbcharset, $cfg_dbprefix);
    }
}

if ($action=="sdname"){//获取数据库列表
	 
	$dbhost = empty($_POST['dbhost']) ? '' : $_POST['dbhost'];
	$dbuser = empty($_POST['dbuser']) ? '' : $_POST['dbuser'];
	$dbpwd = empty($_POST['dbpwd']) ? '' : $_POST['dbpwd'];
	
	$rs = get_db_list($dbhost, $dbuser, $dbpwd);
	if ($rs === false)
	exit('errorpwd');
	foreach ($rs AS $k => $v) {
		$json[] = $v;
	}
	$a = json_encode($json);
	exit($a);
}
elseif ($action == 'chkdname') {//验证是否可以连接数据库
    $dbhost = empty($_POST['dbhost']) ? '' : $_POST['dbhost'];
    $dbuser = empty($_POST['dbuser']) ? '' : $_POST['dbuser'];
    $dbpwd = empty($_POST['dbpwd']) ? '' : $_POST['dbpwd'];
    $conn = @mysql_connect($dbhost, $dbuser, $dbpwd);

    if ($conn === false) {
        exit('0');
    } else {
        exit('1');
    }
}
//创建配置文件
elseif ($action == 'createConfigFile') { 
	 
    $dbhost = empty($_POST['dbhost']) ? '' : $_POST['dbhost'];
    $dbuser = empty($_POST['dbuser']) ? '' : $_POST['dbuser'];
    $dbpwd = empty($_POST['dbpwd']) ? '' : $_POST['dbpwd'];
    $dbname = empty($_POST['dbname']) ? '' : $_POST['dbname'];
    $dbprefix = empty($_POST['dbprefix']) ? '' : $_POST['dbprefix'];
    $dblang = empty($_POST['dblang']) ? '' : $_POST['dblang'];
   //重新生成db.config.php文件
    $info = create_config_file($dbhost, $dbuser, $dbpwd, $dbname, $dbprefix, $dblang);
    $json['rs'] = $info['rs'];
    $json['info'] = $info['msg'];
    $jsons = json_encode($json);
    exit($jsons);
    die;
}
//初始化数据库
elseif ($action == 'createDatabase') {
    global $errinfo;
    $dbhost = empty($_POST['dbhost']) ? '' : $_POST['dbhost'];
    $dbuser = empty($_POST['dbuser']) ? '' : $_POST['dbuser'];
    $dbpwd = empty($_POST['dbpwd']) ? '' : $_POST['dbpwd'];
    $dbname = empty($_POST['dbname']) ? '' : $_POST['dbname'];
    $dbprefix = empty($_POST['dbprefix']) ? '' : $_POST['dbprefix'];
    $dblang = empty($_POST['dblang']) ? '' : $_POST['dblang'];
    
    
    $rs = create_database($dbhost, $dbuser, $dbpwd, $dbname, $dbprefix, $dblang);
    if ($rs) {
        $rs = '1';
        $info = '数据库创建';
    } else {
        $rs = '0';
        $info = $errinfo;
    }

    $json['rs'] = $rs;
    $json['info'] = $info; //."   create_database($dbhost,$dbuser,$dbpwd, $dbname, $dbprefix, $dblang)";
    $jsons = json_encode($json);
    exit($jsons);
    die;
}
//安装表节构
elseif ($action == 'installBaseData') {
    $bkdir = WEBROOT . 'appData/installsql/';   
            
    if (!$dh = @dir($bkdir)) {
        $json['rs'] = 0;
        $json['msg'] = '没找到的安装数据库初始数据文件,请联系技术人员, 或重新下载最新安装!';
        $jsons = json_encode($json);
        exit($jsons);
        die;
    } 
          
    while (($filename = $dh->read()) !== false) {   	
    	        
        if (!preg_match('/sql$/', $filename)) {
            continue;
        }
       
        if (preg_match('/table_struct/', $filename)) {
            $structfile = $filename;
            break;
        }  
    }
    $dh->close();    

    $info=createDataTableStruct($bkdir.$structfile);
	
    $jsons = json_encode($info);
    exit($jsons);
    die;
}
//安装表数据
elseif ($action == 'installBaseData_table') {
     $bkdir = WEBROOT . 'appData/installsql/';
      if (!$dh = @dir($bkdir)) {
        $json['rs'] = 0;
        $json['msg'] = '没找到的安装数据库初始数据文件,请联系技术人员, 或重新下载最新安装!';
        $jsons = json_encode($json);
        exit($jsons);
        die;
    }    
     while (($filename = $dh->read()) !== false) {   	
    	        
        if (!preg_match('/sql$/', $filename)) {
            continue;
        }       
        if (preg_match('/table_data/', $filename)) {
            $datafile = $filename;
            break;
        }  
    }
    $dh->close();   
    
    $info=insertInitData($bkdir.$datafile);      
    $jsons = json_encode($info);
    exit($jsons);
    die;
}
//保存网站信息
elseif($action=='savewebinfo'){
		
	    $agentID = trim($_POST['agentID']);
	    $sid = trim($_POST['sid']);
	    $apiKey = trim($_POST['apiKey']);
	    $webName = trim($_POST['webName']);
	    $domainName = trim($_POST['domainName']);
	    $city=trim($_POST['city']);
	    $username = trim($_POST['username']);
	    $password = trim($_POST['password']);
	    $shortName=trim($_POST['shortName']);
	    //1. 验证联盟ID
	    if (!checkAgent($agentID, $sid,$apiKey)) {
	        exit('联盟推广ID或API Key不正确！');
	    }
	
	    //开始插入config
	    $rs = saveConfig($agentID, $sid, $apiKey, $webName, $domainName,$city,$shortName);
	    if (!$rs) {
	        exit('保存不成功,请重试！');
	    }
	
	    $rs = saveAdmin($username, $password);
	    if (!$rs) {
	        exit('账号密码信息保存失败,请重试！');
	    }
	    //重新生成site.config.php
	    $sysConfig=new sysConfig();
	    $sysConfig->load();
	    $sysConfig->rewriteConfigFile();
	    //ReWriteConfig();
	    $rs_write = writeInstallLock();
	   
	    if ($rs_write['code']==1) {
	    	$session=new Session();
	    	$session->set('admin', $username);
	    	$session->set('city', $city);	
	    	setRegister($agentID,$sid,$apiKey);
	       exit('1');
	    } else {
	        exit($rs_write['msg']);
	    }
	   
}


?>
 