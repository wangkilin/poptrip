<?php

/**
 * 用于回复的图文消息类型
 */
class WechatNewsResponse extends WechatResponse {

    protected $articles = array();

    protected $msgContent = "
<xml>
  %s
  <ArticleCount>%s</ArticleCount>
  <Articles>
    %s
  </Articles>
</xml>
";

    public function __construct($toUserName, $fromUserName, $articleInfoList) {
        $msgType = parent::MSG_TYPE_NEWS;
        parent::__construct($toUserName, $fromUserName, $msgType);
        $this->articles = $this->createArticles($articleInfoList);
    }

    public function __toString() {
        return sprintf($this->msgContent,
                $this->getMsgHeader(),
                count($this->articles),
                implode($this->articles)
        );
    }

    protected function createArticles($articleInfoList)
    {
        $articles = array();
        foreach($articleInfoList as $_article) {
            if ($_article instanceof WechatNewsArticle) {
                $articles[] = $_article;
            } else if (is_array($_article)) {
                if(isset($_article['title'], $_article['description'], $_article['picUrl'], $_article['url'])) {
                    $articles[] = (new WechatNewsArticle($_article['title'], $_article['description'], $_article['picUrl'], $_article['url']));
                } else if(count($_article)>=4) {
                    $articles[] = (new WechatNewsArtical($_article[0], $_article[1], $_article[2], $_article[4]));
                }
            }
        }

        return $articles;
    }

}

/* EOF */