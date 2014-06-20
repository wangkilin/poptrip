
(function(window, undefined){
	var iJS = function(){};
	iJS.extend = function(){
		var options, copy,
		target = arguments[0] || {},
		i = 1,
		length = arguments.length,
		deep = false;
		
		if ( typeof target === "boolean" ) {
			deep = target;
			target = arguments[1] || {};
			i = 2;
		}
		if ( length === i ) {
			target = this;
			--i;
		}
		for ( ; i < length; i++ ) {
			if ( (options = arguments[ i ]) != null ) {
				for ( name in options ) {
					copy = options[ name ];
					if ( target === copy ) {
						continue;
					}
					if ( copy !== undefined ) {
						target[ name ] = copy;
					}
				}
			}
		}
		return target;
	};
	if(!Function.prototype.bind || typeof Function.prototype.bind !== 'function'){
		iJS.extend(Function.prototype, {
			bind:function(a){
				var b = this,
					c = Array.prototype.slice.call(arguments,1);
				return function(){
					return b.apply(a,c.concat(Array.prototype.slice.call(arguments,0)))
				}
			}
		})
	}
	var ajaxSettings = function(win){
		var ajaxops={
			url:'',
			type:'GET',
			async:true,
			dataType:'html',
			data:null,
			beforeSend:null,
			success:function(){},
			error:function(){},
			complete:null,
			cache:true
		}
		var s = navigator.userAgent.indexOf('Firefox')>0,
			//isIE = /*@cc_on!@*/!1,
			ie678 = !-[1,],
			doc  = win.document,
			head = doc.getElementsByTagName('head')[0];
			
		var createXMLHttpRequest = function(){
			var xmlHttp;
			try{
				xmlHttp = new XMLHttpRequest();
			}catch (e){
				try{
					xmlHttp = new ActiveXObject('Msxml2.XMLHTTP');
				}catch (e){
					try{
						xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
					}catch (e){
						alert('Sorry!Your browser does not support AJAX!');
						return false;
					}
				}
			}
			return xmlHttp;
		}
		var request = function(xmlHttp,sops){
			if(( xmlHttp.status >= 200 && xmlHttp.status < 300 ) || xmlHttp.status === 304 || xmlHttp.status === 1223 || xmlHttp.status === 0){
				var msg;
				switch(sops.dataType){
					case 'html':
						msg=xmlHttp.responseText;
						break;
					case 'xml':
						msg=xmlHttp.responseXML;
						break;
					case 'json':
						msg=xmlHttp.responseText;
						if(msg != ''){
							msg=(new Function('return ('+msg+')'))();
						}
						break;
					default:
						msg=xmlHttp.responseText;
						break;
				}
				sops.success(msg);
			}else{
				sops.error();
			}
		}
		var sAjax = function(options){
			var sops=iJS.extend({},ajaxops,options);
			
			if(sops.url){
				var xmlHttp = createXMLHttpRequest(),
					requestDone = false;
				if(!xmlHttp){return false;}
				var hasPara = /\?/.test(sops.url);
				if(sops.type.toUpperCase() == 'GET' && sops.data){
					sops.url += hasPara ? "&" : "?" + sops.data;
					sops.data = null;
				}
				if(!sops.cache){sops.url += hasPara ? "&ts=" : "?ts=" + (new Date).getTime();}
				
				
					xmlHttp.onreadystatechange = function(){		
						if(xmlHttp.readyState === 4){
							request(xmlHttp,sops);
							if(sops.complete && !requestDone){
								sops.complete();
								requestDone = true;
							}
						}
					}
				
				if(sops.beforeSend){
					sops.beforeSend();
				}
				xmlHttp.open(sops.type,sops.url,sops.async);
				if(sops.type.toUpperCase() == 'POST'){
					xmlHttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded;charset=utf-8');
				}
				xmlHttp.send(sops.data);
			}
		}
		var cAjax = function(options){
			var cops = iJS.extend({},ajaxops,options),
				hasPara = /\?/.test(cops.url),
				done = false;
			if(cops.type.toUpperCase() == 'GET' && cops.data){
				cops.url += hasPara ? "&" : "?" + cops.data;
			}
			if(!cops.cache){cops.url += hasPara ? "&ts=" : "?ts=" + (new Date).getTime();}
			var script = doc.createElement('script');
			var callback = function(isSucc){
				if(isSucc){
					if(typeof SUGGESTION_RESULT != 'undefined'){
						done = true;
						cops.success(SUGGESTION_RESULT);
						delete SUGGESTION_RESULT;
					}else{
						cops.error();
					}
				}else{
					cops.error();
				}
				script.onload = script.onerror = script.onreadystatechange = null;
				if( head && script.parentNode ){
					head.removeChild(script);
				}
			}
			if(ie678){
				script.onreadystatechange = function(){
					var readyState = this.readyState;
					if(!done && (readyState == 'loaded' || readyState == 'complete')){
						callback(true);
					}
				}
			}else{
				script.onload = function(){
					callback(true);
				}
				script.onerror = function(){
					callback();
				}
			}
			script.src = cops.url;
			script.charset = "utf-8"
			head.insertBefore(script, head.firstChild);
		}
		return {sDomain:sAjax,cDomain:cAjax};
	}(window)
	
	iJS.extend({
		unbind: function(oTarget, sEventType, fnHandler) {
			if(oTarget.listeners && oTarget.listeners[sEventType]){
				var listeners = oTarget.listeners[sEventType];
				for(var i = listeners.length-1;i >= 0 && fnHandler;i--){
					if(listeners[i] == fnHandler){
						listeners.splice(i,1);
					}
				}
				if((!listeners.length || !fnHandler) && listeners["_handler"]){
					oTarget.removeEventListener ? oTarget.removeEventListener(sEventType == 'propertychange' ? 'input' : sEventType, listeners["_handler"], false) : oTarget.detachEvent('on' + (sEventType == 'input' ? 'propertychange' : sEventType), listeners["_handler"]);		
					delete oTarget.listeners[sEventType];
				}
			}	
		},
		bind: function(oTarget, sEventType, fnHandler) {
		    if(!oTarget){
		        return;
		    }
			oTarget.listeners = oTarget.listeners || {};
			var listeners = oTarget.listeners[sEventType] = oTarget.listeners[sEventType] || [];
			listeners.push(fnHandler);
			if(!listeners["_handler"]){
				listeners["_handler"] = function(e){
					var e = e || window.event;
					for(var i = 0,fn;fn = listeners[i++];){
						if(fn.call(oTarget,e) === false){
							e.preventDefault ? e.stopPropagation() : e.cancelBubble = true;
							e.preventDefault ? e.preventDefault() : e.returnValue = false;
							return false;
						}
					}
				}
				oTarget.addEventListener ? oTarget.addEventListener(sEventType == 'propertychange' ? 'input' : sEventType, listeners["_handler"], false) : oTarget.attachEvent('on' + (sEventType == 'input' ? 'propertychange' : sEventType), listeners["_handler"]);
			}	
		},
		doHandler: function(oTarget,sEventType){
			if(oTarget.dispatchEvent){
				var e = document.createEvent('Event');
				e.initEvent(sEventType == 'propertychange' ? 'input' : sEventType,true,true);
				oTarget.dispatchEvent(e);
			}else{
				oTarget.fireEvent('on' + (sEventType == 'input' ? 'propertychange' : sEventType));
			}
		},
		insertAfter: function(newEl,targetEl){
			var parentEl = targetEl.parentNode;
			if(parentEl.lastChild == targetEl){
				parentEl.appendChild(newEl);
			}else{
				parentEl.insertBefore(newEl,targetEl.nextSibling);
			}
		},
		makeClass: function(constructor,prototype) {
			var c = constructor || function(){},
				p = prototype || {};
			return function() {
				for(var atr in p) {
					arguments.callee.prototype[atr] = p[atr];
				}			
				c.apply(this,arguments);
			}
		},
		format: function(temp){
			var arg = arguments;
			if(arg.length > 1){
				return temp.replace(/\$(\d+)/g,function(s,k){
					return arg[k] === undefined ? '' : arg[k];
				})
			}else{
				return temp;
			}
		},
		ajax: ajaxSettings.sDomain,
		jsonp: ajaxSettings.cDomain
	});
	window.iJS = iJS;
})(window);

(function(C, undefined){
	var popMap = function(type){
		this.init();
	}
	var mapPrototype = {
		init: function(){
			this.mapTab = document.getElementById('mapIconFilter');
			this.mapTabItems = this.mapTab.getElementsByTagName('a');
			this.transInfoBox = document.getElementById('transInfoBox');
			this.btnTraffic = document.getElementById('btnTraffic');
			this.delMap = document.getElementById('delMap');
			this.trafficDetail = document.getElementById('trafficDetail');

			this.change = {
				"restaurant": this.createRestaurant,
				"entertain": this.createEntertain,
				"shopping": this.createShopping,
				"traffic": this.createTraffic,
				"subway": this.createSubway,
				"scenic": this.createScenic,
				"nearbyHotel":this.createNearbyHotel
			};
			var tempMessage = mapMessageConfig['temp'];
			this.temp = {
				'bus': '<span class="bus_rank">${index}</span><h4><span class="total_time">${time}'+ tempMessage[1] +'</span>${line}</h4><dl class="bus_route_detail"><dt>'+ tempMessage[0] +' ${distance}km / ${station}'+ tempMessage[3] +' / '+ tempMessage[4] +'${poisNum}'+ tempMessage[5] +'</dt>${detail}</dl>',
				'car': '<dt>'+ tempMessage[0] +' '+ tempMessage[2] +'${distance}km / ${time}'+ tempMessage[1] +'</dt>'
			}
			this.inited = false;
			iJS.unbind(this.mapTab, 'click');
			iJS.unbind(this.btnTraffic, 'click');
			iJS.bind(this.mapTab, 'click', this.handleTabClick.bind(this));
			iJS.bind(this.btnTraffic, 'click', this.showMapTraffic.bind(this));
		},
		handleTabClick: function(evt){
			var e = evt || window.event,
				target = e.target || e.srcElement;
			switch(target.className){
				case "hotel":
					this.showMapNearbyHotel();
					break;
				case "train":
					this.showMapSubway();
					break;
				case "sight":
					this.showMapScenic();
					break;
				case "restaurant":
					this.showMapRestaurant();
					break;
				case "shopping":
					this.showMapShopping();
					break;
				case "entertainment":
					this.showMapEntertain();
					break;
				default:
					break;
			}	
		},
		resizeMap: function(){
			if(this.timeResize){
				clearTimeout(this.timeResize);
			}
			if(!this.inited) return;
			this.timeResize = setTimeout(function(){
				var clientWidth = document.documentElement.clientWidth,
					clientHeight = document.documentElement.clientHeight,
					width = Math.max(clientWidth - 160, 600),
					height = Math.max(clientHeight - 160, 400);
				var popMap = document.getElementById('popMap');
				popMap.style.width = width + 'px';
				popMap.style.height = height + 'px';
				var mapContent = document.getElementById('mapContent');
				mapContent.style.height = height + 'px';
				var trafficDetail = $('#trafficDetail');
				trafficDetail.css('height', height - 10 + 'px');
				trafficDetail.find('.bus_route').css('height', height - 10 - 111 + 'px');
				trafficDetail.find('.drive_route').css('height', height - 10 - 111 + 'px');
				var mapEl = this.mapWin.document.getElementById('map');
				mapEl.style.width = width + 'px';
				mapEl.style.height = height + 'px';
				this.map.resize(width, height);

				var scrollLeft = document.documentElement.scrollLeft || document.body.scrollLeft;
				var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
				popMap.style.left = ((clientWidth - width) / 2 + scrollLeft) + 'px';
				popMap.style.top = ((clientHeight - height) / 2 + scrollTop) + 'px';
				var data = $(popMap).data('__mask__');
				var styleTxt='background:#000;position:absolute;left:0;top:0;width:'+Math.max(clientWidth,document.body.scrollWidth)+'px;height:'+Math.max(clientHeight,document.body.scrollHeight)+'px;';
				if(data.maskDiv){
					data.maskDiv.style.cssText=styleTxt+'filter:progid:DXImageTransform.Microsoft.Alpha(opacity=50);opacity:0.5;';
					if (cQuery.browser.isIE && data.maskIframe){
						data.maskIframe.style.cssText=styleTxt+'filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);opacity:0;';
					}
				}
			}.bind(this), 100)
		},
		initMaplet: function(){
			var me = this;
			if(!this.mapMain){
				this.mapMain = document.getElementById('mapContent');
				iJS.bind(this.delMap, 'click', function(){
					var ifm = this.mapMain.getElementsByTagName('iframe')[0];
					$('#popMap').unmask();
					this.mapMain.removeChild(ifm);
					this.inited = false;
				}.bind(this))
				var license = this.create('div', {
					innerHTML: mapMessageConfig['license'],
					cssText: "position:absolute; left:80px; bottom:3px; z-index:10;",
					className: "map_license"
				});
				this.mapMain.appendChild(license);
				$(window).bind("resize", this.resizeMap.bind(this));
			}
			HotelView.showLoading(this.mapMain);
			this.makeIframe(this.mapMain, addressUrlConfig['mapIframe'], function(el) {
				 me.makeMap(this, el, function() {
					var _this = me;
					this.maplet.addControl(new this.MStandardControl());
					this.document.getElementById('LayerControl').className = 'fix_width'; 
					HotelView.hideLoading(_this.mapMain);
					_this.inited = true;
					_this.change[_this.type].call(_this);
				}); 
			});
		},
		create: function(tag, attrs) {
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
		},
		makeIframe: function(el, src, callback) {
			var h = parseInt(el.style.height, 10),
				w = parseInt(el.style.width, 10);
			var ifm = this.create('iframe', {
				src: src,
				width: '100%',
				height: '100%',
				frameBorder: 'none',
				cssText: 'border:none;background:#fff;'
			});
			el.appendChild(ifm);
			var win = (ifm.window || ifm.contentWindow);
			this.mapWin = win;
			win.addEventListener ? win.addEventListener('load', function(){
				callback.call(win, el);
			}, false) : win.attachEvent('onload', function() {
				callback.call(win, el);
			})
		},
		makeMap: function(win, mapDiv, callback) {
			var mapEl = win.document.getElementById('map');
			mapEl.style.width = mapDiv.style.width || (mapDiv.offsetWidth + 'px');
			mapEl.style.height = mapDiv.style.height || (mapDiv.offsetHeight + 'px');
			//win.MOUSEWHEEL = false;
			var map = this.map = win.maplet = new win.Maplet(mapEl);
			map.clickToCenter = false;
			//map.showOverview(true, false);
			var cr = win.document.getElementById('ImgCopyright');
			cr.className = 'invisible';
			var nxt = cr.nextElementSibling || cr.nextSibling;
			while (nxt && nxt.nodeType != 1) {
				nxt = nxt.nextSibling;
			}
			nxt.className = 'invisible';
			callback.apply(win);
		},
		showMapNearbyHotel: function(){
			var tabs = this.mapTabItems;
			for(var i=0, l=tabs.length; i<l; i++){
				var tab = tabs[i];
				if(tab.className == 'hotel' || tab.className == 'hotel_clicked'){
					tab.className = 'hotel_clicked';
				}else{
					tab.className = tab.className.replace('_clicked', '');
				}
			}
			this.transInfoBox.className = 'trans_info_box trans_info_hidden';
			this.type = "nearbyHotel";
			if(!this.inited){
				this.initMaplet();
			}else{
				this.change[this.type].call(this);
			}
		},
		showMapRestaurant: function(){
			var tabs = this.mapTabItems;
			for(var i=0, l=tabs.length; i<l; i++){
				var tab = tabs[i];
				if(tab.className == 'restaurant' || tab.className == 'restaurant_clicked'){
					tab.className = 'restaurant_clicked';
				}else{
					tab.className = tab.className.replace('_clicked', '');
				}
			}
			this.transInfoBox.className = 'trans_info_box trans_info_hidden';
			this.type = "restaurant";
			if(!this.inited){
				this.initMaplet();
			}else{
				this.change[this.type].call(this);
			}
		},
		showMapEntertain: function(){
			var tabs = this.mapTabItems;
			for(var i=0, l=tabs.length; i<l; i++){
				var tab = tabs[i];
				if(tab.className == 'entertainment' || tab.className == 'entertainment_clicked'){
					tab.className = 'entertainment_clicked';
				}else{
					tab.className = tab.className.replace('_clicked', '');
				}
			}
			this.transInfoBox.className = 'trans_info_box trans_info_hidden';
			this.type = "entertain";
			if(!this.inited){
				this.initMaplet();
			}else{
				this.change[this.type].call(this);
			}
		},
		showMapShopping: function(){
			var tabs = this.mapTabItems;
			for(var i=0, l=tabs.length; i<l; i++){
				var tab = tabs[i];
				if(tab.className == 'shopping' || tab.className == 'shopping_clicked'){
					tab.className = 'shopping_clicked';
				}else{
					tab.className = tab.className.replace('_clicked', '');
				}
			}
			this.transInfoBox.className = 'trans_info_box trans_info_hidden';
			this.type = "shopping";
			if(!this.inited){
				this.initMaplet();
			}else{
				this.change[this.type].call(this);
			}
		},
		showMapSubway: function(){
			var tabs = this.mapTabItems;
			for(var i=0, l=tabs.length; i<l; i++){
				var tab = tabs[i];
				if(tab.className == 'train' || tab.className == 'train_clicked'){
					tab.className = 'train_clicked';
				}else{
					tab.className = tab.className.replace('_clicked', '');
				}
			}
			this.transInfoBox.className = 'trans_info_box trans_info_hidden';
			this.type = "subway";
			if(!this.inited){
				this.initMaplet();
			}else{
				this.change[this.type].call(this);
			}
		},
		showMapScenic: function(){
			var tabs = this.mapTabItems;
			for(var i=0, l=tabs.length; i<l; i++){
				var tab = tabs[i];
				if(tab.className == 'sight' || tab.className == 'sight_clicked'){
					tab.className = 'sight_clicked';
				}else{
					tab.className = tab.className.replace('_clicked', '');
				}
			}
			this.transInfoBox.className = 'trans_info_box trans_info_hidden';
			this.type = "scenic";
			if(!this.inited){
				this.initMaplet();
			}else{
				this.change[this.type].call(this);
			}
		},
		showMapTraffic: function(){
			if(this.transInfoBox.className == 'trans_info_box'){
				this.transInfoBox.className = 'trans_info_box trans_info_hidden';
			}else{
				var tabs = this.mapTabItems;
				for(var i=0, l=tabs.length; i<l; i++){
					var tab = tabs[i];
					tab.className = tab.className.replace('_clicked', '');
				}
				this.transInfoBox.className = 'trans_info_box';
			};
			if(this.type == "traffic"){
				return false;
			}else{
				this.type = "traffic";
			}
			
			if(!this.inited){
				this.initMaplet();
			}else{
				this.change[this.type].call(this);
			}
		},
		
		createRestaurant: function(){
			this.map.clearOverlays();
			this.addCenterMarker();

			if(!this.restaurantMarkers){
				this.restaurantMarkers = {};
				var length = 0;
				
				if(!(C.nearFacility && C.nearFacility.restaurant)){
					return;
				}
				var nf = C.nearFacility,
					nf_data = nf['restaurant'];
				
				for(var o in nf_data){
					var obj = nf_data[o];
					if (!obj.position || !obj.position['lat'] || !obj.position['lon'] || (obj.position['lat'] == "0" && obj.position['lon'] == "0") ) continue;
					var pos = new this.mapWin.MPoint(obj.position['lon'], obj.position['lat']);
					var newMkr = this.createFacilityMarker(pos, 'restaurant', obj.name);
					if (newMkr) {
						this.restaurantMarkers[o] = newMkr;
						newMkr.icon.div.title = obj.name + '    '+ mapMessageConfig['distance'] + obj.distance+ 'km';
						this.map.addOverlay(newMkr);
						length++;
					}
				}
				if(length){
					this.map.setAutoZoom();
				}
			}else{
				if(!(C.nearFacility && C.nearFacility.restaurant)){
					return;
				}
				this.restaurantMarkers = {};
				var nf = C.nearFacility,
					nf_data = nf['restaurant'];
				for(var o in nf_data){
					var obj = nf_data[o];
					if (!obj.position || !obj.position['lat'] || !obj.position['lon'] || (obj.position['lat'] == "0" && obj.position['lon'] == "0") ) continue;
					var pos = new this.mapWin.MPoint(obj.position['lon'], obj.position['lat']);
					var newMkr = this.createFacilityMarker(pos, 'restaurant', obj.name);
					if (newMkr) {
						this.restaurantMarkers[o] = newMkr;
						newMkr.icon.div.title = obj.name + '    '+ mapMessageConfig['distance'] + obj.distance+ 'km';
						this.map.addOverlay(newMkr);
					}
				}
				this.map.setAutoZoom();
			}
		},
		createEntertain: function(){
			this.map.clearOverlays();
			this.addCenterMarker();

			if(!this.entertainMarkers){
				this.entertainMarkers = {};
				var length = 0;
				
				if(!(C.nearFacility && C.nearFacility.entertain)){
					return;
				}
				var nf = C.nearFacility,
					nf_data = nf['entertain'];
				
				for(var o in nf_data){
					var obj = nf_data[o];
					if (!obj.position || !obj.position['lat'] || !obj.position['lon'] || (obj.position['lat'] == "0" && obj.position['lon'] == "0") ) continue;
					var pos = new this.mapWin.MPoint(obj.position['lon'], obj.position['lat']);
					var newMkr = this.createFacilityMarker(pos, 'entertain', obj.name);
					if (newMkr) {
						this.entertainMarkers[o] = newMkr;
						newMkr.icon.div.title = obj.name + '    '+ mapMessageConfig['distance'] + obj.distance+ 'km';
						this.map.addOverlay(newMkr);
						length++;
					}
				}
				if(length){
					this.map.setAutoZoom();
				}
				
			}else{
				if(!(C.nearFacility && C.nearFacility.entertain)){
					return;
				}
				this.entertainMarkers = {};
				var nf = C.nearFacility,
					nf_data = nf['entertain'];
				for(var o in nf_data){
					var obj = nf_data[o];
					if (!obj.position || !obj.position['lat'] || !obj.position['lon'] || (obj.position['lat'] == "0" && obj.position['lon'] == "0") ) continue;
					var pos = new this.mapWin.MPoint(obj.position['lon'], obj.position['lat']);
					var newMkr = this.createFacilityMarker(pos, 'entertain', obj.name);
					if (newMkr) {
						this.entertainMarkers[o] = newMkr;
						newMkr.icon.div.title = obj.name + '    '+ mapMessageConfig['distance'] + obj.distance+ 'km';
						this.map.addOverlay(newMkr);
					}
				}
				this.map.setAutoZoom();
			}
		},
		createShopping: function(){
			this.map.clearOverlays();
			this.addCenterMarker();

			if(!this.shoppingMarkers){
				this.shoppingMarkers = {};
				var length = 0;
				
				if(!(C.nearFacility && C.nearFacility.shopping)){
					return;
				}
				var nf = C.nearFacility,
					nf_data = nf['shopping'];
				
				for(var o in nf_data){
					var obj = nf_data[o];
					if (!obj.position || !obj.position['lat'] || !obj.position['lon'] || (obj.position['lat'] == "0" && obj.position['lon'] == "0") ) continue;
					var pos = new this.mapWin.MPoint(obj.position['lon'], obj.position['lat']);
					var newMkr = this.createFacilityMarker(pos, 'shopping', obj.name);
					if (newMkr) {
						this.shoppingMarkers[o] = newMkr;
						newMkr.icon.div.title = obj.name + '    '+ mapMessageConfig['distance'] + obj.distance+ 'km';
						this.map.addOverlay(newMkr);
						length++;
					}
				}
				if(length){
					this.map.setAutoZoom();
				}
			}else{
				if(!(C.nearFacility && C.nearFacility.shopping)){
					return;
				}
				this.shoppingMarkers = {};
				var nf = C.nearFacility,
					nf_data = nf['shopping'];
				for(var o in nf_data){
					var obj = nf_data[o];
					if (!obj.position || !obj.position['lat'] || !obj.position['lon'] || (obj.position['lat'] == "0" && obj.position['lon'] == "0") ) continue;
					var pos = new this.mapWin.MPoint(obj.position['lon'], obj.position['lat']);
					var newMkr = this.createFacilityMarker(pos, 'shopping', obj.name);
					if (newMkr) {
						this.shoppingMarkers[o] = newMkr;
						newMkr.icon.div.title = obj.name + '    '+ mapMessageConfig['distance'] + obj.distance+ 'km';
						this.map.addOverlay(newMkr);
					}
				}
				this.map.setAutoZoom();
			}
		},
		
		createSubway: function(){
			this.map.clearOverlays();
			this.addCenterMarker();

			if(!this.subwayMarkers){
				this.subwayMarkers = {};
				var length = 0
				
				if(!(C.nearFacility && C.nearFacility.subwaystation)){
					return;
				}
				var nf = C.nearFacility,
					nf_data = nf['subwaystation'];
				
				for(var o in nf_data){
					var obj = nf_data[o];
					if (!obj.position || !obj.position['lat'] || !obj.position['lon'] || (obj.position['lat'] == "0" && obj.position['lon'] == "0") ) continue;
					var pos = new this.mapWin.MPoint(obj.position['lon'], obj.position['lat']);
					var newMkr = this.createFacilityMarker(pos, 'subway', obj.name);
					if (newMkr) {
						this.subwayMarkers[o] = newMkr;
						newMkr.icon.div.title = obj.name + '    '+ mapMessageConfig['distance'] + obj.distance+ 'km';
						this.map.addOverlay(newMkr);
						length++;
					}
				}
				if(length){
					this.map.setAutoZoom();
				}
			}else{
				if(!(C.nearFacility && C.nearFacility.subwaystation)){
					return;
				}
				this.subwayMarkers = {};
				var nf = C.nearFacility,
					nf_data = nf['subwaystation'];
				for(var o in nf_data){
					var obj = nf_data[o];
					if (!obj.position || !obj.position['lat'] || !obj.position['lon'] || (obj.position['lat'] == "0" && obj.position['lon'] == "0") ) continue;
					var pos = new this.mapWin.MPoint(obj.position['lon'], obj.position['lat']);
					var newMkr = this.createFacilityMarker(pos, 'subway', obj.name);
					if (newMkr) {
						this.subwayMarkers[o] = newMkr;
						newMkr.icon.div.title = obj.name + '    '+ mapMessageConfig['distance'] + obj.distance+ 'km';
						this.map.addOverlay(newMkr);
					}
				}
				this.map.setAutoZoom();
			}
		},
		createScenic: function(){
			this.map.clearOverlays();
			this.addCenterMarker();

			if(!this.scenicMarkers){
				this.scenicMarkers = {};
				var length = 0;
				
				if(!(C.nearFacility && C.nearFacility.scenic)){
					return;
				}
				var nf = C.nearFacility,
					nf_data = nf['scenic'];
				
				for(var o in nf_data){
					var obj = nf_data[o];
					if (!obj.position || !obj.position['lat'] || !obj.position['lon'] || (obj.position['lat'] == "0" && obj.position['lon'] == "0") ) continue;
					var pos = new this.mapWin.MPoint(obj.position['lon'], obj.position['lat']);
					var newMkr = this.createFacilityMarker(pos, 'scenic', obj.name);
					if (newMkr) {
						this.scenicMarkers[o] = newMkr;
						newMkr.icon.div.title = obj.name + '    '+ mapMessageConfig['distance'] + obj.distance+ 'km';
						this.map.addOverlay(newMkr);
						length++;
					}
				}
				if(length){
					this.map.setAutoZoom();
				}
			}else{
				if(!(C.nearFacility && C.nearFacility.scenic)){
					return;
				}
				this.scenicMarkers = {};
				var nf = C.nearFacility,
					nf_data = nf['scenic'];
				for(var o in nf_data){
					var obj = nf_data[o];
					if (!obj.position || !obj.position['lat'] || !obj.position['lon'] || (obj.position['lat'] == "0" && obj.position['lon'] == "0") ) continue;
					var pos = new this.mapWin.MPoint(obj.position['lon'], obj.position['lat']);
					var newMkr = this.createFacilityMarker(pos, 'scenic', obj.name);
					if (newMkr) {
						this.scenicMarkers[o] = newMkr;
						newMkr.icon.div.title = obj.name + '    '+ mapMessageConfig['distance'] + obj.distance+ 'km';
						this.map.addOverlay(newMkr);
					}
				}
				this.map.setAutoZoom();
			}
		},
		
		createTraffic: function(){
			this.map.clearOverlays(true);
			this.addCenterMarker();
			
			if(!this.trafficCreated){
				iJS.ajax({
					url:addressUrlConfig['trafficinfo'],
					dataType:'json',
					success:function(json){
						if(json){
							var div = document.createElement('div');
							div.className = 'spot_select';
							var select = document.createElement('select');

							var res = [],
								i = 1;
							for(var o in json){
								var item = json[o];
								if(item['place']){
									var links = [],
										bus = [],
										car = [];
									if(item['busRouteReverse'] == 'T'){
										bus.push('busRouteReverse');
									}
									if(item['busRoute'] == 'T'){
										bus.push('busRoute');
									}
									
									if(item['carRouteReverse'] == 'T'){
										car.push('carRouteReverse');
									}
									if(item['carRoute'] == 'T'){
										car.push('carRoute');
									}
									
									if(car.length || bus.length){
										var op_val = (bus.length ? bus.join('|') : car.join('|')) + '@#@' + (bus.length ? car.join('|') : bus.join('|')) + '@#@' + item['place'] + '@#@' + item['placename']; //type@#@another@#@place@#@name
										var op = new Option(item['placename'], op_val);
										select.options.add(op);
									}
								}
							}
							if(select.options.length){
								iJS.bind(select,'change',this.handleTrafficSelect.bind(this));
								this.trafficSelect = select;
								this.trafficDetail.innerHTML = '';
								div.appendChild(select);
								this.trafficDetail.appendChild(div);
								this.trafficSelect.selectedIndex = 0;
								this.handleTrafficSelect();
							}else{
								this.trafficDetail.innerHTML = '<div class="no_info">'+ mapMessageConfig['noInfo'] +'</div>';
							}
						}else{
							this.trafficDetail.innerHTML = '<div class="no_info">'+ mapMessageConfig['noInfo'] +'</div>';
						}
					}.bind(this)
				})
				this.trafficCreated = true;
			}else if(this.trafficDetailDiv){
				this.trafficSelect.selectedIndex = 0;
				this.handleTrafficSelect();
			}
		},
		
		createNearbyHotel: function(){
			this.map.clearOverlays();
			this.addCenterMarker();

			if(!this.nearbyHotelMarkers){
				this.nearbyHotelMarkers = {};
				var length = 0

				if(!(C.nearFacility && C.nearFacility.nearbyHotel)){
					return;
				}
				var nf = C.nearFacility,
					nf_data = nf['nearbyHotel'];

				
				for(var o in nf_data){
					var obj = nf_data[o];
					if (!obj.position || !obj.position['lat'] || !obj.position['lon'] || (obj.position['lat'] == "0" && obj.position['lon'] == "0") ) continue;
					var pos = new this.mapWin.MPoint(obj.position['lon'], obj.position['lat']);
					var newMkr = this.createFacilityMarker(pos, 'nearbyHotel', obj.name);
					if (newMkr) {
						this.nearbyHotelMarkers[o] = newMkr;
						newMkr.icon.div.title = obj.name + '    '+ mapMessageConfig['distance'] + obj.distance+ 'km';
						this.map.addOverlay(newMkr);
						length++;
					}
				}
				if(length){
					this.map.setAutoZoom();
				}
			}else{
				if(!(C.nearFacility && C.nearFacility.nearbyHotel)){
					return;
				}
				this.nearbyHotelMarkers = {};
				var nf = C.nearFacility,
					nf_data = nf['nearbyHotel'];
				for(var o in nf_data){
					var obj = nf_data[o];
					if (!obj.position || !obj.position['lat'] || !obj.position['lon'] || (obj.position['lat'] == "0" && obj.position['lon'] == "0") ) continue;
					var pos = new this.mapWin.MPoint(obj.position['lon'], obj.position['lat']);
					var newMkr = this.createFacilityMarker(pos, 'nearbyHotel', obj.name);
					if (newMkr) {
						this.nearbyHotelMarkers[o] = newMkr;
						newMkr.icon.div.title = obj.name + '    '+ mapMessageConfig['distance'] + obj.distance+ 'km';
						this.map.addOverlay(newMkr);
					}
				}
				this.map.setAutoZoom();
			}
		},
			
		handleTrafficSelect: function(){
			if(this.trafficLock || !this.trafficSelect || !this.trafficSelect.options.length){
				return false;
			}
			this.trafficLock = true;
			var data = this.trafficSelect.value.split('@#@');
			var type = data[0],
				another = data[1],
				place = data[2],
				name = data[3];
			this.createTrafficDetail(type, another, place, name);
			return false;
		},
		handleTrafficClick: function(evt){
			if(this.trafficLock){
				return false;
			}
			var e = evt || window.event,
				target = e.target || e.srcElement;
			if(target.nodeName.toLowerCase() == 'a'){
				var parent = target.parentNode;
				if(target.className == 'current' || parent.className == 'current')return false;
				this.trafficLock = true;
				var type = target.dataset ? target.dataset['type'] : target.getAttribute('data-type'),
					another = target.dataset ? target.dataset['another'] : target.getAttribute('data-another'),
					place = target.dataset ? target.dataset['place'] : target.getAttribute('data-place'),
					name = target.dataset ? target.dataset['name'] : target.getAttribute('data-name');
				this.createTrafficDetail(type, another, place, name);
				return false;
			}
		},
		createTrafficDetail: function(type, another, place, name){
			if(this.trafficDetailDiv){
				this.trafficDetail.removeChild(this.trafficDetailDiv);
				delete this.trafficDetailDiv;
			}
			var types = type.split('|');
			var curType = types[0];
			var isBus = /^bus/.test(type),
				isReverse = /Reverse$/.test(curType),
				hasBus = true,
				hasCar = true;
			if(isBus){
				hasCar = /^car/.test(another);
			}else{
				hasBus = /^bus/.test(another);
			}
			var main = document.createElement('div');
			var typeDiv = document.createElement('div');
			typeDiv.className = 'transfer_tab layoutfix';
			var trafficMessage = mapMessageConfig['traffic'];
			typeDiv.innerHTML = (hasBus ? '<a class="'+ (isBus ? 'current' : '') +'" data-type="'+ (isBus ? type : another) +'" data-another="'+ (isBus ? another : type) +'" data-place="'+ place +'" data-name="'+ name +'" href="javascript:void(0);">'+ trafficMessage[0] +'</a>' : '') 
				+ (hasCar ? '<a class="'+ (isBus ? '' : 'current') +'" data-type="'+ (isBus ? another : type) +'" data-another="'+ (isBus ? type : another) +'" data-place="'+ place +'" data-name="'+ name +'" href="javascript:void(0);">'+ trafficMessage[1] +'</a>' : '');
			iJS.bind(typeDiv, 'click', this.handleTrafficClick.bind(this));
			main.appendChild(typeDiv);
			var sdDiv = document.createElement('div');
			sdDiv.className = 'transfer_info';
			var hotelName = C.hotel.name;
			var changeLink = '';
			if(types.length){
				changeLink = '<a class="exchange_btn" title="'+ trafficMessage[2] +'" href="javascript:void(0);" data-type="'+ types.reverse().join('|') +'" data-another="'+ another +'" data-place="'+ place +'" data-name="'+ name +'"></a>';
				iJS.bind(sdDiv, 'click', this.handleTrafficClick.bind(this));
			}
			sdDiv.innerHTML = '<p>'+ trafficMessage[3] + (isReverse ? name : hotelName) +'</p><p>'+ trafficMessage[4] + (isReverse ? hotelName : name) +'</p>' + changeLink;
			main.appendChild(sdDiv);
			
			this.trafficDetail.appendChild(main);
			this.trafficDetailDiv = main;
			
			this.trafficMarkerData ={
				origName: isReverse ? name : hotelName,
				destName: isReverse ? hotelName : name
			}
			iJS.ajax({
				url: $.format(addressUrlConfig['trafficline'], curType, place),
				dataType: 'xml',
				beforeSend: this.clearMapTraffic.bind(this),
				success: isBus ? this.createBusDetail.bind(this) : this.createCarDetail.bind(this),
				complete: function(){this.trafficLock = false;}.bind(this)
			})
		},
		createCarMarker: function(result){
			if(!result)return;
			var routecenter = result.getElementsByTagName('center')[0].childNodes[0].nodeValue.split(','),
				routezoom = parseInt(result.getElementsByTagName('scale')[0].childNodes[0].nodeValue);
			this.map.centerAndZoom(new this.mapWin.MPoint(routecenter[0], routecenter[1]), routezoom);
			
			var Mpoint = [];
			var points = result.getElementsByTagName('routelatlon')[0].childNodes[0].nodeValue.split(';');
			for(var i=0,len=points.length; i<len; i++){
				var pos = points[i].split(',');
				Mpoint.push(new this.mapWin.MPoint(pos[0],pos[1]));
			}
			var brush = new this.mapWin.MBrush("blue");  
			brush.arrow = false;  
			brush.style = 0;  
			brush.stroke = 4;  
			brush.fill = false;  
			var polyline = new this.mapWin.MPolyline(  
				Mpoint,  
				brush,  
				null
			);
			this.map.addOverlay(polyline);
			var orig = this.trafficMarkerData['orig'];
			if(orig){
				orig = orig.split(',');
				orig = new this.mapWin.MPoint(orig[0], orig[1]);
			
				var icon_addr = "http://pic.c-ctrip.com/hotels110127/htl_map_start.png";
				var origMkr = new this.mapWin.MMarker(
					orig,
					new this.mapWin.MIcon(icon_addr, 18, 20, 9, 10),
					null,
					null,
					null
				)
				
				if (origMkr) {
					this.map.addOverlay(origMkr);
					origMkr.icon.div.title = this.trafficMarkerData['origName'];
				}	
			}
			var dest = this.trafficMarkerData['dest'];
			if(dest){
				dest = dest.split(',');
				dest = new this.mapWin.MPoint(dest[0], dest[1]);
			
				var icon_addr = "http://pic.c-ctrip.com/hotels110127/htl_map_destination.png";
				var destMkr = new this.mapWin.MMarker(
					dest,
					new this.mapWin.MIcon(icon_addr, 18, 20, 9, 10),
					null,
					null,
					null
				)
				
				if (destMkr) {
					this.map.addOverlay(destMkr);
					destMkr.icon.div.title = this.trafficMarkerData['destName'];
				}	
			}
		},
		createBusMarker: function(evt){
			var e = window.event || evt;
			var target = e.target || e.srcElement;
			var nodeName = target.nodeName.toLowerCase(),
				n = 0;
			while(nodeName !== 'li' && n < 4){
				target = target.parentNode;
				nodeName = target.nodeName.toLowerCase();
				n++;
			}
			if(nodeName !== 'li'){
				return;
			}
			
			var lis = this.trafficDetailDiv.getElementsByTagName('li');
			for(var i=0, l=lis.length; i<l; i++){
				lis[i].className = '';
			}
			target.className = 'bus_route_select';
			
			this.map.clearOverlays();
			
			var index = parseInt(target.dataset ? target.dataset['index'] : target.getAttribute('data-index'));
			var item = this.busItems[index];
			var routecenter = item.getElementsByTagName('routecenter')[0].childNodes[0].nodeValue.split(','),
				routezoom = parseInt(item.getElementsByTagName('routezoom')[0].childNodes[0].nodeValue);
			
			this.map.centerAndZoom(new this.mapWin.MPoint(routecenter[0], routecenter[1]), routezoom);
			if(!target.marker){
				target.marker = [];
				var walk = item.getElementsByTagName('walk');
				for(var i=0, l=walk.length; i<l; i++){
					var Mpoint = [];
					var points = walk[i].childNodes[0].nodeValue.split(';');
					for(var j=0,len=points.length; j<len; j++){
						var pos = points[j].split(',');
						Mpoint.push(new this.mapWin.MPoint(pos[0],pos[1]));
					}
					var brush = new this.mapWin.MBrush("blue");  
					brush.arrow = false;  
					brush.style = 1;  
					brush.stroke = 4;  
					brush.fill = false;  
					var polyline = new this.mapWin.MPolyline(  
						Mpoint,  
						brush,  
						null
					);
					this.map.addOverlay(polyline);
					target.marker.push(polyline);
				}
				var vehicles = item.getElementsByTagName('vehicle');
				for(var i=0, l=vehicles.length; i<l; i++){
					var Mpoint = [];
					var points = vehicles[i].childNodes[0].nodeValue.split(';');
					for(var j=0,len=points.length; j<len; j++){
						var pos = points[j].split(',');
						Mpoint.push(new this.mapWin.MPoint(pos[0],pos[1]));
					}
					var brush = new this.mapWin.MBrush("blue");  
					brush.arrow = false;  
					brush.style = 0;  
					brush.stroke = 4;  
					brush.fill = false;  
					var polyline = new this.mapWin.MPolyline(  
						Mpoint,  
						brush,  
						null
					);
					this.map.addOverlay(polyline);
					target.marker.push(polyline);
				}
				var orig = this.trafficMarkerData['orig'];
				if(orig){
					orig = orig.split(',');
					orig = new this.mapWin.MPoint(orig[0], orig[1]);
				
					var icon_addr = "http://pic.c-ctrip.com/hotels110127/htl_map_start.png";
					var origMkr = new this.mapWin.MMarker(
						orig,
						new this.mapWin.MIcon(icon_addr, 18, 20, 9, 10),
						null,
						null,
						null
					)
					if (origMkr) {
						this.map.addOverlay(origMkr);
						origMkr.icon.div.title = this.trafficMarkerData['origName'];
						target.marker.push(origMkr);
					}	
				}
				var dest = this.trafficMarkerData['dest'];
				if(dest){
					dest = dest.split(',');
					dest = new this.mapWin.MPoint(dest[0], dest[1]);
				
					var icon_addr = "http://pic.c-ctrip.com/hotels110127/htl_map_destination.png";
					var destMkr = new this.mapWin.MMarker(
						dest,
						new this.mapWin.MIcon(icon_addr, 18, 20, 9, 10),
						null,
						null,
						null
					)
					if (destMkr) {
						this.map.addOverlay(destMkr);
						destMkr.icon.div.title = this.trafficMarkerData['destName'];
						target.marker.push(destMkr);
					}	
				}
				var points = item.getElementsByTagName('point');
				for(var i=0, l=points.length; i<l; i++){
					var point = points[i].childNodes[0].nodeValue;
					if(point){
						point = point.split(',');
						point = new this.mapWin.MPoint(point[0], point[1]);
					
						var icon_addr = "http://pic.c-ctrip.com/hotels110127/htl_map_exchange.png";
						var pointMkr = new this.mapWin.MMarker(
							point,
							new this.mapWin.MIcon(icon_addr, 18, 20, 9, 10),
							null,
							null,
							null
						)
						
						if (pointMkr) {
							this.map.addOverlay(pointMkr);
							pointMkr.icon.div.title = points[i].getAttribute('name');
							target.marker.push(pointMkr);
						}	
					}
				}
				
			}else{
				var polylines = target.marker;
				for(var i=0,l=polylines.length; i<l; i++){
					this.map.addOverlay(polylines[i]);
				}
			}
		},
		createBusDetail: function(xmlData){
		    if(!xmlData){
		        return false;
		    }
			var bus = xmlData.getElementsByTagName('bus')[0];
			var length = parseInt(bus.getAttribute('count'));
			if(length){
				var items = bus.getElementsByTagName('item');
				this.busItems = items;
				var result = xmlData.getElementsByTagName('result')[0];
				iJS.extend(this.trafficMarkerData,{
					orig:result.getAttribute('orig'),
					dest:result.getAttribute('dest')
				});
				var ul = document.createElement('ul');
				ul.className = 'bus_route';
				ul.style.height = this.trafficDetail.offsetHeight - 111 + 'px';
				var temp = this.temp['bus'];
				var reg = new RegExp('('+ mapMessageConfig['traffic'][5] +')?\\([^:]*\\)','gi');
				for(var i=0; i<length && i<3; i++){
					var item = items[i];
					var li = document.createElement('li');
					var detail = item.getElementsByTagName('detail')[0].childNodes[0].nodeValue;
					if(li.dataset){
						li.dataset['index'] = i;
					}else{
						li.setAttribute('data-index', i);
					}
					li.innerHTML = $.replace(temp, {
						index: i+1,
						time: item.getElementsByTagName('time')[0].childNodes[0].nodeValue,
						line: item.getElementsByTagName('line')[0].childNodes[0].nodeValue.replace(reg,'').replace(/:/g,'&gt;'),
						distance: item.getElementsByTagName('distance')[0].childNodes[0].nodeValue,
						station: item.getElementsByTagName('station')[0].childNodes[0].nodeValue.match(/;/g).length,
						poisNum: item.getElementsByTagName('point').length,
						detail: detail ? '<dd>' + detail.split(',').join('</dd><dd>') + '</dd>' : ''
					})
					iJS.bind(li, 'click', this.createBusMarker.bind(this));
					iJS.bind(li, 'mouseover', function(){
						if(/bus_route_select/.test(this.className)){
							this.className = 'bus_route_select bus_route_hover';
						}else{
							this.className = 'bus_route_hover';
						}
					});
					iJS.bind(li, 'mouseout', function(){
						this.className = this.className.replace(/\s*?bus_route_hover/g,'')
					});
					ul.appendChild(li);
				}
				this.trafficDetailDiv.appendChild(ul);
				var firstItem = ul.getElementsByTagName('li')[0];
				if(firstItem){
				    iJS.doHandler(firstItem, 'click');
				}
			}
		},
		createCarDetail: function(xmlData){
		    if(!xmlData){
		        return false;
		    }
			var routes = xmlData.getElementsByTagName('routes')[0];
			var length = parseInt(routes.getAttribute('count'));
			if(length){
				var items = routes.getElementsByTagName('item');
				
				var result = xmlData.getElementsByTagName('result')[0];
				iJS.extend(this.trafficMarkerData,{
					orig:result.getAttribute('orig'),
					dest:result.getAttribute('dest')
				});
				var dl = document.createElement('dl');
				dl.className = 'drive_route';
				dl.style.height = this.trafficDetail.offsetHeight - 121 + 'px';
				
				var temp = this.temp['car'];
				var distance = result.getElementsByTagName('distance');
				dl.innerHTML = $.replace(temp,{
					distance: distance[distance.length-1].childNodes[0].nodeValue,
					time: parseInt(parseInt(result.getElementsByTagName('duration')[0].childNodes[0].nodeValue) / 60)
				})
				for(var i=0; i<length; i++){
					var item = items[i];
					var dd = document.createElement('dd');
					dd.innerHTML = '<strong>'+ (i+1) +'</strong>. ' + item.getElementsByTagName('strguide')[0].childNodes[0].nodeValue;
					dl.appendChild(dd);
				}
				this.trafficDetailDiv.appendChild(dl);
				
				this.createCarMarker(result);
			}
		},
		clearMapTraffic: function(){
			this.map.clearOverlays(true);
			delete this.busItems;
		},
		createFacilityMarker: function(latlng, type, label) {
			if (!latlng) return;
			type = {
				"restaurant": 'restaurant',
				"entertain": 'entertainment',
				"shopping": 'shopping',
				"subway": 'metro',
				"scenic": 'sight',
				"nearbyHotel":'nearby_htl'
			}[type];
			var ico = 'http://pic.c-ctrip.com/hotels110127/ico_'+ type +'.png',
				ico_shadow = 'http://pic.c-ctrip.com/hotels081118/marker_shadow.png';
			var mkr = new this.mapWin.MMarker(latlng,
				new this.mapWin.MIcon(ico, 22, 26, 11, 16),
				null,
				new this.mapWin.MLabel('<span>' + label + '</span>', {
					xoffset: 26,
					yoffset: -5,
					enableStyle: false
				}),
				new this.mapWin.MIconShadow(ico_shadow, 40, 34, -5, 0)
			);
			mkr.label.div.className = 'searchresult_popname2 hidden';
			mkr.autoHide = false;
			var img = mkr.icon.div.getElementsByTagName('img')[0];
			this.mapWin.MEvent.addListener(mkr, 'mouseover', function(m) {
				img.src = img.src.replace(/(_\w+)(\.png)/, '$1_hover$2');
			});
			this.mapWin.MEvent.addListener(mkr, 'mouseout', function(m) {
				img.src = img.src.replace(/_hover/, '');
			});
			return mkr;
		},
		addCenterMarker: function(){
			var pot = C.hotel.position;
			if (pot) {
				pot = pot.split('|');
				pot = new this.mapWin.MPoint(pot[0], pot[1]);
				this.map.centerAndZoom(pot, 12);
				var thisHotel = C.hotel;
				var icon_addr = "http://pic.c-ctrip.com/hotels110127/hotel_pointer.gif",
					shadow_addr = 'http://pic.c-ctrip.com/hotels081118/marker_shadow.png';
				var spotMkr = new this.mapWin.MMarker(
					pot,
					new this.mapWin.MIcon(icon_addr, 21, 31, 10, 30),
					null,
					new this.mapWin.MLabel('<span>' + thisHotel['name'] + '</span>', {
						xoffset: 30,
						yoffset: 0,
						enableStyle: false
					}),
					new this.mapWin.MIconShadow(shadow_addr, 40, 34, -5, 10)
				)
				if (spotMkr) {
					this.map.addOverlay(spotMkr);
					spotMkr.label.div.className = 'searchresult_popname box_shadow';
				}
			}
		},
		showRestaurantMarker: function(evt){
			var e = evt || window.event,
				target = e.target || e.srcElement,
				i = target.dataset ? target.dataset['index'] : target.getAttribute('data-index');
			var mk = this.restaurantMarkers[i];
			if(mk){
				var img = mk.icon.div.getElementsByTagName('img')[0];
				img.src = img.src.replace(/(_\w+)(\.png)/, '$1_hover$2');
				mk.icon.div.style.zIndex = '999';
				mk.label.div.className = 'searchresult_popname2';
				this.curLabel = mk;
			}
			return false;
		},
		showEntertainMarker: function(evt){
			var e = evt || window.event,
				target = e.target || e.srcElement,
				i = target.dataset ? target.dataset['index'] : target.getAttribute('data-index');
			var mk = this.entertainMarkers[i];
			if(mk){
				var img = mk.icon.div.getElementsByTagName('img')[0];
				img.src = img.src.replace(/(_\w+)(\.png)/, '$1_hover$2');
				mk.icon.div.style.zIndex = '999';
				mk.label.div.className = 'searchresult_popname2';
				this.curLabel = mk;
			}
			return false;
		},
		showShoppingMarker: function(evt){
			var e = evt || window.event,
				target = e.target || e.srcElement,
				i = target.dataset ? target.dataset['index'] : target.getAttribute('data-index');
			var mk = this.shoppingMarkers[i];
			if(mk){
				var img = mk.icon.div.getElementsByTagName('img')[0];
				img.src = img.src.replace(/(_\w+)(\.png)/, '$1_hover$2');
				mk.icon.div.style.zIndex = '999';
				mk.label.div.className = 'searchresult_popname2';
				this.curLabel = mk;
			}
			return false;
		},
		showSubwayMarker: function(evt){
			var e = evt || window.event,
				target = e.target || e.srcElement,
				i = target.dataset ? target.dataset['index'] : target.getAttribute('data-index');
			var mk = this.subwayMarkers[i];
			if(mk){
				var img = mk.icon.div.getElementsByTagName('img')[0];
				img.src = img.src.replace(/(_\w+)(\.png)/, '$1_hover$2');
				mk.icon.div.style.zIndex = '999';
				mk.label.div.className = 'searchresult_popname2';
				this.curLabel = mk;
			}
			return false;
		},
		showScenicMarker: function(evt){
			var e = evt || window.event,
				target = e.target || e.srcElement,
				i = target.dataset ? target.dataset['index'] : target.getAttribute('data-index');
			var mk = this.scenicMarkers[i];
			if(mk){
				var img = mk.icon.div.getElementsByTagName('img')[0];
				img.src = img.src.replace(/(_\w+)(\.png)/, '$1_hover$2');
				mk.icon.div.style.zIndex = '999';
				mk.label.div.className = 'searchresult_popname2';
				this.curLabel = mk;
			}
			return false;
		},
		showNearbyHotelMarker: function(evt){
			var e = evt || window.event,
				target = e.target || e.srcElement,
				i = target.dataset ? target.dataset['index'] : target.getAttribute('data-index');
			var mk = this.nearbyHotelMarkers[i];
			if(mk){
				var img = mk.icon.div.getElementsByTagName('img')[0];
				img.src = img.src.replace(/(_\w+)(\.png)/, '$1_hover$2');
				mk.icon.div.style.zIndex = '999';
				mk.label.div.className = 'searchresult_popname2';
				this.curLabel = mk;
			}
			return false;
		},
		hideLabelMarker: function(){
			if(this.curLabel){
				var img = this.curLabel.icon.div.getElementsByTagName('img')[0];
				img.src = img.src.replace(/_hover/, '');
				this.curLabel.icon.div.style.zIndex = '1';
				this.curLabel.label.div.className = 'searchresult_popname2 hidden';
			}
			return false;
		}
	}
	window.PopMapView = iJS.makeClass(popMap, mapPrototype);
})(hotelDomesticConfig);