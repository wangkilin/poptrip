<?php
// 本类由系统自动生成，仅供测试用途
/*
 *
DROP TABLE IF EXISTS `vote_ip`;
CREATE TABLE IF NOT EXISTS `vote_ip` (
  `ip_id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` bigint(13) NOT NULL,
  `vote_id` int(4) NOT NULL,
  `click_time` datetime NOT NULL,
  PRIMARY KEY (`ip_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `vote_list`
--

DROP TABLE IF EXISTS `vote_list`;
CREATE TABLE IF NOT EXISTS `vote_list` (
  `vote_id` int(4) NOT NULL AUTO_INCREMENT,
  `vote_title` varchar(255) NOT NULL,
  `vote_desc` text NOT NULL,
  `is_public` tinyint(1) NOT NULL,
  `create_time` datetime NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `options` text NOT NULL COMMENT '列表样式 html ? ul list?',
  PRIMARY KEY (`vote_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- 表的结构 `vote_option`
--

DROP TABLE IF EXISTS `vote_option`;
CREATE TABLE IF NOT EXISTS `vote_option` (
  `option_id` int(8) NOT NULL AUTO_INCREMENT,
  `option_title` varchar(1000) NOT NULL,
  `option_desc` text NOT NULL,
  `click_num` int(11) NOT NULL,
  `is_public` tinyint(1) NOT NULL,
  `display_style` text NOT NULL COMMENT ' json 串。 加粗， 斜体， 加红',
  `vote_id` int(4) NOT NULL,
  `order_id` mediumint(4) NOT NULL COMMENT '组内序号',
  PRIMARY KEY (`option_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;
 *
 */
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
        $ipModel = D('Ip');
        $ip = $_SERVER['REMOTE_ADDR'];
        $ipInfo = $ipModel->getByIpAndVoteId(ip2long($ip_address), $voteId);
        if(!$voteSettings['is_public']||$voteSettings['']||$voteSettings['']) {
            $this->redirect('index');
        }
        cookie('','');
        session('','');

        $voteResult = $this->_optionModel->vote($optionId);
        if ($voteResult) {
            $this->success('感谢投票！',U('index'));
        } else {
            $this->redirect('index', array('voteId'=>$voteId));
        }
    }
}
