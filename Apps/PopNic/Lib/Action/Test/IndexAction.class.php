<?php

class IndexAction extends Action
{
    private $_unCallMethods = array (
  2 => '__construct',
  11 => '__set',
  12 => 'get',
  13 => '__get',
  14 => '__isset',
  15 => '__call',
  20 => '__destruct',
);
    protected function getMethods($object)
    {
        return (get_class_methods($object));
    }
    protected function _initialize()
    {
        $methods = $this->getMethods($this);
        $reflectionClass = new ReflectionClass($this);
        $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $_method) {
            $methodName = $_method->getName();
            if (in_array($methodName, $this->_unCallMethods)) {
                continue;
            }
            $methodComment = $_method->getDocComment();
            preg_match('/\/\*([^@]*)@/us', (string)$methodComment, $match);
            if (!$match) {
                preg_match('/\/\*(.*)\*\//us', (string)$methodComment, $match);
            }
            if ($match && isset($match[1])) {
                $methodComment = $match[1];
            } else {
                $methodComment = $methodName;
            }
            echo '<a href="'.U(GROUP_NAME.'/'.MODULE_NAME.'/'.$methodName).'">' . trim($methodComment, " \r\n*"). '</a>' . "<br/>\n";

        }

        echo '<hr/>';
        //$this->importLrc();
    }
    /**
     * Index
     */
    public function Index ()
    {
    }

    /**
     * 导入字幕到数据库
     */
    public function importLrc ()
    {
        $dir = 'E:\\Production';
        $categoryId = $this->storeDirIntoDb('root', 0);
        $lrcFiles = $this->getLrcFiles($dir, $categoryId);
        //print_r($lrcFiles);
        foreach ($lrcFiles as $_fileInfo) {
            $this->_loadLrcFileIntoDb ($_fileInfo);
        }
    }

    protected function _loadLrcFileIntoDb ($fileInfo)
    {
        $categoryId = $fileInfo['categoryId'];
        $filename = basename($fileInfo['filePath']);
        $fp = fopen($fileInfo['filePath'], 'r');
        $dbFileModel = M('lrc_file');
        $dbLrcModel = M('lrc_content');
        $dbFileModel->query('set names "utf8"');
        $fileData = array('file_name' => mb_convert_encoding($filename, 'utf-8', 'gb2312'),
                          'mp3_file_path'=>mb_convert_encoding(substr($fileInfo['filePath'], 0, -4), 'utf-8', 'gb2312') .'.mp3',

                          'category_id'=>$categoryId);
        $fileId = $dbFileModel->add($fileData);
        $previousContent = null;
        while(!feof($fp)) {
            $line=fgets($fp);
            preg_match('/\[(.*)\](.*)/', $line, $match);
            if ($match) {
                $time = $match[1];
                $content = trim($match[2]);
                if ($content==='' && $previousContent==='') {
                    continue;
                }
                $contentData = array('start_time'=>$time,
                        'line_content'=>$content,
                        'file_id'=>$fileId);
                $dbLrcModel->add($contentData);
                $previousContent = $content;
            }
        }
        fclose($fp);
        return ;
    }

    protected function getLrcFiles ($dir, $categoryId=0)
    {
        $lrcFilesList = array();
        if (! file_exists($dir)) {
            return $lrcFilesList;
        }
        $dirHandler = new DirectoryIterator($dir);
        while($dirHandler->valid()) {
            if ($dirHandler->isDot()) {
                $dirHandler->next();
                continue;
            }
            $fileName = $dirHandler->getFilename();
            //echo $i++ . ' ' . $dir . '/' . $fileName . '<br/>';
            $categoryName = mb_convert_encoding($dirHandler->getFilename(), 'UTF8', 'GB2312');
            if ($dirHandler->isDir()) {
                $_dir = $dir . '/' . $fileName;
                $_categoryId = $this->storeDirIntoDb($categoryName, $categoryId);
                $_lrcFilesList = $this->getLrcFiles($_dir, $_categoryId);
                $lrcFilesList = array_merge($lrcFilesList, $_lrcFilesList);
            } else if ($dirHandler->isFile()) {
                $filePath = $dir . '/' . $fileName;
                if (substr($fileName, -4) == '.lrc') {
                    $_mp3FileName = substr($filePath, 0, -4).'.mp3';
                    if (! is_file($_mp3FileName)) {
                        echo $_mp3FileName."<br/>";
                        $dirHandler->next();
                        continue;
                    }
                } else if ($fileName=='.meta') {
                    $this->_loadCdMeta($filePath, $categoryId);
                    $dirHandler->next();
                    continue;
                //} else if ($fileName=='package.zip') {
                    //unlink($filePath);
                } else {
                    $dirHandler->next();
                    continue;
                }
                $lrcFileInfo = array('categoryId'    => $categoryId,
                                     'filePath'      => $filePath
                               );
                $lrcFilesList [] = $lrcFileInfo;
            } else {
                echo $dir . '/' . $fileName;
            }
            $dirHandler->next();
        }

        return $lrcFilesList;
    }

    protected function _loadCdMeta($metaFile, $categoryId)
    {
        if (! is_file($metaFile)) {
            return;
        }
        $metaInfo = parse_ini_file($metaFile);
        $grade = explode(' ', $metaInfo['grade'], 2);//  1-入门，2-初级，3-中级，4-高级，5-熟练
        $star = explode(' ', $metaInfo['rating'], 2);//  五星
        $length = explode(' ', $metaInfo['length'], 2);//  HH:MM:SS
        $cdInfo = array(
                'category_id'    => $categoryId,
                'en_name'        => $metaInfo['name_en'],
                'cn_name'        => mb_convert_encoding($metaInfo['name_cn'], 'utf-8', 'gb2312'),
                'author_en'      => $metaInfo['author_en'],
                'author_cn'      => mb_convert_encoding($metaInfo['author_cn'], 'utf-8', 'gb2312'),
                'grade'          => $grade[0],
                'cd_length'      => $length[0],
                'stars'          => $star[0],
                'intro_en'       => $metaInfo['introduction_en'],
                'intro_cn'       => mb_convert_encoding($metaInfo['introduction_cn'], 'utf-8', 'gb2312'),

        );
        M('lrc_cd_info')->add($cdInfo);

        return;
    }

    protected function storeDirIntoDb($dirName, $parentId=0)
    {
        $dirId = 0;
        $dbModel = M('lrc_category');
        $data = array('category_name'=>$dirName, 'parent_id'=>$parentId);
        $dirId = $dbModel->add($data);

        return $dirId;
    }

    /**
     * 测试方法
     */
    public function Test ()
    {
        echo __FUNCTION__;
    }

}
/* EOF */