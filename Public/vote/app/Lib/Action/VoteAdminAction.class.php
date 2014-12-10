<?php
// 本类由系统自动生成，仅供测试用途
class VoteAdminAction extends Action
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
        $voteId = isset($_REQUEST['voteId']) ? $_REQUEST['voteId'] : 0;
        if ($voteId) {// 查看一个投票数据
            $voteInfo = $this->_voteModel->getVoteById($voteId);
            if ($voteInfo) {
                $voteOptions = $this->_optionModel->getOptionsByVoteId($voteId);
                $this->assign('voteInfo', $voteInfo);
                $this->assign('voteOptions', $voteOptions);
                $this->display('ViewVote');
            } else {
                $url = defined(GROUP_NAME)&&GROUP_NAME ? (GROUP_NAME.'/'.MODULE_NAME.'/index')
                                 : (MODULE_NAME.'/index');
                $url = U($url);

                $this->error('没找到ID为'.$voteId . '投票数据', $url);
            }
        } else {
            $voteList = $this->_voteModel->getVote();
            $this->assign('voteList', $voteList);
            $this->display('Index');
        }
    }

    public function addVote ()
    {
        $voteInfo = array();
        if (strtoupper($_SERVER['REQUEST_METHOD'])=='POST') {
            $voteInfo['vote_title'] = isset($_POST['vote_title']) ? $_POST['vote_title'] : '';
            $voteInfo['vote_desc'] = isset($_POST['vote_desc']) ? $_POST['vote_desc'] : '';
            $voteInfo['is_public'] = isset($_POST['is_public']) ? $_POST['is_public'] : '';
            $voteInfo['start_time'] = isset($_POST['start_time']) ? $_POST['start_time'] : '';
            $voteInfo['end_time'] = isset($_POST['end_time']) ? $_POST['end_time'] : '';
            $voteInfo['options'] = array();
            $voteInfo['options']['display_style'] = isset($_POST['display_style']) ? $_POST['display_style'] : '';
            $voteInfo['options'] = json_encode($voteInfo['options']);
            $result = $this->_voteModel->addVote($voteInfo);
            $voteId = $this->_voteModel->getLastInsID();
            if ($result) {
                $this->success('添加成功', U('addOptioins', array('voteId'=>$voteId)));
            } else {
                $this->error('添加失败');
            }
        }
        $this->assign('action', 'add');
        $this->assign('voteInfo', $voteInfo);

        $this->display('Edit');
    }

    public function updateVote ()
    {
        $voteId = isset($_POST['voteId']) ? intval($_POST['voteId']) : 0;
        if (!voteId) {
            redirect(U('index'));
        }
        $voteInfo = array();
        if (strtoupper($_SERVER['REQUEST_METHOD'])=='POST') {
            $voteInfo['vote_title'] = isset($_POST['vote_title']) ? $_POST['vote_title'] : '';
            $voteInfo['vote_desc'] = isset($_POST['vote_desc']) ? $_POST['vote_desc'] : '';
            $voteInfo['is_public'] = isset($_POST['is_public']) ? $_POST['is_public'] : '';
            $voteInfo['start_time'] = isset($_POST['start_time']) ? $_POST['start_time'] : '';
            $voteInfo['end_time'] = isset($_POST['end_time']) ? $_POST['end_time'] : '';
            $voteInfo['options'] = array();
            $voteInfo['options']['display_style'] = isset($_POST['display_style']) ? $_POST['display_style'] : '';
            $voteInfo['options'] = json_encode($voteInfo['options']);
            $where = array('vote_id' => $voteId);
            $result = $this->_voteModel->save($voteInfo, $where);
            if (false!==$result) {
                $this->success('修改成功', U('idnex'));
            } else {
                $this->error('修改失败');
            }
        } else {
            $voteInfo = $this->_voteModel->getVoteById($voteId);
        }

        $this->assign('voteInfo', $voteInfo);
        $this->assign('action', 'edit');

        $this->display('Edit');
    }

    public function deleteVote ()
    {
        $voteId = isset($_POST['voteId']) ? intval($_POST['voteId']) : 0;
        if (!$voteId) {
            redirect(U('index'));
        }
        $result = $this->_voteModel->deleteVote($voteId);
        if ($result) {
            $this->_optionModel->deleteOptionsByVoteId($voteId);
            $this->success('删除成功', 'index');
        } else {
            $this->error('删除失败', 'index');
        }
    }

    public function getOptions ()
    {

    }

    public function addOptions ()
    {

    }

    public function updateOptions ()
    {

    }

    public function deleteOptions ()
    {

    }
}
