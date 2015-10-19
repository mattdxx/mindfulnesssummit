(function ($) {

	$('.action-register', '#popup-login-popup').show();
	$('.popup-login-options .li-register').hide();

	$('.popup-login-options .popup-login-cta').on('click', function(e) {
		e.preventDefault();
		var $this = $(this),
			$this_li = $this.parent(),
			$this_ul = $this_li.parent(),
			action = $this.data('rel');

		$('#popup-login-popup .popup-login-title, #popup-login-popup .popup-login-content, #popup-login-popup .popup-login-form, #popup-login-popup .popup-login-submit, .popup-login-options ul').addClass('on-switch');
		window.setTimeout(function () {
			$('#popup-login-popup .action-login, #popup-login-popup .action-register, #popup-login-popup .action-reset').not('#popup-login-popup .action-' + action).hide();
			$('.popup-login-options .li-login, .popup-login-options .li-register, .popup-login-options .li-reset').not('.popup-login-options .li-' + action).show();
			$('#popup-login-popup .action-' + action).show();
			$('.popup-login-options .li-' + action).hide();
			$('#popup-login-popup .popup-login-title, #popup-login-popup .popup-login-content, #popup-login-popup .popup-login-form, #popup-login-popup .popup-login-submit, .popup-login-options ul').removeClass('on-switch');
		}, 500);
	})

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
		$('#popup-login-wrapper').fadeIn(800);
		setTimeout(function() {
			resize_popup_login();
		}, 2000);
	});
}(jQuery));