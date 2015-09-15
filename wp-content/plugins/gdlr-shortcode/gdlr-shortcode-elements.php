<?php

	// convert font awesome class to new version
	if( !function_exists('gdlr_fa_class') ){	
		function gdlr_fa_class($class){
			global $theme_option;
			
			if( !empty($theme_option['new-fontawesome']) && $theme_option['new-fontawesome'] == 'enable' ){
				$class = str_replace('icon-', 'fa-', $class);
				return str_replace('-alt', '-o', $class);
			}else{
				return $class;
			}
		}
	}

	// text align
	add_shortcode('gdlr_text_align', 'gdlr_text_align_shortcode');
	function gdlr_text_align_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('class' => 'center'), $atts) );
		return '<div style="text-align: ' . $class . '" >' . do_shortcode($content) . '</div>';
	}

	// location
	add_shortcode('gdlr_location', 'gdlr_location_shortcode');
	function gdlr_location_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('phone' => '', 'email' => '', 'location'=>'', 'title'=>''), $atts) );
		
		$ret  = '<div class="gdlr-location-item" >';
		$ret .= empty($title)? '': '<div class="gdlr-location-title">' . $title . '</div>';
		$ret .= empty($location)? '': '<div class="gdlr-location-place"><i class="fa ' . gdlr_fa_class('icon-location-arrow') . '" ></i>' . $location . '</div>';
		
		$ret .= empty($content)? '': '<div class="gdlr-location-content">' . $content . '</div>';
		
		if( !empty($email) || !empty($phone) ){
			$ret .= '<div class="gdlr-location-info">';
			$ret .= empty($phone)? '': '<span><i class="fa ' . gdlr_fa_class('icon-phone') . '" ></i>' . $phone . '</span>';
			$ret .= empty($email)? '': '<span><i class="fa ' . gdlr_fa_class('icon-envelope') . '" ></i>' . $email . '</span>';
			$ret .= '</div>';
		}
		$ret .= '</div>';
		return $ret;
	}	
	
	// accordion
	add_shortcode('gdlr_accordion', 'gdlr_accordion_shortcode');
	function gdlr_accordion_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('style' => 'style-1', 'initial'=>'1'), $atts) );
	
		global $gdlr_shortcode_tabs; $gdlr_shortcode_tabs = array();
		$settings = array();
		
		do_shortcode($content);
		$settings['style'] = $style;
		$settings['initial-state'] = $initial;
		$settings['accordion'] = $gdlr_shortcode_tabs;

		if( function_exists('gdlr_get_accordion_item') ){
			return '<div class="gdlr-shortcode-wrapper">' . gdlr_get_accordion_item($settings) . '</div>';
		}
		return '';
	}	
	
	// toggle box
	add_shortcode('gdlr_toggle_box', 'gdlr_toggle_box_shortcode');
	function gdlr_toggle_box_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('style' => 'style-1'), $atts) );
	
		global $gdlr_shortcode_tabs; $gdlr_shortcode_tabs = array();
		$settings = array();
		
		do_shortcode($content);
		$settings['style'] = $style;
		$settings['toggle-box'] = $gdlr_shortcode_tabs;
		
		if( function_exists('gdlr_get_toggle_box_item') ){
			return '<div class="gdlr-shortcode-wrapper">' . gdlr_get_toggle_box_item($settings) . '</div>';
		}
		return '';
	}	
	
	// toggle box
	add_shortcode('gdlr_process', 'gdlr_process_shortcode');
	function gdlr_process_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('type' => 'vertical', 'min_height'=>''), $atts) );
	
		global $gdlr_shortcode_tabs; $gdlr_shortcode_tabs = array();
		
		do_shortcode($content);

		$style  = ' style="';
		$style .= empty($min_height)? '': 'min-height: ' . $min_height . ';';
		$style .= '" ';
		
		$ret  = '<div class="gdlr-shortcode-wrapper">';
		$ret .= '<div class="gdlr-process-wrapper gdlr-' . $type . '-process">';
		
		$size = sizeof($gdlr_shortcode_tabs); $item_num = $size;
		foreach( $gdlr_shortcode_tabs as $tab ){
			$ret .= ($type == 'horizontal')? '<div class="' . gdlr_get_column_class('1/' . $size) . '">': '';
			$ret .= '<div class="gdlr-item gdlr-process-tab" ' . $style . '>';
			if( !empty($tab['gdl-tab-icon']) ){
				$ret .= '<div class="gdlr-process-icon" >';
				$ret .= '<i class="fa ' . gdlr_fa_class($tab['gdl-tab-icon']) . '" ></i>';
				$ret .= '</div>';
			}
			$ret .= '<div class="gdlr-process-tab-content" >';
			$ret .= '<h3 class="gdlr-process-title" >' . $tab['gdl-tab-title'] . '</h3>';
			$ret .= '<div class="gdlr-process-caption" >' . $tab['gdl-tab-content'] . '</div>';
			$ret .= '</div>'; // gdlr-process-tab-content
			
			if( $item_num > 1 ){
				$ret .= '<div class="process-line">';
				$ret .= '<div class="process-line-divider"></div>';
				$ret .= ($type == 'horizontal')? '<div class="fa ' . gdlr_fa_class('icon-chevron-right') . '" ></div>': '<div class="fa ' . gdlr_fa_class('icon-chevron-down') . '" ></div>';
				$ret .= '</div>';
			}
			
			$ret .= '</div>'; // gdlr-process-tab-wrapper
			$ret .= ($type == 'horizontal')? '</div>': ''; // gdlr_get_column_class

			$item_num--;
		}
		
		$ret .= '<div class="clear"></div>';
		$ret .= '</div>';
		$ret .= '</div>';
		
		return $ret;
	}	
	
	// price_table
	add_shortcode('gdlr_price_table', 'gdlr_price_table_shortcode');
	function gdlr_price_table_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('columns' => '3'), $atts) );
	
		global $gdlr_shortcode_tabs; $gdlr_shortcode_tabs = array();
		$settings = array();
		
		do_shortcode($content);
		$settings['columns'] = $columns;
		$settings['price-table'] = $gdlr_shortcode_tabs;
		
		if( function_exists('gdlr_get_price_table_item') ){
			return '<div class="gdlr-shortcode-wrapper">' . gdlr_get_price_table_item($settings) . '</div>';
		}
		return '';
	}		
	
	// tabs
	add_shortcode('gdlr_tabs', 'gdlr_tabs_shortcode');
	function gdlr_tabs_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('style' => 'horizontal', 'initial'=>'1'), $atts) );
	
		global $gdlr_shortcode_tabs; $gdlr_shortcode_tabs = array();
		$settings = array();
		
		do_shortcode($content);
		$settings['style'] = $style;
		$settings['initial-state'] = $initial;
		$settings['tab'] = $gdlr_shortcode_tabs;
		
		if( function_exists('gdlr_get_tab_item') ){
			return '<div class="gdlr-shortcode-wrapper">' . gdlr_get_tab_item($settings) . '</div>';
		}
		return '';
	}		

	// item within shortcode
	add_shortcode('gdlr_tab', 'gdlr_tab_shortcode');
	function gdlr_tab_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('title' => '', 'id' => '', 'active' => 'no', 'icon_title'=> '',
			'author_image'=>'', 'position'=>'', 'icon'=>'', 'price'=>'', 'link'=>''), $atts) );

		global $gdlr_shortcode_tabs;
		
		array_push($gdlr_shortcode_tabs, array(
			'gdl-tab-icon'=>gdlr_fa_class($icon), 
			'gdl-tab-icon-title'=>$icon_title, 
			'gdl-tab-title'=>$title, 
			'gdl-tab-title-id'=>$id, 
			'gdl-tab-price'=>$price, 
			'gdl-tab-content'=>do_shortcode($content), 
			'gdl-tab-active'=>$active,
			'gdl-tab-link'=>$link,
			'gdl-tab-author-image'=>$author_image,
			'gdl-tab-position'=>$position,
		));
	}	
	
	// button shortcode
	add_shortcode('gdlr_button', 'gdlr_button_shortcode');
	function gdlr_button_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('size'=>'medium', 'href'=>'#', 'target'=>'_self', 'background'=>'', 
			'color'=>'', 'with_border'=>'no', 'border_color'=>''), $atts) );
		$style = '';
		if(!empty($background) || !empty($color) || !empty($border_color) ){
			$style  = ' style="';
			$style .= !empty($color)? 'color:' . $color . '; ': '';
			$style .= !empty($background)? 'background-color:' . $background . '; ': '';
			$style .= !empty($border_color)? 'border-color:' . $border_color . '; ': '';
			$style .= '" ';
		}
		$button_class  = $size;
		$button_class .= !empty($border_color)? ' with-border': ''; 
		return '<a class="gdlr-button ' . $button_class . '" href="' . $href . '" target="' . $target . '" ' . $style . ' >' . $content . '</a>';
	}	
	
	// header shortcode
	add_shortcode('gdlr_heading', 'gdlr_heading_shortcode');
	function gdlr_heading_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('tag'=>'h2', 'size'=>'', 'font_weight'=>'',
			'color'=>'', 'background'=>'', 'icon'=>'', 'opacity'=>''), $atts) );	
			
		$style = ''; $class = '';
		if( !empty($color) || !empty($background)|| !empty($font_weight) || !empty($size) ){
			$style .= ' style="';
			$style .= empty($color)? '': 'color: ' . $color . ';';
			$style .= empty($background)? '': 'background-color: ' . $background . ';';
			$style .= empty($size)? '': 'font-size: ' . $size . ';';
			$style .= empty($font_weight)? '': 'font-weight: ' . $font_weight . ';';
			$style .= empty($opacity)? '': 'opacity: ' . ($opacity / 100) . '; filter: alpha(opacity=' . $opacity . '); ';
			$style .= '" ';
			
			$class .= empty($background)? '': 'with-background ';
		}
		
		$ret  = '<' . $tag . ' class="gdlr-heading-shortcode ' . $class . '" ' . $style . '>';
		$ret .= empty($icon)? '': '<i class="fa ' . gdlr_fa_class($icon) . '" ></i>'; 
		$ret .= $content;
		$ret .= '</' . $tag . '>';
		return $ret;
	}	
	
	// divider shortcode
	add_shortcode('gdlr_divider', 'gdlr_divider_shortcode');
	function gdlr_divider_shortcode( $atts ){
		extract( shortcode_atts(array('type' => 'solid', 'size'=>''), $atts) );	
		$settings = array('type'=>$type, 'size'=>$size);

		if( function_exists('gdlr_get_divider_item') ){
			return '<div class="gdlr-shortcode-wrapper">' . gdlr_get_divider_item($settings) . '</div>';
		}
		return '';
	}
	
	// circle progress shortcode
	add_shortcode('gdlr_circle_progress', 'gdlr_circle_progress_shortcode');
	function gdlr_circle_progress_shortcode( $atts, $content = null ){
		wp_enqueue_script('jquery-easypiechart', GDLR_PATH . '/plugins/easy-pie-chart/jquery.easy-pie-chart.js', array(), '1.0', true);	
	
		extract( shortcode_atts(array('percent' => '50', 'size'=>'155', 'line_width'=>'8', 'background_color'=>'',
			'progress_background_color'=>'#e9e9e9', 'progress_color'=>'#a9e16e', 'content_color'=>''), $atts) );	
		
		$content_style = empty($content)? '': ' style="color: ' . $content_color . ';" ';
		
		$ret  = '<div class="gdlr-chart gdlr-ux" data-percent="' . $percent . '" data-size="' . $size . '" data-linewidth="' . $line_width . '" ';
		$ret .= 'data-color="' . $progress_color . '" data-bg-color="' . $progress_background_color . '" data-background="' . $background_color . '" >';
		$ret .= '<div class="chart-content-wrapper">';
		$ret .= '<div class="chart-content-inner">';
		$ret .= '<span class="chart-content" ' . $content_style . ' >' . do_shortcode($content) . '</span>';
		$ret .= '<span class="chart-percent-number" style="color:' . $progress_color . ';" >' . $percent . '%' . '</span>';
		$ret .= '</div>'; // chart-content-inner
		$ret .= '</div>'; // chart-content-wrapper
		$ret .= '</div>'; // gdlr-chart		
		return $ret;
	}	
	
	// stunning text shortcode
	add_shortcode('gdlr_stunning_text', 'gdlr_stunning_text_shortcode');
	function gdlr_stunning_text_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('background_color'=>'', 'border_color'=>'#f3f3f3',
			'button'=>'', 'button_link'=>'', 'button_background'=> '',
			'button_text_color'=>'', 'button_border_color'=>'',
			'title'=>'', 'title_color'=>'', 'caption_color'=>''), $atts) );		
	
		$stunning_class  = 'type-normal with-padding ';
		$stunning_class .= (empty($button) || empty($button_link))? '': 'gdlr-button-on ';
		$stunning_class .= (empty($background_color))? 'with-border ': '';
		
		$stunning_style  = 'style="';
		$stunning_style .= (empty($background_color))? 'border-color:' . $border_color . ';' : 'background-color:' . $background_color . ';';
		$stunning_style .= '"';
		
		$ret  = '<div class="gdlr-stunning-text-ux gdlr-ux">';
		$ret .= '<div class="gdlr-stunning-text-item ' . $stunning_class . '" ' . $stunning_style . ' >';
		if(!empty($title)){
			$stunning_style = empty($title_color)? '': 'style="color: ' . $title_color . '" ';
			$ret .= '<h2 class="stunning-text-title" ' . $stunning_style . '>' . $title . '</h2>';
		}
		if(!empty($content)){
			$stunning_style = empty($caption_color)? '': 'style="color: ' . $caption_color . '" ';
			$ret .= '<div class="stunning-text-caption" ' . $stunning_style . '>' . $content . '</div>';
		}
		if(!empty($button) && !empty($button_link)){
			$stunning_style = '';
			if(!empty($button_background) || !empty($button_text_color) || !empty($button_border_color)){
				$stunning_style  = 'style="';
				$stunning_style .= (empty($button_background))? '': 'background-color:' . $button_background . '; ';
				$stunning_style .= (empty($button_text_color))? '': 'color:' . $button_text_color . '; ';
				$stunning_style .= (empty($button_border_color))? '': 'border-color:' . $button_border_color . '; ';
				$stunning_style .= '"';
			}
			
			$ret .= '<a class="stunning-text-button gdlr-button with-border" href="' . $button_link . '" ' . $stunning_style . '>';
			$ret .= $button;
			$ret .= '</a>';
		}
		$ret .= '</div>'; // gdlr-item
		$ret .= '</div>'; // gdlr-ux
		
		return $ret;		
	}		
	
	// skill bar shortcode
	add_shortcode('gdlr_skill_bar', 'gdlr_skill_bar_shortcode');
	function gdlr_skill_bar_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('percent' => '50', 'size'=>'medium', 'text_color'=>'#ffffff', 
			'background_color'=>'#e9e9e9', 'progress_color'=>'#a9e16e', 'icon'=>''), $atts) );	
		
		$settings = array();
		$settings['percent'] = $percent;
		$settings['size'] = $size;
		$settings['text-color'] = $text_color;
		$settings['background-color'] = $background_color;
		$settings['progress-color'] = $progress_color;
		$settings['icon'] = gdlr_fa_class($icon);
		$settings['content'] = $content;
	
		if( function_exists('gdlr_get_divider_item') ){
			return '<div class="gdlr-shortcode-wrapper">' . gdlr_get_skill_bar_item($settings) . '</div>';
		}
		return '';
	}	

	// column shortcode
	add_shortcode('gdlr_row', 'gdlr_row_shortcode');
	add_shortcode('gdlr_column', 'gdlr_column_shortcode');
	function gdlr_row_shortcode( $atts, $content = null ){
		return '<div class="gdlr-shortcode-wrapper gdlr-row-shortcode">' . do_shortcode($content) . '<div class="clear"></div></div>';
	}	
	function gdlr_column_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('size' => '1/2'), $atts) );	
		
		if( function_exists('gdlr_get_column_class') ){
			$ret  = '<div class="' . gdlr_get_column_class($size) . '">';
			$ret .= '<div class="gdlr-item gdlr-column-shortcode">';
			$ret .= do_shortcode($content);
			$ret .= '</div>';
			$ret .= '</div>';
			return $ret;
		}
		return '';
	}	
	
	// frame shortcode
	add_shortcode('gdlr_frame', 'gdlr_frame_shortcode');
	function gdlr_frame_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('type'=>'border', 'align'=>'left', 'caption'=>'',
			'background_color' => '#dddddd'), $atts) );	
		
		$style = (strpos($type, 'solid') !== false)? ' style="background-color: ' . $background_color . ';" ': '';
		$type = str_replace(' ', ' frame-type-', trim($type));
		
		$ret  = '<div class="gdlr-frame-shortcode gdlr-align-' . $align . '">';
		$ret .= '<div class="gdlr-frame frame-type-' . $type . '" ' . $style . '>';
		$ret .= do_shortcode($content);
		$ret .= '</div>';
		$ret .= '<span class="gdlr-frame-caption">' . $caption . '</span>';
		$ret .= '</div>';
		return $ret;
	}	
	
	// lightbox shortcode
	add_shortcode('gdlr_image_link', 'gdlr_image_link_shortcode');
	function gdlr_image_link_shortcode( $atts ){
		extract( shortcode_atts(array('type'=>'image', 'image_url'=>'', 'link_url'=>'', 
			'alt'=>'', 'target'=>'_self', 'width'=>''), $atts) );	
			
		$link_attr = ''; $link_icon = '';
		if($type == 'video'){
			$link_icon = 'icon-film';
			$link_attr = ' data-rel="fancybox" data-fancybox-type="iframe" ';
		}else if($type == 'image'){
			$link_icon = 'icon-search';
			$link_attr = ' data-rel="fancybox" ';
		}else{
			$link_icon = 'icon-link';
		}
		
		$style = empty($width)? '': 'style="max-width: ' . $width . '"';
		
		$ret  = '<div class="gdlr-image-link-shortcode" ' . $style . ' >';
		$ret .= '<img src="' . $image_url . '" alt="' . $alt . '" />';
		$ret .= '<a href="' . $link_url . '" ' . $link_attr . ' target="' . $target . '" >';
		$ret .= '<span class="gdlr-image-link-overlay">&nbsp;</span>';
		$ret .= '<span class="gdlr-image-link-icon"><i class="fa ' . gdlr_fa_class($link_icon) . '" ></i></span>';
		$ret .= '</a>';
		$ret .= '</div>';
		return $ret;
	}	
	
	// space shortcode
	add_shortcode('gdlr_space', 'gdlr_space_shortcode');
	function gdlr_space_shortcode( $atts ){
		extract( shortcode_atts(array('height' => '20px'), $atts) );	
		
		$ret  = '<div class="clear"></div>';
		$ret .= '<div class="gdlr-space" style="margin-top: ' . $height . ';"></div>';
		return $ret;
	}	
	
	// quote shortcode
	add_shortcode('gdlr_quote', 'gdlr_quote_shortcode');
	function gdlr_quote_shortcode($atts, $content = null){
		extract( shortcode_atts(array('align' => 'center'), $atts) );	
		return '<blockquote class="gdlr-align-' . $align . '" >' . do_shortcode($content) . '</blockquote>';
	}	

	// dropcap shortcode
	add_shortcode('gdlr_dropcap', 'gdlr_dropcap_shortcode');
	function gdlr_dropcap_shortcode($atts, $content = null){
		extract( shortcode_atts(array('type' => 'normal', 'color'=>'', 'background'=>''), $atts) );	
		$style = '';
		if(!empty($background) || !empty($color)){
			$style .= 'style="';
			$style .= (!empty($color))? 'color: ' . $color . '; ': '';
			$style .= (!empty($background))? 'background-color: ' . $background . '; ': '';
			$style .= '"';
		}

		return '<div class="gdlr-dropcap gdlr-type-' . $type . '" ' . $style . ' >' . $content . '</div>';
	}	
	
	// icon shortcode
	add_shortcode('gdlr_icon', 'gdlr_icon_shortcode');
	function gdlr_icon_shortcode($atts, $content = null){
		extract( shortcode_atts(array('type' => '', 'color'=>'', 'size'=>'',
			'link'=>'', 'target'=>'_self'), $atts) );	
		
		$style = '';
		if(!empty($color) && !empty($size)){
			$style .= 'style="'; 
			$style .= !empty($color)? 'color: ' . $color . '; ': '';
			$style .= !empty($size)? 'font-size: ' . $size . '; ': '';
			$style .= '"';
		}
		
		$ret  = (empty($link))? '': '<a href="' . $link . '" target="' . $target . '" >';
		$ret .= '<i class="gdlr-icon fa ' . gdlr_fa_class($type) . '" ' . $style . ' ></i>';
		$ret .= (empty($link))? '': '</a>';
		return $ret;
	}		
	
	// notification shortcode
	add_shortcode('gdlr_notification', 'gdlr_notification_shortcode');
	function gdlr_notification_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('icon'=>'icon-flag', 'type' => 'color-background', 
			'background'=>'#99d15e', 'border'=>'#99d15e', 'color'=>'#ffffff'), $atts) );	
		
		$settings = array();
		$settings['color'] = $color;
		$settings['background'] = $background;
		$settings['border'] = $border;
		$settings['icon'] = gdlr_fa_class($icon);
		$settings['type'] = $type;
		$settings['content'] = $content;

		if( function_exists('gdlr_get_notification_item') ){
			return '<div class="gdlr-shortcode-wrapper">' . gdlr_get_notification_item($settings) . '</div>';
		}		
		return '';
	}	
	
	// box icon shortcode
	add_shortcode('gdlr_box_icon', 'gdlr_box_icon_shortcode');
	function gdlr_box_icon_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('icon'=>'icon-gears', 'icon_position'=>'top', 'icon_type'=>'normal',
			'icon_color'=>'', 'icon_background'=>'#91d549', 'title'=>'', ), $atts) );		
		
		$settings = array();
		$settings['icon'] = gdlr_fa_class($icon);
		$settings['icon-position'] = $icon_position;
		$settings['icon-type'] = $icon_type;
		$settings['icon-color'] = $icon_color;
		$settings['icon-background'] = $icon_background;
		$settings['title'] = $title;
		$settings['content'] = $content;
		
		if( function_exists('gdlr_get_box_icon_item') ){
			return '<div class="gdlr-shortcode-wrapper">' . gdlr_get_box_icon_item($settings) . '</div>';
		}
		return '';		
	}
	
	// styled box shortcode
	add_shortcode('gdlr_styled_box', 'gdlr_styled_box_shortcode');
	function gdlr_styled_box_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('background_type'=>'color', 'background_color'=>'#9ada55', 'corner_color'=>'', 
			'background_image'=>'', 'content_color'=>'#ffffff', 'height'=>'' ), $atts) );		
			
		$settings = array();
		$settings['type'] = $background_type;
		$settings['background-color'] = $background_color;
		if( !empty($corner_color) ){
			$settings['flip-corner'] = 'enable';
			$settings['corner-color'] = $corner_color;
		}
		$settings['background-image'] = $background_image;
		$settings['content-color'] = $content_color;
		$settings['content'] = $content;
		$settings['height'] = $height;

		if( function_exists('gdlr_get_styled_box_item') ){
			return '<div class="gdlr-shortcode-wrapper">' . gdlr_get_styled_box_item($settings) . '</div>';
		}
		return '';
	}
	
	// testimonial shortcode
	add_shortcode('gdlr_testimonial', 'gdlr_testimonial_shortcode');
	function gdlr_testimonial_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('columns' => '3', 'type'=>'static', 'style'=>'box-style', 
			'title'=>''), $atts) );
	
		global $gdlr_shortcode_tabs; $gdlr_shortcode_tabs = array();
		$settings = array();
		
		do_shortcode($content);
		$settings['testimonial-columns'] = $columns;
		$settings['testimonial-type'] = $type;
		$settings['testimonial-style'] = $style;
		$settings['testimonial'] = $gdlr_shortcode_tabs;
		$settings['title'] = $title;
		$settings['title-type'] = 'center';
		
		if( function_exists('gdlr_get_testimonial_item') ){
			return '<div class="gdlr-shortcode-wrapper">' . gdlr_get_testimonial_item($settings) . '</div>';
		}
		return '';
	}

	// personnel shortcode
	add_shortcode('gdlr_personnel', 'gdlr_personnel_shortcode');
	function gdlr_personnel_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('columns' => '3', 'type'=>'static', 'style'=>'box-style',
			'title'=>''), $atts) );
	
		global $gdlr_shortcode_tabs; $gdlr_shortcode_tabs = array();
		$settings = array();
		
		do_shortcode($content);
		$settings['personnel-columns'] = $columns;
		$settings['personnel-type'] = $type;
		$settings['personnel-style'] = $style;
		$settings['personnel'] = $gdlr_shortcode_tabs;
		$settings['title'] = $title;
		$settings['title-type'] = 'center';
		$settings['thumbnail-size'] = 'thumbnail';

		if( function_exists('gdlr_get_personnel_item') ){
			return '<div class="gdlr-shortcode-wrapper">' . gdlr_get_personnel_item($settings) . '</div>';
		}
		return '';
	}	
	
	// blog shortcode
	add_shortcode('gdlr_blog', 'gdlr_blog_shortcode');
	function gdlr_blog_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('category' => '', 'num_fetch'=>'8', 'num_excerpt'=>'25', 'pagination'=>'enable',
			'blog_style'=>'blog-full', 'thumbnail_size'=>'large', 'orderby'=>'date', 'order'=>'asc'), $atts) );

		$settings = array();
		
		do_shortcode($content);
		$settings['num-fetch'] = $num_fetch;
		$settings['category'] = $category;
		$settings['blog-style'] = $blog_style;
		$settings['num-excerpt'] = $num_excerpt;
		$settings['thumbnail-size'] = $thumbnail_size;
		$settings['orderby'] = $orderby;
		$settings['order'] = $order;
		$settings['pagination'] = $pagination;
		$settings['blog-layout'] = 'fitRows';

		if( function_exists('gdlr_get_blog_item') ){
			return '<div class="gdlr-shortcode-wrapper">' . gdlr_get_blog_item($settings) . '</div>';
		}
		return '';
	}		
	
	// portfolio shortcode
	add_shortcode('gdlr_portfolio', 'gdlr_portfolio_shortcode');
	function gdlr_portfolio_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('category' => '', 'num_fetch'=>'8', 'num_excerpt'=>'25', 
			'pagination'=>'enable', 'portfolio_style'=>'classic-portfolio', 'portfolio_size'=>'1/3', 
			'thumbnail_size'=>'large', 'orderby'=>'date', 'order'=>'asc'), $atts) );

		$settings = array();
		
		do_shortcode($content);
		$settings['num-fetch'] = $num_fetch;
		$settings['category'] = $category;
		$settings['portfolio-style'] = $portfolio_style;
		$settings['portfolio-size'] = $portfolio_size;
		$settings['num-excerpt'] = $num_excerpt;
		$settings['thumbnail-size'] = $thumbnail_size;
		$settings['orderby'] = $orderby;
		$settings['order'] = $order;
		$settings['pagination'] = $pagination;
		$settings['portfolio-layout'] = 'fitRows';
		$settings['portfolio-filter'] = 'disable';

		if( function_exists('gdlr_print_portfolio_item') ){
			return '<div class="gdlr-shortcode-wrapper">' . gdlr_print_portfolio_item($settings) . '</div>';
		}
		return '';
	}	
	
	// page shortcode
	add_shortcode('gdlr_page', 'gdlr_page_shortcode');
	function gdlr_page_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('category' => '', 'num_fetch'=>'8', 
			'pagination'=>'enable', 'page_style'=>'classic', 'item_size'=>'1/3', 
			'thumbnail_size'=>'large'), $atts) );

		$settings = array();
		
		do_shortcode($content);
		$settings['num-fetch'] = $num_fetch;
		$settings['category'] = $category;
		$settings['page-style'] = $page_style;
		$settings['item-size'] = $item_size;
		$settings['thumbnail-size'] = $thumbnail_size;
		$settings['pagination'] = $pagination;
		$settings['page-layout'] = 'fitRows';

		if( function_exists('gdlr_get_page_list_item') ){
			return '<div class="gdlr-shortcode-wrapper">' . gdlr_get_page_list_item($settings) . '</div>';
		}
		return '';
	}	
	
	// post slider shortcode
	add_shortcode('gdlr_post_slider', 'gdlr_post_slider_shortcode');
	function gdlr_post_slider_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('category' => '', 'num_fetch'=>'8', 'num_excerpt'=>'25', 
			'caption_position'=>'bottom', 'thumbnail_size'=>'large', 'orderby'=>'date', 'order'=>'asc'), $atts) );

		$settings = array();
		
		do_shortcode($content);
		$settings['num-fetch'] = $num_fetch;
		$settings['category'] = $category;
		$settings['num-excerpt'] = $num_excerpt;
		$settings['thumbnail-size'] = $thumbnail_size;
		$settings['orderby'] = $orderby;
		$settings['order'] = $order;
		$settings['caption-style'] = 'post-' . $caption_position . ' post-slider';

		if( function_exists('gdlr_get_post_slider_item') ){
			return '<div class="gdlr-shortcode-wrapper">' . gdlr_get_post_slider_item($settings) . '</div>';
		}
		return '';
	}	
	
	// code shortcode
	add_shortcode('gdlr_code', 'gdlr_code_shortcode');
	function gdlr_code_shortcode($atts, $content = null){
		extract( shortcode_atts(array('title' => '', 'active' => 'yes'), $atts) );	
		
		$icon_class = ($active == 'yes')? 'minus': 'plus';
		$active = ($active == 'yes')? 'active': '';
		$content = str_replace('[', '&#91;', htmlspecialchars($content));
		
		$ret  = '<div class="gdlr-code-item ' . $active . '">';
		$ret .= '<div class="gdlr-code-title"><i class="icon-' . $icon_class . '"></i>' . $title . '</div>';
		$ret .= '<div class="gdlr-code-content">' . $content . '</div>';
		$ret .= '</div>';
		
		return $ret;
	}	
	
	// video shortcode
	add_shortcode('gdlr_video', 'gdlr_video_shortcode');
	function gdlr_video_shortcode($atts){
		extract( shortcode_atts(array('url' => ''), $atts) );	
		
		if( function_exists('gdlr_get_video') ){
			return gdlr_get_video($url);
		}
		return '';
	}	
	
	remove_shortcode('gallery');
	add_shortcode('gallery', 'gdlr_gallery_shortcode');
	function gdlr_gallery_shortcode($atts){
		extract( shortcode_atts(array('columns' => '3', 'ids'=>'', 'link'=>'post', 'type'=>'grid',
			'caption' => 'yes', 'caption_position'=>'left', 'thumbnail_size'=>'thumbnail'), $atts) );	
		
		$settings = array();
		$settings['show-caption'] = $caption;
		$settings['thumbnail-size'] = $thumbnail_size;
		$settings['gallery-columns'] = $columns;
		
		$slides = array();
		$ids = explode(',', $ids);
		foreach( $ids as $slide_id ){
			$data = array();
			if($link == 'file'){
				$data['slide-link'] = 'current';
			}else if($link == 'post'){
				$data['new-tab'] = 'disable';
				$data['slide-link'] = 'url';
				$data['url'] = get_permalink($slide_id);
			}
			
			if($type == 'slider' && $caption == 'yes'){
				$attachment = gdlr_get_attachment_info($slide_id);
				$data['title'] = $attachment['title'];
				$data['caption'] = $attachment['caption'];
				$data['caption-position'] = $caption_position;
			}

			$slides[$slide_id] = $data;
		}

		if( function_exists('gdlr_get_slider_item') && $type == 'slider' ){
			$settings['slider-type'] = 'flexslider';
			return gdlr_get_flex_slider($slides, array('size'=> $thumbnail_size));
		}else if( function_exists('gdlr_get_gallery_thumbnail') && $type == 'thumbnail' ){
			$settings['slider'] = $slides;
			return '<div class="gdlr-shortcode-wrapper">' . gdlr_get_gallery_thumbnail($settings) . '</div>';
		}else if( function_exists('gdlr_get_gallery') ){
			$settings['slider'] = $slides;
			return '<div class="gdlr-shortcode-wrapper">' . gdlr_get_gallery($settings) . '</div>';
		}
		return '';		
	}
	
	// title
	add_shortcode('gdlr_title', 'gdlr_title_shortcode');
	function gdlr_title_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('title'=>'', 'align' => 'left', 'style' => 'divider'), $atts) );
		
		$align .= ($style == 'divider')? '-divider': '';
		
		$settings = array(
			'title-type'=>$align,
			'title'=>$title,
			'caption'=>$content
		);
		
		return '<div class="gdlr-shortcode-wrapper">' . gdlr_get_item_title($settings) . '</div>';
	}	
	
	// title
	add_shortcode('gdlr_social', 'gdlr_social_shortcode');
	function gdlr_social_shortcode( $atts, $content = null ){
		extract( shortcode_atts(array('type'=>''), $atts) );
		
		return '<a class="gdlr-social-shortcode" href="' . esc_attr($content) . '" ><img src="' . GDLR_PATH . '/images/social-icon/' . $type . '.png" alt="' . $type . '-icon" /></a>';
	}	
	
	// button
	add_shortcode('gdlr_lb_button', 'gdlr_lb_button_shortcode');
	function gdlr_lb_button_shortcode( $atts, $content = null ){
		global $gdlr_lb_button_shortcode; 
		$gdlr_lb_button_shortcode = empty($gdlr_lb_button_shortcode)? 1: $gdlr_lb_button_shortcode + 1;
	
		extract( shortcode_atts(array('title'=> 'Book Now', 'text_color'=>'', 'background'=>'', 'border'=>''), $atts) );
		
		$style  = empty($text_color)? '': 'color: ' . $text_color . ';';
		$style .= empty($background)? '': 'background: ' . $background . ';';
		$style .= empty($border)? '': 'border-color: ' . $border . ';';
		$style  = empty($style)? '': 'style="' . $style . '"';
		
		$ret  = '<a href="#contact-form-' . $gdlr_lb_button_shortcode . '" class="gdlr-button with-border" data-rel="fancybox" data-fancybox-type="inline" ' . $style . ' >' . $title . '</a>';
		$ret .= '<div id="contact-form-' . $gdlr_lb_button_shortcode . '" style="display: none;">';
		$ret .= do_shortcode($content);
		$ret .= '</div>';
		
		return $ret;
	}		
	
	
?>