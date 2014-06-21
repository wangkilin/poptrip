<?php
http://travel.qunar.com/place/filter/1?area=0&month=0&tag=0&hot=0


$url = 'http://you.kuxun.cn/beijing-jingdian-60.html';

set_time_limit(0);
$countryList = $provinceList = $cityList = $sceneryList = array();
$query = null;
$server = 'localhost';
$username = 'root';
$password = '';
$databaseName = 'pop';
$conn = mysql_connect($server, $username,$password);
mysql_select_db($databaseName, $conn);
mysql_query('set names "UTF8"');

global $countryList, $provinceList, $cityList, $sceneryList;
global $query;
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
function logError ($errorMsg)
{
    //echo $errorMsg . "\r\n";
	error_log($errorMsg. "\r\n", 3, './poptrip.cn/error.log');
	
}
function logOk ($okMsg)
{
    //echo $okMsg . "\r\n";
	error_log($okMsg. "\r\n", 3, './poptrip.cn/ok.log');
}

function loadDataFromDb()
{
    global $countryList, $provinceList, $cityList, $sceneryList;

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
        $sceneryList[$row['province_id'] . '-' .$row['scenery_name']] = $row['scenery_id'];
    }
}


function getHttpContent($url, $realHtmlFile)
{
    $ch = curl_init($url);
	curl_setopt($ch, CURLOPT_USERAGENT, 'firefox');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/xml'));
	$response = curl_exec($ch);
	$options = curl_getinfo($ch);
	curl_close($ch);
	if($options['http_code']==200 && strlen($response)>1000) {				
	    file_put_contents($realHtmlFile, $response);
	}
}

function getHtml ($dir)
{
    $dir = realpath($dir);
    if(! is_dir($dir)) return;

    $dirIterator = new DirectoryIterator($dir);
	$count = 0;
    foreach($dirIterator as $_dir) {

        if($_dir->isDot()) continue;

        //logError ($_dir->getPath();
		
        if(! $_dir->isDir()) {
			$dirName = $_dir->getFilename();
			if(! strpos($dirName, '-jingdian.html')) {
			    continue;
			}
			$count++;
			continue;
			//echo $dirName . "\r\n";
			$i=2;
			while($i<40) {
				$htmlFile = substr($dirName, 0, -5) . '-' . $i . '.html';
				$realHtmlFile = $dir . DIRECTORY_SEPARATOR . $htmlFile;
			    $url = 'http://you.kuxun.cn/' . $htmlFile;
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_USERAGENT, 'firefox');
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/xml'));
				$response = curl_exec($ch);
				$options = curl_getinfo($ch);
				curl_close($ch);
				if($options['http_code']==200 && strpos($response, '<ul class="scenicMapBox_ul">')) {				
				    file_put_contents($realHtmlFile, $response);
				}
				$i++;
			}
            
        }
    }
	echo $count;
}

function getMapHtml ($dir)
{
    global $countryList, $provinceList, $cityList, $sceneryList;
    $htmlDir = $dir;
    $dir = realpath($dir);
    if(! is_dir($dir)) return;

    $dirIterator = new DirectoryIterator($dir);
	$count = 0;
	
     
    foreach($dirIterator as $_dir) {
        if($_dir->isDot()) continue;
		$newImgPath = './guideMap/' . date('m/d', time()-rand(0,90)*24*3600);
        if(! is_dir($newImgPath)) mkdir($newImgPath);

        //logError ($_dir->getPath();
        if(! $_dir->isDir()) {
			$dirName = $_dir->getFilename();
			if(! strpos($dirName, '-ditu.html') ) {
			    continue;
			}
			$content = file_get_contents($dir . DIRECTORY_SEPARATOR . $dirName);
			if(! preg_match('/<div class="Location" style="padding:0 0 0 10px;">(.*)<\/div>/Us', $content, $matchLocation) || !strpos($matchLocation[1], '国内游')) {
			    //echo $dirName . " not match \r\n";
			    continue;
            }
			$tripInfo = explode('&gt;', $matchLocation[1]);
			$province = substr(trim(strip_tags($tripInfo[3])), 0, 0-strlen('旅游'));
			$scenery = array_pop($tripInfo);
			$scenery = substr(trim(strip_tags($scenery)), 0, 0-strlen('地图'));
			
			if(!isset($provinceList['1-'.$province])) {
			    continue;
			}
			$provinceId = $provinceList['1-'.$province];
			
			if(!isset($sceneryList[$provinceId.'-'.$scenery])) {
			    continue;
			}
            $sceneryId = $sceneryList[$provinceId.'-'.$scenery];
			
			if(! preg_match('/<ul class="map_img_list">(.*)<\/ul>/Us', $content, $match)) {
			    continue;
			}
			
			preg_match_all('/<a target="_blank" href="(.*)">/Us', $match[1], $match1);
			//var_dump($match1);continue;
			$i = 1;
			
			foreach($match1[1] as $match) {
			    $imgExt = substr($match, strrpos($match, '.'));
			    $imgPath = $newImgPath . '/' . $sceneryId . '_'. $i.$imgExt;
				if(copyImage($match, $imgPath)) {
				    $i++;
				    $sql = "insert into scenery_map(map_url, scenery_id) values('$imgPath','$sceneryId')";
					mysql_query($sql);
				}
			}
			if($i>0) {
			    unlink($dir . DIRECTORY_SEPARATOR . $dirName);
			
                sleep(5);
			}
        }
    }
}

function getJianjieHtml ($dir)
{
    global $countryList, $provinceList, $cityList, $sceneryList;
	
    $htmlDir = $dir;
    $dir = realpath($dir);
    if(! is_dir($dir)) return;

    $dirIterator = new DirectoryIterator($dir);
	$count = 0;
    foreach($dirIterator as $_dir) {

        if($_dir->isDot()) continue;

        //logError ($_dir->getPath();
        if(! $_dir->isDir()) {
			$dirName = $_dir->getFilename();
			if(! strpos($dirName, '-jingdian.html') ) {
			    continue;
			}
			$content = file_get_contents($dir . DIRECTORY_SEPARATOR . $dirName);
			if(! preg_match('/<div class="Location" style="padding:0 0 0 10px;">(.*)<\/div>/Us', $content, $matchLocation) || !strpos($matchLocation[1], '国内游')) {
			    //echo $dirName . " not match \r\n";
			    continue;
            }
			$tripInfo = explode('&gt;', $matchLocation[1]);
			$province = substr(trim(strip_tags($tripInfo[3])), 0, 0-strlen('旅游'));
			$scenery = array_pop($tripInfo);
			$scenery = substr(trim(strip_tags($scenery)), 0, 0-strlen('旅游景点大全'));
			
			if(!isset($provinceList['1-'.$province])) {
			    continue;
			}
			$provinceId = $provinceList['1-'.$province];
			
			if(!isset($sceneryList[$provinceId.'-'.$scenery])) {
			    continue;
			}
            $sceneryId = $sceneryList[$provinceId.'-'.$scenery];
			if(! preg_match('/<div class="scenicMapBox">(.*)<\/div>/Us', $content, $match)) {
			    echo $dirName . " not match Spot\r\n";
			    continue;
            }
			preg_match_all('/<h3 class="scenicMapBox_t">(.*)<\/ul>/Us', $match[1], $match1);
			foreach($match1[1] as $match) {
			    if(strpos($match, '<span class="title">五星景点</span>')) {
				    $star = 5;
				} else if(strpos($match, '<span class="title">四星景点</span>')) {
				    $star = 4;
				} else {
				    $star = 3;
				}
				
				preg_match_all('/<a href="(.*)" title="(.*)">/Us', $match, $match2);
				
				$spotName = substr($match2[2][0], 0, 0-strlen('旅游'));
				$spotLink = $match2[1][0];
				$realHtmlFile = './you.kuxun.cn' . str_replace('.html', '-jianjie.html', $spotLink);
				$url = 'http://you.kuxun.cn' . str_replace('.html', '-jianjie.html', $spotLink);
				if(! file_exists($realHtmlFile)) {
				    getHttpContent($url, $realHtmlFile);
				}
				$detail = '';
				if(file_exists($realHtmlFile)) {
				    $content = file_get_contents($realHtmlFile);
					if(preg_match('/<div class="IntroCont">(.*)<input type="hidden"/Us', $content, $match)) {
					    $detail = explode('</div>', $match[1]);
						$detail = trim($detail[1]);
					}
				}
				
				$spotSceneryId = 0;
				if(isset($sceneryList[$provinceId.'-'.$spotName])) {
				    $spotSceneryId = $sceneryList[$provinceId.'-'.$spotName];
				}
				
				$sql = " insert into scenery_spot (spot_title, spot_detail, score, scenery_id, spot_scenery_id) values ('".mysql_real_escape_string($spotName)."', 
				'".mysql_real_escape_string($detail)."', '".mysql_real_escape_string($star)."', 
				'".mysql_real_escape_string($sceneryId)."', '".mysql_real_escape_string($spotSceneryId)."')";
				mysql_query($sql);
			}
        }
    }

}

loadDataFromDb();
//getHtml('./you.kuxun.cn/');exit;
getMapHtml ('./you.kuxun.cn/');
//getJianjieHtml ('./spots/');

/* EOF */