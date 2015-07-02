/**
 * 
 */
;$.extend({
CanvasDrawLine : {
	pointsList : [],    // 画线基准点。 格式： {x:0, y:0, color:'#000, trigger:null}
	options : {
		isClose : false // 是否闭合： 决定是否启用  context.closePath();
	  , speed : 0       // 画线速度： 0-直接画出， 'slow'-慢速画， 'fast'-快速画
	  , speedSlow : 200 // 400 毫秒
	  , speedFast : 100 // 100 毫秒
	  , containerId : 'canvasContainer' // 画布容器
      , color : '#000' // 默认颜色
      , lineWidth : 1
	},
	speedSlow : 1,
	
	init: function (pointsList, options) {
		this.pointsList = pointsList;
		this.options = $.extend(this.options, options);
		
		return this;
	},
	
	_getCanvas : function () {
		
	},
	
	logInfo : function (msg) {
		console ? console.info(msg) : null;
	},
	
	_drawLine : function (pointsList) {
		
	},
	
	drawLine : function () {
		var container = document.getElementById(this.options.containerId);
		if (! container) {
			this.logInfo('container is not found');
			return null;
		}
		if (container.tagName!='CANVAS') {
			var canvas = $('<canvas class="__canvas"></canvas>');
			$(container).append(canvas);
			container = canvas;
		}
		if (! container.getContext) {
			this.logInfo('Browser does not support canvas');
			return null;
		}

		var x, y, color;
		var canvasContext = container.getContext('2d');
		canvasContext.strokeStyle = this.options.color;
		canvasContext.lineWidth = this.options.lineWidth;
		canvasContext.beginPath();
		for (var i=0; i<this.pointsList.length; i++) {
			x = this.pointsList[i].x;
			y = this.pointsList[i].y;
			0===i ? canvasContext.moveTo(x, y) : canvasContext.lineTo(x, y);
		}
		this.options.isClose ? canvasContext.closePath() : null;
		canvasContext.stroke();
	},
	
	
}});