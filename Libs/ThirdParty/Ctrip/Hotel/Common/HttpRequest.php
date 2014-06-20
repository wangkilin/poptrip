<?php
/*
 HttpRequest Class
 唐春龙 2012-05-17
 携程
 */
class myHttpRequest{
	public $url,$method,$port,$hostname,$uri,$protocol,$excption,$_headers=array(),$_senddata,$status,$statusText,$HttpProtocolVersion,$responseBodyWithoutHeader;
	private $fp=0,$_buffer="",$responseBody,$responseHeader,$timeout=0,$useSocket;
	//构造函数
	function __construct($url="",$method="GET",$useSocket=0){
		$this->url = $url;
		$this->method = strtoupper($method);
		$this->useSocket = $useSocket;
		$this->setRequestHeader("Accept","*/*");
		$this->setRequestHeader("Accept-Language","zh-cn");
		//if(extension_loaded('zlib')) $this->setRequestHeader("Accept-Encoding","gzip, deflate");
		$this->setRequestHeader("Accept-Encoding","gzip;q=1.0,identity;q=0.5,*;q=0");
		$this->setRequestHeader("User-Agent","HttpRequest Class 1.0");  //可调用setRequestHeader来修改
	}

	//连接服务器
	public function open($ip="",$port=-1){
		if(!$this->_geturlinfo()) return false;
		$this->setRequestHeader("Host",$this->hostname);
		$this->setRequestHeader("Connection","close");
		$ip = ($ip=="" ? $this->hostname : $ip);
		$port = ($port==-1 ? $this->port : $port);
		if($this->useSocket==1){
			if(!$this->fp=$socket=socket_create(AF_INET,SOCK_STREAM,0)) {
				$this->excption="can not create socket";return false;
			}else{
				if(!socket_connect($this->fp,$ip, $port)	){
					$this->excption="can not connect to server " . $this->hostname . " on port" . $this->port;return false;
				}
			}
		}else{
			$this->fp=@fsockopen($ip, $port,$errno,$errstr,30);
			if($this->fp==false) {
				$this->excption="can not connect to server " . $this->hostname . " on port" . $this->port;
				header("HTTP/1.1 301 Moved Permanently");
				header("Location: http://".$_SERVER['HTTP_HOST']."/site/errorconnect.php?op=1");
				exit(); 
			}
		}
		return true;
	}

	public function send($data=""){
		if(!$this->fp){$this->excption="is not a resource id";return false;}
		if($this->method=="GET" && $data!=""){
			$s_str="?";
			if(strpos($this->uri,"?")>0) $s_str = "&";
			$this->uri.= $s_str . $data;
			$data="";
		}
		$senddata=$this->method . " " . $this->uri . " HTTP/1.1\r\n";
		if($this->method=="POST"){
			$this->setRequestHeader("Content-Length",strlen($data));
			//为了让PHP可以调用API2.0的接口，这边做了修改2012-06-28 CLTANG
			$this->setRequestHeader("Content-Type", "text/xml; charset=utf-8");//application/x-www-form-urlencoded
		}
		foreach($this->_headers as $keys => $value){
			$senddata .= "$keys: $value\r\n";
		}
		$senddata .= "\r\n";
		if($this->method=="POST") $senddata .= $data;
		$this->_senddata = $senddata;
		if($this->useSocket==1){
			socket_write($this->fp,$this->_senddata);
			$buffer="";
			$timestart = time();
			do{
				if($this->timeout>0){
					if(time()-$timestart>$this->timeout){break;}
				}
				$this->_buffer.=$buffer;
				$buffer = socket_read($this->fp,4096);
			}while($buffer!="");
			socket_close($this->fp);
		}else{
			fputs($this->fp, $senddata);
			$this->_buffer="";
			$timestart = time();
			
			//请求超时处理
 			$status = stream_get_meta_data( $this->fp ) ;
			if($status['timed_out']){
				return false;
				die();
			}
 
			while(!feof($this->fp))
			{
				if($this->timeout>0){
					if(time()-$timestart>$this->timeout){break;}
				}
				$this->_buffer.=fgets($this->fp,4096);
			}
			fclose($this->fp);
		}
		$this->_splitcontent();
		$this->_getheaderinfo();
	}

	public function getResponseBody(){
		if($this->getResponseHeader("Content-Encoding")=="gzip" && $this->getResponseHeader("Transfer-Encoding")=="chunked"){
			return gzdecode_1(transfer_encoding_chunked_decode($this->responseBody));
		}else if($this->getResponseHeader("Content-Encoding")=="gzip"){
			return gzdecode_1($this->responseBody);
		}else{
			return $this->responseBody;
		}
	}

	public function getAllResponseHeaders(){
		return 	$this->responseHeader;
	}

	public function getResponseHeader($key){
		$key = str_replace("-","\-",$key);
		$headerstr = $this->responseHeader . "\r\n";
		$count = preg_match_all("/\n$key\:(.+?)\r/is",$headerstr,$result,PREG_SET_ORDER);
		if($count>0){
			$returnstr="";
			foreach($result as $key1=>$value){
				if(strtoupper($key)=="SET\-COOKIE"){
					$value[1] = substr($value[1],0,strpos($value[1],";"));
				}
				$returnstr .= ltrim($value[1]) . "; ";
			}
			$returnstr = substr($returnstr,0,strlen($returnstr)-2);
			return $returnstr;
		}else{return "";}
	}

	public function setTimeout($timeout=0){
		$this->timeout = $timeout;
	}

	public function setRequestHeader($key,$value=""){
		$this->_headers[$key]=$value;
	}

	public function removeRequestHeader($key){
		if(count($this->_headers)==0){return;}
		$_temp=array();
		foreach($this->_headers as $keys => $value){
			if($keys!=$key){
				$_temp[$keys]=$value;
			}
		}
		$this->_headers = $_temp;
	}

	//拆分url
	private function _geturlinfo(){
		$url = $this->url;
		$count = preg_match("/^http\:\/\/([^\:\/]+?)(\:(\d+))?\/(.+?)$/is",$url,$result);
		if($count>0){
			$this->uri="/" . $result[4];
		}else{
			$count = preg_match("/^http\:\/\/([^\:\/]+?)(\:(\d+))?(\/)?$/is",$url,$result);
			if($count>0){
				$this->uri="/";
			}
		}
		if($count>0){
			$this->protocol="http";
			$this->hostname=$result[1];
			if(isset($result[2]) && $result[2]!="") {$this->port=intval($result[3]);}else{$this->port=80;}
			return true;
		}else{$this->excption="url format error";return false;}
	}

	private function _splitcontent(){
		$this->responseHeader="";
		$this->responseBody="";//有Header的返回体
		$this->responseBodyWithoutHeader="";//没有header的XML
		$p1 = strpos($this->_buffer,"\r\n\r\n");
		$p2=strpos($this->_buffer,"<?xml");//第一次出现XML标记的位置
		if($p1>0){
			$this->responseHeader = substr($this->_buffer,0,$p1);
			if($p1+4<strlen($this->_buffer)){
				$this->responseBody = substr($this->_buffer,$p1+4);
			}
		}
		if($p2>1){//构建只有XML的返回体
			if($p2-1<strlen($this->_buffer)){
				$this->responseBodyWithoutHeader = substr($this->_buffer,$p2-1);
			}
		}
	}

	private function _getheaderinfo(){
		$headerstr = $this->responseHeader;
		$count = preg_match("/^HTTP\/(.+?)\s(\d+)\s(.+?)\r\n/is",$headerstr,$result);
		if($count>0){
			$this->HttpProtocolVersion = $result[1];
			$this->status = intval($result[2]);
			$this->statusText = $result[3];
		}
	}
}


//以下两函数参考网络
function gzdecode_1 ($data) {
	$data = ($data);
	if (!function_exists ( 'gzdecode' )) {
		$flags = ord ( substr ( $data, 3, 1 ) );
		$headerlen = 10;
		$extralen = 0;
		$filenamelen = 0;
		if ($flags & 4) {
			$extralen = unpack ( 'v', substr ( $data, 10, 2 ) );
			$extralen = $extralen [1];
			$headerlen += 2 + $extralen;
		}
		if ($flags & 8) // Filename
		$headerlen = strpos ( $data, chr ( 0 ), $headerlen ) + 1;
		if ($flags & 16) // Comment
		$headerlen = strpos ( $data, chr ( 0 ), $headerlen ) + 1;
		if ($flags & 2) // CRC at end of file
		$headerlen += 2;
		$unpacked = @gzinflate ( substr ( $data, $headerlen ) );
		if ($unpacked === FALSE)
		$unpacked = $data;
		return $unpacked;
	}else{
		return gzdecode($data);
	}
}

function transfer_encoding_chunked_decode($in) {
	$out = "";
	while ( $in !="") {
		$lf_pos = strpos ( $in, "\012" );
		if ($lf_pos === false) {
			$out .= $in;
			break;
		}
		$chunk_hex = trim ( substr ( $in, 0, $lf_pos ) );
		$sc_pos = strpos ( $chunk_hex, ';' );
		if ($sc_pos !== false)
		$chunk_hex = substr ( $chunk_hex, 0, $sc_pos );
		if ($chunk_hex =="") {
			$out .= substr ( $in, 0, $lf_pos );
			$in = substr ( $in, $lf_pos + 1 );
			continue;
		}
		$chunk_len = hexdec ( $chunk_hex );
		if ($chunk_len) {
			$out .= substr ( $in, $lf_pos + 1, $chunk_len );
			$in = substr ( $in, $lf_pos + 2 + $chunk_len );
		} else {
			$in = "";
		}
	}
	return $out;
}
function utf8ToGB_bak($str){
	return iconv("utf-8","gbk",$str);
}

function gbToUtf8_bak($str){
	return iconv("gbk","utf-8",$str);
}

?>