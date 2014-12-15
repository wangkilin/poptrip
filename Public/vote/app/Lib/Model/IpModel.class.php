<?php
class IpModel extends Model
{
    // 数据表前缀
    protected $tablePrefix  =   ' ';
    // 模型名称
    protected $name = 'IpModel';
    // 数据表名（不包含表前缀）
    protected $tableName = 'vote_ip';

    public function getByIpAndVoteId ($ip, $voteId)
    {
        $sql = 'SELECT * FROM ' . $this->tablePrefix . $this->tableName;
        $sqlWhere = array();
        $sqlWhere[] = ' vote_id = ' . intval($voteId);
        $sqlWhere[] = ' ip = ' . ip2long($ip);
        $sql = $sql . ' WHERE ' . join(' AND ', $sqlWhere);

        return $this->query($sql);
    }

    public function addIp ($ip, $voteId)
    {
        $ipInfo = array('ip'=>ip2long($ip),'vote_id'=>$voteId);

        return $this->add($ipInfo);
    }
}
