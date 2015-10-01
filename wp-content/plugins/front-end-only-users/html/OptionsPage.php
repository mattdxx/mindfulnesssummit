<?php 
	$Login_Time = get_option("EWD_FEUP_Login_Time");
	$Sign_Up_Email = get_option("EWD_FEUP_Sign_Up_Email");
	$Custom_CSS = get_option("EWD_FEUP_Custom_CSS");
	$Default_User_Level = get_option("EWD_Default_User_Level");
	$Use_Crypt = get_option("EWD_FEUP_Use_Crypt");
	$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");

	$Use_Captcha = get_option("EWD_FEUP_Use_Captcha");
	$Track_Events = get_option("EWD_FEUP_Track_Events");
	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
	$Email_On_Admin_Approval = get_option("EWD_FEUP_Email_On_Admin_Approval");
	$Admin_Email_On_Registration = get_option("EWD_FEUP_Admin_Email_On_Registration");
	$Email_Confirmation = get_option("EWD_FEUP_Email_Confirmation");
	$Default_User_Level = get_option("EWD_Default_User_Level");

	$WooCommerce_Integration = get_option('EWD_FEUP_WooCommerce_Integration');
	$First_Name_Field = get_option('EWD_FEUP_WooCommerce_First_Name_Field');
	$Last_Name_Field = get_option('EWD_FEUP_WooCommerce_Last_Name_Field');
	$Company_Field = get_option('EWD_FEUP_WooCommerce_Company_Field');
	$Address_Line_One_Field = get_option('EWD_FEUP_WooCommerce_Address_Line_One_Field');
	$Address_Line_Two_Field = get_option('EWD_FEUP_WooCommerce_Address_Line_Two_Field');
	$City_Field = get_option('EWD_FEUP_WooCommerce_City_Field');
	$Postcode_Field = get_option('EWD_FEUP_WooCommerce_Postcode_Field');
	$Country_Field = get_option('EWD_FEUP_WooCommerce_Country_Field');
	$State_Field = get_option('EWD_FEUP_WooCommerce_State_Field');
	$Email_Field = get_option('EWD_FEUP_WooCommerce_Email_Field');
	$Phone_Field = get_option('EWD_FEUP_WooCommerce_Phone_Field');


	$feup_Styling_Form_Font =  get_option("EWD_FEUP_Styling_Form_Font");
	$feup_Styling_Form_Font_Size =  get_option("EWD_FEUP_Styling_Form_Font_Size");
	$feup_Styling_Form_Font_Weight =  get_option("EWD_FEUP_Styling_Form_Font_Weight");
	$feup_Styling_Form_Font_Color =  get_option("EWD_FEUP_Styling_Form_Font_Color");
	$feup_Styling_Form_Margin =  get_option("EWD_FEUP_Styling_Form_Margin");
	$feup_Styling_Form_Padding =  get_option("EWD_FEUP_Styling_Form_Padding");
	$feup_Styling_Submit_Bg_Color =  get_option("EWD_FEUP_Styling_Submit_Bg_Color");
	$feup_Styling_Submit_Font =  get_option("EWD_FEUP_Styling_Submit_Font");
	$feup_Styling_Submit_Font_Color =  get_option("EWD_FEUP_Styling_Submit_Font_Color");
	$feup_Styling_Submit_Margin =  get_option("EWD_FEUP_Styling_Submit_Margin");
	$feup_Styling_Submit_Padding =  get_option("EWD_FEUP_Styling_Submit_Padding");

	$feup_Styling_Userlistings_Font =  get_option("EWD_FEUP_Styling_Userlistings_Font");
	$feup_Styling_Userlistings_Font_Size =  get_option("EWD_FEUP_Styling_Userlistings_Font_Size");
	$feup_Styling_Userlistings_Font_Weight =  get_option("EWD_FEUP_Styling_Userlistings_Font_Weight");
	$feup_Styling_Userlistings_Font_Color =  get_option("EWD_FEUP_Styling_Userlistings_Font_Color");
	$feup_Styling_Userlistings_Margin = get_option("EWD_FEUP_Styling_Userlistings_Margin");
	$feup_Styling_Userlistings_Padding = get_option("EWD_FEUP_Styling_Userlistings_Padding");
	$feup_Styling_Userprofile_Label_Font =  get_option("EWD_FEUP_Styling_Userprofile_Label_Font");
	$feup_Styling_Userprofile_Label_Font_Size =  get_option("EWD_FEUP_Styling_Userprofile_Label_Font_Size");
	$feup_Styling_Userprofile_Label_Font_Weight =  get_option("EWD_FEUP_Styling_Userprofile_Label_Font_Weight");
	$feup_Styling_Userprofile_Label_Font_Color =  get_option("EWD_FEUP_Styling_Userprofile_Label_Font_Color");
	$feup_Styling_Userprofile_Content_Font =  get_option("EWD_FEUP_Styling_Userprofile_Content_Font");
	$feup_Styling_Userprofile_Content_Font_Size =  get_option("EWD_FEUP_Styling_Userprofile_Content_Font_Size");
	$feup_Styling_Userprofile_Content_Font_Weight =  get_option("EWD_FEUP_Styling_Userprofile_Content_Font_Weight");
	$feup_Styling_Userprofile_Content_Font_Color =  get_option("EWD_FEUP_Styling_Userprofile_Content_Font_Color");

?>

<div class="wrap feup-options-page-tabbed">
<div class="feup-options-submenu-div">
	<ul class="feup-options-submenu feup-options-page-tabbed-nav">
		<li><a id="Basic_Menu" class="MenuTab options-subnav-tab <?php if ($Display_Tab == '' or $Display_Tab == 'Basic') {echo 'options-subnav-tab-active';}?>" onclick="ShowOptionTab('Basic');">Basic</a></li>
		<li><a id="Premium_Menu" class="MenuTab options-subnav-tab <?php if ($Display_Tab == 'Premium') {echo 'options-subnav-tab-active';}?>" onclick="ShowOptionTab('Premium');">Premium</a></li>
		<li><a id="WooCommerce_Menu" class="MenuTab options-subnav-tab <?php if ($Display_Tab == 'WooCommerce') {echo 'options-subnav-tab-active';}?>" onclick="ShowOptionTab('WooCommerce');">Commerce</a></li>
		<li><a id="Styling_Menu" class="MenuTab options-subnav-tab <?php if ($Display_Tab == 'Styling') {echo 'options-subnav-tab-active';}?>" onclick="ShowOptionTab('Styling');">Styling</a></li>
	</ul>
</div>



<div class="feup-options-page-tabbed-content">
<form method="post" action="admin.php?page=EWD-FEUP-options&DisplayPage=Options&Action=EWD_FEUP_UpdateOptions">
	<div id='Basic' class='feup-option-set'>
	<h3 id='label-basic-options'>Basic Options</h3>
	<table class="form-table">
	<tr>
		<th scope="row">Login Time</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Login Time</span></legend>
			<label title='Login Time'><input type='text' name='login_time' value='<?php echo $Login_Time; ?>' /><span> Minutes</span></label><br />
			<p>For reference: 1440 minutes in a day, 10080 minutes in a week, 43200 minutes in a 30-day month, 525600 minutes in a year</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Send Sign Up Emails</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Send Sign Up Emails</span></legend>
			<label title='Yes'><input type='radio' name='sign_up_email' value='Yes' <?php if($Sign_Up_Email == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
			<label title='No'><input type='radio' name='sign_up_email' value='No' <?php if($Sign_Up_Email == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
			<p>Send e-mails to users after they successfully register.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Custom CSS</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Custom CSS</span></legend>
			<label title='Custom CSS'><textarea name='custom_css'><?php echo $Custom_CSS ?></textarea></label><br />
			<p>Custom CSS that should be included on any page that uses one of the FEUP shortcodes.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Use Crypt</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Use Crypt</span></legend>
			<label title='Yes'><input type='radio' name='use_crypt' value='Yes' <?php if($Use_Crypt == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
			<label title='No'><input type='radio' name='use_crypt' value='No' <?php if($Use_Crypt == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
			<p>Should the plugin use crypt to encode user passwords? (Higher security)<br /><strong>Warning! All current user passwords will permanently stop working when switching between encoding methods!</strong></p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Username is Email</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Username is Email</span></legend>
			<label title='Yes'><input type='radio' name='username_is_email' value='Yes' <?php if($Username_Is_Email == "Yes") {echo "checked='checked'";} ?> /> <span>Yes</span></label><br />
			<label title='No'><input type='radio' name='username_is_email' value='No' <?php if($Username_Is_Email == "No") {echo "checked='checked'";} ?> /> <span>No</span></label><br />
			<p>Should your users register using their e-mail addresses instead of by creating usernames?</p>
			</fieldset>
		</td>
		</tr>
		</table>
	</div>

<div id='Premium' class='feup-option-set feup-hidden'>
<h3 id='label-premium-options'>Premium Options</h3>
	<table class="form-table">
		<tr>
		<th scope="row">Captcha</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Captcha</span></legend>
			<label title='Yes'><input type='radio' name='use_captcha' value='Yes' <?php if($Use_Captcha == "Yes") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
			<label title='No'><input type='radio' name='use_captcha' value='No' <?php if($Use_Captcha == "No") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
			<p>Should Captcha be added to the registration and forgot password forms to prevent spamming? (requires image-creation support for your PHP installation)</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Track User Activity</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Track User Activity</span></legend>
			<label title='Yes'><input type='radio' name='track_events' value='Yes' <?php if($Track_Events == "Yes") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
			<label title='No'><input type='radio' name='track_events' value='No' <?php if($Track_Events == "No") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
			<p>See what pages, attachments, images, etc. each user has looked at, in what order and when, to better tailor your content to your members.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Email Confirmation</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Email Confirmation</span></legend>
			<label title='Yes'><input type='radio' name='email_confirmation' value='Yes' <?php if($Email_Confirmation == "Yes") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
			<label title='No'><input type='radio' name='email_confirmation' value='No' <?php if($Email_Confirmation == "No") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
			<p>Make users confirm their e-mail address before they can log in.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Admin Approval of Users</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Admin Approval of Users</span></legend>
			<label title='Yes'><input type='radio' name='admin_approval' value='Yes' <?php if($Admin_Approval == "Yes") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
			<label title='No'><input type='radio' name='admin_approval' value='No' <?php if($Admin_Approval == "No") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
			<p>Require users to be approved by an administrator in the WordPress back-end before they can log in.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Email on Admin Approval</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Email on Admin Approval</span></legend>
			<label title='Yes'><input type='radio' name='email_on_admin_approval' value='Yes' <?php if($Email_On_Admin_Approval == "Yes") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
			<label title='No'><input type='radio' name='email_on_admin_approval' value='No' <?php if($Email_On_Admin_Approval == "No") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
			<p>Should the users receive an email telling them when they get approved, if admin approval is turned on? Email message can be set on the "Emails" tab.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Admin Email on Registration</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Admin Email on Registration</span></legend>
			<label title='Yes'><input type='radio' name='admin_email_on_registration' value='Yes' <?php if($Admin_Email_On_Registration == "Yes") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
			<label title='No'><input type='radio' name='admin_email_on_registration' value='No' <?php if($Admin_Email_On_Registration == "No") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
			<p>Should the admin email address from the emails tab receive an email each time a new user registers?</p>
			</fieldset>
		</td>
		</tr>
		<th scope="row">Default User Level</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Default User Level</span></legend>
			<label title='Default User Level'><select name='default_user_level' <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?>></label>
				<option value='0'>None (0)</option>
				<?php foreach ($Levels as $Level) {
						echo "<option value='" . $Level->Level_ID . "' ";
						if ($Default_User_Level == $Level->Level_ID) {echo "selected=selected";}
						echo ">" . $Level->Level_Name . " (" . $Level->Level_Privilege . ")</option>";
				}?> 
			</select>
			<p>Which level should users be set to when they register (created on the "Levels" tab)?</p>
			</fieldset>
		</td>
		</tr>
		</table>
</div>

<div id='WooCommerce' class='feup-option-set feup-hidden'>
	<h3 id='label-woocommerce-options'>WooCommerce Integration Options (Premium)</h3>
		<table class="form-table">
		<tr>
		<th scope="row">WooCommerce Integration</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>WooCommerce Integration</span></legend>
			<label title='Yes'><input type='radio' name='woocommerce_integration' value='Yes' <?php if($WooCommerce_Integration == "Yes") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>Yes</span></label><br />
			<label title='No'><input type='radio' name='woocommerce_integration' value='No' <?php if($WooCommerce_Integration == "No") {echo "checked='checked'";} ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "disabled";} ?> /> <span>No</span></label><br />
			<p>Should checkout fields in WooCommerce automatically be filled in for logged in users?</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">First Name Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>First Name Field</span></legend>
			<label title='First Name Field'><input type='text' name='first_name_field' value='<?php echo $First_Name_Field; ?>' /></label><br />
			<p>The name of the FEUP field that should be filled in as "First Name" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Last Name Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Last Name Field</span></legend>
			<label title='Last Name Field'><input type='text' name='last_name_field' value='<?php echo $Last_Name_Field; ?>' /></label><br />
			<p>The name of the FEUP field that should be filled in as "Last Name" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Company Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Company Field</span></legend>
			<label title='Company Field'><input type='text' name='company_field' value='<?php echo $Company_Field; ?>' /></label><br />
			<p>The name of the FEUP field that should be filled in as "Company" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Address Line One Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Address Line One Field</span></legend>
			<label title='Address Line One Field'><input type='text' name='address_line_one_field' value='<?php echo $Address_Line_One_Field; ?>' /></label><br />
			<p>The name of the FEUP field that should be filled in as "Address Line One" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Address Line Two Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Address Line Two Field</span></legend>
			<label title='Address Line Two Field'><input type='text' name='address_line_two_field' value='<?php echo $Address_Line_Two_Field; ?>' /></label><br />
			<p>The name of the FEUP field that should be filled in as "Address Line Two" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">City Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>City Field</span></legend>
			<label title='City Field'><input type='text' name='city_field' value='<?php echo $City_Field; ?>' /></label><br />
			<p>The name of the FEUP field that should be filled in as "City" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Postcode Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Postcode Field</span></legend>
			<label title='Postcode Field'><input type='text' name='postcode_field' value='<?php echo $Postcode_Field; ?>' /></label><br />
			<p>The name of the FEUP field that should be filled in as "Postcode" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Country Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Country Field</span></legend>
			<label title='Country Field'><input type='text' name='country_field' value='<?php echo $Country_Field; ?>' /></label><br />
			<p>The name of the FEUP field that should be filled in as "Country" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">State Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>State Field</span></legend>
			<label title='State Field'><input type='text' name='state_field' value='<?php echo $State_Field; ?>' /></label><br />
			<p>The name of the FEUP field that should be filled in as "State" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Email Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Email Field</span></legend>
			<label title='Email Field'><input type='text' name='email_field' value='<?php echo $Email_Field; ?>' /></label><br />
			<p>The name of the FEUP field that should be filled in as "Email" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		<tr>
		<th scope="row">Phone Field</th>
		<td>
			<fieldset><legend class="screen-reader-text"><span>Phone Field</span></legend>
			<label title='Phone Field'><input type='text' name='phone_field' value='<?php echo $Phone_Field; ?>' /></label><br />
			<p>The name of the FEUP field that should be filled in as "Phone" for billing and shipping in WooCommerce.</p>
			</fieldset>
		</td>
		</tr>
		</table>
		</div>

<div id='Styling' class='feup-option-set feup-hidden'>
		<h3 id='label-styling-options' >Styling Options</h3>
			<div class="feup-label-description"> Apply custom styling to the order tracking plugin </div>
			<div id='styling-view-options' class="feup-options-div feup-options-flex">

		<div class='feup-styling-header'>Form Fields</div>
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Font Family</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_form_font' value='<?php echo $feup_Styling_Form_Font; ?>' /></div>
			</div> 
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Font Size</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_form_font_size' value='<?php echo $feup_Styling_Form_Font_Size; ?>' /></div>
			</div>
		 	<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Font Weight</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_form_font_weight' value='<?php echo $feup_Styling_Form_Font_Weight; ?>' /></div>
			</div> 
		 	<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Font Color</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_form_font_color' value='<?php echo $feup_Styling_Form_Font_Color; ?>' /></div>
			</div> 
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Row Margin</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_form_margin' value='<?php echo $feup_Styling_Form_Margin; ?>' /></div>
			</div>			
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Row Padding</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_form_padding' value='<?php echo $feup_Styling_Form_Padding; ?>' /></div>
			</div>
		<div class='feup-styling-header'>Submit Button</div>
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Background Color</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_submit_bg_color' value='<?php echo $feup_Styling_Submit_Bg_Color; ?>' /></div>
			</div>
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Font Family</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_submit_font' value='<?php echo $feup_Styling_Submit_Font; ?>' /></div>
			</div>
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Font Color</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_submit_font_color' value='<?php echo $feup_Styling_Submit_Font_Color; ?>' /></div>
			</div>
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Button Margin</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_submit_margin' value='<?php echo $feup_Styling_Submit_Margin; ?>' /></div>
			</div>
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Button Padding</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_submit_padding' value='<?php echo $feup_Styling_Submit_Padding; ?>' /></div>
			</div>
		<div class='feup-styling-header'>User Listings</div>
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Listing Font Family</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_userlistings_font' value='<?php echo $feup_Styling_Userlistings_Font; ?>' /></div>
			</div> 
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Listing Font Size</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_userlistings_font_size' value='<?php echo $feup_Styling_Userlistings_Font_Size; ?>' /></div>
			</div>
		 	<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Listing Font Weight</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_userlistings_font_weight' value='<?php echo $feup_Styling_Userlistings_Font_Weight; ?>' /></div>
			</div> 
		 	<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Listing Font Color</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_userlistings_font_color' value='<?php echo $feup_Styling_Userlistings_Font_Color; ?>' /></div>
			</div>
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Listing Row Margin</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_userlistings_margin' value='<?php echo $feup_Styling_Userlistings_Margin; ?>' /></div>
			</div>			
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Listing Row Padding</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_userlistings_padding' value='<?php echo $feup_Styling_Userlistings_Padding; ?>' /></div>
			</div> 
		</div>
		<div class='feup-styling-header'>User Profile Page</div>
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Label Font Family</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_userprofile_label_font' value='<?php echo $feup_Styling_Userprofile_Label_Font; ?>' /></div>
			</div>
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Label Font Size</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_userprofile_label_font_size' value='<?php echo $feup_Styling_Userprofile_Label_Font_Size; ?>' /></div>
			</div>
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Label Font Weight</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_userprofile_label_font_weight' value='<?php echo $feup_Styling_Userprofile_Label_Font_Weight; ?>' /></div>
			</div>
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Label Font Color</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_userprofile_label_font_color' value='<?php echo $feup_Styling_Userprofile_Label_Font_Color; ?>' /></div>
			</div>
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Content Font</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_userprofile_content_font' value='<?php echo $feup_Styling_Userprofile_Content_Font; ?>' /></div>
			</div>
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Content Font Size</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_userprofile_content_font_size' Content='<?php echo $feup_Styling_Userprofile_Content_Font_Size; ?>' /></div>
			</div>
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Content Font Weight</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_userprofile_content_font_weight' value='<?php echo $feup_Styling_Userprofile_Content_Font_Weight; ?>' /></div>
			</div>
			<div class='feup-option feup-styling-option'>
				<div class='feup-option-label'>Content Font Color</div>
				<div class='feup-option-input'><input type='text' name='feup_styling_userprofile_content_font_color' value='<?php echo $feup_Styling_Userprofile_Content_Font_Color; ?>' /></div>
			</div>
			</div>

		<p class="submit"><input type="submit" name="Options_Submit" id="submit" class="button button-primary" value="Save Changes"  /></p></form>

		</div>
		</div>