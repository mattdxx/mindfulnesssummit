(function ($) {
	$('.gdlr-item.gdlr-column-shortcode.with-image')
		.on('click', function() {
			var $this = $(this);
			if ($('.gdlr-image-wrapper', $this).css('display') == 'block') {
				$this.css('height', $('.gdlr-shortcode-wrapper', $this).height() + 'px');
				$('.gdlr-image-wrapper', $this).fadeOut();
				$('.gdlr-shortcode-wrapper', $this).fadeIn();
			}
		})
		.on('mouseleave', function() {
			var $this = $(this);
			if ($('.gdlr-shortcode-wrapper', $this).css('display') == 'block') {
				$this.css('height', $('.gdlr-image-wrapper', $this).height() + 'px');
				$('.gdlr-shortcode-wrapper', $this).fadeOut();
				$('.gdlr-image-wrapper', $this).fadeIn();
			}
		});

	function resize_gdlr_with_image() {
		$('.gdlr-item.gdlr-column-shortcode.with-image').each(function() {
			var $wrapper = $(this);
				$inner_element = $wrapper.children().filter(function() {
					var $element = $(this);
					    return $element.css("display") == "block";
				});
			if ($inner_element.length == 1) {
				if ($inner_element.hasClass('gdlr-image-wrapper')) {
					$inner_element.css('width', ($wrapper.width() + 30) + 'px');
				}
				$wrapper.css('height', $inner_element.height() + 'px');
			}
		});
	}
	window.addEventListener('resize', function() {
		resize_gdlr_with_image();
	});
	$(document).ready(function() {
		setTimeout(function() {
			resize_gdlr_with_image();
			$('.gdlr-item.gdlr-column-shortcode.with-image .gdlr-image-wrapper.static').each(function() {
				var $image = $(this);
				$image.removeClass('static');
			});
		}, 2000);
	});
}(jQuery));