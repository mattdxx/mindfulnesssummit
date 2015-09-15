<?php

// Init plugin auto-update class
function msp_check_for_update() {

    $current_version 	= MSWP_AVERTA_VERSION;
    $update_path 		= 'http://support.averta.net/envato/api/';
    $plugin_slug 		= MSWP_AVERTA_BASE_NAME;
    $slug 				= 'masterslider';
    $item_request_name  = 'masterslider-wp';
    $plugin_file        = MSWP_AVERTA_DIR . '/masterslider.php';

    new Axiom_Plugin_Check_Update ( $current_version, $update_path, $plugin_slug, $slug, $item_request_name, $plugin_file );
}
msp_check_for_update();



function msp_filter_masterslider_admin_menu_title( $menu_title ){
	$current = get_site_transient( 'update_plugins' );

    if ( ! isset( $current->response[ MSWP_AVERTA_BASE_NAME ] ) )
		return $menu_title;
	
	return $menu_title . '&nbsp;<span class="update-plugins"><span class="plugin-count">1</span></span>';
}

add_filter( 'masterslider_admin_menu_title', 'msp_filter_masterslider_admin_menu_title');



function after_masterslider_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ){
    if( MSWP_AVERTA_BASE_NAME == $plugin_file && get_option( MSWP_SLUG . '_is_license_actived', 0 ) ){
        $plugin_meta[] = '<a href="http://masterslider.com/doc/wp/#rate" target="_blank" title="' . esc_attr__( 'Rate this plugin', MSWP_TEXT_DOMAIN ) . '">' . __( 'Rate this plugin', MSWP_TEXT_DOMAIN ) . '</a>';
        $plugin_meta[] = '<a href="http://masterslider.com/doc/wp/#support" target="_blank" title="' . esc_attr__( 'Premium support', MSWP_TEXT_DOMAIN ) . '">' . __( 'Premium support', MSWP_TEXT_DOMAIN ) . '</a>';
    }
    
    return $plugin_meta;
}

add_filter( "plugin_row_meta", 'after_masterslider_row_meta', 10, 4 );