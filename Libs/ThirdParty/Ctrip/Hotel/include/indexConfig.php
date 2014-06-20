<?php
/**
 * 设置和控制index页面上的元素
 */

/**
 * 获取当前选择的默认城市
 */
function setDefaultCityID($DefaultCityID_siteconfig)
{
	$coutw=empty($_REQUEST['defaultcityid']) ? $DefaultCityID_siteconfig : $_REQUEST['defaultcityid'];
	return $coutw;
}