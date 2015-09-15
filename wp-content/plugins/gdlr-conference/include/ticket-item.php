<?php
	/*	
	*	Goodlayers Ticket File
	*/	
	
	// add action to check for ticket item
	add_action('gdlr_print_item_selector', 'gdlr_check_ticket_item', 10, 2);
	function gdlr_check_ticket_item( $type, $settings = array() ){
		if($type == 'ticket'){
			gdlr_print_ticket_item( $settings );
		}
	}
	
	// apply money format to number_format
	function gdlr_ticket_money_format( $amount, $format = '' ){
		if( empty($format) ){
			global $theme_option;
			$format = empty($theme_option['ticket-money-format'])? '$NUMBER': $theme_option['ticket-money-format'];
		}
		$amount = number_format_i18n($amount, 2);
		return str_replace('NUMBER', $amount, $format);	
	}
	
	// print ticket item
	function gdlr_print_ticket_item( $settings ){
		$item_id = empty($settings['page-item-id'])? '': ' id="' . $settings['page-item-id'] . '" ';

		global $gdlr_spaces;
		$margin = (!empty($settings['margin-bottom']) && 
			$settings['margin-bottom'] != $gdlr_spaces['bottom-item'])? 'margin-bottom: ' . $settings['margin-bottom'] . ';': '';
		$margin_style = (!empty($margin))? ' style="' . $margin . '" ': '';		

		// query posts section
		$args = array('post_type' => 'ticket', 'suppress_filters' => false);
		$args['posts_per_page'] = (empty($settings['num-fetch']))? '999': $settings['num-fetch'];
		$args['orderby'] = (empty($settings['orderby']))? 'post_date': $settings['orderby'];
		$args['order'] = (empty($settings['order']))? 'desc': $settings['order'];
		$args['paged'] = 1;
		if( !empty($settings['category']) ){ 
			$args['tax_query'] = array(
				array('terms'=>explode(',', $settings['category']), 'taxonomy'=>'ticket_category', 'field'=>'slug')
			);	
		}
		
		$query = new WP_Query( $args );		
		
		echo gdlr_get_item_title($settings);
		echo '<div class="ticket-item-wrapper" ' . $item_id . $margin_style . ' >'; 	
		echo '<div class="ticket-item-holder">';
		
		$current_size = 0;
		while($query->have_posts()){ $query->the_post();
			$post_val = gdlr_decode_preventslashes(get_post_meta(get_the_ID(), 'post-option', true));
			$post_options = empty($post_val)? array(): json_decode($post_val, true);		
			
			if( $current_size % $settings['ticket-size'] == 0 ){
				echo '<div class="clear"></div>';
			}		

			echo '<div class="' . gdlr_get_column_class('1/' . $settings['ticket-size']) . '">';
			echo '<div class="gdlr-item gdlr-ticket-item">';
			echo '<div class="gdlr-ticket-head">';
			echo '<div class="gdlr-ticket-head-title">' . get_the_title() . '</div>';
			echo '<div class="gdlr-ticket-head-price">' . (empty($post_options['price'])? __('Free', 'gdlr-conference'): gdlr_ticket_money_format($post_options['price'])) . '</div>';

			if( !empty($post_options['featured-ticket']) && $post_options['featured-ticket'] == 'yes' ){
				echo '<div class="gdlr-ticket-head-featured gdlr-info-font">' . __('Featured!', 'gdlr-conference') . '</div>';
			}
			echo '</div>'; // gdlr-ticket-head
			
			echo '<div class="gdlr-ticket-content gdlr-info-font">';
			the_content();
			echo '</div>'; // gdlr-ticket-content

			if( !empty($post_options['button-link']) ){
				echo '<a class="gdlr-ticket-button" href="' . esc_attr($post_options['button-link']) . '">' . __('Book Now', 'gdlr-conference') . '</a>';
			}else if(empty($post_options['price']) && $post_options['price'] == '-1'){
				echo '<a class="gdlr-ticket-button gdlr-sold-out" href="#">' . __('Sold Out', 'gdlr-conference') . '</a>';
			}else{
				global $ticket_id; $ticket_id = empty($ticket_id)? 1: $ticket_id + 1;
				echo '<div class="gdlr-lightbox-form" id="gdlr-form-' . $ticket_id . '" >' . gdlr_paypal_form() . '</div>';
				echo '<a class="gdlr-ticket-button" href="#gdlr-form-' . $ticket_id . '" data-rel="fancybox" data-fancybox-type="inline" >' . __('Book Now', 'gdlr-conference') . '</a>';
			}
			echo '</div>'; // gdlr-item
			echo '</div>'; // gdlr-column-class
			$current_size ++;		
		}
		wp_reset_postdata();
		
		echo '<div class="clear"></div>';
		echo '</div>';
		echo '</div>'; // ticket item wrapper
	}
	
?>