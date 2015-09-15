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
 * @package   WC-Memberships/Frontend
 * @author    SkyVerge
 * @category  Frontend
 * @copyright Copyright (c) 2014-2015, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Frontend class, handles general frontend functionality
 *
 * @since 1.0.0
 */
class WC_Memberships_Frontend {


	/** @var array cart items with member discounts helper **/
	private $_cart_items_with_member_discounts = null;


	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Enqueue JS and styles
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_and_styles' ) );

		// Handle frontend actions
		if ( SV_WC_Plugin_Compatibility::is_wc_version_gte_2_3() ) {
			add_action( 'wp_loaded', array( $this, 'cancel_membership' ) );
			add_action( 'wp_loaded', array( $this, 'renew_membership' ) );
		} else {
			add_action( 'init', array( $this, 'cancel_membership' ) );
			add_action( 'init', array( $this, 'renew_membership' ) );
		}

		// Add cart & checkout notices
		add_action( 'wp', array( $this, 'add_cart_member_login_notice' ) );

		// optional login/link buttons on checkout / thank you pages
		add_action( 'woocommerce_before_template_part', array( $this, 'maybe_render_checkout_member_login_notice' ) );

		// Show memberships on my account dashboard
		add_action( 'woocommerce_before_my_account', array( $this, 'my_account_memberships' ) );

	}


	/**
	 * Enqueue frontend scripts & styles
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts_and_styles() {
		wp_enqueue_style( 'wc-memberships-frontend', wc_memberships()->get_plugin_url() . '/assets/css/frontend/wc-memberships-frontend.min.css', WC_Memberships::VERSION );
	}


	/**
	 * Output memberships table in My Account
	 *
	 * @since 1.0.0
	 */
	public function my_account_memberships() {

		$customer_memberships = wc_memberships_get_user_memberships();

		if ( ! empty( $customer_memberships ) ) {
			wc_get_template( 'myaccount/my-memberships.php', array( 'customer_memberships' => $customer_memberships ) );
		}
	}


	/**
	 * Cancel a membership
	 *
	 * @since 1.0.0
	 */
	public function cancel_membership() {

		if ( ! isset( $_GET['cancel_membership'] ) || ! isset( $_GET['user_membership_id'] ) ) {
			return;
		}

		$user_membership_id = absint( $_GET['user_membership_id'] );
		$user_membership    = wc_memberships_get_user_membership( $user_membership_id );
		$user_can_cancel    = current_user_can( 'wc_memberships_cancel_membership', $user_membership_id );

		if ( ! $user_membership ) {
			wc_add_notice( __( 'Invalid membership.', WC_Memberships::TEXT_DOMAIN ), 'error' );
		}

		else {

			/**
			 * Filter the valid statuses for cancelling a user membership on frontend
			 *
			 * @since 1.0.0
			 * @param array $statuses Array of statuses valid for cancellation
			 */
			$user_membership_can_cancel = in_array( $user_membership->get_status(), apply_filters( 'wc_memberships_valid_membership_statuses_for_cancel', array( 'active' ) ) );

			if ( ! $user_membership->is_cancelled() && $user_can_cancel && $user_membership_can_cancel && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'wc_memberships-cancel_membership' ) ) {

				$user_membership->cancel_membership( __( 'Membership cancelled by customer.', WC_Memberships::TEXT_DOMAIN ) );

				// Message

				/**
				 * Filter the user cancelled membership message on frontend
				 *
				 * @since 1.0.0
				 * @param string $notice
				 */
				$notice = apply_filters( 'wc_memberships_user_membership_cancelled_notice', __( 'Your membership was cancelled.', WC_Memberships::TEXT_DOMAIN ) );
				wc_add_notice( $notice, 'notice' );

				/**
				 * Fires right after a membership has been cancelled by a customer
				 *
				 * @since 1.0.0
				 * @param int $user_membership_id
				 */
				do_action( 'wc_memberships_cancelled_user_membership', $user_membership_id );

			} else {

				wc_add_notice( __( 'Cannot cancel this membership.', WC_Memberships::TEXT_DOMAIN ), 'error' );
			}
		}

		wp_safe_redirect( SV_WC_Plugin_Compatibility::wc_get_page_permalink( 'myaccount' ) );
		exit;
	}


	/**
	 * Renew a membership
	 *
	 * @since 1.0.0
	 */
	public function renew_membership() {

		if ( ! isset( $_GET['renew_membership'] ) || ! isset( $_GET['user_membership_id'] ) ) {
			return;
		}

		$user_membership_id = absint( $_GET['user_membership_id'] );
		$user_membership    = wc_memberships_get_user_membership( $user_membership_id );
		$membership_plan    = $user_membership->get_plan();
		$user_can_renew     = current_user_can( 'wc_memberships_renew_membership', $user_membership_id );


		if ( ! $user_membership ) {

			wc_add_notice( __( 'Invalid membership.', WC_Memberships::TEXT_DOMAIN ), 'error' );
		} else {

			/**
			 * Filter the valid statuses for renewing a user membership on frontend
			 *
			 * @since 1.0.0
			 * @param array $statuses Array of statuses valid for renewal
			 */
			$user_membership_can_renew = in_array( $user_membership->get_status(), apply_filters( 'wc_memberships_valid_membership_statuses_for_renewal', array( 'expired', 'cancelled' ) ) );

			// Try to purchase the same product as before
			$original_product = $user_membership->get_product();

			if ( $original_product && $original_product->is_purchasable() ) {
				$product = $original_product;
			}

			// If that's not available, try to get the first purchasable product
			else {

				foreach ( $membership_plan->get_product_ids() as $product_id ) {

					$another_product = wc_get_product( $product_id );

					// We've found a product that can be purchased to renew access!
					if ( $another_product && $another_product->is_purchasable() ) {
						$product = $another_product;
						break;
					}
				}
			}

			// We can renew! Let's do it!
			if ( $product && $user_can_renew && $user_membership_can_renew && isset( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'wc_memberships-renew_membership' ) ) {

				woocommerce_empty_cart();
				WC()->cart->add_to_cart( $product->id, 1 );

				wc_add_notice( sprintf( __( 'Renew your membership by purchasing %s.', WC_Memberships::TEXT_DOMAIN ), $product->get_title() ), 'success' );
				wp_safe_redirect( WC()->cart->get_checkout_url() );
				exit;

			}
		}

		wc_add_notice( __( 'Cannot renew this membership. Please contact us if you need assistance.', WC_Memberships::TEXT_DOMAIN ), 'error' );

		wp_safe_redirect( SV_WC_Plugin_Compatibility::wc_get_page_permalink( 'myaccount' ) );
		exit;
	}


	/**
	 * Add a notice for members that they can get a discount when logged in
	 *
	 * @since 1.0.0
	 */
	public function add_cart_member_login_notice() {

		$display_in = get_option( 'wc_memberships_display_member_login_notice' );

		if ( ! is_user_logged_in() && is_cart() && in_array( $display_in, array( 'cart', 'both' ) ) ) {

			if ( $this->cart_has_items_with_member_discounts() ) {

				$message = $this->get_member_login_message();
				wc_add_notice( sprintf( $message, '<a href="' . esc_url( SV_WC_Plugin_Compatibility::wc_get_page_permalink( 'myaccount' ) ) . '">', '</a>' ), 'notice' );
			}
		}
	}


	/**
	 * Maybe render checkout member login notice
	 *
	 * @since 1.0.0
	 * @param string $template_name template being loaded by WC
	 */
	public function maybe_render_checkout_member_login_notice( $template_name ) {

		// separate notice at checkout
		if ( ! is_user_logged_in() && 'checkout/form-login.php' === $template_name ) {

			$display_in = get_option( 'wc_memberships_display_member_login_notice' );

			if ( in_array( $display_in, array( 'checkout', 'both' ) ) && $this->cart_has_items_with_member_discounts() ) {

				$message = $this->get_member_login_message();
				wc_print_notice( sprintf( $message, '', '' ), 'notice' );
			}

		}
	}


	/**
	 * Get member login message
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_member_login_message() {

		if ( count( $this->get_cart_items_with_member_discounts() ) > 1 ) {
			$message = __( 'Some items in you cart are discounted for members. %sLog in%s to claim them.' );
		} else if ( count( WC()->cart->get_cart() ) > 1 ) {
			$message = __( "An item in your cart is discounted for members. %sLog in%s to claim it." );
		} else {
			$message = __( "This item is discounted for members. %sLog in%s to claim it." );
		}

		return $message;
	}


	/**
	 * Get items in cart with member discounts
	 *
	 * @since 1.0.0
	 * @return array Array of Product IDs in cart with member discounts
	 */
	private function get_cart_items_with_member_discounts() {

		if ( ! isset( $this->_cart_items_with_member_discounts ) ) {

			$this->_cart_items_with_member_discounts = array();

			foreach ( WC()->cart->get_cart() as $item_key => $item ) {

				$product_id = isset( $item['variation_id'] ) && $item['variation_id'] ? $item['variation_id'] : $item['product_id'];

				if ( wc_memberships()->rules->product_has_member_discount( $product_id ) ) {
					$this->_cart_items_with_member_discounts[] = $product_id;
				}
			}
		}

		return $this->_cart_items_with_member_discounts;
	}


	/**
	 * Check if cart has any items with member discounts
	 *
	 * @since 1.0.0
	 * @return bool True, if has items with member discounts, false otherwise
	 */
	private function cart_has_items_with_member_discounts() {

		$cart_items = $this->get_cart_items_with_member_discounts();
		return ! empty( $cart_items );
	}


	/**
	 * Get a list of products that grant access to a piece of content
	 *
	 * @since 1.0.0
	 * @param int $post_id
	 * @param string $rule_type
	 * @return array|null
	 */
	private function get_products_that_grant_access( $post_id = null, $rule_type = null ) {

		// Default to the 'current' post
		if ( ! $post_id ) {

			global $post;
			$post_id = $post->ID;
		}

		// Get applied rules
		if ( 'purchasing_discount' == $rule_type ) {
			$rules = wc_memberships()->rules->get_product_purchasing_discount_rules( $post_id );
		} else if ( in_array( get_post_type( $post_id ), array( 'product', 'product_variation' ) ) ) {
			$rules = wc_memberships()->rules->get_the_product_restriction_rules( $post_id );
		} else {
			$rules = wc_memberships()->rules->get_post_content_restriction_rules( $post_id );
		}

		// Find products that grant access
		$processed_plans = array(); // holder for membership plans that have been processed already
		$products        = array();

		foreach ( $rules as $rule ) {

			// Skip further checks if this membership plan has already been processed
			if ( in_array( $rule->get_membership_plan_id(), $processed_plans ) ) {
				continue;
			}

			$plan = wc_memberships_get_membership_plan( $rule->get_membership_plan_id() );

			if ( $plan && $plan->has_products() ) {

				foreach ( $plan->get_product_ids() as $product_id ) {
					$products[] = $product_id;
				}
			}

			// Mark this plan as processed, we do not need look into it any further,
			// because we already know if it has any products that grant access or not.
			$processed_plans[] = $rule->get_membership_plan_id();
		}

		return ! empty( $products ) ? $products : null;
	}


	/**
	 * Get and parse a restriction message
	 *
	 * General wrapper around different types of restriction messages
	 *
	 * @since 1.0.0
	 * @param string $type Restriction type
	 * @param int $post_id Post ID that is being restricted
	 * @param array $products List of product IDs that grant access. Optional
	 * @return string Restriction message
	 */
	private function get_restriction_message( $type, $post_id, $products = null ) {

		if ( ! $type ) {
			return false;
		}

		if ( ! empty( $products ) ) {

			foreach ( $products as $key => $product_id ) {

				$product = wc_get_product( $product_id );
				$link    = $product->get_permalink();
				$title   = $product->get_title();

				// Special handling for variations
				if ( $product->is_type( 'variation' ) ) {

					$attributes = $product->get_variation_attributes();

					foreach ( $attributes as $attr_key => $attribute ) {
						$attributes[ $attr_key ] = ucfirst( $attribute );
					}

					$title .= ' &ndash; ' . implode( ', ', $attributes );
				}

				$products[ $key ] = sprintf( '<a href="%s">%s</a>', esc_url( $link ), wp_kses_post( $title ) );
			}

			// Check that the message type is valid for custom messages.
			// For example, purchasing_discount messages cannot be customized per-product
			// so we must leave them out
			if ( in_array( $type, wc_memberships_get_valid_restriction_message_types() ) && 'yes' === get_post_meta( $post_id, "_wc_memberships_use_custom_{$type}_message", true ) ) {
				$message = get_post_meta( $post_id, "_wc_memberships_{$type}_message", true );
			} else {
				$message = get_option( 'wc_memberships_' . $type . '_message' );
			}

			$message = str_replace( '{products}', '<span class="wc-memberships-products-grant-access">' . wc_memberships()->list_items( $products ) . '</span>', $message );
			$message = str_replace( '{login_url}', esc_url( SV_WC_Plugin_Compatibility::wc_get_page_permalink( 'myaccount' ) ), $message );

		} else {
			$message = get_option( 'wc_memberships_' . $type . '_message_no_products' );
		}

		return $message;
	}


	/**
	 * Get the product viewing restricted message
	 *
	 * @since 1.0.0
	 * @param int $post_id Optional. Defaults to current post.
	 * @return string
	 */
	public function get_product_viewing_restricted_message( $post_id = null ) {

		if ( ! $post_id ) {

			global $post;
			$post_id = $post->ID;
		}

		$products = $this->get_products_that_grant_access( $post_id );
		$message  = $this->get_restriction_message( 'product_viewing_restricted', $post_id, $products );

		/**
		 * Filter the product viewing restricted message
		 *
		 * @since 1.0.0
		 * @param string $message The restriction message
		 * @param int $product_id ID of the product being restricted
		 * @param array $products Array of product IDs that grant access to this product
		 */
		return apply_filters( 'wc_memberships_product_viewing_restricted_message', $message, $post_id, $products );
	}


	/**
	 * Get the product purchasing restricted message
	 *
	 * @since 1.0.0
	 * @param int $post_id Optional. Defaults to current post.
	 * @return string
	 */
	public function get_product_purchasing_restricted_message( $post_id = null ) {

		if ( ! $post_id ) {

			global $post;
			$post_id = $post->ID;
		}

		$products = $this->get_products_that_grant_access( $post_id );
		$message  = $this->get_restriction_message( 'product_purchasing_restricted', $post_id, $products );

		/**
		 * Filter the product purchasing restricted message
		 *
		 * @since 1.0.0
		 * @param string $message The restriction message
		 * @param int $product_id ID of the product being restricted
		 * @param array $products Array of product IDs that grant access to this product
		 */
		return apply_filters( 'wc_memberships_product_purchasing_restricted_message', $message, $post_id, $products );
	}


	/**
	 * Get the content restricted message
	 *
	 * @since 1.0.0
	 * @param int $post_id Optional. Defaults to current post.
	 * @return string
	 */
	public function get_content_restricted_message( $post_id = null ) {

		if ( ! $post_id ) {

			global $post;
			$post_id = $post->ID;
		}

		$products = $this->get_products_that_grant_access( $post_id );
		$message  = $this->get_restriction_message( 'content_restricted', $post_id, $products );

		/**
		 * Filter the product purchasing restricted message
		 *
		 * @since 1.0.0
		 * @param string $message The restriction message
		 * @param int $product_id ID of the product being restricted
		 * @param array $products Array of product IDs that grant access to this product
		 */
		return apply_filters( 'wc_memberships_content_restricted_message', $message, $post_id, $products );
	}


	/**
	 * Get the delayed content message
	 *
	 * @since 1.0.0
	 * @param int $user_id Optional. Defaults to current user ID.
	 * @param int $post_id Optional. Defaults to current post ID.
	 * @param string $access_type Optional. Defaults to "view". Applies to products only.
	 * @return string
	 */
	public function get_content_delayed_message( $user_id = null, $post_id = null, $access_type = 'view' ) {

		if ( ! $user_id ) {

			$user_id = get_current_user_id();
		}

		if ( ! $post_id ) {

			global $post;
			$post_id = $post->ID;
		}


		$access_time = wc_memberships()->capabilities->get_user_access_start_time_for_post( $user_id, $post_id, $access_type );

		switch ( get_post_type( $post_id ) ) {

			case 'product':
			case 'product_variation':
				if ( 'view' == $access_type ) {
					$message = __( 'This product is part of your membership, but not yet! You will gain access on {date}', WC_Memberships::TEXT_DOMAIN );
				} else {
					$message = __( 'This product is part of your membership, but not yet! You can purchase it on {date}', WC_Memberships::TEXT_DOMAIN );
				}
				break;

			case 'page':
				$message = __( 'This page is part of your membership, but not yet! You will gain access on {date}', WC_Memberships::TEXT_DOMAIN );
				break;

			case 'post':
				$message = __( 'This post is part of your membership, but not yet! You will gain access on {date}', WC_Memberships::TEXT_DOMAIN );
				break;

			default:
				$message = __( 'This content is part of your membership, but not yet! You will gain access on {date}', WC_Memberships::TEXT_DOMAIN );
				break;

		}


		/**
		 * Filter the delayed content message
		 *
		 * @since 1.0.0
		 * @param string $message Delayed content message
		 * @param int $post_id Post ID that the message applies to
		 * @param string $access_time Access time timestamp
		 */
		$message = apply_filters( 'get_content_delayed_message', $message, $post_id, $access_time );
		$message = str_replace( '{date}', date_i18n( get_option( 'date_format' ), $access_time ), $message );

		return $message;
	}


	/**
	 * Get the member discount message
	 *
	 * @since 1.0.0
	 * @param int $post_id Optional. Defaults to current post ID.
	 * @return string
	 */
	public function get_member_discount_message( $post_id = null ) {

		if ( ! $post_id ) {

			global $post;
			$post_id = $post->ID;
		}

		$products = $this->get_products_that_grant_access( $post_id, 'purchasing_discount' );
		$message  = $this->get_restriction_message( 'product_discount', $post_id, $products );

		/**
		 * Filter the product member discount message
		 *
		 * @since 1.0.0
		 * @param string $message The discount message
		 * @param int $product_id ID of the product that has member discounts
		 * @param array $products Array of product IDs that grant access to this product
		 */
		return apply_filters( 'wc_memberships_member_discount_message', $message, $post_id, $products );
	}

}
