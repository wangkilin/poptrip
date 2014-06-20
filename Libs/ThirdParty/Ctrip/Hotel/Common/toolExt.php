<?php
error_reporting(E_ERROR);

/**
 * 根据酒店设施英文名获取对应中文名
 * Enter description here ...
 * @param $enHotelFacility
 */
function cnHotelFacility($enHotelFacility){
	switch ($enHotelFacility) {
		case 'BroadNet':
			return "宽带";
		case 'AirportShuttle':
			return "机场接送";
		case 'Fitnesscenter':
			return "健身中心";
			case 'Swimmingpool':
			return "游泳池";
		case 'Park':
			return "停车场";
		case 'AirCondition':
			return "空调";
		case 'Bar_Lounge':
			return "酒吧";
		case 'Business_center':
			return "商业中心";
		case 'Golf':
			return "高尔夫";
		case 'Poker_Room':
			return "棋牌室";
		default:return "";
			
	} 
}

/**
 * analysisHotelPosition分析酒店坐标的具体位置名 * 
 * @param $localtionZone 坐标名，格式：（行政区ID,行政区名称-商业区ID,商业区名称-景区ID,景区名称）
 * @return 字符串  具体的中文名，如：长宁区
 */
function  analysisHotelPosition($localtionZone){
	$xzq='';
	$syq='';
	$jdm='';
	$t=explode(',', $localtionZone);
	if (count($t)==4){
		$x=explode('-', $t[1]);
		if (count($x)==2){
			$xzq=$x[0];
		}
		$s=explode('-', $t[2]);
		if (count($s)==2){
			$syq=$s[0];
		}
		$jdm=$t[3];
	}
	return $xzq.$syq.$jdm;
}
/**
 * getStarInfo酒店星级转换
 * Enter description here ...
 * @param 整数  $star 1,2,3,4,5
 * @return 字符串 如：五星级、四星级、三星级、二星级及以下
 */
function getStarInfo($star){
	$str="二星级";
	if ($star==5){
		$star="五星级";
	}elseif ($star==4){		
	$star="四星级";
	}elseif ($star==3){		
	$star="三星级";
	}elseif ($star==2){		
	$star="二星级及以下";
	}else{  $star=
	"";		
	}
	return $star;
}

/** 页面跳转
 * @param $url 
 * **/
function redirect($url){
	echo "<script language='javascript' type='text/javascript'>window.location.href='$url'</script>";
}

/** 弹出系统提示消息框
 * @param $content 消息内容
 * 
 * */
function showMsg($content){	 
	echo "<script language=\"JavaScript\">alert(\"$content\");</script>"; 
}
/**
 * 注册js脚本
 * @param $script 脚本
 * */
function registerScript($script){	 
	echo "<script language=\"JavaScript\">$script;</script>"; 
}



/**
 * 
 * 对所以的html元素Encode，包括单引号、双引号
 * @param $value
 *  @param $charset 默认是UTF-8
 */
function htmlEncode($value,$charset='UTF-8'){
	return htmlentities($value, ENT_QUOTES,$charset);
}
/**
 * 对所以的html元素decode，包括单引号、双引号
 * 
 * @param $value
 * @param $charset 默认是UTF-8
 */
function htmlDecode($value,$charset='UTF-8'){
	return html_entity_decode($value, ENT_QUOTES,'UTF-8');
}

/**
 *
 * @param $str 将输入的字符串进行MD5加密，同时转换为字母大写
 */
function MD5Ext_Strtoupper($str)
{
	$coutw=$str;
	if(strlen($str)>0)
	{
		$coutw=strtoupper(md5($str));
	}
	return  $coutw;
}
/**
 *
 * @param  获取到从1970年1月1日到现在的秒数
 */
function  TimestampFrom1970()
{
	list($usec, $sec) = explode(" ", microtime());
	return ($sec);
}
/**
 *
 * @param $inputString 对输入的字符串做安全性过滤（SQL注入过滤，HTML代码转义）
 */
function InputSafeFilter($inputString)
{
	if(strlen($inputString)>0){
		$inputString=sqlFilter($inputString);//作SQL注入的过滤
		$inputString=htmlspecialchars($inputString);//作HTML的转义
	}
	return $inputString;
}
/**
 *
 * @param $str 作SQL注入的过滤
 */
function sqlFilter($str)
{
	if(strlen($str)>0){
		$str = str_replace("and","",$str);
		$str = str_replace("execute","",$str);
		$str = str_replace("update","",$str);
		$str = str_replace("count","",$str);
		$str = str_replace("chr","",$str);
		$str = str_replace("mid","",$str);
		$str = str_replace("master","",$str);
		$str = str_replace("truncate","",$str);
		$str = str_replace("char","",$str);
		$str = str_replace("declare","",$str);
		$str = str_replace("select","",$str);
		$str = str_replace("create","",$str);
		$str = str_replace("delete","",$str);
		$str = str_replace("insert","",$str);
		$str = str_replace("'","",$str);
		$str = str_replace("\"","",$str);
		$str = str_replace(" ","",$str);
		$str = str_replace("or","",$str);
		$str = str_replace("=","",$str);
	}
	return $str;
}
/**
 * 方法:isInt()
 * 功能:判断当前的字符串是否是int型
 * 参数 $str
 * 返回:如果是int型，则返回当前值；如果不是int型，则返回提示信息
 */
function isInt($str)
{
	$coutw=$str;
	if(!is_int($str))
	{
		$coutw=$str.StringFormatNotice."整数";
		if(strlen((int)$str)==strlen($str))
		{
			$coutw=$str;//如果是可以强转的，则还是可以被称为整数的
		}
	}
	return $coutw;
}
/**
 * 方法:isDouble()
 * 功能:判断当前的字符串是否是double型
 * 参数 $str--原数据
 * 返回:如果是double型，则返回当前值的整数部分；如果不是double型，则返回0
 */
function isDouble($str)
{
	$coutw=$str;
	if(!is_double($str))
	{
		$coutw=intval($str);
	}
	else{
		$coutw=0;
	}
	return $coutw;
}
/**
 * 方法:currencyTransition()
 * 功能:将货币的符号进行转换
 * 参数:$inCurrency 原货币符号
 * 返回:&yen;等
 */
function currencyTransition($inCurrency,$HKD='0')
{
	$coutw=$inCurrency;
	if(strtoupper($inCurrency)=="RMB")
	{
		$coutw="&yen;";
	}
	if($HKD=="1")
	{//因港币等部分页面未返回正常的港币币值，这里转化为RMB
		$coutw="&yen;";
	}
	return  $coutw;
}
/**
 * 方法:currencyTransition()
 * 功能:将货币的符号进行转换
 * 参数:$inCurrency 原货币符号
 * 返回:&yen;等
 */
function currencyTransitionFh($inCurrency)
{
	$coutw=$inCurrency;
	if(strtoupper($inCurrency)=="RMB")
	{
		$coutw="￥";
	}
	return  $coutw;
}
/**
 * 方法:htmlTransition()
 * 功能:将转义后的html标签转义回来
 * 参数:$inString 字符串
 * 返回:html格式
 */
function htmlTransition($str)
{
	if(strlen($str)>0){
		$str = str_replace("&lt;","<",$str);
		$str = str_replace("&gt;",">",$str);
		$str = str_replace("&amp;","&",$str);
		$str = str_replace("&quot;","\"",$str);
	}
	return  $str;
}
/**
 *
 * 截取字符串
 * @param $str
 */
function strSubstr($str,$lengths)
{
	if(!empty($str))
	{
		if($lengths>0&& strlen($str)>$lengths)
		{
			$str=substr($str,$lengths);
		}
	}
	return $str;
}
/**
 * 
 * 给字符串增加尾巴
 * @param $str 待处理字符串
 * @param $lenths 满足多长的字符串，需要加尾巴
 * @param $trads 尾巴
 */
function strAddTrad($str,$lenths,$trads)
{
	if(strlen($str)<$lenths){
		$str=$str.$trads;
	}else{
		$str=substr($str,0,$lenths);
	}
	return $str;
}
/******************************************************************
 * PHP截取UTF-8字符串，解决半字符问题。
 * 英文、数字（半角）为1字节（8位），中文（全角）为3字节
 * @return 取出的字符串, 当$len小于等于0时, 会返回整个字符串
 * @param $str 源字符串
 * $len 左边的子串的长度
 ****************************************************************/
function utf_substr($str,$lenth)
{
	$len = strlen($str);
	$r = array ();
	$lenth=$lenth/3;//与以前版本统一
	for($i=0;$i<$len;$i++)
	{
		$x = substr($str, $i, 1);
		$a = base_convert(ord($x), 10, 2);
		$a = substr('00000000' . $a, -8);
		if (substr($a, 0, 1) == 0) {
			$r[] = substr($str, $i, 1);
		}
		elseif (substr($a, 0, 3) == 110) {
			$r[] = substr($str, $i, 2);
			$i += 1;
		}
		elseif (substr($a, 0, 4) == 1110) {
			$r[] = substr($str, $i, 3);
			$i += 2;
		} else {
			$r[] = '';
		}
		if (++ $m >= $lenth) {
			break;
		}
	}
	return implode('', $r) ;
}

/*
function utf_substr($str,$len)
{
	for($i=0;$i<$len;$i++)
	{
		$temp_str=substr($str,0,1);
		if(ord($temp_str) > 127)
		{
			$i++;
			if($i<$len)
			{
				$new_str[]=substr($str,0,3);
				$str=substr($str,3);
			}
		}
		else
		{
			$new_str[]=substr($str,0,1);
			$str=substr($str,1);
		}
	}
	return join($new_str);
}
*/

/**
 * 方法:isdate()
 * 功能:判断日期格式是否正确
 * 参数:$str 日期字符串 $format日期格式
 * 返回:如果是日期型，则返回当前值；如果不是日期型，则返回提示信息
 */
function isdate($str,$format="Y-m-d"){
	$coutw=$str;
	$strArr = explode("-",$str);
	if(empty($strArr)){
		$coutw=$str.StringFormatNotice."日期";
	}
	foreach($strArr as $val){
		if(strlen($val)<2){
			$val="0".$val;
		}
		$newArr[]=$val;
	}
	$str =implode("-",$newArr);
	$unixTime=strtotime($str);
	$checkDate= date($format,$unixTime);
	if($checkDate==$str){
		$coutw=$str;
	}
	else{
		$coutw=$str.StringFormatNotice."日期";
	}
	return  $coutw;
}
/**
 * 方法：GetIP
 * 功能：获取到客户端的IP地址
 * 参数：无
 * 返回：用户的客户端IP地址
 */
function GetIP(){
	/*if(!empty($_SERVER["HTTP_CLIENT_IP"])){
	 $cip = $_SERVER["HTTP_CLIENT_IP"];
	 }
	 elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
	 $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	 }
	 elseif(!empty($_SERVER["REMOTE_ADDR"])){
	 $cip = $_SERVER["REMOTE_ADDR"];
	 }
	 else{
	 $cip = "0.0.0.0";
	 }
	 if(strpos($cip,','))
	 {
	 $arrayCip=explode(‘,’,$cip);
	 echo json_encode($arrayCip);
	 $cip=$arrayCip(strlen($arrayCip)-1);
	 }
	 return $cip;*/

	if( !empty( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ){
		$REMOTE_ADDR = $_SERVER["HTTP_X_FORWARDED_FOR"];
		$tmp_ip = explode( ",", $REMOTE_ADDR );
		$REMOTE_ADDR = $tmp_ip[0];
	}
	return empty( $REMOTE_ADDR ) ? ( $_SERVER["REMOTE_ADDR"] ) : ( $REMOTE_ADDR ) ;
}
/**
 *
 * 做早餐的文字转换
 * @param $HasBreakfast
 */
function getBreakFastName($HasBreakfast)
{
	$coutw="";
	if($HasBreakfast=="0")
	{
		$coutw="无早";
	}
	else if($HasBreakfast=="1")
	{
		$coutw="单早";
	}
	else if($HasBreakfast=="2")
	{
		$coutw="双早";
	}
	else
	{
		$coutw=$HasBreakfast."早";
	}
	return $coutw;
}
/**
 *
 * 做床型名称的转换
 * @param $BedType
 */
function getBedTypeName($BedType)
{
	$coutw= str_replace("人床","",$BedType);
	$coutw= str_replace("床","",$coutw);
	if($coutw=="大"||$coutw=="双"||$coutw=="单")
	{
		$coutw=$coutw."床";
	}
	return $coutw;
}
function getBedTypeNameIndex($BedType)
{
	$coutw= str_replace("人床","/",$BedType);
	$coutw= str_replace("床","/",$coutw);
	//把最后一个 /去掉
	if(strlen($coutw)>1)
	{
		$coutw=substr($coutw,0,strlen($coutw)-1);
	}
	if($coutw=="大"||$coutw=="双"||$coutw=="单")
	{
		$coutw=$coutw."床";
	}
	$coutw= str_replace("//","/",$coutw);
	return  $coutw;
}
/**
 *
 * 获取有线和无线的名称
 * @param $HasWirelessBroadnet
 * @param $HasWiredBroadnet
 */
function getWireName($HasWirelessBroadnet,$HasWiredBroadnet)
{
	$coutw="收费";
	if($HasWirelessBroadnet=="T"||$HasWiredBroadnet=="T")
	{
		$coutw="免费";
	}
	if(empty($HasWirelessBroadnet)&&empty($HasWiredBroadnet))
	{
		$coutw="无";
	}

	return $coutw;
}
function filterHtmls($str)
{
	$str=str_replace("<b>",",",$str);
	$str=str_replace("</b>",",",$str);
	return $str;
}
/*构造赞的数据*/
function getHotelIDLastChar($hotelID,$supportNum)
{
	//0.先从hotelID中截取出后2位数字
	$hotelIDDouble=$hotelID/100.00;
	$x=explode(".",$hotelIDDouble);
	$intCout=$x[1];
	if($intCout==0||$intCout<10)
	{
		$intCout=10;//后2位为00，和后2位在01--09之间
	}
	else
	{
		$intCout=$intCout;
	}

	//1.没有赞的数据
	if(empty($supportNum))
	{
		$supportNum=0;
	}
	//2.有赞的数据

	$supportNum=$intCout+$supportNum;

	return  $supportNum;
}
/*根据当前的日期，判断显示返现的图标*/
function getGifValue($startTime,$endTime,$values)
{
	$coutw="";
	if(date("Y-m-d",strtotime($startTime))<=date("Y-m-d",time())&&date("Y-m-d",strtotime($endTime))>=date("Y-m-d",time()))
	{
		$coutw="<span class=\"icon_refund\">".isDouble($values)."元</span>";
	}

	return $coutw;
}
/**
 *
 * $var 从输入的日期中获取到年份数据
 * @param $date
 */
function getYearFromDate($date)
{
	$coutw="";
	if(strpos($date,'-')>0)
	{
		$dateArray=explode("-",$date);
		$coutw=$dateArray[0];
	}
	if(strpos($date,'/')>0)
	{
		$dateArray2=explode("/",$date);
		$coutw=$dateArray2[0];
	}
	if(strpos($date,'年')>0)
	{
		$dateArray3=explode("年",$date);
		$coutw=$dateArray3[0];
	}
	return $coutw;
}
/**
 *
 * $var 获取酒店星级
 * @param $level
 */

function get_class_name($level){
	
	$arrayCE=explode(".",$level);
	if($arrayCE[1]>0)
	{
		$customerleve=$arrayCE[0]+1;
		$className="half_diamond0".$customerleve;
	}
	else {
		$className="diamond0".$arrayCE[0];
	}
	return $className;
}

/**
 * 
 * 酒店类型
 * @param 酒店星级 $start
 * @return string
 */


Function getStartName($star)
{

	if($star>=5)  $star= "豪华";
	elseif($star>=4 && $star<5)$star="高档";
	elseif($star>=3 && $star<4)  $star="舒适";
	else   $star="经济";

	return $star;	

}


/**
 *酒店类型
 * @param 酒店星级 $start
 * @return string
 */

function  get_star_info($Star,$Rstar){
	
	If($Star>3)
	{
		$StarInfo[]="国家旅游局评定为".substr($Star,0,'3')."级（用户评定为".substr($Rstar,0,'3')."钻）";
		$StarInfo[]="star star_".$Star;//get_class_name($Star,'');
	}Else{
		$StarInfo[]=getStartName($Rstar)."型"."（用户评定为".substr($Rstar,0,'3')."钻）";
		$StarInfo[]=get_class_name($Rstar);
	}

	return $StarInfo;
}

function removePicWaterMark($url){
	//水印图片变为无水印
	$is_exist = is_int(strpos($url,'am'));
	if($is_exist==false){
		$urlDatas=explode('_', $url);
		$nums=count($urlDatas);
		$endUrl=$urlDatas[$nums-2]."_".$urlDatas[$nums-1];
		$startUrl=str_replace($endUrl,"",$url);
		if($startUrl&&$endUrl)
		$url=$startUrl."am_".$endUrl;
		
	}
	return $url;
}


/**
 *
 * $var 获取时间差
 * @param $Dif
 */

function DifTime($DifSeconds){
	$hour=intval(($DifSeconds)/3600);
	$min=intval(($DifSeconds-$hour*3600)/60);
	if($DifSeconds<60)$min=1;
	$DifTime=$hour?$hour."小时".$min."分钟":$min."分钟";
	
	return $DifTime;
}



?>