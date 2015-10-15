<?php
/*
Plugin Name: Optimize Wordpress
Description: Optimize wordpress to speed up the whole process
Version: 1.4.10
Author: Stratos Nikolaidis
Author URI: https://gr.linkedin.com/in/stratosnikolaidis
Plugin URI: https://gr.linkedin.com/in/stratosnikolaidis
License: Toptal
*/


/**
 * Fix Mandrill reset password issue
 * Fix provided by @paulveevers
 */
add_filter( 'retrieve_password_message', 'forgot_password_email', 10, 2 );
function forgot_password_email($message, $key) {

  // Replace first open bracket
  $message = str_replace('<', '', $message);

  // Replace second open bracket
  $message = str_replace('>', '', $message);

  // Convert line returns to <br>'s
  $message = str_replace("\r\n", '<br>', $message);

  return $message;
}


/**
 * Disable plugins for specific pages in order to speed up the page load
 */
// add_filter( 'option_active_plugins', 'disable_plugins_on_demand' );
function disable_plugins_on_demand($plugins){
	// Template
    // if(strpos($_SERVER['REQUEST_URI'], '/store/') === FALSE AND strpos($_SERVER['REQUEST_URI'], '/wp-admin/') === FALSE) {
    //     $key = array_search( 'cart66/cart66.php' , $plugins );
    //     if ( false !== $key ) {
    //         unset( $plugins[$key] );
    //     }
    // }
    return $plugins;
}


/* v.1.1.8
How to use the following scripts. Right now, the shortcode [wcm_restrict plans="free-members"] is like this:
In case the image is higher than the textbox, we can add the extra class called "extra-height" and also, we need to
wrap the button in a <div class="gdlr-button-wrapper"> wrapper.

<div class="gdlr-item gdlr-column-shortcode">
    <div class="gdlr-shortcode-wrapper">
        ...

By using this code, we change it like this:

<div class="gdlr-item gdlr-column-shortcode with-image">
    <div class="gdlr-image-wrapper static">
        <img src="http://mindsummit.staging.wpengine.com/wp-content/uploads/2015/10/r1.jpg">
    </div>
    <div class="gdlr-shortcode-wrapper">
        ...

And the magic begins. The page by default will display the gdlr-image section, and on hover (or touch) the
page will fade out the image and replace it with the the gdlr-shortcode section (fade in).

Yes, it's responsive also. :)
*/
$add_gdlr_with_image_scripts = false;
add_action('init', 'register_gdlr_with_image_script_script');
add_action('wp_footer', 'print_gdlr_with_image_script_script');
function register_gdlr_with_image_script_script() {
    wp_register_script('gdlr-with-image', plugin_dir_url(__FILE__).'assets/js/gdlr-with-image.min.js', array(), '1.1.8', true);
    wp_register_style('gdlr-with-image', plugin_dir_url(__FILE__).'assets/css/gdlr-with-image.min.css', array(), '1.1.8');
}

function print_gdlr_with_image_script_script() {
	global $add_gdlr_with_image_scripts;

	if (!$add_gdlr_with_image_scripts)
		return;

	wp_print_styles('gdlr-with-image');
	wp_print_scripts('gdlr-with-image');
}

/*
Shortcode for the image switcher
examples:
[gdlr_image_switcher image="http://cdn.themindfulnesssummit.com/wp-content/uploads/2015/10/Joeseph-Goldstein-preview3.jpg"]
[gdlr_image_switcher image="http://cdn.themindfulnesssummit.com/wp-content/uploads/2015/10/Joeseph-Goldstein-preview3.jpg" extraheight="1"]
[gdlr_image_switcher image="http://cdn.themindfulnesssummit.com/wp-content/uploads/2015/10/Joeseph-Goldstein-preview3.jpg" extraheight="1" count="9" session="rick-hanson"]
*/
function gdlr_image_switcher_func( $atts ) {
	global $add_gdlr_with_image_scripts;
	$add_gdlr_with_image_scripts = true;

    $params = shortcode_atts( array(
        'image' => '',
        'count' => '1',
        'session' => 'mark-williams',
        'extraheight' => '0',
    ), $atts );

    $extraheight = $params['extraheight'] != '0';
    $has_image = isset($params['image']) && ($params['image'] != '');

    ob_start();
?>
<?php if ($has_image): ?>
	<div class="gdlr-item gdlr-column-shortcode with-image <?php if ($extraheight): ?>extra-height<?php endif ?>">
		<div class="gdlr-image-wrapper static">
			<img src="<?php echo $params['image'] ?>" alt="" />
		</div>
<?php else: ?>
	<div class="gdlr-item gdlr-column-shortcode">
<?php endif ?>
		<div class="gdlr-shortcode-wrapper">
			<div class="gdlr-box-with-icon-ux gdlr-ux" style="opacity: 1; padding-top: 0px; margin-bottom: 0px;">
				<div class="gdlr-item gdlr-box-with-icon-item pos-left type-normal" style="background-color: #303c41 !important; color: #fff !important; margin: 0; position: relative;">
					<div class="box-with-icon-caption" style="font-size: 14px;">The free period to access this video and audio content has ended.
						You can unlock it to stream and download 100% of the content for the summit immediately when you donate to mindfulness charities by upgrading to a full access pass.
						<div class="gdlr-button-wrapper">
							<a class="gdlr-button small" href="/?add_to_cart=4067" target="_self">Donate for Full Access Pass</a>
						</div>
						<p>View today’s free live session <a href="/live">here</a></p>
						<p>Day <?php echo $params['count'] ?>’s Introduction to Mindfulness is available <a href="/sessions/<?php echo $params['session'] ?>/">here</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
	$contents = ob_get_contents();
	ob_end_clean();

    return $contents;
}
add_shortcode( 'gdlr_image_switcher', 'gdlr_image_switcher_func' );
