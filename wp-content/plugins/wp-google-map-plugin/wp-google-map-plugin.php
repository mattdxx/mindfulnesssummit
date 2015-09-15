<?php 
/*
Plugin Name: WP Google Map Plugin
Description:  Display Google Maps in Pages, Posts, Sidebar or Custom Templates. It’s Responsive, Multi-Lingual, Multi-Site Supported.
Author: flippercode
Version: 2.3.6
Author URI: http://www.flippercode.com
*/

register_activation_hook( __FILE__, 'wpgmp_network_propagate' );

add_action( 'plugins_loaded', 'wpgmp_load_plugin_languages' );

function wpgmp_load_plugin_languages() {
  load_plugin_textdomain( 'wpgmp_google_map', false, dirname( plugin_basename( __FILE__ ) ).'/languages/' ); 
}

/**
 * This function used to install required tables in the database on time of activation.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
 
  function wpgmp_network_propagate($network_wide)
        {
				if ( is_multisite() && $network_wide ) { // See if being activated on the entire network or one blog
					global $wpdb;

					// Get this so we can switch back to it later
					$currentblog = $wpdb->blogid;
					// For storing the list of activated blogs
					$activated = array();

					// Get all blogs in the network and activate plugin on each one
					$sql = "SELECT blog_id FROM {$wpdb->blogs}";
					$blog_ids = $wpdb->get_col($wpdb->prepare($sql,null));
					foreach ($blog_ids as $blog_id) {
						switch_to_blog($blog_id);
						wpgmp_activation();
						
					}
			 
					// Switch back to the current blog
					switch_to_blog($currentblog);

					// Store the array for a later function
					update_site_option('wpgmp_activated', $activated);
				} else { // Running on a single blog
					wpgmp_activation();
				}
		} // END public static function wprpw_network_propagate()
 
function wpgmp_activation() {
  global $wpdb;	
  $map_location = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."map_locations` (
  				  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  				  `location_title` varchar(255) DEFAULT NULL,
  				  `location_address` varchar(255) DEFAULT NULL,
  				  `location_draggable` varchar(255) DEFAULT NULL,
 				  `location_latitude` varchar(255) DEFAULT NULL,
  				  `location_longitude` varchar(255) DEFAULT NULL, 
  				  `location_messages` text DEFAULT NULL,
  				  `location_marker_image` text DEFAULT NULL,
  				  `location_group_map` int(11) DEFAULT NULL,
  				  `location_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  				  PRIMARY KEY (`location_id`)
				  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
 $wpdb->query($map_location);
 
 $create_map = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."create_map` (
  			   `map_id` int(11) NOT NULL AUTO_INCREMENT,
  			   `map_title` varchar(255) DEFAULT NULL,
  			   `map_width` varchar(255) DEFAULT NULL,
  			   `map_height` varchar(255) DEFAULT NULL,
  			   `map_zoom_level` varchar(255) DEFAULT NULL,
  			   `map_type` varchar(255) DEFAULT NULL,
  			   `map_scrolling_wheel` varchar(255) DEFAULT NULL,
 			   `map_visual_refresh` varchar(255) DEFAULT NULL,
  			   `map_45imagery` varchar(255) DEFAULT NULL,
  			   `map_street_view_setting` text DEFAULT NULL,
  			   `map_route_direction_setting` text DEFAULT NULL,
  			   `map_all_control` text DEFAULT NULL,
  			   `map_info_window_setting` text DEFAULT NULL,
  			   `style_google_map` text DEFAULT NULL,
  			   `map_locations` text DEFAULT NULL,
  			   `map_layer_setting` text DEFAULT NULL,
  			   `map_polygon_setting` text DEFAULT NULL,
  			   `map_polyline_setting` text DEFAULT NULL,
  			   `map_cluster_setting` text DEFAULT NULL,
  			   `map_overlay_setting` text DEFAULT NULL,
  			   PRIMARY KEY (`map_id`)
			   ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
 $wpdb->query($create_map);

 $group_map = "CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."group_map` (
  			  `group_map_id` int(11) NOT NULL AUTO_INCREMENT,
  			  `group_map_title` varchar(255) DEFAULT NULL,
  			  `group_marker` text DEFAULT NULL,
  			  `group_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  			  PRIMARY KEY (`group_map_id`)
			  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
 $wpdb->query($group_map);
}

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * This function used to register required scripts.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
 
function wpgmp_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	 wpgmp_google_map_load();

}

/**
 * This function used to register required styles in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
 
function wpgmp_admin_styles() {
	wp_enqueue_style('thickbox');
}

$wpgmp_containers=array('map'); 

/**
 * This function used to display navigations menu in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */

function wpgmp_google_map_page() {
    define("wpgmp_plugin_permissions", "add_users");
   $pagehook1 = add_menu_page(
        __("WP Google Map", "wpgmp_google_map"),
        __("WP Google Map", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_google_map_pro",
        "wpgmp_admin_overview"
    );
   $pagehook2 = add_submenu_page(
        "wpgmp_google_map_pro",
        __("Add Locations", "wpgmp_google_map"),
        __("Add Locations", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_add_location",
        "wpgmp_add_locations"
    );
   $pagehook3 = add_submenu_page(
        "wpgmp_google_map_pro",
        __("Manage Locations", "wpgmp_google_map"),
        __("Manage Locations", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_manage_location",
        "wpgmp_manage_locations"
    );
    
	$pagehook4 = add_submenu_page(
        "wpgmp_google_map_pro",
        __("Create Map", "wpgmp_google_map"),
        __("Create Map", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_create_map",
        "wpgmp_create_map"
    );
	$pagehook5 = add_submenu_page(
        "wpgmp_google_map_pro",
        __("Manage Map", "wpgmp_google_map"),
        __("Manage Map", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_google_wpgmp_manage_map",
        "wpgmp_manage_map"
    );
	
	$pagehook6 = add_submenu_page(
        "wpgmp_google_map_pro",
        __("Create Marker Group", "wpgmp_google_map"),
        __("Create Marker Group", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_google_wpgmp_create_group_map",
        "wpgmp_create_group_map"
    );
	
	$pagehook7 = add_submenu_page(
        "wpgmp_google_map_pro",
        __("Manage Marker Groups", "wpgmp_google_map"),
        __("Manage Marker Groups", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_google_wpgmp_manage_group_map",
        "wpgmp_manage_group_map"
    );
	$pagehook8 = add_submenu_page(
        "wpgmp_google_map_pro",
        __("Settings", "wpgmp_google_map"),
        __("Settings", "wpgmp_google_map"),
        wpgmp_plugin_permissions,
        "wpgmp_google_settings",
        "wpgmp_settings"
    );
    
   add_action('load-'.$pagehook1, 'load_color_js');
   add_action('load-'.$pagehook2, 'load_color_js');
   add_action('load-'.$pagehook3, 'load_color_js');

   add_action('load-'.$pagehook4, 'load_color_js');
   add_action('load-'.$pagehook5, 'load_color_js');
   add_action('load-'.$pagehook6, 'load_color_js');

   add_action('load-'.$pagehook6, 'load_color_js');
   add_action('load-'.$pagehook7, 'load_color_js');
   add_action('load-'.$pagehook8, 'load_color_js');


}

function load_color_js(){
wp_enqueue_style(
		'google_bootstrap_css',
		plugins_url( '/css/bootstrap.css' , __FILE__ ));	

wp_enqueue_script('wpgmp_map_preview',"http://maps.google.com/maps/api/js?libraries=places&region=uk&language=en&sensor=false");
		

}


/**
 * This function used to show map on front end side.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
 
function wpgmp_show_location_in_map($atts, $content=null){
 ob_start();

wpgmp_google_map_load();	

 global $wpdb;
 extract( shortcode_atts( array(
		'zoom' => get_option('wpgmp_zoomlevel'),
		'width' => get_option('wpgmp_mapwidth'),
		'height' => get_option('wpgmp_mapheight'),
		'title' => 'WP Google Map',
		'class' => 'map',
		'center_latitude' => get_option('wpgmp_centerlatitude'),
		'center_longitude' => get_option('wpgmp_centerlongitude'),
		'container_id' => 'map',
		'id' => ''
 ),$atts));
	
 $icon= isset($atts['icon']) ? $atts['icon'] : '';
 include_once dirname(__FILE__).'/class-google-map.php';
 $map = new Wpgmp_Google_Map();
  
 $map_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."create_map where map_id=%d",$atts['id']));
 
 $unserialize_map_street_view_setting = unserialize($map_data->map_street_view_setting);
 $unserialize_map_control_setting = unserialize($map_data->map_all_control);
 $unserialize_map_info_window_setting = unserialize($map_data->map_info_window_setting);
 $unserialize_map_layer_setting = unserialize($map_data->map_layer_setting);
  
 if( !empty($map_data) )
 {
	$un_loc_add = unserialize($map_data->map_locations);
 	$loc_data = $wpdb->get_row($wpdb->prepare("SELECT location_address,location_latitude,location_longitude FROM ".$wpdb->prefix."map_locations where location_id=%d",$un_loc_add[0]));
 
 if( !empty($center_latitude) ) {
	$map->center_lat = $center_latitude;
 } else {
	$map->center_lat = $loc_data->location_latitude;
 }
 
 if( !empty($center_longitude) ) {
	 $map->center_lng = $center_longitude;
 } else {
	$map->center_lng = $loc_data->location_longitude;
 }
    
 if( !empty($unserialize_map_street_view_setting['street_control']) ) {
	 
	$map->street_control = $unserialize_map_street_view_setting['street_control'];
	$map->street_view_close_button = $unserialize_map_street_view_setting['street_view_close_button'];
	$map->links_control = $unserialize_map_street_view_setting['links_control'];
	$map->street_view_pan_control = $unserialize_map_street_view_setting['street_view_pan_control'];  
 }
  
  
   $map->map_type=$map_data->map_type;
  
  if( empty($map_data->map_width) ) {
	 $map->map_width = $width;
  } else {
	 $map->map_width = $map_data->map_width;
  }	
	
  if( empty($map_data->map_height) ) {
	 $map->map_height = $height;
  } else {
	 $map->map_height = $map_data->map_height;
  }
  
  $map->map_scrolling_wheel =$map_data->map_scrolling_wheel;
  $map->map_pan_control =$unserialize_map_control_setting['pan_control'];
  $map->map_zoom_control =$unserialize_map_control_setting['zoom_control'];
  $map->map_type_control =$unserialize_map_control_setting['map_type_control'];
  $map->map_scale_control =$unserialize_map_control_setting['scale_control'];
  $map->map_street_view_control =$unserialize_map_control_setting['street_view_control'];
  $map->map_overview_control =$unserialize_map_control_setting['overview_map_control'];
  
  
  $map->visualrefresh =$map_data->map_visual_refresh;
  $map->map_layers=$unserialize_map_layer_setting['choose_layer'];

  if( empty($map_data->map_zoom_level) ) {
		$map->zoom = $zoom;
  } else {
		$map->zoom = $map_data->map_zoom_level;
  }
  
 
}

if( !empty($atts['id']) ) {
	
$map_locations = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."create_map where map_id=%d",$atts['id']));
$un_info_window_setting = unserialize($map_locations->map_info_window_setting);
 
   	$map_address = unserialize($map_locations->map_locations);
	
	if( $map_address!='' ) {
		$address[] = array();
		
   	foreach($map_address as $map_ad) {
   	
	$map_locations_records = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."map_locations where location_id=%d",$map_ad));
    
	$group_marker = $wpdb->get_row($wpdb->prepare("SELECT group_marker FROM ".$wpdb->prefix."group_map where group_map_id=%d",$map_locations_records->location_group_map));
	
	$wpgm_marker =  get_option('wpgmp_default_marker');
	
	$unmess_info_message = unserialize(base64_decode($map_locations_records->location_messages));
	
	$loc_image_src = '';
	
	if( !empty($group_marker->group_marker) ) {
		$loc_image_src = $group_marker->group_marker;
	} elseif( !empty($map_locations_records->location_marker_image) ) {
		$loc_image_src = $map_locations_records->location_marker_image;
	} elseif( !empty($wpgm_marker) ) {
		$loc_image_src = $wpgm_marker;
	}
	
	$latitude = $map_locations_records->location_latitude;
	$longitude = $map_locations_records->location_longitude;
	$title = $map_locations_records->location_title;
	$dragg = $map_locations_records->location_draggable;
		
	$address['first']['message'] = $unmess_info_message['googlemap_infowindow_message_one'];
	
	$address = array_filter($address);
		
		if( $address['first']['message']!='' ) {
			
			$map->addMarker($latitude,$longitude,$un_info_window_setting['info_window'],$title,$address,$loc_image_src,'',$dragg);
		} else {
			
			wp_print_scripts( 'wpgmp_map' );
			
			$new_loc_adds = array();
			
			$new_loc_adds = $map_locations_records->location_address;
			
			$address_coordinates = wpgmp_get_address_coordinates( $new_loc_adds );
			
			$map->addMarker($latitude,$longitude,$un_info_window_setting['info_window'],$title,$new_loc_adds,$loc_image_src,'',$dragg);
				
	    }
	
   }
  }
} elseif( $content ) {
	wp_print_scripts( 'wpgmp_map' );
	if( empty($zoom) || empty($width) || empty($height) || empty($title) ) {
		$map->zoom = 14;
		$map->width = '600';
		$map->height = '400';
		$map->title = 'WP Google Map Plugin';
	} else {
		$map->zoom = $zoom;
		$map->width = $width;
		$map->height = $height;
		$map->title = $title;
	}
	
	$address = '';
	$coordinates = wpgmp_get_coordinates( $content );
	$address = '<h3>'.$coordinates["address"].'</h3>';
	$address .= '<p>Latitude='.$coordinates["lat"].'</p>';
	$address .= '<p>Longitude='.$coordinates["lng"].'</p>';
	$map->center_lat = $coordinates['lat'];
	$map->center_lng = $coordinates['lng'];
	$map->addMarker($map->center_lat,$map->center_lng,'true',$map->title,$address);
 
   if( !is_array( $coordinates ) )
   return;
} else {
	
	return "Thank you for using this plugin. Please <a href='".admin_url('admin.php?page=wpgmp_add_location')."'>Add your locations</a> or set plugin <a href='".admin_url('admin.php?page=wpgmp_google_settings')."'>Settings</a>.";
}
	
 echo $map->showmap();
 $content =  ob_get_contents();
 ob_clean();
 
 return $content;
}

/**
 * This function used to show success/failure message in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
 
function wpgmp_settings(){
?>
<div class="wpgmp-wrap"> 
<div class="col-md-11">   
<div id="icon-options-general" class="icon32"><br></div>
<h3><span class="glyphicon glyphicon-asterisk"></span><?php _e( 'WP Google Map Plugin Settings', 'wpgmp_google_map' ) ?></h3>
<div class="wpgmp-overview">
        <form method="post" action="options.php">  
            <?php wp_nonce_field('update-options') ?>  
      <p>
<a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=wpgmp_add_location"><?php _e( 'Click Here', 'wpgmp_google_map' ) ?></a>&nbsp; <?php _e( 'to add a new location or', 'wpgmp_google_map' ) ?>&nbsp;<a href="<?php echo site_url() ?>/wp-admin/admin.php?page=wpgmp_manage_location"><?php _e( 'Browse', 'wpgmp_google_map' ) ?></a>&nbsp; <?php _e( 'your existings locations.', 'wpgmp_google_map' ) ?>
 </p>
       
      <div class="form-horizontal">
    <div class="col-md-4 left"><label for="wpgmp_zoomlevel"><?php _e( 'Zoom Level', 'wpgmp_google_map' ) ?></label></div>
   <div class="col-md-7"><input type="text" class="form-control" name="wpgmp_zoomlevel" size="45" value="<?php echo get_option('wpgmp_zoomlevel'); ?>" />
<p class="description"><?php _e( 'Choose Zoom Level between 1 to 14. Default is 4.', 'wpgmp_google_map' ) ?> </p></div>
           	
    <div class="col-md-4 left"><label for="wpgmp_centerlatitude"><?php _e( 'Center Latitude', 'wpgmp_google_map' ) ?></label></div>
   <div class="col-md-7"><input type="text"  class="form-control" name="wpgmp_centerlatitude" size="45" value="<?php echo get_option('wpgmp_centerlatitude'); ?>" />
<p class="description"><?php _e( 'Write down center location on the map.', 'wpgmp_google_map' ) ?></p></div>
       
        	
    <div class="col-md-4 left"><label for="wpgmp_centerlongitude"><?php _e( 'Center Longitude', 'wpgmp_google_map' ) ?></label></div>
   <div class="col-md-7"><input type="text" class="form-control" name="wpgmp_centerlongitude" size="45" value="<?php echo get_option('wpgmp_centerlongitude'); ?>" />
<p class="description"><?php _e( 'Write down center location on the map.', 'wpgmp_google_map' ) ?></p></div>
       
        	
    <div class="col-md-4 left"><label for="wpgmp_language"><?php _e( 'Select Language', 'wpgmp_google_map' ) ?></label></div>
   <div class="col-md-7">
<select name="wpgmp_language" class="form-control">
 <option value="en"<?php selected(get_option('wpgmp_language'),'en') ?>><?php _e( 'ENGLISH', 'wpgmp_google_map' ) ?></option>
 <option value="ar"<?php selected(get_option('wpgmp_language'),'ar') ?>><?php _e( 'ARABIC', 'wpgmp_google_map' ) ?></option>
 <option value="eu"<?php selected(get_option('wpgmp_language'),'eu') ?>><?php _e( 'BASQUE', 'wpgmp_google_map' ) ?></option>
 <option value="bg"<?php selected(get_option('wpgmp_language'),'bg') ?>><?php _e( 'BULGARIAN', 'wpgmp_google_map' ) ?></option>
 <option value="bn"<?php selected(get_option('wpgmp_language'),'bn') ?>><?php _e( 'BENGALI', 'wpgmp_google_map' ) ?></option>
 <option value="ca"<?php selected(get_option('wpgmp_language'),'ca') ?>><?php _e( 'CATALAN', 'wpgmp_google_map' ) ?></option>
 <option value="cs"<?php selected(get_option('wpgmp_language'),'cs') ?>><?php _e( 'CZECH', 'wpgmp_google_map' ) ?></option>
 <option value="da"<?php selected(get_option('wpgmp_language'),'da') ?>><?php _e( 'DANISH', 'wpgmp_google_map' ) ?></option>
 <option value="de"<?php selected(get_option('wpgmp_language'),'de') ?>><?php _e( 'GERMAN', 'wpgmp_google_map' ) ?></option>
 <option value="el"<?php selected(get_option('wpgmp_language'),'el') ?>><?php _e( 'GREEK', 'wpgmp_google_map' ) ?></option>
 <option value="en-AU"<?php selected(get_option('wpgmp_language'),'en-AU') ?>><?php _e( 'ENGLISH (AUSTRALIAN)', 'wpgmp_google_map' ) ?></option>
 <option value="en-GB"<?php selected(get_option('wpgmp_language'),'en-GB') ?>><?php _e( 'ENGLISH (GREAT BRITAIN)', 'wpgmp_google_map' ) ?></option>
 <option value="es"<?php selected(get_option('wpgmp_language'),'es') ?>><?php _e( 'SPANISH', 'wpgmp_google_map' ) ?></option>
 <option value="fa"<?php selected(get_option('wpgmp_language'),'fa') ?>><?php _e( 'FARSI', 'wpgmp_google_map' ) ?></option>
 <option value="fi"<?php selected(get_option('wpgmp_language'),'fi') ?>><?php _e( 'FINNISH', 'wpgmp_google_map' ) ?></option>
 <option value="fil"<?php selected(get_option('wpgmp_language'),'fil') ?>><?php _e( 'FILIPINO', 'wpgmp_google_map' ) ?></option>
 <option value="fr"<?php selected(get_option('wpgmp_language'),'fr') ?>><?php _e( 'FRENCH', 'wpgmp_google_map' ) ?></option>
 <option value="gl"<?php selected(get_option('wpgmp_language'),'gl') ?>><?php _e( 'GALICIAN', 'wpgmp_google_map' ) ?></option>
 <option value="gu"<?php selected(get_option('wpgmp_language'),'gu') ?>><?php _e( 'GUJARATI', 'wpgmp_google_map' ) ?></option>
 <option value="hi"<?php selected(get_option('wpgmp_language'),'hi') ?>><?php _e( 'HINDI', 'wpgmp_google_map' ) ?></option>
 <option value="hr"<?php selected(get_option('wpgmp_language'),'hr') ?>><?php _e( 'CROATIAN', 'wpgmp_google_map' ) ?></option>
 <option value="hu"<?php selected(get_option('wpgmp_language'),'hu') ?>><?php _e( 'HUNGARIAN', 'wpgmp_google_map' ) ?></option>
 <option value="id"<?php selected(get_option('wpgmp_language'),'id') ?>><?php _e( 'INDONESIAN', 'wpgmp_google_map' ) ?></option>
 <option value="it"<?php selected(get_option('wpgmp_language'),'it') ?>><?php _e( 'ITALIAN', 'wpgmp_google_map' ) ?></option>
 <option value="iw"<?php selected(get_option('wpgmp_language'),'iw') ?>><?php _e( 'HEBREW', 'wpgmp_google_map' ) ?></option>
 <option value="ja"<?php selected(get_option('wpgmp_language'),'ja') ?>><?php _e( 'JAPANESE', 'wpgmp_google_map' ) ?></option>
 <option value="kn"<?php selected(get_option('wpgmp_language'),'kn') ?>><?php _e( 'KANNADA', 'wpgmp_google_map' ) ?></option>
 <option value="ko"<?php selected(get_option('wpgmp_language'),'ko') ?>><?php _e( 'KOREAN', 'wpgmp_google_map' ) ?></option>
 <option value="lt"<?php selected(get_option('wpgmp_language'),'lt') ?>><?php _e( 'LITHUANIAN', 'wpgmp_google_map' ) ?></option>
 <option value="lv"<?php selected(get_option('wpgmp_language'),'lv') ?>><?php _e( 'LATVIAN', 'wpgmp_google_map' ) ?></option>
 <option value="ml"<?php selected(get_option('wpgmp_language'),'ml') ?>><?php _e( 'MALAYALAM', 'wpgmp_google_map' ) ?></option>
 <option value="mr"<?php selected(get_option('wpgmp_language'),'mr') ?>><?php _e( 'MARATHI', 'wpgmp_google_map' ) ?></option>
 <option value="nl"<?php selected(get_option('wpgmp_language'),'nl') ?>><?php _e( 'DUTCH', 'wpgmp_google_map' ) ?></option>
 <option value="no"<?php selected(get_option('wpgmp_language'),'no') ?>><?php _e( 'NORWEGIAN', 'wpgmp_google_map' ) ?></option>
 <option value="pl"<?php selected(get_option('wpgmp_language'),'pl') ?>><?php _e( 'POLISH', 'wpgmp_google_map' ) ?></option>
 <option value="pt"<?php selected(get_option('wpgmp_language'),'pt') ?>><?php _e( 'PORTUGUESE', 'wpgmp_google_map' ) ?></option>
 <option value="pt-BR"<?php selected(get_option('wpgmp_language'),'pt-BR') ?>><?php _e( 'PORTUGUESE (BRAZIL)', 'wpgmp_google_map' ) ?></option>
 <option value="pt-PT"<?php selected(get_option('wpgmp_language'),'pt-PT') ?>><?php _e( 'PORTUGUESE (PORTUGAL)', 'wpgmp_google_map' ) ?></option>
 <option value="ro"<?php selected(get_option('wpgmp_language'),'ro') ?>><?php _e( 'ROMANIAN', 'wpgmp_google_map' ) ?></option>
 <option value="ru"<?php selected(get_option('wpgmp_language'),'ru') ?>><?php _e( 'RUSSIAN', 'wpgmp_google_map' ) ?></option>
 <option value="sk"<?php selected(get_option('wpgmp_language'),'sk') ?>><?php _e( 'SLOVAK', 'wpgmp_google_map' ) ?></option>
 <option value="sl"<?php selected(get_option('wpgmp_language'),'sl') ?>><?php _e( 'SLOVENIAN', 'wpgmp_google_map' ) ?></option>
 <option value="sr"<?php selected(get_option('wpgmp_language'),'sr') ?>><?php _e( 'SERBIAN', 'wpgmp_google_map' ) ?></option>
 <option value="sv"<?php selected(get_option('wpgmp_language'),'sv') ?>><?php _e( 'SWEDISH', 'wpgmp_google_map' ) ?></option>
 <option value="tl"<?php selected(get_option('wpgmp_language'),'tl') ?>><?php _e( 'TAGALOG', 'wpgmp_google_map' ) ?></option>
 <option value="ta"<?php selected(get_option('wpgmp_language'),'ta') ?>><?php _e( 'TAMIL', 'wpgmp_google_map' ) ?></option>
 <option value="te"<?php selected(get_option('wpgmp_language'),'te') ?>><?php _e( 'TELUGU', 'wpgmp_google_map' ) ?></option>
 <option value="th"<?php selected(get_option('wpgmp_language'),'th') ?>><?php _e( 'THAI', 'wpgmp_google_map' ) ?></option>
 <option value="tr"<?php selected(get_option('wpgmp_language'),'tr') ?>><?php _e( 'TURKISH', 'wpgmp_google_map' ) ?></option>
 <option value="uk"<?php selected(get_option('wpgmp_language'),'uk') ?>><?php _e( 'UKRAINIAN', 'wpgmp_google_map' ) ?></option>
 <option value="vi"<?php selected(get_option('wpgmp_language'),'vi') ?>><?php _e( 'VIETNAMESE', 'wpgmp_google_map' ) ?></option>
 <option value="zh-CN"<?php selected(get_option('wpgmp_language'),'zh-CN') ?>><?php _e( 'CHINESE (SIMPLIFIED)', 'wpgmp_google_map' ) ?></option>
 <option value="zh-TW"<?php selected(get_option('wpgmp_language'),'zh-TW') ?>><?php _e( 'CHINESE (TRADITIONAL)', 'wpgmp_google_map' ) ?></option>
 </select>
<p class="description"><?php _e( 'Default is English.', 'wpgmp_google_map' ) ?></p>
</div>
 <input type="hidden" name="action" value="update" />  
<input type="hidden" name="page_options" value="wpgmp_zoomlevel,wpgmp_centerlatitude,wpgmp_centerlongitude,wpgmp_mapwidth,wpgmp_mapheight,wpgmp_language,wpgmp_default_marker,wpgmp_mashup" />  
   <div class="col-md-4 left">  </div><div class="col-md-7">  
    <input type="submit" name="submit" id="submit" class="btn btn-lg btn-primary" value="<?php _e( 'Save Changes', 'wpgmp_google_map' ) ?>"></div>
 		   </form> 
</div>
    </div> </div>  
<?php
}
/**
 * This function used to show success/failure message in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_google_map_widget(){
	register_widget('wpgmp_google_map_widget');
}
/**
 * This class used to add widget support in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
class wpgmp_google_map_widget extends WP_Widget{
	public function __construct()
	{
		parent::__construct(
			'wpgmp_google_map_widget',
			'WP Google Map Plugin',
			array('description' => __('A widget that displays the google map' , 'wpgmp_google_map'))
		);
	}
	function widget( $args, $instance )
	{
		global $wpdb;
		extract($args);
		$title=$instance['title'];
		$map_title = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."create_map where map_id='".$title."'");
		echo $before_widget;
		
		if($title)
			echo $before_title . $map_title->map_title . $after_title;
		
		echo do_shortcode('[put_wpgm id='.$title.']' ).$after_widget;
	
	}
	function update( $new_instance, $old_instance )
	{
		$instance=$old_instance;
		$instance['title']=strip_tags($new_instance['title']);
		update_option('wpgmp_short_mapselect_marker' , $mark);
		return $instance;
	}
	function form( $instance )
	{
	
	global $wpdb;
	$map_records = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."create_map",NULL));
	?>
	
		<p>
			<label for="<?php echo $this->get_field_id('title');?>" style="font-weight:bold;"><?php _e('Select Your Map:' , 'wpgmp_google_map');?>
			</label> 
				<select id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" style="width:80%;">
                <option value=""><?php _e( 'Select map', 'wpgmp_google_map' ) ?></option>
				<?php foreach($map_records as $key => $map_record){  ?>
 				<option value="<?php echo $map_record->map_id; ?>"<?php selected($map_record->map_id,$instance['title']); ?>><?php echo $map_record->map_title; ?></option>
				<?php } ?>	
				</select>
        </p>        
	<?php	
	}
}
/**
 * This function used to register google map script.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_scripts_method(){
    wp_enqueue_script('wpgmp_map','http://www.google.com/jsapi');
}



/**
 * This function used to load css in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_google_map_load(){
wp_enqueue_style(
		'google_map_css',
		plugins_url( '/css/google-map.css' , __FILE__ ));
	
}
  
function wpgmp_excerpt_more(){
 return '<br /><a class="read-more" href="'. get_permalink( get_the_ID() ) . '" target="_blank">Read More</a>';
}


 
/**
 * This function used to create tab.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_google_map_tabs_filter($tabs)
{
        $newtab = array('ell_insert_gmap_tab' => __('Choose Icons','wpgmp_google_map'));
        return array_merge($tabs,$newtab);
}
 
function wpgmp_google_map_media_upload_tab() {
	return wp_iframe('wpgmp_google_map_icon');
}
function wpgmp_google_map_icon()
{
echo media_upload_header();
$form_action_url = site_url( "wp-admin/media-upload.php?type={$GLOBALS['type']}&tab=ell_insert_gmap_tab", 'admin');
?>
<script type="text/javascript">
 function add_icon_to_images()
 {
	  if(jQuery('.read_icons').hasClass('active'))
	  {	  
	  	imgsrc = jQuery('.active').find('img').attr('src');
   		
		var win = window.dialogArguments || opener || parent || top;
   		
		win.send_icon_to_map(imgsrc);
  		
	  }
	  else
	  {
   		alert('Choose your icon.');
  	  }
 }
</script>
<form enctype="multipart/form-data" method="post" action="<?php echo esc_attr($form_action_url); ?>" class="media-upload-form" id="library-form">
<h3 class="media-title" style="color: #5A5A5A; font-family: Georgia, 'Times New Roman', Times, serif; font-weight: normal; font-size: 1.6em; margin-left: 10px;"><?php _e( 'Select Icons', 'wpgmp_google_map' ) ?></h3>
<div style="margin-bottom:30px; float:left;">
<ul style="margin-left:10px; float:left;" id="select_icons">
<?php
$dir = plugin_dir_path( __FILE__ ) . 'icons/';
if ( is_dir($dir)  )
{
  if ( $dh = opendir($dir) )
  {
    while (($file = readdir($dh)) !== false)
	{
?>	
<li class="read_icons" style="float:left;">	
      <img src="<?php echo plugins_url('/icons/'.$file.'', __FILE__ ); ?>" style="cursor:pointer;" />
</li>
<?php
    }
?>
<?php
    closedir($dh);
  }
}
?>
</ul>
<button type="button" class="button" style="margin-left:10px;" value="1" onclick="add_icon_to_images();" name="send[<?php echo $picid ?>]"><?php _e( 'Insert into Post', 'wpgmp_google_map' ) ?></button>
</div>
</form>
<?php
}  
 
/**
 * This function used to registered all action.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
  
 
 
function wpgmp_load_actions()
{
 wpgmp_scripts_method();
add_action('media_upload_ell_insert_gmap_tab', 'wpgmp_google_map_media_upload_tab');
add_filter('media_upload_tabs', 'wpgmp_google_map_tabs_filter');
add_action('admin_menu', 'wpgmp_google_map_page');
add_shortcode('put_wpgm','wpgmp_show_location_in_map');
add_shortcode('display_map','wpgmp_display_map');
add_action('admin_enqueue_scripts', 'wpgmp_admin_scripts');
add_action('admin_print_styles', 'wpgmp_admin_styles');
add_action('admin_head', 'wpgmp_js_head');
add_shortcode('post','return_post_content');
}

function return_post_content($atts, $content=null)
{
	$post = get_post($atts['id']);
	setup_postdata($post);
	$content = "<div><h4>".get_the_title()."</h4>";
	$content .= "<p>".get_the_excerpt()."</p>";
	wp_reset_postdata();
	return $content;
}

add_action('widgets_init' , 'wpgmp_google_map_widget');
add_action('init', 'wpgmp_load_actions');
include_once("wpgmp-all-js.php");
include_once("wpgmp-add-location.php");
include_once("wpgmp-manage-location.php");
include_once("wpgmp-create-map.php");
include_once("wpgmp-manage-map.php");
include_once("wpgmp-create-group-map.php");
include_once("wpgmp-manage-group-map.php");
include_once("wpgmp-display-map.php");

/**
 * This function used to show success/failure message in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_showMessage($message, $errormsg = false)
{
	if( empty($message) )
	return;
	
	if ( $errormsg ) {
		echo '<div id="message" class="error">';
	}
	else {
		echo '<div id="message" class="updated">';
	}
	echo "<p><strong>$message</strong></p></div>";
} 
/**
 * This function used to show basic instruction for how to use this plugin.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Maps
 */
function wpgmp_admin_overview()  {
	?>
	<div class="wrap wpgmp-wrap">
    <div class="col-md-11"> 
		<h3><span class="glyphicon glyphicon-asterisk"></span><?php _e('How to Use', 'wpgmp_google_map') ?></h3>
		<div id="dashboard-widgets-container" class="wpgmp-overview">
		    <div id="dashboard-widgets" class="metabox-holder">
				<div id="post-body">
					<div id="dashboard-widgets-main-content">
						<div class="postbox-container" id="main-container" style="width:75%;">
							<?php _e('Go through the steps below to create your first map:', 'wpgmp_google_map') ?>
							<p>
							
							<b><?php _e('Step 1', 'wpgmp_google_map') ?></b> - <?php _e('Use our auto suggestion enabled location box to add your location', 'wpgmp_google_map') ?> <a href="<?php echo admin_url('admin.php?page=wpgmp_add_location') ?>"><?php _e('Here', 'wpgmp_google_map') ?></a>. <?php _e('You can add multiple locations. All those locations will be available to choose when you create your map.', 'wpgmp_google_map') ?> </li>
							
							</p>
							<p>
							<b><?php _e('Step 2', 'wpgmp_google_map') ?></b> - <?php _e('Now', 'wpgmp_google_map') ?> <a href="<?php echo admin_url('admin.php?page=wpgmp_create_map') ?>"><?php _e('Click Here', 'wpgmp_google_map') ?></a> <?php _e(' to create a map. You can create as many maps you want to add. Using shortcode, you can add maps on posts/pages.', 'wpgmp_google_map') ?> </li>
							</p>
							<p>
							<b><?php _e('Step 3', 'wpgmp_google_map') ?></b> - <?php _e('When done with administrative tasks, you can display map on posts/pages using', 'wpgmp_google_map') ?> <a href="<?php echo admin_url('admin.php?page=wpgmp_google_wpgmp_manage_map') ?>"><?php _e('Shortcode', 'wpgmp_google_map') ?></a> <?php _e(' enable map in the widgets section to display in sidebar. ', 'wpgmp_google_map') ?> .</li>
							</p>
						</div>
			    		<div class="postbox-container" id="side-container" style="width:24%;">
						</div>						
					</div>
				</div>
		    </div>
		</div>
		<div style="clear:both"></div>
			<h3><span class="glyphicon glyphicon-asterisk"></span><?php _e('Pro Version', 'wpgmp_google_map') ?></h3>
		<div id="dashboard-widgets-container" class="wpgmp-overview">
		    <div id="dashboard-widgets" class="metabox-holder">
				<div id="post-body">
					<div id="dashboard-widgets-main-content">
						<div class="postbox-container" id="main-container" style="width:75%;">
							<?php _e('Explore countless other customizable features in the pro version of this most advanced Google Maps plugin.', 'wpgmp_google_map') ?> <a href="http://codecanyon.net/item/advanced-google-maps/5211638" target="_blank"><?php _e('Pro Version', 'wpgmp_google_map') ?></a> is available on Codecanyon.
						</div>
			    		<div class="postbox-container" id="side-container" style="width:24%;">
						</div>						
					</div>
				</div>
		    </div>
		</div>

	
		<div style="clear:both"></div>
			<h3><span class="glyphicon glyphicon-asterisk"></span><?php _e('Online Documentation', 'wpgmp_google_map') ?></h3>
		<div id="dashboard-widgets-container" class="wpgmp-overview">
		    <div id="dashboard-widgets" class="metabox-holder">
				<div id="post-body">
					<div id="dashboard-widgets-main-content">
						<div class="postbox-container" id="main-container" style="width:75%;">
							<?php _e('Documentation is available with the zipped package of the purchased plugin or visit our ', 'wpgmp_google_map') ?> <a href="http://www.flippercode.com" target="_blank"><?php _e('Official Website', 'wpgmp_google_map') ?></a> for <a href="http://www.flippercode.com" target="_blank"><?php _e('online documentation', 'wpgmp_google_map') ?></a>.
						</div>
			    		<div class="postbox-container" id="side-container" style="width:24%;">
						</div>						
					</div>
				</div>
		    </div>
		</div>
	</div>
	
	<?php
}
function wpgmp_is_mobile() {
	        static $is_mobile;
	
	        if ( isset($is_mobile) )
	                return $is_mobile;
	
	        if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
	                $is_mobile = false;
	        } elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
	                || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
	                || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
	                || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
	                || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
	                || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
	                || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
	                        $is_mobile = true;
	        } else {
	                $is_mobile = false;
	        }
	
	        return $is_mobile;
}
function wpgmp_get_coordinates( $content, $force_refresh = false ) {
    $address_hash = md5( $content );
    $coordinates = get_transient( $address_hash );
    if ($force_refresh || $coordinates === false) {
    	$args       = array( 'address' => urlencode( $content ), 'sensor' => 'false' );
    	$url        = add_query_arg( $args, 'http://maps.googleapis.com/maps/api/geocode/json' );
     	$response 	= wp_remote_get( $url );
     	if( is_wp_error( $response ) )
     		return;
     	$data = wp_remote_retrieve_body( $response );
     	if( is_wp_error( $data ) )
     		return;
		if ( $response['response']['code'] == 200 ) {
			$data = json_decode( $data );
			if ( $data->status === 'OK' ) {
			  	$coordinates = $data->results[0]->geometry->location;
			  	$cache_value['lat'] 	= $coordinates->lat;
			  	$cache_value['lng'] 	= $coordinates->lng;
			  	$cache_value['address'] = (string) $data->results[0]->formatted_address;
			  	// cache coordinates for 3 months
			  	set_transient($address_hash, $cache_value, 3600*24*30*3);
			  	$data = $cache_value;
			} elseif ( $data->status === 'ZERO_RESULTS' ) {
			  	return __( 'No location found for the entered address.', 'wpgmp_google_map' );
			} elseif( $data->status === 'INVALID_REQUEST' ) {
			   	return __( 'Invalid request. Did you enter an address?', 'wpgmp_google_map' );
			} else {
				return __( 'Something went wrong while retrieving your map, please ensure you have entered the short code correctly.', 'wpgmp_google_map' );
			}
		} else {
		 	return __( 'Unable to contact Google API service.', 'wpgmp_google_map' );
		}
    } else {
       // return cached results
       $data = $coordinates;
    }
    return $data;
}
function wpgmp_get_address_coordinates( $new_loc_add, $force_refresh = false ) {
    $address_hash = md5( $new_loc_add );
	
    $coordinates = get_transient( $address_hash );
    if ($force_refresh || $coordinates === false) {
    	$args       = array( 'address' => urlencode( $new_loc_add ), 'sensor' => 'false' );
    	$url        = add_query_arg( $args, 'http://maps.googleapis.com/maps/api/geocode/json' );
     	$response 	= wp_remote_get( $url );
     	if( is_wp_error( $response ) )
     		return;
     	$data = wp_remote_retrieve_body( $response );
     	if( is_wp_error( $data ) )
     		return;
		if ( $response['response']['code'] == 200 ) {
			$data = json_decode( $data );
			if ( $data->status === 'OK' ) {
			  	$coordinates = $data->results[0]->geometry->location;
			  	$cache_value['lat'] 	= $coordinates->lat;
			  	$cache_value['lng'] 	= $coordinates->lng;
			  	$cache_value['address'] = (string) $data->results[0]->formatted_address;
			  	// cache coordinates for 3 months
			  	set_transient($address_hash, $cache_value, 3600*24*30*3);
			  	$data = $cache_value;
			} elseif ( $data->status === 'ZERO_RESULTS' ) {
			  	return __( 'No location found for the entered address.', 'wpgmp_google_map' );
			} elseif( $data->status === 'INVALID_REQUEST' ) {
			   	return __( 'Invalid request. Did you enter an address?', 'wpgmp_google_map' );
			} else {
				return __( 'Something went wrong while retrieving your map, please ensure you have entered the short code correctly.', 'wpgmp_google_map' );
			}
		} else {
		 	return __( 'Unable to contact Google API service.', 'wpgmp_google_map' );
		}
    } else {
       // return cached results
       $data = $coordinates;
    }
    return $data;
}
