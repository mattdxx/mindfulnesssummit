<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$_product = get_product( $product_id ); ?>

<tr class="item" data-order_item_id="<?php echo $product_id; ?>">

	<td class="check-column"><input type="checkbox" /></td>

	<td class="thumb">
		<?php if ( $_product ) : ?>
			<a href="<?php echo esc_url( admin_url( 'post.php?post=' . absint( $product_id ) . '&action=edit' ) ); ?>" class="tips" data-tip=""><?php echo $_product->get_image( 'shop_thumbnail', array( 'title' => '' ) ); ?></a>
		<?php else : ?>
			<?php echo wc_placeholder_img( 'shop_thumbnail' ); ?>
		<?php endif; ?>

	</td>

	<td class="name">
		<a href="<?php echo esc_url( admin_url( 'post.php?post=' . absint( $product_id ) . '&action=edit' ) ); ?>" class="tips" data-tip="">
			<?php echo $_product->get_formatted_name(); ?>
		</a>
	</td>


	<td class="quantity" width="1%">

		<div class="view" data-item-id='<?php echo $product_id; ?>'>
			<?php echo $product['quantity']; ?>
		</div>
		<div class="edit" style="display:none" data-item-id='<?php echo $product_id; ?>'>
			<input type="num" min="1" autocomplete="off" name="product[<?php echo $product_id; ?>][quantity]" value='<?php echo absint( $product['quantity'] ); ?>'>
		</div>

	</td>

	<td>
		<a class="edit_cart_item" href="javascript:void(0);" data-item-id='<?php echo $product_id; ?>'><img src="<?php echo WC()->plugin_url(); ?>/assets/images/icons/edit.png" alt="Edit" width="14" /></a>
	</td>

</tr>