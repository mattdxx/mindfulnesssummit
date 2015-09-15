<?php
	/*	
	*	Goodlayers Ticket Option File
	*/	
	 
	// create the ticket post type
	add_action( 'init', 'gdlr_conference_create_ticket' );
	function gdlr_conference_create_ticket() {
		register_post_type( 'ticket',
			array(
				'labels' => array(
					'name'               => __('Tickets', 'gdlr-conference'),
					'singular_name'      => __('Ticket', 'gdlr-conference'),
					'add_new'            => __('Add New', 'gdlr-conference'),
					'add_new_item'       => __('Add New ticket', 'gdlr-conference'),
					'edit_item'          => __('Edit ticket', 'gdlr-conference'),
					'new_item'           => __('New ticket', 'gdlr-conference'),
					'all_items'          => __('All tickets', 'gdlr-conference'),
					'view_item'          => __('View ticket', 'gdlr-conference'),
					'search_items'       => __('Search tickets', 'gdlr-conference'),
					'not_found'          => __('No tickets found', 'gdlr-conference'),
					'not_found_in_trash' => __('No tickets found in Trash', 'gdlr-conference'),
					'parent_item_colon'  => '',
					'menu_name'          => __('Tickets', 'gdlr-conference')
				),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				//'rewrite'            => array( 'slug' => 'ticket'  ),
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => 5,
				'supports'           => array( 'title', 'editor', 'author', 'custom-fields' )
			)
		);	
		
		// create ticket categories
		register_taxonomy(
			'ticket_category', array("ticket"), array(
				'hierarchical' => true,
				'show_admin_column' => true,
				'label' => __('Ticket Categories', 'gdlr-conference'), 
				'singular_label' => __('Ticket Category', 'gdlr-conference'), 
				'rewrite' => array( 'slug' => 'ticket_category'  )));
		register_taxonomy_for_object_type('ticket_category', 'ticket');
		
		add_filter('single_template', 'gdlr_conference_register_ticket_template');
	}
	
	// register single ticket template
	function gdlr_conference_register_ticket_template($template) {
		global $wpdb, $post, $current_user;

		if( $post->post_type == 'ticket' ){
			if( is_user_logged_in() ){
				$template = dirname(dirname( __FILE__ )) . '/single-ticket.php';
			}else{
				$template = GDLR_LOCAL_PATH . '/404.php';
			}
		}
		
		return $template;	
	}
	
	// add a speaker option to speaker page
	if( is_admin() ){ add_action('after_setup_theme', 'gdlr_create_ticket_options'); }
	if( !function_exists('gdlr_create_ticket_options') ){
	
		function gdlr_create_ticket_options(){
			global $gdlr_sidebar_controller;
			
			if( !class_exists('gdlr_page_options') ) return;
			new gdlr_page_options( 
				
				// page option attribute
				array(
					'post_type' => array('ticket'),
					'meta_title' => __('Goodlayers Ticket Option', 'gdlr-conference'),
					'meta_slug' => 'goodlayers-page-option',
					'option_name' => 'post-option',
					'position' => 'normal',
					'priority' => 'high',
				),
					  
				// page option settings
				array(
					'ticket-info' => array(
						'title' => __('Ticket Info', 'gdlr-conference'),
						'options' => array(
							'price' => array(
								'title' => __('Ticket Price' , 'gdlr-conference'),
								'type' => 'text', 
								'description' => __('Please only fill number here. Leaving this field blank for free tickets.', 'gdlr-conference')
							),	
							'button-link' => array(
								'title' => __('Book Button Link' , 'gdlr-conference'),
								'type' => 'text',
								'description' => __('Leaving this field blank will display the contact form when button is clicked.', 'gdlr-conference')
							),
							'featured-ticket' => array(
								'title' => __('Featured Ticket' , 'gdlr-conference'),
								'type' => 'combobox',
								'options' => array(
									'no' => __('No', 'gdlr-conference'),
									'yes' => __('Yes', 'gdlr-conference')
								)
							),	
						)
					),	
				)
			);
			
		}
	}	
	
	// add the function to collaborate with page builder
	add_filter('gdlr_page_builder_option', 'gdlr_register_ticket_item');
	function gdlr_register_ticket_item( $page_builder = array() ){
		global $gdlr_spaces;
	
		$page_builder['content-item']['options']['ticket'] = array(
			'title'=> __('Ticket', 'gdlr-conference'), 
			'type'=>'item',
			'options'=>array_merge(gdlr_page_builder_title_option(true), array(					
				'category'=> array(
					'title'=> __('Category' ,'gdlr-conference'),
					'type'=> 'multi-combobox',
					'options'=> gdlr_get_term_list('ticket_category'),
					'description'=> __('You can use Ctrl/Command button to select multiple categories or remove the selected category. <br><br> Leave this field blank to select all categories.', 'gdlr-conference')
				),		
				'ticket-size'=> array(
					'title'=> __('Ticket Item Size' ,'gdlr-conference'),
					'type'=> 'combobox',
					'options'=> array(
						'4'=>'1/4',
						'3'=>'1/3',
						'2'=>'1/2',
						'1'=>'1/1'
					),
					'default'=>'3'
				),					
				'orderby'=> array(
					'title'=> __('Order By' ,'gdlr-conference'),
					'type'=> 'combobox',
					'options'=> array(
						'date' => __('Publish Date', 'gdlr-conference'), 
						'title' => __('Title', 'gdlr-conference'), 
						'rand' => __('Random', 'gdlr-conference'), 
					)
				),
				'order'=> array(
					'title'=> __('Order' ,'gdlr-conference'),
					'type'=> 'combobox',
					'options'=> array(
						'desc'=>__('Descending Order', 'gdlr-conference'), 
						'asc'=> __('Ascending Order', 'gdlr-conference'), 
					)
				),			
				'margin-bottom' => array(
					'title' => __('Margin Bottom', 'gdlr-conference'),
					'type' => 'text',
					'default' => $gdlr_spaces['bottom-item'],
					'description' => __('Spaces after ending of this item', 'gdlr-conference')
				),				
			))
		);
		return $page_builder;
	}	

?>