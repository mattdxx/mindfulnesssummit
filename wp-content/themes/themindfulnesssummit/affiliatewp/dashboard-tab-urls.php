<div id="affwp-affiliate-dashboard-url-generator" class="affwp-tab-content">

<p><b>THANK YOU </b><span style="font-weight: 400;">for stepping up and wanting to share The Mindfulness Summit, a not-for-profit project with a mission to make mindfulness mainstream.</span></p>

<p><span style="font-weight: 400;">The summit, a free online event, hosted between October 1-31st 2015 is designed to make high-quality mindfulness training accessible to everyone, giving easy access to 31 of the world’s leading experts on meditation and mindfulness for a series of online interviews, practice sessions and presentations. </span></p>

<p><b>We want to create a global community of people bringing mindfulness into their lives, whilst making a positive impact on the lives of others. </b><span style="font-weight: 400;">This is why all monies raised from the sale of the summit will be going to mindfulness based charities (you can choose to have your affiliate earnings given to mindfulness charities if you'd like as well or we'll still be happy to send you cash for spreading the summit)</span></p>

<p><b>We believe that mindfulness has the capacity to change the whole world from the inside out, one person at a time.</b></p>

<p>We are asking you to share The Mindfulness Summit with your networks, social channels, website and email list.<br>
<br>
</p>

<div class="gdlr-box-with-icon-ux gdlr-ux" style="opacity: 1; padding-top: 0px; margin-bottom: 0px;"><div style="margin-bottom: 60px;" class="gdlr-item gdlr-box-with-icon-item pos-left type-circle"><div style="background-color: #e78325; padding: 18px 18px 13px 18px;" class="box-with-circle-icon"><i style="color:#ffffff;" class="fa fa-link"></i><br></div><h4 class="box-with-icon-title"><strong>Here's your referral URL</strong></h4><div class="clear"></div><div class="box-with-icon-caption">
<p><strong class="reflink1" style="color:#f62b0a;"><?php printf( __( '%s', 'affiliate-wp' ), esc_url( affwp_get_affiliate_referral_url() ) ); ?>&amp;utm_source=affiliate&amp;utm_campaign=<?php printf( __( '%s', 'affiliate-wp' ), affwp_get_affiliate_id() ); ?></strong></p>

<p>Feel free to make a short Bit.ly url by pasting the above link here: <a href="https://bitly.com/shorten/" target="_blank">https://bitly.com/shorten/</a></p>
</div></div></div>


<div class="gdlr-box-with-icon-ux gdlr-ux" style="opacity: 1; padding-top: 0px; margin-bottom: 0px;"><div style="margin-bottom: 60px;" class="gdlr-item gdlr-box-with-icon-item pos-left type-circle"><div style="background-color: #e78325; padding: 18px 18px 13px 18px;" class="box-with-circle-icon"><i style="color:#ffffff;" class="fa  fa-file-pdf-o"></i><br></div><h4 class="box-with-icon-title"><strong>Download your affiliate pack here</strong></h4><div class="clear"></div><div class="box-with-icon-caption">
<p><a target="_blank" href="http://bit.ly/1Fb1w4N">Download PDF version</a></p>
<p><a target="_blank" href="http://bit.ly/1UBkgS8">Download DOC version</a></p>
</div></div></div>


<div class="gdlr-box-with-icon-ux gdlr-ux" style="opacity: 1; padding-top: 0px; margin-bottom: 0px;"><div style="margin-bottom: 50px;" class="gdlr-item gdlr-box-with-icon-item pos-left type-circle"><div style="background-color: #e78325; padding: 18px 18px 13px 18px;" class="box-with-circle-icon"><i style="color:#ffffff;" class="fa  fa-file-image-o"></i><br></div><h4 class="box-with-icon-title"><strong>Download your affiliate images here</strong></h4><div class="clear"></div><div class="box-with-icon-caption">

<p>All the beautifully designed banners, social media posts, eDM &amp; social headers here.</p>

<p><a target="_blank" href="http://bit.ly/1NRkDrJ">ZIP file</a> (8.2 MB)</p>

<p><a target="_blank" href="http://bit.ly/1Kxq91H">Browse images</a> </p>
</div></div></div>

<p><strong>Your pack includes:</strong>
<ul>
	<li>Newsletter header &amp; copy (Short and Long copy)</li>
	<li>Blog article</li>
	<li>Website &amp; social links (including hashtags and @ tags)</li>
	<li>Sample social media posts</li>
</ul>
</p>

<p>Click on the <a href="/affiliate-area/?tab=creatives">creatives</a> link at the top of this page to find banners for your website with pre encoded affiliate links so you can copy paste onto your website or emails easily. You can also see how many visitors you're sending and how many referral sales you receive (the all summit access pass will start being sold on Oct 1st)</p>

<p>Thank you so much for spreading the word about the mindfulness summit!</p>

<p>Melli and TheMindfulnessSummit team.</p>


<div class="gdlr-divider thick" style="margin: 50px 0 50px 0;"></div>


	<h2><strong><?php _e( 'Referral URL Generator', 'affiliate-wp' ); ?></strong></h2>

	<?php if ( 'id' == affwp_get_referral_format() ) : ?>
  <p><?php printf( __( 'Your affiliate ID is: <strong>%s</strong>', 'affiliate-wp' ), affwp_get_affiliate_id() ); ?></p>
	<?php elseif ( 'username' == affwp_get_referral_format() ) : ?>
		<p><?php printf( __( 'Your affiliate username is: <strong>%s</strong>', 'affiliate-wp' ), affwp_get_affiliate_username() ); ?></p>
	<?php endif; ?>

	<p><?php printf( __( 'Your referral URL is: <strong>%s</strong>', 'affiliate-wp' ), esc_url( affwp_get_affiliate_referral_url() ) ); ?></p>
	<p><?php _e( 'Enter any URL from this website in the form below to generate a referral link!', 'affiliate-wp' ); ?></p>

	<form id="affwp-generate-ref-url" class="affwp-form" method="get" action="#affwp-generate-ref-url">
		<div class="affwp-wrap affwp-base-url-wrap">
			<label for="affwp-url"><?php _e( 'Page URL', 'affiliate-wp' ); ?></label>
			<input type="text" name="url" id="affwp-url" value="<?php echo esc_url( affwp_get_affiliate_base_url() ); ?>" />
		</div>

		<div class="affwp-wrap affwp-referral-url-wrap" <?php if ( ! isset( $_GET['url'] ) ) { echo 'style="display:none;"'; } ?>>
			<label for="affwp-referral-url"><?php _e( 'Referral URL', 'affiliate-wp' ); ?></label>
			<input type="text" id="affwp-referral-url" value="<?php echo esc_url( affwp_get_affiliate_referral_url() ); ?>" />
			<div class="description"><?php _e( '(now copy this referral link and share it anywhere)', 'affiliate-wp' ); ?></div>
		</div>

		<div class="affwp-referral-url-submit-wrap">
			<input type="hidden" class="affwp-affiliate-id" value="<?php echo esc_attr( affwp_get_referral_format_value() ); ?>" />
			<input type="hidden" class="affwp-referral-var" value="<?php echo esc_attr( affiliate_wp()->tracking->get_referral_var() ); ?>" />
			<input type="submit" class="button" value="<?php _e( 'Generate URL', 'affiliate-wp' ); ?>" />
		</div>
	</form>
</div>
