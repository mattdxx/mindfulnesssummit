<?php
/*
    Plugin Name: AffiliateWP Mailchimp Add-on
    Plugin URI: http://bosun.me/affiliatewp-mailchimp-addon
    Description: Adds a checkbox for new affiliates to subscribe to your MailChimp Newsletter during signup.
    Version: 1.0.6
    Author: Tunbosun Ayinla
    Author URI: http://www.bosun.me
    License:           GPL-2.0+
    License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
    GitHub Plugin URI: https://github.com/tubiz/affiliatewp-mailchimp-addon
 */


if ( ! defined( 'ABSPATH' ) ) exit;


if( ! class_exists( 'AffiliateWP_MailChimp_Add_on' ) ){

    final class AffiliateWP_MailChimp_Add_on {
        private static $instance = false;

        public static function get_instance() {
            if ( ! self::$instance ) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        private function __construct() {
            add_action( 'admin_init', array( $this, 'activation' ) );
            add_filter( 'affwp_settings_integrations', array( $this, 'affwp_mailchimp_settings' ), 10 );
            add_action( 'affwp_register_user', array( $this, 'affwp_mailchimp_add_user_to_list'), 10 , 2 );

            if( ! is_admin() ) {
                add_action( 'affwp_register_fields_before_tos', array( $this, 'affwp_mailchimp_subscribe_checkbox' ) );
                add_action( 'affwp_affiliate_dashboard_before_submit', array( $this, 'affwp_dashboard_mailchimp_subscribe_checkbox' ), 10, 2 );
                add_action( 'affwp_update_affiliate_profile_settings', array( $this, 'affwp_dashboard_mailchimp_add_user_to_list' ) );
            }

            if( is_admin() ){
                add_action( 'affwp_new_affiliate_bottom', array( $this, 'affwp_mailchimp_admin_subscribe_checkbox' ) );
                add_action( 'affwp_insert_affiliate', array( $this, 'affwp_mailchimp_admin_add_user_to_list' ) );
            }
        }

        // Checks if AffiliateWP is installed
        public function activation() {
            global $wpdb;

            $affwp_plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/affiliate-wp/affiliate-wp.php', false, false );

            if ( ! class_exists( 'Affiliate_WP' ) ) {

                // is this plugin active?
                if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {

                    // deactivate the plugin
                    deactivate_plugins( plugin_basename( __FILE__ ) );

                    // unset activation notice
                    unset( $_GET[ 'activate' ] );

                    // display notice
                    add_action( 'admin_notices', array( $this, 'admin_notices' ) );
                }

            }
            else {
                add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'settings_link' ), 10, 2 );
            }
        }

        //Shows admin notice if AffiliateWP isn't installed
        public function admin_notices() {

            $affwp_plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/affiliate-wp/affiliate-wp.php', false, false );

            if ( ! class_exists( 'Affiliate_WP' ) ) {
                echo '<div class="error"><p>You must install and activate <strong><a href="https://affiliatewp.com/pricing" title="AffiliateWP" target="_blank">AffiliateWP</a></strong> to use <strong>AffiliateWP MailChimp Add-on</strong></p></div>';
            }

            if ( $affwp_plugin_data['Version'] < '1.1' ) {
                echo '<div class="error"><p><strong>AffiliateWP MailChimp Add-on</strong> requires <strong>AffiliateWP 1.1</strong> or greater. Please update <strong>AffiliateWP</strong>.</p></div>';
            }
        }

        //Plugin Settings Link
        public function settings_link( $links ) {
            $plugin_link = array(
                '<a href="' . admin_url( 'admin.php?page=affiliate-wp-settings&tab=integrations' ) . '">Settings</a>',
            );
            return array_merge( $plugin_link, $links );
        }

        //AffiliateWP Mailchimp Settings
        public function affwp_mailchimp_settings( $settings ) {

            $mailchimp_api_key  = affiliate_wp()->settings->get( 'affwp_mailchimp_api_key' );

            $mailchimp_lists    = $this->affwp_mailchimp_get_lists();

            if ($mailchimp_lists === false ) {
                $mailchimp_lists = array ();
            }

            if( ! empty ( $mailchimp_api_key ) ){
                $mailchimp_lists = array_merge( array( '' => 'Select a list' ), $mailchimp_lists );
            }
            else{
                $mailchimp_lists = array( '' => 'Enter your MailChimp API Key and save to see your lists' );
            }


            $affwp_mailchimp_settings = array(
                'affwp_mailchimp_header' => array(
                    'name' => '<strong>AffiliateWP MailChimp Settings</strong>',
                    'type' => 'header'
                ),
                'affwp_enable_mailchimp' => array(
                    'name' => 'Enable/Disable',
                    'type' => 'checkbox',
                    'desc' => 'Enable MailChimp Subscription. This will allow affiliate to subscribe to your Mailchimp list.'
                ),
                'affwp_mailchimp_form_label' => array(
                    'name' =>'Checkbox Label',
                    'desc' => 'Enter the form label here.<br />The default label is "Signup for our newsletter".',
                    'type' => 'text',
                    'std' => 'Signup for our newsletter'
                ),
                'affwp_mailchimp_api_key' => array(
                    'name' =>'MailChimp API Key',
                    'desc' => '<br />Enter your MailChimp API Key here. Click <a href="https://us2.admin.mailchimp.com/account/api/" target="_blank">here</a> to login to MailChimp and get your API key.',
                    'type' => 'text',
                    'std' => ''
                ),
                'affwp_mailchimp_enable_opt_in' => array(
                    'name' => 'Double Opt-In',
                    'desc' => 'If enabled, affiliates will receive an email with a link to confirm their subscription to the list.',
                    'type' => 'checkbox'
                ),
                'affwp_mailchimp_auto_subscribe' => array(
                    'name' => 'Auto Subscribe',
                    'desc' => 'If enabled, affiliates will be subscribed to your newsletter automatically without them ticking the subscribe checkbox. <br />  N.B: This will remove the checkbox from the registration page as there is no need to display it.',
                    'type' => 'checkbox'
                ),
                'affwp_mailchimp_list' => array(
                    'name' => 'Newsletter List',
                    'desc' => 'Choose the List you want the affiliate to be subscribe to when registered.',
                    'type' => 'select',
                    'options' => $mailchimp_lists
                )
            );

            return array_merge( $settings, $affwp_mailchimp_settings );
        }

        //Add Subscribe Checkbox to the signup page
        public function affwp_mailchimp_subscribe_checkbox(){
            $mailchimp_enabled          = affiliate_wp()->settings->get( 'affwp_enable_mailchimp' );
            $mailchimp_label            = affiliate_wp()->settings->get( 'affwp_mailchimp_form_label' );
            $mailchimp_api_key          = affiliate_wp()->settings->get( 'affwp_mailchimp_api_key' );
            $mailchimp_list             = affiliate_wp()->settings->get( 'affwp_mailchimp_list' );
            $mailchimp_auto_subscribe   = affiliate_wp()->settings->get( 'affwp_mailchimp_auto_subscribe' );

            if ( ! $mailchimp_auto_subscribe ){

                ob_start();
                    if ( $mailchimp_enabled && $mailchimp_api_key && $mailchimp_list ){ ?>
                    <p>
                        <label for="affwp_mailchimp_subscribe" style=" width: auto; ">
                        <input name="affwp_mailchimp_subscribe" id="affwp_mailchimp_subscribe" type="checkbox" checked="checked"/>
                            <?php
                                if ( ! empty ( $mailchimp_label ) ){
                                    echo $mailchimp_label;
                                }
                                else{
                                    echo 'Signup for our newsletter';
                                }
                            ?>
                        </label>
                    </p>
                    <?php
                }
                echo ob_get_clean();

            }
        }

        //Add Subscribe Checkbox to the Add New Affiliate Page In the WordPress backend
        public function affwp_mailchimp_admin_subscribe_checkbox(){
            $mailchimp_enabled  = affiliate_wp()->settings->get( 'affwp_enable_mailchimp' );
            $mailchimp_label    = affiliate_wp()->settings->get( 'affwp_mailchimp_form_label' );
            $mailchimp_api_key  = affiliate_wp()->settings->get( 'affwp_mailchimp_api_key' );
            $mailchimp_list     = affiliate_wp()->settings->get( 'affwp_mailchimp_list' );

            ob_start();
                if ( ! empty ( $mailchimp_enabled ) && ! empty ( $mailchimp_api_key )  && ! empty ( $mailchimp_list ) ){ ?>
                <p>
                    <input name="affwp_mailchimp_subscribe" id="affwp_mailchimp_subscribe" type="checkbox" checked="checked"/>
                    <label for="affwp_mailchimp_subscribe">Add Affiliate to MailChimp List</label>
                </p>
                <?php
            }
            echo ob_get_clean();
        }

        //Add subscribe checkbox to the Affiliate settings dashboard page
        public function affwp_dashboard_mailchimp_subscribe_checkbox(  $affiliate_id, $user_id ){

            $mailchimp_enabled  = affiliate_wp()->settings->get( 'affwp_enable_mailchimp' );
            $mailchimp_label    = affiliate_wp()->settings->get( 'affwp_mailchimp_form_label' );
            $mailchimp_api_key  = affiliate_wp()->settings->get( 'affwp_mailchimp_api_key' );
            $mailchimp_list     = affiliate_wp()->settings->get( 'affwp_mailchimp_list' );

            $subscribe_status   = get_user_meta( $user_id, 'tbz_affwp_subscribed_to_mailchimp', true );

            if( ! $subscribe_status &&  $mailchimp_enabled && $mailchimp_api_key && $mailchimp_list ){

                ob_start();
                    if ( $mailchimp_enabled && $mailchimp_api_key && $mailchimp_list ){ ?>
                    <p>
                        <label for="affwp_mailchimp_subscribe" style=" width: auto; ">
                        <input name="affwp_mailchimp_subscribe" id="affwp_mailchimp_subscribe" type="checkbox"/>
                            <?php
                                if ( ! empty ( $mailchimp_label ) ){
                                    echo $mailchimp_label;
                                }
                                else{
                                    echo 'Signup for our newsletter';
                                }
                            ?>
                        </label>
                    </p>
                    <?php
                }
                echo ob_get_clean();

            }
        }

        //Add new Affiliate from the Affiliate settings dashboard page
        public function affwp_dashboard_mailchimp_add_user_to_list( $data ){

            global $wpdb;

            $affiliate_id   = $data['affiliate_id'];

            $affiliate      = affiliate_wp()->affiliates->get_by( 'affiliate_id', $affiliate_id );
            $user_id        = $affiliate->user_id;

            $email          = $wpdb->get_var( $wpdb->prepare( "SELECT user_email FROM $wpdb->users WHERE ID = '%d'", $user_id ) );
            $name           = affiliate_wp()->affiliates->get_affiliate_name( $affiliate_id );

            $mailchimp_api_key  = affiliate_wp()->settings->get( 'affwp_mailchimp_api_key' );

            if( ! empty( $_POST['affwp_mailchimp_subscribe'] ) && $mailchimp_api_key  ) {

                $name               = explode( ' ', $name );

                $first_name         = $name[0];
                $last_name          = isset( $name[1] ) ? $name[1] : '';

                $mailchimp_list     = affiliate_wp()->settings->get( 'affwp_mailchimp_list' );

                $mailchimp_api_key  = trim( $mailchimp_api_key );

                $check_opt_in       = affiliate_wp()->settings->get( 'affwp_mailchimp_enable_opt_in' );

                if( ! empty ( $check_opt_in ) ){
                    $optin = true;
                }else{
                    $optin = false;
                }

                require_once  plugin_dir_path( __FILE__ ) . 'classes/api/MailChimp.php';

                $MailChimp = new AffWPMailChimp( $mailchimp_api_key );

                $result = $MailChimp->call('lists/subscribe', array(
                    'id'                => $mailchimp_list,
                    'email'             => array( 'email'=> $email ),
                    'merge_vars'        => array( 'FNAME'=> $first_name, 'LNAME'=> $last_name ),
                    'double_optin'      => $optin,
                    'update_existing'   => true,
                    'replace_interests' => false,
                    'send_welcome'      => false,
                ));

                update_user_meta( $user_id, 'tbz_affwp_subscribed_to_mailchimp', 'yes' );

                if ( 'error' == $result['status'] ){
                    return false;
                }

                return true;

            }

            return false;
        }

        //Add New Affiliate to Newsletter List
        public function affwp_mailchimp_add_user_to_list( $affiliate_id, $status ){

            $mailchimp_enabled          = affiliate_wp()->settings->get( 'affwp_enable_mailchimp' );
            $mailchimp_api_key          = affiliate_wp()->settings->get( 'affwp_mailchimp_api_key' );
            $mailchimp_auto_subscribe   = affiliate_wp()->settings->get( 'affwp_mailchimp_auto_subscribe' );

            if( $mailchimp_enabled && $mailchimp_auto_subscribe ){

                if( is_user_logged_in() ){

                    global $wpdb;

                    $user_id = get_current_user_id();

                    $email   = $wpdb->get_var( $wpdb->prepare( "SELECT user_email FROM $wpdb->users WHERE ID = '%d'", $user_id ) );
                    $name    = $wpdb->get_var( $wpdb->prepare( "SELECT display_name FROM $wpdb->users WHERE ID = '%d'", $user_id ) );

                    $name           = explode( ' ', $name );
                    $first_name     = $name[0];
                    $last_name      = isset( $name[1] ) ? $name[1] : '';
                }
                else{
                    $name           = explode( ' ', sanitize_text_field( $_POST['affwp_user_name'] ) );

                    $first_name     = $name[0];
                    $last_name      = isset( $name[1] ) ? $name[1] : '';
                    $email          = sanitize_text_field( $_POST['affwp_user_email'] );

                    $affiliate      = affiliate_wp()->affiliates->get_by( 'affiliate_id', $affiliate_id );
                    $user_id        = $affiliate->user_id;
                }

                $mailchimp_list             = affiliate_wp()->settings->get( 'affwp_mailchimp_list' );

                $mailchimp_api_key          = trim( $mailchimp_api_key );

                $check_opt_in               = affiliate_wp()->settings->get( 'affwp_mailchimp_enable_opt_in' );

                require_once  plugin_dir_path( __FILE__ ) . 'classes/api/MailChimp.php';

                $MailChimp = new AffWPMailChimp( $mailchimp_api_key );

                if( $check_opt_in ){
                    $optin = true;
                }else{
                    $optin = false;
                }

                $result = $MailChimp->call('lists/subscribe', array(
                    'id'                => $mailchimp_list,
                    'email'             => array( 'email'=> $email ),
                    'merge_vars'        => array( 'FNAME'=> $first_name, 'LNAME'=> $last_name ),
                    'double_optin'      => $optin,
                    'update_existing'   => true,
                    'replace_interests' => false,
                    'send_welcome'      => false,
                ));

                if( isset( $result['status'] ) && ( 'error' == $result['status'] ) ){
                    return false;
                }
                else{
                    update_user_meta( $user_id, 'tbz_affwp_subscribed_to_mailchimp', 'yes' );
                    return true;
                }
            }

            if( ! empty( $_POST['affwp_mailchimp_subscribe'] ) && $mailchimp_api_key ) {

                if( is_user_logged_in() ){

                    global $wpdb;

                    $user_id = get_current_user_id();

                    $email   = $wpdb->get_var( $wpdb->prepare( "SELECT user_email FROM $wpdb->users WHERE ID = '%d'", $user_id ) );
                    $name    = $wpdb->get_var( $wpdb->prepare( "SELECT display_name FROM $wpdb->users WHERE ID = '%d'", $user_id ) );

                    $name           = explode( ' ', $name );
                    $first_name     = $name[0];
                    $last_name      = isset( $name[1] ) ? $name[1] : '';
                }
                else{
                    $name           = explode( ' ', sanitize_text_field( $_POST['affwp_user_name'] ) );

                    $first_name     = $name[0];
                    $last_name      = isset( $name[1] ) ? $name[1] : '';
                    $email          = sanitize_text_field( $_POST['affwp_user_email'] );

                    $affiliate      = affiliate_wp()->affiliates->get_by( 'affiliate_id', $affiliate_id );
                    $user_id        = $affiliate->user_id;
                }

                $mailchimp_list             = affiliate_wp()->settings->get( 'affwp_mailchimp_list' );

                $mailchimp_api_key          = trim( $mailchimp_api_key );

                $check_opt_in               = affiliate_wp()->settings->get( 'affwp_mailchimp_enable_opt_in' );

                if( $check_opt_in ){
                    $optin = true;
                }else{
                    $optin = false;
                }

                require_once  plugin_dir_path( __FILE__ ) . 'classes/api/MailChimp.php';

                $MailChimp = new AffWPMailChimp( $mailchimp_api_key );

                $result = $MailChimp->call('lists/subscribe', array(
                    'id'                => $mailchimp_list,
                    'email'             => array( 'email'=> $email ),
                    'merge_vars'        => array( 'FNAME'=> $first_name, 'LNAME'=> $last_name ),
                    'double_optin'      => $optin,
                    'update_existing'   => true,
                    'replace_interests' => false,
                    'send_welcome'      => false,
                ));

                if( isset( $result['status'] ) && ( 'error' == $result['status'] ) ){
                    return false;
                }
                else{
                    update_user_meta( $user_id, 'tbz_affwp_subscribed_to_mailchimp', 'yes' );
                    return true;
                }
            }

            return false;
        }

        //Add New Affiliate to NewsLetter List from the Admin Add New Affiliate Page
        public function affwp_mailchimp_admin_add_user_to_list( $add ){
            global $wpdb;

            $affiliate  = affiliate_wp()->affiliates->get_by( 'affiliate_id', $add );
            $user_id    = $affiliate->user_id;

            $email      = $wpdb->get_var( $wpdb->prepare( "SELECT user_email FROM $wpdb->users WHERE ID = '%d'", $user_id ) );
            $name       = affiliate_wp()->affiliates->get_affiliate_name( $add );

            $mailchimp_api_key  = affiliate_wp()->settings->get( 'affwp_mailchimp_api_key' );

            if( ! empty( $_POST['affwp_mailchimp_subscribe'] ) && ! empty( $mailchimp_api_key ) ) {

                $name               = explode( ' ', $name );

                $first_name         = $name[0];
                $last_name          = isset( $name[1] ) ? $name[1] : '';

                $mailchimp_list     = affiliate_wp()->settings->get( 'affwp_mailchimp_list' );

                $mailchimp_api_key  = trim( $mailchimp_api_key );

                $check_opt_in       = affiliate_wp()->settings->get( 'affwp_mailchimp_enable_opt_in' );

                if( ! empty ( $check_opt_in ) ){
                    $optin = true;
                }else{
                    $optin = false;
                }

                require_once  plugin_dir_path( __FILE__ ) . 'classes/api/MailChimp.php';

                $MailChimp = new AffWPMailChimp( $mailchimp_api_key );

                $result = $MailChimp->call('lists/subscribe', array(
                    'id'                => $mailchimp_list,
                    'email'             => array( 'email'=> $email ),
                    'merge_vars'        => array( 'FNAME'=> $first_name, 'LNAME'=> $last_name ),
                    'double_optin'      => $optin,
                    'update_existing'   => true,
                    'replace_interests' => false,
                    'send_welcome'      => false,
                ));

                if( isset( $result['status'] ) && ( 'error' == $result['status'] ) ){
                    return false;
                }
                else{
                    update_user_meta( $user_id, 'tbz_affwp_subscribed_to_mailchimp', 'yes' );
                    return true;
                }

            }

            return false;
        }

        //Get MailChimp Lists
        public function affwp_mailchimp_get_lists(){

            $mailchimp_api_key      = affiliate_wp()->settings->get( 'affwp_mailchimp_api_key' );
            $mailchimp_api_key      = trim( $mailchimp_api_key );

            if ( ! empty( $mailchimp_api_key ) ) {

                $mailchimp_lists        = array();

                if ( ! class_exists( 'AffWPMailChimp' ) )
                    require_once  plugin_dir_path( __FILE__ ) . 'classes/api/MailChimp.php';

                $mailchimp = new AffWPMailChimp( $mailchimp_api_key );
                $lists = $mailchimp->call('lists/list');

                $lists_count =  $lists['total'];

                foreach ($lists['data'] as $list) {
                    $mailchimp_lists[ $list ['id'] ]  = $list['name'];
                }

                return $mailchimp_lists;
            }
            return false;
        }

    }

}


function tbz_affwp_mailchimp_addon() {
    return AffiliateWP_MailChimp_Add_on::get_instance();
}
add_action( 'plugins_loaded', 'tbz_affwp_mailchimp_addon' );
