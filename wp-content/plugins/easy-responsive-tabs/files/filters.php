<?php
/****************************************************************
 * Write these filter into your theme's functions.php to make plugin
 * compatible to your theme, You can use all of selective filters
 * according to your need.
 *****************************************************************/

/*
  * Filter for Custom options
  */
function apply_ert_custom_option( $prevent ) {
	return true;
}
add_filter( 'ert_custom_option', 'apply_ert_custom_option' );


/*
 *Filter for bootstrap_admin.css
 */
function apply_ert_custom_bootstrap_admin_css( $prevent ) {
	return true;
}
add_filter( 'ert_custom_bootstrap_admin_css', 'apply_ert_custom_bootstrap_admin_css' );


/*
  * Filter for bootstrap.min.js url this filter is only applicable if you selected js inclusion from plugin in ert Settings
  */

function apply_ert_bootstrap_js_url( $url ) {
	$ert_js_url='';// write your desired bootstrap.min.js url here
	return $ert_js_url;
}
add_filter( 'ert_bootstrap_js_url', 'apply_ert_bootstrap_js_url' );


/*
  * Filter for bootstrap.min.css urlthis filter is only applicable if you selected css inclusion from plugin in ert Settings
  */

function apply_ert_bootstrap_css_url( $url ) {
	$ert_css_url='';// write your bootstrap.min.css  url here
	return $ert_css_url;
}
add_filter( 'ert_bootstrap_css_url', 'apply_ert_bootstrap_css_url' );
