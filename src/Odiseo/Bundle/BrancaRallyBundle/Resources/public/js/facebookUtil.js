var facebookUtil = (function($)
{
	$(document).ready(function() 
	{    
		/**-- Facebook --**/
		$.ajaxSetup({ cache: true });
		$.getScript('//connect.facebook.net/es_LA/all.js', function()
		{
			FB.init({
				appId:  '331542763722302',
				status: true,
				xfbml:  true,
				cookie : true
			});
					
			//FB.Canvas.setAutoGrow();
		});	
	});
	
	var _login = function(responseCallback)
	{
		FB.login(responseCallback, {
			'scope': 'public_profile, email, publish_actions'
		});
	};
	
	var _sharePost = function(url, responseCallback)
	{
		FB.ui({
			method: 'share',
			href: url,
		},
		function(response) 
		{
			if (response && !response.error_code) {
				  $.event.trigger('post_completed', { responseData : response });
			} else {
				  $.event.trigger('post_error', { responseData : response });
			}
		});
	};
	
	return {
		login: _login,
		sharePost: _sharePost
	}
})(jQuery);