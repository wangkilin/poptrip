<?php
/**
 *@Author        : WangKilin
 *@Email         : wangkilin@126.com
 *@Homepage      : http://www.kinful.com
 *@CreateDate    : 2012-11-7
 *
 *
 * $Id$
 * $LastChangedDate$
 */
function getSringsToBeTranslatedFromFiles($path)
{
    $stringsList = array();
    $dir = new DirectoryIterator($path);
    if(!$dir->isDir()) {
        return null;
    }
    foreach($dir as $fileInfo) {
        if($fileInfo->isDot()) {
            continue;
        }
        if($fileInfo->isDir()) {
            $return = getSringsToBeTranslatedFromFiles($fileInfo->getPathname());
            $stringsList = is_array($return)&&count($return) ? array_merge($stringsList + $return) : $stringsList;
        }
        if($fileInfo->isFile() && $fileInfo->isReadable()) {
            $fileObj = $fileInfo->openFile('r');
            $contents = '';
            while(!$fileObj->eof()) {
                $contents .= $fileObj->fgets();
            }

            preg_match_all('/\$t\->_\((\'\w+\'|"\w+")\)/i', $contents, $match);
            if(isset($match[1]) && count($match[1])) {
                $filename = $fileInfo->getRealPath($fileInfo->getPathname());
                $stringsList[$filename] = array_unique($match[1]);
            }
        }
        $filename = $fileInfo->getFilename();
    }

    return $stringsList;
}

$entrancePath = realpath(dirname(__FILE__) . '/../../');

$lengthPath = strlen($entrancePath);
$stringMaps = getSringsToBeTranslatedFromFiles($entrancePath);
$newStringMaps = array();
$allStrings = array();
foreach($stringMaps as $_filename=>$_strings) {
    $_shortFilename = substr($_filename, $lengthPath);
    foreach($_strings as $key=>$_string) {
        $_strings[$key] = substr($_string, 1, -1);
        if(!isset($allStrings[$_strings[$key]])) {
            $allStrings[$_strings[$key]] = array($_shortFilename);
        } else {
            //unset($_strings[$key]);
            $allStrings[$_strings[$key]][] = $_shortFilename;
            foreach($allStrings[$_strings[$key]] as $_shortFilename) {
                $_strings[$key] = ' ' . $_shortFilename ."\n;" . $_strings[$key];
            }
            $_strings[$key] = ';' . $_strings[$key];
        }
    }
    $newStringMaps[$_shortFilename] = $_strings;
}
//var_dump($stringMaps, $newStringMaps);

foreach($newStringMaps as $filename=>$_strings) {
    echo ";;;;;;;;;;;;;;;;;;;;;;;;;;\n";
    echo '; ' . $filename . "\n";
    echo ";;;;;;;;;;;;;;;;;;;;;;;;;;\n";
    foreach($_strings as $_string) {
        echo $_string . ' = ""' . "\n";
    }
    echo "\n\n";
}
