<?php
	/*	
	*	Goodlayers Session File
	*/	
	
	function gdlr_get_session_speaker_list($speakers){
		$speaker_posts = array();
		
		if( !empty($speakers) ){
			foreach( $speakers as $speaker_name ){
				$speaker = get_posts(array('post_type'=>'speaker', 'name'=>$speaker_name, 'showposts'=>1));
				$speaker_posts[] = $speaker[0];
			}
		}
		return $speaker_posts;
	}
	
	function gdlr_get_session_thumbnail($size = 'thumbnail'){
		$image_id = get_post_thumbnail_id();
		if( !empty($image_id) ){
			if( is_single() ){
				return '<div class="gdlr-session-thumbnail">' . gdlr_get_image($image_id, $size, true) . '</div>';
			}else{
				$ret  = '<div class="gdlr-session-thumbnail"><a href="' . get_permalink() . '" >';
				$ret .= gdlr_get_image($image_id, $size);
				$ret .= '</a></div>';
				return $ret;
			}
		}
		return '';
	}	
	
	function gdlr_session_time_conversion($time){
		global $theme_option;
		
		if( !empty($theme_option['time-format']) ){
			preg_match_all( "/(2[0-3]|[0-1]?[0-9]):[0-5]?[0-9]/", $time, $matches);
			
			foreach($matches[0] as $time24){
				$time12 = date("g:i a", strtotime($time24));
				$time = str_replace($time24, $time12, $time);
			}
		}
		return $time;
	}
	
	function gdlr_get_session_info($array = array(), $option=array(), $speakers=array(), $wrapper = true){ 
		// font awesome icon
		global $theme_option;
		if( !empty($theme_option['new-fontawesome']) && $theme_option['new-fontawesome'] == 'enable' ){
			$icon_class = array('time'=>'fa-clock-o', 'document'=>'fa-file-pdf-o');
		}else{
			$icon_class = array('time'=>'icon-time', 'document'=>'icon-download');
		}	
	
		$ret = '';
		
		foreach($array as $post_info){	
			switch( $post_info ){
				case 'date':
					if(empty($option['session-time'])) break;
					$session_date = strtotime($option['session-date'] . ' 00:00:00');
			
					$ret .= '<div class="session-info session-time">';
					$ret .= '<i class="fa ' . gdlr_fa_class('icon-calendar') . '" ></i>';
					$ret .= date_i18n($theme_option['date-format'], $session_date);						
					$ret .= '</div>';						
				
					break;				
				case 'time':
					if(empty($option['session-time'])) break;
				
					$ret .= '<div class="session-info session-time">';
					$ret .= '<i class="fa ' . $icon_class['time'] . '" ></i>';
					$ret .= gdlr_session_time_conversion($option['session-time']);						
					$ret .= '</div>';						
				
					break;	
				case 'location':
					if(empty($option['location'])) break;
				
					$ret .= '<div class="session-info session-location">';
					$ret .= '<i class="fa ' . gdlr_fa_class('icon-location-arrow') . '" ></i>';
					$ret .= $option['location'];						
					$ret .= '</div>';						

					break;	
				case 'speaker':
					if(empty($speakers)) break;
				
					$ret .= '<div class="session-info session-speaker">';
					$ret .= '<div class="session-speaker-inner">';
					$ret .= '<i class="fa ' . gdlr_fa_class('icon-user') . '" ></i>';
					$ret .= '<div class="session-speaker-list">';
					foreach($speakers as $speaker){
						$ret .= '<div class="session-speaker-list-item">';
						$ret .= '<a href="' . get_permalink($speaker) . '" >';
						$ret .= get_the_title($speaker);						
						$ret .= '</a>';	
						$ret .= '</div>';
					}
					$ret .= '</div>'; // session-speaker-list				
					$ret .= '</div>'; // session-speaker-inner					
					$ret .= '</div>'; 						

					break;		
				case 'document':
					if(empty($option['document-link'])) break;
				
					$ret .= '<div class="session-info session-document">';
					$ret .= '<a href="' . $option['document-link'] . '" target="_blank" >';
					$ret .= '<i class="fa ' . $icon_class['document'] . '" ></i>';
					$ret .= __('Download Document' ,'gdlr-conference');
					$ret .= '</a>';					
					$ret .= '</div>';						
				
					break;				
			}
		}

		if($wrapper && !empty($ret)){
			return '<div class="gdlr-session-info">' . $ret . '<div class="clear"></div></div>';
		}else if( !empty($ret) ){
			return $ret . '<div class="clear"></div>';
		}
		return '';
	}	

	// add action to check for speaker item
	add_action('gdlr_print_item_selector', 'gdlr_check_session_item', 10, 2);
	function gdlr_check_session_item( $type, $settings = array() ){
		if($type == 'session'){
			gdlr_print_session_item( $settings );
		}else if($type == 'session-counter'){
			gdlr_print_session_counter_item( $settings );
		}
	}

	// print session counter item
	function gdlr_print_session_counter_item( $settings ){
		$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

		global $gdlr_spaces;
		$margin = (!empty($settings['margin-bottom']) && 
			$settings['margin-bottom'] != $gdlr_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
		$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
		
		$session_post = get_posts( array('post_type'=>'session', 'name'=>$settings['select-session'], 'posts_per_page'=>1) );
		$session_time = strtotime(get_post_meta($session_post[0]->ID, 'session-date', true));	
		$current_date = strtotime(current_time('mysql'));
		
		
		if( $session_time > $current_date ){
			$total_time = $session_time - $current_date;
			$day = intval($total_time / 86400);
			
			$total_time = $total_time % 86400;
			$hrs = intval($total_time / 3600);
			
			$total_time = $total_time % 3600;
			$min = intval($total_time / 60);
			$sec = $total_time % 60;
			
			echo '<div class="gdlr-session-counter-item gdlr-item" ' . $item_id . $margin_style . ' >';
			echo '<div class="session-counter-block gdlr-block-day" >';
			echo '<span class="gdlr-time gdlr-day">' . $day . '</span>';
			echo '<span class="gdlr-unit">' . __('Days', 'gdlr-conference') . '</span>';
			echo '</div>';
			
			echo '<div class="session-counter-block gdlr-block-hrs" >';
			echo '<span class="gdlr-time gdlr-hrs">' . $hrs . '</span>';
			echo '<span class="gdlr-unit">' . __('Hours', 'gdlr-conference') . '</span>';
			echo '</div>';

			echo '<div class="session-counter-block gdlr-block-min" >';
			echo '<span class="gdlr-time gdlr-min">' . $min . '</span>';
			echo '<span class="gdlr-unit">' . __('Mins', 'gdlr-conference') . '</span>';
			echo '</div>';

			echo '<div class="session-counter-block gdlr-block-sec" >';
			echo '<span class="gdlr-time gdlr-sec">' . $sec . '</span>';
			echo '<span class="gdlr-unit">' . __('Secs', 'gdlr-conference') . '</span>';
			echo '</div>';
			echo '<div class="clear"></div>';
 			echo '</div>';			
		}
	}
	
	// print session counter item
	function gdlr_print_session_item( $settings ){
		$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

		global $gdlr_spaces;
		$margin = (!empty($settings['margin-bottom']) && 
			$settings['margin-bottom'] != $gdlr_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
		$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';

		// query posts section
		$args = array('post_type' => 'session', 'suppress_filters' => false);
		$args['posts_per_page'] = '9999';
		$args['meta_key'] = 'session-date';
		$args['orderby'] = 'meta_value';
		$args['order'] = 'asc';
		if( !empty($settings['category']) ){ 
			$args['tax_query'] = array(
				array('terms'=>explode(',', $settings['category']), 'taxonomy'=>'session_category', 'field'=>'slug')
			);	
		}
		$query = new WP_Query( $args );		
		
		// set the excerpt length
		if( !empty($settings['num-excerpt']) ){
			global $gdlr_excerpt_length; $gdlr_excerpt_length = $settings['num-excerpt'];
			add_filter('excerpt_length', 'gdlr_set_excerpt_length');
		} 		
		
		echo gdlr_get_item_title($settings);
		echo '<div class="session-item-wrapper" ' . $item_id . $margin_style . ' >'; 	
		if( $settings['session-style'] == 'full' ){
			gdlr_print_full_session($query);
		}else if( $settings['session-style'] == 'tab' ){
			gdlr_print_tab_session($query);
		}else if( $settings['session-style'] == 'small' ){
			gdlr_print_small_session($query);
		}
		
		remove_filter('excerpt_length', 'gdlr_set_excerpt_length');
		
		echo '<div class="clear"></div>';
		echo '</div>'; // speaker item wrapper
	}	
	
	// print full session item
	function gdlr_print_full_session($query){
		global $theme_option;
		if( !empty($theme_option['new-fontawesome']) && $theme_option['new-fontawesome'] == 'enable' ){
			$icon_class = array('time'=>'fa-clock-o');
		}else{
			$icon_class = array('time'=>'icon-time');
		}	
		
		$current_session_day = 0;
		$current_session_date = '';
		
		echo '<div class="gdlr-session-item gdlr-full-session-item gdlr-item" >';
		while($query->have_posts()){ $query->the_post();
			$gdlr_post_option = gdlr_decode_preventslashes(get_post_meta(get_the_ID(), 'post-option', true));
			$gdlr_post_option = json_decode($gdlr_post_option, true);
			$gdlr_speakers = gdlr_get_session_speaker_list($gdlr_post_option['session-speaker']);
			
			$session_date_o = strtotime(get_post_meta(get_the_ID(), 'session-date', true));
			$session_date = date_i18n($theme_option['date-format'], $session_date_o);
			
			if( $current_session_date != $session_date ){
				$current_session_day++;
				$current_session_date = $session_date;
				
				echo '<div class="gdlr-session-item-head ' . (($current_session_day == 1)? 'gdlr-first': '') . '">';
				echo '<div class="gdlr-session-item-head-info gdlr-active">';
				echo '<div class="gdlr-session-head-day">' . sprintf(__('Day %d', 'gdlr-conference'), $current_session_day) . '</div>';
				echo '<div class="gdlr-session-head-date">' . $current_session_date . '</div>';
				echo '</div>';
				echo '</div>';
			}
			
			echo '<div class="gdlr-session-item-content-wrapper">';
			echo '<div class="gdlr-session-item-divider"></div>';
			if( !empty($gdlr_post_option['session-type']) && $gdlr_post_option['session-type'] == 'break' ){
				echo '<div class="session-break-content">';
				echo '<div class="session-break-info">';
				echo '<i class="fa ' . $icon_class['time'] . '" ></i>';
				echo $gdlr_post_option['session-time'];						
				echo '</div>';	
				echo '<h3 class="gdlr-session-break-title">' . get_the_title() . '</h3>'; 
				echo '</div>';
			}else{			
				echo '<div class="gdlr-session-item-content-info">';
				echo gdlr_get_session_info(array('time', 'location', 'speaker'), $gdlr_post_option, $gdlr_speakers); 
				echo '</div>';
				
				echo '<div class="gdlr-session-item-content" >';
				echo '<h3 class="gdlr-session-item-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>'; 
				echo '<div class="gdlr-session-item-excerpt">' . get_the_excerpt() . '</div>';
				
				if( !empty($gdlr_speakers) ){
					echo '<div class="gdlr-session-thumbnail-wrapper">';
					foreach( $gdlr_speakers as $speaker ){
						echo gdlr_get_speaker_thumbnail('thumbnail', $speaker->ID, array(), true, true);
					}
					echo '</div>';
				}
				
				echo '</div>';
			}
			echo '<div class="clear"></div>';
			echo '</div>'; // session-item-content-wrapper
		}
		echo '</div>'; // gdlr-full-session-item
		
		wp_reset_postdata();
		
	}
	
	// print tab session item
	function gdlr_print_tab_session($query){
		global $theme_option;
		if( !empty($theme_option['new-fontawesome']) && $theme_option['new-fontawesome'] == 'enable' ){
			$icon_class = array('time'=>'fa-clock-o');
		}else{
			$icon_class = array('time'=>'icon-time');
		}	
		
		echo '<div class="gdlr-session-item gdlr-tab-session-item gdlr-item" >';
		echo '<div class="gdlr-session-item-head"  >';
		
		$current_session_day = 0;
		$current_session_date = '';
		while($query->have_posts()){ $query->the_post();
			$session_date_o = strtotime(get_post_meta(get_the_ID(), 'session-date', true));
			$session_date = date_i18n($theme_option['date-format'], $session_date_o);

			if( $current_session_date != $session_date ){
				$current_session_day++;
				$current_session_date = $session_date;

				echo '<div class="gdlr-session-item-head-info ' . (($current_session_day == 1)? 'gdlr-active': '') . '" data-tab="gdlr-tab-' . $current_session_day . '">';
				echo '<div class="gdlr-session-head-day">' . sprintf(__('Day %d', 'gdlr-conference'), $current_session_day) . '</div>';
				echo '<div class="gdlr-session-head-date">' . $current_session_date . '</div>';
				echo '</div>';
			}
		}
		echo '<div class="clear"></div>';
		echo '</div>'; // session-item-head
		rewind_posts();
		
		$current_session_day = 0;
		$current_session_date = '';
		while($query->have_posts()){ $query->the_post();
			$gdlr_post_option = gdlr_decode_preventslashes(get_post_meta(get_the_ID(), 'post-option', true));
			$gdlr_post_option = json_decode($gdlr_post_option, true);
			$gdlr_speakers = gdlr_get_session_speaker_list($gdlr_post_option['session-speaker']);		

			$session_date_o = strtotime(get_post_meta(get_the_ID(), 'session-date', true));
			$session_date = date_i18n($theme_option['date-format'], $session_date_o);
		
			if( $current_session_date != $session_date ){
				$current_session_day++;
				$current_session_date = $session_date;
				
				echo ($current_session_day == 1)? '': '</div>'; // gdlr-session-item-tab-content 
				echo '<div class="gdlr-session-item-tab-content gdlr-tab-' . $current_session_day . ' ' . (($current_session_day == 1)? 'gdlr-active': '') . '">';
			}		
		
			echo '<div class="gdlr-session-item-content-wrapper">';
			echo '<div class="gdlr-session-item-divider"></div>';
			if( !empty($gdlr_post_option['session-type']) && $gdlr_post_option['session-type'] == 'break' ){
				echo '<div class="session-break-content">';
				echo '<div class="session-break-info">';
				echo '<i class="fa ' . $icon_class['time'] . '" ></i>';
				echo $gdlr_post_option['session-time'];						
				echo '</div>';	
				echo '<h3 class="gdlr-session-break-title">' . get_the_title() . '</h3>'; 
				echo '</div>';
			}else{
				echo '<div class="gdlr-session-item-content-info">';
				echo gdlr_get_session_info(array('time', 'location', 'speaker'), $gdlr_post_option, $gdlr_speakers); 
				echo '</div>';
				
				echo '<div class="gdlr-session-item-content" >';
				echo '<h3 class="gdlr-session-item-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>'; 
				echo '<div class="gdlr-session-item-excerpt">' . get_the_excerpt() . '</div>';
				
				if( !empty($gdlr_speakers) ){
					echo '<div class="gdlr-session-thumbnail-wrapper">';
					foreach( $gdlr_speakers as $speaker ){
						echo gdlr_get_speaker_thumbnail('thumbnail', $speaker->ID, array(), true, true);
					}
					echo '</div>';
				}
				
				echo '</div>';
			}
			echo '<div class="clear"></div>';
			echo '</div>'; // session-item-content-wrapper
			
		}
		echo '</div>'; // gdlr-session-item-tab-content 
		echo '</div>'; // gdlr-tab-session-item
		
		wp_reset_postdata();
		
	}

	// print small session item
	function gdlr_print_small_session($query){
		global $theme_option;
		if( !empty($theme_option['new-fontawesome']) && $theme_option['new-fontawesome'] == 'enable' ){
			$icon_class = array('time'=>'fa-clock-o');
		}else{
			$icon_class = array('time'=>'icon-time');
		}	
		
		echo '<div class="gdlr-session-item gdlr-small-session-item gdlr-item" >';
		echo '<div class="gdlr-session-item-head" >';
		
		$current_session_day = 0;
		$current_session_date = '';
		while($query->have_posts()){ $query->the_post();
			$session_date_o = strtotime(get_post_meta(get_the_ID(), 'session-date', true));
			$session_date = date_i18n($theme_option['date-format'], $session_date_o);

			if( $current_session_date != $session_date ){
				$current_session_day++;
				$current_session_date = $session_date;

				echo '<div class="gdlr-session-item-head-info ' . (($current_session_day == 1)? 'gdlr-active': '') . '" data-tab="gdlr-tab-' . $current_session_day . '">';
				echo '<div class="gdlr-session-head-day">' . sprintf(__('Day %d', 'gdlr-conference'), $current_session_day) . '</div>';
				echo '<div class="gdlr-session-head-date">' . $current_session_date . '</div>';
				echo '</div>';
			}
		}
		echo '<div class="clear"></div>';
		echo '</div>'; // session-item-head
		rewind_posts();
		
		$current_session_day = 0;
		$current_session_date = '';
		while($query->have_posts()){ $query->the_post();
			$gdlr_post_option = gdlr_decode_preventslashes(get_post_meta(get_the_ID(), 'post-option', true));
			$gdlr_post_option = json_decode($gdlr_post_option, true);
			$gdlr_speakers = gdlr_get_session_speaker_list($gdlr_post_option['session-speaker']);		

			$session_date_o = strtotime(get_post_meta(get_the_ID(), 'session-date', true));
			$session_date = date_i18n($theme_option['date-format'], $session_date_o);
		
			if( $current_session_date != $session_date ){
				$current_session_day++;
				$current_session_date = $session_date;
				
				echo ($current_session_day == 1)? '': '</div>'; // gdlr-session-item-tab-content 
				echo '<div class="gdlr-session-item-tab-content gdlr-tab-' . $current_session_day . ' ' . (($current_session_day == 1)? 'gdlr-active': '') . '">';
			}		
		
			echo '<div class="gdlr-session-item-content-wrapper">';
			echo '<div class="gdlr-session-item-divider"></div>';
			
			if( !empty($gdlr_post_option['session-type']) && $gdlr_post_option['session-type'] == 'break' ){
				echo '<div class="session-break-content">';
				echo '<div class="session-break-info">';
				echo '<i class="fa ' . $icon_class['time'] . '" ></i>';
				echo $gdlr_post_option['session-time'];						
				echo '</div>';	
				echo '<h3 class="gdlr-session-break-title">' . get_the_title() . '</h3>'; 
				echo '</div>';
			}else{
				echo '<div class="gdlr-session-item-content" >';
				if( !empty($gdlr_speakers) ){
					echo '<div class="gdlr-session-thumbnail-wrapper">';
					echo gdlr_get_speaker_thumbnail('thumbnail', $gdlr_speakers[0]->ID, array(), true, true);
					echo '</div>';
				}			
				
				echo '<div class="gdlr-session-item-content-inner" >';
				echo '<h3 class="gdlr-session-item-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>'; 
				echo '<div class="gdlr-session-item-content-info">';
				echo gdlr_get_session_info(array('time', 'location', 'speaker'), $gdlr_post_option, $gdlr_speakers); 
				echo '</div>'; // session-item-content-info		 
				echo '</div>'; // session-item-content-inner
				echo '</div>'; // session-item-content
			}
			
			echo '<div class="clear"></div>';
			echo '</div>'; // session-item-content-wrapper
			
		}
		echo '</div>'; // gdlr-session-item-tab-content 
		echo '</div>'; // gdlr-tab-session-item
		
		wp_reset_postdata();
		
	}	
	
?>