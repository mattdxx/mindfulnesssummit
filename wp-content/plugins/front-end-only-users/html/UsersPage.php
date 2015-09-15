<?php 
		$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
		$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");
?>
<div id="col-right">
<div class="col-wrap">

<!-- Display a list of the products which have already been created -->
<?php wp_nonce_field(); ?>
<?php wp_referer_field(); ?>

<?php 
	if (isset($_GET['Page'])) {$Page = $_GET['Page'];}
	else {$Page = 1;}
			
	$Fields = $wpdb->get_results("SELECT * FROM $ewd_feup_fields_table_name WHERE Field_Show_In_Admin='Yes'");
	$AllFields = $wpdb->get_results("SELECT * FROM $ewd_feup_fields_table_name"); 
			
	$Sql = "SELECT * FROM $ewd_feup_user_table_name ";
	if (isset($_POST['UserSearchValue']) and $_POST['UserSearchField'] == "Username") {
		$Sql .= "WHERE " . $_POST['UserSearchField'] . " ";
		if ($_POST['UserSearchOperator'] == "LIKE") {$Sql .= " LIKE '%". $_POST['UserSearchValue'] . "%' ";}
		else {$Sql .= "='" . $_POST['UserSearchValue'] . "' ";}
	}
	elseif (isset($_POST['UserSearchValue'])) {
		$Sql .= "INNER JOIN $ewd_feup_user_fields_table_name ON $ewd_feup_user_table_name.User_ID=$ewd_feup_user_fields_table_name.User_ID";
		$Sql .= " WHERE Field_ID=" . $_POST['UserSearchField'] . " AND Field_Value ";
		if ($_POST['UserSearchOperator'] == "LIKE") {$Sql .= " LIKE '%". $_POST['UserSearchValue'] . "%' ";}
		else {$Sql .= "='" . $_POST['UserSearchValue'] . "' ";}
	}
	if (isset($_GET['OrderBy']) and $_GET['DisplayPage'] == "Users") {$Sql .= "ORDER BY " . $_GET['OrderBy'] . " " . $_GET['Order'] . " ";}
	else {$Sql .= "ORDER BY User_Date_Created ";}
	$Sql .= "LIMIT " . ($Page - 1)*20 . ",20";
	$myrows = $wpdb->get_results($Sql);
	$RowCount = $wpdb->get_results("SELECT User_ID FROM $ewd_feup_user_table_name ");
	$Number_of_Pages = ceil($wpdb->num_rows/20);
	$Current_Page_With_Order_By = "admin.php?page=EWD-FEUP-options&DisplayPage=Users";
	if (isset($_GET['OrderBy'])) {$Current_Page_With_Order_By .= "&OrderBy=" .$_GET['OrderBy'] . "&Order=" . $_GET['Order'];}
	$UserCount = $wpdb->num_rows;
?>

<form action="admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_MassUserAction&DisplayPage=Users" method="post"> 
<p class="search-box">
	<label class="screen-reader-text" for="post-search-input">Search Users:</label>
	<select name='UserSearchField' class='ewd-admin-select-search'>
		<option value='Username'>Username</option>
		<?php 
			foreach ($AllFields as $Field) {
				echo "<option value='" . $Field->Field_ID . "'>" . $Field->Field_Name . "</option>";
			}
		?>
	</select>
	<select name='UserSearchOperator' class='ewd-admin-select-search'>
		<option value='LIKE'>Like</option>
		<option value='EQUALS'>Equals</option>
	</select>
	<input type="search" id="post-search-input" name="UserSearchValue" value="">
	<input type="submit" name="" id="search-submit" class="button" value="Search Users">
</p>   
<div class="tablenav top">
	<div class="alignleft actions">
		<select name='action'>
  			<option value='-1' selected='selected'><?php _e("Bulk Actions", 'EWD_FEUP') ?></option>
			<option value='delete'><?php _e("Delete", 'EWD_FEUP') ?></option>
			<option value='approve'><?php _e("Approve", 'EWD_FEUP') ?></option>
			<option value='0'>Level: None (0)</option>
			<?php 
				$Levels = $wpdb->get_results("SELECT * FROM $ewd_feup_levels_table_name");
				if (is_array($Levels)) {
					foreach ($Levels as $Level) {
						echo "<option value='" . $Level->Level_ID . "'>Level: " . $Level->Level_Name . " (" . $Level->Level_Privilege . ")</option>"; 
					}
				}
			?>
		</select>
		<input type="submit" name="" id="doaction" class="button-secondary action" value="<?php _e('Apply', 'EWD_FEUP') ?>"  />
		<a class='feup-confirm-all-users button-secondary action' href='admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_DeleteAllUsers&DisplayPage=Users'><?php _e("Delete All Users", 'EWD_FEUP'); ?></a>
	</div>
	<div class='tablenav-pages <?php if ($Number_of_Pages == 1) {echo "one-page";} ?>'>
		<span class="displaying-num"><?php echo $UserCount; ?> <?php _e("users", 'EWD_FEUP') ?> <?php if ($EWD_FEUP_Full_Version != "Yes") {echo "out of 100";}?></span>
		<span class='pagination-links'>
			<a class='first-page <?php if ($Page == 1) {echo "disabled";} ?>' title='Go to the first page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=1'>&laquo;</a>
			<a class='prev-page <?php if ($Page <= 1) {echo "disabled";} ?>' title='Go to the previous page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=<?php echo $Page-1;?>'>&lsaquo;</a>
			<span class="paging-input"><?php echo $Page; ?> <?php _e("of", 'EWD_FEUP') ?> <span class='total-pages'><?php echo $Number_of_Pages; ?></span></span>
			<a class='next-page <?php if ($Page >= $Number_of_Pages) {echo "disabled";} ?>' title='Go to the next page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=<?php echo $Page+1;?>'>&rsaquo;</a>
			<a class='last-page <?php if ($Page == $Number_of_Pages) {echo "disabled";} ?>' title='Go to the last page' href='<?php echo $Current_Page_With_Order_By . "&Page=" . $Number_of_Pages; ?>'>&raquo;</a>
		</span>
	</div>
</div>

<table class="wp-list-table widefat tags sorttable fields-list ui-sortable" cellspacing="0">
	<thead>
		<tr>
			<th scope='col' id='cb' class='manage-column column-cb check-column'  style="">
				<input type="checkbox" />
			</th>
			<th scope='col' class='manage-column column-cb check-column'  style="">
				<span>Username</span>
			</th>
			<?php if ($Admin_Approval == "Yes") { ?>
				<?php if ($_GET['OrderBy'] == "User_Admin_Approved" and $_GET['Order'] == "ASC") {$Order = "DESC";}
				  	  else {$Order = "ASC";} ?>
				<th scope='col' class='manage-column column-cb check-column'  style="">
					<a href="admin.php?page=EWD-FEUP-options&DisplayPage=Users&OrderBy=User_Admin_Approved&Order=<?php echo $Order; ?>">
					<span>Admin Approved</span>
					<span class="sorting-indicator"></span>
					</a>
				</th>
			<?php } ?>
			<?php foreach ($Fields as $Field) { ?>
				<?php if ($_GET['OrderBy'] == $Field->Field_Name and $_GET['Order'] == "ASC") {$Order = "DESC";}
					  else {$Order = "ASC";} ?>
				<th scope='col' class='manage-column column-cb check-column'  style="">
					<a href="admin.php?page=EWD-FEUP-options&DisplayPage=Users&OrderBy=<?php echo $Field->Field_Name; ?>&Order=<?php echo $Order; ?>">
						<span><?php echo $Field->Field_Name; ?></span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
			<?php } ?>
			<th scope='col' class='manage-column column-cb check-column'  style="">
				<?php if ($_GET['OrderBy'] == "User_Last_Login" and $_GET['Order'] == "ASC") {$Order = "DESC";}
					  else {$Order = "ASC";} ?>
				<a href="admin.php?page=EWD-FEUP-options&DisplayPage=Users&OrderBy=User_Last_Login&Order=<?php echo $Order; ?>">
				<span>Last Login</span>
				<span class="sorting-indicator"></span>
			</th>
			<th scope='col' class='manage-column column-cb check-column'  style="">
				<?php if ($_GET['OrderBy'] == "User_Date_Created" and $_GET['Order'] == "ASC") {$Order = "DESC";}
					  else {$Order = "ASC";} ?>
				<a href="admin.php?page=EWD-FEUP-options&DisplayPage=Users&OrderBy=User_Date_Created&Order=<?php echo $Order; ?>">
				<span>Joined Date</span>
				<span class="sorting-indicator"></span>
			</th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th scope='col' id='cb' class='manage-column column-cb check-column'  style="">
				<input type="checkbox" />
			</th>
			<th scope='col' class='manage-column column-cb check-column'  style="">
				<span>Username</span>
			</th>
			<?php if ($Admin_Approval == "Yes") { ?>
				<?php if ($_GET['OrderBy'] == "User_Admin_Approved" and $_GET['Order'] == "ASC") {$Order = "DESC";}
					  else {$Order = "ASC";} ?>
				<th scope='col' class='manage-column column-cb check-column'  style="">
					<a href="admin.php?page=EWD-FEUP-options&DisplayPage=Users&OrderBy=User_Admin_Approved&Order=<?php echo $Order; ?>">
						<span>Admin Approved</span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
			<?php } ?>
			<?php foreach ($Fields as $Field) { ?>
				<?php if ($_GET['OrderBy'] == $Field->Field_Name and $_GET['Order'] == "ASC") {$Order = "DESC";}
					  else {$Order = "ASC";} ?>
				<th scope='col' class='manage-column column-cb check-column'  style="">
					<a href="admin.php?page=EWD-FEUP-options&DisplayPage=Users&OrderBy=<?php echo $Field->Field_Name; ?>&Order=<?php echo $Order; ?>">
						<span><?php echo $Field->Field_Name; ?></span>
						<span class="sorting-indicator"></span>
					</a>
				</th>
			<?php } ?>
			<th scope='col' class='manage-column column-cb check-column'  style="">
				<?php if ($_GET['OrderBy'] == "User_Last_Login" and $_GET['Order'] == "ASC") {$Order = "DESC";}
					  else {$Order = "ASC";} ?>
				<a href="admin.php?page=EWD-FEUP-options&DisplayPage=Users&OrderBy=User_Last_Login&Order=<?php echo $Order; ?>">
				<span>Last Login</span>
				<span class="sorting-indicator"></span>
			</th>
			<th scope='col' class='manage-column column-cb check-column'  style="">
				<?php if ($_GET['OrderBy'] == "User_Date_Created" and $_GET['Order'] == "ASC") {$Order = "DESC";}
					  else {$Order = "ASC";} ?>
				<a href="admin.php?page=EWD-FEUP-options&DisplayPage=Users&OrderBy=User_Date_Created&Order=<?php echo $Order; ?>">
				<span>Joined Date</span>
				<span class="sorting-indicator"></span>
			</th>
		</tr>
	</tfoot>

	<tbody id="the-list" class='list:tag'>
		
	<?php
		if ($myrows) { 
	  		foreach ($myrows as $User) {
				$FieldCount = 0;
				echo "<tr id='User" . $User->User_ID ."'>";
				echo "<th scope='row' class='check-column'>";
				echo "<input type='checkbox' name='Users_Bulk[]' value='" . $User->User_ID ."' />";
				echo "</th>";
				$Username = $wpdb->get_var("SELECT Username FROM $ewd_feup_user_table_name WHERE User_ID='" . $User->User_ID . "'");
				echo "<td class='username column-name'>" . $Username . "</td>";
				if ($Admin_Approval == "Yes") {
					echo "<td class='name column-name'>";
					echo $User->User_Admin_Approved;
					echo "</td>";
				}
				foreach ($Fields as $Field) { 
					$User_Info = $wpdb->get_row($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE User_ID='%d' and Field_Name='%s'", $User->User_ID, $Field->Field_Name));
					echo "<td class='name column-name'>";
					if ($FieldCount == 0) {
						echo "<strong>";
						echo "<a class='row-title' href='admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_User_Details&Selected=User&User_ID=" . $User->User_ID ."' title='Edit User'>";
					}
					echo substr($User_Info->Field_Value, 0, 60);
					if (strlen($User_Info->Field_Value) > 60) {echo "...";}
					if ($FieldCount == 0) {
						echo "</a></strong>";
						echo "<br />";
						echo "<div class='row-actions'>";
						echo "<span class='delete'>";
						echo "<a class='delete-tag feup-confirm-one-user' href='admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_DeleteUser&DisplayPage=Users&User_ID=" . $User->User_ID ."'>" . __("Delete", 'EWD_FEUP') . "</a>";
		 				echo "</span>";
						echo "</div>";
						echo "<div class='hidden' id='inline_" . $User->User_ID ."'>";
					}												
					echo "</td>";
					$FieldCount++;
				}
				echo "<td class='name column-name'>" .  $User->User_Last_Login . "</td>";
				echo "<td class='name column-name'>" .  $User->User_Date_Created . "</td>";
				echo "</tr>";
			}
		}
	?>

	</tbody>
</table>

<div class="tablenav bottom">
	<?php /*<div class="alignleft actions">
		<select name='action'>
  			<option value='-1' selected='selected'><?php _e("Bulk Actions", 'EWD_FEUP') ?></option>
			<option value='delete'><?php _e("Delete", 'EWD_FEUP') ?></option>
		</select>
		<input type="submit" name="" id="doaction" class="button-secondary action" value="<?php _e('Apply', 'EWD_FEUP') ?>"  />
	</div>*/ ?>
	<div class='tablenav-pages <?php if ($Number_of_Pages == 1) {echo "one-page";} ?>'>
		<span class="displaying-num"><?php echo $UserCount; ?> <?php _e("items", 'EWD_FEUP') ?></span>
		<span class='pagination-links'>
			<a class='first-page <?php if ($Page == 1) {echo "disabled";} ?>' title='Go to the first page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=1'>&laquo;</a>
			<a class='prev-page <?php if ($Page <= 1) {echo "disabled";} ?>' title='Go to the previous page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=<?php echo $Page-1;?>'>&lsaquo;</a>
			<span class="paging-input"><?php echo $Page; ?> <?php _e("of", 'EWD_FEUP') ?> <span class='total-pages'><?php echo $Number_of_Pages; ?></span></span>
			<a class='next-page <?php if ($Page >= $Number_of_Pages) {echo "disabled";} ?>' title='Go to the next page' href='<?php echo $Current_Page_With_Order_By; ?>&Page=<?php echo $Page+1;?>'>&rsaquo;</a>
			<a class='last-page <?php if ($Page == $Number_of_Pages) {echo "disabled";} ?>' title='Go to the last page' href='<?php echo $Current_Page_With_Order_By . "&Page=" . $Number_of_Pages; ?>'>&raquo;</a>
		</span>
	</div>
	<br class="clear" />
</div>
</form>

<br class="clear" />
</div>
</div> <!-- /col-right -->

<!-- Form to upload a list of new products from a spreadsheet -->
<div id="col-left">
<div class="col-wrap">

<div class="form-wrap">
<h2><?php _e("Add New User", 'EWD_FEUP') ?></h2>
<?php 
$Fields = $AllFields;
$Levels = $wpdb->get_results("SELECT * FROM $ewd_feup_levels_table_name ORDER BY Level_Privilege ASC");
?>
<!-- Form to create a new product -->
<form id="addtag" method="post" action="admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_AddUser&DisplayPage=Users" class="validate" enctype="multipart/form-data">
<input type="hidden" name="action" value="Add_User" />
<?php wp_nonce_field(); ?>
<?php wp_referer_field(); ?>
<?php if($Username_Is_Email == "Yes") { ?>
<label for='Username' id='ewd-feup-register-username-div' class='ewd-feup-field-label'><?php _e('Email', 'EWD_FEUP');?>: </label>
<input type='email' class='ewd-feup-text-input' name='Username'>
<?php } else {?>
<label for='Username' id='ewd-feup-register-username-div' class='ewd-feup-field-label'><?php _e('Username', 'EWD_FEUP');?>: </label>
<input type='text' class='ewd-feup-text-input' name='Username'>
<?php } ?>
<label for='Password' id='ewd-feup-register-password-div' class='ewd-feup-field-label'><?php _e('Password', 'EWD_FEUP')?>: </label>
<input type='password' class='ewd-feup-text-input' name='User_Password'>
<label for='Repeat Password' id='ewd-feup-register-password-confirm-div' class='ewd-feup-field-label'><?php _e('Repeat Password', 'EWD_FEUP');?>: </label>
<input type='password' class='ewd-feup-text-input' name='Confirm_User_Password'>
<label for='Level ID' id='ewd-feup-register-user-level-div' class='ewd-feup-field-label'><?php _e('User Level', 'EWD_FEUP');?>: </label>
<select name='Level_ID'>
<option value='0'>None (0)</option>
<?php foreach ($Levels as $Level) {
		echo "<option value='" . $Level->Level_ID . "'>" . $Level->Level_Name . " (" . $Level->Level_Privilege . ")</option>";
}?> 
</select>
<?php if ($Admin_Approval == "Yes") { ?>
	<label for='Admin Approved' id='ewd-feup-register-admin-approved-div' class='ewd-feup-field-label'><?php _e('Admin Approved', 'EWD_FEUP');?>: </label>
	<input type='radio' class='ewd-feup-text-input' name='Admin_Approved' value='Yes'>Yes<br />
	<input type='radio' class='ewd-feup-text-input' name='Admin_Approved' value='No'>No<br />
<?php } ?>
<?php foreach ($Fields as $Field) { ?>
<div class="form-field form-required">
	<label for="<?php echo $Field->Field_Name; ?>"><?php echo $Field->Field_Name; ?></label>
	<?php if ($Field->Field_Type == "text" or $Field->Field_Type == "mediumint") {?><input name="<?php echo $Field->Field_Name; ?>" id="<?php echo $Field->Field_Name; ?>" type="text" value="" size="60" />
	<?php } elseif ($Field->Field_Type == "date") {?>
			<input name='<?php echo $Field->Field_Name; ?>' id='ewd-feup-register-input-<?php echo $Field->Field_ID; ?>' class='ewd-feup-date-input pure-input-1-3' type='date' value='' />
	<?php } elseif ($Field->Field_Type == "datetime") { ?>
			<input name='<?php echo $Field->Field_Name; ?>' id='ewd-feup-register-input-<?php echo $Field->Field_ID; ?>' class='ewd-feup-datetime-input pure-input-1-3' type='datetime-local' value='' />
	<?php } elseif ($Field->Field_Type == "textarea") { ?>
			<textarea name="<?php echo $Field->Field_Name; ?>" id="<?php echo $Field->Field_Name; ?>"></textarea>
	<?php } elseif ($Field->Field_Type == "file") {?>
			<input name='<?php echo $Field->Field_Name; ?>' id='ewd-feup-register-input-<?php echo $Field->Field_ID; ?>' class='ewd-feup-file-input pure-input-1-3' type='file' value='' />
	<?php } elseif ($Field->Field_Type == "select") { ?>
			<?php $Options = explode(",", $Field->Field_Options); ?>
			<select name="<?php echo $Field->Field_Name; ?>" id="<?php echo $Field->Field_Name; ?>">
			<?php foreach ($Options as $Option) { ?><option value='<?php echo $Option; ?>'><?php echo $Option; ?></option><?php } ?>
			</select>
	<?php } elseif ($Field->Field_Type == "radio") { ?>
			<?php $Options = explode(",", $Field->Field_Options); ?>
			<?php foreach ($Options as $Option) { ?><input type='radio' class='ewd-admin-small-input' name="<?php echo $Field->Field_Name; ?>" value="<?php echo $Option; ?>"><?php echo $Option ?><br/><?php } ?>
	<?php } elseif ($Field->Field_Type == "checkbox") { ?>
			<?php $Options = explode(",", $Field->Field_Options); ?>
			<?php foreach ($Options as $Option) { ?><input type="checkbox" class='ewd-admin-small-input' name="<?php echo $Field->Field_Name; ?>[]" value="<?php echo $Option; ?>"><?php echo $Option; ?></br><?php } ?>
	<?php } ?>
	<p><?php echo $Field->Field_Description; ?></p>
</div>
<?php } ?>

<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Add New User', 'EWD_FEUP') ?>"  /></p></form>

</div>
<br class="clear" />

<h3><?php _e("Add Users from Spreadsheet", 'EWD_FEUP') ?></h3>
<?php if ($EWD_FEUP_Full_Version == "Yes") { ?>
<div class="wrap">

<form id="addtag" method="post" action="admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_AddUserSpreadsheet&DisplayPage=Users" class="validate" enctype="multipart/form-data">
<?php wp_nonce_field(); ?>
<div class="form-field form-required">
		<label for="Users_Spreadsheet"><?php _e("Spreadhseet Containing Users", 'EWD_FEUP') ?></label>
		<input name="Users_Spreadsheet" id="Users_Spreadsheet" type="file" value=""/>
		<p><?php _e("The spreadsheet containing all of the users you wish to add. Make sure that the column title names are the same as the field names for users (ex: Username, Email, First Name, etc.).", 'EWD_FEUP') ?></p>
</div>
<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Add New Users', 'EWD_FEUP') ?>"  /></p>
</form>
</div>

<?php } else { ?>
<div class="Info-Div">
	<h2><?php _e("Full Version Required!", 'EWD_FEUP') ?></h2>
	<div class="upcp-full-version-explanation">
		<?php _e("The full version of Front-End Only Users is required to use tags.", "EWD_FEUP");?><a href="http://www.etoilewebdesign.com/front-end-users-plugin/"><?php _e(" Please upgrade to unlock this page!", 'EWD_FEUP'); ?></a>
	</div>
</div>
<?php } ?>

<h3><?php _e("Export Users to Spreadsheet", 'EWD_FEUP') ?></h3>

<?php if ($EWD_FEUP_Full_Version == "Yes") { ?>
<div class="wrap">

<form method="post" action="admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_ExportToExcel">
<p><?php _e("Downloads all users currently in the database to Excel", 'EWD_FEUP') ?></p>
<p class="submit"><input type="submit" name="Export_Submit" id="submit" class="button button-primary" value="Export to Excel"  /></p>
</form>
</div>
<?php } else { ?>
<div class="Info-Div">
	<h2><?php _e("Full Version Required!", 'EWD_FEUP') ?></h2>
	<div class="upcp-full-version-explanation">
		<?php _e("The full version of Front-End Only Users is required to use tags.", "EWD_FEUP");?><a href="http://www.etoilewebdesign.com/front-end-users-plugin/"><?php _e(" Please upgrade to unlock this page!", 'EWD_FEUP'); ?></a>
	</div>
</div>
<?php } ?>

</div>
</div>		