<?php
/**
 * 获取年月日形式的日期(参数为空，输出2012年12月23日；输入分隔符，则输出2012-12-23形式)
 * cltang 2012年5月11日 携程
 * @param $linkChar
 */
function getDateYMD($linkChar)
{
	date_default_timezone_set('PRC');//设置时区
	$coutValue=strlen($linkChar)>0?date("Y".$linkChar."m".$linkChar."d"):date("Y年m月d日");
	return $coutValue;
}

/**
 * 获取年月日形式的日期(参数为空，输出2012年12月23日；输入分隔符，则输出2012-12-23形式)
 * cltang 2012年5月11日 携程
 * @param $linkChar-分隔符号；
 */
function getDateYMD_addDay($linkChar,$addDay)
{
	$coutValue=strlen($linkChar)>0?date("Y".$linkChar."m".$linkChar."d",strtotime("$d   +$addDay   day")):date("Y年m月d日",strtotime("$d   +$addDay   day"));
	
	return $coutValue;
}
date("Y-m-d",strtotime("$d   +1   day"));

/**
 * 获取年月日时分秒形式的日期(参数为空，输出2012年12月23日 12:23:50；输入分隔符，则输出2012-12-23  12:23:50形式)
 * cltang 2012年5月11日 携程
 * @param $linkChar
 */
function getDateYMDSHM($linkChar)
{
	
	$coutValue=strlen($linkChar)>0?date("Y".$linkChar."m".$linkChar."d H:i:s"):date("Y年m月d日  H:i:s");
	return $coutValue;
}
/**
 * 获取到当前Unix时间戳：分msec sec两个部分，msec是微妙部分，sec是自Unix纪元（1970-1-1 0:00:00）到现在的秒数
 */
function getMicrotime()
{
	return  microtime();
}
/**
 * 
 * 将日期转换为秒
 * @param $datatime
 */
function timeToSecond($datatime)
{
  return	strtotime($datatime);
}
/**
 * 
 * 计算时间的差值：返回{天、小时、分钟、秒}
 * @param $begin_time
 * @param $end_time
 */
function timediff($begin_time,$end_time)
{
     if($begin_time < $end_time){
        $starttime = $begin_time;
        $endtime = $end_time;
     }
     else{
        $starttime = $end_time;
        $endtime = $begin_time;
     }
     $timediff = $endtime-$starttime;
     //echo $begin_time.$end_time.$timediff;
     $days = intval($timediff/86400);
     $remain = $timediff%86400;
     $hours = intval($remain/3600);
     $remain = $remain%3600;
     $mins = intval($remain/60);
     $secs = $remain%60;
     $res = array("day" => $days,"hour" => $hours,"min" => $mins,"sec" => $secs);
     return $res;
}
?>