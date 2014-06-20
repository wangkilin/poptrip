<?php
/********************************************************/
/*****                 @!!@                          ****/
/********************************************************/
/**
 *@FileName :
 *@Author   : WangKilin
 *@Date     :
 *@Version  : 0.1
 */
//error_reporting(E_ERROR);
$server = 'localhost';
$username = 'root';
$password = '';
$databaseName = 'pop';
'http://travel.qunar.com/place/api/poi/image?poiId=710431&offset=100&limit=100';
"ALTER TABLE `scenery` ADD `poi_id` INT( 8 ) NOT NULL COMMENT 'id on qunar.com.'";
"ALTER TABLE `scenery` ADD `pic_loaded` BOOLEAN NOT NULL COMMENT 'if the picture has been captured'";
"ALTER TABLE `scenery` ADD `guide_map` VARCHAR( 255 ) NOT NULL COMMENT 'guide map pic path' AFTER `percent`";
"ALTER TABLE `scenery_img` ADD `pic_loaded` BOOLEAN NOT NULL COMMENT 'if the picture has been captured'";
"
CREATE TABLE IF NOT EXISTS `scenery_img` (
  `img_id` int(11) NOT NULL AUTO_INCREMENT,
  `img_path` varchar(255) NOT NULL,
  `img_url` varchar(255) NOT NULL,
  `img_width` int(5) NOT NULL,
  `img_height` int(5) NOT NULL,
  `icon_path` varchar(255) NOT NULL,
  `small_path` varchar(255) NOT NULL,
  `middle_path` varchar(255) NOT NULL,
  `big_path` varchar(255) NOT NULL,
  `icon_url` varchar(255) NOT NULL,
  `small_url` varchar(255) NOT NULL,
  `middle_url` varchar(255) NOT NULL,
  `big_url` varchar(255) NOT NULL,
  `scenery_id` int(11) NOT NULL,
  `show_in_province` tinyint(1) NOT NULL,
  `show_in_city` tinyint(1) NOT NULL,
  `show_in_country` tinyint(1) NOT NULL,
  `pic_loaded` tinyint(1) NOT NULL COMMENT 'if the picture has been captured',
  PRIMARY KEY (`img_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$countryList = $provinceList = $cityList = $sceneryList = array();
$query = null;

global $countryList, $provinceList, $cityList, $sceneryList;
global $query;

function logError ($errorMsg)
{
    echo $errorMsg . "\r\n";
}
function logOk ($okMsg)
{
    echo $okMsg . "\r\n";
}

function loadDataFromDb()
{
    global $countryList, $provinceList, $cityList, $sceneryList;
    global $server, $username, $password, $databaseName;

    $conn = mysql_connect($server, $username, $password);
    mysql_select_db($databaseName, $conn);
    mysql_query('set names "UTF8"');
    $query = 'SELECT * FROM country';
    $result = mysql_query($query);
    while(($row=mysql_fetch_assoc($result))) {
        $countryList[$row['country_name']] = $row['country_id'];
    }
    $query = 'SELECT * FROM province';
    $result = mysql_query($query);
    while(($row=mysql_fetch_assoc($result))) {
        $provinceList[$row['country_id'] . '-' .$row['province_name']] = $row['province_id'];
    }
    $query = 'SELECT * FROM city';
    $result = mysql_query($query);
    while(($row=mysql_fetch_assoc($result))) {
        $cityList[$row['province_id'] . '-' .$row['city_name']] = $row['city_id'];
    }
    $query = 'SELECT * FROM scenery';
    $result = mysql_query($query);
    while(($row=mysql_fetch_assoc($result))) {
        if(strpos($row['scenery_name'], '（')) {
        }
        $sceneryList[$row['city_id'] . '-' .$row['scenery_name']] = $row['scenery_id'];
    }
}

function addCountry ($countryName)
{
    global $countryList;
    global $query;

    $query = "INSERT INTO country (country_name) VALUES('" . mysql_real_escape_string(trim($countryName)) . "')";
    mysql_query($query);

    $countryId = mysql_insert_id();
    $countryList[$countryName] = $countryId;

    return $countryId;
}

function updateCountry ($countryId, $countryInfo)
{
    global $query;
    $sets = array();
    if(isset($countryInfo['playDays'])) {
        $sets[] = " play_day ='" . mysql_real_escape_string($countryInfo['playDays']) . "'";
    }
    if(isset($countryInfo['goodSeason'])) {
        $sets[] = " good_season ='" . mysql_real_escape_string($countryInfo['goodSeason']) . "'";
    }
    if(isset($countryInfo['intro'])) {
        $sets[] = " summary ='" . mysql_real_escape_string($countryInfo['intro']) . "'";
    }
    if(isset($countryInfo['season'])) {
        $sets[] = " season ='" . mysql_real_escape_string($countryInfo['season']) . "'";
    }
    if(isset($countryInfo['comm'])) {
        $sets[] = " traffic ='" . mysql_real_escape_string($countryInfo['comm']) . "'";
    }
    if(isset($countryInfo['useful'])) {
        $sets[] = " useful_info ='" . mysql_real_escape_string($countryInfo['useful']) . "'";
    }
    if(isset($countryInfo['tip'])) {
        $sets[] = " tip ='" . mysql_real_escape_string($countryInfo['tip']) . "'";
    }
    if(isset($countryInfo['money'])) {
        $sets[] = " currency ='" . mysql_real_escape_string($countryInfo['money']) . "'";
    }

    $return = false;
    if($sets) {
        $query = "UPDATE country SET " . join(', ', $sets) . "WHERE country_id = " . intval($countryId);
        mysql_query($query);
        $return = mysql_errno() === 0;
    }

    return $return;
}

function addProvince ($provinceName, $countryId)
{
    global $provinceList;
    global $query;

    $query = "INSERT INTO province (province_name, country_id) VALUES('" . mysql_real_escape_string(trim($provinceName)) . "', '".intval($countryId)."')";
    mysql_query($query);

    $provinceId = mysql_insert_id();
    $provinceList[$countryId . '-' . $provinceName] = $provinceId;

    return $provinceId;
}

function updateProvince ($provinceId, $provinceInfo)
{
    global $query;
    $sets = array();
    if(isset($provinceInfo['playDays'])) {
        $sets[] = " play_day ='" . mysql_real_escape_string($provinceInfo['playDays']) . "'";
    }
    if(isset($provinceInfo['goodSeason'])) {
        $sets[] = " good_season ='" . mysql_real_escape_string($provinceInfo['goodSeason']) . "'";
    }
    if(isset($provinceInfo['intro'])) {
        $sets[] = " summary ='" . mysql_real_escape_string($provinceInfo['intro']) . "'";
    }
    if(isset($provinceInfo['season'])) {
        $sets[] = " season ='" . mysql_real_escape_string($provinceInfo['season']) . "'";
    }
    if(isset($provinceInfo['comm'])) {
        $sets[] = " traffic ='" . mysql_real_escape_string($provinceInfo['comm']) . "'";
    }
    if(isset($provinceInfo['tip'])) {
        $sets[] = " tip ='" . mysql_real_escape_string($provinceInfo['tip']) . "'";
    }
    if(isset($provinceInfo['countryId'])) {
        $sets[] = " country_id ='" . mysql_real_escape_string($provinceInfo['countryId']) . "'";
    }

    $return = false;
    if($sets) {
        $query = "UPDATE province SET " . join(', ', $sets) . "WHERE province_id = " . intval($provinceId);
        mysql_query($query);
        $return = mysql_errno() === 0;
    }

    return $return;
}

function addCity ($cityName, $provinceId)
{
    global $cityList;
    global $query;

    $query = "INSERT INTO city (city_name, province_id) VALUES('" . mysql_real_escape_string(trim($cityName)) . "', '".intval($provinceId)."')";
    mysql_query($query);

    $cityId = mysql_insert_id();
    $cityList[$provinceId . '-' . $cityName] = $cityId;

    return $provinceId;
}

function updateCity ($cityId, $cityInfo)
{
    global $query;
    $sets = array();
    if(isset($cityInfo['intro'])) {
        $sets[] = " summary ='" . mysql_real_escape_string($cityInfo['intro']) . "'";
    }
    if(isset($cityInfo['comm'])) {
        $sets[] = " traffic ='" . mysql_real_escape_string($cityInfo['comm']) . "'";
    }
    if(isset($cityInfo['useful'])) {
        $sets[] = " useful_info ='" . mysql_real_escape_string($cityInfo['useful']) . "'";
    }
    if(isset($cityInfo['tip'])) {
        $sets[] = " tip ='" . mysql_real_escape_string($cityInfo['tip']) . "'";
    }
    if(isset($cityInfo['longitude'])) {
        $sets[] = " longitude ='" . mysql_real_escape_string($cityInfo['longitude']) . "'";
    }
    if(isset($cityInfo['latitude'])) {
        $sets[] = " latitude ='" . mysql_real_escape_string($cityInfo['latitude']) . "'";
    }
    if(isset($cityInfo['playDays'])) {
        $sets[] = " play_day ='" . mysql_real_escape_string($cityInfo['playDays']) . "'";
    }
    if(isset($cityInfo['goodSeason'])) {
        $sets[] = " good_season ='" . mysql_real_escape_string($cityInfo['goodSeason']) . "'";
    }
    if(isset($cityInfo['season'])) {
        $sets[] = " season ='" . mysql_real_escape_string($cityInfo['season']) . "'";
    }
    if(isset($cityInfo['provinceId'])) {
        $sets[] = " auto_province_id ='" . mysql_real_escape_string($cityInfo['provinceId']) . "'";
    }

    $return = false;
    if($sets) {
        $query = "UPDATE city SET " . join(', ', $sets) . "WHERE city_id = " . intval($cityId);
        mysql_query($query);
        $return = mysql_errno() === 0;
    }

    return $return;
}

function addScenery ($sceneryName, $cityId)
{
    global $sceneryList;
    global $query;

    $query = "INSERT INTO scenery (scenery_name, city_id) VALUES('" . mysql_real_escape_string(trim($sceneryName)) . "', '".intval($cityId)."')";
    mysql_query($query);

    $sceneryId = mysql_insert_id();
    $sceneryList[$cityId . '-' . $sceneryName] = $sceneryId;

    return $sceneryId;
}

function updateScenery ($sceneryId, $sceneryInfo)
{
    global $query;
    $sets = array();
    if(isset($sceneryInfo['playTime'])) {
        $sets[] = " play_time ='" . mysql_real_escape_string($sceneryInfo['playTime']) . "'";
    }
    if(isset($sceneryInfo['cityId'])) {
        $sets[] = " auto_city_id ='" . mysql_real_escape_string($sceneryInfo['cityId']) . "'";
    }
    if(isset($sceneryInfo['provinceId'])) {
        $sets[] = " auto_province_id ='" . mysql_real_escape_string($sceneryInfo['provinceId']) . "'";
    }
    if(isset($sceneryInfo['score'])) {
        $sets[] = " score ='" . mysql_real_escape_string($sceneryInfo['score']) . "'";
    }
    if(isset($sceneryInfo['rank'])) {
        $sets[] = " rank ='" . mysql_real_escape_string($sceneryInfo['rank']) . "'";
    }
    if(isset($sceneryInfo['tip'])) {
        $sets[] = " tip ='" . mysql_real_escape_string($sceneryInfo['tip']) . "'";
    }
    if(isset($sceneryInfo['longitude'])) {
        $sets[] = " longitude ='" . mysql_real_escape_string($sceneryInfo['longitude']) . "'";
    }

    if(isset($sceneryInfo['latitude'])) {
        $sets[] = " latitude ='" . mysql_real_escape_string($sceneryInfo['latitude']) . "'";
    }
    if(isset($sceneryInfo['aboutInfo'])) {
        $sets[] = " summary ='" . mysql_real_escape_string($sceneryInfo['aboutInfo']) . "'";
    }
    if(isset($sceneryInfo['ticketInfo'])) {
        $sets[] = " ticket ='" . mysql_real_escape_string($sceneryInfo['ticketInfo']) . "'";
    }
    if(isset($sceneryInfo['seasonInfo'])) {
        $sets[] = " season ='" . mysql_real_escape_string($sceneryInfo['seasonInfo']) . "'";
    }
    if(isset($sceneryInfo['commInfo'])) {
        $sets[] = " traffic ='" . mysql_real_escape_string($sceneryInfo['commInfo']) . "'";
    }
    if(isset($sceneryInfo['tipInfo'])) {
        $sets[] = " tip ='" . mysql_real_escape_string($sceneryInfo['tipInfo']) . "'";
    }
    if(isset($sceneryInfo['percent'])) {
        $sets[] = " percent ='" . mysql_real_escape_string($sceneryInfo['percent']) . "'";
    }
    if(isset($sceneryInfo['poiId'])) {
        $sets[] = " poi_id ='" . mysql_real_escape_string($sceneryInfo['poiId']) . "'";
    }



    $return = array(
            'bigPic'=>$sceneryInfo['bigPic'],
            'thumbs'=>$sceneryInfo['thumbs'],
    );

    $return = false;
    if($sets) {
        $query = "UPDATE scenery SET " . join(', ', $sets) . "WHERE scenery_id = " . intval($sceneryId);
        mysql_query($query);
        $return = mysql_errno() === 0;
    }

    return $return;
}

function addPic ($picPath, $sceneryId)
{
    global $query;

    $query = "INSERT INTO picture (pic_path, scenery_id) VALUES('" . mysql_real_escape_string(trim($picPath)) . "', '".intval($sceneryId)."')";
    mysql_query($query);

    return mysql_insert_id();
}






$dir = './';
function parseQunarCity ($dir)
{
    global $countryList, $provinceList, $cityList;
    $dir = realpath($dir);
    if(! is_dir($dir)) return;

    $dirIterator = new DirectoryIterator($dir);
    foreach($dirIterator as $_dir) {

        if($_dir->isDot()) continue;

        //logError ($_dir->getPath();
        if($_dir->isDir()) {
            continue;
            //parseQunar($_dir->getPathname());
        }

        if($_dir->isFile()) {
        //$domIterator = new Docoument
            $detailDir = $dir . DIRECTORY_SEPARATOR . $_dir->getBasename('.html');
            $filepath = $dir . DIRECTORY_SEPARATOR .$_dir->getFilename();

            logOk('=== START ===' . $filepath);
            $cityInfo = parseCity($filepath);
            if(is_dir($detailDir)) {
                $cityIntro = parseCityIntro($detailDir . DIRECTORY_SEPARATOR . 'du.html');
                $citySeason = parseCityTripSeason($detailDir . DIRECTORY_SEPARATOR . 'du~typeId=415.html');
                $cityCommunication = parseCityCommunication($detailDir . DIRECTORY_SEPARATOR . 'du~typeId=407.html');
                $cityUsefulInfo = parseCityUsefulInfo($detailDir . DIRECTORY_SEPARATOR . 'du~typeId=419.html');
                $cityTip = parseCityTips($detailDir . DIRECTORY_SEPARATOR . 'du~typeId=406.html');
            } else {
                $cityIntro = '';
                $citySeason = '';
                $cityCommunication = '';
                $cityUsefulInfo = '';
                $cityTip = '';
                logError ($filepath . " has no detail folder<br/>");
            }

            $countryId = isset($countryList[$cityInfo['country']]) ? $countryList[$cityInfo['country']] : addCountry($cityInfo['country']);
            $countryList[$cityInfo['country']] = $countryId;
            $provinceId = isset($provinceList[$countryId.'-'.$cityInfo['province']]) ? $provinceList[$countryId.'-'.$cityInfo['province']] : addProvince($cityInfo['province'], $countryId);
            $provinceList[$countryId.'-'.$cityInfo['province']] = $provinceId;
            $cityId = isset($cityList[$provinceId.'-'.$cityInfo['city']]) ? $cityList[$provinceId.'-'.$cityInfo['city']] : addCity($cityInfo['city'], $provinceId);
            $cityList[$provinceId.'-'.$cityInfo['city']] = $cityId;

            $return = array('info'=>$cityInfo,
                            'intro'=>$cityIntro,
                            'season'=>$citySeason,
                            'comm'=>$cityCommunication,
                            'useful'=>$cityUsefulInfo,
                            'tip'=>$cityTip,
                            'countryId'=>$countryId,
                            'provinceId'=>$provinceId,
                            'longitude'=>$cityInfo['longitude'],
                            'latitude'=>$cityInfo['latitude'],
                            'goodSeason'=>$cityInfo['goodSeason'],
                            'playDays'=>$cityInfo['playDays'],
                            'city'=>$cityInfo['city']
                            );
            if(updateCity($cityId, $return)) {
                logOk('=== END === ' . $cityInfo['city'] . ' ==== ' . $filepath . "\r\n");
            } else {
                logError($GLOBALS['query']);
                logError('*** END ***' . $filepath . " failed to update city \r\n");
            }
        }
    }
}

function parseCity ($filepath)
{
    if(! is_file($filepath)) return;

    $content = file_get_contents($filepath);
    if(! preg_match('/<div class="e_crumbs">.*<\/div>/Us', $content, $match)) {
        logError ($filepath . " has no destination<br/>");
        return;
    }
    if(! preg_match('/var PRE_DEST_NAME = "(.*)";/Us', $content, $matchCity)) {
        logError ($filepath . " has no city data<br/>");
        return;
    }
    if(! preg_match('/var PRE_LNG = "(.*)";/Us', $content, $matchLongitude)) {
        logError ($filepath . " has no longitude data<br/>");
        return;
    }
    if(! preg_match('/var PRE_LAT = "(.*)";/Us', $content, $matchLatitude)) {
        logError ($filepath . " has no latitude data<br/>");
        return;
    }
    if(! preg_match('/<div class="e_cover_des_r">(.*)<\/div>/Us', $content, $matchGoodSeason)) {
        logError ($filepath . " has no good season data<br/>");
        return;
    }
    $city = $matchCity[1];
    $longitude = $matchLongitude[1];
    $latitude  = $matchLatitude[1];
    $goodSeasons = explode("\n", $matchGoodSeason[1]);
    $goodSeason = '';
    $playDays = 0;
    foreach($goodSeasons as $_key=>$_season) {
        $_season = trim($_season);
        if($_season=='') unset($goodSeasons[$_key]);
        if(strpos($_season, '最佳旅游时节：')===0) {
            $goodSeason = strip_tags(substr($_season, strlen('最佳旅游时节：')));
        } else if(strpos($_season, '建议游玩时间：')===0) {
            $playDays = substr($_season, strlen('建议游玩时间：'));
            $playDays = strip_tags(substr(trim($playDays), 3));
        }
    }
    //var_dump($city, $longitude, $latitude, $goodSeason, $playDays);
    $breadCrumbs = explode('<span>></span>', $match[0]);
    foreach($breadCrumbs as $_key=>$_crumb) {
        $breadCrumbs[$_key] = trim(strip_tags($_crumb));
        //logError ($breadCrumbs[$_key];
    }

    if($breadCrumbs[0]=='旅行' && $breadCrumbs[1]=='目的地') {
        if(!isset($breadCrumbs[3]) || ($breadCrumbs[3]!=$city && $breadCrumbs[4]!=$city)) {
            logError ($filepath . " can NOT get right city name<br/>");
        }
    }
    $country = $breadCrumbs[2];
    $province = $breadCrumbs[3];
    $return = array('city'=>$city,
                    'province'=>$province,
                    'country'=>$country,
                    'longitude'=>$longitude,
                    'latitude'=>$latitude,
                    'goodSeason'=>$goodSeason,
                    'playDays'=>$playDays);
    //var_dump($return);
    return $return;
}

function parseCityIntro ($filepath)
{
    if(! is_file($filepath)) {
        logError ($filepath . " has no Intro file");
        return;
    }

    $content = file_get_contents($filepath);
    if(! preg_match('/<div class="e_content">(.*)<div class="l_720r235_r">/Us', $content, $match)) {
        logError ($filepath . " has no destination<br/>");
        return;
    }
    $cityInfo = strip_tags(trim($match[1]), '<h2><h3><h4><h5><p><span>');
    $cityInfo = str_replace(array('class="MsoNormal" ', '<o:p></o:p>','style="text-indent: 2em"',
                                    'align="left"','style="line-height:150%;"', "\t",
                                    'class="MsoHyperlink"','class="MsoListParagraph"'),
                            array('', '','class="p_indent"', '', '', '','',''),
                            $cityInfo);
    //var_dump($cityInfo);exit;
    $cityInfo = trim(preg_replace('/style=".*"/i', '', $cityInfo));

    return $cityInfo;
}

function parseCityTripSeason ($filepath)
{
    if(! is_file($filepath)) {
        logError ($filepath . " has no TripSeason file");
        return;
    }

    $content = file_get_contents($filepath);
    if(! preg_match('/<div class="e_content">(.*)<div class="l_720r235_r">/Us', $content, $match)) {
        logError ($filepath . " has no destination<br/>");
        return;
    }
    $cityInfo = strip_tags(trim($match[1]), '<h2><h3><h4><h5><p><span>');
    $cityInfo = str_replace(array('class="MsoNormal" ', '<o:p></o:p>','style="text-indent: 2em"',
                                    'align="left"','style="line-height:150%;"', "\t",
                                    'class="MsoHyperlink"','class="MsoListParagraph"'),
                            array('', '','class="p_indent"', '', '', '','',''),
                            $cityInfo);
    //var_dump($cityInfo);exit;
    $cityInfo = trim(preg_replace('/style=".*"/i', '', $cityInfo));

    return $cityInfo;
}

function parseCityCommunication ($filepath)
{
    if(! is_file($filepath)) {
        logError ($filepath . " has no TripSeason file");
        return;
    }

    $content = file_get_contents($filepath);
    if(! preg_match('/<div class="e_content">(.*)<div class="l_720r235_r">/Us', $content, $match)) {
        logError ($filepath . " has no destination<br/>");
        return;
    }
    $cityInfo = strip_tags(trim($match[1]), '<h2><h3><h4><h5><p><span>');
    $cityInfo = str_replace(array('class="MsoNormal" ', '<o:p></o:p>','style="text-indent: 2em"',
                                    'align="left"','style="line-height:150%;"', "\t",
                                    'class="MsoHyperlink"','class="MsoListParagraph"'),
                            array('', '','class="p_indent"', '', '', '','',''),
                            $cityInfo);
    //var_dump($cityInfo);exit;
    $cityInfo = trim(preg_replace('/style=".*"/i', '', $cityInfo));

    return $cityInfo;
}

function parseCityUsefulInfo ($filepath)
{
    if(! is_file($filepath)) {
        logError ($filepath . " has no TripSeason file");
        return;
    }

    $content = file_get_contents($filepath);
    if(! preg_match('/<div class="e_content">(.*)<div class="l_720r235_r">/Us', $content, $match)) {
        logError ($filepath . " has no destination<br/>");
        return;
    }
    $cityInfo = strip_tags(trim($match[1]), '<h2><h3><h4><h5><p><span>');
    $cityInfo = str_replace(array('class="MsoNormal" ', '<o:p></o:p>','style="text-indent: 2em"',
                                    'align="left"','style="line-height:150%;"', "\t",
                                    'class="MsoHyperlink"','class="MsoListParagraph"'),
                            array('', '','class="p_indent"', '', '', '','',''),
                            $cityInfo);
    //echo ($cityInfo);exit;
    $cityInfo = trim(preg_replace('/style=".*"/i', '', $cityInfo));

    return $cityInfo;
}

function parseCityTips ($filepath)
{
    if(! is_file($filepath)) {
        logError ($filepath . " has no TripSeason file");
        return;
    }

    $content = file_get_contents($filepath);
    if(! preg_match('/<div class="e_content">(.*)<div class="l_720r235_r">/Us', $content, $match)) {
        logError ($filepath . " has no destination<br/>");
        return;
    }
    $cityInfo = strip_tags(trim($match[1]), '<h2><h3><h4><h5><p><span>');
    $cityInfo = str_replace(array('class="MsoNormal" ', '<o:p></o:p>','style="text-indent: 2em"',
                                    'align="left"','style="line-height:150%;"', "\t",
                                    'class="MsoHyperlink"','class="MsoListParagraph"'),
                            array('', '','class="p_indent"', '', '', '','',''),
                            $cityInfo);
    $cityInfo = trim(preg_replace('/style=".*"/i', '', $cityInfo));
    //echo ($cityInfo);exit;

    return $cityInfo;
}




function parseQunarCountry ($dir)
{
    global $countryList;

    $dir = realpath($dir);
    if(! is_dir($dir)) return;

    $dirIterator = new DirectoryIterator($dir);
    foreach($dirIterator as $_dir) {

        if($_dir->isDot()) continue;

        //logError ($_dir->getPath();
        if($_dir->isDir()) {
            continue;
            //parseQunar($_dir->getPathname());
        }

        if($_dir->isFile()) {
            //$domIterator = new Docoument
            $detailDir = $dir . DIRECTORY_SEPARATOR . $_dir->getBasename('.html');
            $filepath = $dir . DIRECTORY_SEPARATOR .$_dir->getFilename();

            logOk('=== START ===' . $filepath);
            $cityInfo = parseCountry($filepath);
            if(is_dir($detailDir)) {
                $cityIntro = parseCountryIntro($detailDir . DIRECTORY_SEPARATOR . 'du.html');
                $citySeason = parseCountryTripSeason($detailDir . DIRECTORY_SEPARATOR . 'du~typeId=415.html');
                $cityCommunication = parseCountryCommunication($detailDir . DIRECTORY_SEPARATOR . 'du~typeId=407.html');
                $cityUsefulInfo = parseCountryUsefulInfo($detailDir . DIRECTORY_SEPARATOR . 'du~typeId=411.html');
                $cityTip = parseCountryTips($detailDir . DIRECTORY_SEPARATOR . 'du~typeId=406.html');
                $money = parseCountryMoney($detailDir . DIRECTORY_SEPARATOR . 'du~typeId=418.html');
            } else {
                $cityIntro = '';
                $citySeason = '';
                $cityCommunication = '';
                $cityUsefulInfo = '';
                $cityTip = '';
                $money = '';
                logError ($filepath . " has no detail folder<br/>");
            }
            //return;

            $countryId = isset($countryList[$cityInfo['country']]) ? $countryList[$cityInfo['country']] : addCountry($cityInfo['country']);
            $goodSeason = $cityInfo['goodSeason'];
            $playDays = $cityInfo['playDays'];

            $return = array( 'goodSeason'=> $goodSeason,
                             'playDays' => $playDays,
                             'info'=>$cityInfo,
                             'intro'=>$cityIntro,
                             'season'=>$citySeason,
                             'comm'=>$cityCommunication,
                             'useful'=>$cityUsefulInfo,
                             'tip'=>$cityTip,
                             'money'=>$money);
            $result = updateCountry($countryId, $return);
            if($result) {
                $countryList[$cityInfo['country']] = $countryId;
                logOk('=== END ===' . $filepath . "\r\n");
            } else {
                var_dump($result, mysql_errno(), mysql_error());
                logError($GLOBALS['query']);
                logError ('### END ###' . $filepath . "\r\n");
            }
            //var_dump($return);
        }
    }
}

function parseCountry ($filepath)
{
    if(! is_file($filepath)) {
        logError ($filepath . " has no country file");
        return;
    }

    $content = file_get_contents($filepath);
    if(! preg_match('/<div class="e_crumbs">.*<\/div>/Us', $content, $match)) {
        logError ($filepath . " has no destination<br/>");
        return;
    }
    if(! preg_match('/var PRE_DEST_NAME = "(.*)";/Us', $content, $matchCountry)) {
        logError ($filepath . " has no country data<br/>");
    }
    if(! preg_match('/<div class="e_cover_des_r">(.*)<\/div>/Us', $content, $matchGoodSeason)) {
        logError ($filepath . " has no good season data<br/>");
    }
    $country = $matchCountry[1];
    $goodSeasons = explode("\n", $matchGoodSeason[1]);
    $goodSeason = '';
    $playDays = 0;
    foreach($goodSeasons as $_key=>$_season) {
        $_season = trim($_season);
        if($_season=='') unset($goodSeasons[$_key]);
        if(strpos($_season, '最佳旅游时节：')===0) {
            $goodSeason = strip_tags(substr($_season, strlen('最佳旅游时节：')));
        } else if(strpos($_season, '建议游玩时间：')===0) {
            $playDays = substr($_season, strlen('建议游玩时间：'));
            $playDays = strip_tags(substr(trim($playDays), 3));
        }
    }
    //var_dump($city, $longitude, $latitude, $goodSeason, $playDays);
    $breadCrumbs = explode('<span>></span>', $match[0]);
    foreach($breadCrumbs as $_key=>$_crumb) {
        $breadCrumbs[$_key] = trim(strip_tags($_crumb));
        //logError ($breadCrumbs[$_key];
    }

    if($breadCrumbs[0]=='旅行' && $breadCrumbs[1]=='目的地') {
        if(!isset($breadCrumbs[2]) || $breadCrumbs[2]!=$country) {
            logError ($filepath . " can NOT get right country name<br/>");
        }
    }
    $return = array('country'=>$country, 'goodSeason'=>$goodSeason, 'playDays'=>$playDays);
    //var_dump($return);
    return $return;
}

function parseCountryMoney($filepath)
{
    if(! is_file($filepath)) {
        logError ($filepath . " has no Money file");
        return;
    }

    $content = file_get_contents($filepath);
    if(! preg_match('/<div class="e_content">(.*)<div class="l_720r235_r">/Us', $content, $match)) {
        logError ($filepath . " has no money<br/>");
        return;
    }
    $cityInfo = strip_tags(trim($match[1]), '<h2><h3><h4><h5><p><span>');
    $cityInfo = str_replace(array('class="MsoNormal" ', '<o:p></o:p>','style="text-indent: 2em"',
            'align="left"','style="line-height:150%;"', "\t",
            'class="MsoHyperlink"','class="MsoListParagraph"'),
            array('', '','class="p_indent"', '', '', '','',''),
            $cityInfo);
    $cityInfo = trim(preg_replace('/style=".*"/i', '', $cityInfo));
    //echo ($cityInfo);exit;

    return $cityInfo;
}

function parseCountryIntro ($filepath)
{
    if(! is_file($filepath)) {
        logError ($filepath . " has no Intro file");
        return;
    }

    $content = file_get_contents($filepath);
    if(! preg_match('/<div class="e_content">(.*)<div class="l_720r235_r">/Us', $content, $match)) {
        logError ($filepath . " has no destination<br/>");
        return;
    }
    $cityInfo = strip_tags(trim($match[1]), '<h2><h3><h4><h5><p><span>');
    $cityInfo = str_replace(array('class="MsoNormal" ', '<o:p></o:p>','style="text-indent: 2em"',
            'align="left"','style="line-height:150%;"', "\t",
            'class="MsoHyperlink"','class="MsoListParagraph"'),
            array('', '','class="p_indent"', '', '', '','',''),
            $cityInfo);
    $cityInfo = trim(preg_replace('/style=".*"/i', '', $cityInfo));
    //var_dump($cityInfo);exit;

    return $cityInfo;
}

function parseCountryTripSeason ($filepath)
{
    if(! is_file($filepath)) {
        logError ($filepath . " has no TripSeason file");
        return;
    }

    $content = file_get_contents($filepath);
    if(! preg_match('/<div class="e_content">(.*)<div class="l_720r235_r">/Us', $content, $match)) {
        logError ($filepath . " has no TripSeason<br/>");
        return;
    }
    $cityInfo = strip_tags(trim($match[1]), '<h2><h3><h4><h5><p><span>');
    $cityInfo = str_replace(array('class="MsoNormal" ', '<o:p></o:p>','style="text-indent: 2em"',
            'align="left"','style="line-height:150%;"', "\t",
            'class="MsoHyperlink"','class="MsoListParagraph"'),
            array('', '','class="p_indent"', '', '', '','',''),
            $cityInfo);
    $cityInfo = trim(preg_replace('/style=".*"/i', '', $cityInfo));
    //var_dump($cityInfo);exit;

    return $cityInfo;
}

function parseCountryCommunication ($filepath)
{
    if(! is_file($filepath)) {
        logError ($filepath . " has no Communication file");
        return;
    }

    $content = file_get_contents($filepath);
    if(! preg_match('/<div class="e_content">(.*)<div class="l_720r235_r">/Us', $content, $match)) {
        logError ($filepath . " has no communication<br/>");
        return;
    }
    $cityInfo = strip_tags(trim($match[1]), '<h2><h3><h4><h5><p><span>');
    $cityInfo = str_replace(array('class="MsoNormal" ', '<o:p></o:p>','style="text-indent: 2em"',
            'align="left"','style="line-height:150%;"', "\t",
            'class="MsoHyperlink"','class="MsoListParagraph"'),
            array('', '','class="p_indent"', '', '', '','',''),
            $cityInfo);
    $cityInfo = trim(preg_replace('/style=".*"/i', '', $cityInfo));
    //var_dump($cityInfo);exit;

    return $cityInfo;
}

function parseCountryUsefulInfo ($filepath)
{
    if(! is_file($filepath)) {
        logError ($filepath . " has no TripSeason file");
        return;
    }

    $content = file_get_contents($filepath);
    if(! preg_match('/<div class="e_content">(.*)<div class="l_720r235_r">/Us', $content, $match)) {
        logError ($filepath . " has no destination<br/>");
        return;
    }
    $cityInfo = strip_tags(trim($match[1]), '<h2><h3><h4><h5><p><span>');
    $cityInfo = str_replace(array('class="MsoNormal" ', '<o:p></o:p>','style="text-indent: 2em"',
            'align="left"','style="line-height:150%;"', "\t",
            'class="MsoHyperlink"','class="MsoListParagraph"'),
            array('', '','class="p_indent"', '', '', '','',''),
            $cityInfo);
    $cityInfo = trim(preg_replace('/style=".*"/i', '', $cityInfo));
    //echo ($cityInfo);exit;

    return $cityInfo;
}

function parseCountryTips ($filepath)
{
    if(! is_file($filepath)) {
        logError ($filepath . " has no TripSeason file");
        return;
    }

    $content = file_get_contents($filepath);
    if(! preg_match('/<div class="e_content">(.*)<div class="l_720r235_r">/Us', $content, $match)) {
        logError ($filepath . " has no destination<br/>");
        return;
    }
    $cityInfo = strip_tags(trim($match[1]), '<h2><h3><h4><h5><p><span>');
    $cityInfo = str_replace(array('class="MsoNormal" ', '<o:p></o:p>','style="text-indent: 2em"',
            'align="left"','style="line-height:150%;"', "\t",
            'class="MsoHyperlink"','class="MsoListParagraph"'),
            array('', '','class="p_indent"', '', '', '','',''),
            $cityInfo);
    $cityInfo = trim(preg_replace('/style=".*"/i', '', $cityInfo));
    //echo ($cityInfo);exit;

    return $cityInfo;
}




function parseQunarProvince ($dir)
{
    global $countryList, $provinceList;

    $dir = realpath($dir);
    if(! is_dir($dir)) return;

    $dirIterator = new DirectoryIterator($dir);
    foreach($dirIterator as $_dir) {

        if($_dir->isDot()) continue;

        //logError ($_dir->getPath();
        if($_dir->isDir()) {
            continue;
            //parseQunar($_dir->getPathname());
        }

        if($_dir->isFile()) {
            //$domIterator = new Docoument
            $detailDir = $dir . DIRECTORY_SEPARATOR . $_dir->getBasename('.html');
            $filepath = $dir . DIRECTORY_SEPARATOR .$_dir->getFilename();

            logOk('=== START ===' . $filepath);
            $cityInfo = parseProvince($filepath);
            if(is_dir($detailDir)) {
                $cityIntro = parseProvinceIntro($detailDir . DIRECTORY_SEPARATOR . 'du.html');
                $citySeason = parseProvinceTripSeason($detailDir . DIRECTORY_SEPARATOR . 'du~typeId=415.html');
                $cityCommunication = parseProvinceCommunication($detailDir . DIRECTORY_SEPARATOR . 'du~typeId=407.html');
                $cityTip = parseProvinceTips($detailDir . DIRECTORY_SEPARATOR . 'du~typeId=406.html');
            } else {
                $cityIntro = '';
                $citySeason = '';
                $cityCommunication = '';
                $cityTip = '';
                logError ($filepath . " has no detail folder<br/>");
            }

            $countryId = isset($countryList[$cityInfo['country']]) ? $countryList[$cityInfo['country']] : addCountry($cityInfo['country']);
            $countryList[$cityInfo['country']] = $countryId;
            $provinceId = isset($provinceList[$countryId.'-'.$cityInfo['province']]) ? $provinceList[$countryId.'-'.$cityInfo['province']] : addProvince($cityInfo['province'], $countryId);
            $provinceList[$countryId.'-'.$cityInfo['province']] = $provinceId;
            $return = array( 'playDays'=>$cityInfo['playDays'],
                             'goodSeason'=>$cityInfo['goodSeason'],
                             'countryId'=>$countryId,

                             'info'=>$cityInfo,
                             'intro'=>$cityIntro,
                             'season'=>$citySeason,
                             'comm'=>$cityCommunication,
                             //'useful'=>$cityUsefulInfo,
                             'tip'=>$cityTip);
            //var_dump($return);
            if(updateProvince($provinceId, $return)) {
                logOk('=== END ===' . $filepath . "\r\n");
            } else {
                logError($GLOBALS['query']);
                logError('### END ###' . $filepath . "\r\n");
            }
            //return;
        }
    }
}

function parseProvince ($filepath)
{
    if(! is_file($filepath)) {
        logError ($filepath . " has no province file");
        return;
    }

    $content = file_get_contents($filepath);
    if(! preg_match('/<div class="e_crumbs">.*<\/div>/Us', $content, $match)) {
        logError ($filepath . " has no destination<br/>");
        return;
    }
    if(! preg_match('/var PRE_DEST_NAME = "(.*)";/Us', $content, $matchCity)) {
        logError ($filepath . " has no city data<br/>");
        return;
    }
    if(! preg_match('/<div class="e_cover_des_r">(.*)<\/div>/Us', $content, $matchGoodSeason)) {
        logError ($filepath . " has no good season data<br/>");
        return;
    }
    $province = $matchCity[1];
    $goodSeasons = explode("\n", $matchGoodSeason[1]);
    $goodSeason = '';
    $playDays = 0;
    foreach($goodSeasons as $_key=>$_season) {
        $_season = trim($_season);
        if($_season=='') unset($goodSeasons[$_key]);
        if(strpos($_season, '最佳旅游时节：')===0) {
            $goodSeason = strip_tags(substr($_season, strlen('最佳旅游时节：')));
        } else if(strpos($_season, '建议游玩时间：')===0) {
            $playDays = substr($_season, strlen('建议游玩时间：'));
            $playDays = strip_tags(substr(trim($playDays), 3));
        }
    }
    //var_dump($city, $longitude, $latitude, $goodSeason, $playDays);
    $breadCrumbs = explode('<span>></span>', $match[0]);
    foreach($breadCrumbs as $_key=>$_crumb) {
        $breadCrumbs[$_key] = trim(strip_tags($_crumb));
        //logError ($breadCrumbs[$_key];
    }

    if($breadCrumbs[0]=='旅行' && $breadCrumbs[1]=='目的地') {
        if(!isset($breadCrumbs[3]) || $breadCrumbs[3]!=$province) {
            logError ($filepath . " can NOT get right city name<br/>");
        }
    }
    $country = $breadCrumbs[2];
    $return = array('province'=>$province,
                    'country'=>$country,
                    'goodSeason'=>$goodSeason,
                    'playDays'=>$playDays);
    //var_dump($return);
    return $return;
}

function parseProvinceIntro ($filepath)
{
    if(! is_file($filepath)) {
        logError ($filepath . " has no Intro file");
        return;
    }

    $content = file_get_contents($filepath);
    if(! preg_match('/<div class="e_content">(.*)<div class="l_720r235_r">/Us', $content, $match)) {
        logError ($filepath . " has no destination<br/>");
        return;
    }
    $cityInfo = strip_tags(trim($match[1]), '<h2><h3><h4><h5><p><span>');
    $cityInfo = str_replace(array('class="MsoNormal" ', '<o:p></o:p>','style="text-indent: 2em"',
            'align="left"','style="line-height:150%;"', "\t",
            'class="MsoHyperlink"','class="MsoListParagraph"'),
            array('', '','class="p_indent"', '', '', '','',''),
            $cityInfo);
    //var_dump($cityInfo);exit;
    $cityInfo = trim(preg_replace('/style=".*"/i', '', $cityInfo));

    return $cityInfo;
}

function parseProvinceTripSeason ($filepath)
{
    if(! is_file($filepath)) {
        logError ($filepath . " has no TripSeason file");
        return;
    }

    $content = file_get_contents($filepath);
    if(! preg_match('/<div class="e_content">(.*)<div class="l_720r235_r">/Us', $content, $match)) {
        logError ($filepath . " has no destination<br/>");
        return;
    }
    $cityInfo = strip_tags(trim($match[1]), '<h2><h3><h4><h5><p><span>');
    $cityInfo = str_replace(array('class="MsoNormal" ', '<o:p></o:p>','style="text-indent: 2em"',
            'align="left"','style="line-height:150%;"', "\t",
            'class="MsoHyperlink"','class="MsoListParagraph"'),
            array('', '','class="p_indent"', '', '', '','',''),
            $cityInfo);
    //var_dump($cityInfo);exit;
    $cityInfo = trim(preg_replace('/style=".*"/i', '', $cityInfo));

    return $cityInfo;
}

function parseProvinceCommunication ($filepath)
{
    if(! is_file($filepath)) {
        logError ($filepath . " has no TripSeason file");
        return;
    }

    $content = file_get_contents($filepath);
    if(! preg_match('/<div class="e_content">(.*)<div class="l_720r235_r">/Us', $content, $match)) {
        logError ($filepath . " has no destination<br/>");
        return;
    }
    $cityInfo = strip_tags(trim($match[1]), '<h2><h3><h4><h5><p><span>');
    $cityInfo = str_replace(array('class="MsoNormal" ', '<o:p></o:p>','style="text-indent: 2em"',
            'align="left"','style="line-height:150%;"', "\t",
            'class="MsoHyperlink"','class="MsoListParagraph"'),
            array('', '','class="p_indent"', '', '', '','',''),
            $cityInfo);
    //var_dump($cityInfo);exit;
    $cityInfo = trim(preg_replace('/style=".*"/i', '', $cityInfo));

    return $cityInfo;
}

function parseProvinceUsefulInfo ($filepath)
{
    if(! is_file($filepath)) {
        logError ($filepath . " has no TripSeason file");
        return;
    }

    $content = file_get_contents($filepath);
    if(! preg_match('/<div class="e_content">(.*)<div class="l_720r235_r">/Us', $content, $match)) {
        logError ($filepath . " has no destination<br/>");
        return;
    }
    $cityInfo = strip_tags(trim($match[1]), '<h2><h3><h4><h5><p><span>');
    $cityInfo = str_replace(array('class="MsoNormal" ', '<o:p></o:p>','style="text-indent: 2em"',
            'align="left"','style="line-height:150%;"', "\t",
            'class="MsoHyperlink"','class="MsoListParagraph"'),
            array('', '','class="p_indent"', '', '', '','',''),
            $cityInfo);
    //echo ($cityInfo);exit;
    $cityInfo = trim(preg_replace('/style=".*"/i', '', $cityInfo));

    return $cityInfo;
}

function parseProvinceTips ($filepath)
{
    if(! is_file($filepath)) {
        logError ($filepath . " has no TripSeason file");
        return;
    }

    $content = file_get_contents($filepath);
    if(! preg_match('/<div class="e_content">(.*)<div class="l_720r235_r">/Us', $content, $match)) {
        logError ($filepath . " has no destination<br/>");
        return;
    }
    $cityInfo = strip_tags(trim($match[1]), '<h2><h3><h4><h5><p><span>');
    $cityInfo = str_replace(array('class="MsoNormal" ', '<o:p></o:p>','style="text-indent: 2em"',
            'align="left"','style="line-height:150%;"', "\t",
            'class="MsoHyperlink"','class="MsoListParagraph"'),
            array('', '','class="p_indent"', '', '', '','',''),
            $cityInfo);
    $cityInfo = preg_replace('/style=".*"/i', '', $cityInfo);
    $cityInfo = trim(preg_replace('/style=".*"/i', '', $cityInfo));
    //echo ($cityInfo);exit;

    return $cityInfo;
}





function parseQunarScenery ($dir)
{
    global $countryList, $provinceList, $cityList, $sceneryList;

    $dir = realpath($dir);
    if(! is_dir($dir)) return;

    $dirIterator = new DirectoryIterator($dir);
    foreach($dirIterator as $_dir) {

        if($_dir->isDot()) continue;

        //logError ($_dir->getPath();
        if($_dir->isDir()) {
            continue;
            //parseQunar($_dir->getPathname());
        }

        if($_dir->isFile()) {
            $filepath = $dir . DIRECTORY_SEPARATOR .$_dir->getFilename();
            logOk('=== START ===' . $filepath);
            $cityInfo = parseScenery ($filepath);
            if(! $cityInfo) {
                continue;
            }
            //return;

            $countryId = isset($countryList[$cityInfo['country']]) ? $countryList[$cityInfo['country']] : addCountry($cityInfo['country']);
            $countryList[$cityInfo['country']] = $countryId;
            $provinceId = isset($provinceList[$countryId.'-'.$cityInfo['province']]) ? $provinceList[$countryId.'-'.$cityInfo['province']] : addProvince($cityInfo['province'], $countryId);
            $provinceList[$countryId.'-'.$cityInfo['province']] = $provinceId;
            $cityId = isset($cityList[$provinceId.'-'.$cityInfo['city']]) ? $cityList[$provinceId.'-'.$cityInfo['city']] : addCity($cityInfo['city'], $provinceId);
            $cityList[$provinceId.'-'.$cityInfo['city']] = $cityId;
            $sceneryId = isset($sceneryList[$cityId.'-'.$cityInfo['scenery']]) ? $sceneryList[$cityId.'-'.$cityInfo['scenery']] : addScenery($cityInfo['scenery'], $cityId);
            $sceneryList[$cityId.'-'.$cityInfo['scenery']] = $sceneryId;
            $cityInfo['cityId'] = $cityId;
            $cityInfo['provinceId'] = $provinceId;

            if(updateScenery($sceneryId, $cityInfo)) {
                logOk('=== END ===' . $filepath . "\r\n");
            } else {
                logError($GLOBALS['query']);
                logError('*** END ***' . $filepath . "  failed to update scenery.\r\n");
            }
        }
    }
}

function parseScenery ($filepath)
{
    if(! is_file($filepath)) {
        logError ($filepath . " has no Scenery file");
        return;
    }

    $content = file_get_contents($filepath);
    if(! preg_match('/<div class="e_crumbs">.*<\/div>/Us', $content, $match)) {
        logError ($filepath . " has no destination<br/>");
        return;
    }

    // check if it is scenery page
    $breadCrumbs = explode('<span>></span>', strip_tags($match[0], '<span>'));
    if(count($breadCrumbs)<5 || trim($breadCrumbs[0])!='旅行' || trim($breadCrumbs[1])!='目的地') {
        logError ($filepath . " is NOT Scenery file<br/>");
        return;
    }
    if(trim($breadCrumbs[3])=='') {
        logError ($filepath . " can NOT get right city name<br/>");
        return;
    }
    if(isset($breadCrumbs[6])) {
        logError ($filepath . " has TOO MUCH bread crumbs<br/>");
        return;
    }

    $country = trim($breadCrumbs[2]);
    $province = trim($breadCrumbs[3]);
    if(!isset($breadCrumbs[5])) {
        $city = trim($breadCrumbs[3]);
        $scenery = trim($breadCrumbs[4]);
    } else {
        $city = trim($breadCrumbs[4]);
        $scenery = trim($breadCrumbs[5]);
    }

    // load Score info
    $score = 0;
    if(! preg_match('/<div class="scorebox clrfix">(.*)<\/div>/Us', $content, $matchScore)) {
        logError ($filepath . " has no Score<br/>n");
    } else {
        $scoreInfo = explode("\n", trim($matchScore[1]));
        $score = substr(trim($scoreInfo[0]), strlen('<span class="cur_score">'), 0-strlen('</span>'));
    }
    // load ranking info
    $rank = 0;
    if(! preg_match('/<div class="ranking">(.*)<\/div>/Us', $content, $matchRank)) {
        logError ($filepath . " has no Rank<br/>");
    } else {
        if(! strpos($matchRank[1], '景点排名')) {
            logError ($filepath . " is NOT scenery file!!!<br/>");
            return;
        }
        $rankInfo = explode("\n", trim($matchRank[1]));
        $rankInfo[0] = trim($rankInfo[0]);
        $rank = substr($rankInfo[0], strpos($rankInfo[0], '"sum">')+6, -7);
    }
    // load play time info
    $playTime = 0;
    if(! preg_match('/<div class="time">(.*)<\/div>/Us', $content, $matchPlayTime)) {
        logError ($filepath . " has no PlayTime<br/>");
    } else {
        $playTime = substr($matchPlayTime[1], strlen('建议游玩时间：'));
    }
    // load GPS info
    $gps = array();
    if(! preg_match('/<div class="mapbox" latlng="(.*)" data-beacon="map">/i', $content, $matchGps)) {
        logError ($filepath . " has no GPS<br/>");
    } else {
        $gps = explode(',', $matchGps[1]);
    }
    // load about info
    $aboutInfo = '';
    if(! preg_match('/<!-- 概述 开始 -->(.*)<!-- 概述 结束 -->/Us', $content, $matchAbout)) {
        logError ($filepath . " has no About<br/>");
    } else {
        $aboutInfo = str_replace(array('class="b_detail_section b_detail_summary"',
                                           'data-key="summary" id="gs"', 'class="e_db_content_box"',
                                           'class="b_detail_section b_detail_summary"',
                                           'class="e_summary_list_box"',
                                            " class='e_summary_list clrfix'",
                                            '<div class="short">',
                                            '</div><span class="ellipsis"><span class="expand">展开全部</span></span>',
                                            '<div id="intro_hidden" class="all intro_hidden">',
                                            '<span class="fold">收起</span></div>'
                                            ),
                                     array('class="sceneryInfo"',
                                           'id="sceneryInfo"', 'class="sceneryBox"',
                                           'class="contentBox"',
                                           'class="contactBox"',
                                           '',
                                           '',
                                           '',
                                           '',
                                           ''),
                                     trim($matchAbout[1]));
    }
    // load ticket info
    $ticketInfo = '';
    if(! preg_match('/<!-- 门票 开始 -->(.*)<!-- 门票 结束 -->/Us', $content, $matchTicket)) {
        logError ($filepath . " has no ticket<br/>");
    } else {
        $matchTicket[1] = preg_replace('/<div class="e_ticket_info_box">.*<\/div>$/Uis', '', trim($matchTicket[1])).'</div>';
        $ticketInfo = str_replace(array('data-key="ticket" id="mp"',
                                           'class="b_detail_section b_detail_ticket"', 'class="e_title_box"',
                                           'class="e_title_content"',
                                           'class="e_db_content_box e_db_content_dont_indent"',
                                            '<div class="short">',
                                            '</div><span class="ellipsis"><span class="expand">展开全部</span></span>',
                                            '<div id="intro_hidden" class="all intro_hidden">',
                                            '<span class="fold">收起</span></div>'
                                            ),
                                     array('id="ticketInfo"','class="ticketInfo"',
                                           'class="sceneryTitleBox"', '',
                                           'class="sceneryDetailBox"',
                                           '',
                                           '',
                                           '',
                                           ''
                                           ),
                                     trim($matchTicket[1]));
    }
    // load play season info
    $seasonInfo = '';
    if(! preg_match('/<!-- 旅游时节\/推荐菜开始 -->(.*)<!-- 旅游时节\/推荐菜结束 -->/Us', $content, $matchSeason)) {
        logError ($filepath . " has no Season<br/>");
    } else {
        $seasonInfo = str_replace(array('class="b_detail_section b_detail_travelseason" id="lysj"',
                                           'class="e_title_box"',
                                           'class="e_title_content"',
                                           'class="e_db_content_box e_db_content_dont_indent"',
                                            ),
                                     array('id="seasonBox"',
                                           'class="sceneryTitleBox"', '',
                                           'class="sceneryDetailBox"',
                                           ),
                                     trim($matchSeason[1]));
    }
    // load communication info
    $commInfo = '';
    if(! preg_match('/<!-- 交通指南开始 -->(.*)<!-- 交通指南结束 -->/Us', $content, $matchCommunication)) {
        logError ($filepath . " has no Communication<br/>");
    } else {
        $commInfo = str_replace(array('class="b_detail_section b_detail_traffic" id="jtzn"',
                                           'class="e_title_box"',
                                           'class="e_title_content"',
                                           'class="e_db_content_box e_db_content_dont_indent"',
                                            ),
                                     array('id="commBox"',
                                           'class="sceneryTitleBox"', '',
                                           'class="sceneryDetailBox"',
                                           ),
                                     trim($matchCommunication[1]));
    }
    // load tips info
    $tipInfo = '';
    if(! preg_match('/<!-- 贴士开始 -->(.*)<!-- 贴士结束 -->/Us', $content, $matchTip)) {
        logError ($filepath . " has no Tips<br/>");
    } else {
        $tipInfo = str_replace(array('class="b_detail_section b_detail_tips" id="ts"',
                                           'class="e_title_box"',
                                           'class="e_title_content"',
                                           'class="e_db_content_box e_db_content_dont_indent"',
                                            ),
                                     array('id="tipsBox"',
                                           'class="sceneryTitleBox"', '',
                                           'class="sceneryDetailBox"',
                                           ),
                                     trim($matchTip[1]));
    }
    // load big pic info
    if(! preg_match('/<ul id="idSlider" class="list_item">(.*)<\/ul>/Us', $content, $matchBigPic)) {
        logError ($filepath . " has no Bigpic<br/>");
    } else {
        preg_match_all('/src="([^"]*)"/i', $matchBigPic[1], $matchBigPic);
    }
    // load thumbs info
    if(! preg_match('/<ul class="list_item clrfix"  id="idNum">(.*)<\/ul>/Us', $content, $matchThumbs)) {
        logError ($filepath . " has no Thumbs<br/>");
    } else {
        preg_match_all('/src="([^"]*)"/i', $matchThumbs[1], $matchThumbs);
    }
    // load percent info
    $percent = 0;
    if(! preg_match('/<div class="percent">(.*)<\/div>/Us', $content, $matchPercent)) {
        logError ($filepath . " has no Percent<br/>");
    } else {
        $percent = $matchPercent[1];
    }

    $fileInfo = explode('-', $filepath);
    $poiId = substr($fileInfo[count($fileInfo)-1], 0, -5);

    $return = array('country'=>$country,
                    'province'=>$province,
                    'city'=>$city,
                    'scenery'=>$scenery,
                    'score'=>$score,
                    'rank'=>$rank,
                    'playTime'=>$playTime,
                    //'gps'=>$gps,
                    'longitude'=>(isset($gps[0])?$gps[0]:0),
                    'latitude'=>(isset($gps[1])?$gps[1]:0),
                    'aboutInfo'=>$aboutInfo,
                    'ticketInfo'=>$ticketInfo,
                    'seasonInfo'=>$seasonInfo,
                    'commInfo'=>$commInfo,
                    'tipInfo'=>$tipInfo,
                    'bigPic'=>$matchBigPic[1],
                    'thumbs'=>$matchThumbs[1],
                    'percent'=>$percent,
                    'poiId'  => $poiId
    );
    //var_dump($return);

    return $return;
}

loadDataFromDb();

//var_dump($countryList, $provinceList, $cityList, $sceneryList);

parseQunarCountry('./place/country/');
parseQunarProvince('./place/province/');
parseQunarCity('./place/city/');
parseQunarScenery('./place/poi/');
















exit;
$conn = mysql_connect($server, $username,$password);
mysql_select_db($databaseName, $conn);
mysql_query('set names "GBK"');
$mdbFilename = realpath('../../../../../../jingdian/jingdian/data.mdb');
$connection = odbc_connect("Driver={Microsoft Access Driver (*.mdb)};DBQ=$mdbFilename",
                  '', '');
$sql = "SELECT * from jingdiang";
$rs = odbc_exec($connection,$sql);
$i = 0;
while (($row=odbc_fetch_array($rs))) {
    //$row['Title'] = mb_convert_encoding($row['Title'], 'UTF-8', 'GBK');
    //$row['Content'] = mb_convert_encoding($row['Content'], 'UTF-8', 'GBK');
    $row['Content'] = trim(preg_replace(array('/<p align=center><\/p>/i','/<P class=wtext>/i','/&nbsp;<p>/i'), array('', '<p>','<p>'), $row['Content']));
    $row['s_id'] = intval($row['s_id']);
    $row['o_id'] = intval($row['o_id']);
    $sql = "insert into scenery (scenery_name, scenery_desc, o_id, s_id) value ('".mysql_real_escape_string($row['Title'])."',
                    '".mysql_real_escape_string($row['Content'])."',
                    '".$row['o_id']."',
                    '".$row['s_id']."')";
    mysql_query($sql);
}
odbc_close($connection);

/* EOF */