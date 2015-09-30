<?php
	/*	
	*	Goodlayers Function File
	*	---------------------------------------------------------------------
	*	This file include all of important function and features of the theme
	*	---------------------------------------------------------------------
	*/	
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

/**
 * This filter will bypass the Cart page when a product is added to cart and
 * send the customer directly to checkout.
 */
add_filter ('woocommerce_add_to_cart_redirect', 'woo_redirect_to_checkout');
function woo_redirect_to_checkout() {
	$checkout_url = WC()->cart->get_checkout_url();

	return $checkout_url;
}