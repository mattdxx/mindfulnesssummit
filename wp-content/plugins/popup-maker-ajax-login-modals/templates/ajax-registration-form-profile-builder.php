<?php
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit; ?>
<div class='popmake-ajax-form popmake-registration-form'><?php
	echo do_shortcode( '[wppb-register]' );
	echo popmake_alm_footer_links( array( 'login', 'recovery' ) ); ?>
</div>
