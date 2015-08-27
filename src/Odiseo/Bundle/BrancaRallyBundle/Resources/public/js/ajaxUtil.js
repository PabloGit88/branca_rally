var ajaxUtil = (function(){
	
	return {
		sendAjaxPostRequest  : function (url, data, doneCallback, errorCallback){
			$.ajax({
				  method: "POST",
				  url: url,
				  data: data
				}) .done(function( data ) {
						doneCallback(data);
				});
		},
		sendEmails : function(element,url) {
			
			$(element).removeClass('btn-success');
			$(element).addClass('btn-default');
			$.ajax({
				  method: "POST",
				  url: url,
				}) .done(function( data ) {
					alert(data.message);
					$(element).addClass('btn-success');
					$(element).removeClass('btn-default');
				});
		}
	}
	
})(jQuery);