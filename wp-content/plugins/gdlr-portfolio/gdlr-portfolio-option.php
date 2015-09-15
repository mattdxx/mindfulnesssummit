<?php
	/*	
	*	Goodlayers Portfolio Option file
	*	---------------------------------------------------------------------
	*	This file creates all portfolio options and attached to the theme
	*	---------------------------------------------------------------------
	*/
	
	// add a portfolio option to portfolio page
	if( is_admin() ){ add_action('after_setup_theme', 'gdlr_create_portfolio_options'); }
	if( !function_exists('gdlr_create_portfolio_options') ){
	
		function gdlr_create_portfolio_options(){
			global $gdlr_sidebar_controller;
			
			if( !class_exists('gdlr_page_options') ) return;
			new gdlr_page_options( 
				
				// page option attribute
				array(
					'post_type' => array('portfolio'),
					'meta_title' => __('Goodlayers Portfolio Option', 'gdlr-portfolio'),
					'meta_slug' => 'goodlayers-page-option',
					'option_name' => 'post-option',
					'position' => 'normal',
					'priority' => 'high',
				),
					  
				// page option settings
				array(
					'page-layout' => array(
						'title' => __('Page Layout', 'gdlr-portfolio'),
						'options' => array(
								'sidebar' => array(
									'type' => 'radioimage',
									'options' => array(
										'no-sidebar'=>GDLR_PATH . '/include/images/no-sidebar-2.png',
										'both-sidebar'=>GDLR_PATH . '/include/images/both-sidebar-2.png', 
										'right-sidebar'=>GDLR_PATH . '/include/images/right-sidebar-2.png',
										'left-sidebar'=>GDLR_PATH . '/include/images/left-sidebar-2.png'
									),
									'default' => 'no-sidebar'
								),	
								'left-sidebar' => array(
									'title' => __('Left Sidebar' , 'gdlr-portfolio'),
									'type' => 'combobox',
									'options' => $gdlr_sidebar_controller->get_sidebar_array(),
									'wrapper-class' => 'sidebar-wrapper left-sidebar-wrapper both-sidebar-wrapper'
								),
								'right-sidebar' => array(
									'title' => __('Right Sidebar' , 'gdlr-portfolio'),
									'type' => 'combobox',
									'options' => $gdlr_sidebar_controller->get_sidebar_array(),
									'wrapper-class' => 'sidebar-wrapper right-sidebar-wrapper both-sidebar-wrapper'
								),						
						)
					),
					
					'page-option' => array(
						'title' => __('Page Option', 'gdlr-portfolio'),
						'options' => array(
							'page-caption' => array(
								'title' => __('Page Caption' , 'gdlr-portfolio'),
								'type' => 'textarea'
							),							
							'header-background' => array(
								'title' => __('Header Background Image' , 'gdlr-portfolio'),
								'button' => __('Upload', 'gdlr-portfolio'),
								'type' => 'upload',
							),	
							'clients' => array(
								'title' => __('Clients' , 'gdlr-portfolio'),
								'type' => 'text',
							),
							'skills' => array(
								'title' => __('Skills' , 'gdlr-portfolio'),
								'type' => 'text',
							),
							'website' => array(
								'title' => __('Website' , 'gdlr-portfolio'),
								'type' => 'text',
							),							
							'thumbnail-type' => array(
								'title' => __('Thumbnail Type' , 'gdlr-portfolio'),
								'type' => 'combobox',
								'options' => array(
									'feature-image'=> __('Feature Image', 'gdlr-portfolio'),
									'video'=> __('Video', 'gdlr-portfolio'),
									'slider'=> __('Slider', 'gdlr-portfolio')
								),
								'wrapper-class' => 'gdlr-top-divider'
							),
							'thumbnail-link' => array(
								'title' => __('Thumbnail Link' , 'gdlr-portfolio'),
								'type' => 'combobox',
								'options' => array(
									'current-post'=> __('Link to Portfolio', 'gdlr-portfolio'),
									'current'=> __('Lightbox to Full Image', 'gdlr-portfolio'),
									'url'=> __('Link to URL', 'gdlr-portfolio'),
									'image'=> __('Lightbox Image', 'gdlr-portfolio'),
									'video'=> __('Lightbox Video', 'gdlr-portfolio')
								),
								'wrapper-class' => 'thumbnail-type-wrapper feature-image-wrapper'								
							),
							'thumbnail-url' => array(
								'title' => __('Specify Url' , 'gdlr-portfolio'),
								'type' => 'text',
								'wrapper-class' => 'thumbnail-link-wrapper video-wrapper image-wrapper url-wrapper'
							),	
							'thumbnail-new-tab' => array(
								'title' => __('Open In New Tab' , 'gdlr-portfolio'),
								'type' => 'checkbox',
								'wrapper-class' => 'thumbnail-link-wrapper url-wrapper'
							),							
							'thumbnail-video' => array(
								'title' => __('Video Url' , 'gdlr-portfolio'),
								'type' => 'text',
								'wrapper-class' => 'video-wrapper thumbnail-type-wrapper'
							),		
							'thumbnail-slider' => array(
								'title' => __('Slider' , 'gdlr-portfolio'),
								'type' => 'slider',
								'wrapper-class' => 'slider-wrapper thumbnail-type-wrapper'
							),								
							'inside-thumbnail-type' => array(
								'title' => __('Inside Portfolio Thumbnail Type' , 'gdlr-portfolio'),
								'type' => 'combobox',
								'options' => array(
									'thumbnail-type'=> __('Same As Thumbnail Type', 'gdlr-portfolio'),
									'image'=> __('Image', 'gdlr-portfolio'),
									'video'=> __('Video', 'gdlr-portfolio'),
									'slider'=> __('Slider', 'gdlr-portfolio'),
									'stack-image'=> __('Stack Images', 'gdlr-portfolio')
								),
								'wrapper-class' => 'gdlr-top-divider'
							),		
							'inside-thumbnail-image' => array(
								'title' => __('Image Url' , 'gdlr-portfolio'),
								'type' => 'upload',
								'wrapper-class' => 'image-wrapper inside-thumbnail-type-wrapper'
							),							
							'inside-thumbnail-video' => array(
								'title' => __('Video Url' , 'gdlr-portfolio'),
								'type' => 'text',
								'wrapper-class' => 'video-wrapper inside-thumbnail-type-wrapper'
							),		
							'inside-thumbnail-slider' => array(
								'title' => __('Slider' , 'gdlr-portfolio'),
								'type' => 'slider',
								'wrapper-class' => 'stack-image-wrapper slider-wrapper inside-thumbnail-type-wrapper'
							),								
						)
					),

				)
			);
			
		}
	}	
	
	// add portfolio in page builder area
	add_filter('gdlr_page_builder_option', 'gdlr_register_portfolio_item');
	if( !function_exists('gdlr_register_portfolio_item') ){
		function gdlr_register_portfolio_item( $page_builder = array() ){
			global $gdlr_spaces;
		
			$page_builder['content-item']['options']['portfolio'] = array(
				'title'=> __('Portfolio', 'gdlr-portfolio'), 
				'type'=>'item',
				'options'=>array_merge(gdlr_page_builder_title_option(true), array(					
					'category'=> array(
						'title'=> __('Category' ,'gdlr-portfolio'),
						'type'=> 'multi-combobox',
						'options'=> gdlr_get_term_list('portfolio_category'),
						'description'=> __('You can use Ctrl/Command button to select multiple categories or remove the selected category. <br><br> Leave this field blank to select all categories.', 'gdlr-portfolio')
					),	
					'tag'=> array(
						'title'=> __('Tag' ,'gdlr-portfolio'),
						'type'=> 'multi-combobox',
						'options'=> gdlr_get_term_list('portfolio_tag'),
						'description'=> __('Will be ignored when the portfolio filter option is enabled.', 'gdlr-portfolio')
					),					
					'portfolio-style'=> array(
						'title'=> __('Portfolio Style' ,'gdlr-portfolio'),
						'type'=> 'combobox',
						'options'=> array(
							'classic-portfolio' => __('Classic Style', 'gdlr-portfolio'),
							'classic-portfolio-no-space' => __('Classic No Space Style', 'gdlr-portfolio'),
							'modern-portfolio' => __('Modern Style', 'gdlr-portfolio'),
							'modern-portfolio-no-space' => __('Modern No Space Style', 'gdlr-portfolio'),
						),
					),					
					'num-fetch'=> array(
						'title'=> __('Num Fetch' ,'gdlr-portfolio'),
						'type'=> 'text',	
						'default'=> '8',
						'description'=> __('Specify the number of portfolios you want to pull out.', 'gdlr-portfolio')
					),	
					'num-excerpt'=> array(
						'title'=> __('Num Excerpt' ,'gdlr-portfolio'),
						'type'=> 'text',	
						'default'=> '20',
						'wrapper-class'=>'portfolio-style-wrapper classic-portfolio-wrapper classic-portfolio-no-space-wrapper'
					),					
					'portfolio-size'=> array(
						'title'=> __('Portfolio Size' ,'gdlr-portfolio'),
						'type'=> 'combobox',
						'options'=> array(
							'1/4'=>'1/4',
							'1/3'=>'1/3',
							'1/2'=>'1/2',
							'1/1'=>'1/1'
						),
						'default'=>'1/3'
					),					
					'portfolio-layout'=> array(
						'title'=> __('Portfolio Layout Order' ,'gdlr-portfolio'),
						'type'=> 'combobox',
						'options'=> array(
							'fitRows' =>  __('FitRows ( Order items by row )', 'gdlr-portfolio'),
							'masonry' => __('Masonry ( Order items by spaces )', 'gdlr-portfolio'),
							'carousel' => __('Carousel ( Only For Grid And Modern Style )', 'gdlr-portfolio'),
						),
						'description'=> __('You can see an example of these two layout here', 'gdlr-portfolio') . 
							'<br><br> http://isotope.metafizzy.co/demos/layout-modes.html'
					),
					'portfolio-filter'=> array(
						'title'=> __('Enable Portfolio filter' ,'gdlr-portfolio'),
						'type'=> 'checkbox',
						'default'=> 'disable',
						'description'=> __('*** You have to select only 1 ( or none ) portfolio category when enable this option. This option cannot works with carousel function.','gdlr-portfolio')
					),						
					'thumbnail-size'=> array(
						'title'=> __('Thumbnail Size' ,'gdlr-portfolio'),
						'type'=> 'combobox',
						'options'=> gdlr_get_thumbnail_list(),
						'description'=> __('Only effects to <strong>standard and gallery post format</strong>','gdlr-portfolio')
					),	
					'orderby'=> array(
						'title'=> __('Order By' ,'gdlr-portfolio'),
						'type'=> 'combobox',
						'options'=> array(
							'date' => __('Publish Date', 'gdlr-portfolio'), 
							'title' => __('Title', 'gdlr-portfolio'), 
							'rand' => __('Random', 'gdlr-portfolio'), 
						)
					),
					'order'=> array(
						'title'=> __('Order' ,'gdlr-portfolio'),
						'type'=> 'combobox',
						'options'=> array(
							'desc'=>__('Descending Order', 'gdlr-portfolio'), 
							'asc'=> __('Ascending Order', 'gdlr-portfolio'), 
						)
					),			
					'pagination'=> array(
						'title'=> __('Enable Pagination' ,'gdlr-portfolio'),
						'type'=> 'checkbox'
					),					
					'margin-bottom' => array(
						'title' => __('Margin Bottom', 'gdlr-portfolio'),
						'type' => 'text',
						'default' => $gdlr_spaces['bottom-blog-item'],
						'description' => __('Spaces after ending of this item', 'gdlr-portfolio')
					),				
				))
			);
			return $page_builder;
		}
	}
	
?>