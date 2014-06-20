/**
 * @author xuanzhang
 * hotel_search page 
 */

(function (Ctrip) {
	$.ready(function () {		
		var repostSearch = $("#repostSearch");
		Ctrip.parent = function (ele, depth) {
			var par = ele.parentNode;
			for (var i = 0; i < depth - 1; i++) {
				if (par.parentNode) {
					par = par.parentNode;
				}
			}
			return par;
		}
		Ctrip.tooltip = function (sel) {

			var tip = document.createElement('div');
			tip.id = "titleTip";
			tip.innerHTML = '<b class="tri_t"></b><div id="contentTip"></div>';
			var ori = '';
			var hideTimer = '';
			var isTrigger = false;
			document.body.appendChild(tip);
			sel.bind('mouseover', function (e) {
				ori = this.title;
				this.title = '';
				if (ori != '') {
					$('#contentTip').html(ori);
					var ofTop = $(this).offset().top + 22;
					var ofLeft = $(this).offset().left;
					$('#titleTip')[0].style.top = ofTop + 'px';
					$('#titleTip')[0].style.left = ofLeft + 'px';
					$('#titleTip').css('display', 'block');
				}
				/*$('#titleTip').bind('mouseout',function(e){
				var self = $(this);
				setTimeout(function(){
				self.hide();
				},500)
				})*/
				e.preventDefault();
			})
			sel.bind('mouseout', function (e) {
				this.title = ori;
				$('#titleTip').hide();
				isTrigger = false;

			})
			/*$('#titleTip').bind('mouseover',function(e){
			clearTimeout(hideTimer);
			isTrigger = true;
			})
			$('#titleTip').bind('mouseout',function(e){
			var self = $(this);
			if(isTrigger){
			setTimeout(function(){
			self.hide();
			},500)
			}
			})*/
			
			

		}
		
		
		var priceInput = document.main_hotelsearchRquest.main_Search_Price;
		var starInput = document.main_hotelsearchRquest.main_Search_Star;
		var orderName = document.main_hotelsearchRquest.main_Search_ordername;
		var orderType = document.main_hotelsearchRquest.main_Search_ordertype;
		var hotelFacility = document.main_hotelsearchRquest.main_Search_hotelfacility;
		var hotelBrand = document.main_hotelsearchRquest.main_Search_hotelbrand;
		var hotelName = document.main_hotelsearchRquest.main_Search_HotelName;
		//format the selector

		var price = priceInput.value;
		var star = starInput.value;
		var facility = hotelFacility.value;
		var brand = hotelBrand.value;
			
		var startValues = [price, star, facility, brand];
		var startRangers = [$('#priceRanger'), $('#starRanger'), $('#facPanger'), $('#brandRanger')];
		for (var i = 0; i < startValues.length; i++) {
			if (startValues[i] != '') {
				startRangers[i].find('.basefix>a').removeClass('cate_all_current').addClass('cate_all');
				startRangers[i].find('a[href="#' + startValues[i] + '"]').addClass('current');
			}
		}
		var sortList = $('#sortHolder>a');
		if (orderName.value != "Recommend") {
			sortList.removeClass('current');
			$('#sortHolder').find('.' + orderName.value).addClass('current');
			var span = $('#sortHolder').find('.' + orderName.value + ' span');
			if (orderType.value == "ASC") {
				span.removeClass('').addClass('tab_tri_up');
			} else {
				span.removeClass('').addClass('tab_tri_down');
			}
		}

		Ctrip.showMap = function(pos,name,id){
			/*var clientWidth = document.documentElement.clientWidth,
				clientHeight = document.documentElement.clientHeight,
				width = 640,
				height = 450;
			
			popMap.style.width = width + 'px';
			popMap.style.height = height + 'px';*/
			var popMap = document.getElementById('pop_map');
			$(popMap).mask();
			var mapEl = document.getElementById('innermap');
			var map  = window.maplet = new window.Maplet(mapEl);
			map.clickToCenter = false;
			//map.showOverview(true, false);
			var cr = window.document.getElementById('ImgCopyright');
			cr.className = 'invisible';
			var nxt = cr.nextElementSibling || cr.nextSibling;
			while (nxt && nxt.nodeType != 1) {
				nxt = nxt.nextSibling;
			}
			nxt.className = 'invisible';
			map.addControl(new MStandardControl());

			var pot = pos.split('|');
			pot = new window.MPoint(pot[1], pot[0]);
			map.centerAndZoom(pot, 12);
			var icon_addr = "http://pic.c-ctrip.com/hotels110127/hotel_pointer.gif",
				shadow_addr = 'http://pic.c-ctrip.com/hotels081118/marker_shadow.png';
			var spotMkr = new window.MMarker(
				pot,
				new window.MIcon(icon_addr, 21, 31, 10, 30),
				null,
				new window.MLabel('<span>' + name + '</span>', {
					xoffset: 30,
					yoffset: 0,
					enableStyle: false
				}),
				new window.MIconShadow(shadow_addr, 40, 34, -5, 10)
			)
			if (spotMkr) {
				map.addOverlay(spotMkr);
				spotMkr.label.div.className = 'searchresult_popname box_shadow';
			}
			var suburl = $('#siteUrl').value()+"/site/ajaxrequest/hotelDetailNearByInfoRequest.php?";
			suburl += 'cityid='+$('#main_Search_CityID').value()+'&hotelid='+id;
			$.ajax(suburl, {
				method: 'GET',
				onsuccess: function (msg) {
					var temp = JSON.parse(msg.responseText);
					var data = temp.hotelPlaceInfo;
					$('#pop_traffic_load').html('');
					var cname = '';
					for(var i in data){
						var html = '<div class="item_container">';
						if(i == 'train'){
							cname = "火车站";
						}else if(i == 'airport'){
							cname = '机场';
						}else if(i == "center"){
							cname = '市中心';
						}else{
							cname = '热门地区';
						}
						html += '<div class="left_type">'+cname+'</div>';
						html += '<div class="right_items">';
						for(var j in data[i]){
							if(data[i].hasOwnProperty(j)){
								html += data[i][j].PlaceName + "距离酒店" + data[i][j].Distance + "公里" ;
								if(data[i][j].ArrivalWay != ''){
									html+="<span data-role=\"jmp\" data-params=\"{'options':{'type':'jmp_text','template':'$jmp_text','content':{'txt':'"+data[i][j].ArrivalWay+"'},'classNames':{'boxType':'jmp_text'},'css':{'maxWidth':450},'position':'bottomLeft-topRight','group':'distance'}}\" class=\"icon_notice\"></span><br/>";
								}else{
									html+="<br/>";
								}
								
							}
						}
						html +='</div><p style="clear:both"></p></div>';
						$('#pop_traffic_load')[0].innerHTML += html;
						/*$.mod.load('jmp','1.0',function(){
							$(document).regMod('jmp','1.0',{});
						})*/
					}
				}
			})
			return false;
			}
		$('#delMap').bind('click',function(e){
			$('#pop_map').unmask();
			e.preventDefault();
		})
		/*$('.viewMap').bind('click',function(e){
			alert(123);
			var clientWidth = document.documentElement.clientWidth,
				clientHeight = document.documentElement.clientHeight,
				width = Math.max(clientWidth - 170, 600),
				height = Math.max(clientHeight - 170, 400);
			var popMap = document.getElementById('popMap');
			popMap.style.width = width + 'px';
			popMap.style.height = height + 'px';
			$(popMap).mask();
			e.preventDefault();	
		})*/
		//$('.viewMap').bind('click', handlePopMap);
		//dyn load subroom data via ajax
		var objSize = function(obj) {
		    var size = 0, key;
		    for (key in obj) {
		        if (obj.hasOwnProperty(key)) size++;
		    }
		    return size;
		};
		var suburl = $('#siteUrl').value()+"/site/ajaxrequest/hotelSubRoomRequest.php?";
		suburl += "hid=" + $('#hotelIdList').value();
		suburl += "&CheckInDate="+$('#main_Search_CheckInDate').value()+"&CheckOutDate="+$('#main_Search_CheckOutDate').value();
		suburl += "&city="+$('#main_Search_CityID').value()+",上海";
		function formatSubHTML(data,index){
			var html ='';
			var displayStr = (index>2)?"style='display:none' ":"";
			html += '<tr '+displayStr+'>\
				<td style="padding-left:20px;"><a href="'+data.hotelDetailSubRoomUrl+'|'+data.tdID+'" title="'+data.RoomName+'" class="room_pic">'+data.RoomName+'</a></td>\
				<td>'+data.getBedTypeName+'</td>\
				<td>'+data.getBreakFastNames+'</td>\
				<td>'+data.getWireInfo+'</td>\
				<td><dfn>¥'+data.getAvaeragePrices+'</dfn></td>\
				<td style="text-align: right;">'+data.guaranteeHtml + data.bookingClickHtml +'</td>\
				</tr>\
				<tr style="display:none">\
				<!--以下是没有房型的详细数据，用hotelID+RoomID 调用D_HotelDetail来实现显示 -->\
				<td colspan="6" id="'+data.tdID+'" name="'+data.tdID+'">'+data.baseRoomDetail+'</td></tr>';
			return html;

		}
		if($('#hotelIdList').value() != ''){
			$.ajax(suburl,{
				method:'GET',
				onsuccess:function(msg){
					var roomData = JSON.parse(msg.responseText);
					for(var key in roomData){
						var value = roomData[key].hotelSubRooms;
						var descText = "{'options':{'type':'jmp_text','template':'$jmp_text','content':{'txt':'"+roomData[key].hotelDescriptions+"'},'classNames':{'boxType':'jmp_text'},'css':{'maxWidth':450},'position':'bottomLeft-topRight','group':'distance'}}";
						$('#briefInfo_'+key).attr('data-params',descText);
						$('#subRoomHotelId_'+key+' tbody').html('');
						var html = '';
						var i =0;
						var size = objSize(value);
						for(var key_2 in value){
							//console.log(formatSubHTML(value[key_2]));
							html += formatSubHTML(value[key_2],i);
							i++;
						}
						$('#subRoomHotelId_'+key+' tbody').html(html);
						if(i > 2){
							$('#resultBox_'+key+'')[0].innerHTML += '<p class="hotel_toggle"><a href="#" class="toggle_down toggle_roomtype">所有房型('+size+')</a></p>';
						}
					}
					$.mod.load('jmp','1.0',function(){
							$(document).regMod('jmp','1.0',{});
					})
					Ctrip.bindAjaxEvents();
				}
			})
			//bind tooltip plugin
			Ctrip.tooltip($('.icon_desc_text'));			
			
		}
		

		//bind click events
		/*$.mod.load('jmp','1.0',function(){
				$(document).regMod('jmp','1.0',{});
		})*/
		Ctrip.bindAjaxEvents = function(){
			var expandRoomTypes = $('.search_result_box .toggle_roomtype');
			expandRoomTypes.bind('click', function (e) {
				var trs = $(Ctrip.parent(this, 2).getElementsByTagName('tbody')[0]).find('>tr');
				if ($(this).hasClass("toggle_down")) {
					$(this).removeClass("toggle_down").addClass("toggle_up");
					var roomNum = $(this).html().match(/\d+/g)[0];
					this.title = roomNum;
					$(this).html('收起房型');
					for (var i = 0; i < trs.length; i += 2) {
						trs[i].style.display = "";
					}
				} else {
					$(this).removeClass("toggle_up").addClass("toggle_down");
					$(this).html("所有房型(" + this.title + ")");
					for (var i = 6; i < trs.length; i += 2) {
						trs[i].style.display = "none";
					}
				}
				e.preventDefault();
			})
			var roomAjax = $('.room_pic');
			roomAjax.bind('click', function (e) {
				var pars = this.href.split('|');
				if ($('#' + pars[1])[0].parentNode.style.display == '') {
					$('#' + pars[1])[0].parentNode.style.display = 'none';
				} else {
					if ($('#' + pars[1]).html() != '') {
						$('#' + pars[1])[0].parentNode.style.display = '';
					} else {
						$('#' + pars[1])[0].parentNode.style.display = '';
						/*$('#' + pars[1]).html("<div style='margin-left:50px'><img src='" + Ctrip.siteUrl + "/site/images/loading.gif'></div>");
						$.ajax(pars[0], {
							method: 'GET',
							onsuccess: function (msg) {
								console.log(msg);
								$('#' + pars[1]).html(msg.responseText);
							}
						})*/
					}
				}
				
				e.preventDefault();
			})
		$('.room_list').bind('click', function (e) {
			var tar;
			if (e.target) {
				tar = e.target;
			} else if (e.srcElement) {
				tar = e.srcElement;
			}

			if (tar.className === 'hide') {
				var par = Ctrip.parent(tar, 3);
				par.style.display = 'none';
				e.preventDefault();
			}

			//var par = Ctrip.parent(this,3);
			//par.style.display = "none";

		})
		}

		//history cookie remove
		var deleteList = $('.history .delete');
		deleteList.bind('click', function (e) {
			var self = this;
			$(this.parentNode).remove();
			$.ajax($('#siteUrl').value()+"/site/ajaxrequest/browseHistoryRequest.php?bkeys=" + encodeURI(self.rel), {
				method: 'GET',
				onsuccess: function (msg) {
					//do nothing
				}
			})
			e.preventDefault();
		})
		//a simple delegation method


		$('.clearFilter').bind('click', function (e) {
			var title = this.title;
			if (title == "price") {
				priceInput.value = "";
			} else if (title == "star") {
				starInput.value = "";
			} else if (title == "facility") {
				hotelFacility.value = "";
			} else if (title == "brand") {
				hotelBrand.value = "";
				hotelName.value = "";
			}
			repostSearch.trigger('click');
			e.preventDefault();
		})
		$('.search_cate li>a').bind('click', function (e){
			var para = decodeURI(this.href.split('#')[1]);
			var filter = {
				price: /\d+-\d+/g,
				star: /^[0-9]$/g,
				facility: /[a-zA-Z]+/g,
				brand: /[\u4e00-\u9fa5]+/g
			}
			if (filter.price.test(para)) {
				priceInput.value = para;
			} else if (filter.facility.test(para)) {
				hotelFacility.value = para;
			} else if (filter.star.test(para)) {
				starInput.value = para;
			} else if (filter.brand.test(para)) {
				hotelBrand.value = para;
			}
			repostSearch.trigger('click');
			e.preventDefault();

		})
		sortList.bind('click', function (e) {
			var sortName = this.href.split('#')[1];
			if (sortName == "Recommend" && $(this).hasClass('current')) {
				e.preventDefault();
			} else if (sortName == "Recommend" && !$(this).hasClass('current')) {
				orderName.value = "Recommend";
				orderType.value = "DESC";
				repostSearch.trigger('click');
			} else if (sortName != "Recommend" && !$(this).hasClass('current')) {
				orderName.value = sortName;
				var cname = this.getElementsByTagName('span')[0].className;
				if (cname == "tab_tri_up") {
					orderType.value = "ASC";
				} else {
					orderType.value = "DESC";
				}
				repostSearch.trigger('click');
			} else if (sortName != "Recommend" && $(this).hasClass('current')) {
				var cname = this.getElementsByTagName('span')[0].className;
				if (cname == "tab_tri_up") {
					orderType.value = "DESC";
				} else {
					orderType.value = "ASC";
				}
				repostSearch.trigger('click');
			}
		})
	})
})(window.Ctrip = window.Ctrip || {})
