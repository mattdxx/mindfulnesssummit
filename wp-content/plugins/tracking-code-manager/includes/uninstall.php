<?php 

function tcm_uninstall($networkwide=NULL) {
	global $wpdb;

}

register_uninstall_hook(TCM_PLUGIN_FILE, 'tcm_uninstall');
?>