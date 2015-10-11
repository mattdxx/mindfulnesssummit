(function ($) {
	$('.gdlr-item.gdlr-column-shortcode.with-image')
		.on('mouseenter', function() {
			var $this = $(this);
			$this.css('height', $('.gdlr-shortcode-wrapper', $this).height() + 'px');
			$('.gdlr-image-wrapper', $this).css('opacity', '0');
			$('.gdlr-shortcode-wrapper', $this).css('opacity', '1');
		})
		.on('mouseleave', function() {
			var $this = $(this);
			$this.css('height', $('.gdlr-image-wrapper', $this).height() + 'px');
			$('.gdlr-image-wrapper', $(this)).css('opacity', '1');
			$('.gdlr-shortcode-wrapper', $(this)).css('opacity', '0');
		});
	function resize_gdlr_with_image() {
		$('.gdlr-item.gdlr-column-shortcode.with-image').each(function() {
			var $wrapper = $(this);
				$inner_element = $wrapper.children().filter(function() {
					var $element = $(this);
					    return $element.css("opacity") == "1";
				});
			if ($inner_element.length == 1) {
				$wrapper.css('height', $inner_element.height() + 'px');
			}
		});
	}
	window.addEventListener('resize', function() {
		resize_gdlr_with_image();
	});
	$(document).ready(function() {
		setTimeout(function() {resize_gdlr_with_image();}, 1000);
	});
}(jQuery));