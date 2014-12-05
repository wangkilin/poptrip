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
        if ($voteId) {
            $voteInfo = $this->_voteModel->getVoteById($voteId);
            if ($voteInfo) {

            } else {
                $url = GROUP_NAME ? (GROUP_NAME.'/'.MODULE_NAME.'/index')
                                 : (MODULE_NAME.'/index');
                redirect($url);
            }
        } else {
            $this->_voteModel->getVote();
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
            $result = $this->_voteModel->add($voteInfo);
            if ($result) {
                $this->success('添加成功', U('idnex'));
            } else {
                $this->error('添加失败');
            }
        }
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
                $this->success('添加成功', U('idnex'));
            } else {
                $this->error('添加失败');
            }
        } else {
            $voteInfo = $this->_voteModel->getVoteById($voteId);
        }

        $this->assign('voteInfo', $voteInfo);

        $this->display('Edit');
    }

    public function deleteVote ()
    {

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