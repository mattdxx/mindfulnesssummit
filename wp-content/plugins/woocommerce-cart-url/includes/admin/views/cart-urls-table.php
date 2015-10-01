<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Cart URLs table.
 *
 * Display table with all the user configured Cart URLs.
 *
 * @author		Shop Plugins
 * @package 	WooCommerce Cart URL
 * @version		1.0.0
 */

$cart_urls = get_posts( array( 'posts_per_page' => '-1', 'post_type' => 'cart_url', 'post_status' => array( 'draft', 'publish' ) ) );

?><tr valign="top">
	<th scope="row" class="titledesc">
		<?php _e( 'Cart URLs', 'woocommerce-cart-url' ); ?>:<br />
	</th>
	<td class="forminp" id="cart_urls_table">

		<table class='wp-list-table wcu-table widefat'>
			<thead>
				<tr>
					<th style='padding-left: 10px; width: 120px;'><?php _e( 'Title', 'woocommerce-cart-url' ); ?></th>
					<th style='padding-left: 10px;'><?php _e( 'Cart URL', 'woocommerce-cart-url' ); ?></th>
					<th style='padding-left: 10px; width: 60px;'><?php _e( '# products', 'woocommerce-cart-url' ); ?></th>
				</tr>
			</thead>

			<tbody><?php

				$i = 0;
				foreach ( $cart_urls as $cart_url ) :

					$products = get_post_meta( $cart_url->ID, '_products', true );
					$alt 		= ( $i++ ) % 2 == 0 ? 'alternate' : '';
					?><tr class='<?php echo $alt; ?>'>

						<td>
							<strong>
								<a href='<?php echo get_edit_post_link( $cart_url->ID ); ?>' class='row-title' title='<?php _e( 'Edit Cart URL', 'woocommerce-cart-url' ); ?>'>
									<?php echo ! empty( $cart_url->post_title ) ? $cart_url->post_title : __( 'No title', 'woocommerce-cart-url' ); ?>
								</a>
							</strong>
							<div class='row-actions'>
								<span class='edit'>
									<a href='<?php echo get_edit_post_link( $cart_url->ID ); ?>' title='<?php _e( 'Edit Cart URL', 'woocommerce-cart-url' ); ?>'>
										<?php _e( 'Edit', 'woocommerce-cart-url' ); ?>
									</a>
									 |
								</span>
								<span class='trash'>
									<a href='<?php echo get_delete_post_link( $cart_url->ID ); ?>' title='<?php _e( 'Delete Cart URL', 'woocommerce-cart-url' ); ?>'>
										<?php _e( 'Delete', 'woocommerce-cart-url' ); ?>
									</a>
								</span>
							</div>
						</td>

						<td>
							<input type='text' readonly="readonly" style='width: 90%;' value='<?php echo WooCommerce_Cart_Url()->generate_url( $cart_url->ID ); ?>'>
						</td>
						<td><?php
							echo count( $products );
						?></td>
					</tr><?php

				endforeach;

				if ( empty( $products ) ) :

					?><tr>
						<td colspan='3'><?php _e( 'There are no Cart URLs. Yet...', 'woocommerce-cart-url' ); ?></td>
					</tr><?php

				endif;

			?></tbody>

			<tfoot>
				<tr>
					<th colspan='4' style='padding-left: 10px;'>
						<a href='<?php echo admin_url( 'post-new.php?post_type=cart_url' ); ?>' class='add button'><?php _e( 'Add Cart URL', 'wapl' ); ?></a>
					</th>
				</tr>
			</tfoot>
		</table>
	</td>
</tr>