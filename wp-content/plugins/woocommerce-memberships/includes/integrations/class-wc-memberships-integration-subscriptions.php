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
 * Integration class for WooCommerce Subscriptions
 *
 * @since 1.0.0
 */
class WC_Memberships_Integration_Subscriptions {


	/** @private array Subscription trial end date lazy storage */
	private $_subscription_trial_end_date = array();

	/** @private array Membership plan subscription check lazy storage */
	private $_has_membership_plan_subscription = array();


	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Subscription events (pre 2.0)
		add_action( 'subscription_put_on-hold', array( $this, 'handle_subscription_status_change_1_5' ), 10, 2 );
		add_action( 'reactivated_subscription', array( $this, 'handle_subscription_status_change_1_5' ), 10, 2 );
		add_action( 'subscription_expired',     array( $this, 'handle_subscription_status_change_1_5' ), 10, 2 );
		add_action( 'cancelled_subscription',   array( $this, 'handle_subscription_status_change_1_5' ), 10, 2 );
		add_action( 'subscription_trashed',     array( $this, 'handle_subscription_status_change_1_5' ), 10, 2 );
		add_action( 'subscription_deleted',     array( $this, 'handle_subscription_status_change_1_5' ), 10, 2 );

		add_action( 'woocommerce_subscriptions_set_expiration_date', array( $this, 'update_membership_end_date' ), 10, 3 );

		// 2.0 Subscription events
		add_action( 'woocommerce_subscription_status_updated', array( $this, 'handle_subscription_status_change' ), 10, 2 );
		add_action( 'woocommerce_subscription_date_updated',   array( $this, 'update_related_membership_dates' ), 10, 3 );
		add_action( 'trashed_post',                            array( $this, 'cancel_related_membership' ) );
		add_action( 'delete_post',                             array( $this, 'cancel_related_membership' ) );

		// Handle membership status change
		add_action( 'wc_memberships_user_membership_status_changed', array( $this, 'handle_user_membership_status_change' ), 10, 3 );

		// Access dates etc (2.0 & backwards compatible)
		add_filter( 'wc_memberships_access_from_time',  array( $this, 'adjust_post_access_from_time' ), 10, 3 );

		// Renew membership URL (2.0 & backwards compatible)
		add_filter( 'wc_memberships_get_renew_membership_url', array( $this, 'renew_membership_url' ), 10, 2 );

		// Grant membership access (2.0 & backwards compatible)
		add_filter( 'wc_memberships_renew_membership',                      array( $this, 'renew_membership' ), 10, 3 );
		add_filter( 'wc_memberships_access_granting_purchased_product_id',  array( $this, 'adjust_access_granting_product_id' ), 10, 3 );
		add_action( 'wc_memberships_grant_access_from_existing_purchase',   array( $this, 'maybe_grant_access_from_subscription' ), 10, 2 );
		add_filter( 'wc_memberships_new_membership_data',                   array( $this, 'adjust_new_membership_data' ), 10, 2 );
		add_action( 'wc_memberships_grant_membership_access_from_purchase', array( $this, 'save_subscription_data' ), 10, 2 );

		// Add a free_trial membership status (2.0 & backwards compatible)
		add_filter( 'wc_memberships_user_membership_statuses',                   array( $this, 'add_free_trial_status' ) );
		add_filter( 'wc_memberships_valid_membership_statuses_for_cancel',       array( $this, 'enable_cancel_for_free_trial' ) );
		add_filter( 'wc_memberships_edit_user_membership_screen_status_options', array( $this, 'edit_user_membership_screen_status_options' ), 10, 2 );
		add_filter( 'wc_memberships_bulk_edit_user_memberships_status_options',  array( $this, 'remove_free_trial_from_bulk_edit' ) );

		// Frontend UI hooks (2.0 & backwards compatible)
		add_action( 'wc_memberships_my_memberships_column_headers',     array( $this, 'output_subscription_column_headers' ) );
		add_action( 'wc_memberships_my_memberships_columns',            array( $this, 'output_subscription_columns' ), 20 );
		add_action( 'wc_memberships_my_account_my_memberships_actions', array( $this, 'my_membership_actions' ), 10, 2 );

		// Admin UI hooks (2.0 & backwards compatible)
		add_action( 'wc_memberships_after_user_membership_billing_details',    array( $this, 'output_subscription_details' ) );
		add_action( 'wc_membership_plan_options_membership_plan_data_general', array( $this, 'output_subscription_options' ) );
		add_action( 'wc_memberships_restriction_rule_access_schedule_field',   array( $this, 'output_exclude_trial_option' ), 10, 2 );

		// AJAX actions (2.0 & backwards compatible)
		add_action( 'wp_ajax_wc_memberships_membership_plan_has_subscription_product', array( $this, 'ajax_plan_has_subscription' ) );

		// Activation/deactivation (2.0 & backwards compatible)
		add_action( 'wc_memberships_activated',              array( $this, 'handle_activation' ), 1 );
		add_action( 'woocommerce_subscriptions_activated',   array( $this, 'handle_activation' ), 1 );
		add_action( 'woocommerce_subscriptions_deactivated', array( $this, 'handle_deactivation' ) );

		add_action( 'admin_init', array( $this, 'handle_upgrade' ) );
	}


	/** Internal & helper methods ******************************************/


	/**
	 * Check Subscriptions version
	 *
	 * Note: edit this if you want to test 2.0+, as 2.0-bleeding has the internal
	 * version still at 1.5.26 ¯\_(ツ)_/¯
	 *
	 * @since 1.0.0
	 * @return bool True if Subscriptions version is >= 2.0, false otherwise
	 */
	private function is_subscriptions_gte_2_0() {

		return version_compare( WC_Subscriptions::$version, '2.0.0', '>=' );
	}


	/**
	 * Get subscription by order_id and product_id
	 *
	 * Compatibility method for supporting both Subscriptions 2.0
	 * and earlier
	 *
	 * @since 1.0.0
	 * @param int $order_id
	 * @param int $product_id
	 * @return mixed Subscription array (pre 2.0), object (2.0 onwards) or null if not found
	 */
	private function get_order_product_subscription( $order_id, $product_id ) {

		if ( $this->is_subscriptions_gte_2_0() ) {

			$subscriptions = wcs_get_subscriptions( array(
				'order_id'   => $order_id,
				'product_id' => $product_id,
			) );

			$subscription = reset( $subscriptions );
		}

		else {

			$subscription_key = WC_Subscriptions_Manager::get_subscription_key( $order_id, $product_id );
			$subscription     = WC_Subscriptions_Manager::get_subscription( $subscription_key );
		}

		return $subscription;
	}


	/**
	 * Get user memberships by subscription ID
	 *
	 * @since 1.0.0
	 * @param int $subscription_id Subscription ID
	 * @return array|null Array of user membership objects or null, if none found
	 */
	private function get_user_memberships_by_subscription_id( $subscription_id ) {

		global $wpdb;

		$user_memberships = null;

		$user_membership_id = $wpdb->get_col( $wpdb->prepare( "
			SELECT post_id
			FROM $wpdb->postmeta pm
			RIGHT JOIN $wpdb->posts p ON pm.post_id = p.ID
			WHERE pm.meta_key = '_subscription_id'
			AND pm.meta_value = %d
			AND p.post_type = 'wc_user_membership'
		", $subscription_id ) );

		if ( ! empty( $user_membership_ids ) ) {

			$user_memberships = array();

			foreach ( $user_membership_ids as $user_membership_id ) {
				$user_memberships[] = wc_memberships_get_user_membership( $user_membership_id );
			}
		}

		return $user_memberships;
	}


	/**
	 * Get user memberships by subscription key
	 *
	 * @since 1.0.0
	 * @param string @subscription_key Subscription key
	 * @return array|null Array of user membership objects or null, if none found
	 */
	private function get_user_memberships_by_subscription_key( $subscription_key ) {

		global $wpdb;

		$user_memberships = null;

		$user_membership_ids = $wpdb->get_col( $wpdb->prepare( "
			SELECT post_id
			FROM $wpdb->postmeta pm
			RIGHT JOIN $wpdb->posts p ON pm.post_id = p.ID
			WHERE pm.meta_key = '_subscription_key'
			AND pm.meta_value = %s
			AND p.post_type = 'wc_user_membership'
		", $subscription_key ) );

		if ( ! empty( $user_membership_ids ) ) {

			$user_memberships = array();

			foreach ( $user_membership_ids as $user_membership_id ) {
				$user_memberships[] = wc_memberships_get_user_membership( $user_membership_id );
			}
		}

		return $user_memberships;
	}


	/** Subscription event methods *****************************************/


	/**
	 * Handle subscription status change (pre 2.0)
	 *
	 * @since 1.0.0
	 * @param int $user_id User ID
	 * @param string $subscription_key Subscription key
	 */
	public function handle_subscription_status_change_1_5( $user_id, $subscription_key ) {

		$user_memberships = $this->get_user_memberships_by_subscription_key( $subscription_key );

		if ( ! $user_memberships ) {
			return;
		}

		$note = '';

		switch ( current_filter() ) {

			case 'subscription_trashed':
				$note = __( 'Membership cancelled because subscription was trashed.', WC_Memberships::TEXT_DOMAIN );
				break;

			case 'subscription_deleted':
				$note = __( 'Membership cancelled because subscription was deleted.', WC_Memberships::TEXT_DOMAIN );
				break;
		}

		foreach ( $user_memberships as $user_membership ) {

			$subscription = WC_Subscriptions_Manager::get_subscription( $subscription_key );
			$this->update_related_membership_status( $subscription, $user_membership, $subscription['status'], $note );
		}

	}


	/**
	 * Handle subscription status change (2.0 and onwards)
	 *
	 * @since 1.0.0
	 * @param int $user_id User ID
	 * @param string $subscription_key Subscription key
	 */
	public function handle_subscription_status_change( WC_Subscription $subscription, $new_status ) {

		$user_memberships = $this->get_user_memberships_by_subscription_id( $subscription->id );

		if ( ! $user_memberships ) {
			return;
		}

		foreach ( $user_memberships as $user_membership ) {
			$this->update_related_membership_status( $subscription, $user_membership, $new_status );
		}

	}


	/**
	 * Update related membership status based on subscription status
	 *
	 * @since 1.0.0
	 * @param mixed $subscription
	 * @param WC_Memberships_User_Membership $user_membership
	 * @param string $status
	 * @param string $note Optional
	 */
	public function update_related_membership_status( $subscription, $user_membership, $status, $note = '' ) {

		switch ( $status ) {

			case 'active':

				$trial_end = $this->is_subscriptions_gte_2_0()
									 ? $subscription->get_time( 'trial_end' )
									 : ( $subscription['trial_expiry_date'] ? strtotime( $subscription['trial_expiry_date'] ) : '' );

				if ( $trial_end && $trial_end > current_time( 'timestamp', true ) ) {

					if ( ! $note ) {
						$note = __( 'Membership free trial activated because subscription was re-activated.', WC_Memberships::TEXT_DOMAIN );
					}
					$user_membership->update_status( 'free_trial', $note );

				} else {

					if ( ! $note ) {
						$note = __( 'Membership activated because subscription was re-activated.', WC_Memberships::TEXT_DOMAIN );
					}
					$user_membership->activate_membership( $note );
				}
				break;

			case 'on-hold':

				if ( ! $note ) {
					$note = __( 'Membership paused because subscription was put on-hold.', WC_Memberships::TEXT_DOMAIN );
				}

				$user_membership->pause_membership( $note );
				break;

			case 'expired':

				if ( ! $note ) {
					$note = __( 'Membership expired because subscription expired.', WC_Memberships::TEXT_DOMAIN );
				}

				$user_membership->update_status( 'expired', $note );
				break;

			case 'pending-cancel':

				if ( ! $note ) {
					$note = __( 'Membership marked as pending cancellation because subscription is pending cancellation.', WC_Memberships::TEXT_DOMAIN );
				}

				$user_membership->update_status( 'pending', $note );
				break;

			case 'cancelled':

				if ( ! $note ) {
					$note = __( 'Membership cancelled because subscription was cancelled.', WC_Memberships::TEXT_DOMAIN );
				}

				$user_membership->cancel_membership( $note );
				break;

			case 'trash':

				if ( ! $note ) {
					$note = __( 'Membership cancelled because subscription was trashed.', WC_Memberships::TEXT_DOMAIN );
				}

				$user_membership->cancel_membership( $note );
				break;

		}
	}


	/**
	 * Update related membership upon subscription date change
	 *
	 * @since 1.0.0
	 * @param WC_Subscription $subscription
	 * @param string $date_type
	 * @param string $datetime
	 */
	public function update_related_membership_dates( WC_Subscription $subscription, $date_type, $datetime ) {

		if ( 'end' == $date_type ) {

			$user_memberships = $this->get_user_memberships_by_subscription_id( $subscription->id );

			if ( ! $user_memberships ) {
				return;
			}

			foreach ( $user_memberships as $user_membership ) {

				$plan_id = $user_membership->get_plan_id();

				if ( $plan_id && $this->plan_grants_access_while_subscription_active( $plan_id ) ) {

					update_post_meta( $user_membership->get_id(), '_end_date', $datetime ? $datetime : '' );
				}
			}

		}
	}


	/**
	 * Cancel user membership when subscription is deleted
	 *
	 * @since 1.0.0
	 * @param int $post_id
	 */
	public function cancel_related_membership( $post_id ) {

		// Bail out if the post being deleted is not a subscription
		if ( 'shop_subscription' !== get_post_type( $post_id ) ) {
			return;
		}

		$user_memberships = $this->get_user_memberships_by_subscription_id( $post_id );

		if ( ! $user_memberships ) {
			return;
		}

		switch ( current_filter() ) {

			case 'trashed_post':
				$note = __( 'Membership cancelled because subscription was trashed.', WC_Memberships::TEXT_DOMAIN );
				break;

			case 'delete_post':
				$note = __( 'Membership cancelled because subscription was deleted.', WC_Memberships::TEXT_DOMAIN );
				break;
		}

		foreach ( $user_memberships as $user_membership ) {
			$user_membership->cancel_membership( $note );
		}

	}


	/**
	 * Update membership end date when subscription expiration date is changed
	 *
	 * @since 1.0.0
	 * @param bool $is_set
	 * @param string $expiration_date Expiration date, as timestamp
	 * @param string $subscription_key Subscription key
	 */
	public function update_membership_end_date( $is_set, $expiration_date, $subscription_key ) {

		$user_memberships = $this->get_user_memberships_by_subscription_key( $subscription_key );

		if ( ! $user_memberships ) {
			return;
		}

		foreach ( $user_memberships as $user_membership ) {

			$plan_id = $user_membership->get_plan_id();

			if ( $plan_id && $this->plan_grants_access_while_subscription_active( $plan_id ) ) {

				update_post_meta( $user_membership->get_id(), '_end_date', $expiration_date ? date( 'Y-m-d H:i:s', $expiration_date ) : '' );
			}
		}

	}


	/**
	 * Handle user membership status changes
	 *
	 * @since 1.0.0
	 * @param WC_User_Membership $user_membership
	 * @param string $old_status
	 * @param string $new_status
	 */
	public function handle_user_membership_status_change( $user_membership, $old_status, $new_status ) {

		// Save the new membership end date and remove the paused date.
		// This means that if the membership was paused, or, for example,
		// paused and then cancelled, and then re-activated, the time paused
		// will be added to the expiry date, so that the end date is pushed back.
		//
		// Note: this duplicates the behavior in core, when status is changed to 'active'
		if ( 'free_trial' == $new_status && $paused_date = $user_membership->get_paused_date() ) {

			$user_membership->set_end_date( $user_membership->get_end_date() );
			delete_post_meta( $user_membership->get_id(), '_paused_date' );
		}
	}


	/** General methods ****************************************************/


	/**
	 * Adjust user membership post scheduled content 'access from' time for subscription-based memberships
	 *
	 * @since 1.0.0
	 * @param string $from_time Access from time, as a timestamp
	 * @param WC_Memberships_Membership_Plan_rule $rule Related rule
	 * @param WC_Memberships_User_Membership $user_membership
	 * @return string Modified from_time, as timestamp
	 */
	public function adjust_post_access_from_time( $from_time, WC_Memberships_Membership_Plan_Rule $rule, WC_Memberships_User_Membership $user_membership ) {

		if ( 'yes' == $rule->get_access_schedule_exclude_trial() ) {

			$has_subscription = $this->has_user_membership_subscription( $user_membership->get_id() );
			$trial_end_date   = $this->get_user_membership_trial_end_date( $user_membership->get_id(), 'timestamp' );

			if ( $has_subscription && $trial_end_date ) {

				$from_time = $trial_end_date;
			}
		}

		return $from_time;
	}


	/**
	 * Adjust renew membership URL for subscription-based memberships
	 *
	 * @since 1.0.0
	 * @param string $url Renew membership URL
	 * @param WC_Memberships_User_Membership $user_membership
	 * @return string Modified renew URL
	 */
	public function renew_membership_url( $url, WC_Memberships_User_Membership $user_membership ) {

		if ( $this->has_membership_plan_subscription( $user_membership->get_plan_id() ) ) {

			// note that we must also check if order contains a subscription since users
			// can be manually-assigned to memberships and not have an associated subscription

			// 2.0 onwards
			if ( $this->is_subscriptions_gte_2_0() ) {

				if ( wcs_order_contains_subscription( $user_membership->get_order() ) ) {
					$url = wcs_get_users_resubscribe_link( $this->get_user_membership_subscription_id( $user_membership->get_id() ) );
				}
			}

			// Earlier
			else {

				if ( WC_Subscriptions_Order::order_contains_subscription( $user_membership->get_order() ) ) {
					$subscription_key = $this->get_user_membership_subscription_key( $user_membership->get_id() );
					$url              = WC_Subscriptions_Renewal_Order::get_users_renewal_link( $subscription_key );
				}
			}
		}

		return $url;
	}


	/**
	 * Adjust whether a membership should be renewed or not
	 *
	 * @since 1.0.0
	 * @param bool $renew
	 * @param WC_Memberships_Membership_Plan $plan
	 * @param array $args
	 * @return bool
	 */
	public function renew_membership( $renew, $plan, $args ) {

		$product = wc_get_product( $args['product_id'] );

		// Disable renewing via a re-purchase of a subscription product
		if ( $product->is_type( array( 'subscription', 'subscription_variation', 'variable-subscription' ) ) ) {

			if ( $this->plan_grants_access_while_subscription_active( $plan->get_id() ) ) {

				$renew = false;
			}
		}

		return $renew;
	}


	/**
	 * Adjust the product ID that grants access to a membership plan on purchase
	 *
	 * Subscription products take priority over all other products
	 *
	 * @since 1.0.0
	 * @param int $product_id Product ID
	 * @param array $access_granting_product_ids Array of product IDs
	 * @param WC_Memberships_Membership_Plan $plan
	 * @return int Product ID, adjusted if necessary
	 */
	public function adjust_access_granting_product_id( $product_id, $access_granting_product_ids, WC_Memberships_Membership_Plan $plan ) {

		// Check if more than one products may grant access, and if the plan even
		// allows access while subscription is active
		if ( count( $access_granting_product_ids ) > 1 && $this->plan_grants_access_while_subscription_active( $plan->get_id() ) ) {

			// First, find all subscription products that grant access
			$access_granting_subscription_product_ids = array();

			foreach ( $access_granting_product_ids as $_product_id ) {
				$product = wc_get_product( $_product_id );

				if ( $product->is_type( array( 'subscription', 'subscription_variation', 'variable-subscription' ) ) ) {
					$access_granting_subscription_product_ids[] = $product->id;
				}
			}

			// If there are any, decide which one actually gets to grant access
			if ( ! empty( $access_granting_subscription_product_ids ) ) {

				// Only one subscription grants access, short-circuit it as the winner
				if ( count( $access_granting_subscription_product_ids ) == 1 ) {
					$product_id = $access_granting_subscription_product_ids[0];
				}

				// Multiple subscriptions grant access, let's select the most
				// gracious one - whichever gives access for a longer period, wins.
				else {

					$longest_expiration_date = 0;

					foreach ( $access_granting_subscription_product_ids as $_subscription_product_id ) {

						$expiration_date = WC_Subscriptions_Product::get_expiration_date( $_subscription_product_id );

						// No expiration date? Ladies and gentlemen - we've got a winner!
						if ( ! $expiration_date ) {
							$product_id = $_subscription_product_id;
							break;
						}

						// This one beats the previous sub! Out of the way, you sub-optimal sucker, you!
						if ( strtotime( $expiration_date ) > $longest_expiration_date ) {
							$product_id              = $_subscription_product_id;
							$longest_expiration_date = strtotime( $expiration_date );
						}
					}
				}

			}
		}

		return $product_id;
	}


	/**
	 * Only grant access from existing subscription if it's active
	 *
	 * @since 1.0.0
	 * @param bool $grant_access
	 * @param array $args
	 * @return bool
	 */
	public function maybe_grant_access_from_subscription( $grant_access, $args ) {

		$product = wc_get_product( $args['product_id'] );

		// Handle access from subscriptions
		if ( $product->is_type( array( 'subscription', 'subscription_variation', 'variable-subscription' ) ) ) {

			$subscription = $this->get_order_product_subscription( $args['order_id'], $product->id );
			$status       = is_array( $subscription ) ? $subscription['status'] : $subscription->get_status();

			if ( 'active' != $status ) {
				$grant_access = false;
			}
		}

		return $grant_access;
	}


	/**
	 * Adjust new membership data
	 *
	 * Sets the end date to match subscription end date
	 *
	 * @since 1.0.0
	 * @param array $data Original membership data
	 * @param array $args Array of arguments
	 * @return array Modified membership data
	 */
	public function adjust_new_membership_data( $data, $args ) {

		$product = wc_get_product( $args['product_id'] );

		// Handle access from subscriptions
		if ( $product->is_type( array( 'subscription', 'subscription_variation', 'variable-subscription' ) ) ) {

			$subscription = $this->get_order_product_subscription( $args['order_id'], $product->id );
			$trial_end    = $this->is_subscriptions_gte_2_0()
										? $subscription->get_time( 'trial_end' )
										: ( $subscription['trial_expiry_date'] ? strtotime( $subscription['trial_expiry_date'] ) : '' );

			if ( $trial_end && $trial_end > current_time( 'timestamp', true ) ) {
				$data['post_status'] = 'wcm-free_trial';
			}
		}

		return $data;
	}


	/**
	 * Save related subscription data when a membership access is granted via a purchase
	 *
	 * Sets the end date to match subscription end date
	 *
	 * @since 1.0.0
	 * @param WC_Memberships_Membership_Plan $plan
	 * @param array $args
	 */
	public function save_subscription_data( WC_Memberships_Membership_Plan $plan, $args ) {

		$product = wc_get_product( $args['product_id'] );

		// Handle access from subscriptions
		if ( $this->has_membership_plan_subscription( $plan->get_id() ) && $product->is_type( array( 'subscription', 'subscription_variation', 'variable-subscription' ) ) ) {

			// note: always use the product ID (not variation ID) when looking up a subscription
			// as Subs requires it
			$subscription = $this->get_order_product_subscription( $args['order_id'], $product->id );

			if ( ! $subscription ) {
				return;
			}

			if ( $this->is_subscriptions_gte_2_0() ) {

				// Save related subscription ID
				update_post_meta( $args['user_membership_id'], '_subscription_id', $subscription->id );

			} else {

				// Save related subscription key
				$subscription_key = WC_Subscriptions_Manager::get_subscription_key( $args['order_id'], $product->id );
				update_post_meta( $args['user_membership_id'], '_subscription_key', $subscription_key );
			}

			// Set membership expiry date based on subscription expiry date
			if ( $this->plan_grants_access_while_subscription_active( $plan->get_id() ) ) {

				$end_date = $this->is_subscriptions_gte_2_0()
									? ( $subscription->get_date( 'end' ) ? $subscription->get_date( 'end' ) : '' )
									: ( $subscription['expiry_date'] ? $subscription['expiry_date'] : '' );

				update_post_meta( $args['user_membership_id'], '_end_date', $end_date );
			}

		}
	}


	/**
	 * Get subscription key for a membership
	 *
	 * @since 1.0.0
	 * @param int $user_membership_id User Membership ID
	 * @return string|null Subscription key
	 */
	public function get_user_membership_subscription_key( $user_membership_id ) {
		return get_post_meta( $user_membership_id, '_subscription_key', true );
	}


	/**
	 * Get subscription ID for a membership
	 *
	 * @since 1.0.0
	 * @param int $user_membership_id User Membership ID
	 * @return string|null Subscription key
	 */
	public function get_user_membership_subscription_id( $user_membership_id ) {
		return get_post_meta( $user_membership_id, '_subscription_id', true );
	}


	/**
	 * Get the subscription for a membership
	 *
	 * @since 1.0.0
	 * @param int $user_membership_id User Membership ID
	 * @return array|null Subscription or null, if not found
	 */
	public function get_user_membership_subscription( $user_membership_id ) {

		// 2.0 onwards
		if ( $this->is_subscriptions_gte_2_0() ) {
			$subscription_id = $this->get_user_membership_subscription_id( $user_membership_id );

			if ( ! $subscription_id ) {
				return null;
			}

			return wcs_get_subscription( $subscription_id );
		}

		// Earlier
		else {

			$subscription_key = $this->get_user_membership_subscription_key( $user_membership_id );

			if ( ! $subscription_key ) {
				return null;
			}

			$user_membership = wc_memberships_get_user_membership( $user_membership_id );

			// It seems that the order has been deleted
			if ( false === get_post_status( $user_membership->get_order_id() ) ) {
				return null;
			}

			// It seems the subscription product has been removed from the order
			if ( ! WC_Subscriptions_Order::get_item_id_by_subscription_key( $subscription_key ) ) {
				return null;
			}

			return WC_Subscriptions_Manager::get_subscription( $subscription_key );
		}

	}


	/**
	 * Check if membership is tied to a subscription
	 *
	 * @since 1.0.0
	 * @param int $user_membership_id User Membership ID
	 * @return bool True, if has subscription, false otherwise
	 */
	public function has_user_membership_subscription( $user_membership_id ) {

		if ( $this->is_subscriptions_gte_2_0() ) {
			return (bool) $this->get_user_membership_subscription_id( $user_membership_id );
		} else {
			return (bool) $this->get_user_membership_subscription_key( $user_membership_id );
		}
	}


	/**
	 * Get the membership subscription trial end datetime
	 *
	 * May return null if the membership is not tied to a subscription
	 *
	 * @since 1.0.0
	 * @param int $user_membership_id User Membership ID
	 * @param string $format Optional. Defaults to 'mysql'
	 * @return string|null Returns the trial end date or null
	 */
	public function get_user_membership_trial_end_date( $user_membership_id, $format = 'mysql' ) {

		if ( ! isset( $this->_subscription_trial_end_date[ $user_membership_id ] ) ) {

			if ( ! $this->has_user_membership_subscription( $user_membership_id ) ) {
				return null;
			}

			if ( $this->is_subscriptions_gte_2_0() ) {

				$subscription = $this->get_user_membership_subscription( $user_membership_id );
				$this->_subscription_trial_end_date[ $user_membership_id ] = 'mysql' == $format
																																	 ? $subscription->get_date( 'trial_end' )
																																	 : $subscription->get_time( 'trial_end' );
			} else {

				$subscription_key = $this->get_user_membership_subscription_key( $user_membership_id );
				$this->_subscription_trial_end_date[ $user_membership_id ] = WC_Subscriptions_Manager::get_trial_expiration_date( $subscription_key, null, $format );
			}

		}

		return $this->_subscription_trial_end_date[ $user_membership_id ];
	}


	/**
	 * Check if the membership plan has at least one subscription product that grants access
	 *
	 * @since 1.0.0
	 * @param int $plan_id Membership Plan ID
	 * @return bool True, if has a subscription product, false otherwise
	 */
	public function has_membership_plan_subscription( $plan_id ) {

		if ( ! isset( $this->_has_membership_plan_subscription[ $plan_id ] ) ) {
			$plan = wc_memberships_get_membership_plan( $plan_id );

			$product_ids = $plan->get_product_ids();
			$product_ids = ! empty( $product_ids ) ? array_map( 'absint',  $product_ids ) : null;

			$this->_has_membership_plan_subscription[ $plan_id ] = false;

			if ( ! empty( $product_ids ) ) {

				foreach ( $product_ids as $product_id ) {

					if ( ! is_numeric( $product_id ) || ! $product_id ) {
						continue;
					}

					$product = wc_get_product( $product_id );

					if ( ! $product ) {
						continue;
					}

					if ( $product->is_type( array( 'subscription', 'subscription_variation', 'variable-subscription' ) ) ) {
						$this->_has_membership_plan_subscription[ $plan_id ] = true;
						break;
					}
				}
			}
		}

		return $this->_has_membership_plan_subscription[ $plan_id ];
	}


	/**
	 * Does a membership plan allow access while subscription is active?
	 *
	 * @since 1.0.0
	 * @param int $plan_id Membership Plan ID
	 * @return bool True, if access is allowed, flase otherwise
	 */
	public function plan_grants_access_while_subscription_active( $plan_id ) {

		/**
		 * Filter whether a plan grants access to a membership while subscription is active
		 *
		 * @since 1.0.0
		 * @param bool $grants_access Default: true
		 * @param int $plan_id Membership Plan ID
		 */
		return apply_filters( 'wc_memberships_plan_grants_access_while_subscription_active', true, $plan_id );
	}


	/** Membership status hooks ********************************************/


	/**
	 * Add free trial status to membership statuses
	 *
	 * @since 1.0.0
	 * @param array $statuses Array of statuses
	 * @return array Modified array of statuses
	 */
	public function add_free_trial_status( $statuses ) {

		$statuses = SV_WC_Helper::array_insert_after( $statuses, 'wcm-active', array(
			'wcm-free_trial' => array(
				'label'       => _x( 'Free Trial', 'Membership Status', WC_Memberships::TEXT_DOMAIN ),
				'label_count' => _n_noop( 'Free Trial <span class="count">(%s)</span>', 'Free Trial <span class="count">(%s)</span>', WC_Memberships::TEXT_DOMAIN ),
			)
		) );

		return $statuses;
	}


	/**
	 * Add free trial status to valid statuses for membership cancellation
	 *
	 * @since 1.0.0
	 * @param array $statuses Array of status slugs
	 * @return array modified status slugs
	 */
	public function enable_cancel_for_free_trial( $statuses ) {

		$statuses[] = 'free_trial';
		return $statuses;
	}


	/**
	 * Remove free trial status from status options, unless the membership
	 * actually is on free trial.
	 *
	 * @since 1.0.0
	 * @param array $statuses Array of status options
	 * @param int $user_membership_id User Membership ID
	 * @return array Modified array of status options
	 */
	public function edit_user_membership_screen_status_options( $statuses, $user_membership_id ) {

		$user_membership = wc_memberships_get_user_membership( $user_membership_id );

		if ( 'free_trial' != $user_membership->get_status() ) {
			unset( $statuses['wcm-free_trial'] );
		}

		return $statuses;
	}


	/**
	 * Remove free trial from bulk edit status options
	 *
	 * @since 1.0.0
	 * @param array $statuses Array of statuses
	 * @return array Modified array of statuses
	 */
	public function remove_free_trial_from_bulk_edit( $statuses ) {

		unset( $statuses['wcm-free_trial'] );
		return $statuses;
	}


	/** UI-affecting methods ***********************************************/


	/**
	 * Display subscription details in edit membership screen
	 *
	 * @since 1.0.0
	 * @param WC_Memberships_User_Membership $user_membership
	 */
	public function output_subscription_details( WC_Memberships_User_Membership $user_membership ) {

		$subscription = $this->get_user_membership_subscription( $user_membership->get_id() );

		if ( ! $subscription ) {
			return;
		}

		if ( ! $this->is_subscriptions_gte_2_0() ) {
			$subscription_key = $this->get_user_membership_subscription_key( $user_membership->get_id() );
		}

		if ( in_array( $user_membership->get_status(), array( 'free_trial', 'active' ) ) ) {

			$next_payment = $this->is_subscriptions_gte_2_0()
										? $subscription->get_time( 'next_payment' )
										: WC_Subscriptions_Manager::get_next_payment_date( $subscription_key, $user_membership->get_user_id(), 'timestamp' );
		} else {
			$next_payment = null;
		}

		$subscription_link = $this->is_subscriptions_gte_2_0()
											 ? get_edit_post_link( $subscription->id )
											 : esc_url( admin_url( 'admin.php?page=subscriptions&s=' . $subscription['order_id'] ) );

		$subscription_link_text = $this->is_subscriptions_gte_2_0() ? $subscription->id : $subscription_key;

		// TODO: subs 1.5.x doesn't account for the site timezone
		$subscription_expires = $this->is_subscriptions_gte_2_0()
									? $subscription->get_date_to_display( 'end' )
									: $subscription['expiry_date'] ? date_i18n( wc_date_format(), strtotime( $subscription['expiry_date'] ) ) : __( 'Subscription not yet ended', WC_Memberships::TEXT_DOMAIN );
		?>
			<table>
				<tr>
					<td><?php _e( 'Subscription:', WC_Memberships::TEXT_DOMAIN ); ?></td>
					<td><a href="<?php echo $subscription_link ?>"><?php echo $subscription_link_text ?></a></td>
				</tr>
				<tr>
					<td><?php _e( 'Next Bill On:', WC_Memberships::TEXT_DOMAIN ); ?></td>
					<td><?php echo $next_payment ? date_i18n( wc_date_format(), $next_payment ) : __( 'N/A', WC_Memberships::TEXT_DOMAIN ); ?></td>
				</tr>
			</table>
		<?php

		// replace the expiration date input
		wc_enqueue_js( '
			$( "._end_date_field" ).find( ".js-user-membership-date, .ui-datepicker-trigger, .description" ).hide();
			$( "._end_date_field" ).append( "<span>' . esc_html( $subscription_expires ) . '</span>" );
		' );
	}


	/**
	 * Display subscription column headers in my memberships section
	 *
	 * @since 1.0.0
	 */
	public function output_subscription_column_headers() {
		?><th class="membership-next-bill-on"><span class="nobr"><?php _e( 'Next Bill On', WC_Memberships::TEXT_DOMAIN ); ?></span></th><?php
	}


	/**
	 * Display subscription columns in my memberships section
	 *
	 * @since 1.0.0
	 * @param WC_Memberships_User_Membership $user_membership
	 */
	public function output_subscription_columns( WC_Memberships_User_Membership $user_membership ) {

		$subscription = $this->get_user_membership_subscription( $user_membership->get_id() );

		if ( ! $this->is_subscriptions_gte_2_0() ) {
			$subscription_key = $this->get_user_membership_subscription_key( $user_membership->get_id() );
		}

		if ( $subscription && in_array( $user_membership->get_status(), array( 'active', 'free_trial' ) ) ) {
			$next_payment = $this->is_subscriptions_gte_2_0()
										? $subscription->get_time( 'next_payment' )
										: WC_Subscriptions_Manager::get_next_payment_date( $subscription_key, $user_membership->get_user_id(), 'timestamp' );
		}

		?>
		<td class="membership-membership-next-bill-on" data-title="<?php esc_attr_e( 'Next Bill On', WC_Memberships::TEXT_DOMAIN ); ?>">
			<?php if ( $subscription && ! empty( $next_payment ) ) : ?>
				<?php echo date_i18n( get_option( 'date_format' ), $next_payment ) ?>
			<?php else : ?>
				<?php _e( 'N/A', WC_Memberships::TEXT_DOMAIN ); ?>
			<?php endif; ?>
		</td>
		<?php
	}


	/**
	 * Remove cancel action from memberships tied to a subscription
	 *
	 * @since 1.3.0
	 * @param array $actions
	 * @param WC_User_Membership $membership
	 * @return array
	 */
	public function my_membership_actions( $actions, WC_Memberships_User_Membership $membership ) {

		if ( $this->membership_has_subscription( $membership ) ) {
			unset( $actions['cancel'] );
		}

		return $actions;
	}


	/**
	 * Display subscriptions options and JS in the membership plan edit screen
	 *
	 * @since 1.0.0
	 */
	public function output_subscription_options() {
		global $post;

		$has_subscription = $this->has_membership_plan_subscription( $post->ID );

		if ( $this->plan_grants_access_while_subscription_active( $post->ID ) ) {
			?>
			<p class="subscription-access-notice <?php if ( ! $has_subscription ) : ?>hide<?php endif; ?> js-show-if-has-subscription">
				<span class="description"><?php _e( 'Membership will be active while the purchased subscription is active.', WC_Memberships::TEXT_DOMAIN ); ?></span>
				<img class="help_tip" data-tip='<?php _e( 'If membership access is granted via the purchase of a subscription, then membership length will be automatically equal to the length of the subscription, regardless of the membership length setting above.', WC_Memberships::TEXT_DOMAIN ) ?>' src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" />
			</p>

			<style type="text/css">
				.subscription-access-notice .description {
					margin-left: 150px;
				}
			</style>
			<?php
		}

		// Check if a membership plan has subscription(s)
		//
		// Check if the current membership plan has at least one
		// subscription product that grants access. If so, enable the
		// subscription-specific controls
		//
		// @since 1.0.0
		//
		wc_enqueue_js('
			var checkIfPlanHasSubscription = function() {

				var product_ids = $("#_product_ids").val() || [];
				product_ids = $.isArray( product_ids ) ? product_ids : product_ids.split(",");

				$.get( wc_memberships_admin.ajax_url, {
					action:      "wc_memberships_membership_plan_has_subscription_product",
					security:    "' . wp_create_nonce( "check-subscriptions" ) . '",
					product_ids: product_ids,
				}, function (subscription_products) {

					var action = subscription_products && subscription_products.length ? "removeClass" : "addClass"
					$(".js-show-if-has-subscription")[ action ]("hide");

					if ( subscription_products && subscription_products.length === product_ids.length ) {
						$("#_access_length_period").closest(".form-field").hide();
					} else {
						$("#_access_length_period").closest(".form-field").show();
					}

				});
			}

			checkIfPlanHasSubscription();

			// Purely cosmetic improvement
			$(".subscription-access-notice").appendTo( $( "#_access_length_period" ).closest( ".options_group" ) );

			$("#_product_ids").on( "change", function() {
				checkIfPlanHasSubscription();
			});
		');

	}


	/**
	 * Display subscriptions options for a restriction rule
	 *
	 * This method will be called both in the membership plan screen
	 * as well as on any individual product screens.
	 *
	 * @since 1.0.0
	 */
	public function output_exclude_trial_option( $rule, $index ) {

		$has_subscription = $rule->get_membership_plan_id() ? $this->has_membership_plan_subscription( $rule->get_membership_plan_id() ): false;
		$type             = $rule->get_rule_type();
		?>

		<span class="rule-control-group rule-control-group-access-schedule-trial <?php if ( ! $has_subscription ) : ?>hide<?php endif; ?> js-show-if-has-subscription">

			<input type="checkbox" name="_<?php echo $type; ?>_rules[<?php echo $index; ?>][access_schedule_exclude_trial]" id="_<?php echo $type; ?>_rules_<?php echo $index; ?>_access_schedule_exclude_trial" value="yes" <?php checked( $rule->get_access_schedule_exclude_trial(), 'yes' ); ?> class="access_schedule-exclude-trial" <?php if ( ! $rule->current_user_can_edit() ) : ?>disabled<?php endif; ?> />
			<label for="_<?php echo $type; ?>_rules_<?php echo $index; ?>_access_schedule_exclude_trial" class="label-checkbox">
				<?php _e( 'Start after trial', WC_Memberships::TEXT_DOMAIN ); ?>
			</label>

		</span>
		<?php
	}


	/** AJAX methods *******************************************************/


	/**
	 * Check if a plan has a subscription product
	 *
	 * Responds with an array of subscription products, if any.
	 *
	 * @since 1.0.0
	 */
	public function ajax_plan_has_subscription() {

		check_ajax_referer( 'check-subscriptions', 'security' );

		$product_ids = isset( $_REQUEST['product_ids'] ) && is_array( $_REQUEST['product_ids'] ) ? array_map( 'absint', $_REQUEST['product_ids'] ) : null;

		if ( empty( $product_ids ) ) {
			die();
		}

		$subscription_products = array();

		foreach ( $product_ids as $product_id ) {
			$product = wc_get_product( $product_id );

			if ( ! $product ) {
				continue;
			}

			if ( $product->is_type( array( 'subscription', 'variable-subscription', 'subscription_variation' ) ) ) {
				$subscription_products[] = $product->id;
			}
		}

		wp_send_json( $subscription_products );
	}


	/** Lifecycle methods **************************************************/


	/**
	 * Re-activate subscription-based memberships
	 *
	 * Find any memberships that are paused, and may need to be
	 * re-activated/put back on trial
	 *
	 * @since 1.0.0
	 */
	public function update_subscription_memberships() {

		$args = array(
			'post_type'    => 'wc_user_membership',
			'nopaging'     => true,
			'post_status'  => 'any',
			'meta_key'     => $this->is_subscriptions_gte_2_0() ? '_subscription_id' : '_subscription_key',
			'meta_value'   => '0',
			'meta_compare' => '>',
		);

		$posts = get_posts( $args );

		// Bail out if there are no memberships to work with
		if ( empty( $posts ) ) {
			return;
		}

		foreach ( $posts as $post ) {
			$user_membership = wc_memberships_get_user_membership( $post );

			// Get the related subscription
			$subscription = $this->get_user_membership_subscription( $user_membership->get_id() );

			if ( ! $subscription ) {
				continue;
			}

			$subscription_status = $this->is_subscriptions_gte_2_0()
													 ? $subscription->get_status()
													 : $subscription['status'];

			// Statuses do not match, update
			if ( $subscription_status != $user_membership->get_status() ) {

				// Special handling for paused memberships
				if ( 'paused' == $user_membership->get_status() ) {

					// Get trial end date
					$trial_end = $this->is_subscriptions_gte_2_0()
										 ? $subscription->get_time( 'trial_end' )
										 : ( $subscription['trial_expiry_date'] ? strtotime( $subscription['trial_expiry_date'] ) : '' );

					// If there is no trial end date, activate the membership
					if ( ! $trial_end || current_time( 'timestamp' ) >= strtotime( $trial_end ) ) {
						$user_membership->activate_membership( __( 'Membership activated because WooCommerce Subscriptions was activated.', WC_Memberships::TEXT_DOMAIN ) );

					// Otherwise, put it on free trial
					} else {
						$user_membership->update_status( 'free_trial', __( 'Membership free trial activated because WooCommerce Subscriptions was activated.', WC_Memberships::TEXT_DOMAIN ) );
					}

				// All other statuses: simply update the membership status
				} else {

					$this->update_related_membership_status( $subscription, $user_membership, $subscription_status );
				}
			}

			// Get the subscription end date
			$end_date = $this->is_subscriptions_gte_2_0()
								? ( $subscription->get_date( 'end' ) ? $subscription->get_date( 'end' ) : '' )
								: ( $subscription['expiry_date'] ? $subscription['expiry_date'] : '' );

			// End date has changed
			if ( strtotime( $end_date ) != $user_membership->get_end_date( 'timestamp' ) ) {
				update_post_meta( $user_membership->get_id(), '_end_date', $end_date );
			}
		}
	}


	/**
	 * Pause subscription-based memberships
	 *
	 * Find any memberships that are on free trial and pause them
	 *
	 * @since 1.0.0
	 */
	public function pause_free_trial_subscription_memberships() {

		$args = array(
			'post_type'   => 'wc_user_membership',
			'post_status' => 'wcm-free_trial',
			'nopaging'    => true,
		);

		$posts = get_posts( $args );

		// Bail out if there are no memberships on free trial
		if ( empty( $posts ) ) {
			return;
		}

		foreach ( $posts as $post ) {
			$user_membership = wc_memberships_get_user_membership( $post );
			$user_membership->pause_membership( __( 'Membership paused because WooCommerce Subscriptions was deactivated.', WC_Memberships::TEXT_DOMAIN ) );
		}
	}


	/**
	 * Handle subscriptions activation
	 *
	 * @since 1.0.0
	 * @param string $plugin Plugin
	 */
	public function handle_activation( $plugin ) {
		$this->update_subscription_memberships();
	}


	/**
	 * Handle subscriptions deactivation
	 *
	 * @since 1.0.0
	 */
	public function handle_deactivation() {
		$this->pause_free_trial_subscription_memberships();
	}


	/**
	 * Handle subscriptions upgrade
	 *
	 * This method runs on each admin page load and checks the current
	 * Subscriptions version against our record of Subscriptions version.
	 * We can't rely on the `woocommerce_subscriptions_upgraded` hook because
	 * that cannot be caught when Memberships is deactivated during upgrade.
	 *
	 * This solution catches all upgrades, even if they were done while Memberships
	 * was not active.
	 *
	 * @since 1.0.0
	 */
	public function handle_upgrade() {

		$subscriptions_version = get_option( 'wc_memberships_subscriptions_version' );

		// Versions match, we don't need to do anything
		if ( $subscriptions_version == WC_Subscriptions::$version ) {
			return;
		}

		// Upgrade routine from Subscriptions pre-2.0 to 2.0
		if ( version_compare( WC_Subscriptions::$version, '2.0', '>=' ) && version_compare( $subscriptions_version, '2.0', '<' ) ) {

			global $wpdb;

			// Upgrade user memberships to use Subscription IDs instead of keys.
			$results = $wpdb->get_results("
				SELECT pm.post_id as ID, pm.meta_value as subscription_key
				FROM $wpdb->postmeta pm
				LEFT JOIN $wpdb->posts p ON p.ID = pm.post_id
				WHERE pm.meta_key = '_subscription_key'
				AND p.post_type = 'wc_user_membership'
			");

			// Bail out if there are no memberships with subscription keys
			if ( empty( $results ) ) {
				return;
			}

			foreach ( $results as $result ) {
				$subscription_id = wcs_get_subscription_id_from_key( $result->subscription_key );

				if ( $subscription_key ) {
					update_post_meta( $result->ID, '_subscription_id', $subscription_id );
					delete_post_meta( $result->ID, '_subscription_key' );
				}
			}

		}

		// Update our record of Subscriptions version
		update_option( 'wc_memberships_subscriptions_version', WC_Subscriptions::$version );
	}


	/**
	 * Check whether a membership is subscription-based or not
	 *
	 * @since 1.0.1
	 * @param int|WC_User_Membership $user_membership
	 * @return bool True, if memberships is subscription-based, false otherwise
	 */
	public function membership_has_subscription( $user_membership ) {

		$id = is_object( $user_membership ) ? $user_membership->get_id() : $user_membership;

		if ( $this->is_subscriptions_gte_2_0() ) {
			$subscription = get_post_meta( $id, '_subscription_id', true );
		}
		else {
			$subscription = get_post_meta( $id, '_subscription_key', true );
		}

		return (bool) $subscription;
	}

}

new WC_Memberships_Integration_Subscriptions();
