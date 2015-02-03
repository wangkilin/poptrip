function autoSubmit(formElement)
{
	while($(formElement).parent().length) {
		if($(formElement).parent().get(0).tagName.toUpperCase()=='FORM') {
			$(formElement).parent().submit();
			break;
		}
		formElement = $(formElement).parent();
	}
	
	return;
}

/**
 * some widgets here
 */
;(function($){
	$.fn.extend({   
		/**
		 * jQuery中定义鼠标右键方法，接收一个函数参数   
		 * @example
		 * function alertHello() { alert('hello'); }
		 * $('body').rightClick(alertHello);
		 */
		rightClick : function(fn){
			//调用这个方法后将禁止系统的右键菜单
			$(this).bind('contextmenu',function(e){
				fn(e);
				return false;
			});
			//为这个对象绑定鼠标释放事件
			$(this).mouseup(function(e){
				//如果按下的是右键，则执行函数
				if(3 == e.which){
					return false;
				}

				return false;
			});
			return false;
		},

		/**
		 * jQuery中定义按住shift键进行鼠标点击方法， 接收两个函数参数：点击前和点击后
		 * @example
		 * function beforeClickFunc(event, clickObj) { alert('hello'); }
		 * $('body').shiftKeyAndClick(beforeClickFunc);
		 */
		shiftKeyAndClick : function (beforeClickFunc, afterClickFunc) {
			$(this).bind('click', function (e) {
				if (typeof beforeClickFunc == 'function') {
					beforeClickFunc(e, $(this));
				}
				if (1==e.shiftKey) {
					if ($(this).hasClass('shiftKeyClick')) {
						$(this).removeClass('shiftKeyClick');
					} else {
					    $(this).addClass('shiftKeyClick');
					}
				} else {
                    if($('.shiftKeyClick').length==1){
                        $('.shiftKeyClick').removeClass('shiftKeyClick');
                    }
                        $(this).addClass('shiftKeyClick');

				}

				if (typeof afterClickFunc == 'function') {
					afterClickFunc(e, $(this));
				}
                $('.shiftKeyClick').draggable({
                    start: function (event, ui) {//鼠标拖拽过程中的事件
                        if(! $(this).hasClass('shiftKeyClick')) {
                            return false;
                        }
                        startX = event.clientX;//鼠标开始移动位置X
                        startY = event.clientY;//鼠标开始移动位置Y
                        allPos = [];
                        allSiblings = $(this).siblings(".shiftKeyClick");
                        total = allSiblings.length;
                        var _pos = [];
                        for (var i=0; i<total; i++) {

                            allPos.push($(this).siblings(".shiftKeyClick").eq(i).offset());
                        }
//                            console.log(allPos);console.log(total);
                    },
                    drag : function (event,ui) {
                        if(! $(this).hasClass('shiftKeyClick')) {
                            return false;
                        }
                        var endX = event.clientX;//鼠标结束移动位置X
                        var endY = event.clientY;//鼠标结束移动位置Y
                        var X = endX - startX;//鼠标总移动X
                        var Y = endY - startY;//鼠标总移动Y
                        var _pos = [];
                        var _offset = {};
                        for (var i=0; i<total; i++) {
                            _offset.left = allPos[i].left + X;
                            _offset.top = allPos[i].top + Y;
                            $(this).siblings(".shiftKeyClick").eq(i).offset(_offset);
                        }
                    },
                    stop: function (event, ui) {//鼠标拖拽停止时的事件
                        if(! $(this).hasClass('shiftKeyClick')) {
                            return false;
                        }
                        var endX = event.clientX;//鼠标结束移动位置X
                        var endY = event.clientY;//鼠标结束移动位置Y
                        var X = endX - startX;//鼠标总移动X
                        var Y = endY - startY;//鼠标总移动Y
                        var _pos = [];
                        var _offset = {};
                        for (var i=0; i<total; i++) {
                            _offset.left = allPos[i].left + X;
                            _offset.top = allPos[i].top + Y;
                            $(this).siblings(".shiftKeyClick").eq(i).offset(_offset);
                        }

                    }
                });
                return false;
			});
            return false;
		}
	});
	
	
})(jQuery);

