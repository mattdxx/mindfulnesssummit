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
 * @package   WC-Memberships/Templates
 * @author    SkyVerge
 * @copyright Copyright (c) 2014-2015, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<h2><?php echo apply_filters( 'wc_memberships_my_account_my_memberships_title', __( 'My Memberships', WC_Memberships::TEXT_DOMAIN ) ); ?></h2>

<table class="shop_table shop_table_responsive my_account_memberships">

	<thead>
		<tr>
			<th class="membership-plan"><span class="nobr"><?php esc_html_e( 'Plan', WC_Memberships::TEXT_DOMAIN ); ?></span></th>
			<th class="membership-start-date"><span class="nobr"><?php esc_html_e( 'Signup date', WC_Memberships::TEXT_DOMAIN ); ?></span></th>
			<th class="membership-status"><span class="nobr"><?php esc_html_e( 'Status', WC_Memberships::TEXT_DOMAIN ); ?></span></th>
			<?php
				/**
				 * Fires after the membership columns, before the actions column in my memberships table header
				 *
				 * @since 1.0.0
				 * @param WC_Memberships_User_Membership $user_membership
				 */
				do_action( 'wc_memberships_my_memberships_column_headers' );
			?>
			<th class="membership-actions">&nbsp;</th>
		</tr>
	</thead>

	<tbody>
		<?php foreach ( $customer_memberships as $membership ) : ?>
		<tr class="membership">

			<td class="membership-plan" data-title="<?php esc_attr_e( 'Plan', WC_Memberships::TEXT_DOMAIN ); ?>">
				<?php echo esc_html( $membership->get_plan()->get_name() ); ?>
			</td>

			<td class="membership-start-date" data-title="<?php esc_attr_e( 'Signup Date', WC_Memberships::TEXT_DOMAIN ); ?>">
				<?php if ( $membership->get_start_date( 'timestamp' ) ) : ?>
					<time datetime="<?php echo date( 'Y-m-d', $membership->get_start_date( 'timestamp' ) ); ?>" title="<?php echo esc_attr( $membership->get_start_date( 'timestamp' ) ); ?>"><?php echo date_i18n( get_option( 'date_format' ), $membership->get_start_date( 'timestamp' ) ); ?></time>
				<?php else : ?>
					<?php esc_html_e( 'N/A', WC_Memberships::TEXT_DOMAIN ); ?>
				<?php endif; ?>
			</td>

			<td class="membership-status" style="text-align:left; white-space:nowrap;" data-title="<?php esc_attr_e( 'Status', WC_Memberships::TEXT_DOMAIN ); ?>">
				<?php echo esc_html( wc_memberships_get_user_membership_status_name( $membership->get_status() ) ); ?>
			</td>

			<?php
				/**
				 * Fires after the membership columns, before the actions column in my memberships table
				 *
				 * @since 1.0.0
				 * @param WC_Memberships_User_Membership $user_membership
				 */
				do_action( 'wc_memberships_my_memberships_columns', $membership );
			?>

			<td class="membership-actions" data-title="<?php esc_attr_e( 'Actions', WC_Memberships::TEXT_DOMAIN ); ?>">
				<?php
					$actions = array();

					if ( $membership->is_expired() && $membership->get_plan()->has_products() ) {
						$actions['renew'] = array(
							'url'  => $membership->get_renew_membership_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ),
							'name' => __( 'Renew', WC_Memberships::TEXT_DOMAIN ),
						);
					}

					if ( ! $membership->is_cancelled() && ! $membership->is_expired() && current_user_can( 'wc_memberships_cancel_membership', $membership->get_id() ) ) {
						$actions['cancel'] = array(
							'url'  => $membership->get_cancel_membership_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ),
							'name' => __( 'Cancel', WC_Memberships::TEXT_DOMAIN ),
						);
					}

					/**
					 * Filter membership actions on my account page
					 *
					 * @since 1.0.0
					 * @param array $actions
					 * @param WC_Memberships_User_Membership $membership
					 */
					$actions = apply_filters( 'wc_memberships_my_account_my_memberships_actions', $actions, $membership );

					if ( $actions ) {
						foreach ( $actions as $key => $action ) {
							echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
						}
					}
				?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
