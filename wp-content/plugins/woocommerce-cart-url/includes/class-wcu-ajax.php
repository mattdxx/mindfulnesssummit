<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WCU_Ajax.
 *
 * Initialize the WCU Ajax post type.
 *
 * @class		WCU_Ajax
 * @author		Shop Plugins
 * @package		WooCommerce Cart URL
 * @version		1.0.0
 */
class WCU_Ajax {

	/**
	 * Constructor.
	 *
	 * Initialize class by add hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Add elements
		add_action( 'wp_ajax_wcu_add_product', array( $this, 'add_product' ) );
		add_action( 'wp_ajax_wcu_remove_product', array( $this, 'remove_product' ) );

	}


	/**
	 * Add Product.
	 *
	 * Add a product to the post, include extra data if its a variation. Default quantity is 1.
	 *
	 * @since 1.0.0
	 */
	public function add_product() {

		$products = get_post_meta( absint( $_POST['post_id'] ), '_products', true );

		if ( 'product' == get_post_type( absint( $_POST['item_to_add'] ) ) ) :

			$products[ absint( $_POST['item_to_add'] ) ] = array(
				'quantity' => '1'
			);

		elseif ( 'product_variation' == get_post_type( absint( $_POST['item_to_add'] ) ) ) :

			$variation = new WC_Product_Variation( absint( $_POST['item_to_add'] ) );
			$products[ absint( $_POST['item_to_add'] ) ] = array(
				'variation_id' 		=> absint( $_POST['item_to_add'] ),
				'variation_data'	=> $variation->get_variation_attributes(),
				'quantity'			=> '1'
			);

		endif;

		update_post_meta( absint( $_POST['post_id'] ), '_products', $products );

		// Variables for table row.
		$product = array( 'quantity' => '1' );
		$product_id = absint( $_POST['item_to_add'] );

		// Table row.
		require plugin_dir_path( __FILE__ ) . 'meta-boxes/views/html-order-item.php';

		die();

	}


	/**
	 * Remove Product.
	 *
	 * Remove products from product list.
	 *
	 * @since 1.0.0
	 */
	public function remove_product() {

		$products = get_post_meta( absint( $_POST['post_id'] ), '_products', true );
		$remove_ids = array_map( 'absint', $_POST['remove_ids'] );

		foreach ( $remove_ids as $remove_id ) :

			unset( $products[ $remove_id ] );

		endforeach;

		update_post_meta( absint( $_POST['post_id'] ), '_products', $products );

		die();

	}


}
