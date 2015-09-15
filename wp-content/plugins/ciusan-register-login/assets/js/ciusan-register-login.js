jQuery(document).ready(function ($) {
    // Display form from link inside a popup
	jQuery('#pop_login, #pop_signup').live('click', function (e) {
        formToFadeOut = jQuery('form#register');
        formtoFadeIn = jQuery('form#login');
        if (jQuery(this).attr('id') == 'pop_signup') {
            formToFadeOut = jQuery('form#login');
            formtoFadeIn = jQuery('form#register');
        }
        formToFadeOut.fadeOut(500, function () {
            formtoFadeIn.fadeIn();
        })
        return false;
    });
	
	// Display lost password form 
	jQuery('#pop_forgot').click(function(){
		formToFadeOut = jQuery('form#login');
		formtoFadeIn = jQuery('form#forgot_password');
		formToFadeOut.fadeOut(500, function () {
        	formtoFadeIn.fadeIn();
		})
		return false;
	});

	// Close popup
    jQuery(document).on('click', '.close', function () {
		jQuery('form#login, form#register, form#forgot_password').fadeOut(500, function () {
            jQuery('.crl_overlay').remove();
        });
        return false;
    });

    // Show the login/signup popup on click
    jQuery('#show_login, #show_signup').on('click', function (e) {
        jQuery('body').prepend('<div class="crl_overlay"></div>');
        if (jQuery(this).attr('id') == 'show_login')
			jQuery('form#login').fadeIn(500);
        else 
			jQuery('form#register').fadeIn(500);
        e.preventDefault();
    });

	// Perform AJAX login/register on form submit
	jQuery('form#login, form#register').on('submit', function (e) {
        if (!jQuery(this).valid()) return false;
        jQuery('p.status', this).show().text(ajax_auth_object.loadingmessage);
		action = 'ajaxlogin';
		username = 	jQuery('form#login #username').val();
		password = jQuery('form#login #password').val();
		email = '';
		security = jQuery('form#login #security').val();
		if (jQuery(this).attr('id') == 'register') {
			action = 'ajaxregister';
			username = jQuery('#signonname').val();
			password = jQuery('#signonpassword').val();
			email = jQuery('#email').val();
			security = jQuery('#signonsecurity').val();
			recaptcha = jQuery('#g-recaptcha-response').val();
		}
		ctrl = jQuery(this);
		$.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_auth_object.ajaxurl,
			data: {
				'action': action,
				'username': username,
				'password': password,
				'email': email,
				'security': security,
				'recaptcha': recaptcha
            },
            success: function (data) {
				if((jQuery(ctrl).attr ('id') == 'register') && (data.loggedin == false)) grecaptcha.reset();
				jQuery('p.status', ctrl).text(data.message);
				if (data.loggedin == true) {
					document.location.href = jQuery(ctrl).attr ('id') == 'register' ? ajax_auth_object.register_redirect : ajax_auth_object.redirecturl;
                }
            }
        });
        e.preventDefault();
    });

	// Perform AJAX forget password on form submit
	jQuery('form#forgot_password').on('submit', function(e){
		if (!jQuery(this).valid()) return false;
		jQuery('p.status', this).show().text(ajax_auth_object.loadingmessage);
		ctrl = jQuery(this);
		$.ajax({
			type: 'POST',
            dataType: 'json',
            url: ajax_auth_object.ajaxurl,
			data: { 
				'action': 'ajaxforgotpassword', 
				'user_login': jQuery('#user_login').val(), 
				'security': jQuery('#forgotsecurity').val(), 
			},
			success: function(data){					
				jQuery('p.status',ctrl).text(data.message);				
			}
		});
		e.preventDefault();
		return false;
	});

	// Client side form validation
    if (jQuery("#register").length) 
		jQuery("#register").validate();
    else if (jQuery("#login").length) 
		jQuery("#login").validate();
	if(jQuery('#forgot_password').length)
		jQuery('#forgot_password').validate();
});