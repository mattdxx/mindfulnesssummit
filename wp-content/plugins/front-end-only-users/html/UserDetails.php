<?php 
	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
?>
<!-- The details of a specific user for editing, based on the user ID -->
	<?php $UserDetails = $wpdb->get_results($wpdb->prepare("SELECT * FROM $ewd_feup_user_fields_table_name WHERE User_ID ='%d'", $_GET['User_ID'])); ?>
	<?php $UserAdmin = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE User_ID ='%d'", $_GET['User_ID'])); ?>
	<?php $Levels = $wpdb->get_results("SELECT * FROM $ewd_feup_levels_table_name ORDER BY Level_Privilege ASC"); ?>
		
	<div class="OptionTab ActiveTab" id="EditProduct">
		<div class="form-wrap UserDetail">
			<a href="admin.php?page=EWD-FEUP-options&DisplayPage=Users" class="NoUnderline">&#171; <?php _e("Back", 'EWD_FEUP') ?></a>
			<h2><?php _e("Edit User", 'EWD_FEUP'); ?>: <?php echo($UserAdmin->Username); ?></h2>
			<?php $Fields = $wpdb->get_results("SELECT * FROM $ewd_feup_fields_table_name"); ?>
			<!-- Form to update a user -->
			<form id="addtag" method="post" action="admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_EditUser&DisplayPage=Users" class="validate" enctype="multipart/form-data">
				<input type="hidden" name="action" value="Edit_User" />
				<input type="hidden" name="User_ID" value="<?php echo $_GET['User_ID']; ?>" />
				<?php wp_nonce_field(); ?>
				<?php wp_referer_field(); ?>
				<select name='Level_ID'>
				<option value='0'>None (0)</option>
				<?php foreach ($Levels as $Level) {
						echo "<option value='" . $Level->Level_ID . "' ";
						if ($UserAdmin->Level_ID == $Level->Level_ID) {echo "selected=selected";}
						echo ">" . $Level->Level_Name . " (" . $Level->Level_Privilege . ")</option>";
				}?> 
				</select>
				<?php if ($Admin_Approval == "Yes") { ?>
						<label for='Admin Approved' id='ewd-feup-register-admin-approved-div' class='ewd-feup-field-label'><?php _e('Admin Approved', 'EWD_FEUP');?>: </label>
						<input type='radio' class='ewd-feup-text-input' name='Admin_Approved' value='Yes' <?php if ($UserAdmin->User_Admin_Approved == "Yes"){echo "checked";} ?>>Yes<br />
						<input type='radio' class='ewd-feup-text-input' name='Admin_Approved' value='No' <?php if ($UserAdmin->User_Admin_Approved == "No"){echo "checked";} ?>>No<br />
				<?php } ?>
				<?php foreach ($Fields as $Field) {
						$Value = "";
						foreach ($UserDetails as $UserField) { 
							if ($Field->Field_Name == $UserField->Field_Name) {$Value = $UserField->Field_Value;}
						}
				?>
						<div class="form-field form-required">
							<label for="<?php echo $Field->Field_Name; ?>"><?php echo $Field->Field_Name; ?></label>
							<?php if ($Field->Field_Type == "text" or $Field->Field_Type == "mediumint") {?><input name="<?php echo $Field->Field_Name; ?>" class='ewd-admin-regular-text' id="<?php echo $Field->Field_Name; ?>" type="text" value="<?php echo $Value;?>" size="60" />
							<?php } elseif ($Field->Field_Type == "date") {?>
										<input name='<?php echo $Field->Field_Name; ?>' id='ewd-feup-register-input-<?php echo $Field->Field_ID; ?>' class='ewd-feup-date-input pure-input-1-3' type='date' value='<?php echo $Value;?>' />
							<?php } elseif ($Field->Field_Type == "datetime") { ?>
										<input name='<?php echo $Field->Field_Name; ?>' id='ewd-feup-register-input-<?php echo $Field->Field_ID; ?>' class='ewd-feup-datetime-input pure-input-1-3' type='datetime-local' value='<?php echo $Value;?>' />
							<?php } elseif ($Field->Field_Type == "textarea") { ?>
										<textarea name="<?php echo $Field->Field_Name; ?>" class='ewd-admin-large-text' id="<?php echo $Field->Field_Name; ?>"><?php echo $Value ?></textarea>
							<?php } elseif ($Field->Field_Type == "file") {?>
										<?php echo __("Current file:", 'EWD_FEUP') . " " . substr($Value, 10);?>
										<input name='<?php echo $Field->Field_Name; ?>' id='ewd-feup-register-input-<?php echo $Field->Field_ID; ?>' class='ewd-feup-date-input pure-input-1-3' type='file' value='' />
							<?php } elseif ($Field->Field_Type == "select") { ?>
										<?php $Options = explode(",", $Field->Field_Options); ?>
										<select name="<?php echo $Field->Field_Name; ?>" id="<?php echo $Field->Field_Name; ?>">
										<?php foreach ($Options as $Option) { ?><option value='<?php echo $Option; ?>' <?php if ($Value == $Option) {echo "Selected";} ?>><?php echo $Option; ?></option><?php } ?>
										</select>
							<?php } elseif ($Field->Field_Type == "radio") { ?>
										<?php $Options = explode(",", $Field->Field_Options); ?>
										<?php foreach ($Options as $Option) { ?><input type='radio' name="<?php echo $Field->Field_Name; ?>" class='ewd-admin-small-input' value="<?php echo $Option; ?>" <?php if ($Value == $Option) {echo "checked";} ?>><?php echo $Option ?><br/><?php } ?>
							<?php } elseif ($Field->Field_Type == "checkbox") { ?>
										<?php $Options = explode(",", $Field->Field_Options); ?>
										<?php $User_Checkbox = explode(",", $Value); ?>
										<?php foreach ($Options as $Option) { ?><input type="checkbox" class='ewd-admin-small-input' name="<?php echo $Field->Field_Name; ?>[]" value="<?php echo $Option; ?>" <?php if (in_array($Option, $User_Checkbox)) {echo "checked";} ?>><?php echo $Option; ?></br><?php } ?>
							<?php } ?>
							<p><?php echo $Field->Field_Description; ?></p>
						</div>
				<?php } ?>

			<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Edit User ', 'EWD_FEUP') ?>"  /></p></form>
						
		</div>
	</div>	