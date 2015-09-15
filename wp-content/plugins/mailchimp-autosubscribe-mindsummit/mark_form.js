jQuery('.popmake').on('popmakeInit', function(){

	//('console' in window) && ('log' in window.console) && window.console.log('fix ok');
	
	// cheating with form serializer: add mark
	// just before sending the form to the server
	var _old_serializer = jQuery.fn.serializeObject;
	jQuery.fn.serializeObject = function(){
		var o = _old_serializer.call(this);
		this.each(function(){
			var id = this.id || (('getAttribute' in this) && this.getAttribute('id'))
			if (id == 'ajax-registration-form')
				o.mailchimp_autosubscribe = 1;
		});
		return o;
	};
	
});

