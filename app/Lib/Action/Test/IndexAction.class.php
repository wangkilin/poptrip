<?php
function getMethods($object)
{
    return (get_class_methods($object));
}
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
    protected function _initialize()
    {
        $methods = getMethods($this);
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
        $lrcFiles = $this->getLrcFiles($dir);
        //print_r($lrcFiles);
        foreach ($lrcFiles as $_fileInfo) {
            $this->_loadLrcFileIntoDb ($_fileInfo);
        }
    }

    protected function _loadLrcFileIntoDb ($fileInfo)
    {
        $categoryId = $fileInfo['categoryId'];
        $filename = $fileInfo['filename'];
        $dbModel = M('lrc_file');
        return ;
    }

    protected function getLrcFiles ($dir, $categoryId=0)
    {
        $lrcFilesList = array();
        if (! file_exists($dir)) {
            echo $dir;
            return $lrcFilesList;
        }
        $dirHandler = new DirectoryIterator($dir);
        static $i=0;
        while($dirHandler->valid()) {
            if ($dirHandler->isDot()) {
                $dirHandler->next();
                continue;
            }
            $fileName = $dirHandler->getFilename();
            echo $i++ . ' ' . $dir . '/' . $fileName . '<br/>';
            //$fileName = mb_convert_encoding($dirHandler->getFilename(), 'UTF8', 'GB2312');
            if ($dirHandler->isDir()) {
                $_dir = $dir . '/' . $fileName;
                $_categoryId = $this->storeDirIntoDb($_dir, $categoryId);
                $_lrcFilesList = $this->getLrcFiles($_dir, $_categoryId);
                $lrcFilesList = array_merge($lrcFilesList, $_lrcFilesList);
            } else if ($dirHandler->isFile()) {
                $filePath = $dir . '/' . $fileName;
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