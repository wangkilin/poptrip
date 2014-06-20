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