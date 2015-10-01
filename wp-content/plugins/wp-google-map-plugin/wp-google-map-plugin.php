<?php
/**
 * Wpgmp_Google_Map_Lite class file.
 * @package Maps
 * @author Flipper Code <hello@flippercode.com>
 * @version 3.0.5
 */

/*
Plugin Name: WP Google Map Plugin
Plugin URI: http://www.flippercode.com/
Description: Display Google Maps in Pages, Posts, Sidebar or Custom Templates. Unlimited maps, locations and categories supported. It’s Responsive, Multi-Lingual and Multi-Site Supported.
Author: flippercode
Author URI: http://www.flippercode.com/
Version: 3.0.5
Text Domain: wpgmp_google_map
Domain Path: /lang/
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'You are not allowed to call this page directly.' );
}

if ( ! class_exists( 'Wpgmp_Google_Map_Lite' ) ) {

	/**
	 * Main plugin class
	 * @author Flipper Code <hello@flippercode.com>
	 * @package Maps
	 */
	class Wpgmp_Google_Map_Lite
	{
		/**
		 * List of Modules.
		 * @var array
		 */
		private $modules = array();
		/**
		 * Intialize variables, files and call actions.
		 * @var array
		 */
		public function __construct() {
			error_reporting( E_ERROR | E_PARSE );
			$this->_define_constants();
			$this->_load_files();
			$this->modules = glob( WPGMP_MODEL.'**/model.*.php' );
			register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'plugin_deactivation' ) );
			add_action( 'plugins_loaded', array( $this, 'load_plugin_languages' ) );
			add_action( 'init', array( $this, '_init' ) );
			add_action( 'widgets_init', array( $this, 'wpgmp_google_map_widget' ) );
		}
		/**
		 * Call WordPress hooks.
		 */
		function _init() {

			global $wpdb;

			// Actions.
			add_action( 'admin_menu', array( $this, 'create_menu' ) );
			add_action( 'media_upload_ell_insert_gmap_tab', array( $this, 'wpgmp_google_map_media_upload_tab' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'wpgmp_frontend_scripts' ) );

			// Filters.
			add_filter( 'media_upload_tabs', array( $this, 'wpgmp_google_map_tabs_filter' ) );
			// Shortodes.
			add_shortcode( 'put_wpgm', array( $this, 'wpgmp_show_location_in_map' ) );
		}
		/**
		 * Register WP Google Map Widget
		 */
		function wpgmp_google_map_widget() {

			register_widget( 'WPGMP_Google_Map_Widget_Class' );
		}
		/**
		 * Eneque scripts at frontend.
		 */
		function wpgmp_frontend_scripts() {

			$scripts = array();
			wp_enqueue_script( 'jquery' );
			if ( isset( $_SERVER['HTTPS'] ) && ( 'on' == $_SERVER['HTTPS'] || 1 == $_SERVER['HTTPS'] ) || isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' == $_SERVER['HTTP_X_FORWARDED_PROTO'] ) {
				$wpgmp_apilocation = 'https';
			} else {
				$wpgmp_apilocation = 'http';
			}
			if ( get_option( 'wpgmp_api_key' ) != '' ) {
				$wpgmp_apilocation .= '://www.google.com/jsapi?key='.get_option( 'wpgmp_api_key' );
			} else {
				$wpgmp_apilocation .= '://www.google.com/jsapi';
			}

			$scripts[] = array(
			'handle'  => 'wpgmp-google-api',
			'src'   => $wpgmp_apilocation,
			'deps'    => array(),
			);

			$scripts[] = array(
			'handle'  => 'wpgmp-google-map-main',
			'src'   => WPGMP_JS.'maps.js',
			'deps'    => array( 'wpgmp-google-api' ),
			);

			if ( $scripts ) {
				foreach ( $scripts as $script ) {
					wp_enqueue_script( $script['handle'], $script['src'], $script['deps'], '', false );
				}
			}

			$wpgmp_local = array();
			if ( get_option( 'wpgmp_language' ) ) {
				$wpgmp_local['language'] = get_option( 'wpgmp_language' );
			} else { $wpgmp_local['language'] = 'en'; }
			$wpgmp_local['urlforajax'] = admin_url( 'admin-ajax.php' );

			wp_localize_script( 'wpgmp-google-map-main', 'wpgmp_local', $wpgmp_local );

			$frontend_styles = array(
			'wpgmp-frontend'  => WPGMP_CSS.'frontend.css',
			);

			if ( $frontend_styles ) {
				foreach ( $frontend_styles as $frontend_style_key => $frontend_style_value ) {
					wp_enqueue_style( $frontend_style_key, $frontend_style_value );
				}
			}
		}
		/**
		 * Display map at the frontend using put_wpgmp shortcode.
		 * @param  array  $atts   Map Options.
		 * @param  string $content Content.
		 */
		function wpgmp_show_location_in_map($atts, $content = null) {

			try {
				$factoryObject = new FactoryControllerWPGMP();
				$viewObject = $factoryObject->create_object( 'shortcode' );
				 $output = $viewObject->display( 'put-wpgmp',$atts );
				 return $output;

			} catch (Exception $e) {
				echo WPGMP_Template::show_message( array( 'error' => $e->getMessage() ) );

			}

		}
		/**
		 * Display map at the frontend using display_map shortcode.
		 * @param  array $atts    Map Options.
		 */
		function wpgmp_display_map($atts) {

			try {

				$factoryObject = new FactoryControllerWPGMP();
				$viewObject = $factoryObject->create_object( 'shortcode' );
				$viewObject->display( 'put-wpgmp',$atts );

			} catch (Exception $e) {
				echo WPGMP_Template::show_message( array( 'error' => $e->getMessage() ) );

			}

		}
		/**
		 * Process slug and display view in the backend.
		 */
		function processor() {

			$return = '';
			$page = sanitize_text_field( $_GET['page'] );
			$pageData = explode( '_', $page );
			$obj_type = $pageData[2];
			$obj_operation = $pageData[1];

			if ( count( $pageData ) < 3 ) {
				die( 'Cheating!' );
			}

			try {
				if ( count( $pageData ) > 3 ) {
					$obj_type = $pageData[2].'_'.$pageData[3];
				}

				$factoryObject = new FactoryControllerWPGMP();
				$viewObject = $factoryObject->create_object( $obj_type );
				$viewObject->display( $obj_operation );

			} catch (Exception $e) {
				echo WPGMP_Template::show_message( array( 'error' => $e->getMessage() ) );

			}

		}
		/**
		 * Create backend navigation.
		 */
		function create_menu() {

			global $navigations;

			$pagehook1 = add_menu_page(
				__( 'WP Google Map', WPGMP_TEXT_DOMAIN ),
				__( 'WP Google Map', WPGMP_TEXT_DOMAIN ),
				'wpgmp_admin_overview',
				WPGMP_SLUG,
				array( $this,'processor' )
			);

			if ( current_user_can( 'manage_options' )  ) {
								$role = get_role( 'administrator' );
								$role->add_cap( 'wpgmp_admin_overview' );
			}

			$this->load_modules_menu();

			add_action( 'load-'.$pagehook1, array( $this, 'wpgmp_backend_scripts' ) );

		}
		/**
		 * Read models and create backend navigation.
		 */
		function load_modules_menu() {

			$files = $this->modules;
			$pagehooks = array();

			if ( is_array( $files ) ) {
				foreach ( $files as $file ) {

					$module_info = (get_file_data( $file,array( 'class' => 'Class', 'menu_order' => 'Menu Order' ) ));
					if ( '' != $module_info['class'] ) {
						if ( isset( $module_info['menu_order'] ) and  '' != $module_info['menu_order'] ) {
							$menu_order[ $module_info['menu_order'] ][] = array( $file,$module_info );
						} else { 						$menu_order[100][] = array( $file, $module_info ); }
					}
				}
			}
			ksort( $menu_order );
			foreach ( $menu_order as $order => $menus ) {

				foreach ( $menus as $i => $menu ) {
					$file = $menu[0];
					$module_info = $menu[1];
					if ( file_exists( $file ) ) {
						include_once( $file );
						$object = new $module_info['class'];
						if ( method_exists( $object,'navigation' ) ) {

							if ( ! is_array( $object->navigation() ) ) {
								continue;
							}

							foreach ( $object->navigation() as $nav => $title ) {

								if ( current_user_can( 'manage_options' ) && is_admin() ) {
									$role = get_role( 'administrator' );
									$role->add_cap( $nav );

								}

								$pagehooks[] = add_submenu_page(
									WPGMP_SLUG,
									$title,
									$title,
									$nav,
									$nav,
									array( $this,'processor' )
								);

							}
						}
					}
				}
			}

			if ( is_array( $pagehooks ) ) {

				foreach ( $pagehooks as $key => $pagehook ) {
					add_action( 'load-'.$pagehooks[ $key ], array( $this, 'wpgmp_backend_scripts' ) );
				}
			}

		}
		/**
		 * Eneque scripts in the backend.
		 */
		function wpgmp_backend_scripts() {

			if ( get_option( 'wpgmp_api_key' ) != '' ) {
				if ( isset( $_SERVER['HTTPS'] ) && ( 'on' == $_SERVER['HTTPS'] || 1 == $_SERVER['HTTPS'] ) || isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' == $_SERVER['HTTP_X_FORWARDED_PROTO'] ) {
					$wpgmp_apilocation = 'https';
				} else {
					$wpgmp_apilocation = 'http';
				}

				$wpgmp_apilocation .= '://www.google.com/jsapi?key='.get_option( 'wpgmp_api_key' );
			} else {
				if ( isset( $_SERVER['HTTPS'] ) && ( 'on' == $_SERVER['HTTPS'] || 1 == $_SERVER['HTTPS'] ) || isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' == $_SERVER['HTTP_X_FORWARDED_PROTO'] ) {
					$wpgmp_apilocation = 'https';
				} else {
					$wpgmp_apilocation = 'http';
				}

				$wpgmp_apilocation .= '://www.google.com/jsapi';
			}

			wp_enqueue_style( 'thickbox' );
			wp_enqueue_style( 'wp-color-picker' );
			$wp_scripts = array( 'jQuery','thickbox', 'wp-color-picker' );

			if ( $wp_scripts ) {
				foreach ( $wp_scripts as $wp_script ) {
					wp_enqueue_script( $wp_script );
				}
			}

			$scripts = array();

			$scripts[] = array(
			'handle'  => 'wpgmp-backend-google-maps',
			'src'   => WPGMP_JS.'backend.js',
			'deps'    => array(),
			);

			$scripts[] = array(
			'handle'  => 'wpgmp-backend-google-api',
			'src'   => $wpgmp_apilocation,
			'deps'    => array(),
			);

			$scripts[] = array(
			'handle'  => 'wpgmp-map',
			'src'   => WPGMP_JS.'maps.js',
			'deps'    => array(),
			);

			if ( $scripts ) {
				foreach ( $scripts as $script ) {
					wp_enqueue_script( $script['handle'], $script['src'], $script['deps'] );
				}
			}

			$wpgmp_local = array();
			if ( get_option( 'wpgmp_language' ) ) {
				$wpgmp_local['language'] = get_option( 'wpgmp_language' );
			} else { $wpgmp_local['language'] = 'en'; }

			$wpgmp_local['urlforajax'] = admin_url( 'admin-ajax.php' );
			wp_localize_script( 'wpgmp-map', 'wpgmp_local', $wpgmp_local );
			$admin_styles = array(
			'wpgmp-map-bootstrap' => WPGMP_CSS.'bootstrap.min.flat.css',
			'wpgmp-backend-google-map' => WPGMP_CSS.'backend.css',
			);

			if ( $admin_styles ) {
				foreach ( $admin_styles as $admin_style_key => $admin_style_value ) {
					wp_enqueue_style( $admin_style_key, $admin_style_value );
				}
			}
		}
		/**
		 * Load plugin language file.
		 */
		function load_plugin_languages() {
			load_plugin_textdomain( WPGMP_TEXT_DOMAIN, false, WPGMP_FOLDER.'/lang/' );
		}
		/**
		 * Call hook on plugin activation for both multi-site and single-site.
		 */
		function plugin_activation() {

			if ( is_multisite() && $network_wide ) {
				global $wpdb;
				$currentblog = $wpdb->blogid;
				$activated = array();
				$sql = "SELECT blog_id FROM {$wpdb->blogs}";
				$blog_ids = $wpdb->get_col( $wpdb->prepare( $sql, null ) );

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					$this->wpgmp_activation();
					$activated[] = $blog_id;
				}

				switch_to_blog( $currentblog );
				update_site_option( 'op_activated', $activated );

			} else {
				$this->wpgmp_activation();
			}
		}
		/**
		 * Call hook on plugin deactivation for both multi-site and single-site.
		 */
		function plugin_deactivation() {

			if ( is_multisite() && $network_wide ) {
				global $wpdb;
				$currentblog = $wpdb->blogid;
				$activated = array();
				$sql = "SELECT blog_id FROM {$wpdb->blogs}";
				$blog_ids = $wpdb->get_col( $wpdb->prepare( $sql, null ) );

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					$this->wpgmp_deactivation();
					$activated[] = $blog_id;
				}

				switch_to_blog( $currentblog );
				update_site_option( 'op_activated', $activated );

			} else {
				$this->wpgmp_deactivation();
			}
		}

		/**
		 * Create choose icon tab in media manager.
		 * @param  array $tabs Current Tabs.
		 * @return array       New Tabs.
		 */
		function wpgmp_google_map_tabs_filter($tabs) {

			$newtab = array( 'ell_insert_gmap_tab' => __( 'Choose Icons', WPGMP_TEXT_DOMAIN ) );
			return array_merge( $tabs, $newtab );
		}
		/**
		 * Intialize wp_iframe for icons tab
		 * @return [type] [description]
		 */
		function wpgmp_google_map_media_upload_tab() {

			return wp_iframe( array( $this, 'wpgmp_google_map_icon' ), $errors );
		}
		/**
		 * Read images/icons folder.
		 */
		function wpgmp_google_map_icon() {

			wp_enqueue_style( 'media' );
			media_upload_header();
			$form_action_url = site_url( "wp-admin/media-upload.php?type={$GLOBALS['type']}&tab=ell_insert_gmap_tab", 'admin' );
		?>

		<style type="text/css">
		#select_icons .read_icons {
		width: 32px;
		height: 32px;ß
		}
		#select_icons .active img {
		border: 3px solid #000;
		width: 26px;
		}
		</style>

		<script type="text/javascript">

		jQuery(document).ready(function($) {

		$(".read_icons").click(function () {

		$(".read_icons").removeClass('active');
		$(this).addClass('active');
		});

		$('input[name="wpgmp_search_icon"]').keyup(function() {
		if($(this).val() == '')
		$('.read_icons').show();
		else {
		$('.read_icons').hide();
        $('img[title^="' + $(this).val() + '"]').parent().show();
		}

    	});

		});

		function add_icon_to_images(target) {

		if(jQuery('.read_icons').hasClass('active'))
		{
		imgsrc = jQuery('.active').find('img').attr('src');
		var win = window.dialogArguments || opener || parent || top;
		win.send_icon_to_map(imgsrc,target);
		}
		else
		{
		alert('Choose your icon.');
		}
		}
		</script>

		<form enctype="multipart/form-data" method="post" action="<?php echo esc_attr( $form_action_url ); ?>" class="media-upload-form" id="library-form">
	<h3 class="media-title" style="color: #5A5A5A; font-family: Georgia, 'Times New Roman', Times, serif; font-weight: normal; font-size: 1.6em; margin-left: 10px;"><?php _e( 'Choose icon', WPGMP_TEXT_DOMAIN ) ?> 	<input name="wpgmp_search_icon" id="wpgmp_search_icon" type='text' value="" placeholder="<?php _e( 'Search icons',WPGMP_TEXT_DOMAIN ); ?>" />
</h3>
	<div style="margin-bottom:20px; float:left; width:100%;">
	<ul style="float:left; width:100%;" id="select_icons">
	<?php
	$dir = WPGMP_ICONS_DIR;
	$file_display = array( 'jpg', 'jpeg', 'png', 'gif' );

	if ( file_exists( $dir ) == false ) {
		echo 'Directory \'', $dir, '\' not found!';

	} else {
		$dir_contents = scandir( $dir );
		foreach ( $dir_contents as $file ) {
			$image_data = explode( '.', $file );
			$file_type = strtolower( end( $image_data ) );
			if ( '.' !== $file && '..' !== $file && true == in_array( $file_type, $file_display ) ) {
			?>
			<li class="read_icons" style="float:left;">
			<img alt="<?php echo $image_data[0]; ?>" title="<?php echo $image_data[0]; ?>" src="<?php echo WPGMP_ICONS.$file; ?>" style="cursor:pointer;" />
		</li>
		<?php
			}
		}
	}
		?>
		</ul>
		<button type="button" class="button" style="margin-left:10px;" value="1" onclick="add_icon_to_images('<?php echo (wp_unslash( $_GET['target'] )); ?>');" name="send[<?php echo $picid ?>]"><?php _e( 'Insert into Post', WPGMP_TEXT_DOMAIN ) ?></button>
	</div>
	</form>
	<?php
		}
		/**
		 * Perform tasks on plugin deactivation.
		 */
		function wpgmp_deactivation() {

		}

		/**
		 * Perform tasks on plugin deactivation.
		 */
		function wpgmp_activation() {

			global $wpdb;

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			$files = $this->modules;
			$pagehooks = array();

			if ( is_array( $files ) ) {
				foreach ( $files as $file ) {

					$module_info = (get_file_data( $file,array( 'class' => 'Class', 'menu_order' => 'Menu Order' ) ));
					if ( '' != $module_info['class'] ) {
						if ( file_exists( $file ) ) {
							include_once( $file );
							$object = new $module_info['class'];
							if ( method_exists( $object,'install' ) ) {
								$tables[] = $object->install();
							}
						}
					}
				}
			}

			if ( is_array( $tables ) ) {
				foreach ( $tables as $i => $sql ) {
					dbDelta( $sql );
				}
			}

		}
		/**
		 * Define all constants.
		 */
		private function _define_constants() {

			global $wpdb;

			if ( ! defined( 'WPGMP_SLUG' ) ) {
				define( 'WPGMP_SLUG', 'wpgmp_view_overview' );
			}

			if ( ! defined( 'WPGMP_VERSION' ) ) {
				define( 'WPGMP_VERSION', '3.0.0' );
			}

			if ( ! defined( 'WPGMP_TEXT_DOMAIN' ) ) {
				define( 'WPGMP_TEXT_DOMAIN', 'wp-google-map-plugin' );
			}

			if ( ! defined( 'WPGMP_FOLDER' ) ) {
				define( 'WPGMP_FOLDER', basename( dirname( __FILE__ ) ) );
			}

			if ( ! defined( 'WPGMP_DIR' ) ) {
				define( 'WPGMP_DIR', plugin_dir_path( __FILE__ ) );
			}

			if ( ! defined( 'WPGMP_ICONS_DIR' ) ) {
				define( 'WPGMP_ICONS_DIR', WPGMP_DIR.'/assets/images/icons/' );
			}

			if ( ! defined( 'WPGMP_CORE_CLASSES' ) ) {
				define( 'WPGMP_CORE_CLASSES', WPGMP_DIR.'core/' );
			}

			if ( ! defined( 'WPGMP_CONTROLLER' ) ) {
				define( 'WPGMP_CONTROLLER', WPGMP_CORE_CLASSES );
			}

			if ( ! defined( 'WPGMP_CORE_CONTROLLER_CLASS' ) ) {
				define( 'WPGMP_CORE_CONTROLLER_CLASS', WPGMP_CORE_CLASSES.'class.controller.php' );
			}

			if ( ! defined( 'WPGMP_MODEL' ) ) {
				define( 'WPGMP_MODEL', WPGMP_DIR.'modules/' );
			}

			if ( ! defined( 'WPGMP_URL' ) ) {
				define( 'WPGMP_URL', plugin_dir_url( WPGMP_FOLDER ).WPGMP_FOLDER.'/' );
			}

			if ( ! defined( 'WPGMP_INC_URL' ) ) {
				define( 'WPGMP_INC_URL', WPGMP_URL.'includes/' );
			}

			if ( ! defined( 'WPGMP_VIEWS_PATH' ) ) {
				define( 'WPGMP_VIEWS_PATH', WPGMP_CLASSES.'view' );
			}

			if ( ! defined( 'WPGMP_CSS' ) ) {
				define( 'WPGMP_CSS', WPGMP_URL.'/assets/css/' );
			}

			if ( ! defined( 'WPGMP_JS' ) ) {
				define( 'WPGMP_JS', WPGMP_URL.'/assets/js/' );
			}

			if ( ! defined( 'WPGMP_IMAGES' ) ) {
				define( 'WPGMP_IMAGES', WPGMP_URL.'/assets/images/' );
			}

			if ( ! defined( 'WPGMP_FONTS' ) ) {
				define( 'WPGMP_FONTS', WPGMP_URL.'fonts/' );
			}

			if ( ! defined( 'WPGMP_ICONS' ) ) {
				define( 'WPGMP_ICONS', WPGMP_URL.'/assets/images/icons/' );
			}
			$upload_dir = wp_upload_dir();
			if ( ! defined( 'WPGMP_BACKUP' ) ) {

				if ( ! is_dir( $upload_dir['basedir'].'/maps-backup' ) ) {
					mkdir( $upload_dir['basedir'].'/maps-backup' );
				}
				define( 'WPGMP_BACKUP',$upload_dir['basedir'].'/maps-backup/' );
				define( 'WPGMP_BACKUP_URL',$upload_dir['baseurl'].'/maps-backup/' );

			}

			if ( ! defined( 'TBL_LOCATION' ) ) {
				define( 'TBL_LOCATION', $wpdb->prefix.'map_locations' );
			}

			if ( ! defined( 'TBL_GROUPMAP' ) ) {
				define( 'TBL_GROUPMAP', $wpdb->prefix.'group_map' );
			}

			if ( ! defined( 'TBL_MAP' ) ) {
				define( 'TBL_MAP', $wpdb->prefix.'create_map' );
			}

			if ( ! defined( 'TBL_ROUTES' ) ) {
				define( 'TBL_ROUTES', $wpdb->prefix.'map_routes' );
			}

			if ( ! defined( 'TBL_BACKUPS' ) ) {
				define( 'TBL_BACKUPS', $wpdb->prefix.'wpgmp_backups' );
			}

		}
		/**
		 * Load all required core classes.
		 */
		private function _load_files() {

			$files_to_include = array(
			'class.map-widget.php',
			'class.tabular.php',
			'class.template.php',
			'abstract.factory.php',
			'class.controller-factory.php',
			'class.model-factory.php',
			'class.controller.php',
			'class.model.php',
			'class.validation.php',
			'class.database.php',
			);
			foreach ( $files_to_include as $file ) {
				require_once( WPGMP_CORE_CLASSES.$file );
			}
		}
	}
}

new Wpgmp_Google_Map_Lite();
