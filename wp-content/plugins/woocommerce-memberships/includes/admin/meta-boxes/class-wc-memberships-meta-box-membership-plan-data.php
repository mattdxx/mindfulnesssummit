<?php
/**
 * WooCommerce Memberships
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Memberships to newer
 * versions in the future. If you wish to customize WooCommerce Memberships for your
 * needs please refer to http://docs.woothemes.com/document/woocommerce-memberships/ for more information.
 *
 * @package   WC-Memberships/Admin/Meta-Boxes
 * @author    SkyVerge
 * @category  Admin
 * @copyright Copyright (c) 2014-2015, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Membership Plan Data Meta Box
 *
 * @since 1.0.0
 */
class WC_Memberships_Meta_Box_Membership_Plan_Data extends WC_Memberships_Meta_Box {


	/** @var string meta box id **/
	protected $id = 'wc-memberships-membership-plan-data';

	/** @var string meta box priority **/
	protected $priority = 'high';

	/** @var array list of supported screen IDs **/
	protected $screens = array( 'wc_membership_plan' );


	/**
	 * Constructor
	 *
	 * @since 1.0.1
	 * @see WC_Memberships_Meta_Box::__construct()
	 */
	public function __construct() {

		parent::__construct();

		// Add dismissible admin notices for content & product restriction tabs
		wc_memberships()->get_admin_notice_handler()->add_admin_notice(
			sprintf(
				__( 'When you add a restriction rule for content, it will no longer be public on your site. By adding a rule for a page, post, or taxonomy, it will become restricted, and can only be accessed by members of this plan, or by members of another plan that grants access to the content.%sLearn more about %srestriction rules in the documentation%s.', WC_Memberships::TEXT_DOMAIN ),
				'<br /><em>',
				'<a href="http://docs.woothemes.com/document/woocommerce-memberships-restrict-content/">',
				'</a></em>'
			),
			'restrict-content-notice',
			array( 'always_show_on_settings' => false, 'notice_class' => 'updated force-hide js-memberships-restrict-notice js-memberships-restrict-content-notice' )
		);

		wc_memberships()->get_admin_notice_handler()->add_admin_notice(
			sprintf(
				__( 'When you add a %sviewing%s restriction rule for a product, it will no longer be public on your site, and can only be accessed by members of this plan, or by members of another plan that grants access to the product. By adding a %spurchasing%s restriction rule, the product can be viewed publicly, but only purchased by members.', WC_Memberships::TEXT_DOMAIN ),
				'<strong>',
				'</strong>',
				'<strong>',
				'</strong>'
			),
			'restrict-products-notice',
			array( 'always_show_on_settings' => false, 'notice_class' => 'updated force-hide js-memberships-restrict-notice js-memberships-restrict-products-notice' )
		);

		add_action( 'admin_footer',  array( $this, 'render_admin_notice_js' ), 20 );
	}


	/**
	 * Get the meta box title
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_title() {
		return __( 'Membership Plan Data', WC_Memberships::TEXT_DOMAIN );
	}


	/**
	 * Display the membership data meta box
	 *
	 * @param WP_Post $post
	 * @since 1.0.0
	 */
	public function output( WP_Post $post ) {
		global $post;

		$membership_plan = wc_memberships_get_membership_plan( $post );

		// Place post types and taxonomies into separate option groups
		// so that they are easier to distinguish visually.
		$content_restriction_content_type_options = array(
			'post_types' => array(),
			'taxonomies' => array(),
		);


		// We need to prefix post_type/taxonomy names (values), so that
		// if a post type and taxonomy share a name, we can still distinguish
		// between them
		foreach ( wc_memberships()->admin->get_valid_post_types_for_content_restriction() as $post_type_name => $post_type ) {
			$content_restriction_content_type_options['post_types'][ 'post_type|' . $post_type_name ] = $post_type;
		}

		foreach ( wc_memberships()->admin->get_valid_taxonomies_for_content_restriction() as $taxonomy_name => $taxonomy ) {
			$content_restriction_content_type_options['taxonomies'][ 'taxonomy|' . $taxonomy_name ] = $taxonomy;
		}

		// Prepare access_length period toggler options
		$access_length_period_toggler_options = array(
			'unlimited' => __( 'unlimited', WC_Memberships::TEXT_DOMAIN ),
			'specific'  => __( 'specify a length', WC_Memberships::TEXT_DOMAIN ),
		);

		// Prepare access_schedule period toggler options
		$access_schedule_period_toggler_options = array(
			'immediate' => __( 'immediately', WC_Memberships::TEXT_DOMAIN ),
			'specific'  => __( 'specify a time', WC_Memberships::TEXT_DOMAIN ),
		);

		$period_options = array(
			'days'   => __( 'day(s)', WC_Memberships::TEXT_DOMAIN ),
			'weeks'  => __( 'week(s)', WC_Memberships::TEXT_DOMAIN ),
			'months' => __( 'month(s)', WC_Memberships::TEXT_DOMAIN ),
			'years'  => __( 'year(s)', WC_Memberships::TEXT_DOMAIN ),
		);


		// Get applied content restriction rules
		$content_restriction_rules = $membership_plan->get_content_restriction_rules();

		// Add empty option to create a HTML template for new rules
		$content_restriction_rules['__INDEX__'] = new WC_Memberships_Membership_Plan_Rule( array(
			'rule_type'                     => 'content_restriction',
			'membership_plan_id'            => $post->ID,
			'id'                            => '',
			'content_type'                  => '',
			'content_type_name'             => '',
			'object_ids'                    => array(),
			'access_schedule'               => 'immediate',
			'access_schedule_exclude_trial' => 'no',
		));


		// Prepare product content type options
		$post_type_product = get_post_type_object( 'product' );

		$product_restriction_content_type_options = $purchasing_discount_content_type_options = array(
			'post_types' => array(
				'post_type|product' => $post_type_product,
			),
			'taxonomies' => array(),
		);

		// prepare product restriction access_type options
		$product_restriction_access_type_options = array(
			'view'     => __( 'View', WC_Memberships::TEXT_DOMAIN ),
			'purchase' => __( 'Purchase', WC_Memberships::TEXT_DOMAIN ),
		);

		foreach ( wc_memberships()->admin->get_valid_taxonomies_for_product_restriction() as $taxonomy_name => $taxonomy ) {
			$product_restriction_content_type_options['taxonomies'][ 'taxonomy|' . $taxonomy_name ] = $taxonomy;
		}

		foreach ( wc_memberships()->admin->get_valid_taxonomies_for_purchasing_discounts() as $taxonomy_name => $taxonomy ) {
			$purchasing_discount_content_type_options['taxonomies'][ 'taxonomy|' . $taxonomy_name ] = $taxonomy;
		}

		// Get applied product restriction rules
		$product_restriction_rules = $membership_plan->get_product_restriction_rules();

		// Add empty option to create a HTML template for new rules
		$product_restriction_rules['__INDEX__'] = new WC_Memberships_Membership_Plan_Rule( array(
			'rule_type'                     => 'product_restriction',
			'membership_plan_id'            => $post->ID,
			'id'                            => '',
			'content_type'                  => '',
			'content_type_name'             => '',
			'object_ids'                    => array(),
			'access_type'                   => '',
			'access_schedule'               => 'immediate',
			'access_schedule_exclude_trial' => 'no',
		));

		// prepare product restriction access_type options
		$purchasing_discount_type_options = array(
			'percentage' => '%',
			'amount'     => '$',
		);

		// Get applied product restriction rules
		$purchasing_discount_rules = $membership_plan->get_purchasing_discount_rules();

		// Add empty option to create a HTML template for new rules
		$purchasing_discount_rules['__INDEX__'] = new WC_Memberships_Membership_Plan_Rule( array(
			'rule_type'          => 'purchasing_discount',
			'membership_plan_id' => $post->ID,
			'id'                 => '',
			'content_type'       => '',
			'content_type_name'  => '',
			'object_ids'         => array(),
			'discount_type'      => '',
			'discount_amount'    => '',
			'active'             => '',
		));

		?>

		<div class="panel-wrap data">

			<?php if ( ! SV_WC_Plugin_Compatibility::is_wc_version_gte_2_3() ) : ?>
				<div class="wc-tabs-back"></div>
			<?php endif; ?>

			<ul class="membership_plan_data_tabs wc-tabs">
				<?php

					/**
					 * Filter membership plan data tabs
					 *
					 * @since 1.0.0
					 * @param array $tabs Associative array of membership plan tabs
					 */
					$membership_plan_data_tabs = apply_filters( 'wc_membership_plan_data_tabs', array(
						'general' => array(
							'label'  => __( 'General', WC_Memberships::TEXT_DOMAIN ),
							'target' => 'membership-plan-data-general',
							'class'  => array( 'active' ),
						),
						'restrict_content' => array(
							'label'  => __( 'Restrict Content', WC_Memberships::TEXT_DOMAIN ),
							'target' => 'membership-plan-data-restrict-content',
						),
						'restrict_products' => array(
							'label'  => __( 'Restrict Products', WC_Memberships::TEXT_DOMAIN ),
							'target' => 'membership-plan-data-restrict-products',
						),
						'purchasing_discounts' => array(
							'label'  => __( 'Purchasing Discounts', WC_Memberships::TEXT_DOMAIN ),
							'target' => 'membership-plan-data-purchasing-discounts',
						),
					) );

					foreach ( $membership_plan_data_tabs as $key => $tab ) {
						$class = isset( $tab['class'] ) ? $tab['class'] : array();
						?><li class="<?php echo sanitize_html_class( $key ); ?>_options <?php echo sanitize_html_class( $key ); ?>_tab <?php echo implode( ' ' , array_map( 'sanitize_html_class', $class ) ); ?>">
							<a href="#<?php echo esc_attr( $tab['target'] ); ?>"><?php echo esc_html( $tab['label'] ); ?></a>
						</li><?php
					}

					/**
					 * Fires after the membership plan write panel tabs are displayed
					 *
					 * @since 1.0.0
					 */
					do_action( 'wc_membership_plan_write_panel_tabs' );
				?>
			</ul>


			<div id="membership-plan-data-general" class="panel woocommerce_options_panel"><?php

				echo '<div class="options_group">';

					// Slug
					woocommerce_wp_text_input( array( 'id' => 'post_name', 'label' => __( 'Slug', WC_Memberships::TEXT_DOMAIN ), 'value' => $post->post_name ) );

				echo '</div>';

				echo '<div class="options_group">';

				?>
				<p class="form-field"><label for="_product_ids"><?php esc_html_e( 'Grant Access to people who purchase:', WC_Memberships::TEXT_DOMAIN ); ?></label>

				<?php if ( SV_WC_Plugin_Compatibility::is_wc_version_gte_2_3() ) : ?>
					<input type="hidden" id="_product_ids" name="_product_ids" class="js-ajax-select-products" style="width: 50%;" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', WC_Memberships::TEXT_DOMAIN ); ?>" data-multiple="true" data-selected="<?php
						$product_ids = array_filter( array_map( 'absint', (array) get_post_meta( $post->ID, '_product_ids', true ) ) );
						$json_ids    = array();

						foreach ( $product_ids as $product_id ) {
							$product = wc_get_product( $product_id );
							if ( is_object( $product ) ) {
								$json_ids[ $product_id ] = wp_kses_post( html_entity_decode( $product->get_formatted_name() ) );
							}
						}

						echo esc_attr( wc_memberships()->wp_json_encode( $json_ids ) );
					?>" value="<?php echo esc_attr( implode( ',', array_keys( $json_ids ) ) ); ?>" />
				<?php else : ?>
					<select id="_product_ids" id="_product_ids" name="_product_ids[]" class="ajax_chosen_select_products js-ajax-select-products" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', WC_Memberships::TEXT_DOMAIN ); ?>">
						<?php
							if ( $membership_plan->get_product_ids() ) {

								foreach ( $membership_plan->get_product_ids() as $product_id ) {

									$product = wc_get_product( $product_id );

									if ( $product ) {
										echo '<option value="' . esc_attr( $product_id ) . '" selected="selected">' . strip_tags( $product->get_formatted_name() ) . '</option>';
									}
								}
							}
						?>
					</select>
				<?php endif; ?>

				<img class="help_tip" data-tip="<?php esc_attr_e( 'Leave empty to only allow members you manually assign.', WC_Memberships::TEXT_DOMAIN ) ?>" src="<?php echo esc_url( WC()->plugin_url() ); ?>/assets/images/help.png" height="16" width="16" />

				</p>

				<p class="form-field plan-access-length-field">
					<label for="_access_length"><?php esc_html_e( 'Membership Length', WC_Memberships::TEXT_DOMAIN ); ?></label>

					<span class="plan-access-length-selectors">
						<?php $current_access_length = $membership_plan->get_access_length() ? 'specific' : 'unlimited'; ?>
						<?php foreach ( $access_length_period_toggler_options as $value => $label ) : ?>
							<label class="label-radio">
								<input type="radio" name="_access_length" class="js-access-length-period-selector js-access-length-type" value="<?php echo esc_attr( $value ); ?>" <?php checked( $value, $current_access_length ); ?> />
								<?php echo esc_html( $label ); ?>
							</label>
						<?php endforeach; ?>
						<img class="help_tip" data-tip="<?php esc_attr_e( 'When does the membership expire?', WC_Memberships::TEXT_DOMAIN ) ?>" src="<?php echo esc_url( WC()->plugin_url() ); ?>/assets/images/help.png" height="16" width="16" />
					</span>

					<span class="plan-access-length-specific js-hide-if-access-length-unlimited <?php if ( ! $membership_plan->get_access_length() ) : ?>hide<?php endif;?>">

						<input type="number" min="0" name="_access_length_amount" id="_access_length_amount" value="<?php echo esc_attr( $membership_plan->get_access_length_amount() ); ?>" class="access_length-amount" />

						<select name="_access_length_period" id="_access_length_period" class="short access_length-period js-access-length-period-selector">
							<?php foreach ( $period_options as $key => $label ) : ?>
								<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $membership_plan->get_access_length_period() ); ?>><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>

					</span>

				</p>

				<?php

				echo '</div>';

				/**
				 * Fires after the membership plan general data panel is displayed
				 *
				 * @since 1.0.0
				 */
				do_action( 'wc_membership_plan_options_membership_plan_data_general' );
				?>
			</div><!-- //#membership-plan-data-general -->


			<div id="membership-plan-data-restrict-content" class="panel woocommerce_options_panel">

				<div class="table-wrap">
					<?php require( wc_memberships()->get_plugin_path() . '/includes/admin/meta-boxes/views/html-content-restriction-rules.php' ); ?>
				</div>

				<?php if ( $public_posts = wc_memberships()->rules->get_public_posts() ) : ?>
					<p><?php printf( __( 'These posts are public, and will be excluded from all restriction rules: %s', WC_Memberships::TEXT_DOMAIN ), wc_memberships()->admin_list_post_links( $public_posts ) ); ?></p>
				<?php endif; ?>

				<?php
					/**
					 * Fires after the membership plan content restriction panel is displayed
					 *
					 * @since 1.0.0
					 */
					do_action( 'wc_membership_plan_options_membership_plan_data_restrict_content' );
				?>
			</div><!-- //#membership-plan-data-restrict-content -->


			<div id="membership-plan-data-restrict-products" class="panel woocommerce_options_panel">

				<div class="table-wrap">
					<?php require( wc_memberships()->get_plugin_path() . '/includes/admin/meta-boxes/views/html-product-restriction-rules.php' ); ?>
				</div>

				<?php if ( $public_products = wc_memberships()->rules->get_public_products() ) : ?>
					<p><?php printf( __( 'These products are public, and will be excluded from all restriction rules: %s', WC_Memberships::TEXT_DOMAIN ), wc_memberships()->admin_list_post_links( $public_products ) ); ?></p>
				<?php endif; ?>

				<?php
					/**
					 * Fires after the membership plan product restriction panel is displayed
					 *
					 * @since 1.0.0
					 */
					do_action( 'wc_membership_plan_options_membership_plan_data_restrict_products' );
				?>
			</div><!-- //#membership-plan-data-restrict-products -->


			<div id="membership-plan-data-purchasing-discounts" class="panel woocommerce_options_panel">

				<div class="table-wrap">
					<?php require( wc_memberships()->get_plugin_path() . '/includes/admin/meta-boxes/views/html-purchasing-discount-rules.php' ); ?>
				</div>

				<?php
					/**
					 * Fires after the membership plan purchasing discounts panel is displayed
					 *
					 * @since 1.0.0
					 */
					do_action( 'wc_membership_plan_options_membership_plan_data_purchasing_discounts' );
				?>
			</div><!-- //#membership-plan-data-purchase-discounts -->

			<?php
				/**
				 * Fires after the membership plan data panels are displayed
				 *
				 * @since 1.0.0
				 */
				do_action( 'wc_membership_plan_data_panels' );
			?>

			<div class="clear"></div>

		</div><!-- //.panel-wrap -->

		<?php
	}


	/**
	 * Save membership plan data
	 *
	 * @since 1.0.0
	 * @param int $post_id
	 * @param WP_Post $post
	 */
	public function update_data( $post_id, WP_Post $post ) {

		// Save product IDs that grant access to this membership
		if ( isset( $_POST['_product_ids'] ) ) {

			$product_ids = is_array( $_POST['_product_ids'] ) ? $_POST['_product_ids'] : ( $_POST['_product_ids'] ? explode( ',', $_POST['_product_ids'] ) : array() );

			// sanitize
			$product_ids = array_map( 'absint', $product_ids );

			update_post_meta( $post_id, '_product_ids', $product_ids );
		}

		// Save membership length
		if ( isset( $_POST['_access_length'] ) ) {
			if ( 'specific' == $_POST['_access_length'] ) {
				update_post_meta( $post_id, '_access_length', sprintf( '%d %s', $_POST['_access_length_amount'], $_POST['_access_length_period'] ) );
			} else {
				delete_post_meta( $post_id, '_access_length' );
			}
		}

		// Save access from subscription
		update_post_meta( $post_id, '_access_while_subscription_active', isset( $_POST['_access_while_subscription_active'] ) ? 'yes' : 'no' );

		// Update restriction & discount rules
		wc_memberships()->admin->update_rules( $post_id, array( 'content_restriction', 'product_restriction', 'purchasing_discount' ), 'plan' );
	}


	/**
	 * Render admin notice JS
	 *
	 * @since 1.0.1
	 */
	public function render_admin_notice_js() {

		ob_start();
		?>

		// remove force-hide class (which prevents message flicker on page load)
		// and simply hide the hidden notices
		$( '.js-wc-plugin-framework-admin-notice.force-hide' ).removeClass('force-hide').hide();

		<?php
		$javascript = ob_get_clean();

		wc_enqueue_js( $javascript );
	}

}
