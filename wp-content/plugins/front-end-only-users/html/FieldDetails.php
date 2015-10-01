
<!-- The details of a specific product for editing, based on the product ID -->
		
		<?php $Field = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_fields_table_name WHERE Field_ID ='%d'", $_GET['Field_ID'])); ?>
		
		<div class="OptionTab ActiveTab" id="EditField">
				<div class="form-wrap EditField">
						<a href="admin.php?page=EWD-FEUP-options&DisplayPage=Field" class="NoUnderline">&#171; <?php _e("Back", 'EWD_FEUP') ?></a>
						<h3>Edit <?php echo $Field->Field_Name;?></h3>
						<form id="addtag" method="post" action="admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_EditField&DisplayPage=Field" class="validate" enctype="multipart/form-data">
								<input type="hidden" name="action" value="Edit_Field" />
								<?php wp_nonce_field(); ?>
								<?php wp_referer_field(); ?>
								<input type='hidden' name='Field_ID' value='<?php echo $Field->Field_ID; ?>'>
								<div class="form-field form-required">
										<label for="Field_Name"><?php _e("Name", 'EWD_FEUP') ?></label>
										<input name="Field_Name" class='ewd-admin-regular-text' id="Field_Name" type="text" value="<?php echo $Field->Field_Name; ?>" size="60" />
										<p><?php _e("The name of the field your users will see.", 'EWD_FEUP') ?></p>
								</div>
								<div class="form-field">
										<label for="Field_Type"><?php _e("Type", 'EWD_FEUP') ?></label>
										<select name="Field_Type" id="Field_Type">
												<option value='text' <?php if ($Field->Field_Type == 'text') {echo "selected='selected'";} ?>>Short Text</option>
												<option value='mediumint' <?php if ($Field->Field_Type == 'mediumint') {echo "selected='selected'";} ?>>Integer</option>
												<option value='select' <?php if ($Field->Field_Type == 'select') {echo "selected='selected'";} ?>>Select Box</option>
												<option value='radio' <?php if ($Field->Field_Type == 'radio') {echo "selected='selected'";} ?>>Radio Button</option>
												<option value='checkbox' <?php if ($Field->Field_Type == 'checkbox') {echo "selected='selected'";} ?>>Checkbox</option>
												<option value='textarea' <?php if ($Field->Field_Type == 'textarea') {echo "selected='selected'";} ?>>Text Area</option>
												<option value='file' <?php if ($Field->Field_Type == 'file') {echo "selected='selected'";} ?>>File</option>
												<option value='date' <?php if ($Field->Field_Type == 'date') {echo "selected='selected'";} ?>>Date</option>
												<option value='datetime' <?php if ($Field->Field_Type == 'datetime') {echo "selected='selected'";} ?>>Date/Time</option>
												<option value='countries' <?php if ($Field->Field_Type == 'countries') {echo "selected='selected'";} ?>>Country Select</option>
										</select>
										<p><?php _e("The input method for the field and type of data that the field will hold.", 'EWD_FEUP') ?></p>
								</div>
								<div class="form-field">
										<label for="Field_Description"><?php _e("Description", 'EWD_FEUP') ?></label>
										<textarea name="Field_Description" class='ewd-admin-large-text' id="Field_Description" rows="2" cols="40"><?php echo $Field->Field_Description; ?></textarea>
										<p><?php _e("The description of the field, which the user will see as the instruction for the field.", 'EWD_FEUP') ?></p>
								</div>
								<div>
										<label for="Field_Options"><?php _e("Input Values", 'EWD_FEUP') ?></label>
										<input name="Field_Options" class='ewd-admin-regular-text' id="Field_Options" type="text" value="<?php echo $Field->Field_Options; ?>" size="60" />
										<p><?php _e("A comma-separated list of acceptable input values for this field. These values will be the options for select, checkbox, and radio inputs. All values will be accepted if left blank.", 'EWD_FEUP') ?></p>
								</div>
								<div>
										<label for="Field_Show_In_Admin"><?php _e("Show in Admin Table?", 'EWD_FEUP') ?></label>
										<input type='radio' name="Field_Show_In_Admin" value="Yes" <?php if ($Field->Field_Show_In_Admin == "Yes") {echo "checked";} ?>>Yes<br/>
										<input type='radio' name="Field_Show_In_Admin" value="No" <?php if ($Field->Field_Show_In_Admin == "No") {echo "checked";} ?>>No<br/>
										<p><?php _e("Should this field appear in the users table in the admin area?", 'EWD_FEUP') ?></p>
								</div>
								<div>
										<label for="Field_Show_In_Front_End"><?php _e("Show in User Profile", 'EWD_FEUP') ?></label>
										<input type='radio' name="Field_Show_In_Front_End" value="Yes" <?php if ($Field->Field_Show_In_Front_End == "Yes") {echo "checked";} ?>>Yes<br/>
										<input type='radio' name="Field_Show_In_Front_End" value="No" <?php if ($Field->Field_Show_In_Front_End == "No") {echo "checked";} ?>>No<br/>
										<p><?php _e("Should this field appear in users' profiles in the front-end?", 'EWD_FEUP') ?></p>
								</div>
								<div>
										<label for="Field_Required"><?php _e("Required?", 'EWD_FEUP') ?></label>
										<input type='radio' name="Field_Required" value="Yes" <?php if ($Field->Field_Required == "Yes") {echo "checked";} ?>>Yes<br/>
										<input type='radio' name="Field_Required" value="No" <?php if ($Field->Field_Required == "No") {echo "checked";} ?>>No<br/>
										<p><?php _e("Area users required to fill out this field?", 'EWD_FEUP') ?></p>
								</div>

								<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Edit Field', 'EWD_FEUP') ?>"  /></p></form>

				</div>
		</div>	