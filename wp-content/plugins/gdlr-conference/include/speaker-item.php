<?php
	/*	
	*	Goodlayers Speaker File
	*/	

	function gdlr_get_speaker_thumbnail($size = 'thumbnail', $post_id = '', $post_option = array(), $permalink = false, $title = false){
		if( empty($post_id) ){
			$post_id = get_the_ID();
			$image_id = get_post_thumbnail_id();
			$title = empty($title)? '': get_the_title();
			$permalink = empty($permalink)? '': get_permalink();
		}else{
			$image_id = get_post_thumbnail_id($post_id);
			$title = empty($title)? '': get_the_title($post_id);
			$permalink = empty($permalink)? '': get_permalink($post_id);
		}
		
		if( !empty($image_id) ){
			if( is_single() && get_post_type() == 'speaker' ){
				return '<div class="gdlr-speaker-thumbnail">' . gdlr_get_image($image_id, $size, true) . '</div>';
			}else if(!empty($permalink) || !empty($title)){
				$ret  = '<div class="gdlr-speaker-thumbnail">';
				$ret .= '<div class="gdlr-speaker-thumbnail-inner">';
				$ret .= empty($permalink)? '': '<a href="' . $permalink . '" >';
				$ret .= gdlr_get_image($image_id, $size);
				$ret .= empty($permalink)? '': '</a>';
				$ret .= '</div>';
				
				if( !empty($title) ){
					$ret .= '<div class="gdlr-speaker-thumbnail-title">' . $title . '</div>';
				}
				$ret .= '</div>';
				return $ret;
			}else{
				$overlay_icon = '';
				for($i=1; $i<=3; $i++){
					if( !empty($post_option['icon-' . $i]) && !empty($post_option['icon-link-' . $i]) ){
						$overlay_icon .= '<a href="' . esc_attr($post_option['icon-link-' . $i]) . '" target="_blank">';
						$overlay_icon .= '<i class="fa ' . esc_attr($post_option['icon-' . $i]) . '" ></i>';
						$overlay_icon .= '</a>';
					}
				}
				
				$ret  = '<div class="gdlr-speaker-thumbnail">';
				$ret .= gdlr_get_image($image_id, $size);
				if( !empty($overlay_icon) ){
					$ret .= '<span class="gdlr-speaker-thumbnail-overlay" ></span>';
					$ret .= '<span class="gdlr-speaker-thumbnail-overlay-icon">' . $overlay_icon . '</span>';
				}else{
					$ret .= '<a class="gdlr-speaker-thumbnail-overlay-link" href="' . get_permalink((empty($post_id)? '': $post_id)) . '" >';
					$ret .= '<span class="gdlr-speaker-thumbnail-overlay" ></span>';
					$ret .= '</a>';
				}
				$ret .= '</div>';
				return $ret;
			}
		}
		return '';
	}
	
	function gdlr_get_speaker_info($array = array(), $option='', $wrapper = true){ 
		$ret = '';
		
		foreach($array as $post_info){	
			switch( $post_info ){
				case 'telephone':
					if(empty($option['telephone'])) break;
				
					$ret .= '<div class="speaker-info speaker-telephone">';
					$ret .= '<i class="fa ' . gdlr_fa_class('icon-phone') . '" ></i>';
					$ret .= $option['telephone'];						
					$ret .= '</div>';						
				
					break;	
				case 'email':
					if(empty($option['email'])) break;
				
					$ret .= '<div class="speaker-info speaker-email">';
					$ret .= '<i class="fa ' . gdlr_fa_class('icon-envelope') . '" ></i>';
					$ret .= $option['email'];						
					$ret .= '</div>';						

					break;	
				case 'website':
					if(empty($option['website'])) break;
				
					$ret .= '<div class="speaker-info speaker-website">';
					$ret .= '<i class="fa ' . gdlr_fa_class('icon-link') . '" ></i>';
					$ret .= '<a href="' . $option['website'] . '" target="_blank" >' . $option['website'] . '</a>';					
					$ret .= '</div>';						
				
					break;				
			}
		}

		if($wrapper && !empty($ret)){
			return '<div class="gdlr-speaker-info">' . $ret . '<div class="clear"></div></div>';
		}else if( !empty($ret) ){
			return $ret . '<div class="clear"></div>';
		}
		return '';
	}	
	
	// add action to check for speaker item
	add_action('gdlr_print_item_selector', 'gdlr_check_speaker_item', 10, 2);
	function gdlr_check_speaker_item( $type, $settings = array() ){
		if($type == 'speaker'){
			gdlr_print_speaker_item( $settings );
		}
	}
	
	// print speaker item
	function gdlr_print_speaker_item( $settings ){
		$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

		global $gdlr_spaces;
		$margin = (!empty($settings['margin-bottom']) && 
			$settings['margin-bottom'] != $gdlr_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
		$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';
		
		if( $settings['speaker-layout'] == 'carousel' ){
			$settings['carousel'] = true;
		}		

		// query posts section
		$args = array('post_type' => 'speaker', 'suppress_filters' => false);
		$args['posts_per_page'] = (empty($settings['num-fetch']))? '5': $settings['num-fetch'];
		$args['orderby'] = (empty($settings['orderby']))? 'post_date': $settings['orderby'];
		$args['order'] = (empty($settings['order']))? 'desc': $settings['order'];
		$args['paged'] = (get_query_var('paged'))? get_query_var('paged') : 1;
		if( !empty($settings['category']) ){ 
			$args['tax_query'] = array(
				array('terms'=>explode(',', $settings['category']), 'taxonomy'=>'speaker_category', 'field'=>'slug')
			);	
		}
		
		$query = new WP_Query( $args );		
		
		echo gdlr_get_item_title($settings);
		echo '<div class="speaker-item-wrapper" ' . $item_id . $margin_style . ' >'; 	
		echo '<div class="speaker-item-holder gdlr-speaker-type-' .  $settings['speaker-style'] . '">';
		gdlr_print_speaker($query, $settings['speaker-size'], $settings['thumbnail-size'], $settings['speaker-layout']);
		echo '<div class="clear"></div>';
		echo '</div>';

		// create pagination
		if($settings['pagination'] == 'enable'){
			echo gdlr_get_pagination($query->max_num_pages, $args['paged']);
		}	
		echo '</div>'; // speaker item wrapper
	}
	
	// print speaker
	function gdlr_print_speaker($query, $size, $thumbnail_size, $layout){
		if($layout == 'carousel'){ 
			return gdlr_print_carousel_speaker($query, $size, $thumbnail_size); 
		}
			
		$current_size = 0;
		while($query->have_posts()){ $query->the_post();
			$post_val = gdlr_decode_preventslashes(get_post_meta(get_the_ID(), 'post-option', true));
			$post_options = empty($post_val)? array(): json_decode($post_val, true);		
			
			if( $current_size % $size == 0 ){
				echo '<div class="clear"></div>';
			}		

			echo '<div class="' . gdlr_get_column_class('1/' . $size) . '">';
			echo '<div class="gdlr-item gdlr-speaker-item">';
			echo '<div class="gdlr-ux gdlr-speaker-item-ux">';
			echo gdlr_get_speaker_thumbnail($thumbnail_size, '', $post_options);
			
			echo '<div class="gdlr-speaker-item-content">';
			echo '<h3 class="gdlr-speaker-item-title gdlr-skin-title"><a href="' . get_permalink() . '" >' . get_the_title() . '</a></h3>';
			if( !empty($post_options['page-caption']) ) {
				echo '<div class="gdlr-speaker-item-position gdlr-info-font gdlr-skin-info" >' . $post_options['page-caption'] . '</div>';
			}
			echo '</div>'; // gdlr-speaker-item-content
			
			echo '</div>'; // gdlr-ux
			echo '</div>'; // gdlr-item
			echo '</div>'; // gdlr-column-class
			$current_size ++;		
		}
		wp_reset_postdata();
	}
	function gdlr_print_carousel_speaker($query, $size, $thumbnail_size){
		
		echo '<div class="gdlr-item gdlr-speaker-carousel-wrapper">';
		echo '<div class="flexslider" data-type="carousel" data-nav-container="speaker-item-wrapper" data-columns="' . $size . '" >';	
		echo '<ul class="slides" >';		
		while($query->have_posts()){ $query->the_post();
			$post_val = gdlr_decode_preventslashes(get_post_meta(get_the_ID(), 'post-option', true));
			$post_options = empty($post_val)? array(): json_decode($post_val, true);			

			echo '<li class="gdlr-item gdlr-speaker-item">';
			echo gdlr_get_speaker_thumbnail($thumbnail_size, '', $post_options);
			
			echo '<div class="gdlr-speaker-item-content">';
			echo '<h3 class="gdlr-speaker-item-title gdlr-skin-title"><a href="' . get_permalink() . '" >' . get_the_title() . '</a></h3>';
			if( !empty($post_options['page-caption']) ) {
				echo '<div class="gdlr-speaker-item-position gdlr-info-font gdlr-skin-info" >' . $post_options['page-caption'] . '</div>';
			}
			echo '</div>'; // gdlr-speaker-item-content
			
			echo '</li>'; // gdlr-item
		}
		echo '</ul>'; 
		echo '</div>'; // flexslider
		echo '</div>'; // gdlr-item
		wp_reset_postdata();
	}
	
?>