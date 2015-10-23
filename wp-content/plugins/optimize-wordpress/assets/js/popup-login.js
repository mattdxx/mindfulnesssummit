function showError(errorMsg) {
	var errorElm = jQuery('.popup-login-error'),
		updateMessage = function() {
			errorElm.removeClass('info');
			errorElm.find('span').html(errorMsg);
			errorElm.fadeIn();
		}
	if (errorElm.length > 0) {
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
			errorElm.fadeIn();
		}
	if (errorElm.length > 0) {
		if ('block' == errorElm.css('display')) {
			errorElm.fadeOut(400, function() {
				updateMessage();
			});
		} else {
			updateMessage();
		}
	}
}

function hideError() {
	jQuery('.popup-login-error').fadeOut(400, function() {
		jQuery('.popup-login-error').removeClass('info');
	});
}
function hideInfo() {
	hideError();
}

(function ($) {
	$('.popup-login-options .popup-login-cta').on('click', function(e) {
		e.preventDefault();
		var $this = $(this),
			$this_li = $this.parent(),
			$this_ul = $this_li.parent(),
			action = $this.data('rel');

		$('#popup-login-popup .popup-login-title, #popup-login-popup .popup-login-content, #popup-login-popup .popup-login-form, #popup-login-popup .popup-login-submit, .popup-login-options ul').addClass('on-switch');
		$('#popup-login-form input[name="action"]').val(action);
		window.setTimeout(function () {
			$('#popup-login-popup .action-login, #popup-login-popup .action-register, #popup-login-popup .action-reset').not('#popup-login-popup .action-' + action).hide();
			$('.popup-login-options .li-login, .popup-login-options .li-register, .popup-login-options .li-reset').not('.popup-login-options .li-' + action).show();
			$('#popup-login-popup .action-' + action).show();
			$('.popup-login-options .li-' + action).hide();
			$('#popup-login-popup .popup-login-title, #popup-login-popup .popup-login-content, #popup-login-popup .popup-login-form, #popup-login-popup .popup-login-submit, .popup-login-options ul').removeClass('on-switch');
		}, 500);
	})

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

			if ((name == '') || (name.length < 5)) {
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

		}

		if (!cont) {
			showError(errMsg);
			return false;
		}

		$('input[type="submit"]', $(this)).attr('disabled', 'disabled');
		$('.popup-login-options a').addClass('disabled');
	});

	function resize_popup_login() {
		// $('.gdlr-item.gdlr-column-shortcode.with-image').each(function() {
		// 	var $wrapper = $(this);
		// 		$inner_element = $wrapper.children().filter(function() {
		// 			var $element = $(this);
		// 			    return $element.css("display") == "block";
		// 		});
		// 	if ($inner_element.length == 1) {
		// 		if ($inner_element.hasClass('gdlr-image-wrapper')) {
		// 			$inner_element.css('width', ($wrapper.width() + 30) + 'px');
		// 		}
		// 		$wrapper.css('height', $inner_element.height() + 'px');
		// 	}
		// });
	}
	window.addEventListener('resize', function() {
		resize_popup_login();
	});
	$(document).ready(function() {
		setTimeout(function() {
			resize_popup_login();
		}, 2000);
	});
}(jQuery));