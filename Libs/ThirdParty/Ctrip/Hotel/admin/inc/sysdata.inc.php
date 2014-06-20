<?php 
include_once ("../appData/database.config.php");//加载整站系统的配置文件
include_once (WEBROOT.'include/db.class.php');
date_default_timezone_set('PRC');//设置时区


$tablename= $cfg_dbprefix."siteconfig";
$db=new DB($cfg_dbhost, $cfg_dbuser, $cfg_dbpwd, $cfg_dbname, $cfg_dbcharset, $cfg_dbprefix);
$tables=$db->getAll("show tables");
if ($tables === false)
exit('数据库链接失败');


if($_POST){
	if($_POST['pwd']!=$cfg_dbpwd){
		exit('数据库密码错误');	
	}
	if(empty($_POST['checkbox']))exit('数据表不能为空');	
	
	foreach ($_POST['checkbox'] as  $table){
		$sqldump .=data2sql($table);
	}
	//去除AUTO_INCREMENT
	$sqldump = preg_replace("#AUTO_INCREMENT=([0-9]{1,})[ \r\n\t]{1,}#i", "", $sqldump);
	
	// 如果数据内容不是空就开始保存
	if(trim($sqldump)){
		$filename="../data/dbbackup/".date("YmdHis")."-".$cfg_dbname.".sql";
		@$fp = fopen($filename, "w+");
		if ($fp){
			@flock($fp, 3);
			if(@!fwrite($fp, $sqldump))
			{
			    @fclose($fp);
			    echo "数据文件无法保存到服务器，请检查目录属性你是否有写的权限。";
			}
			else
			{
			   	echo "数据成功备份至服务器 data/dbbackup文件夹中。";
			}
		}
		else{
			echo "无法打开你指定的目录". $filename ."，请确定该目录是否存在，或者是否有相应权限";
		}
		// 保存到服务器结束
	}else{
		echo "数据表没有任何内容";
	}
	
}


function data2sql($table){
	global $db;
	$tabledump = "DROP TABLE IF EXISTS $table;\n";
	$createtable = $db->query("SHOW CREATE TABLE $table");
	$create = $db->fetch_row($createtable);
	$tabledump .= $create[1].";\n\n";
	$rows = $db->query("SELECT * FROM $table");
	while ($row = $db->fetch_row($rows)){
	   $comma = "";
	   $tabledump .= "INSERT INTO $table VALUES(";
	   for($i = 0; $i < count($row); $i++){
	   	$tabledump .= $comma."'".mysql_escape_string($row[$i])."'";
		$comma = ",";
	   }
	   $tabledump .= ");\n";
	}
	$tabledump .= "\n";
	return $tabledump;
}






?>