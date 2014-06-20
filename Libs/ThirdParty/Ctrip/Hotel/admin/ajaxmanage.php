<?php
header("Content-Type:text/html;charset=utf-8");

 if (!defined(WEBROOT)){
	 	define(WEBROOT, preg_replace("/admin/", '', dirname(__FILE__)));
	 }
require_once (WEBROOT.'appData/db_sitead.php');
require_once (WEBROOT.'appData/db_friendlink.php');
include ("inc/ad.inc.php");
require_once (WEBROOT.'Common/Session.php');//加载Session处理类
$session=new Session();

include_once ("../appData/database.config.php");//加载整站系统的配置文件
include_once (WEBROOT.'include/db.class.php');
date_default_timezone_set('PRC');//设置时区


$filedir="../data/dbbackup";

//验证是否登录
if (!$session->contain('admin')){
	exit("访问受限.......");
}

$module=$_POST['m'];
$action=$_POST['action'];
if ($module=='ad'){//广告异步处理逻辑
	 
	if ($action=='get'){ //根据索引获取指定的广告信息
		$adkey=$_POST['key'];		 
		if (array_key_exists($adkey, $siteAdArray)){
			$ad=$siteAdArray[$adkey];
			foreach ($ad as $k=>$v){
				$ad[$k]=htmlDecode($v);
			}
			exit(json_encode($ad));
		}
	}elseif($action=='update'){//更新指定的广告信息
		//todo 添加保存广告代码
		$adkey=$_POST['key'];		 
		if (array_key_exists($adkey, $siteAdArray)){
			$ad=new Ad();
			$ad->key=$adkey;
			$ad->orderId=$_POST['orderId'];
			$ad->linkLable=$_POST['linkLable'];
			$ad->linkPosition=$_POST['linkPosition'];
			$ad->linkName=$_POST['linkName'];
			$ad->linkUrl=$_POST['linkUrl'];
			$ad->type=$_POST['type'];
			$ad->updateDate=date('Y-m-d'); //$_POST['updateDate'];
			$ad->src=$_POST['src'];
			
			$rs= $ad->update();
			exit(json_encode($rs));
		} 		
	}
	exit('0');
}
elseif ($module=="friendlink"){//友情链接异步处理逻辑
	include_once ("inc/friendlink.inc.php");
	
	
	if ($action=="delete"){//删除操作逻辑
		$flkey=$_POST['key'];
		//$flkeys=$_POST['keys'];
		$rs=friendLink::batchDelete($flkey);
		exit(json_encode($rs));
	}elseif($action=="get"){//根据索引获取指定的信息
  		$key=$_POST['key'];		 
		if (array_key_exists($key, $siteFriendLinkArray)){
			$fl=$siteFriendLinkArray[$key];
			foreach ($fl as $k=>$v){
				$fl[$k]=htmlDecode($v);
			}
			exit(json_encode($fl));		 
		}
	}elseif ($action=="update"){
		 
		$key=$_POST['key'];	
		$fl=new friendLink();
		$fl->key=$key;
		$fl->orderId=$_POST['orderid'];
		$fl->linkName=$_POST['linkname'];
		$fl->linkUrl=$_POST['linkurl'];
		$fl->statue=$_POST['statue'];
		$fl->type=$_POST['type'];
		$fl->updateDate=date("Y-m-d");
		$fl->aliveDate=$_POST['alivedate'];
		$fl->srcUrl=$_POST['src'];
		if (array_key_exists($key, $siteFriendLinkArray)){		
			$rs=  $fl->update();
			exit(json_encode($rs));
		}
		elseif($key=="addnew"){
			$rs=$fl->add();
			exit(json_encode($rs));
		}
		
	}
	
}
elseif($module=="policy"){//网站挂牌异步处理逻辑
	include_once ("inc/policy.inc.php");
if ($action=="delete"){//删除操作逻辑
		$policykey=$_POST['key'];
		//$flkeys=$_POST['keys'];
		$rs=sitePolicy::delete($policykey);
		exit(json_encode($rs));
	}elseif($action=="get"){//根据索引获取指定的信息
  		$key=$_POST['key'];		 
		if (array_key_exists($key, $sitePolicyArray)){
			$policy=$sitePolicyArray[$key];
			foreach ($policy as $k=>$v){
				$policy[$k]=htmlDecode($v);
			}
			exit(json_encode($policy));		 
		}
	}elseif ($action=="update"){//更新操作
		 
		$key=$_POST['key'];	
		$policy=new sitePolicy();
		$policy->key=$key;
		$policy->orderId=$_POST['orderid'];
		$policy->policyName=$_POST['policyname'];
		$policy->linkUrl=$_POST['linkurl'];
		 
		$policy->imageUrl=$_POST['src'];
		if (array_key_exists($key, $sitePolicyArray)){	//更新	
			$rs=  $policy->update();
			exit(json_encode($rs));
		}
		elseif($key=="addnew"){//新增
			$rs=$policy->add();
			exit(json_encode($rs));
		}
		
	}
}
elseif ($module=="permission"){//权限管理异步处理逻辑
	include_once ("inc/permission.inc.php");
	$p=new permission();
	if ($action=="delete"){//删除操作
		/*$username=$_POST['username'];
		exit(json_encode($p->deleteUser($username))) ;*/		 
		$userids=$_POST['ids'];		 
		exit(json_encode($p->batchDeleteUser($userids))) ;
	}
	elseif($action=="update"){//修改操作
		unset($userinfo);
		$userinfo['UserName']=$_POST['username'];
		$userinfo['Password']=$_POST['password'];
		$userinfo['Password']=md5($userinfo['Password']);		
		exit(json_encode($p->updateUser($userinfo))) ;
	}
	elseif ($action=="add"){//新增操作
		unset($userinfo);
		$userinfo ['UserName']=$_POST['username'];
		$userinfo ['Password']=$_POST['password'];
		$userinfo ['Password']=md5($userinfo ['Password']);	
		exit(json_encode($p->addUser($userinfo))) ;
	}
}
elseif ($module=="keyword"){//网页关键字管理异步处理逻辑
	include_once ("inc/keyword.inc.php");
	$kw=new keyword();
	if ($action=="update"){//更新操作
		$kw->id=$_POST['id'];
		$kw->pagename=$_POST['pagename'];
		$kw->page=$_POST['page'];
		$kw->title=$_POST['title'];
		$kw->keywords=$_POST['keywords'];
		$kw->description=$_POST['description'];
		$kw->times=date('Y-m-d  h:i:s'); 
		$kw->rule=$_POST['rule'];
		exit($kw->save());
	}
	elseif($action=="get"){//获取单个关键字信息
		$id=$_POST['id'];
		if($kw->loadSingle($id)){	
			//if($id=='4')$kw->rule="{sitename}=表示站点名称，{city}=城市名称，{hotelname}=酒店的名字，{address}=酒店地址，{rating}=酒店点评分，{novoters}=酒店点评数{des}=酒店简介";//本次更新只与酒店详情页面相关		
			$json=	json_encode(array('id'=>$kw->id,'pagename'=>$kw->pagename,'page'=>$kw->page,'title'=>$kw->title,'keywords'=>$kw->keywords,'description'=>$kw->description,'rule'=>$kw->rule));
			exit($json);	
		}
		exit('0');
	}
}
elseif($module=="siteset"){//网站设置异步处理逻辑
	
}

elseif($module=='del'){
	$file=$_POST['file'];
	if($_POST['dbpwd']!=$cfg_dbpwd){
		exit('数据库密码错误');	
	}
	if($file){
		if(@unlink($filedir.'/'.$file)){
			echo 'delok';
			die;
		}else{
			echo $filedir.'删除失败';
			die;
		}
	}
}elseif($module=='revert'){
	if($_POST['dbpwd']!=$cfg_dbpwd){
		exit('数据库密码错误');	
	}
	$db=new DB($cfg_dbhost, $cfg_dbuser, $cfg_dbpwd, $cfg_dbname, $cfg_dbcharset, $cfg_dbprefix);
	$dbbackupSql=$_POST['file'];
	$tbdata = '';
    $fp = @fopen("$filedir/$dbbackupSql", 'r');
    while(!feof($fp)){
    	$tbdata .= @fgets($fp, 1024);
    }
    @fclose($fp);
    $querys = explode(';', $tbdata);
	foreach($querys as $q){
    	$db->query(trim($q));
    }
    
	die('数据库更新成功');
	
}  







?>