//# stretch registration modal in mobile view
jQuery('.popmake').on('popmakeAfterReposition', function(){
	
	var $ = jQuery;
	
	if ($(window).width() < 740)
		$('.popmake').css({
				top: 0,
				left: '0',
				width: '100%',
				height: '100%',
				position: 'fixed'
			});

});

jQuery('.popmake').on('popmakeInit', function(){

	//('console' in window) && ('log' in window.console) && window.console.log('fix ok');
	
	var $ = jQuery;
	
	var login = $('.popmake-login-form');
	var registration = $('.popmake-registration-form');
	var registration_form = $('form', registration);
	var login_form = $('form', login);
	var recovery = $('.popmake-recovery-form');

	// we want to be able to parse query_string
	var _get_param = function(p){
			var qs = document.location.search.substring(1).replace('+', '%20').split('&');
			var _p = p+'=';
			var _p_len = _p.length;
			for (var i = 0; i < qs.length; i++)
				if (qs[i].substring(0, _p_len) == _p)
					return decodeURIComponent(qs[i].substring(_p_len));
		};

	// check if 'login' form should appear first
	var pop = _get_param('pop');
	switch (pop)
	{
		case 'login':
			recovery.hide();
			registration.hide();
			login.show();
			break;
		case 'reg':
			// same as default
		default:
			recovery.hide();
			login.hide()
			registration.show();
	}
	
	// prefill email address if any
	var email = _get_param('email');
	if (email) {
		login.find('input[name=log]').val(email);
		registration.find('input[name=user_email]').val(email);
		recovery.find('input[name=user_login]').val(email);
	};
	
	var fname = _get_param('fname');
	var lname = _get_param('lname');
	var fullname = _get_param('fullname');
	var uname = _get_param('uname');
	if ( !(fname || lname) && fullname)
	{
		var fullname_parts = fullname.split(' ', 2);
		fname = fullname_parts[0] || '';
		lname = fullname_parts[1] || '';
	}
	if (fname || lname)
		registration.find('input[name=user_login]').val(fname+' '+lname);
	
	//# change css properties
	registration.find('.registration-username').css('width', '100%');
	registration.find('.registration-email').css('display', 'inline-block');
	registration.find('.registration-email').css('float', 'none');
	registration.find('.registration-password').hide();
	//# replace labels
	registration.find('.registration-confirm label').text('Password');
	registration.find('.registration-username label').text('Name (i.e. First & Last name)');
	login.find('.login-username label').text('Email');
	$('ul.popmake-alm-footer-links li').each(function(){
		for (var n = this.firstChild; n; n = n.nextSibling)
			if (n.nodeType == 3) //# TEXT_NODE
				n.nodeValue = n.nodeValue.replace('account', "'Access Pass'");
	});
	
	//# this is a data from a database
	var titles = window.popmake_login_appearance || {};
	
	//# placeholders
	var text_shift = '';
	registration.find('input[name=user_login]').attr(
			'placeholder',
			text_shift + (titles.regphname || 'Your First & Last name')
		);
	registration.find('input[name=user_email]').attr(
			'placeholder',
			text_shift + (titles.regphemail || 'Email')
		);
	registration.find('input[name=user_pass2]').attr(
			'placeholder',
			text_shift + (titles.regphpass || 'Password')
		);
	login.find('input[name=log]').attr(
			'placeholder',
			text_shift + (titles.logphemail || 'Email')
		);
	login.find('input[name=pwd]').attr(
			'placeholder',
			text_shift + (titles.logphpass || 'Password')
		);
	recovery.find('input[name=user_login]').attr(
			'placeholder',
			text_shift + (titles.recphemail || 'Email')
		);
	
	// fix titles
	var popmake_title = $('.popmake-title');
	var reg_title = 
		popmake_title.clone()
			.html(titles.regcapt || 'Register')
			.insertBefore( $('.popmake-registration-form>:first') )
		;
	var login_title =
		popmake_title.clone()
			.html(titles.logcapt || 'Log in')
			.insertBefore( $('.popmake-login-form>:first') )
		;
	var recovery_title =
		popmake_title.clone()
			.html(titles.reccapt || 'Password reset')
			.insertBefore( $('.popmake-recovery-form>:first') )
		;
	popmake_title.remove();
	
	// paragraph text (registration form)
	var paragraph_text = email ? titles.regtext2 : titles.regtext;
	paragraph_text &&
		$('<p>')
			.html(paragraph_text)
			.insertAfter(reg_title);
	
	// precheck and hide 'remember me' checkbox
	login.find('[name=rememberme]').prop('checked', true);
	login.find('.login-remember').hide();
	
	// do not show the form if user is already registered one
	document.cookie.match('(?:^|;)\s*mindsummitreg=1\s*(?:;|$)') &&
		$('.popmake').popmake('close');
	
	// uname randomizer
	var random_uname = function(email) {
		var email_fp = String.prototype.split.call(email, '@');
		var uname = email_fp[0].replace(/[^a-zA-Z0-9]/g, '_');
		if (!uname)
			uname = 'default';
		
		var min = 1;
		var max = 9999;
		var r = Math.floor(Math.random() * (max - min + 1)) + min;
		if (r < 10)
			return ''+uname+'000'+r;
		if (r < 100)
			return ''+uname+'00'+r;
		if (r < 1000)
			return ''+uname+'0'+r;
		return ''+uname+r;
	}

	// cheating with hidden elements: certain fields are filled
	// just before sending them to server
	var _old_serializer = $.fn.serializeObject;
	$.fn.serializeObject = function(){
		
		var is_reg_form = 0;
		this.each(function(){
			var id = this.id || (('getAttribute' in this) && this.getAttribute('id'))
			if (id == 'ajax-registration-form') {
				is_reg_form = 1;
				//# want to pass additional checks in original plugin
				this.user_pass.value = this.user_pass2.value;
			}
			if (id == 'ajax-recovery-form') {
				//# remember user's email
				var date = new Date();
				date.setDate(date.getDate() + 1);
				document.cookie =
					'mindsummit_passreset_email='+encodeURIComponent(this.user_login.value) +
					'; path=/; expires='+date.toUTCString();
			}

		});
		
		var o = _old_serializer.call(this);
		
		if (is_reg_form)
		{
			var _fullname = o.user_login;
			if (fname+' '+lname == _fullname)
			{	//# user hasn't changed them
				if (fname) o.fname = fname;
				if (lname) o.lname = lname;
			}
			else if (_fullname)
			{	//# user has changed them
				var fullname_parts = _fullname.split(' ', 2);
				o.fname = fullname_parts[0];
				o.lname = fullname_parts[1];
			}
			o.user_login = uname ? uname : random_uname(o.user_email);
			o.popmake_reg = 1;
		}
		
		return o;
	};
	
});

