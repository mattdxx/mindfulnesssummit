<?php
// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit; ?>
<div class='popmake-ajax-form popmake-registration-form'><?php
	$content = do_shortcode( '[wp-members page=register]' );
	echo wpmem_texturize( $content );
	echo popmake_alm_footer_links( array( 'login', 'recovery' ) ); ?>
</div>
