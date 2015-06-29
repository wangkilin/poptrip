/**
 * 
 */
;$.extend({
ConvasDrawLine : {
	pointsList : [],    // 画线基准点。 格式： {x:0, y:0, color:'#000, }
	options : {
		isClose : false // 是否闭合： 决定是否启用  context.closePath();
	  , speed : 0       // 画线速度： 0-直接画出， 'slow'-慢速画， 'fast'-快速画
	  , speedSlow : 200 // 400 毫秒
	  , speedFast : 100 // 100 毫秒
	  , containerId : '#canvasContainer' // 画布容器
      , color : '#000', // 默认颜色
	},
	speedSlow : 1,
	
	init: function (options) {
		this.options = $.extend(this.options, options);
	},
	
	draw : function () {
		
	}
}});