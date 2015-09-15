<?php
/*
Plugin Name: Woocommerce Login / Signup Lite
Plugin URI: http://phoeniixx.com/
Description: With this free Sign Up/ Login plugin, you can easily create a sign up and login process for your ecommerce site.
Author: phoeniixx
Author URI: http://phoeniixx.com/
Version: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

ob_start();

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) 
{

	function add_login_shortcode() 
	{
		ob_start();
		
		if ( !is_user_logged_in() ) 
		{ 
		
			echo'<div class="woocommerce">';
			
			if(isset($_POST['login'])) 
			{	
			
				global $wpdb;
				
				
				$username = $wpdb->escape(sanitize_text_field($_POST['username']));
				
				$password = $wpdb->escape(sanitize_text_field($_POST['password']));
				
				$remember = $wpdb->escape(sanitize_text_field($_POST['rememberme']));
				
				$remember = ( $remember ) ? 'true' : 'false';
				
				if($username == '')
				{
					
						echo '<ul class="woocommerce-error">
									
									<li><strong>Error:</strong> Username is required.</li>
								
							</ul>';
							
				}
				else if($password == '')
				{
					
					echo '<ul class="woocommerce-error">
								
								<li><strong>Error:</strong> Password is required.</li>
						
						</ul>';
						
				}
				else
				{
					
					if(is_email($username)) 
					{
						
						$user= get_user_by('email',$username);
						
						if($user)
						{
							
							if(wp_check_password( $password, $user->user_pass))
							{
								
								wp_set_current_user( $user->ID, $user->user_login );
								
								wp_set_auth_cookie( $user->ID );
								
								do_action( 'wp_login', $user->user_login );
								
								$location = home_url()."/my-account/";
								
								wp_redirect( $location );
								
								exit;
								
							}
							else
							{
								
								echo '<ul class="woocommerce-error">
										
											<li><strong>ERROR</strong>: The password you entered for the username <strong>'.$user->user_login.'</strong> is incorrect. 		
										
											<a href="'.get_site_url().'/my-account/lost-password/">Lost your password?</a></li>
									 
									</ul>';
							
							}
							
						}
						else
						{
							
							echo '<ul class="woocommerce-error">
									
									<li><strong>Error:</strong> A user could not be found with this email address.</li>
								  
								 </ul>';
								 
						}
						
					}
					else
					{
						
						$login_data = array();

						$login_data['user_login'] = $username;

						$login_data['user_password'] = $password;

						$login_data['remember'] = $remember;
		
						$user_verify = wp_signon($login_data,false);  
						
						if(is_wp_error($user_verify))
						{
								
								echo '<ul class="woocommerce-error">
				
											<li>'.$user_verify->get_error_message().'</li>
									  
									  </ul>';       
									  
						}
						else 
						{ 
							wp_set_current_user( $user_verify->ID, $user_verify->user_login );
						
							wp_set_auth_cookie( $user_verify->ID );
							
							do_action( 'wp_login', $user_verify->user_login );
							
							$location = home_url();  
							
							wp_redirect( $location );
							
							exit;
						
						} 
						
					}   
					
				}
				
			}
?>        
							
			<div class="col-set" id="customer_login">
				<div class="col">
					<h2>Login</h2>
					<form method="post" class="login">
						<p class="form-row form-row-wide">
							<label for="username">Username or email address <span class="required">*</span></label>
							<input type="text" class="input-text" name="username" id="username" value="<?php echo isset($_POST['username']) ? $_POST['username']: '' ?>">
						</p>
						<p class="form-row form-row-wide">
							<label for="password">Password <span class="required">*</span></label>
							<input class="input-text" type="password" name="password" id="password">
						</p>
						<p class="form-row">
							<input type="hidden" id="_wpnonce" name="_wpnonce" value="fd684f83cf">
							<input type="hidden" name="_wp_http_referer" value="/my-account/">				
							<input type="submit" class="button" id="login" name="login" value="Login">
							<label for="rememberme" class="inline">
								<input name="rememberme" type="checkbox" id="rememberme" value="forever"> Remember me </label>
						</p>
						<p class="lost_password">
							<a href="<?php echo get_site_url(); ?>/my-account/lost-password/">Lost your password?</a>
						</p>
					</form>
				</div>
			</div>
			</div>        
<?php  
		}
		else
		{
			  
			  $location = get_home_url();
			  wp_redirect( $location);
			  
		}
	return ob_get_clean();
	}
	
    function add_signup_shortcode()
	{ 
		ob_start();
		if ( !is_user_logged_in() ) 
		{ 
			
			echo ' <div class="woocommerce">';
			
			if(isset($_POST['register']))
			{ 
				
				$reg_email = sanitize_email($_POST['email']);
				
				$reg_password =  sanitize_text_field($_POST['password']);
				
				$arr_name = split("@",$reg_email);  $temp = $arr_name[0];
				
				$user = get_user_by( 'email',$reg_email );			   
			    
				if($reg_email == '')
				{
					
					echo '<ul class="woocommerce-error">
							<li><strong>Error:</strong> Please provide a valid email address.</li>
						  </ul>';
			    
				}
				
				else if($reg_password == '')
				{
				
					echo '<ul class="woocommerce-error">
							<li><strong>Error:</strong> Please enter an account password.</li>
					      </ul>';
			    }
				else
				{
					
					if(is_email($reg_email))
					{ 	
						
						if($user->user_email == $reg_email)
						{
						
							echo'<ul class="woocommerce-error">
									
									<li><strong>Error:</strong> An account is already registered with your email address. Please login.</li>
								 
								 </ul>';
						}
					    else
						{             
							
							$userdata=array("role"=>"customer",
							
											"user_email"=>$reg_email,
											
											"user_login"=>$temp,
											
											"user_pass"=>$reg_password);
							
							if($user_id = wp_insert_user( $userdata ))
							{ 
							    
								$user1 = get_user_by('id',$user_id);
							    
								wp_set_current_user( $user1->ID, $user1->user_login );
											   
							    wp_set_auth_cookie( $user1->ID );
							   
							    do_action( 'wp_login', $user1->user_login );
							   
							    $location = home_url()."/my-account/"; 
							   
							    wp_redirect( $location );
							   
							    exit;												 
							}
							
						}
						
					}
					else
					{
						echo '<ul class="woocommerce-error">
							
									<li><strong>Error:</strong> Please provide a valid email address.</li>
							
							</ul>';
							
					} 
					
				}
				
			}
			
?>        
	
			<div class="col-set" id="customer_login">
				<div class="col">
					<h2>Register</h2>
					<form method="post" class="register">						
						<p class="form-row form-row-wide">
							<label for="reg_email">Email address <span class="required">*</span></label>
							<input type="email" class="input-text" name="email" id="reg_email" value="<?php echo isset($_POST['email']) ? $_POST['email']: '' ?>" >
						</p>			
							<p class="form-row form-row-wide">
								<label for="reg_password">Password <span class="required">*</span></label>
								<input type="password" class="input-text" name="password" id="reg_password " >
							</p>			
						<div style="left: -999em; position: absolute;"><label for="trap">Anti-spam</label><input type="text" name="email_2" id="trap" tabindex="-1"></div>						
						<p class="form-row">
							<input type="hidden" id="_wpnonce" name="_wpnonce" value="70c2c9e9dd"><input type="hidden" name="_wp_http_referer" value="/my-account/">				
							
							<input type="submit" class="button" name="register" value="Register">
						</p>
					</form>
				</div>
			</div>
		</div>
		
<?php        

		}
		else
		{
			
			$location = get_home_url();
			
			wp_redirect( $location);
			
	    }
		return ob_get_clean();
	} // end of add_signup_shortcode().
	   
      // header short code area start(login):
	
	add_action( 'wp_ajax_val_header', 'header_validate' );
	
	add_action( 'wp_ajax_nopriv_val_header', 'header_validate' );
	
	function header_validate()
	{
		
		if ( !is_user_logged_in() ) 
		{  
			
			global $wpdb;
									
			$username = $wpdb->escape(sanitize_text_field($_POST['username']));
			
			$password = $wpdb->escape(sanitize_text_field($_POST['password']));
			
			$remember = $wpdb->escape(sanitize_text_field($_POST['rememberme']));
			
			if($remember) $remember = "true";
			
			else $remember = "false";
			
			if($username == '')
			{
				
				echo '<ul class="woocommerce-error">
						
						<li><strong>Error:</strong> Username is required.</li>
					  
					  </ul>';
					  
			}
			else if($password == '')
			{
				
				echo '<ul class="woocommerce-error">
						
						<li><strong>Error:</strong> Password is required.</li>
					  
					  </ul>';
					  
			}
			else
			{				
					
					if(is_email($username))
					{
						
						$user= get_user_by('email',$username);
						
						if($user)
						{
							
							if(wp_check_password( $password, $user->user_pass))
							{
							   
							   echo "success";	
							    
							   wp_set_current_user( $user->ID, $user->user_login );
							   
							   wp_set_auth_cookie( $user->ID );
							   
							   do_action( 'wp_login', $user->user_login );
							   
							   exit;
							   
							}
							else
							{
								
								echo '<ul class="woocommerce-error">
									
										<li><strong>ERROR</strong>: The password you entered for the username <strong>'.$user->user_login.'</strong> is incorrect. 
									  
										 <a href="'.get_site_url().'/my-account/lost-password/">Lost your password?</a></li>
									 
									</ul>';
							
							}	
							
						}
						else
						{
							
							echo '<ul class="woocommerce-error">
							
										<li><strong>Error:</strong> A user could not be found with this email address.</li>
								  
								 </ul>';
								 
						}						
						
						}
						else
						{
						
							$login_data = array();
							
							$login_data['user_login'] = $username;
							
							$login_data['user_password'] = $password;
							
							$login_data['remember'] = $remember;
							
							$user_verify = wp_signon($login_data,false);  
							 
							if (is_wp_error($user_verify))
							{
								
								echo '<ul class="woocommerce-error">
								
											<li>'.$user_verify->get_error_message().'</li>
									 
									</ul>';                        
							
							}
							else
							{ 

								echo "success";
							  
								wp_set_current_user( $user_verify->ID, $user_verify->user_login );
							    
								wp_set_auth_cookie( $user_verify->ID );
							    
								do_action( 'wp_login', $user_verify->user_login );
							    
							    exit;
							
							} 
						
						}      
			
			}

			exit;
        
		}
		else
		{
			
			echo '<ul class="woocommerce-error">
                     
					 <li><strong>Error:</strong> A user already loged in, Logout First.</li>
                  
				 </ul>';
				 
		}
		
		exit;
    
	}   // end of header_validate	 
	    // header short code area end(login)
		// header short code area start(signup):
		 
	add_action( 'wp_ajax_val_header_signup', 'header_validate_signup' );
	
	add_action( 'wp_ajax_nopriv_val_header_signup', 'header_validate_signup' );
    
	function header_validate_signup()
	{
		
		if (!is_user_logged_in())
		{ 
		    
			$reg_email = sanitize_email($_POST['email']);
		    
			$reg_password =  sanitize_text_field($_POST['password']);
		    
			$arr_name = split("@",$reg_email);  $temp = $arr_name[0];
		    
			$user = get_user_by( 'email',$reg_email );
		   
		    if($reg_email == '')
			{
				
				echo '<ul class="woocommerce-error">
						
						<li><strong>Error:</strong> Please provide a valid email address.</li>
					  
					</ul>';
					
		    }
			
			else if($reg_password == '')
			{
				
				echo '<ul class="woocommerce-error">
			    
						<li><strong>Error:</strong> Please enter an account password.</li>
				      
					 </ul>';
					 
		    }
			else
			{
			   
				if(is_email($reg_email))
				{ 
					
					if($user->user_email == $reg_email)
					{
						
						echo'<ul class="woocommerce-error">
								
								<li><strong>Error:</strong> An account is already registered with your email address. Please login.</li>
							 
							</ul>';
					
					}
					else
					{             
						
						$userdata=array("role"=>"customer",
						
									"user_email"=>$reg_email,
									
									"user_login"=>$temp,
									
									"user_pass"=>$reg_password);
						
						if($user_id = wp_insert_user( $userdata ))
						{
							
							echo "success";
							
							$user1 = get_user_by('id',$user_id);
							
							wp_set_current_user( $user1->ID, $user1->user_login );
							
							wp_set_auth_cookie( $user1->ID );
							
							do_action( 'wp_login', $user1->user_login );
							
							exit;
											 
						}
						else
						{
						
						}
					
					}
			    
				}
				else
				{
					
					echo '<ul class="woocommerce-error">
						
							<li><strong>Error:</strong> Please provide a valid email address.</li>
						
						</ul>';
				
				} 
			
			}		
			
			exit;
		}
		else
		{
			
			echo '<ul class="woocommerce-error">
			
						<li><strong>Error:</strong> A user already loged in, Logout First.</li>
				  
				  </ul>';
		
		}			
		
		exit;
	
	}// header short code area  end(signup)
   
    function add_header_shortcode()
	{
        ob_start();
		
		if (!is_user_logged_in())
		{ 
			
			// ajax call start
			
			wp_enqueue_script("login-signup-js",plugins_url( '' , __FILE__ )."/assets/js/custom.js",array('jquery'),'',true);
			
			wp_localize_script( 'login-signup-js', 'woo_log_ajaxurl', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
			
			//end of ajax call
     
			
			
			wp_enqueue_script("jquery.colorbox-js",plugins_url( '' , __FILE__ )."/assets/js/jquery.colorbox.js",array('jquery'),'',true);
                
			echo '<link rel="stylesheet" type="text/css" href="'.plugins_url( '' , __FILE__ ).'/assets/css/colorbox.css" />';
        
			if(get_option("popup_status") == 'on')
			{
			
				echo '<p><a href="#" class="header_login" >Login</a><a href="#" class="header_signup"> Sign Up</a> ';
				
?>
				
				<div style="display: none;">
<?php          
            
				echo '<div id="login_data">';          
				
				echo'<div class="woocommerce">';        
				
?>                      
				<div class="col-set" id="customer_login" >
					<div class="col" >
					<div class="result1"></div> 
						<h2>Login</h2>
						<form method="post" class="login" id="js_login">
							<p class="form-row form-row-wide">
								<label for="username">Username or email address <span class="required">*</span></label>
								<input type="text" class="input-text" name="username" id="username" value="<?php echo isset($_POST['username']) ? $_POST['username']: '' ?>">
							</p>
							<p class="form-row form-row-wide">
								<label for="password">Password <span class="required">*</span></label>
								<input class="input-text" type="password" name="password" id="password">
							</p>
							<p class="form-row">
								<input type="hidden" id="_wpnonce" name="_wpnonce" value="fd684f83cf">
								<input type="hidden" name="_wp_http_referer" value="/my-account/"><div class="loader1" style="display:none;" ><img src="<?php echo plugins_url( '' , __FILE__ )."/assets/img/ajax-loader.gif" ?>"/></div>				
								<input type="submit" class="button" name="login" value="Login" id="login1">
								<label for="rememberme" class="inline">
            					<input name="rememberme" type="checkbox" id="rememberme" value="forever"> Remember me </label>
							</p>
            			<p class="lost_password">
							<a href="<?php echo get_site_url(); ?>/my-account/lost-password/">Lost your password?</a>
            			</p>
            		</form>
            	</div>
            </div> 
            </div>  
            </div>  <!-- end of login data -->
           
            <div id="signup_data">
<?php       
     
            echo ' <div class="woocommerce">';
         
?>        
        
   
                <div class="col-set" id="customer_login">
            	<div class="col" >
            		<div class="result2"></div>
					<h2>Register</h2>
            		<form method="post" class="register" id="js_signup" >						
            			<p class="form-row form-row-wide">
            				<label for="reg_email">Email address <span class="required">*</span></label>
            				<input type="email" class="input-text" name="email" id="reg_email_header" value="<?php echo isset($_POST['email']) ? $_POST['email']: '' ?>" >
            			</p>			
            				<p class="form-row form-row-wide">
            					<label for="reg_password">Password <span class="required">*</span></label>
            					<input type="password" class="input-text" name="password" id="reg_password_header" >
            				</p>			
            			<!-- Spam Trap -->
            			<div style="left: -999em; position: absolute;"><label for="trap">Anti-spam</label><input type="text" name="email_2" id="trap" tabindex="-1"></div>						
            			<p class="form-row">
            				<input type="hidden" id="_wpnonce" name="_wpnonce" value="70c2c9e9dd"><input type="hidden" name="_wp_http_referer" value="/my-account/">				
                            <div class="loader_reg" style="display:none;" ><img src="<?php echo plugins_url( '' , __FILE__ )."/assets/img/ajax-loader.gif" ?>"/></div>				
							<input type="submit" class="button" name="register_header" value="Register">
            			</p>
            		</form>
            	</div>
            </div>
            </div>
            </div> <!-- end of signup data -->
<?php 
			}
			else
			{
				
				echo '<p><a href="'.get_option("login_url").'"  >Login </a><a href="'.get_option("signup_url").'">Sign Up</a> ';
				 
			} 
?> 
            </div>
       
<?php
         
		}
		else
		{
			
			$user_obj = wp_get_current_user();
			 			 
?>

			<p>Hello <strong><?php echo $user_obj->user_login; ?></strong> (not <?php echo $user_obj->user_login; ?> 
			  <a href="<?php echo wp_logout_url( get_permalink() );  ?>">Sign out</a>).
			</p>

<?php	
				
		}
		return ob_get_clean();
	}
       
       
	add_shortcode("wp-login-form","add_login_shortcode");
       
	add_shortcode("wp-signup-form","add_signup_shortcode");
    
	add_shortcode("wp-header","add_header_shortcode");
	
    add_filter( 'widget_text', 'shortcode_unautop');
	
	add_filter('widget_text', 'do_shortcode');
	
	function ph_login_signup_add_menu()
	{
		
		$page_title='Login/Signup Setting';
		
		$menu_title='Login/Signup';
		
		$capability='manage_options';
		
		$menu_slug='login_signup_settings';
		
		$function='settings_wp_login_signup';
		
        
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug,$function , $icon_url, $position );
		
		add_menu_page( 'phoeniixx', __( 'Phoeniixx', 'phe' ), 'nosuchcapability', 'phoeniixx', NULL, plugin_dir_url( __FILE__ ).'assets/img/logo-wp.png', 57 );
        
		add_submenu_page( 'phoeniixx', $page_title, $menu_title, $capability, $menu_slug, $function );

	}
	
    add_action("admin_menu","ph_login_signup_add_menu",99);
	
    function settings_wp_login_signup()
	{ 
        
		
		
		echo "<h3>Plugin Settings</h3>"; 
     
        if(isset($_POST['submit_1']))
		{
            
			$popup = sanitize_text_field($_POST['popup']);
            
			$login_url= sanitize_text_field($_POST['login_page']);
            
			$signup_url= sanitize_text_field($_POST['signup_page']);
            
            if($popup=='on')
			{
                
                $option="popup_status";
            
				$value="on";
                
				$autoload="yes";
                
				update_option($option, $value, $autoload );
                
?>
 
               <div class="updated notice is-dismissible below-h2" id="message"><p>Successfully saved. </p><button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>

<?php
                                                
            }
			else if($popup!='on' && $login_url!='' && $signup_url !='' )
			{
                
                $option="popup_status";
                
				$value="off";
                
                $autoload="yes";
                
                update_option($option, $value, $autoload ); 
                
                $option="login_url";
                
				$value= $login_url;
                
                $autoload="yes";
                
                update_option($option, $value, $autoload ); 
                
                $option="signup_url";
                
				$value=$signup_url;
                
                $autoload="yes";
                
                update_option($option, $value, $autoload );              
                
?> 

               <div class="updated notice is-dismissible below-h2" id="message"><p>Successfully saved. </p><button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>

<?php

                        
            }
			else
			{
               
?>

                <div class="error notice is-dismissible below-h2" id="message"><p>Fields with * are mandatory, try again. </p><button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>

<?php                

            }
        
		}
        if(get_option("popup_status")!= 'on')
		{  

	?>
    
			<div class="wrap" id="profile-page">
			<form action="" id="form7" method="post">
			<table class="form-table">
			<tbody>		
			<tr class="user-nickname-wrap">
				<th><label>Popup Enable</label></th><td><input type="checkbox" id="popup1" name="popup"    /></td>
			</tr>
		
			<tr class="login user-nickname-wrap">
			<th><label>Login Page Slug</label></th><td><?php echo get_site_url()."/ "; ?><input id="log_url" type="text"  name="login_page" value="<?php echo get_option("login_url"); ?>"   />*</td>
			
			</tr>
			
			<tr class="signup user-nickname-wrap">
				<th><label>Signup Page Slug</label></th><td><?php echo get_site_url()."/ "; ?><input id="sign_url" type="text"  name="signup_page" value="<?php echo get_option("signup_url"); ?>"    />*</td>
			   
			</tr>
			
			<tr class="user-nickname-wrap">
			<td colspan="2"><input type="submit" class="button button-primary" id="submit1" name="submit_1" value="Save" /> </td>
			
			</tr>
   		   </tbody>	
			</table>
			</form>
			</div>
		   
<?php

        }
		else
		{    

	?>  
        
			<style>
			.login{display: none;}
			.signup{display:none;}
			</style>

        
			<div class="wrap" id="profile-page">
			<form action="" id="form7" method="post">
			<table class="form-table">
			<tbody>		
			<tr class="user-nickname-wrap">
				<th><label>Popup Enable</label></th><td><input type="checkbox" id="popup1" name="popup"  checked  /></td>
			</tr>
			
			<tr class="login user-nickname-wrap">
			<th><label>Login Page Slug</label></th><td><?php echo get_site_url()."/ "; ?><input id="log_url" type="text"  name="login_page"   />*</td>
				
			</tr>
				
			<tr class="signup user-nickname-wrap">
				<th><label>Signup Page Slug</label></th><td><?php echo get_site_url()."/ "; ?><input id="sign_url" type="text"  name="signup_page"    />*</td>
				
			</tr>
			
			<tr class="user-nickname-wrap">
			<td colspan="2"><input type="submit" class="button button-primary" id="submit1" name="submit_1" value="Save" /> </td>
			
			</tr>
		   </tbody>	
			</table>
			</form>
			</div>      
			
<?php      

        } 
       
		wp_enqueue_script("conditions-js",plugins_url( '' , __FILE__ ).'/assets/js/admin.js',array('jquery'),'',true);
    
	}
	
}
else
{ 

?>

    <div class="error notice is-dismissible " id="message"><p>Please <strong>Activate</strong> WooCommerce Plugin First, to use woocommerce Social Login.</p><button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
        
<?php 

} 
  
ob_clean(); 
  
?>