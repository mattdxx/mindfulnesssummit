<?php 
		$Login_Time = get_option("EWD_FEUP_Login_Time");
?>
<div class="wrap">
<div id="icon-options-general" class="icon32"><br /></div><h2>Settings</h2>

<form method="post" action="admin.php?page=EWD-FEUP-options&DisplayPage=Advanced&Action=EWD_FEUP_UpdateAdvanced">
<!--<table class="form-table">
<tr>
<th scope="row">Login Time</th>
<td>
	<fieldset><legend class="screen-reader-text"><span>Login Time</span></legend>
	<label title='Blue'><input type='text' name='login_time' value='<?php echo $Login_Time; ?>' /><span>Minutes</span></label><br />
	</fieldset>
</td>
</tr>
</table>-->


<p class="submit"><input type="submit" name="Advanced_Submit" id="submit" class="button button-primary" value="Save Changes"  /></p></form>

</div>