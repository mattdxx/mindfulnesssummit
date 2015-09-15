<?php if(!function_exists(ciusan_submit_donation)){ function ciusan_submit_donation(){ ?>
<div class="wrap">
	<h2><?php echo __('Submit a Donation for Ciusan Plugin', 'Ciusan'); ?></h2><hr/>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
		<legend><h4>Donate with PayPal</h4></legend>
		<input type="hidden" name="cmd" value="_donations">
		<input type="hidden" name="business" value="paypal@ciusan.com">
		<input type="hidden" name="lc" value="GB">
		<input type="hidden" name="item_name" value="Ciusan Plugin">
		<input type="hidden" name="item_number" value="Ciusan Plugin">
		<input type="hidden" name="no_note" value="0">
		<input type="hidden" name="currency_code" value="USD">
		<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHostedGuest">
		<input type="image" src="https://www.paypalobjects.com/webstatic/en_US/btn/btn_donate_pp_142x27.png" border="0" name="submit" alt="">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
	</form>
<hr/>
<form action="https://www.moneybookers.com/app/payment.pl" method="post" target="_blank">
<fieldset>
<img src="https://www.skrill.com/fileadmin/templates/images/skrill-logo.svg"/><br/>
<legend><h4>Donate with Skrill/Moneybookers</h4></legend>
<input name="pay_to_email" type="hidden" value="greshbe@gmail.com“" />
Please enter the amount you would like to give<br />
<input name="return_url" type="hidden" value="http://www.yourdomain.com/thanks.htm“" />
<input name="language" type="hidden" value="EN" />
<select name="currency" size="1">
<option>Select a currency</option>
<option value="USD">US dollar</option>
<option value="GBP">GB pound</option>
<option value="EUR">Euro</option>
<option value="JPY">Yen</option>
<option value="CAD">Canadian $</option>
<option value="AUD">Australian $</option>
</select>
amount:
<input name="amount" size="10" type="text" value="5.00" />
<input anhblog.net“="" help="" name="detail1_description" support="" to="" type="hidden" value="donation" />
<input anhblog.net“="" help="" name="detail1_text" support="" to="" type="hidden" value="donation" />
<input a="" alt="click" anhblog.net”="" donation="" make="" to="" type="submit" value="Donate!" />
</fieldset>
</form>
</div>
<?php }} ?>