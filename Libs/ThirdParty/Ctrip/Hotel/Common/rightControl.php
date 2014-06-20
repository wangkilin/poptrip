<?php
/**
 * 获取界面输入的联盟信息对应的字符串
 * @生成Heard
 * @$AID=联盟的ID；$SID=联盟站点的ID；$KEYS=联盟的密钥;$RequestType请求服务的类型
 */
function getRightString($AID,$SID,$KEYS,$RequestType)
{
	$Timestamp=TimestampFrom19702();//需要计算的时间戳
	$SecretKeyMd5=MD5Ext_strtoupper2($KEYS);//先加密一次
	$KeyString=$Timestamp.$AID.$SecretKeyMd5.$SID.$RequestType;
	$Signature=MD5Ext_strtoupper2($KeyString);
	return "AllianceID=\"".$AID."\" SID=\"".$SID."\" TimeStamp=\"".$Timestamp."\"  RequestType=\"".$RequestType."\" Signature=\"".$Signature."\"";
}
/**
 * 
 * 通过http请求时需要构造的权限数据
 * @param $AID_INPUT
 * @param $SID_INPUT
 * @param $KEYS
 * @param $RequestType
 */
function getRightStringURL($AID_INPUT,$SID_INPUT,$KEYS,$RequestType)
{
	$Timestamp=TimestampFrom19702();//需要计算的时间戳
	$SecretKeyMd5=MD5Ext_strtoupper2($KEYS);//先加密一次
	$KeyString=$Timestamp.$AID.$SecretKeyMd5.$SID.$RequestType;
	$Signature=MD5Ext_strtoupper2($KeyString);
	return  "SID=".$SID_INPUT."&AllianceID=".$AID_INPUT."&Timestamp=".$Timestamp."&Signature=".$Signature."&RequestType=".$RequestType;
}

/**
 *
 * @param  获取到从1970年1月1日到现在的秒数
 */
function  TimestampFrom19702()
{
	list($usec, $sec) = explode(" ", microtime());
	return ($sec);
}
/**
 * @param $str 将输入的字符串进行MD5加密，同时转换为字母大写
 */
function MD5Ext_Strtoupper2($str)
{
	$coutw=$str;
	if(strlen($str)>0)
	{
		$coutw=strtoupper(md5($str));
	}
	return  $coutw;
}
?>
