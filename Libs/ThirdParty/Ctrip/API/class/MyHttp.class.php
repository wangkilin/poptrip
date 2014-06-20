<?php

/* PHP SDK
 * @version 2.0.0
 * @author magicsky0@163.com
 * @copyright © 2014, Ctrip Corporation. All rights reserved.
 */

class MyHttp
{
	public $url;
	public $method;
	public $timeout = 15;
	public $type;
	public $responed;
	
	private $_host;
	private $_uri;
	private $_port = 80;
	private $_header = array();
	private $_body = '';

	/**
	 * @param stirng $url
	 * @param stirng $method get|post
	 * @param stirng $type smart|curl|socket|fscoket
	 * @param int $timeout 
	 */
	public function __construct( $url, $type='smart', $method='post', $timeout=15)
	{
		$this->url = $url;
		$this->method = 'POST'; // strtoupper($method) TODO:暂时只支持POST
		$this->type = strtolower($type);
		$this->timeout = $timeout;
		
		$this->parse_url();
	}
	
	private function parse_url()
	{
		preg_match("/^http\:\/\/([^\:\/]+?)(\:(\d+))?\/(.+?)$/is", $this->url, $match);
		$this->_host = $match[1]; 
		$this->_uri = '/'.$match[4];
		$this->_port = !empty($match[2])?intval($match[3]):80;
	}
	
	public function set_header($request, $options = array() )
	{		
		$header['Accept'] = '*/*';
		$header['Accept-Language'] = 'zh-cn';
		$header['Accept-Encoding'] = '';
		$header['Transfer-Encoding'] = '';
		if( extension_loaded('zlib') && function_exists('gzdecode') )
		{
			$header['Accept-Encoding'] = 'gzip,deflate'; // open api服务器默认为此设置
			//$header['Transfer-Encoding'] = 'chunked'; // gzip后Content-Length长度不确定
		}
		$header['User-Agent'] = 'HttpRequest Class 1.1';
		$header['Host'] = $this->_host;
		$header['Connection'] = 'close';
		$header['Content-Length'] = strlen($request);
		$header['Content-Type'] = 'text/xml;charset=utf-8';
		
		// 允许覆盖上面的默认配置项
		if( !empty($options) )
		{
			$header = array_merge( $header, $options );
		}
		
		$this->_header = $header;
	}
	
	public function send($request)
	{		
		debugger($request,'Request Entity');
		$this->set_header($request);
		
		switch ($this->type)
		{
			case 'stream' : $this->stream($request); break;
			case 'curl' : $this->curl($request); break;
			case 'socket' : $this->socket($request); break;
			case 'fsocket' : $this->fsocket($request); break;
			default : $this->smart($request);
		}
		
		debugger($this->responed,'Responed Entity');
		return $this->responed;
	}
	
	private function encode_respond( $buffer )
	{
		debugger($buffer,'Buffer Entity');
		
		$header = $body = '';
		$p = strpos( $buffer, "\r\n\r\n" );
		
		// 去除头部信息
		if( $p > 0 )
		{
			$header = substr( $buffer, 0, $p );
			if( $p+4 < strlen($buffer) ) 
			{
				$body = substr( $buffer, $p+4 );
			}
		}
		
		// 如果header为续传标记则继续去除头部信息
		if( $header = 'HTTP/1.1 100 Continue' )
		{
			$p = strpos( $body, "\r\n\r\n" );
			
			if( $p > 0 )
			{
				$header = substr( $body, 0, $p );
				if( $p+4 < strlen($body) ) 
				{
					$body = substr( $body, $p+4 );
				}
			}
		}
		
		/*
		$body_without_header = '';
		$p2 = strpos( $buffer, "<?xml" ); 	// 第一次出现XML标记的位置
		if( $p2 > 1 )
		{
			if( $p2-1 < strlen($buffer) )
			{
				$body_without_header = substr( $buffer,$p2-1 );
			}
		}
		*/
		try
		{
			if( $this->_header['Accept-Encoding'] == 'gzip,deflate' )
			{
				$body = @gzdecode($body);
				if( !$body ) 
				{
					trigger_error('返回体解析错误',E_USER_WARNING);
				}
			}
	
			if( $this->_header['Transfer-Encoding'] == 'chunked' )
			{
				$body = @http_chunked_decode($body);
			}
		}
		catch( Exception $e )
		{
			trigger_error($e);
		}

		return $body;
	}
	
	// 智能选择通信模式
	private function smart($request)
	{
		if ( function_exists('curl_init') )
		{
			$this->curl($request);
			return TRUE;
		}

		if ( function_exists('fsockopen') )
		{
			$this->fsocket($request);
			return TRUE;
		}

		if ( function_exists('stream_context_create') )
		{
			$this->stream($request);
			return TRUE;
		}

		trigger_error('您的系统环境不支持远程调用！',E_USER_ERROR);
	}
	
	private function stream($request)
	{	
		$header = '';
		foreach($this->_header as $keys => $value)
		{
			$header .= "$keys: $value\r\n";
		}
		
		$opts = array( 
				'http' => array( 
					'method' => $this->method, 
					'header' => $header,
					'content' => $request 
				) 
			);	
		$context = stream_context_create($opts);
		$fp = fopen($this->url, 'r', FALSE, $context);
		$buffer = stream_get_contents($fp);
		fclose($fp);
	
		$this->responed = $buffer;
	}
	
	private function curl($request)
	{
		foreach($this->_header as $keys => $value)
		{
			$header[] = "$keys:$value";
		}
		$body = $request;
		
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $this->url);
	    curl_setopt($ch, CURLOPT_PORT, $this->_port);
	    curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);  
	     
	    curl_setopt($ch, CURLOPT_HEADER, TRUE); // 启用时会将头文件的信息作为数据流输出
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	    
	    curl_setopt($ch, CURLOPT_POST, TRUE); 
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
	     	    
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // 在启用时，返回原生的（Raw）输出 
	    $buffer = curl_exec($ch);
	    curl_close($ch);
	    
	    $this->responed = $this->encode_respond($buffer);
	}
	
	private function socket($request)
	{
		$body = "$this->method $this->_uri HTTP/1.1\r\n";		
		foreach($this->_header as $keys => $value)
		{
			$body .= "$keys: $value\r\n";
		}
		$body .= "\r\n".$request;
		
		$fp = socket_create(AF_INET,SOCK_STREAM,0);
		socket_connect($fp, $this->_host, $this->_port); // openapi.ctrip.com
		socket_write($fp,$body);
		
		$timestart = time();
		$buffer = $tmp = '';
		do{
			$buffer .= $tmp;
			$tmp = socket_read($fp,4096);
		}
		while( $tmp && (time()-$timestart) <= $this->timeout );
		
		socket_close($fp);
		
		$this->responed = $this->encode_respond($buffer);
	}
	
	private function fsocket($request)
	{
		$body = "$this->method $this->_uri HTTP/1.1\r\n";

		foreach($this->_header as $keys => $value)
		{
			$body .= "$keys: $value\r\n";
		}
		$body .= "\r\n".$request;
		
		$fp = fsockopen($this->_host, $this->_port, $errno, $errstr, $this->timeout);
		
		fputs($fp, $body);
		
		$timestart = time();
		$buffer = '';
		while( !feof($fp) && (time()-$timestart) <= $this->timeout )
		{
			$buffer .= fgets($fp,4096);
		}
		fclose($fp);

		$this->responed = $this->encode_respond($buffer);
	}
}