<?php
/**
 * WCOPC_Admin_Editor class.
 *
 * @since 2.0
 */
class WCOPC_Admin_Editor {

	/**
	 * Constructor
	 */
	public function __construct() {

		add_action( 'admin_head', array( $this, 'add_shortcode_button' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 99 );
		add_filter( 'tiny_mce_version', array( $this, 'refresh_mce' ), 20 );
		add_filter( 'mce_external_languages', array( $this, 'add_tinymce_lang' ), 20, 1 );

		add_action( 'wp_ajax_one_page_checkout_shortcode_iframe', array( $this, 'one_page_checkout_shortcode_iframe' ), 9 );

	}

	/**
	 * Add a button for the OPC shortcode to the WP editor.
	 */
	public function add_shortcode_button() {

		$screen = get_current_screen();

		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) || $screen->post_type == 'product' ) {
			return;
		}

		if ( 'true' == get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', array( $this, 'add_shortcode_tinymce_plugin' ), 20 );
			add_filter( 'mce_buttons', array( $this, 'register_shortcode_button' ), 20 );
		}
	}

	/**
	 * Enqueue scripts
	 */
	public static function enqueue_scripts() {

		global $pagenow, $typenow;

		/**
		 * Enqueue on post edit screens for all post types
		 */
		if ( $pagenow=='post-new.php' OR $pagenow=='post.php' ) {

			wp_enqueue_script( 'iframe-resizer', PP_One_Page_Checkout::$plugin_url . '/js/admin/iframeResizer.min.js', array(), '2.8.5' );

		}

	}

	/**
	 * woocommerce_add_tinymce_lang function.
	 *
	 * @param array $arr
	 * @return array
	 */
	public function add_tinymce_lang( $arr ) {
		$arr['wcopc_shortcode_button'] = PP_One_Page_Checkout::$plugin_path . '/js/admin/editor_plugin_lang.php';
		return $arr;
	}

	/**
	 * Register the shortcode button.
	 *
	 * @param array $buttons
	 * @return array
	 */
	public function register_shortcode_button( $buttons ) {
		array_push( $buttons, '|', 'wcopc_shortcode_button' );
		return $buttons;
	}

	/**
	 * Add the shortcode button to TinyMCE
	 *
	 * @param array $plugin_array
	 * @return array
	 */
	public function add_shortcode_tinymce_plugin( $plugin_array ) {
		$wp_version = get_bloginfo( 'version' );
		$suffix     = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$plugin_array['wcopc_shortcode_button'] = PP_One_Page_Checkout::$plugin_url . '/js/admin/editor_plugin.js';

		return $plugin_array;
	}

	/**
	 * Force TinyMCE to refresh.
	 *
	 * @param int $ver
	 * @return int
	 */
	public function refresh_mce( $ver ) {
		$ver += 3;
		return $ver;
	}


	/**
	 * Display the contents of the iframe used when the One Page Checkout
	 * TinyMCE button is clicked.
	 *
	 * @param int $ver
	 * @return int
	 */
	public static function one_page_checkout_shortcode_iframe() {
		global $wp_scripts;

		set_current_screen( 'wcopc' );

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.9.2';

		wp_enqueue_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
		wp_enqueue_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/' . $jquery_version . '/themes/smoothness/jquery-ui.css', array(), WC_VERSION );

		wp_enqueue_script( 'jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip' . $suffix . '.js', array( 'jquery' ), WC_VERSION, true );
		wp_enqueue_script( 'wcopc_iframeresizer_contentwindow', PP_One_Page_Checkout::$plugin_url . '/js/admin/iframeResizer.contentWindow.min.js', array(), '2.8.5' );

		if ( PP_One_Page_Checkout::is_woocommerce_pre( '2.3' ) ) {
			// Chosen is @deprecated (2.3) in favour of select2
			wp_enqueue_script( 'chosen', WC()->plugin_url() . '/assets/js/chosen/chosen.jquery' . $suffix . '.js', array( 'jquery' ), WC_VERSION );
			wp_enqueue_script( 'ajax-chosen', WC()->plugin_url() . '/assets/js/chosen/ajax-chosen.jquery' . $suffix . '.js', array( 'jquery', 'chosen' ), WC_VERSION );
			wp_enqueue_script( 'wcopc_tinymce_dialog', PP_One_Page_Checkout::$plugin_url . '/js/admin/deprecated/one-page-checkout-iframe.js', array( 'ajax-chosen','jquery-ui-datepicker', 'jquery-ui-sortable', 'wcopc_iframeresizer_contentwindow', 'jquery-tiptip' ), WC_VERSION );
			$params = array(
				'search_products_nonce' => wp_create_nonce( 'search-products' ),
			);
			wp_localize_script( 'wcopc_tinymce_dialog', 'wcopc', $params );
		} else {

			// Init the WooCommerce scripts as these aren't attached on iframe pages
			$admin_assets = new WC_Admin_Assets();
			$admin_assets->admin_scripts();

			wp_enqueue_script( 'wcopc_tinymce_dialog', PP_One_Page_Checkout::$plugin_url . '/js/admin/one-page-checkout-iframe.js', array( 'wc-enhanced-select', 'jquery-ui-datepicker', 'jquery-ui-sortable', 'wcopc_iframeresizer_contentwindow', 'jquery-tiptip' ), WC_VERSION );
		}
		iframe_header(); ?>
<style>
/* Make sure select box doesn't extend below iframe */
.select2-results {
	max-height: 150px;
}
@media screen and (max-width: 782px) {
	/* Fix engorged radio buttons */
	#wcopc_settings input[type="radio"], input[type="checkbox"] {
		width: 16px;
		height: 16px;
	}
	#wcopc_settings input[type="radio"]:checked:before {
		width: 6px;
		height: 6px;
		margin: 4px;
	}
}
/* Enlarge Woo's tiny tooltips */
#tiptip_content {
	min-width: 260px;
}
</style>
<div class="wrap" style="margin: 1em;">
<form id="wcopc_settings" style="float: left; width: 100%;">
	<?php do_action( 'wcopc_shortcode_iframe_before' ); ?>
	<fieldset id="wcopc_product_ids_fields" style="margin: 1em 0;">
		<label for="wcopc_product_ids" style="width: 70px; display: inline-block;"><strong><?php _e( 'Products:', 'wcopc' ); ?></strong></label>
		<?php if ( PP_One_Page_Checkout::is_woocommerce_pre( '2.3' ) ) { ?>
		<select id="wcopc_product_ids" name="wcopc_product_ids[]" class="ajax_chosen_select_products" multiple="multiple" data-placeholder="<?php _e( 'Search for a product&hellip;', 'wcopc' ); ?>" style="width: 75%;"></select>
		<?php } else { ?>
		<input type="hidden" id="wcopc_product_ids" name="wcopc_product_ids[]" data-multiple="true" class="wc-product-search" data-placeholder="<?php _e( 'Search for a product&hellip;', 'wcopc' ); ?>" style="width: 75%;"/>
		<?php } ?>
	</fieldset>
	<fieldset id="wcopc_template_fields" style="margin: 1em 0;">
		<div style="font-weight: bold;"><?php _e( 'Template:', 'wcopc' ); ?></div>
		<?php $first = true; ?>
		<?php foreach( PP_One_Page_Checkout::$templates as $id => $template_details ) : ?>
		<label for="<?php echo esc_html( $id ); ?>" style="width: 75%; display: inline-block;">
			<input id="<?php echo esc_html( $id ); ?>" name="wcopc_template" type="radio" value="<?php echo $id; ?>" style="width: 16px; height: 16px;" <?php checked( $first ); $first = false; ?>>
			<?php echo esc_html( $template_details['label'] ); ?>
			<?php if ( ! empty( $template_details['description'] ) ) : ?>
			<img data-tip="<?php echo esc_attr( $template_details['description'] ); ?>" class="help_tip" src="<?php echo WC()->plugin_url() . '/assets/images/help.png'; ?>" height="16" width="16">
			<?php endif; ?>
		</label>
		<?php endforeach; ?>
	</fieldset>
	<?php do_action( 'wcopc_shortcode_iframe_after' ); ?>
	<fieldset style="margin: 1em 0;">
		<input id="wcopc_submit" type="submit" class="button-primary" value="<?php _e( 'Create Shortcode', 'wcopc' ); ?>" />
		<input id="wcopc_cancel" type="button" class="button" value="<?php _e( 'Cancel', 'wcopc' ); ?>" />
	</fieldset>
</form>
</div>
<?php
		iframe_footer();
		exit();
	}
}

new WCOPC_Admin_Editor();
