var visible = false,
	popupTimer = {};
function showError(errorMsg) {
	var errorElm = jQuery('.popup-login-error'),
		updateMessage = function() {
			errorElm.removeClass('info');
			errorElm.find('span').html(errorMsg);
			errorElm.fadeIn(400, function() {
				popupTimer = setTimeout(function() {
					hideError();
				}, 5000);
			});
 
		}
	if (errorElm.length > 0) {
		clearTimeout(popupTimer);
		if ('block' == errorElm.css('display')) {
			errorElm.fadeOut(400, function() {
				updateMessage();
			});
		} else {
			updateMessage();
		}
	}
}
function showInfo(errorMsg) {
	var errorElm = jQuery('.popup-login-error'),
		updateMessage = function() {
			errorElm.addClass('info');
			errorElm.find('span').html(errorMsg);
			errorElm.fadeIn(600, function() {
				popupTimer = setTimeout(function() {
					hideInfo();
				}, 5000);
			});
		}
	if (errorElm.length > 0) {
		clearTimeout(popupTimer);
		if ('block' == errorElm.css('display')) {
			errorElm.fadeOut(600, function() {
				updateMessage();
			});
		} else {
			updateMessage();
		}
	}
}

function hideError() {
	clearTimeout(popupTimer);
	jQuery('.popup-login-error').fadeOut(600, function() {
		jQuery('.popup-login-error').removeClass('info');
	});
}
function hideInfo() {
	hideError();
}

(function ($) {
	var calculate_height = function() {
		window.setTimeout(function() {
			var action = $('#popup-login-form input[name="action"]').val();
			$('#popup-login-form').animate({'height': $('.action-' + action).height() + 'px'}, 400);
		}, 100);
	};

	$('body').on('click', '.popup-login-options .popup-login-cta', function(e) {
		e.preventDefault();
		var $this = $(this),
			$this_li = $this.parent(),
			$this_ul = $this_li.parent(),
			action = $this.data('rel'),
			prev_action = $('#popup-login-form input[name="action"]').val();

		hideError();

		$('.action-' + prev_action).fadeOut(250, function() {
			$('#' + action + '-email').val($('#' + prev_action + '-email').val()); // Copy the entered email from action to action
			$('#popup-login-form input[name="action"]').val(action); // Set the new action
			$('.action-' + action).slideDown(250, function() {
				calculate_height();
			});
			$('.popup-login-options .li-login, .popup-login-options .li-register, .popup-login-options .li-reset').not('.popup-login-options .li-' + action).show();
			$('.popup-login-options .li-' + action).hide();
		});

	});
	$('body').on('click', '.call-register', function(e) {
		e.preventDefault();
		hideError();
		$('a[data-rel="register"]').click();
	});
	$('body').on('click', '.call-login', function(e) {
		e.preventDefault();
		hideError();
		$('a[data-rel="login"]').click();
	});
	$('body').on('click', '.call-reset', function(e) {
		e.preventDefault();
		hideError();
		$('a[data-rel="reset"]').click();
	});

	$('.popup-login-error-close').on('click', function() {
		hideError();
	});

	$('#popup-login-form').on('submit', function() {

		hideError();

		var action = $('#popup-login-form input[name="action"]').val(),
			cont = true,
			errMsg = '';
		if ('login' == action) {
			var email = $('#login-email').val().trim(),
				password = $('#login-password').val().trim(),
				re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;

			if (email == '' || !re.test(email)) {
				cont = false;
				errMsg = 'Please enter a valid email address.';
			} else if (password == '') {
				cont = false;
				errMsg = 'Password cannot be empty.';
			}
		} else if ('register' == action) {
			var name = $('#register-name').val().trim(),
				email = $('#register-email').val().trim(),
				password = $('#register-password').val().trim(),
				re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;

			if ((name == '') || (name.length < 2)) {
				cont = false;
				errMsg = 'Please enter a valid name.';
			} else if (email == '' || !re.test(email)) {
				cont = false;
				errMsg = 'Please enter a valid email address.';
			} else if (password == '') {
				cont = false;
				errMsg = 'Password cannot be empty.';
			}
		} else if ('reset' == action) {
			var email = $('#reset-email').val().trim(),
				re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
			if (email == '' || !re.test(email)) {
				cont = false;
				errMsg = 'Please enter a valid email address.';
			}
		}

		if (!cont) {
			showError(errMsg);
			return false;
		}

		$('input[type="submit"]', $(this)).attr('disabled', 'disabled');
		$('.popup-login-options a').addClass('disabled');
	});

	window.addEventListener('resize', function() {
		calculate_height();
	});
	$(document).ready(function() {
		setTimeout(function() {
			calculate_height();
		}, 2000);
	});
}(jQuery));