<?php 

include_once (WEBROOT.'include/db.class.php');
 
/**
 * 权限管理类
 * 
 * @author wulx
 *
 */
class permission{
	
	 private  $admin_table ;
	 private  $db=null;	 
	  
	 function __construct(){
	 	require (WEBROOT.'appData/database.config.php');
	 	 
	 	$this->admin_table= $cfg_dbprefix."managermentuser";
	 	 
	 	$this->db=new DB($cfg_dbhost, $cfg_dbuser, $cfg_dbpwd, $cfg_dbname, $cfg_dbcharset, $cfg_dbprefix);
		 
	 }
	 
	 function __destruct(){
	 	$this->db->close();
	 }
	 /**
	  * 獲取所以的管理員信息
	  * Enter description here ...
	  */
	 function getAllUser(){
		  $rs=	$this->db->getAll("select * from $this->admin_table");
		  return $rs;
	 }
 
	/**
	 * 新增管理员操作
	 * @param $userInfo 用户信息数组
	 * @return $rs 操作结果，$rs['rs']='0'操作失败，$rs['rs']='1'操作成功，$rs['msg']操作提示信息
	 */
	 function addUser($userInfo){
	 	 $rs;
	 	 if (isset($this->db)){
	 	 	if (!$this->is_userExist($userInfo['UserName'])){
	 	 		
	 	 		//$sql="insert into $this->admin_table values('".$userInfo['username']."','".$userInfo['password']."')";
	 	 		$r=$this->db->autoExecute($this->admin_table,$userInfo,'INSERT');
	 	 		if ($r){
	 	 			$rs['rs']='1';
	 	 			$rs['msg']='添加成功';
	 	 		}
	 	 		else{
	 	 			$rs['rs']='0';
	 	 			$rs['msg']='添加失败';
	 	 		}
	 	 	}else{
	 	 		$rs['rs']='0';
	 	 		$rs['msg']="用户名".$userInfo['UserName']."已经存在";
	 	 	}
	 	 	
	 	 }else{
	 	 	$rs['rs']='0';
	 	 	$rs['msg']='数据库连接失败';
	 	 }
	 	return $rs;
	}
	/**
	 * 修改管理员操作
	 * @param $userInfo 用户信息数组
	 * @return $rs 操作结果，$rs['rs']='0'操作失败，$rs['rs']='1'操作成功，$rs['msg']操作提示信息
	 */
	 function updateUser($userInfo){
		 
	 	$rs;
	 	 if (isset($this->db)){
	 	 	 	$where="username='".$userInfo['UserName']."'"; 	 		 	 		 
	 	 		$r=$this->db->autoExecute($this->admin_table,$userInfo,'UPDATE',$where);
	 	 		if ($r){
	 	 			$rs['rs']='1';
	 	 			$rs['msg']='修改成功';
	 	 		}
	 	 		else{
	 	 			$rs['rs']='0';
	 	 			$rs['msg']='修改失败';
	 	 		} 	 	 
	 	 	
	 	 }else{
	 	 	$rs['rs']='0';
	 	 	$rs['msg']='数据库连接失败';
	 	 }
		return $rs;
	}
	/**
	 * 删除管理员操作
	 * @param $username 用户名
	 * @return $rs 操作结果，$rs['rs']='0'操作失败，$rs['rs']='1'操作成功，$rs['msg']操作提示信息
	 */
	 function deleteUser($username){
	 	$rs;
	 	if (isset($this->db)){
	 		
			$r = $this->db->execute("DELETE FROM $this->admin_table WHERE username ='$username'");
	 	 	if ($r){
	 	 			$rs['rs']='1';
	 	 			$rs['msg']='删除成功';
	 	 		}
	 	 		else{
	 	 			$rs['rs']='0';
	 	 			$rs['msg']='删除失败';
	 	 		} 	 	 
	 	}else{
	 	 	$rs['rs']='0';
	 	 	$rs['msg']='数据库连接失败';
	 	 }
		return $rs;
	}
/**
	 * 批量删除管理员操作
	 * @param $userids 用户名(1,2,3,4)
	 * @return $rs 操作结果，$rs['rs']='0'操作失败，$rs['rs']='1'操作成功，$rs['msg']操作提示信息
	 */
 function batchDeleteUser($userids){
	 	$rs;
	 	if (isset($this->db)){
	 		$userids=rtrim($userids,',');
			$r = $this->db->execute("DELETE FROM $this->admin_table WHERE id in ($userids)");			 
	 	 	if ($r){
	 	 			$rs['rs']='1';
	 	 			$rs['msg']='删除成功';
	 	 		}
	 	 		else{
	 	 			$rs['rs']='0';
	 	 			$rs['msg']='删除失败';
	 	 		} 	 	 
	 	}else{
	 	 	$rs['rs']='0';
	 	 	$rs['msg']='数据库连接失败';
	 	 }
		return $rs;
	}
	/**
	 * 根据用户名获取ID
	 * Enter description here ...
	 * @param $username
	 */
	function getIDByUserName($username){
		$sql="select ID from $this->admin_table where username='$username'";
		return $this->db->getOne($sql);
	}
	
	/**
	 * 判断管理员是否存在
	 * @param $username 用户名
	 * @return bool 存在返回true，不存在返回false
	 */
	 function is_userExist($username){
		$sql="select count(*) from $this->admin_table where username='$username'";
		// echo $sql;		 
		$count= $this->db->getOne($sql);		 
		//echo $count;
		return $count>0?true:false;
	}
	
}
 

/**
 * 测试
 */
/*$p=new permission();*/
 
//測試
//print_r($p->getAllUser());

//print_r($p->is_userExist("wlx"));//测试is_userExist()
 

/*print_r($p->batchDeleteUser("5,6,7,8,"));//测试deleteUser
echo "<br/><br/>";
*/
//测试updateUser
/*$user["username"]="wlx3";
$user["password"]="11331";
print_r($p->updateUser($user));
echo "<br/><br/>";*/

//测试addUser
/*$userinfo['UserName']='www';
$userinfo['Password']="www";
$userinfo['Password']=md5($userinfo['Password']);
print_r($p->addUser($userinfo));
echo "<br/><br/>";*/

?>