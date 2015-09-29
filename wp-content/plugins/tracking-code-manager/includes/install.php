<?php
register_activation_hook(TCM_PLUGIN_FILE, 'tcm_install');
function tcm_install($networkwide=NULL) {
	global $wpdb, $tcm;

    $time=$tcm->Options->getPluginInstallDate();
    if($time==0) {
        $tcm->Options->setPluginInstallDate(time());
    }
    $tcm->Options->setPluginUpdateDate(time());
    $tcm->Options->setShowWhatsNew(TRUE);
    $tcm->Options->setPluginFirstInstall(TRUE);
}

add_action('admin_init', 'tcm_first_redirect');
function tcm_first_redirect() {
    global $tcm;
    if ($tcm->Options->isPluginFirstInstall()) {
        $tcm->Options->setPluginFirstInstall(FALSE);
        $tcm->Options->setShowActivationNotice(TRUE);
        $tcm->Utils->redirect(TCM_PAGE_MANAGER);
    }
}
