<?php if ($EWD_FEUP_Full_Version == "Yes") { ?>
<div id="col-right">
<div class="col-wrap">

<!-- Display a list of the products which have already been created -->
<?php wp_nonce_field(); ?>
<?php wp_referer_field(); ?>

<?php 
	if (isset($_GET['Page'])) {$Page = $_GET['Page'];}
	else {$Page = 1;}
			
	$Sql = "SELECT * FROM $ewd_feup_levels_table_name ORDER BY Level_Privilege DESC ";
	$Levels = $wpdb->get_results($Sql);
	$num_rows = $wpdb->num_rows; ?>

<form action="admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_MassDeleteLevels&DisplayPage=Levels" method="post">    
<div class="tablenav top">
	<div class="alignleft actions">
		<select name='action'>
  			<option value='-1' selected='selected'><?php _e("Bulk Actions", 'EWD_FEUP') ?></option>
			<option value='delete'><?php _e("Delete", 'EWD_FEUP') ?></option>
		</select>
		<input type="submit" name="" id="doaction" class="button-secondary action" value="<?php _e('Apply', 'EWD_FEUP') ?>"  />
	</div>
</div>

<!--<div class="nav-tabs-wrapper">
	<div id="Catalogues" class="nav-tabs">
		<span class="nav-tab nav-tab-active"><?php echo $CatalogueCatalogue_Name; ?></span>		
	</div>
</div>-->

					
<table class="wp-list-table widefat tags sorttable levels-list">
	<thead>
		<tr>
			<th scope='col' id='cb' class='manage-column column-cb check-column'  style=""><input type="checkbox" /></th>
			<th scope='col' id='level-name' class='manage-column column-name'  style=""><?php _e("Name", 'EWD_FEUP') ?></th>
			<th><?php _e("User Count", 'EWD_FEUP') ?></th>
			<th><?php _e("Privilege Level", 'EWD_FEUP') ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th scope='col' id='cb' class='manage-column column-cb check-column'  style=""><input type="checkbox" /></th>
			<th scope='col' id='level-name' class='manage-column column-name'  style=""><?php _e("Name", 'EWD_FEUP') ?></th>
			<th><?php _e("User Count", 'EWD_FEUP') ?></th>
			<th><?php _e("Privilege Level", 'EWD_FEUP') ?></th>
		</tr>
	</tfoot>
	<tbody id="the-list" class='list:tag'>
		<?php 
			foreach ($Levels as $Level) { 
				$user_count = $wpdb->get_var( "SELECT COUNT(*) FROM $ewd_feup_user_table_name WHERE Level_ID=" . $Level->Level_ID);
		?>
				<tr id="list-item-<?php echo $Level->Level_Item_ID; ?>" class="list-item">
					<th scope='row' class='check-column'>
						<input type='checkbox' name='Levels_Bulk[]' value='<?php echo $Level->Level_ID; ?>' />
					</th>
					<td class="level-name">
						<?php echo $Level->Level_Name; ?><br />
						<div class='row-actions'><span class='delete'>
						<a class='delete-tag' href='admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_DeleteLevel&DisplayPage=Levels&Level_ID=<?php echo $Level->Level_ID; ?>'><?php _e("Delete", 'EWD_FEUP'); ?></a>
		 				</span></div>
					</td>
					<td class="level-user-count"><?php echo print_r($user_count, true); ?></td>
					<td class="level-privilege-level"><?php echo $Level->Level_Privilege; ?></td>
				</tr>
			<?php } ?>
	</tbody>
</table>					

<div class="tablenav bottom">
	<div class="alignleft actions">
		<select name='action'>
  			<option value='-1' selected='selected'><?php _e("Bulk Actions", 'EWD_FEUP') ?></option>
			<option value='delete'><?php _e("Delete", 'EWD_FEUP') ?></option>
		</select>
		<input type="submit" name="" id="doaction" class="button-secondary action" value="<?php _e('Apply', 'EWD_FEUP') ?>"  />
	</div>
	<br class="clear" />
</div>
</form>

<br class="clear" />
</div>
</div> <!-- /col-right -->


<div id="col-left">
<div class="col-wrap">

<div class="form-wrap">
<h2><?php _e("Add New Level", 'EWD_FEUP') ?></h2>
<!-- Form to create a new level -->
<form id="addtag" method="post" action="admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_AddLevel&DisplayPage=Levels" class="validate" enctype="multipart/form-data">
<input type="hidden" name="action" value="Add_Level" />
<?php wp_nonce_field(); ?>
<?php wp_referer_field(); ?>
<div class="form-field form-required">
	<label for="Level_Name"><?php _e("Name", 'EWD_FEUP') ?></label>
	<input name="Level_Name" id="Level_Name" type="text" value="" size="60" />
	<p><?php _e("The name of the level you will assign to a group of users.", 'EWD_FEUP') ?></p>
</div>
<div class="form-field">
	<label for="Level_Privilege"><?php _e("Privilege Level", 'EWD_FEUP') ?></label>
	<select name="Level_Privilege" id="Level_Privilege">
		<?php $Insert = $num_rows+1; echo "<option value='" . $Insert . "'>" . $Insert . "</option>";
			for ($i=1; $i<=10; $i++) { 
				echo "<option value='" . $i . "'>" . $i . "</option>";
			} 
		?>
	</select>
	<p><?php _e("The privilege level for this user level. Privilege levels can affect who can see what content. Inserting a new level will increase the privilege level of all above levels.", 'EWD_FEUP') ?></p>
</div>

<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Add New Level', 'EWD_FEUP') ?>"  /></p></form>

</div>

<br class="clear" />
<!--</div>-->
</div><!-- /col-left -->

<?php } else { ?>
<div class="Info-Div">
	<h2><?php _e("Full Version Required!", 'EWD_FEUP') ?></h2>
	<div class="upcp-full-version-explanation">
		<?php _e("The full version of Front-End Only Users is required to use tags.", "EWD_FEUP");?><a href="http://www.etoilewebdesign.com/front-end-users-plugin/"><?php _e(" Please upgrade to unlock this page!", 'EWD_FEUP'); ?></a>
	</div>
<!--</div>-->
<?php } ?>