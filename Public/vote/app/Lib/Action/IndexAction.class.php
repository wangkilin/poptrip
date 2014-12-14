<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action
{
    private $_voteModel = null;
    private $_optionModel = null;

    public function _initialize ()
    {
        $this->_voteModel = D('Vote');
        $this->_optionModel = D('Option');
    }

    public function index()
    {
        $voteId = isset($_REQUEST['voteId']) ? intval($_REQUEST['voteId']) : 0;
        if (!$voteId) {
            $voteInfo = $this->_voteModel->getCurrentVote();
        } else {
            $voteInfo = $this->_voteModel->getVoteById($voteId);
        }
        if ($voteInfo) {
            $options = $this->_optionModel->getOptionsByVoteId($voteInfo['vote_id']);
        } else {
            $options = array();
        }
        $this->assign('voteInfo', $voteInfo);
        $this->assign('options', $options);
        $this->display('Index');
    }

    public function vote ()
    {
        $voteId = isset($_REQUEST['voteId']) ? intval($_REQUEST['voteId']) : 0;
        $optionId = isset($_REQUEST['optionId']) ? intval($_REQUEST['optionId']) : 0;
        if (!$voteId || !$optionId) {
            $this->redirect('index');
        }
        $voteInfo = $this->_voteModel->getVoteById($voteId);
        if (! $voteInfo) {
            $this->redirect('index');
        }
        $optionInfo = $this->_optionModel->getOptionById($optionId);
        if (! $optionInfo) {
            $this->redirect('index');
        }
        if ($optionInfo['vote_id']!=$voteId) {
            $this->redirect('index');
        }
        $voteSettings = @json_decode($voteInfo['vote_option'], true);

        $voteResult = $this->_optionModel->vote($optionId);
        if ($voteResult) {
            $this->success('感谢投票！',U('index'));
        } else {
            $this->redirect('index', array('voteId'=>$voteId));
        }
    }
}