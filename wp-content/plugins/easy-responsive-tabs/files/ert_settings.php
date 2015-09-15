<?php
if (isset($_POST['ert_submit'])) {
	update_option( 'ERT_BOOTSTRAP_JS_LOCATION', $_POST['ert_js'] );
	update_option( 'ERT_BOOTSTRAP_CSS_LOCATION', $_POST['ert_css'] );

	$ertjs = $_POST['ert_js'];
	$ertcss = $_POST['ert_css'];
}
else {
	$ertjs = get_option( 'ERT_BOOTSTRAP_JS_LOCATION', 1 );
	$ertcss = get_option( 'ERT_BOOTSTRAP_CSS_LOCATION', 1 );
}

?>
<div class="wrap">
<?php echo screen_icon('plugins');?><h2>Easy Bootstrap Shortcodes Settings for js/css files</h2>
<form name="ert_setting" id="ert_setting" method="post" action="">
<table class="form-table">
	<tbody>
	<tr valign="top">
		<th scope="row">
			<label for="blogname">bootstrap.js file</label>
		</th>
		<td>
			<input type="radio" name="ert_js" id="ert_js_plugin" value="1" <?php echo ($ertjs == 1) ? 'checked=checked' : ''; ?>>
			<label for="ert_js_plugin">Use from EBS Plugin</label>
			<input type="radio" name="ert_js" id="ert_js_theme" class="check_cdn" value="2" <?php echo ($ertjs == 2) ? 'checked=checked' : ''; ?>><label for="ert_js_theme">Use from theme or any other plugin</label>

		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<label for="blogname">bootstrap.css file</label>
		</th>
		<td>
			<input type="radio" name="ert_css" id="ert_css_plugin" value="1" <?php echo ($ertcss == 1) ? 'checked=checked' : '' ?>>
			<label for="ert_css_plugin">Use from EBS Plugin</label>
			<input type="radio" name="ert_css" id="ert_css_theme" class="check_cdn" value="2" <?php echo ($ertcss == 2) ? 'checked=checked' : ''; ?>><label for="ert_css_theme">Use from theme or any other plugin</label>

		</td>
	</tr>
	</tbody>
</table>
	<p>
	<input id="submit" class="button button-primary" type="submit" value="Save Changes" name="ert_submit">
	</p>
</form>
</div>