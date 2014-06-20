//     Vtip.js 1.0.3
//     (c) 2012 Viking.
//     For all details and documentation:
//     https://github.com/vikingmute/vtooltip
(function(){
	var settings = {
		title:'testTitle',
		width:450,
		//content:'',
		btnOkEvent:function(){}
		
	};
	var cache = {
		
	};
	$.fn.vmodal = function(options,callback){
		var opts = $.extend({},settings,options);
		return this.each(function(){
			var self = $(this);
			
			/*if(opts.content!=''){
				self.html('');
				self.append('<h3>'+opts.title+'</h3>');
				self.append('<a href="#" class="close cancel">��</a>');
				self.append("<p class='delete'>"+opts.content+"</p>");
				self.append("<div class='btn_box'><input type='button' value='ȷ  ��' class='btn_orange cancel'/></div>");
			}*/
		 
			var dismiss = self.find('.cancel');
			var sh = self.height();
			var sw = self.width();
			self.css({'position':'absolute','top':'50%','left':'50%','z-index':'1000','background':'#fff'});
			self.css({'margin-top':-(sh/2),'margin-left':-(sw/2)});
			self.show();
			opts.mask.show();
			//bind events
			dismiss.off('click');
			dismiss.bind('click',function(){				 
				self.hide();
				opts.mask.hide();
			});			 
			opts.mask.bind('click',function(){
				self.hide();
				opts.mask.hide();
			});
			var btnOK=self.find('.ok');
			btnOK.off('click');
			btnOK.bind('click',opts.btnOkEvent);
			self.find('h3').html(opts.title);		 
			
			var dragbar = self.find('h3');
			dragbar.off('mouseenter');
			var mouseDown = false;
			var mouseDownX = -1;
			var mouseDownY = -1;
			var selfx = self.offset().left;
			var selfy = self.offset().top;
			var gapx = 0;
			var gapy = 0;
			
			dragbar.bind('mouseenter',function(){
				var db = $(this);
				db.css('cursor','pointer');
				$(document).mousedown(function(event){
					mouseDown = true;
					mouseDownX = event.pageX;
					mouseDownY = event.pageY;
					gapx = mouseDownX - selfx;
					gapy = mouseDownY - selfy;
					return false;
				});
				$(document).mousemove(function(event){
					if(mouseDown){
						self.css('margin','0');
						mouseDownX = event.pageX;
						mouseDownY = event.pageY;
						selfy = mouseDownY - gapy;
						selfx = mouseDownX - gapx;
						self.css({top:selfy,left:selfx});
					}
				});
				
				$(document).mouseup(function(event){
					mouseDown = false;
					db.css('cursor','default');
				});
			});
			dragbar.bind('mouseleave',function(event){
				$(document).off();
			});

		});
	};
})(jQuery);