<?php
/* v.0.0.1
New popup login procedure.

Yes, it's responsive also. :)
*/
add_action('init', 'register_popup_login_script');
add_action('wp_footer', 'print_popup_login_script');
function register_popup_login_script() {
    wp_register_script('popup-login', plugin_dir_url(__FILE__).'assets/js/popup-login.js', array(), '0.0.1', true);
    wp_register_style('popup-login', plugin_dir_url(__FILE__).'assets/css/popup-login.css', array(), '0.0.1');
}

function print_popup_login_script() {

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
            <div class="popup-login-form">
                <div class="action-login">
                    <div class="popup-login-section">
                        <label for="login-email">Email</label>
                        <input type="text" name="login-email" id="login-email" class="input" value="" placeholder="Email" autocomplete="off">
                    </div>
                    <div class="popup-login-section">
                        <label for="login-password">Password</label>
                        <input type="text" name="login-password" id="login-password" class="input" value="" placeholder="Password" autocomplete="off">
                    </div>
                </div>
                <div class="action-register">
                    <div class="popup-login-section full">
                        <label for="register-name">Name (i.e. First &amp; Last name)</label>
                        <input type="text" name="register-name" id="register-name" class="input" value="" placeholder="Your First &amp; Last name" autocomplete="off">
                    </div>
                    <div class="popup-login-section">
                        <label for="register-email">Email</label>
                        <input type="text" name="register-email" id="register-email" class="input" value="" placeholder="Email" autocomplete="off">
                    </div>
                    <div class="popup-login-section">
                        <label for="register-password">Password</label>
                        <input type="text" name="register-password" id="register-password" class="input" value="" placeholder="Password" autocomplete="off">
                    </div>
                </div>
                <div class="action-reset">
                    <div class="popup-login-section full">
                        <label for="reset-email">Username or Email</label>
                        <input type="text" name="reset-email" id="reset-email" class="input" value="" placeholder="Username or Email" autocomplete="off">
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
    </div>
</div>

<?php
    wp_print_styles('popup-login');
    wp_print_scripts('popup-login');
}
