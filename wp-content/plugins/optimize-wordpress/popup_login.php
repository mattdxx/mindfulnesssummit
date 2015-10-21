<?php
/* v.0.3.0
New popup login procedure.

Yes, it's responsive also. :)

http://themindfulnesssummit.dev.com/sessions/joseph-goldstein/
CN9h*!pWVgrJIk7B
*/

# Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

if (!class_exists('Popup_Login_Custom_Window'))
{
    class Popup_Login_Custom_Window
    {
        private static $instance;
        private $active_page = 'register';
        private $is_error = false;
        private $error_message = '';
        private $variables = array(
            'login' => array(
                'email' => '',
            ),
            'register' => array(
                'name' => '',
                'email' => '',
            ),
        );

        public static function instance()
        {
            if (!self::$instance)
            {
                self::$instance = new Popup_Login_Custom_Window();
                self::$instance->hooks();
            }
            return self::$instance;
        }

        private function hooks()
        {
            add_action('init', array($this, 'register_popup_login_script'));
            add_action('wp_footer', array($this, 'print_popup_login_script'));
        }

        public function register_popup_login_script() {

            if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['is-popup-login'])) {

                $action = $_POST['action'];
                if ("login" == strtolower($action)) {

                    $this->active_page = 'login';
                    $this->variables['login']['email'] = $_POST['login']['email'];

                    $creds = array();
                    $creds['user_login'] = $_POST['login']['email'];
                    $creds['user_password'] = $_POST['login']['password'];
                    $creds['remember'] = false;
                    $user = wp_signon( $creds, false );
                    if ( is_wp_error($user) ) {
                        $this->is_error = true;
                        $this->error_message = $user->get_error_message();
                    } else {
                        // Crazy part: Wordpress was completing the login process but didn't set the current user. :S
                        wp_set_current_user($user->ID);
                    }

                } elseif ("register" == strtolower($action)) {

                    $this->active_page = 'register';
                    $this->variables['register']['name'] = $_POST['register']['name'];
                    $this->variables['register']['email'] = $_POST['register']['email'];

                    $user_id = username_exists( $_POST['register']['email'] );
                    if ( !$user_id and email_exists($_POST['register']['email']) == false ) {
                        $user_id = wp_create_user( $_POST['register']['email'], $_POST['register']['password'], $_POST['register']['email'] );
                        if ( is_wp_error($user_id) ) {
                            $this->is_error = true;
                            $this->error_message = $user_id->get_error_message();
                        } else {
                            wp_update_user(array(
                                'ID' => $user_id,
                                'user_nicename' => $_POST['register']['name'],
                                'display_name' => $_POST['register']['name'],
                                ));
                        }
                    } else {
                        $this->is_error = true;
                        $this->error_message = 'User already exists.';
                    }

                } elseif ("submit" == strtolower($action)) {

                    $this->active_page = 'reset';

                    $user_id = username_exists( $_POST['register']['email'] );
                    if ( !$user_id ) {
                        $user_id = email_exists($_POST['register']['email']);
                    }
                    if ($user_id) {
                        $random_password = wp_generate_password();
                        wp_update_user(array(
                            'ID' => $user_id,
                            'user_pass' => $random_password,
                        ));
                    } else {
                        $this->is_error = true;
                        $this->error_message = 'Invalid username or e-mail.';
                    }

                } else {
                    // Unknown action, let them show once more the login popup.
                    $this->is_error = true;
                    $this->error_message = 'Invalid action.';
                }
            }

            wp_register_script('popup-login', plugin_dir_url(__FILE__).'assets/js/popup-login.js', array(), '0.0.1', true);
            wp_register_style('popup-login', plugin_dir_url(__FILE__).'assets/css/popup-login.css', array(), '0.0.1');
        }

        public function print_popup_login_script() {

            if (!is_user_logged_in() && ("post" == get_post_type())) {

?>
<div id="popup-login-wrapper">
    <div id="popup-login-popup" class="popup-login-window">
        <div id="popup-login-logo">
            <!-- img src="/wp-content/uploads/2015/05/The-Mindfulness-Summit3.png" -->
            <img src="http://cdn.themindfulnesssummit.com/wp-content/uploads/2015/05/The-Mindfulness-Summit3.png">
        </div>
        <div class="popup-login-title">
            <span class="action-login">Login</span>
            <span class="action-register">Register Access Pass</span>
            <span class="action-reset">Recover My Password</span>
        </div>
        <div class="popup-login-content">
            <span class="action-login">Login to the mindfulness summit. You will have access to everything in here!</span>
            <span class="action-register">Instantly access the mindfulness summit by finishing your 'free access pass' registration. If you created a password already click login down below.</span>
            <span class="action-reset">You forgot your password? No problem dude. Here we are to help you. Just provide us your username or email and we'll send you a new password! How cool is this?</span>
        </div>
        <form method="POST">
            <input type="hidden" name="is-popup-login" value="1">
            <div class="popup-login-form">
                <div class="action-login">
                    <div class="popup-login-section">
                        <label for="login-email">Email</label>
                        <input type="text" name="login[email]" id="login-email" class="input" value="<?php echo $this->variables['login']['email'] ?>" placeholder="Email" autocomplete="off">
                    </div>
                    <div class="popup-login-section">
                        <label for="login-password">Password</label>
                        <input type="password" name="login[password]" id="login-password" class="input" value="" placeholder="Password" autocomplete="off">
                    </div>
                </div>
                <div class="action-register">
                    <div class="popup-login-section full">
                        <label for="register-name">Name (i.e. First &amp; Last name)</label>
                        <input type="text" name="register[name]" id="register-name" class="input" value="<?php echo $this->variables['register']['name'] ?>" placeholder="Your First &amp; Last name" autocomplete="off">
                    </div>
                    <div class="popup-login-section">
                        <label for="register-email">Email</label>
                        <input type="text" name="register[email]" id="register-email" class="input" value="<?php echo $this->variables['register']['email'] ?>" placeholder="Email" autocomplete="off">
                    </div>
                    <div class="popup-login-section">
                        <label for="register-password">Password</label>
                        <input type="password" name="register[password]" id="register-password" class="input" value="" placeholder="Password" autocomplete="off">
                    </div>
                </div>
                <div class="action-reset">
                    <div class="popup-login-section full">
                        <label for="reset-email">Username or Email</label>
                        <input type="text" name="reset[email]" id="reset-email" class="input" value="" placeholder="Username or Email" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="popup-login-submit">
                <input type="submit" name="action" id="submit" class="button-primary action-login" value="Login">
                <input type="submit" name="action" id="submit" class="button-primary action-register" value="Register">
                <input type="submit" name="action" id="submit" class="button-primary action-reset" value="Submit">
            </div>
        </form>
        <div class="popup-login-options">
            <ul>
                <li class="li-login">Already have an 'Access Pass'? <a href="#" class="popup-login-cta" data-rel="login">Log in</a></li>
                <li class="li-register">Don't have an 'Access Pass'? <a href="#" class="popup-login-cta" data-rel="register">Register</a></li>
                <li class="li-reset">Lost your password? <a href="#" class="popup-login-cta" data-rel="reset">Click here</a></li>
            </ul>
        </div>
        <div class="popup-login-error <?php if ($this->is_error): ?>with-content<?php endif ?>">
            <?php echo $this->error_message ?>
        </div>
    </div>
</div>
<script>
(function ($) {
    $(document).ready(function() {
        $('.action-<?php echo $this->active_page ?>', '#popup-login-popup').show();
        $('.popup-login-options .li-<?php echo $this->active_page ?>').hide();
    });
}(jQuery));
</script>

<?php
                wp_print_styles('popup-login');
                wp_print_scripts('popup-login');
            }
        }
    } // class Popup_Login_Custom_Window
} // if class_exists

add_action('plugins_loaded', 'Popup_Login_Custom_Window_Load');
function Popup_Login_Custom_Window_Load() {
    Popup_Login_Custom_Window::instance();
}
