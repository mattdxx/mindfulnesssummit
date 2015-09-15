<?php global $options; $options = get_option('ciusan_register_login'); ?>
<form method="post" id="mainform" action="">
<table class="ciusan-plugin widefat" style="margin-top:50px;">
	<thead>
		<tr>
			<th scope="col">Title Settings</th>
			<th scope="col">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="titledesc">Login text</td>
			<td class="forminp">
				<input name="login_title" id="login_title" style="width:250px;" value="<?php echo $options[login_title]; ?>" type="text" class="required" placeholder="eg: &quot;Login&quot;">
			</td>
		</tr><tr>
			<td class="titledesc">Register text</td>
			<td class="forminp">
				<input name="register_title" id="register_title" style="width:250px;" value="<?php echo $options[register_title]; ?>" type="text" placeholder="eg: &quot;Create an Account!&quot;">
			</td>
		</tr><tr>
			<td class="titledesc">Forgot password text</td>
			<td class="forminp">
				<input name="forgot_password_title" id="forgot_password_title" style="width:250px;" value="<?php echo $options[forgot_password_title]; ?>" type="text" class="required" placeholder="eg: &quot;Forgot Password?&quot;">
			</td>
		</tr>
	</tbody>
</table><table class="ciusan-plugin widefat" style="margin-top:25px;">
	<thead>
		<tr>
			<th scope="col">Button Settings</th>
			<th scope="col">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="titledesc">Login Button Text</td>
			<td class="forminp">
				<input name="button_login" id="button_class" style="width:250px;" value="<?php echo $options[button_login]; ?>" type="text" class="required" placeholder="eg: &quot;Login&quot;">
			</td>
		</tr><tr>
			<td class="titledesc">Register Button Text</td>
			<td class="forminp">
				<input name="button_register" id="button_class" style="width:250px;" value="<?php echo $options[button_register]; ?>" type="text" class="required" placeholder="eg: &quot;Register Now&quot;">
			</td>
		</tr><tr>
			<td class="titledesc">Forgot Password Text</td>
			<td class="forminp">
				<input name="button_forgot_password" id="button_class" style="width:250px;" value="<?php echo $options[button_forgot_password]; ?>" type="text" class="required" placeholder="eg: &quot;Get Password&quot;">
			</td>
		</tr><tr>
			<td class="titledesc">Button class</td>
			<td class="forminp">
				<input name="button_class" id="button_class" style="width:250px;" value="<?php echo $options[button_class]; ?>" type="text" class="required" placeholder="eg: &quot;button&quot; or &quot;button big red&quot;">
			</td>
		</tr>
	</tbody>
</table><table class="ciusan-plugin widefat" style="margin-top:25px;">
	<thead>
		<tr>
			<th scope="col">Redirect URL Settings</th>
			<th scope="col">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="titledesc">Login Redirect URL</td>
			<td class="forminp">
				<input name="login_redirect_URL" id="button_class" style="width:250px;" value="<?php echo $options[login_redirect_URL]; ?>" type="text" class="required" placeholder="Please use &quot;http://&quot;">
			</td>
		</tr><tr>
			<td class="titledesc">Register Redirect URL</td>
			<td class="forminp">
				<input name="register_redirect_URL" id="button_class" style="width:250px;" value="<?php echo $options[register_redirect_URL]; ?>" type="text" class="required" placeholder="Please use &quot;http://&quot;">
			</td>
		</tr>
	</tbody>
</table><table class="ciusan-plugin widefat" style="margin-top:25px;">
	<thead>
		<tr>
			<th scope="col">Google reCaptcha</th>
			<th scope="col"><a target="_blank" href="https://www.google.com/recaptcha/intro/index.html">More information</a></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="titledesc">Google Site Key</td>
			<td class="forminp">
				<input name="Google_Site_Key" id="button_class" style="width:250px;" value="<?php echo $options[Google_Site_Key]; ?>" type="text" class="required" placeholder="eg &quot;6Lf75gIT02AFBH8KfqlV0PV_t6J2vEB-1tbbCDG&quot;">
			</td>
		</tr><tr>
			<td class="titledesc">Google Secret Key</td>
			<td class="forminp">
				<input name="Google_Secret_Key" id="button_class" style="width:250px;" value="<?php echo $options[Google_Secret_Key]; ?>" type="text" class="required" placeholder="eg &quot;6Lf75gIT02AFBH8KfqlV0PV_t6J2vEB-1tbbCDG&quot;">
			</td>
		</tr>
	</tbody>
</table>
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="<?php get_option($options) ?>" />
<p class="submit"><input type="submit" name="save" id="submit" class="button button-primary" value="Save Changes"/></p>
</form>
</div>

<div class="wrap"><hr /></div>