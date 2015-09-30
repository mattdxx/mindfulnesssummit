<?php
/*
Plugin Name: Optimize Wordpress
Description: Optimize wordpress to speed up the whole process
Version: 1.0
Author: Stratos Nikolaidis
Author URI: https://gr.linkedin.com/in/stratosnikolaidis
Plugin URI: https://gr.linkedin.com/in/stratosnikolaidis
License: Toptal
*/


/*
Prevent Contact Form 7 to reload the contact us forms data, in case the page is cached.
*/

remove_action( 'wp_enqueue_scripts', 'wpcf7_do_enqueue_scripts' );

add_action( 'wp_enqueue_scripts', 'wpcf7_do_enqueue_scripts_with_no_cache' );
function wpcf7_do_enqueue_scripts_with_no_cache() {
	if ( wpcf7_load_js() ) {
		wpcf7_enqueue_scripts_with_no_cache();
	}

	if ( wpcf7_load_css() ) {
		wpcf7_enqueue_styles();
	}
}

function wpcf7_enqueue_scripts_with_no_cache() {
	// jquery.form.js originally bundled with WordPress is out of date and deprecated
	// so we need to deregister it and re-register the latest one
	wp_deregister_script( 'jquery-form' );
	wp_register_script( 'jquery-form',
		wpcf7_plugin_url( 'includes/js/jquery.form.min.js' ),
		array( 'jquery' ), '3.51.0-2014.06.20', true );

	$in_footer = true;

	if ( 'header' === wpcf7_load_js() ) {
		$in_footer = false;
	}

	wp_enqueue_script( 'contact-form-7',
		wpcf7_plugin_url( 'includes/js/scripts.js' ),
		array( 'jquery', 'jquery-form' ), WPCF7_VERSION, $in_footer );

	$_wpcf7 = array(
		'loaderUrl' => wpcf7_ajax_loader(),
		'sending' => __( 'Sending ...', 'contact-form-7' ) );

	// if ( defined( 'WP_CACHE' ) && WP_CACHE )
	// 	$_wpcf7['cached'] = 1;

	if ( wpcf7_support_html5_fallback() )
		$_wpcf7['jqueryUi'] = 1;

	wp_localize_script( 'contact-form-7', '_wpcf7', $_wpcf7 );

	do_action( 'wpcf7_enqueue_scripts' );
}

/**
 * CACHE:
 *
 * WP_Engine uses their own caching system (which is good) but it's based on database (cache info is stored in database).
 * There will be a dramatic increase of the performarce if you switch on memory cache (perhaps this is all you might need)
 *
**/
