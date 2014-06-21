<?php
/********************************************************/
/*****                 @!!@                          ****/
/********************************************************/
/**
 *@FileName    : loadPic.php
 *@Author      : Kilin WANG <wangkilin@126.com>
 *@Date        : 2014-3-22
 *@Version     : 0.1
 */
set_time_limit(0);

$server = 'localhost';
$username = 'root';
$password = '';
$databaseName = 'pop';

$conn = mysql_connect($server, $username, $password);
mysql_select_db($databaseName, $conn);
mysql_query('set names "UTF8"');
/*
$query = 'SELECT * FROM city ';
$result = mysql_query($query);
$link = 'http://travel.qunar.com/place/city/%s/du?typeId=';
while(($row=mysql_fetch_assoc($result))) {
    if($row['season']=='') {
	    error_log('<a href="'.sprintf($link, $row['city_dir']).'415">a</a>'."\r\n", 3, 'cityInfo.html');
	}
    if($row['traffic']=='') {
	    error_log('<a href="'.sprintf($link, $row['city_dir']).'407">a</a>'."\r\n", 3, 'cityInfo.html');
	}
    if($row['tip']=='') {
	    error_log('<a href="'.sprintf($link, $row['city_dir']).'406">a</a>'."\r\n", 3, 'cityInfo.html');
	}
    if($row['useful_info']=='') {
	    error_log('<a href="'.sprintf($link, $row['city_dir']).'419">a</a>'."\r\n", 3, 'cityInfo.html');
	}
}

exit;
*/
/*
$query = 'SELECT scenery_id, poi_id FROM scenery WHERE pic_loaded = false AND poi_id > 0';
$result = mysql_query($query);
$link = 'http://travel.qunar.com/place/api/poi/image?poiId=%d&offset=%d&limit=50';
while(($row=mysql_fetch_assoc($result))) {

    $i = 0;
    while($i<=1) {
        $content = file_get_contents(sprintf($link, $row['poi_id'], $i*50));
        $content = @json_decode($content);
        $i++;
        if(! is_object($content)) continue; // failed to parse JSON
        if($content->errcode!==0) continue;
        if($content->totalCount==0) {		    
            $query = "UPDATE scenery SET pic_loaded = true WHERE scenery_id = " . $row['scenery_id'];
            mysql_query($query);
		    break;
		}
        foreach ($content->data as $picInfoObj) {
            $isValid = $picInfoObj->valid;
            if($isValid!==true) continue;

            $imgUrl = $picInfoObj->url;
            $imgWidth = $picInfoObj->width;
            $imgHeight = $picInfoObj->height;
            $smallImageURL = $picInfoObj->smallImageURL;
            $iconImageURL = $picInfoObj->iconImageURL;
            $middleImageURL = $picInfoObj->middleImageURL;
            $bigImageURL = $picInfoObj->bigImageURL;
            error_log('<a href="' . $imgUrl."\">a</a>\r\n<a href=\"".$smallImageURL."\">a</a>\r\n<a href=\"".$iconImageURL."\">a</a>\r\n<a href=\"".
                     $middleImageURL."\">a</a>\r\n<a href=\"".$bigImageURL."\">a</a>\r\n",3, './sceneryImg.txt');
            $query = "INSERT INTO scenery_img (scenery_id,
                                           img_url,
                                           img_width,
                                           img_height,
                                           icon_url,
                                           small_url,
                                           middle_url,
                                           big_url )
                                 VALUES (
                                          '".mysql_real_escape_string($row['scenery_id'])."',
                                          '".mysql_real_escape_string($imgUrl)."',
                                          '".mysql_real_escape_string($imgWidth)."',
                                          '".mysql_real_escape_string($imgHeight)."',
                                          '".mysql_real_escape_string($iconImageURL)."',
                                          '".mysql_real_escape_string($smallImageURL)."',
                                          '".mysql_real_escape_string($middleImageURL)."',
                                          '".mysql_real_escape_string($bigImageURL)."'
                                )

                     ";
            mysql_query($query);
        }
        $query = "UPDATE scenery SET pic_loaded = true WHERE scenery_id = " . $row['scenery_id'];
        mysql_query($query);

        if($content->totalCount<=50) break; // it doesn't have so many pic
    }
}

exit;
*/

function copyImage($imgUrl, $copyToPath)
{
    $return = true;
    if(!file_exists($copyToPath)) {
        $pathInfo = explode('/', $copyToPath);
        $size = count($pathInfo)-1;
        $path = $pathInfo[0];
        $i = 0;
        while($i<$size) {
            if(! is_dir($path)) mkdir($path);
            $path = $path . '/' . $pathInfo[$i+1];
            $i++;
        }
        $return = file_put_contents($copyToPath, file_get_contents($imgUrl))>0;
    } else {
	    error_log($copyToPath ."\r\n", 3, './existingImg.txt');
	}
    return $return;
}

$imgRootDir = './';
$newImgRootDir = './poptrip.cn/images/scenery/201403';
$query = "SELECT i.*, s.auto_province_id province_id, s.auto_city_id city_id
          FROM scenery_img i
          INNER JOIN scenery s ON i.scenery_id = s.scenery_id
          WHERE i.pic_loaded = false AND s.pic_loaded = true
		  ORDER BY s.score DESC ";
$result = mysql_query($query);
while(($row=mysql_fetch_assoc($result))) {
    $imgUrl = $row['img_url'];
    $iconImageURL = $row['icon_url'];
    $smallImageURL = $row['small_url'];
    $middleImageURL = $row['middle_url'];
    $bigImageURL = $row['big_url'];

    $imgPath = $imgRootDir . substr($imgUrl, 7);
    $iconPath = $imgRootDir . substr($iconImageURL, 7);
    $smallPath = $imgRootDir . substr($smallImageURL, 7);
    $middlePath = $imgRootDir . substr($middleImageURL, 7);
    $bigPath = $imgRootDir . substr($bigImageURL, 7);

    if(copyImage($imgUrl, $imgPath) && copyImage($iconImageURL, $iconPath)
      && copyImage($smallImageURL, $smallPath) && copyImage($middleImageURL, $middlePath)
      && copyImage($bigImageURL, $bigPath)) {
        if(! is_dir($newImgRootDir)) mkdir($newImgRootDir);
        $newImgPath = $newImgRootDir . '/' . date('d', time()-rand(0,90)*24*3600);
        if(! is_dir($newImgPath)) mkdir($newImgPath);
        $newImgPath .= '/'.dechex($row['province_id']).'_'.dechex($row['city_id']);
        if(! is_dir($newImgPath)) mkdir($newImgPath);
        $newFileName = substr(md5(microtime().$row['province_id'].'_'.$row['city_id']), 0, 12).'_'.dechex($row['scenery_id']);

        $newBigPath = $newImgPath . '/' . $newFileName . '_1024x768' . substr($bigPath, -4);
        $newMiddlePath = $newImgPath . '/' . $newFileName . '_480x360' . substr($middlePath, -4);
        $newSmallPath = $newImgPath . '/' . $newFileName . '_160x120' . substr($smallPath, -4);
        $newIconPath = $newImgPath . '/' . $newFileName . '_80x60' .substr($iconPath, -4);
        $newImgPath = $newImgPath . '/' . $newFileName . substr($imgPath, -4);
        copy($imgPath, $newImgPath);
        copy($iconPath, $newIconPath);
        copy($smallPath, $newSmallPath);
        copy($middlePath, $newMiddlePath);
        copy($bigPath, $newBigPath);

        $query = "UPDATE scenery_img
                  SET pic_loaded = true ,
                      img_path = '".mysql_real_escape_string(ltrim($newImgPath, './'))."' ,
                      icon_path = '".mysql_real_escape_string(ltrim($newIconPath, './'))."' ,
                      small_path = '".mysql_real_escape_string(ltrim($newSmallPath, './'))."' ,
                      middle_path = '".mysql_real_escape_string(ltrim($newSmallPath, './'))."' ,
                      big_path = '".mysql_real_escape_string(ltrim($newBigPath, './'))."'
                  WHERE img_id = " . $row['img_id'];
        mysql_query($query);
    }
}
