<?php

if( !function_exists('gdlr_paypal_form') ){
	function gdlr_paypal_form(){
		global $theme_option;
		
		$user = empty($theme_option['paypal-recipient-email'])? '': $theme_option['paypal-recipient-email'];
		$action = 'https://www.' . ((!empty($theme_option['paypal-action']) && $theme_option['paypal-action'] == 'sandbox')? 'sandbox.': '') . 'paypal.com/cgi-bin/webscr';
		$paypal = (empty($theme_option['enable-paypal']) || $theme_option['enable-paypal'] == 'enable')? 'Yes': 'No';
		$currency_code = empty($theme_option['paypal-currency-code'])? 'USD': $theme_option['paypal-currency-code'];

		$post_val = gdlr_decode_preventslashes(get_post_meta(get_the_ID(), 'post-option', true));
		$post_options = empty($post_val)? array(): json_decode($post_val, true);
		
		ob_start();
?>
<div class="gdlr-paypal-form-wrapper">
	<h3 class="gdlr-paypal-form-head"><?php echo __('You are booking for :','gdlr_translate') . ' <span>' . get_the_title() . '</span>'; ?></h3>
	<form class="gdlr-paypal-form" action="<?php echo esc_attr($action); ?>" method="post" data-ajax="<?php echo AJAX_URL; ?>" >
		<div class="gdlr-paypal-fields">
			<div class="six columns"><span class="gdlr-head"><?php echo __('Name *', 'gdlr_translate'); ?></span>
				<input class="gdlr-require" type="text" name="gdlr-name">
			</div>
			<div class="six columns gdlr-right"><span class="gdlr-head"><?php echo __('Last Name *', 'gdlr_translate'); ?></span>
				<input class="gdlr-require" type="text" name="gdlr-last-name">
			</div>
			<div class="clear"></div>
			<div class="six columns"><span class="gdlr-head"><?php echo __('Email *', 'gdlr_translate'); ?></span>
				<input class="gdlr-require gdlr-email" type="text" name="gdlr-email">
			</div>
			<div class="six columns gdlr-right"><span class="gdlr-head"><?php echo __('Phone', 'gdlr_translate'); ?></span>
				<input type="text" name="gdlr-phone">
			</div>		
			<div class="clear"></div>
			<div class="six columns"><span class="gdlr-head"><?php echo __('Address', 'gdlr_translate'); ?></span>
				<textarea name="gdlr-address"></textarea>
			</div>
			<div class="six columns gdlr-right"><span class="gdlr-head"><?php echo __('Additional Note', 'gdlr_translate'); ?></span>
				<textarea name="gdlr-additional-note"></textarea>
			</div>		
			<div class="clear"></div>
		</div>
		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="business" value="<?php echo esc_attr($user); ?>">
		<input type="hidden" name="item_name" value="<?php echo get_the_title(); ?>">
		<input type="hidden" name="ticket_id" value="<?php echo get_the_ID(); ?>">
		<input type="hidden" name="invoice" value="">
		<input type="hidden" name="amount" value="">    
		<input type="hidden" name="return" value="<?php echo get_permalink(); ?>">
		<input type="hidden" name="no_shipping" value="0">
		<input type="hidden" name="no_note" value="1">
		<input type="hidden" name="currency_code" value="<?php echo esc_attr($currency_code); ?>">
		<input type="hidden" name="lc" value="AU">
		<input type="hidden" name="bn" value="PP-BuyNowBF">
		<input type="hidden" class="gdlr-paypal-action" name="action" value="">
		<input type="hidden" name="security" value="<?php echo wp_create_nonce('gdlr-paypal-create-nonce'); ?>">
		<div class="gdlr-notice email-invalid" ><?php echo __('Invalid Email Address ', 'gdlr_translate'); ?></div>
		<div class="gdlr-notice require-field" ><?php echo __('Please fill all required fields', 'gdlr_translate'); ?></div>
		<div class="gdlr-notice alert-message" ></div>
		<div class="gdlr-paypal-loader" ></div>
		
		<input type="button" class="gdlr-button-mail gdlr-button with-border" value="<?php _e('Book By E-Mail And We Will Contact You Back', 'gdlr_translate'); ?>" >
		<?php if( $paypal == 'Yes' && !empty($post_options['price']) ){ ?>
		<div class="gdlr-paypal-or">
			<span class="gdlr-or-text"><?php _e('OR', 'gdlr_translate'); ?></span>
		</div>
		<input type="button" class="gdlr-button-paypal gdlr-button with-border" value="<?php _e('Check Out Via PayPal', 'gdlr_translate'); ?>" >
		<?php } ?>
		<div class="clear"></div>
	</form>
</div>
<?php	
		$ret = ob_get_contents();
		ob_end_clean();
		
		return $ret;
	}	
}

// ajax to send contact form
add_action( 'wp_ajax_send_contact_form_mail', 'gdlr_send_contact_form_mail' );
add_action( 'wp_ajax_nopriv_send_contact_form_mail', 'gdlr_send_contact_form_mail' );
if( !function_exists('gdlr_send_contact_form_mail') ){
	function gdlr_send_contact_form_mail(){
		global $theme_option;
	
		$ret = array();
		if( false && !check_ajax_referer('gdlr-paypal-create-nonce', 'security', false) ){
			$ret['status'] = 'failed'; 
			$ret['message'] = __('The page has been expired. Please refresh the page to try this again.', 'gdlr_translate');
		}else{
			$recipient = empty($theme_option['paypal-recipient-name'])? '': $theme_option['paypal-recipient-name'];
			
			$post_val = gdlr_decode_preventslashes(get_post_meta($_POST['ticket_id'], 'post-option', true));
			$post_options = empty($post_val)? array(): json_decode($post_val, true);	
			if(empty($post_options['price'])){
				$ticket_price =  __('Free', 'gdlr-conference');
			}else{
				$ticket_price = function_exists('gdlr_ticket_money_format')? gdlr_ticket_money_format($post_options['price']): $post_options['price'];
			}

			$headers  = 'From: ' . $recipient . ' <' . $_POST['business'] . '>' . "\r\n";
			$message  = __('Ticket Title :', 'gdlr_translate') . ' ' . $_POST['item_name'] . "\r\n";
			$message .= __('Name :', 'gdlr_translate') . ' ' . $_POST['gdlr-name'] . ' ' . $_POST['gdlr-last-name'] . "\r\n";
			$message .= __('Email :', 'gdlr_translate') . ' ' . $_POST['gdlr-email'] . "\r\n";
			$message .= __('Phone :', 'gdlr_translate') . ' ' . $_POST['gdlr-phone'] . "\r\n";
			$message .= __('Price :', 'gdlr_translate') . ' ' . $ticket_price . "\r\n";
			$message .= __('Address :', 'gdlr_translate') . ' ' . $_POST['gdlr-address'] . "\r\n";
			$message .= __('Additional Message :', 'gdlr_translate') . ' ' . $_POST['gdlr-additional-note'] . "\r\n";
	
			if( wp_mail($_POST['business'], __('You receive new ticket booking message', 'gdlr_translate'), $message, $headers ) ){
				$ret['status'] = 'success'; 
				$ret['message'] = __('Your message was sent successfully.', 'gdlr_translate');
			}else{
				$ret['status'] = 'failed'; 
				$ret['message'] = __('Failed to send your message. Please try later or contact the administrator by another method.', 'gdlr_translate');
				$ret['log'] = $message;
			}
		}
		die(json_encode($ret));		
	}
}

// ajax to save form data
add_action( 'wp_ajax_save_paypal_form', 'gdlr_save_paypal_form' );
add_action( 'wp_ajax_nopriv_save_paypal_form', 'gdlr_save_paypal_form' );
if( !function_exists('gdlr_save_paypal_form') ){
	function gdlr_save_paypal_form(){
		$ret = array();
		if( false && !check_ajax_referer('gdlr-paypal-create-nonce', 'security', false) ){
			$ret['status'] = 'failed'; 
			$ret['message'] = __('The page has been expired. Please refresh the page to try this again.', 'gdlr_translate');
		}else{
			$record = get_option('gdlr_paypal',array());
			$item_id = sizeof($record); 

			$post_val = gdlr_decode_preventslashes(get_post_meta($_POST['ticket_id'], 'post-option', true));
			$post_options = empty($post_val)? array(): json_decode($post_val, true);				

			$record[$item_id]['name'] = $_POST['gdlr-name'];
			$record[$item_id]['last-name'] = $_POST['gdlr-last-name'];
			$record[$item_id]['email'] = $_POST['gdlr-email'];
			$record[$item_id]['phone'] = $_POST['gdlr-phone'];
			$record[$item_id]['address'] = $_POST['gdlr-address'];
			$record[$item_id]['addition'] = $_POST['gdlr-additional-note'];
			$record[$item_id]['post-id'] = $_POST['ticket_id'];
			$record[$item_id]['amount'] = $post_options['price'];
			
			$ret['status'] = 'success'; 
			$ret['message'] = __('Redirecting to paypal', 'gdlr_translate');
			$ret['amount'] = $post_options['price'];
			$ret['invoice'] = $item_id;
			
			update_option('gdlr_paypal',$record);
		}
		die(json_encode($ret));
	}
}

if( isset($_GET['paypal']) ){
	global $theme_option;
	$paypal_action = 'https://www.' . ((!empty($theme_option['paypal-action']) && $theme_option['paypal-action'] == 'sandbox')? 'sandbox.': '') . 'paypal.com/cgi-bin/webscr';
	
	// STEP 1: read POST data
	 
	// Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
	// Instead, read raw POST data from the input stream. 
	$raw_post_data = file_get_contents('php://input');
	$raw_post_array = explode('&', $raw_post_data);
	$myPost = array();
	foreach ($raw_post_array as $keyval) {
	  $keyval = explode ('=', $keyval);
	  if (count($keyval) == 2)
		 $myPost[$keyval[0]] = urldecode($keyval[1]);
	}
	// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
	$req = 'cmd=_notify-validate';
	if(function_exists('get_magic_quotes_gpc')) {
	   $get_magic_quotes_exists = true;
	} 
	foreach ($myPost as $key => $value) {        
	   if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) { 
			$value = urlencode(stripslashes($value)); 
	   } else {
			$value = urlencode($value);
	   }
	   $req .= "&$key=$value";
	}
	 
	 
	// Step 2: POST IPN data back to PayPal to validate
	$ch = curl_init($paypal_action);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
	 
	if( !($res = curl_exec($ch)) ) {
		curl_close($ch);
		exit;
	}
	// update_option('gdlr_paypal', '1:' . $ret . ':2:' . curl_error($ch));
	// update_option('gdlr_paypal', $_POST);
	curl_close($ch);
	
	// inspect IPN validation result and act accordingly
	if( strcmp ($res, "VERIFIED") == 0 ) {
		$recipient = empty($theme_option['paypal-recipient-name'])? '': $theme_option['paypal-recipient-name'];
	
		$record = get_option('gdlr_paypal', array());
		$num = $_POST['invoice'];
		$record[$num]['status'] = $_POST['payment_status'];
		$record[$num]['txn_id'] = $_POST['txn_id'];
		$record[$num]['pay_amount'] = $_POST['mc_gross'] . ' ' . $_POST['mc_currency'];
		
		$item_name = $_POST['item_name'];

		if( $_POST['payment_status'] == 'Completed' ){
		
			// send the mail
			$headers  = 'From: ' . $recipient . ' <' . $_POST['receiver_email'] . '>' . "\r\n";
			$message  = __('Thank you very much for your purchasing for', 'gdlr_translate') . ' ' . $_POST['item_name'] . "\r\n";
			$message .= __('Below is details of your purchasing.', 'gdlr_translate') . "\r\n";
			$message .= __('Name of Recipient :', 'gdlr_translate') . ' ' . $_POST['receiver_email'] . "\r\n";
			$message .= __('Name :', 'gdlr_translate') . ' ' . $record[$num]['name'] . ' ' . $record[$num]['last-name'] . "\r\n";
			$message .= __('Date :', 'gdlr_translate') . ' ' . $_POST['payment_date'] . "\r\n";
			$message .= __('Amount :', 'gdlr_translate') . ' ' . $record[$num]['amount'] . "\r\n";
			$message .= __('Pay Amount :', 'gdlr_translate') . ' ' . $record[$num]['pay_amount'] . "\r\n";
			$message .= __('Transaction ID :', 'gdlr_translate') . ' ' . $record[$num]['txn_id'] . "\r\n";
			$message .= __('Regards,', 'gdlr_translate') . ' ' . $recipient;
	
			if( wp_mail($record[$num]['email'], __('Thank you for your purchasing', 'gdlr_translate'), $message, $headers ) ){
				$record[$num]['mail_status'] = 'complete';
			}else{
				$record[$num]['mail_status'] = 'failed';
			}
			
			$headers  = 'From: ' . $recipient . "\r\n";
			$message  = __('Ticket Name :', 'gdlr_translate') . ' ' . $_POST['item_name'] . "\r\n";
			$message .= __('Name :', 'gdlr_translate') . ' ' . $record[$num]['name'] . ' ' . $record[$num]['last-name'] . "\r\n";
			$message .= __('Email :', 'gdlr_translate') . ' ' . $record[$num]['email'] . "\r\n";
			$message .= __('Phone :', 'gdlr_translate') . ' ' . $record[$num]['phone'] . "\r\n";
			$message .= __('Address :', 'gdlr_translate') . ' ' . $record[$num]['address'] . "\r\n";
			$message .= __('Additional Message :', 'gdlr_translate') . ' ' . $record[$num]['addition'] . "\r\n";
			$message .= __('Date :', 'gdlr_translate') . ' ' . $_POST['payment_date'] . "\r\n";
			$message .= __('Amount :', 'gdlr_translate') . ' ' . $record[$num]['amount'] . "\r\n";
			$message .= __('Pay Amount :', 'gdlr_translate') . ' ' . $record[$num]['pay_amount'] . "\r\n";
			$message .= __('Transaction ID :', 'gdlr_translate') . ' ' . $record[$num]['txn_id'];

			if( wp_mail($_POST['receiver_email'], __('You received new payment', 'gdlr_translate'), $message, $headers ) ){
				$record[$num]['notify_status'] = 'complete';
			}else{
				$record[$num]['notify_status'] = 'failed';
			}			
		}
		update_option('gdlr_paypal', $record);
	}else if( strcmp ($res, "INVALID") == 0 ){
		echo "The response from IPN was: " . $res;
	}
}else if( isset($_GET['paypal_print']) && is_user_logged_in() ){
	print_r(get_option('gdlr_paypal', array()));
	die();
}else if( isset($_GET['paypal_clear']) && is_user_logged_in() ){
	delete_option('gdlr_paypal');
	echo 'Option Deleted';
	die();
}
?>