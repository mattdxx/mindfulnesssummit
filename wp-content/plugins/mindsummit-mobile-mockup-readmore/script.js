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
		
		//# This should be a hack. Client wants to hide the text,
		//# but video-iframe is to be visible. So I will place all
		//# the content before the video in a separate div.
		var $content = (function($c){

			var before_iframe = true;
			var before_iframe_list = [];
			$c.children().each(function(){
				if (!before_iframe)
					return;
				var $this = $(this)
				if ($this.find('iframe').size())
					before_iframe = false;
				else
					before_iframe_list.push($this);
			});

			var $d = $('<div>');
			$d.prependTo($c);

			for (var i = 0; i < before_iframe_list.length; i++)
				before_iframe_list[i].appendTo($d);
			
			return $d;
		
		})($content);

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

