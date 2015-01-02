/**
 * 区域元素轮播效果
 * 
 */

;(function($){
	$.extend({
		ScrollFrame:{
			bindElements : {
				frameContainerId : '#scrollFrame', // 滚动帧的外围容器ID
				frameItemClass : '.frameItems',    // 滚动帧的class名称
				prevArrowId : '#scrollPrevArrow',  // 点击进入前一帧的按钮ID
				nextArrowId : '#scrollNextArrow',  // 点击进入下一帧的按钮ID 
				previewContainerId : '#previewContatinerId', // 缩略图的外围容器ID
				previewItemClass : '.previewItems', // 缩略图帧的class名称
				prevPageId : '#scrollPrevPage', // 点击切换上一页的按钮ID
				nextPageId : '#scrollNextPage', // 点击切换下一页的按钮id
			},
			
			scrollToNumber : 1, // scroll to which frame ? 跳转到第几帧
			scrollSpeed : 1, // 1~100  播放速度
			direction : 'H', // 'H' or 'V' horizonal/vertical  切换的方 向
			autoPlay  : false, // if play the frames automatically 是否自动播放
			
			prevArrowClick : function () {},  // 监听事件。 点击前一帧按钮时的监听事件
			nextArrowClick : function () {},  // 监听事件。 点击后一帧按钮时的监听事件
			prevPageClick : function () {},   // 监听事件。 点击前一页按钮时的监听事件
			nextPageClick : function () {},   // 监听事件. 点击后一页按钮时的监听事件
			
			_frames : [],  // 获得所有帧的对象
			_previews : [], // 获得所有预览帧的对象

    		
    		/**
    		 * 初始化
    		 * 
    		 * initialize the settings
    		 * @param object settings 传入的配置信息
    		 */
			init: function(settings) {				
				if(typeof settings =='object') {
					if(typeof settings.frameItemClass == 'string') {
						this.frameItemClass = settings.frameItemClass;
					}
					if(typeof settings.prevArrowClick == 'function') {
						this.prevArrowClick = settings.prevArrowClick;
					}
				}
				
				this.loadItems (); // 载入帧对象
				this.bindClickPrevArrow(); // 绑定 点击上一帧按钮 事件
				this.bindClickNextArrow(); // 绑定 点击下一帧按钮事件
				this.bindClickPrevPage();  // 绑定点击 一页按钮事件
				this.bindClickNextPage();  // 绑定点击下一页按钮事件
				this.bindClickPreview();   // 绑定点击预览事件
			},
			
			loadItems : function () {
				var _css = this.bindElements.frameContainerId + ' ' + this.bindElements.frameItemClass;
				var i = 1;
				$(_css).each(function () {
					$(this).attr('_scrollNum', i++);
				});
				this._frames = $(_css);
				
				_css = this.bindElements.previewContainerId + ' ' + this.bindElements.previewItemClass;
				i = 1;
				$(_css).each(function () {
					$(this).attr('_scrollNum', i++);
				});
				this._previews = $(_css);
			},
			
			play : function () {
				
			},
			// 判断是否在滚动中
			isScrolling : function () {
				var _css = this.bindElements.frameContainerId + ' .scrolling';
				return $(_css).length > 0;
				
			},
			// 跳转到指定帧
			goToSpecifiedItem : function (targetNumber) {
				if (targetNumber >= this._frames.length) {
					return false;
				}
				if (this->isScrolling()) {
					return false;
				}
				// moving start
				var currentItem = this.getCurrentItem();
				if (! currentItem.length) {
					return false;
				}
				
				// moving end
			},
			// 获得当前帧
			getCurrentItem : function () {
				var _css = this.bindElements.frameContainerId + ' ' + this.bindElements.frameItemClass;
				var currentItem = $(_css).filter('.scrollingCurrent');
				if (! currentItem.length) {
					$(_css).eq(0).addClass('.scrollingCurrent');
					currentItem = $(_css).filter('.scrollingCurrent');
				}
				return currentItem.eq(0);
			},
				
			bindClickPrevArrow : function () {

			},
			
			bindClickNextArrow : function () {
			},

			bindClickPrevPage : function () {

			},
			
			bindClickNextPage : function () {

			}

		} // end of ScrollFrame
    });
})(jQuery);