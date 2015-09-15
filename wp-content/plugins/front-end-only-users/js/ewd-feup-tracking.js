jQuery(document).ready(function() {	
	if (typeof(User_ID) != "undefined" && User_ID !== null) {
		jQuery('a').click( function(event) {
			event.preventDefault();
			var link = this;

			var CurrentLocation = jQuery(location).attr('href');
			var data = 'User_ID=' + User_ID + '&Target=' + event.target + '&Location=' + CurrentLocation + '&action=feup_user_event';
			jQuery.post(ajaxurl, data, function(response) {});
			
			// unbind the click event and then simulate a click 
			// so that the default action occurs
			jQuery(link).unbind('click');
			setTimeout(function(){
				var a = jQuery(link)[0];
				var evObj = document.createEvent('MouseEvents');
				evObj.initMouseEvent('click', true, true, window);
				a.dispatchEvent(evObj);
			}, 100);
		});
	}
});