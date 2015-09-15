<?php
//column renderer
function tcm_ui_manager_column($active, $values=NULL, $hide=FALSE) {
    global $tcm;
    ?>
    <td style="text-align:center;">
        <?php
        if($hide) {
            $text='-';
        } else {
            if($active) {
                $text='<span style="font-weight:bold; color:green">'.$tcm->Lang->L('Yes').'</span>';
            } else {
                $text='<span style="font-weight:bold; color:red">'.$tcm->Lang->L('No').'</span>';
            }
            if($active && $values) {
                if(!is_array($values)) {
                    $text.='&nbsp;{'.$values.'}';
                } elseif(count($values)>0) {
                    $what=implode(',', $values);
                    if($what!='') {
                        $text.='&nbsp;['.$what.']';
                    }
                }
            }
        }
        echo $text;
        ?>
    </td>
<?php
}

function tcm_ui_free_notice() {
    global $tcm;

    if($tcm->Manager->rc()<=0) {
        $tcm->Options->pushErrorMessage('FreeLicenseReached');
    } elseif($tcm->Manager->count()>0) {
        $tcm->Options->pushSuccessMessage('PluginLimit.Line1', 6, $tcm->Manager->rc());
        $url='http://intellywp.com/tracking-code-manager/?utm_source=free-users&utm_medium=tcm-banner&utm_campaign=TCM';
        $tcm->Options->pushSuccessMessage('PluginLimit.Line2', $url);
    }
    $tcm->Options->writeMessages();
}
function tcm_ui_manager() {
    global $tcm;

    if($tcm->Plugin->isActive(TCM_PLUGINS_TRACKING_CODE_MANAGER_PRO)) {
        $tcm->Options->pushErrorMessage('YouHaveThePremiumVersion', TCMP_TAB_MANAGER_URI);
        $tcm->Options->writeMessages();
        return;
    }

    $id=intval($tcm->Utils->qs('id', 0));
    if ($tcm->Utils->is('action', 'delete') && $id>0 && wp_verify_nonce($tcm->Utils->qs('tcm_nonce'), 'tcm_delete')) {
        $snippet = $tcm->Manager->get($id);
        if ($tcm->Manager->remove($id)) {
            $tcm->Options->pushSuccessMessage('CodeDeleteNotice', $id, $snippet['name']);
        }
    } elseif($id>0) {
        $snippet=$tcm->Manager->get($id);
        if($tcm->Utils->is('action', 'toggle') && $id>0 && wp_verify_nonce($tcm->Utils->qs('tcm_nonce'), 'tcm_toggle')) {
            $snippet['active']=($snippet['active']==0 ? 1 : 0);
            $tcm->Manager->put($snippet['id'], $snippet);
        }
        $tcm->Options->pushSuccessMessage('CodeUpdateNotice', $id, $snippet['name']);
    }

    $tcm->Options->writeMessages();
    tcm_ui_free_notice();

    //controllo che faccio per essere retrocompatibile con la prima versione
    //dove non avevo un id e salvavo tutto con il con il nome quindi una stringa
    $snippets=$tcm->Manager->keys();
    foreach($snippets as $v) {
        $snippet=$tcm->Manager->get($v, FALSE, TRUE);
        if(!$snippet) {
            $tcm->Manager->remove($v);
        } elseif(!is_numeric($v)) {
            $tcm->Manager->remove($v);
            $tcm->Manager->put('', $snippet);
        }
    }
    $snippets=$tcm->Manager->values();
    if (count($snippets)>0) { ?>
        <div style="float:left;">
            <form method="get" style="margin:5px; float:left;" action="<?php echo TCM_PAGE_PREMIUM?>">
                <input type="hidden" name="utm_source" value="free-users" />
                <input type="hidden" name="utm_medium" value="tcm-manager" />
                <input type="hidden" name="utm_campaign" value="TCM" />
                <input type="submit" class="button" value="<?php $tcm->Lang->P('Button.BuyPRO')?>" />
            </form>
            <form method="get" action="" style="margin:5px; float:left;">
                <input type="hidden" name="page" value="<?php echo TCM_PLUGIN_SLUG?>" />
                <input type="hidden" name="tab" value="<?php echo TCM_TAB_EDITOR?>" />
                <input type="submit" class="button-primary" value="<?php $tcm->Lang->P('Button.Add')?>" />
            </form>
        </div>
        <div style="clear:both;"></div>

        <style>
            .widefat th {
                font-weight: bold!important;
            }
        </style>
        <table class="widefat fixed" style="width:auto;">
            <thead>
                <tr>
                    <th>#N</th>
                    <th><?php $tcm->Lang->P('Name')?></th>
                    <th><?php $tcm->Lang->P('Position')?></th>
                    <th style="text-align:center;"><?php $tcm->Lang->P('Active?')?></th>
                    <th><?php $tcm->Lang->P('Where?')?></th>
                    <th style="text-align:center;"><?php $tcm->Lang->P('Each pages?')?></th>
                    <?php /*<th style="text-align:center;"><?php $tcm->Lang->P('Count')?></th> */?>
                    <th style="text-align:center;"><?php $tcm->Lang->P('Actions')?></th>
                </tr>
            </thead>
            <tbody>
            <?php
            $i=1;
            foreach ($snippets as $snippet) { ?>
                <tr>
                    <td>#<?php echo $i++ ?></td>
                    <td><?php echo $snippet['name']?></td>
                    <td><?php $tcm->Lang->P('Editor.position.'.$snippet['position'])?></td>
                    <td style="text-align:center;">
                        <?php
                        $color='red';
                        $text='No';
                        $question='QuestionActiveOn';
                        if($snippet['active']==1) {
                            $color='green';
                            $text='Yes';
                            $question='QuestionActiveOff';
                        }
                        $text='<span style="font-weight:bold; color:'.$color.'">'.$tcm->Lang->L($text).'</span>';
                        ?>
                        <a onclick="return confirm('<?php echo $tcm->Lang->L($question)?>');" href="<?php echo TCM_TAB_MANAGER_URI?>&tcm_nonce=<?php echo esc_attr(wp_create_nonce('tcm_toggle')); ?>&action=toggle&id=<?php echo $snippet['id'] ?>">
                            <?php echo $text?>
                        </a>
                    </td>
                    <td>
                        <?php
                        $text='Standard';
                        if($snippet['trackMode']!=TCM_TRACK_MODE_CODE) {
                            $text=$tcm->Plugin->getName($snippet['trackMode']);
                        }
                        $tcm->Lang->P($text);
                        ?>
                    </td>
                    <?php
                    $hide=!$snippet['active'];
                    $active=($snippet['trackMode']==TCM_TRACK_MODE_CODE
                        && $snippet['trackPage']==TCM_TRACK_PAGE_ALL);
                    tcm_ui_manager_column($active, NULL, $hide);
                    ?>
                    <?php /*<td style="text-align:center;"><?php echo $snippet['codesCount']?></td> */?>
                    <td style="text-align:center;">
                        <a href="<?php echo TCM_TAB_EDITOR_URI?>&id=<?php echo $snippet['id'] ?>">
                            <?php echo $tcm->Lang->L('Edit')?>
                        </a>
                        &nbsp;|&nbsp;
                        <span class="trash">
                            <a onclick="return confirm('<?php echo $tcm->Lang->L('Question.DeleteQuestion')?>');" href="<?php echo TCM_TAB_MANAGER_URI?>&tcm_nonce=<?php echo esc_attr(wp_create_nonce('tcm_delete')); ?>&action=delete&id=<?php echo $snippet['id'] ?>">
                                <?php echo $tcm->Lang->L('Delete')?>
                            </a>
                        </span>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <h2><?php $tcm->Lang->P('EmptyTrackingList', TCM_TAB_EDITOR_URI)?></h2>
    <?php }
    tcm_notice_pro_features();
}