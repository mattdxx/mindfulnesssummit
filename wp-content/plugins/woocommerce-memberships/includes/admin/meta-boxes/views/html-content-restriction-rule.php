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
 * View for a content restriction rule
 *
 * @since 1.0.0
 * @version 1.0.0
 */
?>
<tbody class="rule content-restriction-rule content-restriction-rule-<?php echo esc_attr( $index ); ?> <?php if ( ! $rule->current_user_can_edit() || ! $rule->current_context_allows_editing() ) : ?>disabled<?php endif; ?>">

<tr>
	<td class="check-column">
		<p class="form-field">
			<label for="_content_restriction_rules_<?php echo esc_attr( $index ); ?>_checkbox"><?php esc_html_e( 'Select', WC_Memberships::TEXT_DOMAIN ); ?>:</label>

			<?php if ( $rule->current_user_can_edit() && $rule->current_context_allows_editing() || ! $rule->content_type_exists() ) : ?>
			<input type="checkbox" id="_content_restriction_rules_<?php echo esc_attr( $index ); ?>_checkbox"  />
			<?php endif; ?>

			<input type="hidden" name="_content_restriction_rules[<?php echo esc_attr( $index ); ?>][membership_plan_id]" value="<?php echo esc_attr( $rule->get_membership_plan_id() ); ?>" />
			<input type="hidden" name="_content_restriction_rules[<?php echo esc_attr( $index ); ?>][id]" class="js-rule-id" value="<?php echo esc_attr( $rule->get_id() ); ?>" />
			<input type="hidden" name="_content_restriction_rules[<?php echo esc_attr( $index ); ?>][remove]" class="js-rule-remove" value="" />

			<?php if ( $rule->get_membership_plan_id() != $post->ID && $rule->has_objects() ) : ?>
				<?php foreach ( $rule->get_object_ids() as $id ) : ?>
				<input type="hidden" name="_content_restriction_rules[<?php echo esc_attr( $index ); ?>][object_ids][]" value="<?php echo esc_attr( $id ); ?>" />
				<?php endforeach; ?>
			<?php endif; ?>
		</p>
	</td>

	<?php if ( $rule->get_membership_plan_id() == $post->ID ) : ?>

	<td class="content-restriction-content-type content-type-column">
		<p class="form-field">
			<label for="_content_restriction_rules_<?php echo esc_attr( $index ); ?>_content_type_key"><?php esc_html_e( 'Type', WC_Memberships::TEXT_DOMAIN ); ?>:</label>
			<select name="_content_restriction_rules[<?php echo esc_attr( $index ); ?>][content_type_key]" id="_content_restriction_rules_<?php echo esc_attr( $index ); ?>_content_type_key" class="js-content-type" <?php if ( ! $rule->current_user_can_edit() ) : ?>disabled<?php endif; ?>>
				<optgroup label="<?php esc_attr_e( 'Post types', WC_Memberships::TEXT_DOMAIN ); ?>">
					<?php foreach ( $content_restriction_content_type_options['post_types'] as $key => $post_type ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $rule->get_content_type_key() ); ?> <?php if ( ! ( current_user_can( $post_type->cap->edit_posts ) && current_user_can( $post_type->cap->edit_others_posts ) ) ) : ?>disabled<?php endif; ?>><?php echo esc_html( $post_type->label ); ?></option>
					<?php endforeach; ?>
				</optgroup>
				<optgroup label="<?php esc_attr_e( 'Taxonomies', WC_Memberships::TEXT_DOMAIN ); ?>">
					<?php foreach ( $content_restriction_content_type_options['taxonomies'] as $key => $taxonomy ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $rule->get_content_type_key() ); ?> <?php if ( ! ( current_user_can( $taxonomy->cap->manage_terms ) && current_user_can( $taxonomy->cap->edit_terms ) ) ) : ?>disabled<?php endif; ?> ><?php echo esc_html( $taxonomy->label ); ?></option>
					<?php endforeach; ?>
				</optgroup>
				<?php if ( ! $rule->is_new() && ! $rule->content_type_exists() ) : ?>
					<option value="<?php echo esc_attr( $rule->get_content_type_key() ); ?>" selected><?php echo esc_html( $rule->get_content_type_key() ); ?></option>
				<?php endif; ?>
			</select>
		</p>
	</td>

	<td class="content-restriction-objects objects-column">
		<p class="form-field">
			<label for="_content_restriction_rules_<?php echo esc_attr( $index ); ?>_object_ids"><?php esc_html_e( 'Title', WC_Memberships::TEXT_DOMAIN ); ?>:</label>
			<?php if ( SV_WC_Plugin_Compatibility::is_wc_version_gte_2_3() ) : ?>
				<input type="hidden" class="wc-memberships-object-search js-object-ids" style="width: 50%;" id="_content_restriction_rules_<?php echo esc_attr( $index ); ?>_object_ids" name="_content_restriction_rules[<?php echo esc_attr( $index ); ?>][object_ids]" data-placeholder="<?php esc_attr_e( 'Search&hellip; or leave blank to apply to all', WC_Memberships::TEXT_DOMAIN ); ?>" data-action="<?php echo esc_attr( $rule->get_object_search_action_name() ); ?>" data-multiple="true" data-selected="<?php
					$json_ids = array();

					if ( $rule->has_objects() ) {
						foreach ( $rule->get_object_ids() as $object_id ) {

							if ( $rule->get_object_label( $object_id ) ) {
								$json_ids[ $object_id ] = wp_kses_post( html_entity_decode( $rule->get_object_label( $object_id ) ) );
							}
						}
					}

					echo esc_attr( wc_memberships()->wp_json_encode( $json_ids ) );
				?>" value="<?php echo esc_attr( implode( ',', array_keys( $json_ids ) ) ); ?>" <?php if ( ! $rule->current_user_can_edit() ) : ?>disabled<?php endif; ?> />
			<?php else : ?>
				<select name="_content_restriction_rules[<?php echo esc_attr( $index ); ?>][object_ids][]" id="_content_restriction_rules_<?php echo esc_attr( $index ); ?>_object_ids" class="ajax_chosen_select_objects js-object-ids" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Search&hellip; or leave blank to apply to all', WC_Memberships::TEXT_DOMAIN ); ?>" <?php if ( ! $rule->current_user_can_edit() ) : ?>disabled<?php endif; ?>>
					<?php
						if ( $rule->has_objects() ) {

							foreach ( $rule->get_object_ids() as $object_id ) {

								if ( $rule->get_object_label( $object_id ) ) {
									echo '<option value="' . esc_attr( $object_id ) . '" selected="selected">' . esc_html( $rule->get_object_label( $object_id ) ) . '</option>';
								}
							}
						}
					?>
				</select>
			<?php endif; ?>
		</p>
	</td>

	<?php else : ?>

	<td class="content-restriction-membership-plan membership-plan-column">
		<p class="form-field">
			<label for="_content_restriction_rules_<?php echo esc_attr( $index ); ?>_membership_plan_id"><?php esc_html_e( 'Plan', WC_Memberships::TEXT_DOMAIN ); ?>:</label>
			<select name="_content_restriction_rules[<?php echo esc_attr( $index ); ?>][membership_plan_id]" id="_content_restriction_rules_<?php echo esc_attr( $index ); ?>_membership_plan_id" <?php if ( ! $rule->current_user_can_edit() || ! $rule->current_context_allows_editing() ) : ?>disabled<?php endif; ?>>
				<?php foreach ( $membership_plan_options as $id => $label ) : ?>
					<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $id, $rule->get_membership_plan_id() ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
	</td>

	<?php endif; ?>

	<td class="content-restriction-access-schedule access-schedule-column">
		<p class="form-field">
			<label><?php esc_html_e( 'Accessible', WC_Memberships::TEXT_DOMAIN ); ?>:</label>

			<span class="rule-control-group">
				<?php $current_access_period = $rule->grants_immediate_access() ? 'immediate' : 'specific'; ?>
				<?php foreach ( $access_schedule_period_toggler_options as $value => $label ) : ?>
					<label class="label-radio">
						<input type="radio" name="_content_restriction_rules[<?php echo esc_attr( $index ); ?>][access_schedule]" class="js-access-schedule-period-selector js-schedule-type" value="<?php echo esc_attr( $value ); ?>" <?php checked( $value, $current_access_period ); ?> <?php if ( ! $rule->current_user_can_edit() || ! $rule->current_context_allows_editing() ) : ?>disabled<?php endif; ?> />
						<?php echo esc_html( $label ); ?></label>
				<?php endforeach; ?>
			</span>

			<span class="rule-control-group rule-control-group-access-schedule-specific js-hide-if-access-schedule-immediate <?php if ( 'immediate' == $rule->get_access_schedule() ) : ?>hide<?php endif;?>">

				<?php ob_start(); ?>
				<input type="number" id="_content_restriction_rules_<?php echo esc_attr( $index ); ?>_access_schedule_amount" name="_content_restriction_rules[<?php echo esc_attr( $index ); ?>][access_schedule_amount]" min="0" class="access_schedule-amount" value="<?php echo esc_html( $rule->get_access_schedule_amount() ); ?>" <?php if ( ! $rule->current_user_can_edit() || ! $rule->current_context_allows_editing() ) : ?>disabled<?php endif; ?> />
				<?php $amount = ob_get_clean(); ?>

				<?php ob_start(); ?>
				<select name="_content_restriction_rules[<?php echo esc_attr( $index ); ?>][access_schedule_period]" class="access_schedule-period js-access-schedule-period-selector" <?php if ( ! $rule->current_user_can_edit() || ! $rule->current_context_allows_editing() ) : ?>disabled<?php endif; ?>>
					<?php foreach ( $period_options as $key => $label ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $rule->get_access_schedule_period() ); ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
				<?php $period = ob_get_clean(); ?>

<?php
/* Translators: the variables represent following values:
* %1$s - an opening HTML tag
* %2$s - a closing HTML tag
* %3$s - a numeric value
* %4$s - a period, such as day(s), month(s), etc
*
* The result will look something like: "after 3 day(s)"
*/
?>
				<?php printf(
					__( '%1$safter%2$s %3$s %4$s', WC_Memberships::TEXT_DOMAIN ),
					'<label for="_content_restriction_rules_' . esc_attr( $index ) . '_access_schedule_amount" class="access_schedule-amount-label">',
					'</label>',
					$amount, // already escaped
					$period // already escaped
				); ?>

			</span>

			<?php
				/**
				 * Fires after the access schedule field is displayed for a restriction rule
				 *
				 * @since 1.0.0
				 * @param WC_Memberships_Membership_Plan_Rule $rule
				 * @param int $index Row index
				 */
				do_action( 'wc_memberships_restriction_rule_access_schedule_field', $rule, $index );
			?>

		</p>
	</td>

</tr>

<?php if ( ! $rule->current_user_can_edit() || ! $rule->current_context_allows_editing() ) : ?>
<tr class="disabled-notice">
	<td class="check-column"></td>
	<td colspan="<?php echo ( 'wc_membership_plan' == $post->post_type ) ? 4 : 3; ?>">
	<?php if ( ! $rule->is_new() && ! $rule->content_type_exists() ) : ?>
		<span class="description"><?php esc_html_e( 'This rule applies to a content type created by a plugin or theme that has been deactivated or deleted.', WC_Memberships::TEXT_DOMAIN ); ?></span>
	<?php elseif ( ! $rule->current_user_can_edit() ) : ?>
		<span class="description"><?php esc_html_e( 'You are not allowed to edit this rule.', WC_Memberships::TEXT_DOMAIN ); ?></span>
	<?php else : ?>
		<span class="description"><?php printf( esc_html__( 'This rule cannot be edited here because it applies to multiple content objects. You can %sedit this rule on the membership plan screen%s.', WC_Memberships::TEXT_DOMAIN ), '<a href="' . esc_url( get_edit_post_link( $rule->get_membership_plan_id() ) ) . '">', '</a>' ); ?></span>
	<?php endif; ?>
	</td>
</tr>
<?php endif; ?>

</tbody>
