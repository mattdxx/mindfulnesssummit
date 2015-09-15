<?php
	/*	
	*	Goodlayers Session Option File
	*/	

	// create the session post type
	add_action( 'init', 'gdlr_conference_create_session' );
	function gdlr_conference_create_session() {
		register_post_type( 'session',
			array(
				'labels' => array(
					'name'               => __('Sessions', 'gdlr-conference'),
					'singular_name'      => __('Session', 'gdlr-conference'),
					'add_new'            => __('Add New', 'gdlr-conference'),
					'add_new_item'       => __('Add New session', 'gdlr-conference'),
					'edit_item'          => __('Edit session', 'gdlr-conference'),
					'new_item'           => __('New session', 'gdlr-conference'),
					'all_items'          => __('All sessions', 'gdlr-conference'),
					'view_item'          => __('View session', 'gdlr-conference'),
					'search_items'       => __('Search sessions', 'gdlr-conference'),
					'not_found'          => __('No sessions found', 'gdlr-conference'),
					'not_found_in_trash' => __('No sessions found in Trash', 'gdlr-conference'),
					'parent_item_colon'  => '',
					'menu_name'          => __('Sessions', 'gdlr-conference')
				),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				//'rewrite'            => array( 'slug' => 'session'  ),
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => 5,
				'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields' )
			)
		);	

		register_taxonomy(
			'session_category', array("session"), array(
				'hierarchical' => true,
				'show_admin_column' => true,
				'label' => __('Session Categories', 'gdlr-conference'), 
				'singular_label' => __('Session Category', 'gdlr-conference'), 
				'rewrite' => array( 'slug' => 'session_category'  )));
		register_taxonomy_for_object_type('session_category', 'session');		
		
		add_filter('single_template', 'gdlr_conference_register_session_template');
	}
	
	// register single session template
	function gdlr_conference_register_session_template($template) {
		global $wpdb, $post, $current_user;

		if( $post->post_type == 'session' ){
			$template = dirname(dirname( __FILE__ )) . '/single-session.php';
		}
		
		return $template;	
	}

	// add a session option to session page
	if( is_admin() ){ add_action('after_setup_theme', 'gdlr_create_session_options'); }
	if( !function_exists('gdlr_create_session_options') ){
	
		function gdlr_create_session_options(){
			global $gdlr_sidebar_controller;
			
			if( !class_exists('gdlr_page_options') ) return;
			new gdlr_page_options( 
				
				// page option attribute
				array(
					'post_type' => array('session'),
					'meta_title' => __('Goodlayers Session Option', 'gdlr-conference'),
					'meta_slug' => 'goodlayers-page-option',
					'option_name' => 'post-option',
					'position' => 'normal',
					'priority' => 'high',
				),
					  
				// page option settings
				array(
					'page-option' => array(
						'title' => __('Page Option', 'gdlr-conference'),
						'options' => array(
							'page-caption' => array(
								'title' => __('Page Caption' , 'gdlr-conference'),
								'type' => 'textarea'
							),
							'session-type' => array(
								'title' => __('Session Type' , 'gdlr-conference'),
								'type' => 'combobox',
								'options' => array(
									'conference' => __('Conference', 'gdlr-conference'),
									'break' => __('Break', 'gdlr-conference'),
								)
							),
							'session-speaker' => array(
								'title' => __('Session Speaker' , 'gdlr-conference'),
								'type' => 'multi-combobox',
								'options' => gdlr_get_post_list('speaker')
							),
							'session-date' => array(
								'title' => __('Session Date' , 'gdlr-conference'),
								'type' => 'date-picker'
							),
							'session-time' => array(
								'title' => __('Session Time' , 'gdlr-conference'),
								'type' => 'text',
								'description' => __('Please fill this field in hh:mm - hh:mm format', 'gdlr-conference')
							),
							'location' => array(
								'title' => __('Location' , 'gdlr-conference'),
								'type' => 'text',
								'wrapper-class' => 'session-type-wrapper conference-wrapper'
							),
							'document-link' => array(
								'title' => __('Document Download Link' , 'gdlr-conference'),
								'type' => 'text',
								'wrapper-class' => 'session-type-wrapper conference-wrapper'
							),
						)
					),					
				)
			);
			
		}
	}	
	
	// filter to save the custom date value
	add_filter('gdlr_custom_page_option_meta', 'gdlr_save_speaker_custom_meta', 10, 2);
	function gdlr_save_speaker_custom_meta($value, $var){
		if( get_post_type() == 'session' && !empty($var) ){
			
			$value[] = array(
				'key' => 'session-date',
				'value' => gdlr_convert_to_iso_date($var['session-date'], $var['session-time'])
			);
			if( !empty($var['session-speaker']) ){
				$value[] = array(
					'key' => 'session-speaker',
					'value' => implode(',', $var['session-speaker'])
				);
			}
		}
		return $value;
	}	
	
	// add the function to collaborate with page builder
	add_filter('gdlr_page_builder_option', 'gdlr_register_session_item');
	function gdlr_register_session_item( $page_builder = array() ){
		global $gdlr_spaces;
	
		$page_builder['content-item']['options']['session'] = array(
			'title'=> __('Session', 'gdlr-conference'), 
			'type'=>'item',
			'options'=>array_merge(gdlr_page_builder_title_option(true), array(					
				'category'=> array(
					'title'=> __('Category' ,'gdlr-conference'),
					'type'=> 'multi-combobox',
					'options'=> gdlr_get_term_list('session_category'),
					'description'=> __('You can use Ctrl/Command button to select multiple categories or remove the selected category. <br><br> Leave this field blank to select all categories.', 'gdlr-conference')
				),		
				'session-style'=> array(
					'title'=> __('Item Style' ,'gdlr-conference'),
					'type'=> 'combobox',
					'options'=> array(
						'small' => __('Small Style', 'gdlr-conference'),
						'tab' => __('Tab Style', 'gdlr-conference'),
						'full' => __('Full Style', 'gdlr-conference')
					),
				),
				'num-excerpt' => array(
					'title' => __('Excerpt Number', 'gdlr-conference'),
					'type' => 'text',
					'default' => '30'
				),
				'margin-bottom' => array(
					'title' => __('Margin Bottom', 'gdlr-conference'),
					'type' => 'text',
					'default' => $gdlr_spaces['bottom-item'],
					'description' => __('Spaces after ending of this item', 'gdlr-conference')
				),				
			))
		);
		
		$page_builder['content-item']['options']['session-counter'] = array(
			'title'=> __('Session Counter', 'gdlr-conference'), 
			'type'=>'item',
			'options'=>array(					
				'select-session'=> array(
					'title'=> __('Select Session' ,'gdlr-conference'),
					'type'=> 'combobox',
					'options'=> gdlr_get_post_list('session')
				),
				'margin-bottom' => array(
					'title' => __('Margin Bottom', 'gdlr-conference'),
					'type' => 'text',
					'default' => $gdlr_spaces['bottom-item'],
					'description' => __('Spaces after ending of this item', 'gdlr-conference')
				),	
			)
		);
		return $page_builder;
	}		
	
?>