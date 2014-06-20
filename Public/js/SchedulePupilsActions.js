$('#levelDescriptionIcon').click(function() {
    var mouseInfo = $(this).offset();
    $('#levelDescription_' + $('select[name="level"]').val()).css({'top':mouseInfo.top+18, 'left':mouseInfo.left-15}).toggle();
    $.each($('.netSpeedIntroArea'), function() {
    	if($(this).attr('id')!=('levelDescription_' + $('select[name="level"]').val())) {
    			$(this).hide();
    	}
    });
    _gaq.push(['_trackEvent','Navigation','getlevelInfo']);
    return false;
});
    
$('.netSpeedClose').click(function() {
	$('#levelDescriptionIcon').trigger('click');
});

$('select[name="level"]').change(function() {
	$('.netSpeedIntroArea').hide();
});
    
    
$('body').click(function(e) {
	var obj = e.target;
	while($(obj).parent().length) {
		obj = $(obj).parent();
		if(obj.hasClass('netSpeedIntroArea')) {
			return false;
		}
	}
	$('.netSpeedIntroArea').hide();
	return true;
});