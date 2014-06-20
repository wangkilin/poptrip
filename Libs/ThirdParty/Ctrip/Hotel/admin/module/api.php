<?php
//验证是否登录
if (!$session->contain('admin')){
	exit("访问受限.......");
}
?>

<table cellspacing="0" cellpadding="0" class="mod_table">
	<thead>
		<tr>
			<th style="width: 150px;">API名称</th>
			<th  >总频次</th>
			<th >使用频次</th>
			<th >频次重置时间</th>
			<th >今日使用流量(K)</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$i=1;
		foreach ($hotelApiArray as $k=>$val){
			$i++;
			$apiStatues=checkAPI($k);
			foreach ($PHP_APIFunction as $key =>$value){
				if($k==$key) $k=$value;
			}
			
	?>

		<tr class="<?php echo $col%2==0?'double':'';?>">
			<td><?php echo $val;?></td>
			<td><?php echo $apiStatues['AccessCount'];?></td>
			<td><?php echo $apiStatues['CurrentCount'];?></td>
			<td><?php echo $apiStatues['ResetTime'];?></td>
			<td><?php echo empty($IPSDataFlowForToday[$k]['SumDataFlow'])?'0':$IPSDataFlowForToday[$k]['SumDataFlow']?></td>
		</tr>
	
	<?php
		ob_flush(); //缓存输出，先将上面部分输出
		flush();
		sleep(1);
		}
	?>


	</tbody>
</table>

<?php if($is_AllDataFlow=='1' && !empty($responseXML->IPSMonitorDataFlowForTodayResponse->SumDataFlow)){?>
<p>您的联盟站点 今日总请求次数为：<?php echo $responseXML->IPSMonitorDataFlowForTodayResponse->SumRequest;?>次</p>
<p>您的联盟站点 今日总请求流量为：<?php echo $responseXML->IPSMonitorDataFlowForTodayResponse->SumDataFlow;?>K</p>
<?php }?>



<!-- 蒙板end -->
