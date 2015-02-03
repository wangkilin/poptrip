
/**
 * some widgets here
 */
;(function($){$.extend({
	Ebook : {
		init : function (options) {
			
		},
		
		listenEvent : function () {
			$('#bookTitleOfChapterList').rightClick(function(e) {
				$('#addChapterMenu').show();
				
				return false;
			});
			
			return false;
		}
	}	
});
})(jQuery);

$(function() {
	$.Ebook.listenEvent();
});
