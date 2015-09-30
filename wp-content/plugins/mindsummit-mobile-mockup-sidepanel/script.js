jQuery(function(){
	
	var $ = jQuery;
	
	var $list = $('.gdlr-sidebar .widget_recent_entries').clone();
	
	//# sliding panel
	var $panel_inner = $('<div class="speaker-menu-inner">')
		.append($list);
	var $panel =
		$('<div class="speaker-menu"><div style="overflow:auto"></div></div>')
			.append($panel_inner)
			.appendTo('body')
			;
	
	var $panel_button =
		$('<div class="speaker-menu-button"><div class="speaker-menu-button-text"></div></div>')
			.appendTo($panel);
	
	//# open/close sliding panel
	var _open = false;
	var _p_width = $panel.outerWidth();
	$panel.css('right', -_p_width);
	$panel_button.click(function(){
		var new_right = _open ? -_p_width : Math.min(0, $(window).width() - _p_width);
		_open = !_open;
		$panel
			.finish()
			.animate({'right': new_right})
			;
	});
	
	//# implementing touch interface for sliding panel
	var obj = $('body').get(0);
	
	var shift;
	
	var begin_swipe = false;
	var window_width;
	var timer_begin;
	var touch_x;
	var touch_y;
	var last_x, last_y;
	
	obj.addEventListener('touchstart', function(e){
		
		window_width = $(window).width();
		touch_x = e.changedTouches[0].clientX;
		touch_y = e.changedTouches[0].clientY;
		
		if (!_open)
		{
			if (touch_x/window_width > 0.9)
			{
				begin_swipe = true;
				shift = 0;
			}
		}
		else
		{
			if (touch_x > window_width - _p_width)
			{
				begin_swipe = true;
				shift = touch_x - window_width + _p_width;
			}
		}

		timer_begin = (window.performance && window.performance.now) ? window.performance.now() : 0;
		last_x = touch_x;
		last_y = touch_y;
		
	}, false);
	
	obj.addEventListener('touchmove', function(e){
		
		if (!begin_swipe) return;
		
		var x = e.changedTouches[0].clientX;
		var y = e.changedTouches[0].clientY;
		
		if (_open)
		{
			if (Math.abs(x - last_x) > Math.abs(y - last_y))
			{
				$panel.css('right', Math.min(0, window_width + shift - x - _p_width));
				e.preventDefault();
			}
		}
		else
		{
			if (touch_x - x < 10)
				$panel.css('right', -_p_width);
			else
				$panel.css('right', Math.min(0, window_width + shift - x - _p_width));

			e.preventDefault();
		}
		
	}, false);

	obj.addEventListener('touchend', function(e){
		if (begin_swipe)
		{
			var x = e.changedTouches[0].clientX;
			var timer_end = (window.performance && window.performance.now) ? window.performance.now() : 0;
			var swipe_time = timer_end - timer_begin;
			var swipe_dist = x - touch_x;
			
			_open =
				(swipe_time && swipe_time < 350 && (swipe_dist > 80 || swipe_dist < -80))
				? (swipe_dist < 0)
				: ( (window_width - x + shift)/window_width > 0.4 )
				;
			
			$panel
				.finish()
				.animate({ 'right': _open ? Math.min(0, window_width - _p_width) : -_p_width })
				;
		}
		begin_swipe = false;
	}, false);

});

