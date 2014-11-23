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
$imgData = file_get_contents('./barimage.bmp');
$sql = 'insert into img_db (img_data) values ("'.$imgData . '")';
mysql_query($sql);
echo mysql_error();

exit;


$imgRootDir = './';
$query = "SELECT i.*
          FROM scenery_img i";
$result = mysql_query($query);
while(($row=mysql_fetch_assoc($result))) {
    $imgUrl = $row['img_url'];
    $iconImageURL = $row['icon_url'];
    $smallImageURL = $row['small_url'];
    $middleImageURL = $row['middle_url'];
    $bigImageURL = $row['big_url'];
    $imgPath = $row['img_path'];
    $iconImagePath = $row['icon_path'];
    $smallImagePath = $row['small_path'];
    $middleImagePath = $row['middle_path'];
    $bigImagePath = $row['big_path'];

    $imgUrl = $imgRootDir . substr($imgUrl, 7);
    $iconImageURL = $imgRootDir . substr($iconImageURL, 7);
    $smallImageURL = $imgRootDir . substr($smallImageURL, 7);
    $middleImageURL = $imgRootDir . substr($middleImageURL, 7);
    $bigImageURL = $imgRootDir . substr($bigImageURL, 7);

	if(!file_exists($imgPath) ||!file_exists($iconImagePath) ||!file_exists($smallImagePath) ||!file_exists($middleImagePath) ||!file_exists($bigImagePath)) {
	    $query = "UPDATE scenery_img
                  SET pic_loaded = false
                  WHERE img_id = " . $row['img_id'];
        mysql_query($query);
		continue;
	}

	if(!file_exists($imgUrl)) {
	    error_log("$imgUrl not exist\r\n", 3, './removeImg.log.txt');
	}
	if(!file_exists($iconImageURL)) {
	    error_log("$iconImageURL not exist\r\n", 3, './removeImg.log.txt');
		continue;
	}
	if(!file_exists($smallImageURL)) {
	    error_log("$smallImageURL not exist\r\n", 3, './removeImg.log.txt');
	}
	if(!file_exists($middleImageURL) ) {
	    error_log("$middleImageURL not exist\r\n", 3, './removeImg.log.txt');
	}
	if(!file_exists($bigImageURL)) {
	    error_log("$bigImageURL not exist\r\n", 3, './removeImg.log.txt');
	}

	if(md5_file($imgPath)!=md5_file($imgUrl)) {
	    error_log( md5_file($imgPath). '   ' . md5_file($imgUrl) . "   $imgUrl -- $imgPath is wrong\r\n", 3, './wrongImg.log.txt');
	}
	if(md5_file($iconImagePath)!=md5_file($iconImageURL)) {
	    error_log( md5_file($iconImagePath). '   ' . md5_file($iconImageURL) . "   $iconImageURL -- $iconImagePath is wrong\r\n", 3, './wrongImg.log.txt');
	}
	if(md5_file($smallImagePath)!=md5_file($smallImageURL)) {
	    error_log( md5_file($smallImagePath). '   ' . md5_file($smallImageURL) . "   $smallImageURL -- $smallImagePath is wrong\r\n", 3, './wrongImg.log.txt');
	}
	if(md5_file($middleImagePath)!=md5_file($middleImageURL)) {
	    error_log( md5_file($middleImagePath). '   ' . md5_file($middleImageURL) . "   $middleImageURL -- $middleImagePath is wrong\r\n", 3, './wrongImg.log.txt');
	}
	if(md5_file($bigImagePath)!=md5_file($bigImageURL)) {
	    error_log( md5_file($bigImagePath). '   ' . md5_file($bigImageURL) . "   $bigImageURL -- $bigImagePath is wrong\r\n", 3, './wrongImg.log.txt');
	}

	@unlink($imgUrl);@unlink($iconImageURL);@unlink($smallImageURL);@unlink($middleImageURL);@unlink($bigImageURL);
}
