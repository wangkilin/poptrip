<?php
/**
 * 首页酒店的搜索模块
 */
$bar_search_cityid=$index_cityID;//设置本页面上的城市ID
$searchHotelPageSize=$SiteHotelSearch_pagesize;//设置查询时的酒店返回条数在site.config.php中定义
?>
<form id="hotelsearchRquest" name="hotelsearchRquest"
	action="" method="post">
	<div class="search_box">
				<h3>酒店搜索</h3>
				<table cellspacing="0" cellpadding="0">
					<tbody>
						<tr>
							<th style="width:60px;">入住城市</th>
							<td><dfn>*</dfn><input id="barSearch_CityName" name="barSearch_CityName" type="text" value="上海" class="input_text input_181" />
							<input id="barSearch_CityID" name="barSearch_CityID" type="hidden" value="2" class="input_text input_181" />
							<input id="barSearch_pagesize" name="barSearch_pagesize" type="hidden" value="<?php echo $searchHotelPageSize;?>"/>
							</td>
						</tr>
						<tr>
							<th>入住日期</th>
							<td><dfn>*</dfn><input id="barSearch_CheckInDate" name="barSearch_CheckInDate" type="text"  class="input_text input_181" /></td>
						</tr>
						<tr>
							<th>离店日期</th>
							<td><dfn>*</dfn><input id="barSearch_CheckOutDate" name="barSearch_CheckOutDate" type="text"  class="input_text input_181" /></td>
						</tr>
						<tr>
							<th>酒店级别</th>
							<td>
								<select name="barSearch_Star" id="barSearch_Star">
									<option value="">不限</option>
									<option value="5">五星级/豪华</option>
									<option value="4">四星级/高档</option>
									<option value="3">三星级/舒适</option>
									<option value="2">二星级以下/经济</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>价格范围</th>
							<td>
								<select name="barSearch_Price">
								   <option value="">不限</option>
									<option value="600-9999999">&yen;600以上</option>
									<option value="451-600">&yen;451-600</option>
									<option value="301-450">&yen;301-450</option>
									<option value="150-300">&yen;150-300</option>
									<option value="0-149">&yen;150以下</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>酒店名称</th>
							<td><input id="barSearch_HotelName" name="barSearch_HotelName" type="text" value="" class="input_text input_layout input_181" />
							</td>
						</tr>
						<tr>
							<th>酒店位置</th>
							<td><input id="barSearch_Location_Zone" name="barSearch_Location_Zone" type="text" value="" class="input_text input_layout input_181" />
								<input id="barSearch_Location_ZoneId_District" type="hidden" name="barSearch_Location_ZoneId_District"/>
								<input id="barSearch_LZId" type="hidden" name="barSearch_LZId"/>
								<input id="barSearch_Location_Type" type="hidden" name="barSearch_Location_Type"/>
								<!-- 将"行政区ID,行政区名称-商业区ID,商业区名称-景区ID,景区名称"拼接后，放在barSearch_Location_ZoneId_District 中 -->
							</td>
						</tr>
					</tbody>
				</table>
				<div class="btn_box"><input type="button" value="搜 索" id="barSearch_submit" class="btn_orange"/></div>
			</div>
			</form>