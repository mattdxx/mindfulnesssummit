jQuery(function(){
	
	var MOBILE_WIDTH = 419;
	
	var $ = jQuery;
	var _already_ran = false;
	
	var $window = $(window);
	$window.resize(function(){
		
		if ($window.width() > MOBILE_WIDTH)
			return;
		
		if (_already_ran)
			return;
		
		_already_ran = true;
		
		var $content = $('.gdlr-blog-content');
		if (!$content.size())
			return
		
		$content.addClass('readmore-cut');
		
		var $shadow = $('<p class="readmore-shadow"></p>');
		$shadow.appendTo($content);
		
		var $more = $('<a class="readmore-link">');
		$more.insertAfter($content);
		
		$more.click(function() {
			
			$shadow.remove();
			$more.remove();
			
			var content_computed_style = getComputedStyle($content.get(0));
			var totalHeight =
				parseInt(content_computed_style.marginTop, 10) +
				parseInt(content_computed_style.marginBottom, 10) +
				parseInt(content_computed_style.paddingTop, 10) +
				parseInt(content_computed_style.paddingBottom, 10)
				;
			$content.children().each(function() {
				totalHeight += $(this).outerHeight();
			});

			$content
				.css({ "height": $content.height() })
				.removeClass('readmore-cut')
				.animate(
					{'height': totalHeight },
					{'complete': function(){ $content.css('height', 'auto') }}
					);
			
			return false;
			
		});
	
	});
});

