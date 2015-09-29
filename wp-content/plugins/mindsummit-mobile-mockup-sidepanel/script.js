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
	
	//# buynow link
	var $buynow_button = $('<div class="buynow-link">')
		.appendTo('body');
		
	//# window resize handler
	var _pb_width, _bb_height, _pb_shift, _bb_shift;
	var _resize_handler = function(){
			
			if (window.getComputedStyle($panel.get(0)).display == 'none')
				return;
			
			if (!_pb_width) _pb_width = $panel_button.outerWidth(true);
			if (!_bb_height) _bb_height = $buynow_button.outerHeight(true);
			if (!_pb_shift) _pb_shift = (_pb_width + _bb_height)/2;
			if (!_bb_shift) _bb_shift = _pb_shift - _pb_width + _bb_height;
			
			var _w_middle = $(this).height()/2 - 50;
			$panel_button.css('top', _w_middle - _pb_shift);
			$buynow_button.css('top', _w_middle - _bb_shift);
		};
	$(window).resize(_resize_handler);
	_resize_handler.call(window);
	
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
});

