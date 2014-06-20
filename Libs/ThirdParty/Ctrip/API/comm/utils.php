<?php

/* PHP SDK
 * @version 2.0.0
 * @author magicsky0@163.com
 * @copyright © 2014, Ctrip Corporation. All rights reserved.
 */

//trigger_error默认错误处理
set_error_handler('my_error_handler');
function my_error_handler($err_no, $err_str, $err_file, $err_line)
{
	if( !CU_DEVELOP ) return;
	switch ($err_no)
	{
		case E_ERROR:
			echo("<b>ERROR:</b> $err_str in $err_file on line $err_line<br>\n");
			exit(1);
			break;
		case E_WARNING:
			echo("<b>WARNING:</b> $err_str in $err_file on line $err_line<br>\n");
			break;
		case E_NOTICE:
			echo("<b>NOTICE:</b> $err_str in $err_file on line $err_line<br>\n");
			break;
		case E_USER_ERROR:
			echo("<b>USER ERROR:</b> $err_str in $err_file on line $err_line<br>\n");
			exit(1);
			break;
		case E_USER_WARNING:
			echo("<b>USER WARNING:</b> $err_str in $err_file on line $err_line<br>\n");
			break;
		case E_USER_NOTICE:
			echo("<b>USER NOTICE:</b> $err_str in $err_file on line $err_line<br>\n");
			break;
		default:
			echo("<b>UNKNOWN:</b> $err_str in $err_file on line $err_line<br>\n");
			break;
	}
}

function debugger($body, $header)
{
	if( !CU_DEVELOP )
	{
		return;
	}

	echo "<meta charset='utf-8' />";
	echo '<hr/>'.$header.':';
	var_dump($body);
	echo '<hr/>';
}

function do_post($url, $data)
{    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    curl_setopt($ch, CURLOPT_POST, TRUE); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
    curl_setopt($ch, CURLOPT_URL, $url);
    $ret = curl_exec($ch);

    curl_close($ch);
    return $ret;
}

function get_url_contents($url)
{
    if (ini_get("allow_url_fopen") == "1")
    {
    	return file_get_contents($url);
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result =  curl_exec($ch);
    curl_close($ch);

    return $result;
}

if( !function_exists('gzdecode') )
{
	function gzdecode($data) 
	{
		$flags = ord ( substr ( $data, 3, 1 ) );
		$headerlen = 10;
		$extralen = 0;
		$filenamelen = 0;
		
		if ($flags & 4) 
		{
			$extralen = unpack ( 'v', substr ( $data, 10, 2 ) );
			$extralen = $extralen [1];
			$headerlen += 2 + $extralen;
		}
		
		if ($flags & 8) // Filename
		{	
			$headerlen = strpos ( $data, chr ( 0 ), $headerlen ) + 1;
		}
		
		if ($flags & 16) // Comment
		{
			$headerlen = strpos ( $data, chr ( 0 ), $headerlen ) + 1;
		}
		
		if ($flags & 2) // CRC at end of file
		{
			$headerlen += 2;
		}
		
		$unpacked = @gzinflate ( substr ( $data, $headerlen ) );
		if ($unpacked === FALSE)
		{
			$unpacked = $data;
		}
		
		return $unpacked;
	}
}

/**
 * HTTP中transfer-coding域值为chunked对报文解码
 * @param string $in
 */
if( !function_exists('http_chunked_decode') )
{
	function http_chunked_decode($in) 
	{
		$out = "";
		while ( $in !="") 
		{
			$lf_pos = strpos ( $in, "\012" );
			if ($lf_pos === false) 
			{
				$out .= $in;
				break;
			}
			
			$chunk_hex = trim ( substr ( $in, 0, $lf_pos ) );
			$sc_pos = strpos ( $chunk_hex, ';' );
			
			if ($sc_pos !== false) $chunk_hex = substr ( $chunk_hex, 0, $sc_pos );
			
			if ($chunk_hex =="") 
			{
				$out .= substr ( $in, 0, $lf_pos );
				$in = substr ( $in, $lf_pos + 1 );
				continue;
			}
			
			$chunk_len = hexdec ( $chunk_hex );
			
			if ($chunk_len) 
			{
				$out .= substr ( $in, $lf_pos + 1, $chunk_len );
				$in = substr ( $in, $lf_pos + 2 + $chunk_len );
			} 
			else  $in = "";
		}
		return $out;
	}
}

if( !function_exists('json_encode') )
{
    require CU_COMM_PATH.'json.php';
    function json_encode($val)
    {
        $json = new Services_JSON();
        return $json->encode($val);
    }
    
    function json_decode($val)
    {
        $json = new Services_JSON();
        return $json->decode($val);
    }
}

function crypt_encode($input, $key)
{
	$input = str_replace("\n", "", $input);
	$input = str_replace("\t", "", $input);
	$input = str_replace("\r", "", $input);
	$key = substr(md5($key), 0, 24);
	$td = mcrypt_module_open('tripledes', '', 'ecb', '');
	$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	mcrypt_generic_init($td, $key, $iv);
	$encrypted_data = mcrypt_generic($td, $input);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
	return trim(chop(base64_encode($encrypted_data)));
}
   
function crypt_decode($input, $key)
{
	$input = str_replace("\n", "", $input);
	$input = str_replace("\t", "", $input);
	$input = str_replace("\r", "", $input);
	$input = trim(chop(base64_decode($input)));
	$td = mcrypt_module_open('tripledes', '', 'ecb', '');
	$key = substr(md5($key), 0, 24);
	$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
	mcrypt_generic_init($td, $key, $iv);
	$decrypted_data = mdecrypt_generic($td, $input);
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
	return trim(chop($decrypted_data));
} 

//DES加密，返回值使用base64重编码
function des_encode($string, $key)
{
	$size = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC);
    $pad = $size - (strlen($string) % $size);
    $string .= str_repeat(chr($pad), $pad);
	return base64_encode(mcrypt_cbc(MCRYPT_DES, $key, $string, MCRYPT_ENCRYPT, $key));
}


//DES解密 输入值是base64重编码过的
function des_decode($string, $key)
{
	$string = base64_decode($string);
	$text = mcrypt_cbc(MCRYPT_DES, $key, $string, MCRYPT_DECRYPT, $key);
	
	$pad = ord($text{strlen($text) - 1});
	if ($pad > strlen($text))
	{
		return false;
	}
	if (strspn($text, chr($pad), strlen($text) - $pad) != $pad)
	{
		return false;
	} 
	
	return substr($text, 0, -1 * $pad);
}