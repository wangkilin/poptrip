<?php
/**
 * 本页面负责友情链接
 */
include_once ("../appData/db_friendlink.php");
include_once ("../include/friendlink.php");
$friends=new friendlink();
$friends->siteFriendLinkArray=$siteFriendLinkArray;
?>
<div class="friendlink box_blue">
<h3>友情链接</h3>
<div class="friendlink_pic basefix"><?php 
$friends->getImageLinks();//获取图片链接
echo $friends->responseHtml;
?></div>
<p>
<?php 
$friends->getWordsLinks();//获取文字链接
echo $friends->responseHtml;
?>
</p>
</div>
