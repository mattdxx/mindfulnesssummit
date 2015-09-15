<?php
function tcm_ui_about() {
    global $tcm;

    $tcm->Options->pushSuccessMessage($tcm->Lang->L('AboutNotice'));
    $tcm->Options->writeMessages();

    ?>
    <div><?php $tcm->Lang->P('AboutText')?></div>
    <style>
        ul li {
            padding:2px;
        }
    </style>
    <ul>
        <li>
            <img style="float:left; margin-right:10px;" src="<?php echo TCM_PLUGIN_IMAGES?>email.png" />
            <a href="mailto:aleste@intellywp.com">aleste@intellywp.com</a>
        </li>
        <li>
            <img style="float:left; margin-right:10px;" src="<?php echo TCM_PLUGIN_IMAGES?>twitter.png" />
            <?php $tcm->Utils->twitter('intellywp')?>
        </li>
        <li>
            <img style="float:left; margin-right:10px;" src="<?php echo TCM_PLUGIN_IMAGES?>internet.png" />
            <a href="http://intellywp.com/?utm_source=free-users&utm_medium=tcm-about&utm_campaign=TCM" target="_new">IntellyWP.com</a>
        </li>
    </ul>
    <?php
}