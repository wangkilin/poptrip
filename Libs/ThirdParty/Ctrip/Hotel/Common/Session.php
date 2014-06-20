<?php 

/**
 * Session 
 * Session封装类，实现对session的操作
 * @author wulx
 *
 */
class Session{
	
	public function __construct(){
		if (!session_id()){
			@session_start();
		}		 
	}
	/**
	 * get()
	 * 根据session name 获取session的值，如果session不存在则返回$default
	 * @param $key
	 * @param $default 默认返回值
	 */
	public function get($key,$default=false){
		$name=$this->constructUnionSessionName($key);		 
		return isset($_SESSION[$name])?$_SESSION[$name]:$default;		
	}
	/**
	 * set()
	 * 给指导的session name赋值
	 * @param string $key
	 * @param unknown_type $value
	 * @return bool true/false
	 */
	public function set($key,$value){
		$name=$this->constructUnionSessionName($key);
		$_SESSION[$name]=$value;
		return true;
	}
	/**
	 * remove()
	 * 删除指定的session
	 * @param string $key
	 * @return bool true/false
	 */
	public function remove($key){
		$name=$this->constructUnionSessionName($key);
		unset($_SESSION[$name]);
		return true;
	}
	/**
	 * contain()
	 * 检测是否给指定session赋值
	 * @param string $key
	 * @return bool  true/false
	 */
	public function contain($key){
		$name=$this->constructUnionSessionName($key);
		return isset($_SESSION[$name]);
	}
	protected function constructUnionSessionName($key){
		return "ctrip_union_".$key;
	} 
	
}



?>