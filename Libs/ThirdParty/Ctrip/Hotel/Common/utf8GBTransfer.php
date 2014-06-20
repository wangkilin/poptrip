<?php
/**
 * 
 * 将utf8转换为gbk
 * @param $str
 */
function utf8ToGB($str){
	return iconv("utf-8","gbk",$str);
}
/**
 * 
 * 将gbk转换为utf8
 * @param $str
 */
function gbToUtf8($str){
	return iconv("gbk","utf-8",$str);
}
?>