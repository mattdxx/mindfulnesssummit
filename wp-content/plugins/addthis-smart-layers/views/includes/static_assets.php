<?php

$addthis_welcome_bar_css_base = apply_filters('addthis_welcome_bar_css_base', plugins_url( basename(dirname(dirname(dirname(__file__))) ) ) . '/css/'   ) ;
$addthis_welcome_bar_js_base = apply_filters('addthis_welcome_bar_css_base', plugins_url( basename(dirname(dirname(dirname(__file__))) )) . '/js/'   ) ;

wp_enqueue_style( 'at-common', $addthis_welcome_bar_css_base .  'common.css', array(), '1.0' );
wp_enqueue_style( 'at-gtc-wombat', $addthis_welcome_bar_css_base .'gtc.wombat.css' , array(), '1.0' );
wp_enqueue_style( 'jquery-miniColors', $addthis_welcome_bar_css_base .'jquery.miniColors.css' , array(), '1.0' );

wp_enqueue_script('addthis-widget', 'http://s7.addthis.com/js/250/addthis_widget.js', false, '1.0.0' );
wp_enqueue_script('at-welcome-extra', $addthis_welcome_bar_js_base . '/at-welcome-extra.js' , false, '1.0.0' );
//wp_enqueue_script('at-core', $addthis_welcome_bar_js_base . '/core-1.0.0.js' , false, '1.0.0' );
wp_enqueue_script('at-lr',$addthis_welcome_bar_js_base . 'lr.js' , false, '1.0.0' );
wp_enqueue_script('at-modal', $addthis_welcome_bar_js_base . 'at-modal.js' , false, '1.0.0' );
wp_enqueue_script('at-gtc-tracking', $addthis_welcome_bar_js_base . 'gtc-tracking.js' , false, '1.0.0' );
wp_enqueue_script('at-gtc-service-list',$addthis_welcome_bar_js_base . '/service_list.js' , false, '1.0.0' );
wp_enqueue_script('jquery-miniColors', $addthis_welcome_bar_js_base . 'jquery.miniColors.min.js' , false, '1.0.0' );
wp_enqueue_script('jaml', $addthis_welcome_bar_js_base . 'jaml.js' , false, '1.0.0' );





$advanced = get_option('addthis_bar_config_advanced');

	if('' . $advanced == '0') {
		wp_enqueue_script('at-gtc-wombat', $addthis_welcome_bar_js_base .'gtc-wombat.js' , false, '1.0.0' );
	}
	
$activated = get_option('addthis_bar_activated');

	if('' . $activated == '0') {
		wp_enqueue_script('at-gtc-wombat', $addthis_welcome_bar_js_base .'gtc-wombat.js' , false, '1.0.0' );
	}
?>
