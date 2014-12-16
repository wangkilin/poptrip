<?php
class OptionModel extends Model
{
    // 数据表前缀
    protected $tablePrefix  =   ' ';
    // 模型名称
    protected $name = 'OptionModel';
    // 数据表名（不包含表前缀）
    protected $tableName = 'vote_option';

    public function getOptionById ($optionId)
    {
        $optionInfo = $this->find($optionId);
        return $optionInfo;
    }

    public function vote ($optionId)
    {
        $sql = 'UPDATE ' . $this->tablePrefix . $this->tableName . '
                SET click_num = click_num + 1
                WHERE option_id = ' . intval($optionId);
        $result = $this->query($sql);

        return $result;
    }

    public function getOptions ($params)
    {
        $sql = 'SELECT * FROM ' . $this->tablePrefix . $this->tableName;
        $sqlWhere = array();
        if (isset($params['voteId'])) {
            $sqlWhere[] = ' vote_id = ' . intval($params['voteId']);
        }

        if ($sqlWhere) {
            $sql = $sql . ' WHERE ' . join(' AND ', $sqlWhere);
        }

        return $this->query($sql);
    }

    public function getOptionsByVoteId($voteId)
    {
        $params = array('voteId'=>intval($voteId));
        $options = $this->getOptions($params);
        if(! $options) {
            $options = array();
        }

        return $options;
    }

    public function addOptions ($params)
    {

    }

    public function updateOptions ($optionId, $params)
    {

    }

    public function deleteOption ($voteId, $optionId)
    {

        return $this->delete(array('where'=> 'vote_id = '.$voteId . ' AND option_id = '.$optionId));
    }

    public function deleteOptionsByVoteId ($voteId)
    {

    }
}
