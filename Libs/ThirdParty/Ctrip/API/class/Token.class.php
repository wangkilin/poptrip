<?php

/* PHP SDK
 * @version 2.0.0
 * @author magicsky0@163.com
 * @copyright © 2014, Ctrip Corporation. All rights reserved.
 */

class Token 
{
	protected $_uid;
	protected $_sid;
	protected $_key;
	protected $_stmp;
	protected $_sign;
	protected $_type;
	
	public function __construct( $type, $uid='', $sid='', $key='' )
	{
		$path = CU_TOKEN_PATH;
		
		if( !$uid || !$sid || !$key )
		{
			if( !file_exists($path) )
			{
				trigger_error('验证文件不存在',E_USER_ERROR);
			}
			
			$tmp = json_decode(file_get_contents($path),TRUE);
			
			if( !array_key_exists('uid', $tmp) || 
				!array_key_exists('sid', $tmp) || 
				!array_key_exists('key', $tmp) )
			{
				trigger_error('data/json文件数据错误',E_USER_ERROR);
			}
			
			$uid = $tmp['uid'];
			$sid = $tmp['sid'];
			$key = $tmp['key'];
		}
		
		$this->verify( $type, $uid, $sid, $key );
	}
	
	public function verify( $type, $uid, $sid, $key )
	{
		$this->_type = $type;
		$this->_uid = $uid;
		$this->_sid = $sid;
		$this->_key = $key;
		list($usec, $sec) = explode(" ", microtime());
		$this->_stmp = $sec;
		$this->_sign = $this->UMD5(
							$this->_stmp
							.$this->_uid
							.$this->UMD5($this->_key)
							.$this->_sid
							.$this->_type
						);
	}
	
	public function UMD5($str)
	{
		return strtoupper(md5($str));
	}
}