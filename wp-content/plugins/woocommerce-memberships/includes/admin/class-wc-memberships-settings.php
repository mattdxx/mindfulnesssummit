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
 * @package   WC-Memberships/Admin
 * @author    SkyVerge
 * @category  Admin
 * @copyright Copyright (c) 2014-2015, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Settings_Memberships' ) ) :

/**
 * Settings class
 *
 * @since 1.0.0
 */
class WC_Settings_Memberships extends WC_Settings_Page {


	/**
	 * Setup settings class
	 *
	 * @since  1.0
	 */
	public function __construct() {

		$this->id    = 'memberships';
		$this->label = __( 'Memberships', WC_Memberships::TEXT_DOMAIN );

		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 20 );
		add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
		add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
	}


	/**
	 * Get sections
	 *
	 * @return array
	 */
	public function get_sections() {

		$sections = array(
			''         => __( 'General', WC_Memberships::TEXT_DOMAIN ),
			'products' => __( 'Products', WC_Memberships::TEXT_DOMAIN )
		);

		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}


	/**
	 * Get settings array
	 *
	 * @since 1.0.0
	 * @param string $current_section Optional. Defaults to empty string.
	 * @return array Array of settings
	 */
	public function get_settings( $current_section = '' ) {

		if ( 'products' == $current_section ) {

			/**
			 * Filter Memberships products Settings
			 *
			 * @since 1.0.0
			 * @param array $settings Array of the plugin settings
			 */
			$settings = apply_filters( 'wc_memberships_products_settings', array(

				array(
					'name' => __( 'Products', WC_Memberships::TEXT_DOMAIN ),
					'type' => 'title',
					'desc' => '',
					'id'   => 'memberships_products_options',
				),

				array(
					'type'     => 'checkbox',
					'id'       => 'wc_memberships_hide_restricted_products',
					'name'     => __( 'Hide restricted products', WC_Memberships::TEXT_DOMAIN ),
					'desc'     => __( 'If enabled, products with viewing restricted will be hidden from the shop catalog. Products will still be accessible directly, unless Content Restriction Mode is "Hide completely".', WC_Memberships::TEXT_DOMAIN ),
					'default'  => 'no',
				),

				array(
					'type' => 'sectionend',
					'id'   => 'memberships_products_options'
				),

				array(
					'name' => __( 'Product Restriction Messages', WC_Memberships::TEXT_DOMAIN ),
					'type' => 'title',
					'desc' =>  sprintf( __( '%s automatically inserts the product(s) needed to gain access. %s inserts the URL to my account page with the login form. HTML is allowed.', WC_Memberships::TEXT_DOMAIN ), '<code>{products}</code>', '<code>{login_url}</code>' ),
					'id'   => 'memberships_product_messages',
				),

				array(
					'type'          => 'textarea',
					'id'            => 'wc_memberships_product_viewing_restricted_message',
					'class'         => 'input-text wide-input',
					'name'          => __( 'Product Viewing Restricted - Purchase Required', WC_Memberships::TEXT_DOMAIN ),
					'desc'          => __( 'Displays when purchase is required to view the product.', WC_Memberships::TEXT_DOMAIN ),
					'default'       => __( 'This product can only be viewed by members. To view or purchase this product, sign up by purchasing {products}.', WC_Memberships::TEXT_DOMAIN ),
					'desc_tip'      => __( 'Message displayed if viewing is restricted to members but access can be purchased.', WC_Memberships::TEXT_DOMAIN ),
				),

				array(
					'type'          => 'textarea',
					'id'            => 'wc_memberships_product_viewing_restricted_message_no_products',
					'class'         => 'input-text wide-input',
					'name'          => __( 'Product Viewing Restricted - Membership Required', WC_Memberships::TEXT_DOMAIN ),
					'desc'          => __( 'Displays if viewing is restricted to a membership that cannot be purchased.', WC_Memberships::TEXT_DOMAIN ),
					'default'       => __( 'This product can only be viewed by members.', WC_Memberships::TEXT_DOMAIN ),
					'desc_tip'      => __( 'Message displayed if viewing is restricted to members and no products can grant access.', WC_Memberships::TEXT_DOMAIN ),
				),

				array(
					'type'          => 'textarea',
					'id'            => 'wc_memberships_product_purchasing_restricted_message',
					'class'         => 'input-text wide-input',
					'name'          => __( 'Product Buying Restricted - Purchase Required', WC_Memberships::TEXT_DOMAIN ),
					'desc'          => __( 'Displays when purchase is required to buy the product.', WC_Memberships::TEXT_DOMAIN ),
					'default'       => __( 'This product can only be purchased by members. To purchase this product, sign up by purchasing {products}.', WC_Memberships::TEXT_DOMAIN ),
					'desc_tip'      => __( 'Message displayed if purchasing is restricted to members but access can be purchased.', WC_Memberships::TEXT_DOMAIN ),
				),

				array(
					'type'          => 'textarea',
					'id'            => 'wc_memberships_product_purchasing_restricted_message_no_products',
					'class'         => 'input-text wide-input',
					'name'          => __( 'Product Buying Restricted - Membership Required', WC_Memberships::TEXT_DOMAIN ),
					'desc'          => __( 'Displays if purchasing is restricted to a membership that cannot be purchased.', WC_Memberships::TEXT_DOMAIN ),
					'default'       => __( 'This product can only be purchased by members.', WC_Memberships::TEXT_DOMAIN ),
					'desc_tip'      => __( 'Message displayed if purchasing is restricted to members and no products can grant access.', WC_Memberships::TEXT_DOMAIN ),
				),

				array(
					'type'          => 'textarea',
					'id'            => 'wc_memberships_product_discount_message',
					'class'         => 'input-text wide-input',
					'name'          => __( 'Product Discounted - Purchase Required', WC_Memberships::TEXT_DOMAIN ),
					'desc'          => __( 'Message displayed to non-members if the product has a member discount.', WC_Memberships::TEXT_DOMAIN ),
					'default'       => __( 'Want a discount? Become a member by purchasing {products}.', WC_Memberships::TEXT_DOMAIN ),
					'desc_tip'      => __( 'Displays below add to cart buttons. Leave blank to disable.', WC_Memberships::TEXT_DOMAIN ),
				),

				array(
					'type'          => 'textarea',
					'id'            => 'wc_memberships_product_discount_message_no_products',
					'class'         => 'input-text wide-input',
					'name'          => __( 'Product Discounted - Membership Required', WC_Memberships::TEXT_DOMAIN ),
					'desc'          => __( 'Message displayed to non-members if the product has a member discount, but no products can grant access.', WC_Memberships::TEXT_DOMAIN ),
					'default'       => __( 'Want a discount? Become a member.', WC_Memberships::TEXT_DOMAIN ),
					'desc_tip'      => __( 'Displays below add to cart buttons. Leave blank to disable.', WC_Memberships::TEXT_DOMAIN ),
				),

				array(
					'type' => 'sectionend',
					'id'   => 'memberships_product_messages'
				),

			) );

		}

		else {

			/**
			 * Filter Memberships general Settings
			 *
			 * @since 1.0.0
			 * @param array $settings Array of the plugin settings
			 */
			$settings = apply_filters( 'wc_memberships_general_settings', array(

				array(
					'name' => __( 'General', WC_Memberships::TEXT_DOMAIN ),
					'type' => 'title',
					'desc' => '',
					'id'   => 'memberships_options',
				),

				array(
					'type'     => 'select',
					'id'       => 'wc_memberships_restriction_mode',
					'name'     => __( 'Content Restriction Mode', WC_Memberships::TEXT_DOMAIN ),
					'options'  => array(
						'hide'         => __( 'Hide completely', WC_Memberships::TEXT_DOMAIN ),
						'hide_content' => __( 'Hide content only', WC_Memberships::TEXT_DOMAIN ),
						'redirect'     => __( 'Redirect to page', WC_Memberships::TEXT_DOMAIN ),
					),
					'class'    => SV_WC_Plugin_Compatibility::is_wc_version_gte_2_3() ? 'wc-enhanced-select' : 'wc-memberships-chosen_select',
					'desc_tip' => __( 'Specifies the way content is restricted: whether to show nothing, excerpts, or send to a landing page.', WC_Memberships::TEXT_DOMAIN ),
					'desc'     => __( '"Hide completely" removes all traces of content for non-members and search engines and 404s restricted pages.<br />"Hide content only" will show items in archives, but protect page or post content and comments.', WC_Memberships::TEXT_DOMAIN ),
					'default'  => 'hide_content',
				),

				array(
					'title'    => __( 'Redirect Page', WC_Memberships::TEXT_DOMAIN ),
					'desc'     => __( 'Select the page to redirect non-members to - should contain the [wcm_content_restricted] shortcode.', WC_Memberships::TEXT_DOMAIN ),
					'id'       => 'wc_memberships_redirect_page_id',
					'type'     => 'single_select_page',
					'class'    => SV_WC_Plugin_Compatibility::is_wc_version_gte_2_3() ? 'wc-enhanced-select-nostd js-redirect-page' : 'wc-memberships-chosen_select_nostd js-redirect-page',
					'css'      => 'min-width:300px;',
					'desc_tip' => true,
				),

				array(
					'type'     => 'checkbox',
					'id'       => 'wc_memberships_show_excerpts',
					'name'     => __( 'Show Excerpts', WC_Memberships::TEXT_DOMAIN ),
					'desc'     => __( 'If enabled, an excerpt of the protected content will be displayed to non-members & search engines.', WC_Memberships::TEXT_DOMAIN ),
					'default'  => 'yes',
				),

				array(
					'type'     => 'select',
					'id'       => 'wc_memberships_display_member_login_notice',
					'name'     => __( 'Show Member Login Notice', WC_Memberships::TEXT_DOMAIN ),
					'options'  => array(
						'never'    => __( 'Never', WC_Memberships::TEXT_DOMAIN ),
						'cart'     => __( 'On Cart Page', WC_Memberships::TEXT_DOMAIN ),
						'checkout' => __( 'On Checkout Page', WC_Memberships::TEXT_DOMAIN ),
						'both'     => __( 'On both Cart & Checkout Page', WC_Memberships::TEXT_DOMAIN ),
					),
					'class'    => SV_WC_Plugin_Compatibility::is_wc_version_gte_2_3() ? 'wc-enhanced-select' : 'wc-memberships-chosen_select',
					'desc_tip' => __( 'Select when & where to display login reminder notice for guests if products in cart have member discounts.', WC_Memberships::TEXT_DOMAIN ),
					'default'  => 'both',
				),

				array(
					'type' => 'sectionend',
					'id'   => 'memberships_options'
				),

				array(
					'title'         => __( 'Content Restricted Messages', WC_Memberships::TEXT_DOMAIN ),
					'type'          => 'title',
					'desc'          =>  sprintf( __( '%s automatically inserts the product(s) needed to gain access. %s inserts the URL to my account page with the login form. HTML is allowed.', WC_Memberships::TEXT_DOMAIN ), '<code>{products}</code>', '<code>{login_url}</code>' ),
					'id'            => 'memberships_restriction_messages'
				),

				array(
					'type'          => 'textarea',
					'id'            => 'wc_memberships_content_restricted_message',
					'class'         => 'input-text wide-input',
					'name'          => __( 'Content Restricted - Purchase Required', WC_Memberships::TEXT_DOMAIN ),
					'desc'          => __( 'Displays when purchase is required to view the content.', WC_Memberships::TEXT_DOMAIN ),
					'default'       => __( 'To access this content, you must purchase {products}.', WC_Memberships::TEXT_DOMAIN ),
					'desc_tip'      => __( 'Message displayed if visitor does not have access to content, but can purchase it.', WC_Memberships::TEXT_DOMAIN ),
				),

				array(
					'type'          => 'textarea',
					'id'            => 'wc_memberships_content_restricted_message_no_products',
					'class'         => 'input-text wide-input',
					'name'          => __( 'Content Restricted - Membership Required', WC_Memberships::TEXT_DOMAIN ),
					'desc'          => __( 'Displays if the content is restricted to a membership that cannot be purchased.', WC_Memberships::TEXT_DOMAIN ),
					'default'       => __( 'This content is only available to members.', WC_Memberships::TEXT_DOMAIN ),
					'desc_tip'      => __( 'Message displayed if visitor does not have access to content and no products can grant access.', WC_Memberships::TEXT_DOMAIN ),
				),

				array(
					'type'  => 'sectionend',
					'id'    => 'memberships_restriction_messages'
				),

			) );
		}

		/**
		 * Filter Memberships Settings
		 *
		 * @since 1.0.0
		 * @param array $settings Array of the plugin settings
		 */
		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );
	}


	/**
	 * Output the settings
	 *
	 * @since 1.0
	 */
	public function output() {
		global $current_section;

		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::output_fields( $settings );
	}


	/**
	 * Save settings
	 */
	public function save() {
		global $current_section;

		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::save_fields( $settings );
	}

}

endif;

return new WC_Settings_Memberships();
