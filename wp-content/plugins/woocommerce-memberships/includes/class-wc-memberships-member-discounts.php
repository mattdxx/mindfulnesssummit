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
 * Member Discounts class
 *
 * This class handles all purchasing discounts for members
 *
 * @since 1.3.0
 */
class WC_Memberships_Member_Discounts {


	/** @var array helper for product price discounts */
	private $member_discounted_products;


	/**
	 * Constructor
	 *
	 * Set up member discounts
	 *
	 * @since 1.3.0
	 */
	public function __construct() {

		$this->enable_price_adjustments();
		$this->enable_price_html_adjustments();

		add_filter( 'woocommerce_cart_item_price',      array( $this, 'on_cart_item_price'), 10, 3 );
		add_filter( 'woocommerce_get_variation_price',  array( $this, 'on_get_variation_price'), 10, 4 );

		add_filter(  'woocommerce_update_cart_action_cart_updated', '__return_true');

		// Member discount badges
		add_action( 'woocommerce_before_shop_loop_item_title',   'wc_memberships_show_product_loop_member_discount_badge', 10 );
		add_action( 'woocommerce_before_single_product_summary', 'wc_memberships_show_product_member_discount_badge', 10 );
	}


	/**
	 * Apply purchasing discounts to product price
	 *
	 * @since 1.0.0
	 * @param string|float $price
	 * @param WC_Product $product
	 * @return float|string
	 */
	public function on_get_price( $price, $product ) {

		if ( ! is_user_logged_in() ) {
			return $price;
		}

		$discounted_price = $this->get_discounted_price( $price, $product );

		if ( $discounted_price ) {
			$price = $discounted_price;
		}

		return $price;
	}


	/**
	 * Adjust discounted product price HTML
	 *
	 * @since 1.3.0
	 * @param string $html
	 * @param WC_Product $product
	 * @return float|string
	 */
	public function on_price_html( $html, $product ) {

		/**
		 * Controls whether or not member prices should use discount format when displayed
		 *
		 * @since 1.3.0
		 * @param bool $use_discount_format Defaults to true
		 */
		if ( ! apply_filters( 'wc_memberships_member_prices_use_discount_format', true ) ) {
			return $html;
		}

		$tax_display_mode = get_option( 'woocommerce_tax_display_shop' );

		$this->disable_price_adjustments();

		$base_price       = 'incl' == $tax_display_mode ? $product->get_price_including_tax() : $product->get_price_excluding_tax();
		$product_id       = $product->is_type( 'variation' ) ? $product->variation_id : $product->id;

		$this->enable_price_adjustments();

		if ( ! $this->has_discounted_price( $base_price, $product_id ) ) {
			return $html;
		}

		/**
		 * Controls whether or not member prices should display sale prices as well
		 *
		 * @since 1.3.0
		 * @param bool $display_sale_price Defaults to false
		 */
		$display_sale_price = apply_filters( 'wc_memberships_member_prices_display_sale_price', false );

		add_filter( 'woocommerce_get_variation_prices_hash', array( $this, 'nonmember_variation_prices_hash' ), 10, 3 );

		if ( ! $display_sale_price ) {
			add_filter( 'woocommerce_product_is_on_sale', array( $this, 'disable_sale_price' ) );
		}

		$this->disable_price_adjustments();
		$this->disable_price_html_adjustments();

		$_html = $product->get_price_html();

		$this->enable_price_adjustments();
		$this->enable_price_html_adjustments();

		remove_filter( 'woocommerce_get_variation_prices_hash', array( $this, 'nonmember_variation_prices_hash' ) );

		if ( ! $display_sale_price ) {
			remove_filter( 'woocommerce_product_is_on_sale', array( $this, 'disable_sale_price' ) );
		}

		if ( $html != $_html ) {
			$html = '<del>' . $_html . '</del> <ins> ' . $html . '</ins>';
		}

		return $html;
	}


	/**
	 * Adjust discounted cart item price HTML
	 *
	 * @since 1.3.0
	 * @param string $html
	 * @param array $cart_item
	 * @param string $cart_item_key
	 * @return string
	 */
	public function on_cart_item_price( $html, $cart_item, $cart_item_key ) {

		$product          = $cart_item['data'];
		$tax_display_cart = get_option( 'woocommerce_tax_display_cart' );

		// Temporarily disable our price adjustments
		$this->disable_price_adjustments();

		// In cart, we need to account for tax display
		$price = 'excl' == $tax_display_cart
					 ? $product->get_price_excluding_tax()
					 : $product->get_price_including_tax();

		// Re-enable disable our price adjustments
		$this->enable_price_adjustments();

		if ( $this->has_discounted_price( $price, $product ) ) {

			// In cart, we need to account for tax display
			$discounted_price = 'excl' == $tax_display_cart
												? $product->get_price_excluding_tax()
												: $product->get_price_including_tax();

			/** This filter is documented in class-wc-memberships-member-discounts.php **/
			$use_discount_format = apply_filters( 'wc_memberships_use_discount_format', true );

			if ( $discounted_price < $price && $use_discount_format ) {
				$html = '<del>' . wc_price( $price ) . '</del><ins> ' . wc_price( $discounted_price ) . '</ins>';
			}

		}

		return $html;
	}


	/**
	 * Adjust variation price
	 *
	 * @since 1.3.0
	 * @param int $price
	 * @param WC_Product $product
	 * @param string $min_or_max
	 * @param bool $display
	 * @return int
	 */
	public function on_get_variation_price( $price, $product, $min_or_max, $display ) {

		$min_price        = $price;
		$max_price        = $price;
		$tax_display_mode = get_option( 'woocommerce_tax_display_shop' );

		$children = $product->get_children();

		if ( isset( $children ) && ! empty( $children ) ) {

			foreach ( $children as $variation_id ) {

				if ( $display ) {

					$variation = $product->get_child( $variation_id );

					if ( $variation ) {

						$this->disable_price_adjustments();

						// In display mode, we need to account for taxes
						$base_price = $tax_display_mode == 'incl'
												? $variation->get_price_including_tax()
												: $variation->get_price_excluding_tax();
						$calc_price = $base_price;

						$discounted_price = $this->get_discounted_price( $base_price, $variation->id );

						if ( $discounted_price && $base_price != $discounted_price ) {
							$calc_price = $discounted_price;
						}

						$this->enable_price_adjustments();

					} else {
						$calc_price = '';
					}

				} else {
					$calc_price = get_post_meta( $variation_id, '_price', true );
				}

				if ( $min_price == null || $calc_price < $min_price ) {
					$min_price = $calc_price;
				}

				if ( $max_price == null || $calc_price > $max_price ) {
					$max_price = $calc_price;
				}
			}
		}

		if ( $min_or_max == 'min' ) {
			return $min_price;
		} elseif ( $min_or_max == 'max' ) {
			return $max_price;
		} else {
			return $price;
		}
	}


	/**
	 * Create variable product nonmember prices hash
	 *
	 * @since 1.3.0
	 * @param WC_Product $product
	 * @return string
	 */
	public function nonmember_variation_prices_hash( $data, $product, $display ) {

		$data[] = 'wc-membership-nonmember';
		return $data;
	}


	/**
	 * Disable on sale
	 *
	 * Used to temporarily disable 'on sale' price for products when
	 * generating price HTML
	 *
	 * @since 1.3.0
	 * @param bool $on_sale
	 * @return bool Always false
	 */
	public function disable_on_sale_price( $on_sale ) {
		return false;
	}


	/**
	 * Get product discounted price for member
	 *
	 * @since 1.3.0
	 * @param int $base_price Original price
	 * @param int|WC_product $product Product ID or product object
	 * @param int $user_id Optional. Defaults to current user id.
	 * @return int|null discounted price, or null if no discount applies
	 */
	public function get_discounted_price( $base_price, $product, $user_id = null ) {

		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		if ( ! is_object( $product ) ) {
			$product = wc_get_product( $product );
		}

		$product_id = $product->is_type( 'variation' ) ? $product->variation_id : $product->id;

		if ( ! isset( $this->member_discounted_products[ $user_id . '_' . $product_id ] ) ) {

			$discount_rules = wc_memberships()->rules->get_user_product_purchasing_discount_rules( $user_id, $product_id );

			if ( empty( $discount_rules ) ) {

				$price = null;

			} else {

				$price = $base_price;

				// Find out the discounted price for the current user
				foreach ( $discount_rules as $rule ) {

					switch ( $rule->get_discount_type() ) {

						case 'percentage':
							$discounted_price = $price * ( 100 - $rule->get_discount_amount() ) / 100;
							break;

						case 'amount':
							$discounted_price = max( $price - $rule->get_discount_amount(), 0 );
							break;
					}

					// Make sure that the lowest price gets applied
					if ( $discounted_price < $price ) {
						$price = $discounted_price;
					}
				}

				if ( $price >= $base_price ) {
					$price = null;
				}

			}

			// If the price is lower than product's base price, return the discounted price, null otherwise
			$this->member_discounted_products[ $user_id . '_' . $product_id ] = $price;
		}

		return $this->member_discounted_products[ $user_id . '_' . $product_id ];
	}


	/**
	 * Check if the product is discounted for the user
	 *
	 * @since 1.3.0
	 * @param int $base_price Original price
	 * @param int|WC_product $product_id Product ID or product object
	 * @param int $user_id Optional. Defaults to current user id.
	 * @return bool True if is discounted, false otherwise.
	 */
	public function has_discounted_price( $base_price, $product, $user_id = null ) {

		$discounted_price = $this->get_discounted_price( $base_price, $product, $user_id );

		return (bool) $discounted_price;
	}


	/**
	 * Disable 'on sale' for a product
	 *
	 * @since 1.3.0
	 * @return bool Always false
	 */
	public function disable_sale_price() {
		return false;
	}


	/**
	 * Enable price adjustments
	 *
	 * @since 1.3.0
	 */
	private function enable_price_adjustments() {
		add_filter( 'woocommerce_get_price', array( $this, 'on_get_price' ), 10, 2 );
	}


	/**
	 * Disable price adjustments
	 *
	 * @since 1.3.0
	 */
	private function disable_price_adjustments() {
		remove_filter( 'woocommerce_get_price', array( $this, 'on_get_price' ) );
	}


	/**
	 * Enable price HTML adjustments
	 *
	 * @since 1.3.0
	 */
	private function enable_price_html_adjustments() {
		add_filter( 'woocommerce_variation_price_html', array( $this, 'on_price_html' ), 10, 2 );
		add_filter( 'woocommerce_get_price_html',       array( $this, 'on_price_html' ), 10, 2 );
	}


	/**
	 * Disable price HTML adjustments
	 *
	 * @since 1.3.0
	 */
	private function disable_price_html_adjustments() {

		remove_filter( 'woocommerce_get_price_html',       array( $this, 'on_price_html' ) );
		remove_filter( 'woocommerce_variation_price_html', array( $this, 'on_price_html' ) );
	}

}
