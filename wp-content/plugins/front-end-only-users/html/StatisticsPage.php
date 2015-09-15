<?php if ($EWD_FEUP_Full_Version == "Yes") { ?>
<?php $Track_Events = get_option("EWD_FEUP_Track_Events"); ?>
<div id="col-right">
<div class="col-wrap">

<h3>Link Statistics</h3>

<?php 
	if (isset($_GET['Page'])) {$Page = $_GET['Page'];}
	else {$Page = 1;}
	
	$Sql = "SELECT * FROM $ewd_feup_user_events_table_name ";
	$Sql .= "GROUP BY Event_Value ";
	if (isset($_GET['OrderBy']) and $_GET['DisplayPage'] == "Statistics") {$Sql .= "ORDER BY " . $_GET['OrderBy'] . " " . $_GET['Order'] . " ";}
	else {$Sql .= "ORDER BY Count(Event_Value) DESC ";}
	$Sql .= "LIMIT " . ($Page - 1)*20 . ",20";
	$myrows = $wpdb->get_results($Sql);
	$num_rows = $wpdb->get_var("SELECT COUNT(DISTINCT Event_Value) FROM $ewd_feup_user_events_table_name");
	$Number_of_Pages = ceil($num_rows/20);
	$Current_Page_With_Order_By = "admin.php?page=EWD-FEUP-options&DisplayPage=Statistics";
	if (isset($_GET['OrderBy'])) {$Current_Page_With_Order_By .= "&OrderBy=" .$_GET['OrderBy'] . "&Order=" . $_GET['Order'];}?>
   
<div class="tablenav top">
	<div class='tablenav-pages <?php if ($Number_of_Pages == 1) {echo "one-page";} ?>'>
		<span class="displaying-num"><?php echo $num_rows; ?> <?php _e("items", 'EWD_FEUP') ?></span>
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
			<th scope='col'  class='manage-column sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Event_Target_Title" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=Event_Target_Title&Order=DESC'>";}
				 	else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=Event_Target_Title&Order=ASC'>";} ?>
					 <span><?php _e("Page Title/Link", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='type' class='manage-column column-type sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Event_Type" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=Event_Type&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=Event_Type&Order=ASC'>";} ?>
					<span><?php _e("Link Type", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='description' class='manage-column column-description sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Count(Event_Value)" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=Count(Event_Value)&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=Count(Event_Value)&Order=ASC'>";} ?>
					<span><?php _e("Total Clicks", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='required' class='manage-column column-users sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Event_Date" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=Event_Date&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=Event_Date&Order=ASC'>";} ?>
					<span><?php _e("Last Click", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th scope='col'  class='manage-column sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Event_Target_Title" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=Event_Target_Title&Order=DESC'>";}
				 	else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=Event_Target_Title&Order=ASC'>";} ?>
					 <span><?php _e("Page Title/Link", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='type' class='manage-column column-type sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Event_Type" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=Event_Type&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=Event_Type&Order=ASC'>";} ?>
					<span><?php _e("Link Type", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='description' class='manage-column column-description sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Count(Event_Value)" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=Count(Event_Value)&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=Count(Event_Value)&Order=ASC'>";} ?>
					<span><?php _e("Total Clicks", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='required' class='manage-column column-users sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Event_Date" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=Event_Date&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=Event_Date&Order=ASC'>";} ?>
					<span><?php _e("Last Click", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
		</tr>
	</tfoot>

	<tbody id="the-list" class='list:tag'>
		
	<?php
		if ($myrows) { 
	 		foreach ($myrows as $Link) {
				$Click_Count = $wpdb->get_var("SELECT COUNT(User_Event_ID) FROM $ewd_feup_user_events_table_name WHERE Event_Value='" . $Link->Event_Value . "'");
				$Last_Click = $wpdb->get_var("SELECT Event_Date FROM $ewd_feup_user_events_table_name WHERE Event_Value='" . $Link->Event_Value . "' ORDER BY Event_Date DESC");
				echo "<tr id='User-" . $Link->Event_Value ."'>";
				echo "<td class='name column-name'>";
				echo "<strong>";
				echo "<a class='row-title' href='admin.php?page=EWD-FEUP-options&Action=EWD_FEUP_Statistics_Details&Selected=User&Event_Target_Title=" . $Link->Event_Target_Title ."' title='Edit " . $Link->Event_Target_Title . "</a></strong>";
				echo "<br />";
				echo "<div class='target-title'>" . $Link->Event_Target_Title . "</div>";
				echo "</td>";
				echo "<td class='description column-event-type'>" . $Link->Event_Type . "</td>";
				echo "<td class='description column-click-count'>" . $Click_Count . "</td>";
				echo "<td class='users column-last-click'>" . $Last_Click . "</td>";
				echo "</tr>";
			}
		}
	?>

	</tbody>
</table>

<div class="tablenav bottom">
	<div class='tablenav-pages <?php if ($Number_of_Pages == 1) {echo "one-page";} ?>'>
		<span class="displaying-num"><?php echo $num_rows; ?> <?php _e("items", 'EWD_FEUP') ?></span>
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

<br class="clear" />

<h3>User Activity Table</h3>

<?php 
	if (isset($_GET['Page'])) {$Page = $_GET['Page'];}
	else {$Page = 1;}
	
	$Sql = "SELECT * FROM $ewd_feup_user_table_name ";
	if (isset($_GET['OrderBy']) and $_GET['DisplayPage'] == "Statistics") {$Sql .= "ORDER BY " . $_GET['OrderBy'] . " " . $_GET['Order'] . " ";}
	else {$Sql .= "ORDER BY User_Last_Login DESC ";}
	$Sql .= "LIMIT " . ($Page - 1)*20 . ",20";
	$myrows = $wpdb->get_results($Sql);
	$TotalFields = $wpdb->get_results("SELECT User_ID FROM $ewd_feup_user_table_name");
	$num_rows = $wpdb->num_rows; 
	$Number_of_Pages = ceil($num_rows/20);
	$Current_Page_With_Order_By = "admin.php?page=EWD-FEUP-options&DisplayPage=Statistics";
	if (isset($_GET['OrderBy'])) {$Current_Page_With_Order_By .= "&OrderBy=" .$_GET['OrderBy'] . "&Order=" . $_GET['Order'];}?>
   
<div class="tablenav top">
	<div class='tablenav-pages <?php if ($Number_of_Pages == 1) {echo "one-page";} ?>'>
		<span class="displaying-num"><?php echo $wpdb->num_rows; ?> <?php _e("items", 'EWD_FEUP') ?></span>
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
			<th scope='col'  class='manage-column sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Username" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=Username&Order=DESC'>";}
				 	else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=Username&Order=ASC'>";} ?>
					 <span><?php _e("Username", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='type' class='manage-column column-type sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "User_Last_Login" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=User_Last_Login&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=User_Last_Login&Order=ASC'>";} ?>
					<span><?php _e("Last Login", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='description' class='manage-column column-description sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "User_Total_Logins" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=User_Total_Logins&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=User_Total_Logins&Order=ASC'>";} ?>
					<span><?php _e("Total Logins", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='required' class='manage-column column-users sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "User_Date_Created" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=User_Date_Created&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=User_Date_Created&Order=ASC'>";} ?>
					<span><?php _e("Joined Date", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<th scope='col'  class='manage-column sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "Username" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=Username&Order=DESC'>";}
				 	else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=Username&Order=ASC'>";} ?>
					 <span><?php _e("Username", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='type' class='manage-column column-type sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "User_Last_Login" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=User_Last_Login&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=User_Last_Login&Order=ASC'>";} ?>
					<span><?php _e("Last Login", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='description' class='manage-column column-description sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "User_Total_Logins" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=User_Total_Logins&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=User_Total_Logins&Order=ASC'>";} ?>
					<span><?php _e("Total Logins", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th scope='col' id='required' class='manage-column column-users sortable desc'  style="">
				<?php if ($_GET['OrderBy'] == "User_Date_Created" and $_GET['Order'] == "ASC") { echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=User_Date_Created&Order=DESC'>";}
					else {echo "<a href='admin.php?page=EWD-FEUP-options&DisplayPage=Statistics&OrderBy=User_Date_Created&Order=ASC'>";} ?>
					<span><?php _e("Joined Date", 'EWD_FEUP') ?></span>
					<span class="sorting-indicator"></span>
				</a>
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

<div class="tablenav bottom">
	<div class='tablenav-pages <?php if ($Number_of_Pages == 1) {echo "one-page";} ?>'>
		<span class="displaying-num"><?php echo $wpdb->num_rows; ?> <?php _e("items", 'EWD_FEUP') ?></span>
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

<br class="clear" />
</div>
</div> <!-- /col-right -->


<div id="col-left">
<div class="col-wrap">

<div class="form-wrap">
<h2><?php _e("Summary Statistics", 'EWD_FEUP') ?></h2>
<?php 
	$wpdb->show_errors();
	$Total_Logins = $wpdb->get_var("SELECT SUM(User_Total_Logins) FROM $ewd_feup_user_table_name");
	$Total_Page_Loads = $wpdb->get_var("SELECT COUNT(User_Event_ID) FROM $ewd_feup_user_events_table_name");
	$Most_Viewed_Page = $wpdb->get_var("SELECT Event_Value FROM $ewd_feup_user_events_table_name WHERE Event_Type='Link' GROUP BY Event_Value ORDER BY Count(Event_Value) DESC LIMIT 1");
	$Most_Viewed_Attachment = $wpdb->get_var("SELECT Event_Value FROM $ewd_feup_user_events_table_name WHERE Event_Type='Attachment' GROUP BY Event_Value ORDER BY Count(Event_Value) DESC LIMIT 1");
	$Most_Viewed_Image = $wpdb->get_var("SELECT Event_Value FROM $ewd_feup_user_events_table_name WHERE Event_Type='Image' GROUP BY Event_Value ORDER BY Count(Event_Value) DESC LIMIT 1");
?>
<div>
<p><strong>Total logins by users:</strong> <?php echo $Total_Logins; ?></p>
<?php if ($Track_Events == "No") { ?>
	<h4>To enable the statistics below, please set "Track User Activity" to "Yes" in the "Options" tab</h4>
<?php } ?>
<p><strong>Total page loads by users:</strong> <?php echo $Total_Page_Loads; ?></p>
<p><strong>Most common link clicked:</strong><br/><?php echo $Most_Viewed_Page; ?></p>
<p><strong>Most common attachment clicked:</strong><br/><?php echo $Most_Viewed_Attachment; ?></p>
<p><strong>Most common image clicked:</strong><br/><?php echo $Most_Viewed_Image; ?></p>

</div>

<br class="clear" />
</div>
</div>
</div><!-- /col-left -->

<?php } else { ?>
<div class="Info-Div">
	<h2><?php _e("Full Version Required!", 'EWD_FEUP') ?></h2>
	<div class="upcp-full-version-explanation">
		<?php _e("The full version of Front-End Only Users is required to use tags.", "EWD_FEUP");?><a href="http://www.etoilewebdesign.com/front-end-users-plugin/"><?php _e(" Please upgrade to unlock this page!", 'EWD_FEUP'); ?></a>
	</div>
</div>
<?php } ?>