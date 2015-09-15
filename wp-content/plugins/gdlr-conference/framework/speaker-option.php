<?php
	/*	
	*	Goodlayers Speaker Option File
	*/	
	 
	// create the speaker post type
	add_action( 'init', 'gdlr_conference_create_speaker' );
	function gdlr_conference_create_speaker() {
		register_post_type( 'speaker',
			array(
				'labels' => array(
					'name'               => __('Speakers', 'gdlr-conference'),
					'singular_name'      => __('Speaker', 'gdlr-conference'),
					'add_new'            => __('Add New', 'gdlr-conference'),
					'add_new_item'       => __('Add New speaker', 'gdlr-conference'),
					'edit_item'          => __('Edit speaker', 'gdlr-conference'),
					'new_item'           => __('New speaker', 'gdlr-conference'),
					'all_items'          => __('All speakers', 'gdlr-conference'),
					'view_item'          => __('View speaker', 'gdlr-conference'),
					'search_items'       => __('Search speakers', 'gdlr-conference'),
					'not_found'          => __('No speakers found', 'gdlr-conference'),
					'not_found_in_trash' => __('No speakers found in Trash', 'gdlr-conference'),
					'parent_item_colon'  => '',
					'menu_name'          => __('Speakers', 'gdlr-conference')
				),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				//'rewrite'            => array( 'slug' => 'speaker'  ),
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => 5,
				'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields' )
			)
		);	
		
		// create speaker categories
		register_taxonomy(
			'speaker_category', array("speaker"), array(
				'hierarchical' => true,
				'show_admin_column' => true,
				'label' => __('Speaker Categories', 'gdlr-conference'), 
				'singular_label' => __('Speaker Category', 'gdlr-conference'), 
				'rewrite' => array( 'slug' => 'speaker_category'  )));
		register_taxonomy_for_object_type('speaker_category', 'speaker');
		
		add_filter('single_template', 'gdlr_conference_register_speaker_template');
	}
	
	// register single speaker template
	function gdlr_conference_register_speaker_template($template) {
		global $wpdb, $post, $current_user;

		if( $post->post_type == 'speaker' ){
			$template = dirname(dirname( __FILE__ )) . '/single-speaker.php';
		}
		
		return $template;	
	}
	
	// add a speaker option to speaker page
	if( is_admin() ){ add_action('after_setup_theme', 'gdlr_create_speaker_options'); }
	if( !function_exists('gdlr_create_speaker_options') ){
	
		function gdlr_create_speaker_options(){
			global $gdlr_sidebar_controller;
			
			if( !class_exists('gdlr_page_options') ) return;
			new gdlr_page_options( 
				
				// page option attribute
				array(
					'post_type' => array('speaker'),
					'meta_title' => __('Goodlayers Speaker Option', 'gdlr-conference'),
					'meta_slug' => 'goodlayers-page-option',
					'option_name' => 'post-option',
					'position' => 'normal',
					'priority' => 'high',
				),
					  
				// page option settings
				array(
					'speaker-info' => array(
						'title' => __('Speaker Info', 'gdlr-conference'),
						'options' => array(
							'page-caption' => array(
								'title' => __('Speaker Position' , 'gdlr-conference'),
								'type' => 'text'
							),	
							'speaker-social' => array(
								'title' => __('Speaker Social Shortcode' , 'gdlr-conference'),
								'type' => 'textarea', 
								'description' => '[gdlr_social type="facebook" ]URL_HERE[/gdlr_social]<br><br>' .
									__('Here\'s the list of the avialable social type : ', 'gdlr-conference') .
									' android, apple, apple2, email, email2, facebook, googleplus, instagram, linkedin, picasa, pinterest, rss, tumblr, twitter, wordpress, youtube, youtube2'
							),		
							'telephone' => array(
								'title' => __('Telephone' , 'gdlr-conference'),
								'type' => 'text'
							),			
							'email' => array(
								'title' => __('E-Mail' , 'gdlr-conference'),
								'type' => 'text'
							),			
							'website' => array(
								'title' => __('Website' , 'gdlr-conference'),
								'type' => 'text'
							),								
						)
					),	

					'thumbnail-hover' => array(
						'title' => __('Social Thumbnail Hover', 'gdlr-conference'),
						'options' => array(
							'icon-1' => array(
								'title' => __('Icon 1' , 'gdlr-conference'),
								'type' => 'text', 
								'description' => 'http://fortawesome.github.io/Font-Awesome/icons/'
							),
							'icon-link-1' => array(
								'title' => __('Icon 1 Link' , 'gdlr-conference'),
								'type' => 'text'
							),
							'icon-2' => array(
								'title' => __('Icon 2' , 'gdlr-conference'),
								'type' => 'text'
							),
							'icon-link-2' => array(
								'title' => __('Icon 2 Link' , 'gdlr-conference'),
								'type' => 'text'
							),
							'icon-3' => array(
								'title' => __('Icon 3' , 'gdlr-conference'),
								'type' => 'text'
							),
							'icon-link-3' => array(
								'title' => __('Icon 3 Link' , 'gdlr-conference'),
								'type' => 'text'
							),
							
						)
					)
				)
			);
			
		}
	}	
	
	// add the function to collaborate with page builder
	add_filter('gdlr_page_builder_option', 'gdlr_register_speaker_item');
	function gdlr_register_speaker_item( $page_builder = array() ){
		global $gdlr_spaces;
	
		$page_builder['content-item']['options']['speaker'] = array(
			'title'=> __('Speaker', 'gdlr-conference'), 
			'type'=>'item',
			'options'=>array_merge(gdlr_page_builder_title_option(true), array(					
				'category'=> array(
					'title'=> __('Category' ,'gdlr-conference'),
					'type'=> 'multi-combobox',
					'options'=> gdlr_get_term_list('speaker_category'),
					'description'=> __('You can use Ctrl/Command button to select multiple categories or remove the selected category. <br><br> Leave this field blank to select all categories.', 'gdlr-conference')
				),		
				'speaker-style'=> array(
					'title'=> __('Speaker Style' ,'gdlr-conference'),
					'type'=> 'combobox',
					'options'=> array(
						'round' => __('Round Thumbnail Style', 'gdlr-conference'),
						'circle' => __('Circle Thumbnail Style', 'gdlr-conference'),
					),
				),					
				'num-fetch'=> array(
					'title'=> __('Num Fetch' ,'gdlr-conference'),
					'type'=> 'text',	
					'default'=> '8',
					'description'=> __('Specify the number of speaker items you want to pull out.', 'gdlr-conference')
				),					
				'speaker-size'=> array(
					'title'=> __('Speaker Item Size' ,'gdlr-conference'),
					'type'=> 'combobox',
					'options'=> array(
						'4'=>'1/4',
						'3'=>'1/3',
						'2'=>'1/2',
						'1'=>'1/1'
					),
					'default'=>'1/3'
				),					
				'speaker-layout'=> array(
					'title'=> __('Speaker Layout Order' ,'gdlr-conference'),
					'type'=> 'combobox',
					'options'=> array(
						'fitRows' =>  __('FitRows ( Order items by row )', 'gdlr-conference'),
						'carousel' => __('Carousel', 'gdlr-conference'),
					)
				),					
				'thumbnail-size'=> array(
					'title'=> __('Thumbnail Size' ,'gdlr-conference'),
					'type'=> 'combobox',
					'options'=> gdlr_get_thumbnail_list()
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
				'pagination'=> array(
					'title'=> __('Enable Pagination' ,'gdlr-conference'),
					'type'=> 'checkbox'
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