/**
 * 页面滚动定位功能插件. 
 * @example :
 * 
 * $.ScrollFocus.init();
 * 
 * @example
 * 
 * function hello($obj)
 * {
 *	  alert($obj.attr('id'));
 * }
 * 
 * $.ScrollFocus.init({scrollingChildCssName:'scrollChild', focusTrigger:hello});
 * 
 * 瀑布流调用,增加div ,建议scrollPosition:8
 * <div class="loading"></div>
 * $.ScrollFocus.init({scrollingChildCssName:'loading', scrollPosition:8, focusTrigger:hello});
 * 
 */

;(function($){
	$.extend({
		ScrollFocus:{
			scrollingChildCssName : 'scrollChild', // 滚屏监视的子元素样式名称
    		scrollPosition : 2,	// 可视窗口分段定位 
    		childPositions : [],	// 滚屏监视的子元素的顶部和底部位置
    		scrollingChilds : null, // 滚屏监视的子元素
    		focusTrigger : null,	// 定位到子元素后调用的方法|函数
    		scrollTrigger : null,	// 可视窗口滚动事件调用的方法|函数
    		scrollItem : 'window',
    		loseFocusTrigger : null,// 没有监控元素被捕捉到时候，调用的函数
    		
    		/**
    		 * 初始化
    		 */
			init: function(settings) {
				// initialize the settings
				if(typeof settings =='object') {
					if(typeof settings.scrollingChildCssName == 'string') {
						this.scrollingChildCssName = settings.scrollingChildCssName;
					}
					if(typeof settings.focusTrigger == 'function') {
						this.focusTrigger = settings.focusTrigger;
					}
					if(typeof settings.loseFocusTrigger == 'function') {
						this.loseFocusTrigger = settings.loseFocusTrigger;
					}
					if(typeof settings.scrollPosition == 'number') {
						this.scrollPosition = settings.scrollPosition;
					}
					if(typeof settings.scrollTrigger == 'function') {
						this.scrollTrigger = settings.scrollTrigger;
					}
					if(typeof settings.scrollItem == 'string') {
						this.scrollItem = settings.scrollItem;
					}
				}
				this.childPositions = [];
				
				if ( this.scrollItem !== 'window' )
				{
					this
						.scrollWindowToHash()
						.loadPostions()			// load the scrolling sections postions
				    	.listenItemScrollEvent();	// listen scroll event
				} 
				 else 
				{
					 this
					    .scrollWindowToHash()	// check if URL has hash
					    .loadPostions()			// load the scrolling sections postions
					    .listenScrollEvent();	// listen scroll event
				}
				
            },
            
            /**
             * scroll window to the specified anchor
             */
            scrollWindowToHash : function () {
				// URL has Hash, and webpage has the content
            	if(window.location.hash && $(window.location.hash).length){
            		var $target = $(window.location.hash);
					var targetScrollTo = $target.offset.top;
					
					// get the current window height
            		var windowHeight = window['innerHeight'] || document.documentElement.clientHeight;
            		
            		// target is smaller than window
            		if(windowHeight > $target.height()) {
            			// target is smaller than half window
            			if (windowHeight > $target.height()) {
            				targetScrollTo = $target.offset.top - windowHeight + $target.height - 10;
            			} else {
            				// target is bigger tan half window
            				targetScrollTo = $target.offset.top - 100;
            			}
            		}
            		// scroll window to target position
					$("html,body").animate({scrollTop:targetScrollTo},300);
				}
				
				return this;
            },
            
            /**
             * Load the postions of the scrolling sections 
             * 载入滚动监听元素的坐标位置
             * 
             * @return this
             */
            loadPostions : function () {
            	// find out all scrolling childs
            	this.scrollingChilds = $('.' + this.scrollingChildCssName);
            	var childNumber = this.scrollingChilds.length;
            	
        		// find out the positions of the children to be focused 
            	for(var i=0; i<childNumber; i++) {
            		var status = '0'
            		if($(this.scrollingChilds.eq(i)).css('display') == 'none'){
                		$(this.scrollingChilds.eq(i)).css('display',"block");
            			status = '1';
            		}
            		var $_pos = {};
            		// get the element's top and bottom position
            		$_pos.top = this.scrollingChilds.eq(i).offset().top;
            		$_pos.bottom = $_pos.top + this.scrollingChilds.eq(i).height();
            		if ( status == '1' ) {
            			$(this.scrollingChilds.eq(i)).css('display',"none");
            		}
            		this.childPositions.push($_pos);
            	}

            	return this;
            },
            
            /**
             * Listening the scroll event. 
             * 监听页面滚动事件
             * 
             */
            listenScrollEvent : function () {
            	var childNumber = this.scrollingChilds.length;
            	
            	// listen the window's scrolling
            	$(window).scroll(function() {
            		var $this = $(this);
            		// 页面滚动时执行指定方法|函数
            		if (typeof($.ScrollFocus.scrollTrigger)=='function') {
        				$.ScrollFocus.scrollTrigger();
        			}
            		
            		// get current window top postion
            		var scrollTop = $(this).scrollTop();
            		if (! scrollTop) {
            			scrollTop = $('body').scrollTop();
            		}
            		
            		// get the current window height
            		var windowHeight = window['innerHeight'] 
            		            || document.documentElement.clientHeight
            		            || document.body.offsetHeight;
            		
            		// get the half size of window height
            		var windowHalfHeight = windowHeight - (windowHeight / $.ScrollFocus.scrollPosition);
            		
            		// get the reference Y position. once the scrolling section moving
            		// to this regerence Y position, trigger the event
            		// 当前屏幕定位点的  Y坐标
            		var referenceY = scrollTop + windowHalfHeight;
            		// compare the scrolling section Top and Bottom position with 
            		// the reference Y position.
            		// once the reference Y position is between Top and Bottom, then
            		// trigger the event
            		var isAChildInInFocus = false;
            		for(var i=0; i<childNumber; i++) {
                		var childOffset = $.ScrollFocus.childPositions[i];

                		// find the current scrolling section
                		if(childOffset.top < referenceY && referenceY < childOffset.bottom) {
                			isAChildInInFocus = true;
                			// the trigger event is set, call it
                			if (typeof($.ScrollFocus.focusTrigger)=='function') {
                				$.ScrollFocus.focusTrigger($.ScrollFocus.scrollingChilds.eq(i));
                			}
                			break;
                		}
                	}
            		// Has no child in Focus area
            		if (isAChildInInFocus == false) {
            			// the trigger event is set, call it
            			if (typeof($.ScrollFocus.loseFocusTrigger)=='function') {
            				$.ScrollFocus.loseFocusTrigger();
            			}
            		}
            	});
            },
            /**
             * 监听指定元素的滚动事件
             * 
             */
            listenItemScrollEvent : function () {
            	var childNumber = this.scrollingChilds.length;
            	
            	// listen the item's scrolling
            	$(this.scrollItem).scroll(function() {
            		var $this = $(this);
            		// 滚动时执行指定方法|函数
            		if (typeof($.ScrollFocus.scrollTrigger)=='function') {
        				$.ScrollFocus.scrollTrigger();
        			}

            		// get the current item height
            		var itemHeight = $this.height();

            		// 监听元素对应父元素的top值
            		var itemPositionHeight = $('.'+$.ScrollFocus.scrollingChildCssName).position().top;
            		
            		// 父元素与监听元素高度差值
            		var itemChangeHeight = itemHeight - $('.'+$.ScrollFocus.scrollingChildCssName).height();
            		
            		// find the current scrolling section
            		if( itemPositionHeight < itemHeight && itemPositionHeight > itemChangeHeight ) {
            			// the trigger event is set, call it
            			if (typeof($.ScrollFocus.focusTrigger)=='function') {
            				$.ScrollFocus.focusTrigger($('.'+$.ScrollFocus.scrollingChildCssName));
            			}
            		}
            	});
            }
		}
    });
})(jQuery);