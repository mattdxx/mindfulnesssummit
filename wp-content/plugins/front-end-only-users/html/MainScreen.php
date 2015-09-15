<div class="EWD_FEUP_Menu">
	<h2 class="nav-tab-wrapper">
		<a id="Dashboard_Menu" class="MenuTab nav-tab <?php if ($Display_Page == '' or $Display_Page == 'Dashboard') {echo 'nav-tab-active';}?>" onclick="ShowTab('Dashboard');"><?php _e("Dashboard", "EWD_FEUP"); ?></a>
		<a id="Users_Menu" class="MenuTab nav-tab <?php if ($Display_Page == 'Users') {echo 'nav-tab-active';}?>" onclick="ShowTab('Users');"><?php _e("Users", "EWD_FEUP"); ?></a>
		<a id="Statistics_Menu" class="MenuTab nav-tab <?php if ($Display_Page == 'Statistics') {echo 'nav-tab-active';}?>" onclick="ShowTab('Statistics');"><?php _e("Statistics", "EWD_FEUP"); ?></a>
		<a id="Fields_Menu" class="MenuTab nav-tab <?php if ($Display_Page == 'Field') {echo 'nav-tab-active';}?>" onclick="ShowTab('Fields');"><?php _e("Fields", "EWD_FEUP"); ?></a>
		<a id="Levels_Menu" class="MenuTab nav-tab <?php if ($Display_Page == 'Levels') {echo 'nav-tab-active';}?>" onclick="ShowTab('Levels');"><?php _e("Levels", "EWD_FEUP"); ?></a>
		<a id="Options_Menu" class="MenuTab nav-tab <?php if ($Display_Page == 'Options') {echo 'nav-tab-active';}?>" onclick="ShowTab('Options');"><?php _e("Options", "EWD_FEUP"); ?></a>
		<a id="Emails_Menu" class="MenuTab nav-tab <?php if ($Display_Page == 'Emails') {echo 'nav-tab-active';}?>" onclick="ShowTab('Emails');"><?php _e("Emails", "EWD_FEUP"); ?></a>
	</h2>
</div>

<div class="clear"></div>

<!-- Add the individual pages to the admin area, and create the active tab based on the selected page -->
<div class="OptionTab <?php if ($Display_Page == "" or $Display_Page == 'Dashboard') {echo 'ActiveTab';} else {echo 'HiddenTab';} ?>" id="Dashboard">
	<?php include( plugin_dir_path( __FILE__ ) . 'DashboardPage.php'); ?>
</div>

<div class="OptionTab <?php if ($Display_Page == 'Users' or $Display_Page == 'User') {echo 'ActiveTab';} else {echo 'HiddenTab';} ?>" id="Users">
	<?php include( plugin_dir_path( __FILE__ ) . 'UsersPage.php'); ?>
</div>

<div class="OptionTab <?php if ($Display_Page == 'Statistics' or $Display_Page == 'Statistic') {echo 'ActiveTab';} else {echo 'HiddenTab';} ?>" id="Statistics">
	<?php include( plugin_dir_path( __FILE__ ) . 'StatisticsPage.php'); ?>
</div>

<div class="OptionTab <?php if ($Display_Page == 'Fields' or $Display_Page == 'Field') {echo 'ActiveTab';} else {echo 'HiddenTab';} ?>" id="Fields">
	<?php include( plugin_dir_path( __FILE__ ) . 'FieldsPage.php'); ?>
</div>

<div class="OptionTab <?php if ($Display_Page == 'Levels' or $Display_Page == 'Level') {echo 'ActiveTab';} else {echo 'HiddenTab';} ?>" id="Levels">
	<?php include( plugin_dir_path( __FILE__ ) . 'LevelsPage.php'); ?>
</div>

</div>	
<div class="OptionTab <?php if ($Display_Page == 'Options' or $Display_Page == 'Option') {echo 'ActiveTab';} else {echo 'HiddenTab';} ?>" id="Options">
	<?php include( plugin_dir_path( __FILE__ ) . 'OptionsPage.php'); ?>
</div>	

<div class="OptionTab <?php if ($Display_Page == 'Emails' or $Display_Page == 'Email') {echo 'ActiveTab';} else {echo 'HiddenTab';} ?>" id="Emails">
	<?php include( plugin_dir_path( __FILE__ ) . 'EmailsPage.php'); ?>
</div>		