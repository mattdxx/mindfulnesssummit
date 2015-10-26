<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $order ) : ?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<p><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'woocommerce' ); ?></p>

		<p><?php
			if ( is_user_logged_in() )
				_e( 'Please attempt your purchase again or go to your account page.', 'woocommerce' );
			else
				_e( 'Please attempt your purchase again.', 'woocommerce' );
		?></p>

		<p>
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My Account', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</p>

	<?php else : ?>


		<p>Thank you supporting mindfulness charities and upgrading your access pass.</p>

		<p>Your account has been upgraded to a Full Access Pass for the mindfulness summit content.</p>

		<p>All the content will be unlocked day by day through October 2015 (and live streamed content will be recorded for you).</p>

		<p>You will have access to all the premium content (transcripts, video & audio downloads) that have been unlocked so far on each of the individual session pages. The latest session released is always accessible here:<br/>
			<a href="http://themindfulnesssummit.com/live/" target="_blank">http://themindfulnesssummit.com/live/</a></p>

		<p>Access each of the past sessions from here (we will update the links after each day has gone live):</br>
			<a href="http://themindfulnesssummit.com/event-schedule/" target="_blank">http://themindfulnesssummit.com/event-schedule/</a></p>

		<p>We will also post additional content and bonuses into you 'My Account' page here:<br/>
			<a href="http://themindfulnesssummit.com/my-account/" target="_blank">http://themindfulnesssummit.com/my-account/</a><br/>
			This will continue to be populated throughout October to add extra links, the bonuses etc.</p>

		<p>In early November once all the content is complete, the live stream recorded and bonus content uploaded, we will send you a reminder.</p>

		<p>Thanks for practicing mindfulness and helping others!</p>

		<p>Melli, Matt and the MrsMindfulness team.</p>
		<p></p>
		<p></p>



		<ul class="order_details">
			<li class="order">
				<?php _e( 'Order Number:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_order_number(); ?></strong>
			</li>
			<li class="date">
				<?php _e( 'Date:', 'woocommerce' ); ?>
				<strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
			</li>
			<li class="total">
				<?php _e( 'Total:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_formatted_order_total(); ?></strong>
			</li>
			<?php if ( $order->payment_method_title ) : ?>
			<li class="method">
				<?php _e( 'Payment Method:', 'woocommerce' ); ?>
				<strong><?php echo $order->payment_method_title; ?></strong>
			</li>
			<?php endif; ?>
		</ul>
		<div class="clear"></div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
	<?php do_action( 'woocommerce_thankyou', $order->id ); ?>

<?php else : ?>

	<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>

<?php endif; ?>

<?php add_facebook_tracking_pixel(); ?>