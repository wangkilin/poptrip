<?php
/**
 * 
 * 数据库连接函数
 * @author cltang
 *
 */
class DB
{
    var $queryNum = 0;
    // 执行SQL语句的次数
    protected $link;
    // MySQL连接标识
    public function __construct($dbhost, $dbuser, $dbpw, $dbname = '', $charset = 'utf_8', $dbPrefix = 'mac_')
    {
        $this->link = mysql_connect($dbhost, $dbuser, $dbpw, true);
        $this->dbPrefix = $dbPrefix;
        if ($this->link) {
            mysql_select_db($dbname, $this->link);
            $charset = str_replace('_', '', $charset);
            mysql_query("SET NAMES '$charset'", $this->link);
        } else {
            $this->throwException('MySQL server error report!');
        }
    }

    //开始执行SQl语句
    //设置SQL语句，会自动把SQL语句里的#@__替换为$this->dbPrefix(在配置文件中为$cfg_dbprefix)
    function query($sql, $limit = null)
    {
    	if (!empty($limit) && stripos($sql, 'LIMIT') === false) {
            $sql = $sql . " LIMIT " . $limit;
        }

        $prefix = "#@__";
        $sql = str_replace($prefix, $this->dbPrefix, $sql);
        
    
        $rs = mysql_query($sql, $this->link);
        
       // echo $rs.'<br/><br/>';
       // echo print_r($rs);
        if ($rs) {
            $this->queryNum++;
            return $rs;
        } else {
            return false;
        }
    }

    /**
     * 返回结果集的数组形式
     * @return array
     */
    function fetch_array($rs, $result_type = MYSQL_ASSOC)
    {
        return @mysql_fetch_array($rs, $result_type);
    }

//	 * 返回结果集的数组形式row
    public function fetch_row($query)
    {
        return mysql_fetch_row($query);
    }
    
//	 * 操作影响数据
    public function affected_row()
    {
        return @mysql_affected_rows();
    }
    

    /**
     * 执行一条SQL语句返回是否成功
     * @param string $sql  SQL语句
     * @return boolean
     */
    public function execute($sql)
    {
        if ($this->query($sql)) {
            $this->queryNum++;
            return true;
        } else {
            return false;
        }
    }

    /**
     * 得到结果集的第一个数据
     * @param string $sql   SQL语句
     * @return mixed
     */
    public function getOne($sql)
    {
        if (!$rs = $this->query($sql, 1)) {
            return false;
        }
        $row = $this->fetch_array($rs);
        $this->free($rs);
        return @array_shift($row);
    }

    /**
     * 返回结果集的一行
     * @param string $sql  SQL语句
     * @return mixed
     */
    public function getRow($sql)
    {
        if (!$rs = $this->query($sql, 1)) {
            return false;
        }
        $row = $this->fetch_array($rs);
        $this->free($rs);
        return $row;
    }

    /**
     * 返回所有结果集
     * @param string $sql   SQL语句
     * @param string $limit SQL语句的LIMIT限制
     * @return mixed
     */
    public function getAll($sql, $limit = null)
    {
        if (!$rs = $this->query($sql, $limit)) {
            return false;
        }
        $all_rows = array();
        while ($rows = $this->fetch_array($rs)) {
            $all_rows[] = $rows;
        }
        $this->free($rs);
        return $all_rows;
    }

    /**
     * 执行INSERT命令.返回AUTO_INCREMENT
     * 返回0为没有插入成功
     * @param string $sql  SQL语句
     * @return integer
     */
    public function insert($sql)
    {
        $this->query($sql);
        return mysql_insert_id();
    }

    //该函数取回数据库记录数
    function getRowsNum($sql)
    {
        $rowno = 0;
        $query = $this->query($sql);
        $rowno = mysql_num_rows($query);
        return $rowno;
    }
    
	function getNums($query)
    {
        $rowno = 0;
        $rowno = @mysql_num_rows($query);
        return $rowno;
    }
    

    public function insert_id()
    {
        return mysql_insert_id($this->link);
    }

    /**
     * 取所有行的第一个字段信息
     *
     * @param string $sql   SQL语句
     * @return array
     * @access public
     */
    function getCol($sql)
    {
        $res = $this->query($sql);
        if ($res !== false) {
            $arr = array();
            while ($row = mysql_fetch_row($res)) {
                $arr[] = $row[0];
            }

            return $arr;
        } else {
            return false;
        }
    }

    function autoExecute($table, $field_values, $mode = 'INSERT', $where = '')
    {
        $field_names = $this->getCol('DESC ' . $table);

        $sql = '';
        if ($mode == 'INSERT') {
            $fields = $values = array();
            foreach ($field_names AS $value) {
                if (array_key_exists($value, $field_values) == true) {
                    $fields[] = '`' . $value . '`';
                    $values[] = "'" . $field_values[$value] . "'";
                }
            }

            if (!empty($fields)) {
                $sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')';
            }
        } else {
            $sets = array();
            foreach ($field_names AS $value) {
                if (array_key_exists($value, $field_values) == true) {
                    $sets[] = '`' . $value . "` = '" . $field_values[$value] . "'";
                }
            }

            if (!empty($sets)) {
                $sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $sets) . ' WHERE ' . $where;
            }
        }

        if ($sql) {
            return $this->query($sql);
        } else {
            return false;
        }
    }

    /**
     * 释放结果集
     * @param resource $rs 结果集
     * @return boolean
     */
    public function free($rs)
    {
        return mysql_free_result($rs);
    }

    /**
     * 关闭数据库
     *
     * @access public
     * @return boolean
     */
    public function close()
    {
        return mysql_close($this->link);
    }

    /**
     * 获取执行SQL语句的个数
     * @access public
     * @return integer
     */
    public function getQueryNum()
    {
        return $this->queryNum;
    }

    /**
     * 获取错误信息
     * @return void
     * @access public
     */
    public function getError()
    {
        echo mysql_errno($this->link) . " : " . mysql_error($this->link);
    }

    /**
     * 抛出一个异常信息
     * @param string $message 异常信息
     * @return void
     */
    protected function throwException($message)
    {
        throw new Exception($message);
    }
}
?>