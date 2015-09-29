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
});

