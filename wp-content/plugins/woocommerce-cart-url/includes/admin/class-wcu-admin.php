<?PHP
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WCU_Admin.
 *
 * Admin settings class.
 *
 * @class       WCU_Admin
 * @version     1.0.0
 * @author      Shop Plugins
 */
class WCU_Admin {


	/**
	 * __construct function.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$this->hooks();

	}


	/**
	 * Class hooks.
	 *
	 * All initial hooks used in this class.
	 *
	 * @since 1.0.0
	 */
	public function hooks() {

		// Add WC settings tab
		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'woocommerce_settings_tab' ), 40 );

		// Settings page contents
		add_action( 'woocommerce_settings_tabs_cart_urls', array( $this, 'woocommerce_settings_page' ) );

		// Save settings page
		add_action( 'woocommerce_update_options_cart_urls', array( $this, 'woocommerce_update_options' ) );

		// Cart URLs table
		add_action( 'woocommerce_admin_field_cart_urls_table', array( $this, 'generate_cart_urls_table_html' ) );

		// Keep WC menu open while in WAS edit screen
		add_action( 'admin_head', array( $this, 'menu_highlight' ) );

		// Add to WooCommerce screen ids.
		add_filter( 'woocommerce_screen_ids', array( $this, 'add_to_wc_screen_ids' ) );

/** License actions */
		// License field
		add_action( 'woocommerce_admin_field_wcu_license', array( $this, 'generate_license_html' ) );

		// Activate/deactivate license action
		add_action( 'admin_init', array( $this, 'activate_deactivate_license' ) );

		// Delete status on option change
		add_action( 'pre_update_option_woocommerce_cart_url_sl_key', array( $this, 'update_license_status_on_key_change' ), 10, 2 );
/** End license actions */

	}


	/**
	 * Settings tab.
	 *
	 * Add a WooCommerce settings tab for the Cart URLs settings page.
	 *
	 * @since 1.0.0
	 *
	 * @param $tabs
	 * @return array All WC settings tabs including newly added.
	 */
	public function woocommerce_settings_tab( $tabs) {

		$tabs['cart_urls'] = __( 'Cart URLs', 'woocommerce-cart-url' );

		return $tabs;

	}


	/**
	 * Settings page array.
	 *
	 * Get settings page fields array.
	 *
	 * @since 1.0.0
	 */
	public function woocommerce_get_settings() {

		$settings = apply_filters( 'woocommerce_cart_url_data_settings', array(

			array(
				'title' 	=> __( 'WooCommerce Cart URL settings', 'woocommerce-cart-url' ),
				'type' 		=> 'title',
				'desc' 		=> '',
				'id' 		=> 'wcu_general'
			),

			array(
				'title'   	=> __( 'Enable Cart URLs', 'woocommerce-cart-url' ),
				'desc' 	  	=> __( 'Use this setting to enable or disable all Cart URLs.', 'woocommerce-cart-url' ),
				'id' 	  	=> 'enable_cart_urls',
				'default' 	=> 'yes',
				'type' 	  	=> 'checkbox',
				'autoload'	=> false
			),

			array(
				'title'   	=> __( 'License key', 'woocommerce-cart-url' ),
				'desc' 	  	=> '',
				'id' 	  	=> 'woocommerce_cart_url_sl_key',
				'default' 	=> '',
				'type' 	  	=> 'wcu_license',
				'autoload'	=> false
			),

			array(
				'title'   	=> __( 'Table', 'woocommerce-cart-url' ),
				'type' 	  	=> 'cart_urls_table',
			),

			array(
				'type' 		=> 'sectionend',
				'id' 		=> 'wcu_general'
			)

		) );

		return $settings;

	}


	/**
	 * Cart URLs table.
	 *
	 * Load and render the Cart URLs table.
	 *
	 * @return string
	 */
	public function generate_cart_urls_table_html() {

		ob_start();

			/**
			 * Load Cart URLs table view.
			 */
			require_once plugin_dir_path( __FILE__ ) . 'views/cart-urls-table.php';

		ob_end_flush();

	}


	/**
	 * validate_additional_conditions_table_field function.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $key
	 * @return bool
	 */
	public function validate_additional_cart_urls_table_field( $key ) {
		return false;
	}


	/**
	 * Keep menu open.
	 *
	 * Highlights the correct top level admin menu item for post type add screens.
	 *
	 * @since 1.0.5
	 */
	public function menu_highlight() {

		global $parent_file, $submenu_file, $post_type;

		if ( 'cart_url' == $post_type ) :
			$parent_file = 'woocommerce';
			$submenu_file = 'wc-settings';
		endif;

	}


	/**
	 * Add to WC Screen IDs.
	 *
	 * Add the Cart URL Custom Post Type to the WooCommerce screen IDs so that
	 * WooCommerce styles and scripts are loaded etc.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $screen_ids List of existing screen IDs.
	 * @return array             List of modified screen IDs.
	 */
	public function add_to_wc_screen_ids( $screen_ids ) {

		$screen_ids[] = 'cart_url';
		return $screen_ids;
	}


	/**
	 * License field.
	 *
	 * Print the HTML formatted license field.
	 *
	 * @since 1.0.0
	 */
	public function generate_license_html() {

		$license 	= get_option( 'woocommerce_cart_url_sl_key' );
		$status 	= get_option( 'woocommerce_cart_url_sl_status' );

		?><tr valign='top'>

			<th scope="row" class="titledesc">
				<label for="woocommerce_cart_url_sl_key"><?php _e( 'License key', 'woocommerce-cart-url' ); ?></label>
			</th>
			<td class="forminp forminp-text">
				<input name="woocommerce_cart_url_sl_key" id="woocommerce_cart_url_sl_key" type="text" style="" value="<?php echo $license; ?>" class="">
				<span class="description"><?php
					_e( 'Enter the license key, found in your <a target="_blank" href="https://shopplugins.com/account/">Shop Plugins dashboard</a>.' );
				?></span>
			</td>

		</tr><?php

		 if ( false !== $license ) :

			wp_nonce_field( 'wcu_nonce_action', 'wcu_nonce' );
			?><tr valign="top">

				<th scope="row" valign="top"><?php
					_e('License status');
				?></th>
				<td><?php
					if ( $status !== false && $status == 'valid' ) :
/* -- Deactivate button
<input type="submit" class="button-secondary" name="wcu_license_deactivate" style='vertical-align:middle; margin-right: 10px;'
							value="<?php _e( 'Deactivate License', 'woocommerce-cart-url' ); ?>"/>
*/
						?><span style="color:green;"><?php _e( 'Active', 'woocommerce-cart-url' ); ?></span><?php
					else :
						?><input type="submit" class="button-secondary" name="wcu_license_activate" style='vertical-align:middle; margin-right: 10px;'
							value="<?php _e( 'Activate License', 'woocommerce-cart-url' ); ?>"/>
						<span style="color:#A00;"><?php _e( 'License not yet activated', 'woocommerce-cart-url' ); ?></span><?php
					endif;
				?></td>

			</tr><?php

		 endif;

	}


	/**
	 * Delete status.
	 *
	 * Delete the license status when the license key changes. This
	 * forces the user to re-activate the license.
	 *
	 * @since 1.0.0
	 *
	 * @param 	mixed 	$new_value 	New value to be saved.
	 * @param 	mixed	$old_value	Current value, about to be overwritten
	 * @return	mixed				The new value.
	 */
	public function update_license_status_on_key_change( $new_value, $old_value ) {

		if ( $old_value && $old_value != $new_value ) :
			delete_option( 'woocommerce_cart_url_sl_status' );
		endif;

		return $new_value;

	}


	/**
	 * Activate/Deactivate license.
	 *
	 * Send a API request to activate/deactivate the current site.
	 *
	 * @since 1.0.0
	 */
	public function activate_deactivate_license() {

		// Bail if not activating license
		if ( ! isset( $_POST['wcu_license_activate'] ) && ! isset( $_POST['wcu_license_deactivate'] ) ) :
			return;
		endif;

		// Verify nonce
		if ( ! isset( $_POST['wcu_nonce'] ) || ! wp_verify_nonce( $_POST['wcu_nonce'], 'wcu_nonce_action' ) ) :
			return;
		endif;


		// data to send in our API request
		$api_params = array(
			'edd_action'	=> isset( $_POST['wcu_license_activate'] ) ? 'activate_license' : 'deactivate_license',
			'license'		=> trim( get_option( 'woocommerce_cart_url_sl_key', '' ) ),
			'item_name'		=> urlencode( 'WooCommerce Cart URL' ),
			'url'			=> home_url(),
		);

		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, WC_CART_URL_SHOP_PLUGINS_URL ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) ) :
			return false;
		endif;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		update_option( 'woocommerce_cart_url_sl_status', $license_data->license );

	}


	/**
	 * Settings page content.
	 *
	 * Output settings page content via WooCommerce output_fields() method.
	 *
	 * @since 1.0.0
	 */
	public function woocommerce_settings_page() {

		WC_Admin_Settings::output_fields( $this->woocommerce_get_settings() );

	}


	/**
	 * Save settings.
	 *
	 * Save settings based on WooCommerce save_fields() method.
	 *
	 * @since 1.0.0
	 */
	public function woocommerce_update_options() {

		WC_Admin_Settings::save_fields( $this->woocommerce_get_settings() );

	}


}
