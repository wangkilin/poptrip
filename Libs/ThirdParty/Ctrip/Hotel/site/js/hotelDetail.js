/**
 * @author xuanzhang
 * hotel_detail page 
 */ 
 //global map stuff
 $.extend(cQuery, {
	replace: function (template, obj) {
		return template.replace(/\$\{([\w\.?]+)\}/g, function (s, k) {
			var keys = k.split('.'), l = keys.length;
			var key = keys[0];
			if (l > 1) {
				var o = obj;
				for (var i = 0; i < l; i++) {
					if (key in o) {
						o = o[key];
						key = keys[i + 1];
					} else return s;
				}
				return o;
			}
			return key in obj ? obj[key] : s;
		});
	},
	format: function (template) {
		var args = arguments, l = args.length;
		if (l > 1) {
			return template.replace(/\$(\d)/g, function (s, k) {
				return args[k] == undefined ? '' : args[k];
			});
		} else return template;
	},
	create: function (tag, attrs) {
		var el = document.createElement(tag);
		for (var p in attrs) {
			if (attrs.hasOwnProperty(p)) {
				if (p == 'cssText') {
					el.style[p] = attrs[p];
				} else {
					el[p] = attrs[p];
				}
			}
		}
		return el;
	}
});

var MadCat = function (fn, cfg) {
	this.events = {};
	fn && fn.call(this, cfg);
};
$.extend(MadCat.prototype, {
	set: function () { },
	get: function () { return null },
	evt: function (key, fn) { this.events[key] = fn },
	init: function () { }
});

	var HotelView = new MadCat(function() {
		this.init = function() {};
		this.set = function() {};
		this.showLoading = function(el, tipsKey) {
			var h = el.clientHeight || el.offsetHeight,
			w = el.clientWidth || el.offsetWidth;
			var paddingTop = Math.abs((h - 80) / 2), h2 = Math.abs(h - paddingTop);
			if (el.loading) {
				el.loading.style.height = h2 + 'px';
				el.loading.style.width = w + 'px';
				el.loading.style.paddingTop = paddingTop + 'px';
				el.loading.style.opacity = '';
				el.loading.style.filter = '';
				el.loading.style.display = '';
			} else {
				var loading_el = $.create('div', {
					 "innerHTML": '<img src="http://pic.ctrip.com/common/loading_50.gif" /></div>',
					 "cssText": $.format('height:$1px;width:$2px;padding-top:$3px;text-align:center;background-color:#fff', h2, w, paddingTop)
				});
				el.appendChild(loading_el);
				el.loading = loading_el;
			}
		};
		this.hideLoading = function(el) {
			el.loading.style.display = 'none';
		};
	});


(function(Ctrip){
	$.ready(function(){
		//selector
		var sidePic = $('.slide_pic');
		var sideList = $('.slide_list li a');
		var bigPic = $('.big_pic img');
		var pageNum = $('.pic_description b');
		var toggleList = $('.hotelList .room_pic');
		var next = $('#next_pic');
		var prev = $('#prev_pic');
		var hideDetail = $('.search_result_box .hide');
		var hotelComment = $('#hotel_comment');
		var commentPre = $('#preCommentList');
		var commentNext = $('#nextCommentList');
		$('.search_result_box').css('display','none');
		Ctrip.picIndex = 0;
		Ctrip.hotelId = $('#main_Search_hotailid').value();
		Ctrip.totalCommentPage = 0;
		Ctrip.currentCommentPage = 0;
		//public method
		Ctrip.index = function(one,list){
			var index = 0;
			for(var i=0;i<list.length;i++){
				if(list[i] == one){
					index = i;
				}
			}
			return index;
		}
		Ctrip.tabs = function(sel,content){
			content[0].style.display = "block";
			sel.bind('click',function(e){
				var self = this;
				var index = Ctrip.index(self,sel);
				sel.removeClass('current');
				$(this).addClass('current');
				content.css('display','none');
				content[index].style.display = "block";
				e.preventDefault();
			})
		}
		Ctrip.triggerEvent = function(element,type){
			var event;
			if(document.createEventObject){
				event = document.createEventObject();
				return element.fireEvent('on'+type,event);
			}else{
				event = document.createEvent('HTMLEvents');
				event.eventName = type;
				event.initEvent(type,true,true);
				return !element.dispatchEvent(event);
			}
		}
		Ctrip.parent = function(ele,depth){
			var par = ele.parentNode;
			for(var i=0;i<depth-1;i++){
				if(par.parentNode){
					par = par.parentNode;
				}
			}
			return par;
		}
		//hotel maps extend to cquery object
		
		var handlePopMap = function (e) {
			e = e || window.event;

			var clientWidth = document.documentElement.clientWidth,
				clientHeight = document.documentElement.clientHeight,
				width = Math.max(clientWidth - 170, 600),
				height = Math.max(clientHeight - 170, 400);
			var popMap = document.getElementById('popMap');
			popMap.style.width = width + 'px';
			popMap.style.height = height + 'px';
			var mapContent = document.getElementById('mapContent');
			mapContent.style.height = height + 'px';
			var trafficDetail = document.getElementById('trafficDetail');
			trafficDetail.style.height = height - 10 + 'px';
			$(popMap).mask();
			popMapView = new PopMapView();
			popMapView.showMapNearbyHotel();



			e.preventDefault ? e.stopPropagation() : e.cancelBubble = true;
			e.preventDefault ? e.preventDefault() : e.returnValue = false;
		}
		$('.viewMap').bind('click', handlePopMap);
		//tabs
			Ctrip.tabs($('.search_main .tab a'),$('.search_result_box'));
			Ctrip.tabs($('.transtab  a'),$('.trans_info'));
		

		//ajax load comment list
		Ctrip.dynamicComment = function(id,page){
			hotelComment.html('<img src="'+Ctrip.siteUrl+'/site/images/loading.gif" style="margin:20px 0px 20px 200px"/>');
			commentPre.removeClass('disableClick');
			commentNext.removeClass('disableClick');
			$.ajax(Ctrip.siteUrl+"/site/ajaxrequest/hotelCommentListRequest.php?hotelID="+id+"&pagesize=5&pageno="+page,{
				method:'GET',
				onsuccess:function(msg){
					hotelComment.html(msg.responseText);
					Ctrip.totalCommentPage = $('#barThisPage_totalPageCount').value();
					Ctrip.currentCommentPage = $('#barThisPage_SelectPageCount').value();
					if(Ctrip.totalCommentPage == 0){
						hotelComment.html("<br/>暂时没有评论");
						$('#commentControl').hide();
					}else{
						$('#commentCurrent').html(Ctrip.currentCommentPage);
						$('#commentTotal').html(Ctrip.totalCommentPage);
						if(Ctrip.totalCommentPage == 1){
							commentPre.addClass('disableClick');
							commentNext.addClass('disableClick');
						} else {
							if(Ctrip.currentCommentPage == 1){
								commentPre.addClass('disableClick');
							}else if(Ctrip.totalCommentPage == Ctrip.currentCommentPage){
								commentNext.addClass('disableClick');
							}
						}

					}

				}
			})
		}
		Ctrip.dynamicComment(Ctrip.hotelId,1);
		commentNext.bind('click',function(e){
			if(!$(this).hasClass('disableClick')){
				Ctrip.currentCommentPage++;
				Ctrip.dynamicComment(Ctrip.hotelId,Ctrip.currentCommentPage);
			}
			e.preventDefault();
		})
		commentPre.bind('click',function(e){
			if(!$(this).hasClass('disableClick')){
				Ctrip.currentCommentPage--;
				Ctrip.dynamicComment(Ctrip.hotelId,Ctrip.currentCommentPage);
			}
			e.preventDefault();
		})

		//trigger book_now event
		$('#book_now').bind('click',function(e){
			$('#hotelBook').trigger('click');
		})
		
		//history cookie remove
		var deleteList = $('.history .delete');
		deleteList.bind('click',function(e){
			var self = this;
			$(this.parentNode).remove();
			$.ajax($('#siteUrl').value()+"/site/ajaxrequest/browseHistoryRequest.php?bkeys="+encodeURI(self.rel),{
				method:'GET',
				onsuccess:function(msg){
					//do nothing
			
				}
			})
			e.preventDefault();
		})
		
		//reg mod calendar
			//extension to date prototype
		Date.prototype.yyyymmdd = function() {
			   var yyyy = this.getFullYear().toString();
			   var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
			   var dd  = this.getDate().toString();
			   return yyyy +'-'+ ((mm.length != 1)?mm:"0"+mm) +'-'+ ((dd.length != 1)?dd:"0"+dd); // padding
	  	};
	  		//check the date format is valid
		Ctrip.isCorrectDate =  function(str){
			var r = str.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);
			if (r == null) return false;
			var d = new Date(r[1], r[3] - 1, r[4]);
			return (d.getFullYear() == r[1] && (d.getMonth() + 1) == r[3] && d.getDate() == r[4]);
		}
	  	var today = new Date().yyyymmdd();
		var checkInDate = $('#hotailDetailCheckindate');
		var checkOutDate = $('#hotailDetailCheckoutdate');
		 Date.prototype.gettimestamp = function(){
			this.setHours(0);
			this.setMinutes(0);
			this.setSeconds(0);
			this.setMilliseconds(0);
			var t = Math.floor(this);
			return t;
		}
		function GetDateStr(dateObj, AddDayCount) {
			dateObj = dateObj.replace(/-/g,'/');
			var today = new Date(dateObj);
			var dd = new Date(today.getTime() + ((24 * 60 * 60 * 1000)*AddDayCount));
			var y = dd.getFullYear();
			var m = dd.getMonth()+1;
			var d = dd.getDate();
			
			if(m.toString().length < 2){
				m = "0" + m;
			}
			if(d.toString().length < 2){
				d = "0" + d;
			}
			
			return y+"-"+m+"-"+d;
		}
		$.mod.load('calendar', '3.0', function () {
			checkInDate.regMod('calendar', '3.0', {
				options: {
				    autoShow: false,
				    showWeek: false
				},
				listeners: {
					onChange: function (input, value) {
						if(!Ctrip.isCorrectDate(value)){
						checkInDate.value(today);
						}else{
							 if(new Date(checkInDate.value().replace(/-/g,'/')).getTime() < new Date().gettimestamp()){
								checkInDate.value(today);
							 }else{
								value = GetDateStr(value,1);
								checkOutDate.value(value);
								checkOutDate[0].setAttribute('minDate',value);
								checkOutDate[0].setAttribute('startDate',value);
							 }

				 		}
					}
				}
			});
			var fillInCheckOut = function(){
				var value = checkInDate.value();
				value = GetDateStr(value,1);
				checkOutDate.value(value);
				checkOutDate[0].setAttribute('minDate',value);
	 			checkOutDate[0].setAttribute('startDate',value);
			}
			checkOutDate.regMod('calendar', '3.0', {
				options: {
				    autoShow: false,
				    showWeek: false
				},
				listeners: {
					onChange: function (input, value) {
				 		if(!Ctrip.isCorrectDate(value)){
				 			fillInCheckOut();
						}else{
							var dayGap = new Date(checkOutDate.value().replace(/-/g,'/')).getTime() - (new Date(checkInDate.value().replace(/-/g,'/')).getTime());
							if(dayGap <= 0){
								alert('离店时间应该大于入住时间');
								fillInCheckOut();
							}else if( dayGap/(60*60*24*1000) > 28){
								alert('只能预定28天内的房间');
								fillInCheckOut();
							}
						}
					}
				}
			});
		});
		//togglelist
		toggleList.bind('click',function(e){
			var id = this.rel;
			var check = $('#'+id).css('display');
			if(check === "none"){
				$('#'+id).css('display','');
			}else{
				$('#'+id).css('display','none');
			}
			e.preventDefault();
		})
		//hide hotel details
		hideDetail.bind('click',function(e){
			var par = Ctrip.parent(this,3);
			par.style.display = "none";
			e.preventDefault();
		})
		
		//slideshow section
		Ctrip.slideshow = (function(){

			var controlButtons = function(controls){
				if(Ctrip.picIndex == 0){
					controls.prev.removeClass('page_up').addClass('page_up_disable');
				}
				if(Ctrip.picIndex == 1){
					controls.prev.removeClass('page_up_disable').addClass('page_up');
				}
				if(Ctrip.picIndex == (Ctrip.totalpage-2)){
					controls.next.removeClass('page_down_disable').addClass('page_down');
				}
				if(Ctrip.picIndex == (Ctrip.totalpage-1)){
					controls.next.removeClass('page_down').addClass('page_down_disable');
				}
			}
			var triggerImgList = function(index,holders,controls){
				Ctrip.picIndex = Math.floor(index/7);
				var movelen = 609*Ctrip.picIndex;
				holders.imgholder.css('left','-'+movelen+'px');
				Ctrip.triggerEvent(holders.imglist[index],"click");
				controlButtons(controls);
			}
			var bindEvents = function(controls,holders){
				controls.next.bind('click',function(e){
					if(!$(this).hasClass('page_down_disable')){
						Ctrip.picIndex++;
						var movelen = 609*Ctrip.picIndex;
						holders.imgholder.css('left','-'+movelen+'px');
						controlButtons(controls);
					}

					e.preventDefault();
				})
				controls.prev.bind('click',function(e){
					if(!$(this).hasClass('page_up_disable')){
						Ctrip.picIndex--;
						var movelen = 609*Ctrip.picIndex;
						holders.imgholder.css('left','-'+movelen+'px');
						controlButtons(controls);
					}
					e.preventDefault();
				})
				holders.imglist.bind('click',function(e){
					e.preventDefault();
					var self = this;
					var index = Ctrip.index(this,holders.imglist);
					pageNum[0].innerHTML = (index+1);
					bigPic[0].src = "/site/images/loading_120.gif";
					var imgObj = document.createElement('img');
					imgObj.src = self.href;
					var alt = self.getElementsByTagName('img')[0].alt;
					imgObj.onload = function(){
						bigPic[0].src  = self.href;
						$('#imagealt').html(alt);
					}
					e.preventDefault();
				})
				holders.sidelist.bind('click',function(e){
					$('#hotelPiclist').trigger('click');
					var index = Ctrip.index(this,holders.sidelist);
					if(this.className === 'show_all'){
						triggerImgList(0,holders,controls);
					}else{
						triggerImgList(index,holders,controls);	
					}
					
				})
			}
			return {
				init:function(controls,holders,perpage){
					var imgsize = holders.imglist.length;
					Ctrip.totalpage = Math.ceil(imgsize/perpage);
					bindEvents(controls,holders);
				}
				
			}
			
		})();
		
		//init slideshow method
		Ctrip.slideshow.init({next:next,prev:prev},{imgholder:$('#scroll_container'),imglist:$('#scroll_container a'),sidelist:sideList},7);
	})
})(window.Ctrip = window.Ctrip || {})
