<?php 
/**
 * macms 公用函数库
 * ============================================================================
 * 版权所有 Walton Zhang。QQ:31323185
 * 网站地址: http://www.t-mac.org；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: zhangwentao $
 * $Id: function.inc.php 1403 2011-03-03 10:18:16Z zhangwentao $
*/

if(!defined('ROOTINC')) exit('Access Denied!');

function daddslashes($string, $force = 0) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force);
			}
		} else {
			$string = addslashes($string);
		}
	}
	return $string;
}

//FILES文件也转义
if (!MAGIC_QUOTES_GPC && $_FILES) {
	$_FILES = daddslashes($_FILES);
}

// 操作提示页面
function redirect($msg, $url = 'javascript:history.go(-1);', $min='4') {
	global  $template;
    $time = 2;
	include (ROOTINC.'/redirect.html');
    exit();
}

function jump( $msg, $url = 'javascript:history.go(-1);', $min='2')
{
	global  $template;
	include template('jump');
}

function ShowMsg($msg, $code, $url = '', $win = 'self')
  {
	@header ('Content-Type: text/html; charset=utf8');
    if ($code == 1)
    {
	 echo '<script language="javascript">alert("' . $msg . '");history.back();</script>';
    }
      elseif ($code == 2)
      {
        echo '<script language="javascript">alert("' . $msg . '");window.close();</script>';
      }
	  elseif ($code == 3)
    {
          echo '<script language="javascript">alert("' . $msg . '");this.document.location="' . $url . '";</script>';
    }
    exit ();
  }

//截取中文字符串
/* 
Utf-8、gb2312都支持的汉字截取函数 
cut_str(字符串, 截取长度, 开始长度, 编码); 
编码默认为 utf-8 
开始长度默认为 0 
*/ 
 
function cut_str($string, $sublen, $start = 0, $code = 'UTF-8') 
{ 
    if($code == 'UTF-8') 
    { 
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/"; 
        preg_match_all($pa, $string, $t_string); 
 
        if(count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen))."..."; 
        return join('', array_slice($t_string[0], $start, $sublen)); 
    } 
    else 
    { 
        $start = $start*2; 
        $sublen = $sublen*2; 
        $strlen = strlen($string); 
        $tmpstr = ''; 
 
        for($i=0; $i< $strlen; $i++) 
        { 
            if($i>=$start && $i< ($start+$sublen)) 
            { 
                if(ord(substr($string, $i, 1))>129) 
                { 
                    $tmpstr.= substr($string, $i, 2); 
                } 
                else 
                { 
                    $tmpstr.= substr($string, $i, 1); 
                } 
            } 
            if(ord(substr($string, $i, 1))>129) $i++; 
        } 
        if(strlen($tmpstr)< $strlen ) $tmpstr.= "..."; 
        return $tmpstr; 
    } 
} 
/**
 *模板调用函数
 */
 function template($filename){     
	global $tpl;
	$file = $tpl->getfile($filename);
	return $file;
 }
?>