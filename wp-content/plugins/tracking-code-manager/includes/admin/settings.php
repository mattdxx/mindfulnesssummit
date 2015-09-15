<?php
function tcm_ui_track() {
    global $tcm;
    $track=$tcm->Utils->qs('track', '');
    if($track!='') {
        $track=intval($track);
        $tcm->Options->setTrackingEnable($track);
        $tcm->Tracking->sendTracking(TRUE);
    }

    $uri=TCM_TAB_SETTINGS_URI.'&track=';
    if($tcm->Options->isTrackingEnable()) {
        $uri.='0';
        $tcm->Options->pushSuccessMessage('EnableAllowTrackingNotice', $uri);
    } else {
        $uri.='1';
        $tcm->Options->pushErrorMessage('DisableAllowTrackingNotice', $uri);
    }
    $tcm->Options->writeMessages();
}
function tcm_ui_settings() {
    global $tcm;

    $tcm->Form->prefix='License';
    if($tcm->Check->nonce('tcm_settings')) {
        $options=$tcm->Options->getMetaboxPostTypes();
        foreach($options as $k=>$v) {
            $v=intval($tcm->Utils->qs('metabox_'.$k, 0));
            $options[$k]=$v;
        }
        $tcm->Options->setMetaboxPostTypes($options);
    }

    $tcm->Form->formStarts();
    {
        $tcm->Form->p('MetaboxSection');
        $metaboxes=$tcm->Options->getMetaboxPostTypes();

        $types=$tcm->Utils->query(TCM_QUERY_POST_TYPES);
        foreach($types as $v) {
            $v=$v['name'];
            $tcm->Form->checkbox('metabox_'.$v, $metaboxes[$v]);
        }
        $tcm->Form->nonce('tcm_settings');
        $tcm->Form->br();
        $tcm->Form->submit('Save');
    }
    $tcm->Form->formEnds();
}