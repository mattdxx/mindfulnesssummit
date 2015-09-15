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
 * @package   WC-Memberships/Classes
 * @author    SkyVerge
 * @copyright Copyright (c) 2014-2015, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Membership Plan class
 *
 * This class represents a single membership plan, eg "silver" or "gold"
 * with it's specific configuration.
 *
 * Technically, it's a wrapper around an instance of WP_Post with the
 * 'wc_membership_plan' custom post type, similar to how WC_Product or
 * WC_Order are implemented.
 *
 *
 * @since 1.0.0
 */
class WC_Memberships_Membership_Plan {


	/** @public int Membership Plan (post) ID */
	public $id;

	/** @public string Membership Plan name */
	public $name;

	/** @public string Membership Plan (post) slug */
	public $slug;

	/** @public object Membership Plan post object */
	public $post;

	/** @private lazy rules getter */
	private $rules = array();


	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @param mixed $id Membership Plan slug, post object or related post ID
	 */
	public function __construct( $id ) {

		if ( ! $id ) {
			return;
		}

		// Get order status post object by ID
		if ( is_numeric( $id ) ) {
			$post = get_post( $id );

			if ( ! $post ) {
				return null;
			}

			$this->post = $post;
		}

		// Initialize from post object
		else if ( is_object( $id ) ) {
			$this->post = $id;
		}

		// Load in post data
		if ( $this->post ) {

			$this->id   = $this->post->ID;
			$this->name = $this->post->post_title;
			$this->slug = $this->post->post_name;
		}

	}


	/**
	 * Get the ID
	 *
	 * @since 1.0.0
	 * @return int Membership Plan ID
	 */
	public function get_id() {
		return $this->id;
	}


	/**
	 * Get the name
	 *
	 * @since 1.0.0
	 * @return string Membership Plan name
	 */
	public function get_name() {
		return $this->name;
	}


	/**
	 * Get the slug
	 *
	 * @since 1.0.0
	 * @return string Membership Plan slug
	 */
	public function get_slug() {
		return $this->slug;
	}


	/**
	 * Get product IDs that grant access to this plan
	 *
	 * @since 1.0.0
	 * @return array Array of product IDs
	 */
	public function get_product_ids() {
		return get_post_meta( $this->get_id(), '_product_ids', true );
	}


	/**
	 * Get access length amount
	 *
	 * Returns the amount part of the access length.
	 * For example, returns '5' for the schedule '5 days'
	 *
	 * @return int|string Amount or empty string if no schedule
	 */
	public function get_access_length_amount() {

		$parts = explode( ' ', $this->get_access_length() );
		return isset( $parts[1] ) ? (int) $parts[0] : '';
	}


	/**
	 * Get access length period
	 *
	 * Returns the period part of the access length.
	 * For example, returns 'days' for the schedule '5 days'
	 *
	 * @return string Period
	 */
	public function get_access_length_period() {

		$parts = explode( ' ', $this->get_access_length() );
		return isset( $parts[1] ) ? $parts[1] : $parts[0];
	}


	/**
	 * Get access length
	 *
	 * @since 1.0.0
	 * @return string Access length in human-readable form, ex: "5 days"
	 */
	public function get_access_length() {
		return get_post_meta( $this->get_id(), '_access_length', true );
	}


	/**
	 * Get membership plan rules
	 *
	 * General rules builder & getter.
	 *
	 * @since 1.0.0
	 *
	 * @param string $rule_type Rule type. One of 'content_restriction', 'product_restriction' or 'purchasing_discount'.
	 * @return array|bool $rules Array of rules or false on error
	 */
	private function get_rules( $rule_type ) {

		if ( ! isset( $this->rules[ $rule_type ] ) ) {

			$all_rules = get_option( 'wc_memberships_rules' );
			$this->rules[ $rule_type ] = array();

			if ( ! empty( $all_rules ) ) {

				foreach ( $all_rules as $rule ) {

					// Skip empty items
					if ( empty( $rule ) || ! is_array( $rule ) ) {
						continue;
					}

					$rule = new WC_Memberships_Membership_Plan_Rule( $rule );

					if ( $rule_type == $rule->get_rule_type() && $rule->get_membership_plan_id() == $this->get_id() ) {
						$this->rules[ $rule_type ][] = $rule;
					}
				}
			}
		}

		return $this->rules[ $rule_type ];
	}


	/**
	 * Get content restriction rules
	 *
	 * @since 1.0.0
	 * @return array Array of content restriction rules
	 */
	public function get_content_restriction_rules() {
		return $this->get_rules( 'content_restriction' );
	}


	/**
	 * Get product restriction rules
	 *
	 * @since 1.0.0
	 * @return array Array of product restriction rules
	 */
	public function get_product_restriction_rules() {
		return $this->get_rules( 'product_restriction' );
	}


	/**
	 * Get purchasing discount rules
	 *
	 * @since 1.0.0
	 * @return array Array of purchasing discount rules
	 */
	public function get_purchasing_discount_rules() {
		return $this->get_rules( 'purchasing_discount' );
	}


	/**
	 * Get related user memberships
	 *
	 * @since 1.0.0
	 * @return array Array of user memberships
	 */
	public function get_memberships() {

		$args = array(
			'post_type'   => 'wc_user_membership',
			'post_status' => 'any',
			'post_parent' => $this->get_id(),
			'nopaging'    => true,
		);

		$posts = get_posts( $args );
		$user_memberships = array();

		if ( ! empty( $posts ) ) {

			foreach ( $posts as $post ) {
				$user_memberships[] = wc_memberships_get_user_membership( $post );
			}
		}

		return $user_memberships;
	}


	/**
	 * Get number of related memberships
	 *
	 * @since 1.0.0
	 * @return int Number of related user memberships
	 */
	public function get_memberships_count() {
		return count( $this->get_memberships() );
	}


	/**
	 * Check if the plan has any active user memberships
	 *
	 * @since 1.0.0
	 * @return bool True, if has active memberships, false otherwise
	 */
	public function has_active_memberships() {

		$has_active = false;

		foreach ( $this->get_memberships() as $user_membership ) {

			if ( ! $user_membership->is_cancelled() && ! $user_membership->is_expired() && $user_membership->is_in_active_period() ) {
				$has_active = true;
				break;
			}
		}

		return $has_active;
	}


	/**
	 * Check if this plan has any products that grant access
	 *
	 * @since 1.0.0
	 * @return bool True, if has products, false otherwise
	 */
	public function has_products() {

		$product_ids = $this->get_product_ids();
		return ! empty( $product_ids );
	}


	/**
	 * Check if this plan has a specified product
	 *
	 * @since 1.0.0
	 * @param int $product_id Product ID to search for
	 * @return bool True, if has has the specified product, false otherwise
	 */
	public function has_product( $product_id ) {
		return in_array( $product_id, (array) $this->get_product_ids() );
	}


	/**
	 * Grant a user access to this plan from a purchase
	 *
	 * @since 1.0.0
	 * @param int $user_id User ID
	 * @param int $product_id Product ID
	 * @param int $order_id Order ID
	 * @return int|null New/Existing User Membership ID or null on failure
	 */
	public function grant_access_from_purchase( $user_id, $product_id, $order_id ) {

		$user_membership_id = null;
		$action             = 'create';

		// Check if user is perhaps a member, but membership is expired/cancelled
		if ( wc_memberships_is_user_member( $user_id, $this->get_id() ) ) {

			$user_membership    = wc_memberships_get_user_membership( $user_id, $this->get_id() );
			$user_membership_id = $user_membership->get_id();

			// Do not allow the same order to renew or reactivate the membership. This
			// prevents admins changing order statuses from extending/reactivating the
			// membership.
			$order_ids = get_post_meta( $user_membership_id, '_order_id' );

			if ( ! empty( $order_ids ) && in_array( $order_id, $order_ids ) ) {
				return null;
			}

			// Otherwise... continue as usual
			$action = 'reactivate';

			if ( wc_memberships_is_user_active_member( $user_id, $this->get_id() ) ) {

				/**
				 * Filter whether an already active membership will be renewed
				 *
				 * @since 1.0.0
				 * @param bool $renew
				 * @param WC_Memberships_Membership_Plan $plan
				 * @param array $args
				 */
				$renew_membership = apply_filters( 'wc_memberships_renew_membership', (bool) $this->get_access_length_amount(), $this, array(
					'user_id'    => $user_id,
					'product_id' => $product_id,
					'order_id'   => $order_id,
				) );

				if ( ! $renew_membership ) {
					return null;
				}

				$action = 'renew';
			}
		}


    // Create/update the user membership
    $user_membership = wc_memberships_create_user_membership( array(
      'user_membership_id' => $user_membership_id,
      'user_id'            => $user_id,
      'product_id'         => $product_id,
      'order_id'           => $order_id,
      'plan_id'            => $this->get_id(),
    ), $action );


		// Add membership note
		$product = wc_get_product( $product_id );
		$order   = wc_get_order( $order_id );

		$user_membership->add_note( sprintf( __( 'Membership access granted from purchasing %s (Order %s)' ), $product->get_title(), $order->get_order_number() ) );


		/**
		 * Fires after a user has been granted membership access from a purchase
		 *
		 * @since 1.0.0
		 * @param WC_Memberships_Membership_Plan $membership_plan The plan that user was granted access to
		 * @param array $args
		 */
		do_action( 'wc_memberships_grant_membership_access_from_purchase', $this, array(
			'user_id'            => $user_id,
			'product_id'         => $product_id,
			'order_id'           => $order_id,
			'user_membership_id' => $user_membership->get_id(),
		) );

		return $user_membership->get_id();
	}

}
