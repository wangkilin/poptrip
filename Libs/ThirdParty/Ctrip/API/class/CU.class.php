<?php

/* PHP SDK
 * @version 2.0.0
 * @author magicsky0@163.com
 * @copyright © 2014, Ctrip Corporation. All rights reserved.
 */

require CU_COMM_PATH."utils.php";
require CU_CLASS_PATH."Token.class.php";
require CU_CLASS_PATH."MyHttp.class.php";

class CU extends Token
{
	public $open_api = 'http://openapi.ctrip.com';	
	protected $dir;
	
	public function __construct( $dir, $type, $uid='', $sid='', $key='' )
	{
		if( empty($dir) )
		{
			trigger_error('未指定类目录',E_USER_ERROR);
		}
		
		if( empty($type) )
		{
			trigger_error('未指定接口类型',E_USER_ERROR);
		}
		
		$this->dir = $dir;
		parent::__construct( $type, $uid, $sid, $key );
	}
	
	public function __call( $name, $args )
	{
		$path = CU_ROOT.$this->dir.'/'.$name.'.php';
		if( !file_exists($path) )
		{
			trigger_error('请求路径错误',E_USER_ERROR);
		}
		
		@require $path;
		
		if( !class_exists($name) )
		{
			trigger_error('实例不存在',E_USER_ERROR);
		}
		
		$C = new $name( $this->open_api, $args[0] );
		
		// xml加壳
		$request_xml = $C->request_xml( $this->_uid, $this->_sid, 
							$this->_stmp, $this->_sign, $this->_type );
		$request = $this->request_xml_shell($request_xml);
		
		// 远程通信
		$http = new MyHttp($C->open_api);
		$result_xml = $http->send($request);

		// xml去壳
		$respond = $this->respond_xml_shell($result_xml);
		$respond_type = array_key_exists(1, $args)?$args[1]:FALSE;
		if( !$respond_type )
		{
			$respond_xml = $C->respond_xml($respond);
		}
		else
		{
			$respond_xml = $this->respond_xml($respond,$respond_type);
		}

		return $respond_xml;
	}
	
	// 请求体xml加壳
	public function request_xml_shell($xml)
	{
		$request_xml_shell = 
				'<?xml version="1.0" encoding="utf-8"?>'
				.'<soap:Envelope '
					.'xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" '
					.'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
					.'xmlns:xsd="http://www.w3.org/2001/XMLSchema">'
				  .'<soap:Body>'
				    .'<Request xmlns="http://ctrip.com/">'
				      .'<requestXML>{%REQUEST_XML%}</requestXML>'
				    .'</Request>'
				  .'</soap:Body>'
				.'</soap:Envelope>';
		return str_replace('{%REQUEST_XML%}', $xml, $request_xml_shell);
	}
	
	// 返回体xml去壳
	public function respond_xml_shell($xml)
	{
		$request_xml_begin = 
				'<?xml version="1.0" encoding="utf-8"?>'
				.'<soap:Envelope '
					.'xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" '
					.'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '
					.'xmlns:xsd="http://www.w3.org/2001/XMLSchema">'
				  .'<soap:Body>'
				    .'<RequestResponse xmlns="http://ctrip.com/">'
				      .'<RequestResult>';
		$request_xml_end =
					  '</RequestResult>'
				    .'</RequestResponse>'
				  .'</soap:Body>'
				.'</soap:Envelope>';
		return str_replace( $request_xml_end, '', str_replace($request_xml_begin, '', $xml) );
	}
	
	public function respond_xml($string, $type='')
	{
		// 将内层xmll中转义的符号恢复
		$string = str_replace("&lt;","<",$string);
		$string = str_replace("&gt;",">",$string);
		
		$simplexml = simplexml_load_string($string);
		
		switch ($type)
		{
			case 'xml':
				return $simplexml;
				break;
			case 'json':
				return json_encode($simplexml);
				break;	
			case 'array':
				return json_decode(json_encode($simplexml),TRUE);
				break;
			case 'object':
				return json_decode(json_encode($simplexml),FALSE);
				break;
			case 'string':
			default:
				return $string;
		}
		
	}
}