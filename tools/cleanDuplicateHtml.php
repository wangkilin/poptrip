<?php
set_time_limit(0);
function cleanHtml ($dir)
{
    $htmlDir = $dir;
    $dir = realpath($dir);
	echo $dir;
    if(! is_dir($dir)) return;

    $dirIterator = new DirectoryIterator($dir);
    foreach($dirIterator as $_dir) {

        if($_dir->isDot()) continue;

        //logError ($_dir->getPath();
        if($_dir->isDir()) {
			$dirName = $_dir->getFilename();
			$subDir = $dir . DIRECTORY_SEPARATOR . $dirName;
			$baseFileContent = file_get_contents($subDir . DIRECTORY_SEPARATOR . 'jingdian-1.html');
			$i = 2;
			error_log('<a href="'.$htmlDir. $dirName .'/jingdian-1.html' .'">a</a>'."\r\n", 3, './jingdianLink.html');
			while($i<=84) {
			    if(! file_exists($subDir . DIRECTORY_SEPARATOR . 'jingdian-'.$i.'.html')) {
				    $i++;
				    continue;
                }
			    $fileContent = file_get_contents($subDir . DIRECTORY_SEPARATOR . 'jingdian-'.$i.'.html');
				if($baseFileContent == $fileContent) {
				    //echo $subDir . DIRECTORY_SEPARATOR . 'jingdian-'.$i.'.html' . "\r\n";
                                @unlink($subDir . DIRECTORY_SEPARATOR . 'jingdian-'.$i.'.html');
				} else {
				    error_log('<a href="'.$htmlDir. $dirName .'/jingdian-'.$i.'.html' .'">a</a>'."\r\n", 3, './jingdianLink.html');
				}
				$i++;
			}
            //sleep(10);
            
        }
    }
}
cleanHtml('./travel.qunar.com/place/city/');
exit;

/* EOF */