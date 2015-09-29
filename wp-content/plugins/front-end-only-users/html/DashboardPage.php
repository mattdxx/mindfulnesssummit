<?php 
	$Admin_Approval = get_option("EWD_FEUP_Admin_Approval");
?>

<!-- Upgrade to pro link box -->
<?php if ($EWD_FEUP_Full_Version != "Yes") { ?>
<div id="side-sortables" class="metabox-holder ">
<div id="feup_pro" class="postbox " >
	<div class="handlediv" title="Click to toggle"></div><h3 class='hndle'><span><?php _e("Full Version", 'EWD_FEUP') ?></span></h3>
	<div class="inside">
		<ul><li><a href="http://www.etoilewebdesign.com/front-end-users-plugin/"><?php _e("Upgrade to the full version ", "EWD_FEUP"); ?></a><?php _e("to take advantage of all the available features of the Front-End Users for Wordpress!", 'EWD_FEUP'); ?></li>
		<div class="full-version-form-div">
			<form action="admin.php?page=EWD-FEUP-options" method="post">
				<div class="form-field form-required">
					<label for="Catalogue_Name"><?php _e("Product Key", 'EWD_FEUP') ?></label>
					<input name="Key" type="text" value="" size="40" />
				</div>							
				<input type="submit" name="Upgrade_To_Full" value="<?php _e('Upgrade', 'EWD_FEUP') ?>">
			</form>
		</div>
	</div>
</div>
</div>
<?php } ?>

<?php /* echo get_option('plugin_error');*/ ?>
<?php if (get_option("EWD_FEUP_Update_Flag") == "Yes" or get_option("EWD_FEUP_Install_Flag") == "Yes") {?>
		<div id="side-sortables" class="metabox-holder ">
			<div id="feup-upgrade" class="postbox " >
				<div class="handlediv" title="Click to toggle"></div>
				<h3 class='hndle'><span><?php _e("Thank You!", 'EWD_FEUP') ?></span></h3>
				<div class="inside">
					<?php /* if (get_option("EWD_FEUP_Install_Flag") == "Yes") { ?><ul><li><?php _e("Thanks for installing the Ultimate Product Catalogue Plugin.", "EWD_FEUP"); ?><br> <a href='https://www.youtube.com/channel/UCZPuaoetCJB1vZOmpnMxJNw'><?php _e("Subscribe to our YouTube channel ", "EWD_FEUP"); ?></a> <?php _e("for tutorial videos on this and our other plugins!", "EWD_FEUP");?> </li></ul>
					<?php } else { ?><ul><li><?php _e("Thanks for upgrading to version 2.4.21!", "EWD_FEUP"); ?><br> <a href='https://www.youtube.com/channel/UCZPuaoetCJB1vZOmpnMxJNw'><?php _e("Subscribe to our YouTube channel ", "EWD_FEUP"); ?></a> <?php _e("for tutorial videos on this and our other plugins!", "EWD_FEUP");?> </li></ul><?php } */ ?>
					
					<?php  if (get_option("EWD_FEUP_Install_Flag") == "Yes") { ?><ul><li><?php _e("Thanks for installing Front-End Only Users.", "EWD_FEUP"); ?><br> <a href='http://www.facebook.com/EtoileWebDesign'><?php _e("Follow us on Facebook", "EWD_FEUP"); ?></a> <?php _e("to suggest new features or hear about upcoming ones!", "EWD_FEUP");?> </li></ul>
					<?php } else { ?><ul><li><?php _e("Thanks for upgrading to version 2.3.1!", "EWD_FEUP"); ?><br> <a href='http://www.facebook.com/EtoileWebDesign'><?php _e("Follow us on Facebook", "EWD_FEUP"); ?></a> <?php _e("to suggest new features or hear about upcoming ones!", "EWD_FEUP");?> </li></ul><?php }  ?>
					
					<?php /* if (get_option("EWD_FEUP_Install_Flag") == "Yes") { ?><ul><li><?php _e("Thanks for installing Front-End Only Users.", "EWD_FEUP"); ?><br> <a href='http://www.facebook.com/EtoileWebDesign'><?php _e("Follow us on Facebook", "EWD_FEUP"); ?></a> <?php _e("to suggest new features or hear about upcoming ones!", "EWD_FEUP");?>  </li></ul>
					<?php } else { ?><ul><li><?php _e("Thanks for upgrading to version 2.0.0!", "EWD_FEUP"); ?><br> <a href='http://wordpress.org/support/view/plugin-reviews/ultimate-product-catalogue'><?php _e("Please rate our plugin", "EWD_FEUP"); ?></a> <?php _e("if you find the Ultimate Product Catalogue Plugin useful!", "EWD_FEUP");?> </li></ul><?php } */ ?>
					
					<?php /* if (get_option("EWD_FEUP_Install_Flag") == "Yes") { ?><ul><li><?php _e("Thanks for installing the Ultimate Product Catalogue Plugin.", "EWD_FEUP"); ?><br> <a href='http://www.facebook.com/EtoileWebDesign'><?php _e("Follow us on Facebook", "EWD_FEUP"); ?></a> <?php _e("to suggest new features or hear about upcoming ones!", "EWD_FEUP");?>  </li></ul>
					<?php } else { ?><ul><li><?php _e("Thanks for upgrading to version 3.0.9!", "EWD_FEUP"); ?><br> <a href='http://wordpress.org/plugins/order-tracking/'><?php _e("Try out order tracking plugin ", "EWD_FEUP"); ?></a> <?php _e("if you ship orders and find the Ultimate Product Catalogue Plugin useful!", "EWD_FEUP");?> </li></ul><?php } */ ?>
					<?php /* if (get_option("EWD_FEUP_Install_Flag") == "Yes") { ?><ul><li><?php _e("Thanks for installing the Ultimate Product Catalogue Plugin.", "EWD_FEUP"); ?><br> <a href='http://www.facebook.com/EtoileWebDesign'><?php _e("Follow us on Facebook", "EWD_FEUP"); ?></a> <?php _e("to suggest new features or hear about upcoming ones!", "EWD_FEUP");?>  </li></ul>
					<?php } else { ?><ul><li><?php _e("Thanks for upgrading to version 2.3.9!", "EWD_FEUP"); ?><br> <a href='http://wordpress.org/support/topic/error-hunt'><?php _e("Please let us know about any small display/functionality errors. ", "EWD_FEUP"); ?></a> <?php _e("We've noticed a couple, and would like to eliminate as many as possible.", "EWD_FEUP");?> </li></ul><?php } */ ?>
					
					<?php /* if (get_option("EWD_FEUP_Install_Flag") == "Yes") { ?><ul><li><?php _e("Thanks for installing the Ultimate Product Catalogue Plugin.", "EWD_FEUP"); ?><br> <a href='https://www.youtube.com/channel/UCZPuaoetCJB1vZOmpnMxJNw'><?php _e("Check out our YouTube channel ", "EWD_FEUP"); ?></a> <?php _e("for tutorial videos on this and our other plugins!", "EWD_FEUP");?> </li></ul>
					<?php } elseif ($Full_Version == "Yes") { ?><ul><li><?php _e("Thanks for upgrading to version 2.6!", "EWD_FEUP"); ?><br> <a href='http://www.facebook.com/EtoileWebDesign'><?php _e("Follow us on Facebook", "EWD_FEUP"); ?></a> <?php _e("to suggest new features or hear about upcoming ones!", "EWD_FEUP");?> </li></ul>
					<?php } else { ?><ul><li><?php _e("Thanks for upgrading to version 3.0!", "EWD_FEUP"); ?><br> <?php _e("Love the plugin but don't need the premium version? Help us speed up product support and development by donating. Thanks for using the plugin!", "EWD_FEUP");?>
										<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
										<input type="hidden" name="cmd" value="_s-xclick">
										<input type="hidden" name="hosted_button_id" value="AQLMJFJ62GEFJ">
										<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
										<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
										</form>
										</li></ul>
					<?php } */ ?>
				</div>
			</div>
		</div>
<?php 
update_option('EWD_FEUP_Update_Flag', "No");
update_option('EWD_FEUP_Install_Flag', "No");  
} ?>

<div id="side-sortables" class="metabox-holder ">
<div id="ewd-feup-support" class="postbox " >
	<div class="handlediv" title="Click to toggle"></div><h3 class='hndle'><span><?php _e("Support Options:", 'EWD_FEUP') ?></span></h3>
	<div class="inside">
		<ul>
			<li><a href='https://www.youtube.com/channel/UCZPuaoetCJB1vZOmpnMxJNw/feed'>Our YouTube channel with getting started and plugin feature tutorials.</a></li>
			<li><a href='http://www.etoilewebdesign.com/front-end-users-faq/'>Plugin in-depth FAQ page.</a></li>
			<li><a href='https://wordpress.org/support/plugin/front-end-only-users'>WordPress support forum.</a></li>
			<li><a href='http://www.etoilewebdesign.com/wp-content/uploads/2015/04/FrontEndOnlyUserPluginDocument.docx.pdf'>PDF of the plugin documentation.</a></li>
		</ul>
	</div>
</div>
</div>

<!-- List of the catalogues which have already been created -->
<div id="col-right">
<div class="col-wrap">
<?php echo get_option('plugin_error'); ?>
<?php wp_nonce_field(); ?>
<?php wp_referer_field(); ?>

<?php if ($EWD_FEUP_Full_Version == "Yes") { ?>

<?php 			
	$Sql = "SELECT * FROM $ewd_feup_user_table_name ORDER BY User_Last_Login DESC";
	$myrows = $wpdb->get_results($Sql);
?>

<div class="tablenav top">
	<div class="alignleft actions">
		Recent User Activity
	</div>
</div>

<table class="wp-list-table widefat fixed tags " cellspacing="0">
	<thead>
		<tr>
			<th scope='col' id='db-username-top' class='manage-column column-username'  style="">
				Username
			</th>
			<th scope='col' id='db-last-login-top' class='manage-column column-last-login'  style="">
				Last Login
			</th>
			<th scope='col' id='db-description-top' class='manage-column column-total-logins'  style="">
				Total Logins
			</th>
			<th scope='col' id='db-required-top' class='manage-column column-joined-date'  style="">
				Joined Date
			</th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th scope='col' id='db-username-bottom' class='manage-column column-username'  style="">
				Username
			</th>
			<th scope='col' id='db-last-login-bottom' class='manage-column column-last-login'  style="">
				Last Login
			</th>
			<th scope='col' id='db-description-bottom' class='manage-column column-total-logins'  style="">
				Total Logins
			</th>
			<th scope='col' id='db-required-bottom' class='manage-column column-joined-date'  style="">
				Joined Date
			</th>
		</tr>
	</tfoot>

	<tbody id="the-list" class='list:tag'>
		<?php
			if ($myrows) { 
	  			foreach ($myrows as $User) {
					echo "<tr id='User-" . $User->User_ID ."'>";
					echo "<td class='name column-name'>";
					echo "<strong>";
					echo "<a class='row-title' href='admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_User_Details&Selected=User&User_ID=" . $User->User_ID ."' title='Edit " . $User->Username . "</a></strong>";
					echo "<br />";
					echo "<div class='username'>" . $User->Username . "</div>";
					echo "</td>";
					echo "<td class='description column-last-login'>" . $User->User_Last_Login . "</td>";
					echo "<td class='description column-description'>" . $User->User_Total_Logins . "</td>";
					echo "<td class='users column-required'>" . $User->User_Date_Created . "</td>";
					echo "</tr>";
				}
			}
		?>
	</tbody>
</table>

<?php } else { ?>

<div id="side-sortables" class="metabox-holder ">
<div id="ewd-feup-pro-features" class="postbox " >
	<div class="handlediv" title="Click to toggle"></div><h3 class='hndle'><span><?php _e("What you get by upgrading:", 'EWD_FEUP') ?></span></h3>
	<div class="inside">
		<ul>
			<li>Access to the "Levels" tab, so you can decide which users can access what content.</li>
			<li>Access to the "Statistics" tab, so you can see recent user activity, and how users interact with your site.</li>
			<li>Ability to import/export users, so that your user information can easily be used by other software such as MailChimp.</li>
			<li>Access to e-mail support. </li>
		</ul>
	</div>
</div>
</div>

<?php } ?>

<br class="clear" />
</div>
</div>

<!-- A list of the products in the catalogue -->
<div id="col-left">
<div class="col-wrap">
	<div id='ewd-feup-one-click-install'>
		<a class='ewd-feup-one-click-install-div-load button button-primary'>Open One-Click Installer</a>
	</div>
	<div id='ewd-feup-one-click-blur'></div>
	<div id='ewd-feup-one-click-install-div' class='ewd-feup-oci-no-show'><?php include EWD_FEUP_CD_PLUGIN_PATH . "html/OneClickInstall.php"; ?></div>
	<div id="dashboard-total-users" class="dashboard-users-total">
		<?php if ($Admin_Approval == "Yes") { ?>
			Unapproved Users:
			<?php $TotalUsers = $wpdb->get_results("SELECT User_ID FROM $ewd_feup_user_table_name WHERE User_Admin_Approved!='Yes'");
				echo $wpdb->num_rows;  ?>
		<?php } else { ?>
			Current Users:
			<?php $TotalUsers = $wpdb->get_results("SELECT User_ID FROM $ewd_feup_user_table_name");
				echo $wpdb->num_rows;  ?>
		<?php } ?>
	</div>
	<div id="dashboard-products-column" class="metabox-holder">	
	<div id="side-sortables" class="meta-box-sortables">

	<div id="add-page" class="postbox " >
	<?php if ($Admin_Approval == "Yes") { ?>
		<div class="handlediv" title="Click to toggle"><br /></div><h3 class='hndle'><span><?php _e("Unapproved Users", 'EWD_FEUP') ?></span></h3>
	<?php } else { ?>
		<div class="handlediv" title="Click to toggle"><br /></div><h3 class='hndle'><span><?php _e("Recent Users", 'EWD_FEUP') ?></span></h3>
	<?php } ?>
	<div class="inside">
	<div id="posttype-page" class="posttypediv">
		<ul id="posttype-page-tabs" class="posttype-tabs add-menu-item-tabs">
			<!--<li  class="tabs"><a class="nav-tab-link" href="/wp-admin/nav-menus.php?page-tab=most-recent#tabs-panel-posttype-page-most-recent">Most Recent</a></li>-->
			<li  class="tabs"><!--<a class="nav-tab-link" href="/wp-admin/nav-menus.php?page-tab=all#page-all">--><?php _e("Recent", 'EWD_FEUP') ?><!--</a>--></li>
			<!--<li ><a class="nav-tab-link" href="/wp-admin/nav-menus.php?page-tab=search#tabs-panel-posttype-page-search">Search</a></li>-->
		</ul>

		<div id="tabs-panel-posttype-page-most-recent" class="tabs-panel tabs-panel-active">
			<ul id="pagechecklist-most-recent" class="categorychecklist form-no-clear">
				<?php //$Products = $wpdb->get_results("SELECT Item_ID, Item_Name FROM $items_table_name ORDER BY Item_Views DESC"); 
					if ($Admin_Approval == "Yes") {$Users = $wpdb->get_results("SELECT User_ID, Username FROM $ewd_feup_user_table_name WHERE User_Admin_Approved!='Yes'");}
					else {$Users = $wpdb->get_results("SELECT User_ID, Username FROM $ewd_feup_user_table_name");}
					foreach ($Users as $User) {
						echo "<li><label class='menu-item-title'><a href='admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_User_Details&Selected=User&User_ID=" . $User->User_ID ."'> " . $User->Username . "</a></label></li>";
					}
				?>
			</ul>
		</div><!-- /.tabs-panel -->

		<div class="tabs-panel tabs-panel-inactive" id="tabs-panel-posttype-page-search">
				<!--<p class="quick-search-wrap">
				<input type="search" class="quick-search input-with-default-title" title="Search" value="" name="quick-search-posttype-page" />
				<img class="waiting" src="http://www.etoilewebdesign.com/wp-admin/images/wpspin_light.gif" alt="" />
				<input type="submit" name="submit" id="submit-quick-search-posttype-page" class="quick-search-submit button-secondary hide-if-js" value="Search"  />			</p>-->

			<ul id="page-search-checklist" class="list:page categorychecklist form-no-clear">
						</ul>
		</div><!-- /.tabs-panel -->

		<div id="page-all" class="tabs-panel tabs-panel-view-all tabs-panel-inactive">

		</div><!-- /.tabs-panel -->

		<p class="button-controls">
			<!--<span class="list-controls">
				<a href="/wp-admin/nav-menus.php?page-tab=all&#038;selectall=1#posttype-page" class="select-all">Select All</a>
			</span>-->

			<!--<span class="add-to-menu">
				<span class="spinner"></span>
				<input type="submit" class="button-secondary submit-add-to-menu" value="Add to Menu" name="add-post-type-menu-item" id="submit-posttype-page" />
			</span>-->
		</p>

	</div><!-- /.posttypediv -->
	</div>
</div>

</div>
</div>
</div>
</div>
