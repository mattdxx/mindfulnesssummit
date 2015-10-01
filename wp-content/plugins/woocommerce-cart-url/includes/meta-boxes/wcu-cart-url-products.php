<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

wp_nonce_field( 'cart_url_meta_box', 'cart_url_meta_box_nonce' );

global $post;

$products = get_post_meta( $post->ID, '_products', true );
?>
<div class="woocommerce_order_items_wrapper wcu-items-wrapper">
	<table cellpadding="0" cellspacing="0" class="woocommerce_order_items wcu_products">
		<thead>
			<tr>

				<th><input type="checkbox" class="check-column" /></th>
				<th class="item" colspan="2"><?php _e( 'Item', 'woocommerce' ); ?></th>

				<th class="quantity"><?php _e( 'Qty', 'woocommerce' ); ?></th>
				<th class="edit"></th>

				<th width="1%">&nbsp;</th>

			</tr>
		</thead>
		<tbody id="order_items_list">

			<?php
			if ( $products ) :

				foreach ( $products as $product_id => $product ) :

					require plugin_dir_path( __FILE__ ) . 'views/html-order-item.php';

				endforeach;

			endif;

		?></tbody>
	</table>
</div>


<p class="bulk_actions">
	<select>
		<option value=""><?php _e( 'Actions', 'woocommerce' ); ?></option>
		<optgroup label="<?php _e( 'Edit', 'woocommerce' ); ?>">
			<option value="delete"><?php _e( 'Delete Lines', 'woocommerce' ); ?></option>
		</optgroup>
	</select>

	<button type="button" class="button do_bulk_action wc-reload" title="<?php _e( 'Apply', 'woocommerce' ); ?>"><span><?php _e( 'Apply', 'woocommerce' ); ?></span></button>
</p>


<p class="add_items">

	<button type="button" class="button add_cart_url_item"><?php _e( 'Add item(s)', 'woocommerce' ); ?></button>
	<input type="hidden" id="add_item_id" name="add_order_items" class="wc-product-search" style="width: 400px; max-width: calc(100% - 110px); float: right;" data-placeholder="<?php _e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-allow-clear='true' />

</p>
<div class="clear"></div>
