<?php
/*
  Plugin Name: Easy Responsive Tabs
  Plugin URI: http://www.oscitasthemes.com
  Description: Make bootstrap tabs res.
  Version: 3.0
  Author: oscitas
  Author URI: http://www.oscitasthemes.com
  License: Under the GPL v2 or later
 */
define('ERT_VERSION', '3.0');
define('ERT_BASE_URL', plugins_url('',__FILE__));
define('ERT_ASSETS_URL', ERT_BASE_URL . '/assets/');
define('ERT_BASE_DIR_LONG', dirname(__FILE__));
$_ert_restabs=array('current_id'=>0);
class easyResponsiveTabs {
    private $resjs_path;
    private $rescss_path;
    private $plugin_name;

    function __construct(){

        if (!isset($_SESSION['ert_js'])) {
            $_SESSION['ert_js'] = array();
        }
        if (!isset($_SESSION['ert_css'])) {
            $_SESSION['ert_css'] = array();
        }

        $pluginmenu=explode('/',plugin_basename(__FILE__));
        $this->plugin_name=$pluginmenu[0];
        $this->resjs_path='js/bootstrap-tabdrop.js';
        $this->rescss_path='css/tabdrop.css';

        add_action('init',array($this,'ert_tab_shortcode'));
        if(!apply_filters('plugin_oscitas_theme_check',false)){
            add_action('admin_menu', array($this, 'ert_register_admin_menu'));
            add_filter( "plugin_action_links_".plugin_basename( __FILE__ ), array($this, 'osc_ert_settings_link' ));
            add_action('admin_enqueue_scripts', array($this, 'ert_admin_scripts'));
            add_action('wp_enqueue_scripts', array($this, 'ert_enqueue_scripts'),-10);
            add_action('wp_enqueue_scripts', array($this, 'ert_dynamic_scripts'),100);

        }
        add_shortcode('restabs', array($this,'ert_theme_tabs'));
        add_shortcode('restab', array($this,'ert_theme_tab'));

    }

    public function ert_activate_plugin(){
        $isSet=apply_filters('ert_custom_option',false);
        if (!$isSet) {
            update_option( 'ERT_BOOTSTRAP_JS_LOCATION', 1 );
            update_option( 'ERT_BOOTSTRAP_CSS_LOCATION', 1 );
        }
    }

    public function ert_deactivate_plugin(){
        $isSet=apply_filters('ert_custom_option',false);
        if (!$isSet) {
            delete_option( 'ERT_BOOTSTRAP_JS_LOCATION' );
            delete_option( 'ERT_BOOTSTRAP_CSS_LOCATION');
        }
    }

    public function ert_register_admin_menu(){
        $isSet=apply_filters('ert_custom_option',false);
        if (!$isSet) {
            add_menu_page('ERT Settings', ' ERT Settings', 'manage_options', $this->plugin_name,array( $this,'osc_ebs_setting_page' ), ERT_ASSETS_URL.'images/menu_icon.png');
        }
    }

    public function osc_ert_settings_link( $links ) {
        $isSet=apply_filters('ert_custom_option',false);
        if (!$isSet) {
            $settings_link = '<a href="admin.php?page='.$this->plugin_name.'">Settings</a>';
            array_push( $links, $settings_link );
        }
        return $links;
    }


    public function osc_ebs_setting_page(){
        include 'files/ert_settings.php';
    }

    public function ert_tab_shortcode(){
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
            return;

        if (get_user_option('rich_editing') == 'true') {
            add_filter("mce_external_plugins", array( $this,"osc_add_ert_plugin"));
        }
    }

    public function osc_register_ert_button($buttons) {
        $buttons[]='oscitasrestabs';
        return $buttons;
    }

    public function osc_add_ert_plugin($plugin_array) {
        add_filter('mce_buttons', array( $this,'osc_register_ert_button'),903.498);
        $plugin_array['oscitasrestabs']=plugins_url('/assets/js/tabs_plugin.js', __FILE__);
        return $plugin_array;
    }

    public function ert_theme_tabs($params, $content = null) {
        global $_ert_restabs, $shortcode_tags;
        if (!count($_ert_restabs)) {
            $_ert_restabs = array('current_id'=>0);
        }
        global $post;
        $slug = get_post( $post )->post_name;
        extract(shortcode_atts(array(
            'ids'=>count($_ert_restabs),
            'id' => count($_ert_restabs).'-'.$slug.'-'.rand(11111,99999),
            'class' => '',
            'pills' =>'',
            'position'=>'',
            'alignment'=>'osc-tabs-left',
            'responsive'=>'true',
            'text'=>'',
            'icon'=>'',
            'tabcolor'=>'',
            'tabheadcolor'=>'',
            'seltabcolor'=>'',
            'seltabheadcolor'=>'',
            'tabhovercolor'=>'',
            'contentcolor'=>''
        ), $params));
        $_ert_restabs[$ids] = array();
        $_ert_restabs['current_id'] = count($_ert_restabs)-1;

		do_shortcode($content);
		if($tabcolor!=''){
			$tabcolor='#oscitas-restabs-' . $id .' li a{background-color:'.$tabcolor.';}';
		}
		if($tabheadcolor!=''){
			$tabheadcolor='#oscitas-restabs-' . $id .' li a { color:'.$tabheadcolor.';}';
		}
		if($seltabcolor!=''){
			//$seltabcolor='#oscitas-restabs-' . $id .' li.active a { background-color:'.$seltabcolor.' !important;}';
			$seltabcolor='#oscitas-restabs-' . $id .' li.active > a { background-color:'.$seltabcolor.';}';
			$seltabcolor.= '#oscitas-restabs-' . $id .' li.active > a:hover { background-color:none;}';
			$seltabcolor.= '#oscitas-restabs-' . $id .' li.active > a:active { background-color:none;}';
		}
		if($seltabheadcolor!=''){
			$seltabheadcolor='#oscitas-restabs-' . $id .' li.active a{color:'.$seltabheadcolor.';}';
		}

		if($tabhovercolor!=''){
			$tabhovercolor='#oscitas-restabs-' . $id .' li a:hover,#oscitas-restabs-' . $id .' li a:focus{background-color:'.$tabhovercolor.';}';
		}
		if($contentcolor!=''){
			$contentcolor='#oscitas-restabcontent-' . $id .'{background-color:'.$contentcolor.';}';
		}

		if($icon=='true'){
			$text='<i class="res_tab_icon"></i>'.$text;
		}
		if($pills=='nav-pills'){
			$navclass='nav-pills';
		}
		else{
			$navclass='nav-tabs';
		}
        $output = '';
        if($position=='tabs-below'){
            $scontent = '<div style="clear:both;width: 100%;" class="'.$alignment.'-div"><ul class="tab-content" id="oscitas-restabcontent-' . $id . '">' . implode('', $_ert_restabs[$ids]['panes']) . '</ul></div><div style="clear:both;width: 100%;"><ul class="nav osc-res-nav '.$navclass.' '.$alignment.'-ul" id="oscitas-restabs-' . $id . '">' . implode('', $_ert_restabs[$ids]['tabs']) . '</ul></div>';
        } else{
            $scontent = '<div style="clear:both;width: 100%;"><ul class="nav osc-res-nav '.$navclass.' '.$alignment.'-ul" id="oscitas-restabs-' . $id . '">' . implode('', $_ert_restabs[$ids]['tabs']) . '</ul></div><div style="clear:both;width: 100%;"><ul class="tab-content" id="oscitas-restabcontent-' . $id . '">' . implode('', $_ert_restabs[$ids]['panes']) . '</ul></div>';
        }

        if (trim($scontent) != "") {
            $output = '<div class="osc-res-tab tabbable '.$class.' '.$position.' '.$alignment.'">' . $scontent;
            $output .= '</div>';

            $jscontent='';
            if($responsive!='false'){
//                $autoselect -= ($autoselect ? 1: 0);
            $jscontent.= <<<EOF
                    jQuery('#oscitas-restabs-$id').tabdrop({'text': '$text'});
EOF;
            }

            $jscontent.= <<<EOF
            var tabHashId = window.location.hash.substr(1);
            if (tabHashId) {
                jQuery('#oscitas-restabs-$id a[href="#'+tabHashId+'"]').tab('show');
            }
EOF;
            $_SESSION['ert_js'][$id]=$jscontent;

		$_SESSION['ert_css'][$id]=$tabcolor.$tabheadcolor.$seltabheadcolor.$tabhovercolor.$seltabcolor.$contentcolor;
			//$_SESSION['ert_css'].=$tabcolor.$tabheadcolor.$seltabcolor.$seltabheadcolor.$tabhovercolor.$contentcolor;
		}
        wp_enqueue_style('ert_tab_css',ERT_ASSETS_URL.$this->rescss_path);
        wp_enqueue_style('ert_css',ERT_ASSETS_URL.'css/ert_css.php');

        $_ert_restabs['current_id'] = $_ert_restabs['current_id']-1;
        return $output;

    }


    public function ert_theme_tab($params, $content = null) {
        global $_ert_restabs;
        extract(shortcode_atts(array(
            'title' => 'title',
            'active' => '',
        ), $params));

        $index = $_ert_restabs['current_id'];
        if (!isset($_ert_restabs[$index]['tabs'])) {
            $_ert_restabs[$index]['tabs'] = array();
        }
        if (!isset($_ert_restabs[$index]['panes'])) {
            $_ert_restabs[$index]['panes'] = array();
        }
        $pane_id = 'ert_pane' . $index . '-' .  count($_ert_restabs[$index]['tabs']);
        $_ert_restabs[$index]['tabs'][] = '<li class="' . $active . '"><a href="#' . $pane_id . '" data-toggle="tab">' . $title
            . '</a></li>';
        $_ert_restabs[$index]['panes'][] = '<li class="tab-pane ' . $active . '" id="'. $pane_id . '">'
            . do_shortcode (trim($content)) . '</li>';
    }
    public function ert_enqueue_scripts(){
        wp_enqueue_script('jquery');

        $ertcss = get_option( 'ERT_BOOTSTRAP_CSS_LOCATION', 1 );
        if($ertcss==1){
            if (!apply_filters('ert_bootstrap_css_url',false)) {
                wp_enqueue_style('bootstrap_tab',ERT_ASSETS_URL.'css/bootstrap_tab.min.css');
                wp_enqueue_style('bootstrap_dropdown',ERT_ASSETS_URL.'css/bootstrap_dropdown.min.css');
            }
            else{
                wp_enqueue_style('ertbootstrap', apply_filters('ert_bootstrap_css_url',false));
            }
        }

        wp_enqueue_style('ert_tab_icon_css',ERT_ASSETS_URL.'css/res_tab_icon.css');


    }
    public function ert_dynamic_scripts(){

        $isSet=apply_filters('ert_custom_option',false);
        if (!$isSet) {
            $ertjs = get_option( 'ERT_BOOTSTRAP_JS_LOCATION', 1 );

            if($ertjs==1){
                if (!apply_filters('ert_bootstrap_js_url',false)) {
                    wp_enqueue_script('bootstrap_dropdown',ERT_ASSETS_URL.'js/bootstrap-dropdown.js',array('jquery'),ERT_VERSION,true);
                    wp_enqueue_script('bootstrap_tab',ERT_ASSETS_URL.'js/bootstrap-tab.js',array('jquery'),ERT_VERSION,true);}
                else{
                    wp_enqueue_script('ertbootstrap', apply_filters('ert_bootstrap_js_url',false),array('jquery'),ERT_VERSION,true);
                }

            }

        }

        wp_enqueue_script('ert_tab_js',ERT_ASSETS_URL.$this->resjs_path,array('jquery'),ERT_VERSION,true);

        wp_enqueue_script('ert_js',ERT_ASSETS_URL.'js/ert_js.php',array('jquery','ert_tab_js'),ERT_VERSION,true);

    }

    public function ert_admin_scripts(){
        global $pagenow;
        if ('post-new.php' == $pagenow || 'post.php' == $pagenow) {
            wp_enqueue_script('jquery');
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script('jquery-ui-dialog');
            wp_enqueue_style ( 'wp-jquery-ui-dialog');
            if (!apply_filters('ert_custom_bootstrap_admin_css',false)) {
                wp_enqueue_style('bootstrap_admin', ERT_ASSETS_URL.'css/bootstrap_admin.min.css');
            }
            wp_enqueue_style('ert_tab_icon_css',ERT_ASSETS_URL.'css/res_tab_icon.css');
        }
    }
}
function ert_init_session () {
    if (!session_id()) {
        @session_start();
    }
}

add_action('init', 'ert_init_session', 1);

$ertrestab= new easyResponsiveTabs();
register_activation_hook(__FILE__, array($ertrestab,'ert_activate_plugin'));
register_deactivation_hook(__FILE__, array($ertrestab,'ert_deactivate_plugin'));
