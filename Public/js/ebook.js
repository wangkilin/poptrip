
/**
 * some widgets here
 */
;(function($){$.extend({
	Ebook : {
		init : function (options) {
			
		},
		
		listenEvent : function () {
			$('#bookTitleOfChapterList').rightClick(function() {
				$('#addChapterMenu').show();
			});
		}
	}	
});
})(jQuery);

$(function() {
	$.Ebook.listenEvent();
});
