<?php
/**
 * 定义首页上酒店排行榜
 * 从/site/ajaxrequest/hotelChartsRequest.php 中调用
 */
?>
	<div class="box_orange" style="z-index:10;">
				<h3>酒店排行榜</h3>
				<div id="hotelranklist">
					<img src="<?php echo $UnionSite_domainName;?>/site/images/loading.gif" style="margin:20px 0px 20px 130px"/>
				</div>	
				
				<div class="more">
					<a href="#" class="togglelist">上海<span></span></a>
					<!-- 浮出层 begin -->
					<ul style="display:none;z-index:1000;position:absolute;" id="switchCity">
						<li><a href="#" class="cityOption">上海</a></li>
						<li><a href="#" class="cityOption">北京</a></li>
						<li><a href="#" class="cityOption">广州</a></li>
						<li><a href="#" class="cityOption">深圳</a></li>
						<li><a href="#" class="cityOption">武汉</a></li>
						<li><a href="#" class="cityOption">西安</a></li>
						<li><a href="#" class="cityOption">南京</a></li>
						<li><a href="#" class="cityOption">成都</a></li>
						<li><a href="#" class="cityOption">天津</a></li>
						<li><a href="#" class="cityOption">重庆</a></li>
					</ul>
					<!-- 浮出层 end -->
				</div>
			</div>