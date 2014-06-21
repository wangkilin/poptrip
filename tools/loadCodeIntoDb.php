<?php
require_once('./code.php');
$server = 'localhost';
$username = 'root';
$password = '';
$databaseName = 'pop';
$conn = mysql_connect($server, $username,$password);
mysql_select_db($databaseName, $conn);
mysql_query('set names "UTF8"');

$newList = array();
foreach($list as $_info) {
    if(!isset($newList[ $_info[0] ])) {
        $newList[ $_info[0] ] = array();
    }
    $newList[ $_info[0] ] [ $_info[1] ] = array($_info[2], $_info[3]);
}

foreach($newList as $p=>$c) {
    $sql = 'SELECT province_id from province where province_name = "' . $p . '"';
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result, MYSQL_ASSOC);
    $p_code = $row['province_id'];

    foreach($c as $c_name=>$c_info) {
        $shortZipCode = substr($c_info[1], 0, 3);
        if(strlen($c_info[0])>3) {
            error_log("array('$p', '$c_name', '".$c_info[0]."','".$c_info[1]."'),\r\n", 3, 'wrongCode.php');
            unset($c[$c_name]);
            continue;
        }
        $sql = 'select * from city where province_id = ' . $p_code . ' and city_name like "' . $c_name . '%"';
        //echo $sql . "\r\n";
        $cityResult = mysql_query($sql);
        $rowsNum = mysql_num_rows($cityResult);
        if($rowsNum==0) {
            continue;
        }
        if($rowsNum>1) {
            echo $c_info[0] . ' = ' . $c_info[1];
            continue;
        }
        $cityInfo = mysql_fetch_array($cityResult);
        $cityShortZipCode = substr($cityInfo['zip_code'], 0, 3);
        if($shortZipCode!=$cityShortZipCode) {
            error_log("===========".$c_name . '  ' . $cityInfo['zip_code']."'~~~~~~~~~~~~~'".$c_info[1]."'),\r\n", 3, 'wrongCode.php');
        }
        //continue;
        $sql = 'update city set area_code = "'.$c_info[0].'" , zip_code = "' . $c_info[1] . '"
                where city_id = ' . $cityInfo['city_id'];
        //echo $sql . "\r\n";
        mysql_query($sql);
        //if(mysql_affected_rows()) {
            unset($c[$c_name]);
        //}
    }

    foreach($c as $c_name=>$c_info) {
        $sql = 'select * from district d
                inner join city c
                  on d.city_id = c.city_id
                where c.province_id = ' . $p_code . '
                  and d.district_name like "' . $c_name . '%"';
        //echo mb_convert_encoding($sql, 'GBK', 'UTF-8') . "\r\n";
        $cityResult = mysql_query($sql);
        $rowsNum = mysql_num_rows($cityResult);
        if($rowsNum==0) {
            continue;
        }
        if($rowsNum>1) {
            echo $c_info[0] . ' = ' . $c_info[1];
            error_log("~~~~~~~~~~~~~~~".$c_info[0] . ' = ' . $c_info[1]."'~~~~~~~~~~~~~'\r\n", 3, 'wrongCode.php');
            continue;
        }
        $districtInfo = mysql_fetch_array($cityResult);
        //continue;
        $sql = 'update district set area_code = "'.$c_info[0].'" , zip_code = "' . $c_info[1] . '"
                where district_code = ' . $districtInfo['district_code'];
        //echo $sql . "\r\n";
        mysql_query($sql);
        //if(mysql_affected_rows()) {
            unset($c[$c_name]);
        //}
    }

    error_log("\r\n\r\n\r\n", 3, 'wrongCode.php');
    foreach($c as $c_name=>$c_info) {
        error_log("array('$p', '$c_name', '".$c_info[0]."','".$c_info[1]."'),\r\n", 3, 'wrongCode.php');
    }
    error_log("\r\n\r\n\r\n", 3, 'wrongCode.php');
}
