<?php
set_time_limit(0);
$query = null;
$server = 'localhost';
$username = 'root';
$password = '';
$databaseName = 'pop';
$conn = mysql_connect($server, $username,$password);
mysql_select_db($databaseName, $conn);
mysql_query('set names "UTF8"');


function logError ($errorMsg)
{
    //echo $errorMsg . "\r\n";
    error_log($errorMsg. "\r\n", 3, './error.log');

}
function logOk ($okMsg)
{
    //echo $okMsg . "\r\n";
    error_log($okMsg. "\r\n", 3, './ok.log');
}


$url = "http://travel.qunar.com/place/filter/%d?area=%d&month=%d&tag=%d&hot=0";
for($page=1; $page<75; $page++) {
    for($area=1; $area<=7; $area++) {
        $tmpUrl = sprintf($url, $page, $area, 0, 0);
        $content = file_get_contents($tmpUrl);
        if(! strpos($content, '目的地分类导航') ) {
            echo $url.'<br/>';
            continue;
        }
        if(! strpos($content, '<div class="list_itembox">')) continue;

        $content = substr($content,
                          strpos($content, '<div class="list_itembox">'),
                          strpos($content, '<!-- 翻页begin -->')-strpos($content, '<div class="list_itembox">')
                );
        $matchResult = preg_match_all('/"http:\/\/travel\.qunar\.com\/place\/city\/([0-9a-z\-]+)"/Us', $content, $cityLinks);
        if(!$matchResult) $cityLinks = array(array(), array());

        foreach($cityLinks[1] as $cityDir) {
            $sql = "select * from city where city_dir ='" . $cityDir . "'";
            $result = mysql_query($sql);
            if(! ($row=mysql_fetch_array($result))) {
                logError($cityDir);
                continue;
            }
            $sql = "update city set area_id = " . $area . " where city_id = '" . $row['city_id'] . "'";
            $result = mysql_query($sql);
            $sql = "insert into hot_poi (poi_id, poi_type, area_id) values('".$row['city_id']."','3','".$area."')";
            mysql_query($sql);
            $sql = "update hot_poi set area_id = " . $area  . " where poi_id=".$row['city_id']." and poi_type=3";;
            mysql_query($sql);
        }

        $matchResult = preg_match_all('/"http:\/\/travel\.qunar\.com\/place\/poi\/([0-9a-z]+)-([0-9]+)"/Us', $content, $cityLinks);
        if(!$matchResult) $cityLinks = array(array(), array(), array());

        foreach($cityLinks[2] as $cityDir) {
            $sql = "select * from scenery where poi_id ='" . $cityDir . "'";
            $result = mysql_query($sql);
            if(! ($row=mysql_fetch_array($result))) {
                continue;
            }
            $sql = "update scenery set area_id = " . $area . " where scenery_id = '" . $row['scenery_id'] . "'";
            $result = mysql_query($sql);
            $sql = "insert into hot_poi (poi_id, poi_type, area_id) values('".$row['scenery_id']."','4','".$area."')";
            mysql_query($sql);
            $sql = "update hot_poi set area_id = " . $area . " where poi_id=".$row['scenery_id']." and poi_type=4";;
            mysql_query($sql);
        }

    }


    for($month=1; $month<=12; $month++) {
        $tmpUrl = sprintf($url, $page, 0, $month, 0);
        $content = file_get_contents($tmpUrl);
        if(! strpos($content, '目的地分类导航') ) {
            echo $url.'<br/>';
            continue;
        }
        if(! strpos($content, '<div class="list_itembox">')) continue;

        $content = substr($content,
                strpos($content, '<div class="list_itembox">'),
                strpos($content, '<!-- 翻页begin -->')-strpos($content, '<div class="list_itembox">')
        );
        $matchResult = preg_match_all('/"http:\/\/travel\.qunar\.com\/place\/city\/([0-9a-z\-]+)"/Us', $content, $cityLinks);
        if(!$matchResult) $cityLinks = array(array(), array());

        foreach($cityLinks[1] as $cityDir) {
            $sql = "select * from city where city_dir ='" . $cityDir . "'";
            $result = mysql_query($sql);
            if(! ($row=mysql_fetch_array($result))) {
                logError($cityDir);
                continue;
            }
            $sql = "update city set months = months | " . pow(2, $month-1) . " where city_id = '" . $row['city_id'] . "'";
            $result = mysql_query($sql);
            $sql = "insert into hot_poi (poi_id, poi_type, months) values('".$row['city_id']."','3','".$month."')";
            mysql_query($sql);
            $sql = "update hot_poi set months = months | " . pow(2, $month-1) . " where poi_id=".$row['city_id']." and poi_type=3";
            mysql_query($sql);
        }

        $matchResult = preg_match_all('/"http:\/\/travel\.qunar\.com\/place\/poi\/([0-9a-z]+)-([0-9]+)"/Us', $content, $cityLinks);
        if(!$matchResult) $cityLinks = array(array(), array(), array());

        foreach($cityLinks[2] as $cityDir) {
            $sql = "select * from scenery where poi_id ='" . $cityDir . "'";
            $result = mysql_query($sql);
            if(! ($row=mysql_fetch_array($result))) {
                continue;
            }
            $sql = "update scenery set months = months | " . pow(2, $month-1) . " where scenery_id = '" . $row['scenery_id'] . "'";
            $result = mysql_query($sql);
            $sql = "insert into hot_poi (poi_id, poi_type, months) values('".$row['scenery_id']."','4','".$month."')";
            mysql_query($sql);
            $sql = "update hot_poi set months = months | " . pow(2, $month-1) . " where poi_id=".$row['scenery_id']." and poi_type=4";;
            mysql_query($sql);
        }

    }


    for($tag=1; $tag<=19; $tag++) {
        $tmpUrl = sprintf($url, $page, 0, 0, $tag);
        $content = file_get_contents($tmpUrl);
        if(! strpos($content, '目的地分类导航') ) {
            echo $url.'<br/>';
            continue;
        }
        if(! strpos($content, '<div class="list_itembox">')) continue;

        $content = substr($content,
                strpos($content, '<div class="list_itembox">'),
                strpos($content, '<!-- 翻页begin -->')-strpos($content, '<div class="list_itembox">')
        );


        $matchResult = preg_match_all('/"http:\/\/travel\.qunar\.com\/place\/city\/([0-9a-z\-]+)"/Us', $content, $cityLinks);
        if(!$matchResult) $cityLinks = array(array(), array());

        foreach($cityLinks[1] as $cityDir) {
            $sql = "select * from city where city_dir ='" . $cityDir . "'";
            $result = mysql_query($sql);
            if(! ($row=mysql_fetch_array($result))) {
                logError($cityDir);
                continue;
            }
            $sql = "update city set tags = tags | " . pow(2, $tag-1) . " where city_id = '" . $row['city_id'] . "'";
            $result = mysql_query($sql);
            $sql = "insert into hot_poi (poi_id, poi_type, tags) values('".$row['city_id']."','3','".$month."')";
            mysql_query($sql);
            $sql = "update hot_poi set tags = tags | " . pow(2, $tag-1) . " where poi_id=".$row['city_id']." and poi_type=3";
            mysql_query($sql);
        }

        $matchResult = preg_match_all('/"http:\/\/travel\.qunar\.com\/place\/poi\/([0-9a-z]+)-([0-9]+)"/Us', $content, $cityLinks);
        if(!$matchResult) $cityLinks = array(array(), array(), array());

        foreach($cityLinks[2] as $cityDir) {
            $sql = "select * from scenery where poi_id ='" . $cityDir . "'";
            $result = mysql_query($sql);
            if(! ($row=mysql_fetch_array($result))) {
                continue;
            }
            $sql = "update scenery set tags = tags | " . pow(2, $tag-1) . " where scenery_id = '" . $row['scenery_id'] . "'";
            $result = mysql_query($sql);
            $sql = "insert into hot_poi (poi_id, poi_type, tags) values('".$row['scenery_id']."','4','".$month."')";
            mysql_query($sql);
            $sql = "update hot_poi set tags = tags | " . pow(2, $tag-1) . " where poi_id=".$row['scenery_id']." and poi_type=4";;
            mysql_query($sql);
        }

    }

}



/* EOF */