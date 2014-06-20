<?php
/**
 * 负责提供酒店评论的列表数据
 */
//示例：http://127.0.0.1:8888/site/ajaxrequest/hotelCommentListRequest.php?hotelID=625&pagesize=5&pageno=1

include_once ('../../SDK.config.php');//配置文件加载--必须加载这个文件
include_once (ABSPATH.'sdk/API/Hotel/D_HotelCommentListPage.php');//加载酒店点评接口这个接口的封装类-带有分页
include_once (ABSPATH.'site/module/main_comment.php');//加载酒店点评接口处理逻辑

//设置参数
$thisPage_totalPageCount=0;//本评论的总页数
$thisPage_SelectPageCount=0;//当前选择的页数

//酒店的携程评价，需要用户点击”酒店点评“后才做加载。初始化时不加载这些数据
$hotelID=$_GET["hotelID"];//获取酒店的ID
$pagesize=$_GET["pagesize"];//每页显示多少数据
$pageno=$_GET["pageno"];//显示第几页
$thisPage_SelectPageCount=$pageno;
if(strlen($hotelID)>0)
{
//调用带有分页的酒店列表接口
$getHotelCommentWithPage=new page_hotelComment($hotelID,$pagesize,$pageno,1);
$getHotelCommentWithPage->recordCount;//数据总数是多少
$thisPage_totalPageCount=$getHotelCommentWithPage->totalPageCount>4?'4':$getHotelCommentWithPage->totalPageCount;//符合条件的数据的总条数
}
else
{
	echo "有什么想告诉大家！";
}
?>
<input id="barThisPage_totalPageCount" value="<?php echo $thisPage_totalPageCount;?>" type="hidden" name="barThisPage_totalPageCount"/>
<input id="barThisPage_SelectPageCount" value="<?php echo $thisPage_SelectPageCount;?>" type="hidden" name="barThisPage_SelectPageCount"/>