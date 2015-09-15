<?php
/*
Plugin Name: Goodlayers Portflio Post Type
Plugin URI: 
Description: A Custom Post Type Plugin To Use With Goodlayers Theme ( This plugin functionality might not working properly on another theme )
Version: 1.0.0
Author: Goodlayers
Author URI: http://www.goodlayers.com
License: 
*/
include_once( 'gdlr-portfolio-item.php');	
include_once( 'gdlr-portfolio-option.php');	

// action to loaded the plugin translation file
add_action('plugins_loaded', 'gdlr_portfolio_init');
if( !function_exists('gdlr_portfolio_init') ){
	function gdlr_portfolio_init() {
		load_plugin_textdomain( 'gdlr-portfolio', false, dirname(plugin_basename( __FILE__ ))  . '/languages/' ); 
	}
}

// add action to create portfolio post type
add_action( 'init', 'gdlr_create_portfolio' );
if( !function_exists('gdlr_create_portfolio') ){
	function gdlr_create_portfolio() {
		global $theme_option;
		
		if( !empty($theme_option['portfolio-slug']) ){
			$portfolio_slug = $theme_option['portfolio-slug'];
			$portfolio_category_slug = $theme_option['portfolio-category-slug'];
			$portfolio_tag_slug = $theme_option['portfolio-tag-slug'];
		}else{
			$portfolio_slug = 'portfolio';
			$portfolio_category_slug = 'portfolio_category';
			$portfolio_tag_slug = 'portfolio_tag';
		}
		
		register_post_type( 'portfolio',
			array(
				'labels' => array(
					'name'               => __('Portfolios', 'gdlr-portfolio'),
					'singular_name'      => __('Portfolio', 'gdlr-portfolio'),
					'add_new'            => __('Add New', 'gdlr-portfolio'),
					'add_new_item'       => __('Add New Portfolio', 'gdlr-portfolio'),
					'edit_item'          => __('Edit Portfolio', 'gdlr-portfolio'),
					'new_item'           => __('New Portfolio', 'gdlr-portfolio'),
					'all_items'          => __('All Portfolios', 'gdlr-portfolio'),
					'view_item'          => __('View Portfolio', 'gdlr-portfolio'),
					'search_items'       => __('Search Portfolio', 'gdlr-portfolio'),
					'not_found'          => __('No portfolios found', 'gdlr-portfolio'),
					'not_found_in_trash' => __('No portfolios found in Trash', 'gdlr-portfolio'),
					'parent_item_colon'  => '',
					'menu_name'          => __('Portfolios', 'gdlr-portfolio')
				),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => $portfolio_slug  ),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => 5,
				'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields' )
			)
		);
		
		// create portfolio categories
		register_taxonomy(
			'portfolio_category', array("portfolio"), array(
				'hierarchical' => true,
				'show_admin_column' => true,
				'label' => __('Portfolio Categories', 'gdlr-portfolio'), 
				'singular_label' => __('Portfolio Category', 'gdlr-portfolio'), 
				'rewrite' => array( 'slug' => $portfolio_category_slug  )));
		register_taxonomy_for_object_type('portfolio_category', 'portfolio');
		
		// create portfolio tag
		register_taxonomy(
			'portfolio_tag', array('portfolio'), array(
				'hierarchical' => false, 
				'show_admin_column' => true,
				'label' => __('Portfolio Tags', 'gdlr-portfolio'), 
				'singular_label' => __('Portfolio Tag', 'gdlr-portfolio'),  
				'rewrite' => array( 'slug' => $portfolio_tag_slug  )));
		register_taxonomy_for_object_type('portfolio_tag', 'portfolio');	

		// add filter to style single template
		if( defined('WP_THEME_KEY') && WP_THEME_KEY == 'goodlayers' ){
			if( !empty($theme_option['portfolio-page-style']) && $theme_option['portfolio-page-style'] == 'blog-style' ){
				add_filter('single_template', 'gdlr_register_portfolio_blog_template');
			}else{
				add_filter('single_template', 'gdlr_register_portfolio_template');
			}
		}
	}
}

if( !function_exists('gdlr_register_portfolio_template') ){
	function gdlr_register_portfolio_template($single_template) {
		global $post;

		if ($post->post_type == 'portfolio') {
			$single_template = dirname( __FILE__ ) . '/single-portfolio.php';
		}
		return $single_template;	
	}
}

if( !function_exists('gdlr_register_portfolio_blog_template') ){
	function gdlr_register_portfolio_blog_template($single_template) {
		global $post;

		if ($post->post_type == 'portfolio') {
			$single_template = dirname( __FILE__ ) . '/single-portfolio-blog.php';
		}
		return $single_template;	
	}
}

// add filter for adjacent portfolio 
add_filter('get_previous_post_where', 'gdlr_portfolio_post_where', 10, 2);
add_filter('get_next_post_where', 'gdlr_portfolio_post_where', 10, 2);
if(!function_exists('gdlr_portfolio_post_where')){
	function gdlr_portfolio_post_where( $where, $in_same_cat ){ 
		global $post; 
		if ( $post->post_type == 'portfolio' ){
			$current_taxonomy = 'portfolio_category'; 
			$cat_array = wp_get_object_terms($post->ID, $current_taxonomy, array('fields' => 'ids')); 
			if($cat_array){ 
				$where .= " AND tt.taxonomy = '$current_taxonomy' AND tt.term_id IN (" . implode(',', $cat_array) . ")"; 
			} 
			}
		return $where; 
	} 	
}
	
add_filter('get_previous_post_join', 'get_portfolio_post_join', 10, 2);
add_filter('get_next_post_join', 'get_portfolio_post_join', 10, 2);	
if(!function_exists('get_portfolio_post_join')){
	function get_portfolio_post_join($join, $in_same_cat){ 
		global $post, $wpdb; 
		if ( $post->post_type == 'portfolio' ){
			$current_taxonomy = 'portfolio_category'; 
			if(wp_get_object_terms($post->ID, $current_taxonomy)){ 
				$join .= " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id"; 
			} 
		}
		return $join; 
	}
}

?>