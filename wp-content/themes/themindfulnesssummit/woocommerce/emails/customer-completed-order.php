<?php
/**
 * Customer completed order email (plain text)
 *
 * @author		WooThemes
 * @package		WooCommerce/Templates/Emails/Plain
 * @version		2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<!DOCTYPE html>
<html dir="<?php echo is_rtl() ? 'rtl' : 'ltr'?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo get_bloginfo( 'name', 'display' ); ?></title>
</head>
<body <?php echo is_rtl() ? 'rightmargin' : 'leftmargin'; ?>="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">

	<img src="http://themindfulnesssummit.com/wp-content/uploads/2015/10/EDM_Header-fixed.jpg" />
	<p>Thank you supporting mindfulness charities and upgrading your access pass.</p>

	<p>Your account has been upgraded to a Full Access Pass for the mindfulness summit content.</p>

	<p>All the content will be unlocked day by day through October 2015 (and live streamed content will be recorded for you).</p>

	<p>You will have access to all the premium content (transcripts, video & audio downloads) that have been unlocked so far on each of the individual session pages. The latest session released is always accessible here:<br/>
		http://themindfulnesssummit.com/live/</p>

	<p>Access each of the past sessions from here (we will update the links after each day has gone live):</br>
		http://themindfulnesssummit.com/event-schedule/</p>

	<p>We will also post additional content and bonuses into you 'My Account' page here:<br/>
		http://themindfulnesssummit.com/my-account/<br/>
		This will continue to be populated throughout October to add extra links, the bonuses etc.</p>

	<p>In early November once all the content is complete, the live stream recorded and bonus content uploaded, we will send you a reminder.</p>

	<p>Thanks for practicing mindfulness and helping others!</p>

	<p>Melli, Matt and the MrsMindfulness team.</p>
	<p></p>
	<p></p>
<?php
do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text );

echo strtoupper( sprintf( __( 'Order number: %s', 'woocommerce' ), $order->get_order_number() ) ) . "<br/>";
echo date_i18n( __( 'jS F Y', 'woocommerce' ), strtotime( $order->order_date ) ) . "<br/>";

do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, false );

echo "<br/>" . $order->email_order_items_table( true, false, true, '', '', true );

echo "<br/>==========<br/><br/>";

if ( $totals = $order->get_order_item_totals() ) {
	foreach ( $totals as $total ) {
		echo $total['label'] . "\t " . $total['value'] . "<br/>";
	}
}

//echo "<br/>=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

//do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text );

//do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, true );

//echo "<br/>=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=<br/><br/>";

//echo apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) );

?>
</body>
</html>
<?php
