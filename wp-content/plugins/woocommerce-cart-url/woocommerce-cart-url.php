<?php
/*
 * Plugin Name: 	WooCommerce Cart URL
 * Plugin URI: 		https://shopplugins.com/plugins/woocommerce-cart-url/
 * Description: 	This plugin allows the WooCommerce store owner to define URLs that when opened setup a pre-defined cart of products and quantities.
 * Version: 		1.1.1
 * Author: 			Shop Plugins
 * Author URI: 		https://shopplugins.com
 * Text Domain: 	woocommerce-cart-url
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


define( 'WC_CART_URL_SHOP_PLUGINS_URL', 'https://shopplugins.com' );
if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	include( dirname( __FILE__ ) . '/includes/updater/EDD_SL_Plugin_Updater.php' );
}
function edd_sl_woocommerce_cart_url_updater() {
	$license_key = trim( get_option( 'woocommerce_cart_url_sl_key' ) );
	$edd_updater = new EDD_SL_Plugin_Updater( WC_CART_URL_SHOP_PLUGINS_URL, __FILE__, array(
			'version' 	=> '1.1.1', 				// current version number
			'license' 	=> $license_key, 			// license key (used get_option above to retrieve from DB)
			'item_name' => 'WooCommerce Cart URL', 	// name of this plugin
			'author' 	=> 'Shop Plugins'			// author of this plugin
		)
	);
}
add_action( 'admin_init', 'edd_sl_woocommerce_cart_url_updater', 0 );


/**
 * Class WooCommerce_Cart_Url.
 *
 * Main WCU class initializes the plugin.
 *
 * @class		WooCommerce_Cart_Url
 * @version		1.0.0
 * @author		Shop Plugins
 */
class WooCommerce_Cart_Url {


	/**
	 * Version.
	 *
	 * @since 1.0.0
	 * @var string $version Plugin version number.
	 */
	public $version = '1.1.1';


	/**
	 * Plugin file.
	 *
	 * @since 1.0.0
	 * @var string $file Plugin file path.
	 */
	public $file = __FILE__;


	/**
	 * Instance of WooCommerce_Cart_Url.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var object $instance The instance of WooCommerce_Cart_Url.
	 */
	private static $instance;


	/**
	 * SL url.
	 *
	 * @since 1.0.0
	 * @var URL for the SL.
	 */
	public $sl_url = 'https://shopplugins.com';



	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		if ( ! function_exists( 'is_plugin_active_for_network' ) ) :
		    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		endif;

		// Check if WooCommerce is active
		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) :
			if ( ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) :
				return;
			endif;
		endif;


		// Initialize plugin parts
		$this->init();

	}


	/**
	 * Instance.
	 *
	 * An global instance of the class. Used to retrieve the instance
	 * to use on other files/plugins/themes.
	 *
	 * @since 1.0.0
	 * @return object Instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) :
			self::$instance = new self();
		endif;

		return self::$instance;

	}


	/**
	 * Init.
	 *
	 * Initialize plugin parts.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		/**
		 * AJAX class.
		 */
		require_once plugin_dir_path( __FILE__ ) . '/includes/class-wcu-ajax.php';
		$this->ajax = new WCU_Ajax();

		/**
		 * Post type class.
		 */
		require_once plugin_dir_path( __FILE__ ) . '/includes/class-wcu-post-type.php';
		$this->post_type = new WCU_Post_Type();

		if ( is_admin() ) :

			/**
			 * Admin class.
			 */
			require_once plugin_dir_path( __FILE__ ) . '/includes/admin/class-wcu-admin.php';
			$this->admin = new WCU_Admin();

		endif;


		// Enqueue scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );

		// WCU init - check for URL
		add_action( 'template_redirect', array( $this, 'cart_url_action' ) );

		// Flush rewrite rules on activation
		register_activation_hook( __FILE__, array( $this, 'rewrite_flush' ) );

		// Plugin Action Links
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_links' ) );
	}


	/**
	 * Flush rewrite rules.
	 *
	 * Flush the rewrite rules on plugin activation so cart_url post type works.
	 *
	 * @since 1.0.0
	 */
	public function rewrite_flush() {

		$post_type = new WCU_Post_Type();
		$post_type->register_post_type();
		flush_rewrite_rules();

	}


	/**
	 * Admin style.
	 *
	 * Enqueue admin stylesheet.
	 *
	 * @since 1.0.0
	 *
	 * @global object $post Global post object.
	 */
	public function admin_enqueue() {

		global $post;

		// WCU Style
		wp_register_style( 'woocommerce-cart-url-style', plugins_url( 'assets/css/woocommerce-cart-url-style.css', __FILE__ ) );

		// WCU Javascript
		wp_register_script( 'woocommerce-cart-url', plugins_url( 'assets/js/woocommerce-cart-url.js', __FILE__ )  );
		wp_localize_script( 'woocommerce-cart-url', 'wcu', array(
			'search_nonce' 		=> wp_create_nonce("search-products"),
			'plugin_url' 		=> WC()->plugin_url(),
			'post_id'			=> isset( $post->ID ) ? $post->ID : '',
			'wcu_item_nonce'	=> wp_create_nonce("order-item"),
			'ajax_url' 			=> admin_url('admin-ajax.php')
		) );

		// Only apply style of the Cart URL post type
		if ( 'cart_url' == get_post_type() || ( isset( $_GET['tab'] ) && 'cart_urls' == $_GET['tab'] ) ) :

			wp_enqueue_script( 'woocommerce-cart-url' );
			wp_enqueue_style( 'woocommerce-cart-url-style' );
			wp_dequeue_script( 'autosave' );

		endif;



	}


	/**
	 * Init function.
	 *
	 * Check and initialize url for add_to_cart parameter.
	 *
	 * @since 1.0.0
	 */
	public function cart_url_action() {

		// Bail when WCU is not enabled.
		if ( 'no' == get_option( 'enable_cart_urls' ) ) :
			return;
		endif;

		// Bail when not the right action.
		if ( ! isset( $_GET['add_to_cart'] ) || ! $_GET['add_to_cart'] || 'cart_url' !== get_post_type( $_GET['add_to_cart'] ) ) :
			return;
		endif;

		$post_id = absint( $_GET['add_to_cart'] );

		if ( get_post_status( $post_id ) != 'publish' ) :
			return;
		endif;

		// Clear cart
		if ( WC()->cart && 'no' != get_post_meta( $post_id, '_clear_cart', true ) ) :
			WC()->cart->empty_cart();
		endif;

		$products_to_add = get_post_meta( $post_id, '_products', true );


		// Add products
		$this->add_to_cart( $products_to_add );

		$redirect = get_post_meta( $post_id, '_redirect', true );
		$redirect = empty( $redirect ) ? wc_get_page_id( 'cart' ) : $redirect;

		// Redirect to cart
		wp_redirect( get_permalink( $redirect ) );
		exit;

	}


	/**
	 * Add to cart.
	 *
	 * Add products to the cart that are passed through the url.
	 *
	 * @since 1.0.0
	 */
	public function add_to_cart( $products ) {

		if ( ! $products ) :
			return;
		endif;


		if ( is_array( $products ) ) :
			foreach ( $products as $product_id => $values ) :

				if ( 'product' != get_post_type( $product_id ) && 'product_variation' != get_post_type( $product_id ) ) :
					continue;
				endif;

				$quantity 		= isset( $values['quantity'] ) 		? $values['quantity'] 						: '1';
				$variation_id 	= isset( $values['variation_id'] ) 	? $values['variation_id'] 					: '';
				$var 			= ! empty( $variation_id ) 			? new WC_Product_Variation( $variation_id ) : null;
				$var			= ! empty( $var ) 					? $var->variation_data 						: null;

				WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $var );

			endforeach;
		endif;

	}


	/**
	 * Add To Cart URL.
	 *
	 * Generate Add To Cart URL from post ID.
	 *
	 * @since 1.0.0
	 *
	 * @param 	int 	$post_id 	Post ID of the cart_url post type to generate URL from.
	 * @return 	string	$url 		Generated URL with all proudcts, quantities included.
	 */
	public function generate_url( $post_id ) {

		/**
		 * @since 1.1.0 use the post ID intead of full blown URLs
		 */
		return esc_url_raw( add_query_arg( 'add_to_cart', $post_id, home_url() ) );

	}


	/**
	 * Add plugin links.
	 *
	 * Add plugin links to the plugins page.
	 *
	 * @since 1.0.0
	 *
	 * @param	array	$links	List of existing links.
	 * @return	array			List of modified links.
	 */
	function plugin_links( $links ) {
		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=cart_urls' ) . '">' . __( 'Settings', 'woocommerce-cart-url' ) . '</a>',
			'<a href="https://shopplugins.com/support">' . __( 'Support', 'woocommerce-cart-url' ) . '</a>',
			'<a href="http://docs.shopplugins.com/article/16-woocommerce-cart-url">' . __( 'Docs', 'woocommerce-cart-url' ) . '</a>',
		);

		return array_merge( $plugin_links, $links );
	}


}


/**
 * The main function responsible for returning the WooCommerce_Cart_Url object.
 *
 * Use this function like you would a global variable, except without needing to declare the global.
 *
 * Example: <?php WooCommerce_Cart_Url()->method_name(); ?>
 *
 * @since 1.1.0
 *
 * @return object WooCommerce_Cart_Url class object.
 */
if ( ! function_exists( 'WooCommerce_Cart_Url' ) ) :

 	function WooCommerce_Cart_Url() {
		return WooCommerce_Cart_Url::instance();
	}

endif;

WooCommerce_Cart_Url();
