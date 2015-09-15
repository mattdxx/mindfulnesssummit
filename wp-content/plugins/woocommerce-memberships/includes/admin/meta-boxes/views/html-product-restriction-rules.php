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
 * @package   WC-Memberships/Admin/Views
 * @author    SkyVerge
 * @category  Admin
 * @copyright Copyright (c) 2014-2015, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * View for product restriction rules table
 *
 * @since 1.0.0
 * @version 1.0.0
 */
?>

<table class="widefat rules product-restriction-rules js-rules">

	<thead>
		<tr>

			<th class="check-column">
				<input type="checkbox" id="product-restriction-rules-select-all">
				<label for="product-restriction-rules-select-all"> <?php esc_html_e( 'Select all', WC_Memberships::TEXT_DOMAIN ); ?></label>
			</th>

			<?php if ( 'wc_membership_plan' == $post->post_type ) : ?>

			<th class="product-restriction-content-type content-type-column">
				<?php esc_html_e( 'Type', WC_Memberships::TEXT_DOMAIN ); ?>
			</th>

			<th class="product-restriction-objects objects-column">
				<?php esc_html_e( 'Title', WC_Memberships::TEXT_DOMAIN ); ?>
				<img class="help_tip" data-tip="<?php esc_attr_e( 'Search&hellip; or leave blank to apply to all', WC_Memberships::TEXT_DOMAIN ); ?>" src="<?php echo esc_url( WC()->plugin_url() ); ?>/assets/images/help.png" height="16" width="16" />
			</th>

			<?php else : ?>

			<th class="product-restriction-membership-plan membership-plan-column">
				<?php esc_html_e( 'Plan', WC_Memberships::TEXT_DOMAIN ); ?>
			</th>

			<?php endif; ?>

			<th class="product-restriction-access-type access-type-column">
				<?php esc_html_e( 'Only Members Can', WC_Memberships::TEXT_DOMAIN ); ?>
			</th>

			<th class="product-restriction-access-schedule access-schedule-column">
				<?php esc_html_e( 'Accessible', WC_Memberships::TEXT_DOMAIN ); ?>
				<img class="help_tip" data-tip="<?php esc_attr_e( 'When will members gain access to products?', WC_Memberships::TEXT_DOMAIN ) ?>" src="<?php echo esc_url( WC()->plugin_url() ); ?>/assets/images/help.png" height="16" width="16" />
			</th>

		</tr>
	</thead>

	<?php foreach ( $product_restriction_rules as $index => $rule ) {
		require( wc_memberships()->get_plugin_path() . '/includes/admin/meta-boxes/views/html-product-restriction-rule.php' );
	} ?>
	
	<?php 
		$membership_plans = wc_memberships_get_membership_plans( array( 'post_status' => array( 'publish', 'private', 'future', 'draft', 'pending', 'trash' ) ) );
	?>

	<tbody class="norules <?php if ( count( $product_restriction_rules ) > 1 ) : ?>hide<?php endif; ?>">
		<tr>
			<td colspan="<?php echo ( 'wc_membership_plan' == $post->post_type ) ? 5 : 4; ?>">
				<?php if ( 'wc_membership_plan' == $post->post_type ) : ?>
					<?php esc_html_e( "There are no rules yet. Click below to add one.", WC_Memberships::TEXT_DOMAIN ); ?>
				<?php else : ?>
					<?php if ( empty( $membership_plans ) ) : ?>
						<?php esc_html_e( 'To create restriction rules, please', WC_Memberships::TEXT_DOMAIN ); ?> <a target="_blank" href="<?php echo admin_url( 'post-new.php?post_type=wc_membership_plan' ); ?>"><?php esc_html_e( 'Add a Membership Plan', WC_Memberships::TEXT_DOMAIN ); ?></a>.
					<?php else : ?>
						<?php esc_html_e( "This product can be viewed & purchased by all customers. Add a rule to restrict viewing and/or purchasing to members.", WC_Memberships::TEXT_DOMAIN ); ?>
					<?php endif; ?>
				<?php endif; ?>
			</td>
		</tr>
	</tbody>

	<?php if ( 'wc_membership_plan' == $post->post_type || ! empty( $membership_plans ) ) : ?>
	<tfoot>
		<tr>
			<th colspan="<?php echo ( 'wc_membership_plan' == $post->post_type ) ? 5 : 4; ?>">
				<button type="button" class="button button-primary add-rule js-add-rule"><?php esc_html_e( 'Add New Rule', WC_Memberships::TEXT_DOMAIN ); ?></button>
				<button type="button" class="button button-secondary remove-rules js-remove-rules <?php if ( count( $product_restriction_rules ) < 2 ) : ?>hide<?php endif; ?>"><?php esc_html_e( 'Delete Selected', WC_Memberships::TEXT_DOMAIN ); ?></button>
			</th>
		</tr>
	</tfoot>
	<?php endif; ?>

</table>
