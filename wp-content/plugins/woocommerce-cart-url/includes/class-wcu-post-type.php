<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *	Class WCU_Post_Type.
 *
 *	Initialize and set up cart_url post type.
 *
 *	@class       WCU_Post_Type
 *	@version     1.0.0
 *	@author      Shop Plugins
 */
class WCU_Post_Type {


	/**
	 * Constructor.
	 *
	 * Initialize this class including hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Register post type
		add_action( 'init', array( $this, 'register_post_type' ) );

		// Update post type messages
		add_filter( 'post_updated_messages', array( $this, 'cart_url_messages' ) );

		// Save post meta
		 add_action( 'save_post', array( $this, 'save_product_meta' ) );

		// Meta boxes
		add_action( 'add_meta_boxes', array( $this, 'product_meta_box' ) );

		// Redirect after delete
		add_action('load-edit.php', array( $this, 'redirect_after_trash' ) );
		// Notice after redirect
		add_action( 'admin_notices', array( $this, 'trash_redirect_notice' ) );

		// Redirect permalink to add_to_cart url
		add_action( 'template_redirect', array( $this, 'permalink_redirect' ) );

	}


	/**
	 * Post type.
	 *
	 * Register post type.
	 *
	 * @since 1.0.0
	 */
	public function register_post_type() {

		$labels = array(
			'name'               => __( 'Cart URL', 'woocommerce-cart-url' ),
			'singular_name'      => __( 'Cart URL', 'woocommerce-cart-url' ),
			'menu_name'          => __( 'Cart URLs', 'woocommerce-cart-url' ),
			'name_admin_bar'     => __( 'Cart URL', 'woocommerce-cart-url' ),
			'add_new'            => __( 'Add New', 'woocommerce-cart-url' ),
			'add_new_item'       => __( 'Add New Cart URL', 'woocommerce-cart-url' ),
			'new_item'           => __( 'New Cart URL', 'woocommerce-cart-url' ),
			'edit_item'          => __( 'Edit Cart URL', 'woocommerce-cart-url' ),
			'view_item'          => __( 'View Cart URL', 'woocommerce-cart-url' ),
			'all_items'          => __( 'All Cart URLs', 'woocommerce-cart-url' ),
			'search_items'       => __( 'Search Cart URLs', 'woocommerce-cart-url' ),
			'parent_item_colon'  => __( 'Parent Cart URL:', 'woocommerce-cart-url' ),
			'not_found'          => __( 'No Cart URLs found.', 'woocommerce-cart-url' ),
			'not_found_in_trash' => __( 'No Cart URLs found in Trash.', 'woocommerce-cart-url' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'add-to-cart' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title' )
		);

		register_post_type( 'cart_url', $args );

	}


	/**
	 * Admin messages.
	 *
	 * Custom admin messages when using cart_url post type.
	 *
	 * @since 1.0.0
	 *
	 * @param $messages
	 * @return array Full list of all messages.
	 */
	public function cart_url_messages( $messages ) {

		$post             = get_post();
		$post_type        = get_post_type( $post );
		$post_type_object = get_post_type_object( $post_type );

		$messages['cart_url'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Cart URL updated.', 'woocommerce-cart-url' ),
			2  => __( 'Custom field updated.', 'woocommerce-cart-url' ),
			3  => __( 'Custom field deleted.', 'woocommerce-cart-url' ),
			4  => __( 'Cart URL updated.', 'woocommerce-cart-url' ),
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Cart URL restored to revision from %s', 'woocommerce-cart-url' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Cart URL published.', 'woocommerce-cart-url' ),
			7  => __( 'Cart URL saved.', 'woocommerce-cart-url' ),
			8  => __( 'Cart URL submitted.', 'woocommerce-cart-url' ),
			9  => sprintf(
				__( 'Cart URL scheduled for: <strong>%1$s</strong>.', 'woocommerce-cart-url' ),
				date_i18n( __( 'M j, Y @ G:i', 'woocommerce-cart-url' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Cart URL draft updated.', 'woocommerce-cart-url' )
		);

		if ( $post_type_object->publicly_queryable && 'cart_url' == $post_type  ) {
			$permalink = admin_url( '/admin.php?page=wc-settings&tab=cart_urls' );

			$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View Cart URL overview', 'woocommerce-cart-url' ) );
			$messages[ $post_type ][1] .= $view_link;
			$messages[ $post_type ][6] .= $view_link;
			$messages[ $post_type ][9] .= $view_link;
			$messages[ $post_type ][8]  .= $view_link;
			$messages[ $post_type ][10] .= $view_link;
		}

		return $messages;

	}


	/**
	 * Meta boxes.
	 *
	 * Add an meta box to add products/variations and a meta box to display the URL.
	 *
	 * @since 1.0.0
	 */
	public function product_meta_box() {

		// Products meta box
		add_meta_box( 'wcu-products', __( 'Products', 'woocommerce-cart-url' ), array( $this, 'render_product_meta_box' ), 'cart_url', 'normal' );

		// Cart url meta box
		add_meta_box( 'woocommerce-cart-url', __( 'Cart URL', 'woocommerce-cart-url' ), array( $this, 'render_cart_url_meta_box' ), 'cart_url', 'normal', 'high' );

	}


	/**
	 * Render product meta box.
	 *
	 * Include and render the products meta box contents.
	 *
	 * @since 1.0.0
	 */
	public function render_product_meta_box() {

		/**
		 * Products meta box content.
		 */
		require_once plugin_dir_path( __FILE__ ) . '/meta-boxes/wcu-cart-url-products.php';

	}


	/**
	 * Render product meta box.
	 *
	 * Include and render the products meta box contents.
	 *
	 * @since 1.0.0
	 */
	public function render_cart_url_meta_box() {

		global $post;

		?><p>
			<label><?php _e( 'You can share this url', 'edd-cart-url' );
				?><input type='text' value='<?php echo esc_attr( WooCommerce_Cart_Url()->generate_url( $post->ID ) ); ?>' readonly style='width: 100%;'>
			</label>
		</p>

		<p>
			<label><?php
			$clear_cart_checked = 'no' == get_post_meta( $post->ID, '_clear_cart', true ) ? 'no' : 'yes';
			?><input type='checkbox' name='clear_cart' value='1' <?php checked( 'yes', $clear_cart_checked ); ?>><?php _e( 'Clear cart when visiting this url', 'woocommerce-cart-url' ); ?></label>
		</p>

		<p>
			<label><?php _e( 'Redirect to', 'woocommerce-cart-url' ); ?><br/><?php
			$redirect = get_post_meta( $post->ID, '_redirect', true );
			$redirect = empty( $redirect ) ? wc_get_page_id( 'cart' ) : $redirect;

			wp_dropdown_pages( array(
				'selected'	=> $redirect,
				'name'		=> 'redirect',
			) );

			?></label>
		</p><?php

	}


	/**
	 * Save meta box.
	 *
	 * Validate and save post meta from meta box.
	 *
	 * @param integer $post_id
	 * @return null
	 */
	public function save_product_meta( $post_id ) {

		if ( ! isset( $_POST['cart_url_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['cart_url_meta_box_nonce'], 'cart_url_meta_box' ) )
			return $post_id;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

		if ( ! current_user_can( 'manage_woocommerce' ) )
			return $post_id;


		$products = (array) get_post_meta( $post_id, '_products', true );

		foreach ( $_POST['product'] as $key => $product ) :

			if ( ! empty( $product['quantity'] ) ) :
				$products[ $key ]['quantity'] = $product['quantity'];
			endif;

		endforeach;

		update_post_meta( $post_id, '_products', $products );

		$clear_cart = isset( $_POST['clear_cart'] ) ? 'yes' : 'no';
		update_post_meta( $post_id, '_clear_cart', $clear_cart );

		update_post_meta( $post_id, '_redirect', sanitize_key( $_POST['redirect'] ) );

	}


	/**
	 * Redirect trash.
	 *
	 * Create a redirect to the settings page after an Cart URL post has been deleted.
	 *
	 * @since 1.0.0
	 */
	public function redirect_after_trash() {

		$screen = get_current_screen();

		if ( 'edit-cart_url' == $screen->id ) :

			if ( isset( $_GET['trashed'] ) &&  intval( $_GET['trashed'] ) > 0 ) :

				$redirect = admin_url( '/admin.php?page=wc-settings&tab=cart_urls&trashed=1&ids=' . $_GET['ids'] );
				wp_redirect( $redirect );
				exit();

			endif;

		endif;

	}


	/**
	 * Trash message.
	 *
	 * Show a notice message when a post is trashed.
	 *
	 * @since 1.0.0
	 */
	public function trash_redirect_notice() {

		if ( isset( $_GET['page'] ) &&
			$_GET['page'] == 'wc-settings' &&
			isset( $_GET['tab'] ) &&
			$_GET['tab'] == 'cart_urls' &&
			isset ( $_GET['trashed'] ) &&
			$_GET['trashed'] == '1' &&
			isset( $_GET['ids'] ) ) :

			$ids = $_GET['ids'];
			$undo = '<a href="' . esc_url( wp_nonce_url( "edit.php?post_type=cart_url&doaction=undo&action=untrash&ids=$ids", "bulk-posts" ) ) . '">' . __('Undo') . '</a>';
			?><div class="updated">
	        	<p><?php echo __( 'Cart URL trashed.', 'woocommerce-cart-url' ) . ' ' . $undo; ?></p>
	        </div><?php

        endif;

	}


	/**
	 * Permalink redirect.
	 *
	 * Redirect to proper Cart URL when current post type is cart_url.
	 *
	 * @since 1.0.0
	 *
	 * @global Object $wp_query Query object to retreive Post ID.
	 */
	public function permalink_redirect() {

		global $wp_query;

		if ( isset( $wp_query->query['post_type'] ) && 'cart_url' == $wp_query->query['post_type'] ) :
			wp_redirect( WooCommerce_Cart_Url()->generate_url( $wp_query->posts[0]->ID ) );
			exit();
		endif;

	}


}
