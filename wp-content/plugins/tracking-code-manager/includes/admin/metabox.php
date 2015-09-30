<?php
function tcm_ui_metabox($post) {
    global $tcm;
    // Add an nonce field so we can check for it later.
    wp_nonce_field('tcm_meta_box', 'tcm_meta_box_nonce');

    $args=array('metabox'=>TRUE, 'field'=>'id');
    $ids=$tcm->Manager->getCodes(-1, $post, $args);

    $allIds=array();
    $snippets=$tcm->Manager->values();
    $postType=$post->post_type;
    foreach($snippets as $snippet) {
        if($snippet['trackMode']==TCM_TRACK_MODE_CODE) {
            if($snippet['active']!=0) {
                if($snippet['exceptPostsOfType_'.$postType.'_Active']==0
                    || !in_array(-1, $snippet['exceptPostsOfType_'.$postType])) {
                    $allIds[]=$snippet['id'];
                }
            }
        }
    }
    ?>
    <div>
        <?php $tcm->Lang->P('Select existing Tracking Code')?>
    </div>
    <input type="hidden" name="tcm_all_ids" value="<?php echo implode(',', $allIds)?>" />

    <div>
        <?php
        $postType=$post->post_type;
        foreach($snippets as $snippet) {
            $id=$snippet['id'];
            if($snippet['trackMode']!=TCM_TRACK_MODE_CODE) {
                continue;
            }

            $disabled='';
            $checked='';

            if(!in_array($id, $allIds)) {
                $disabled=' DISABLED';
            } elseif(in_array($id, $ids)) {
                $checked=' CHECKED';
            }
            ?>
            <input type="checkbox" class="tcm-checkbox" name="tcm_ids[]" value="<?php echo $id?>" <?php echo $checked ?> <?php echo $disabled ?> />
            <?php echo $snippet['name']?></a>
            <a href="<?php echo TCM_TAB_EDITOR_URI?>&id=<?php echo $id?>" target="_blank">&nbsp;››</a>

            <br/>
        <?php } ?>
    </div>

    <br/>
    <?php if($tcm->Manager->rc()>0) { ?>
        <div>
            <label for="tcm_name"><?php $tcm->Lang->P('Or add a name')?></label>
            <br/>
            <input type="text" name="tcm_name" value="" style="width:100%"/>
        </div>
        <div>
            <label for="code"><?php $tcm->Lang->P('and paste HTML code here')?></label>
            <br/>
            <textarea dir="ltr" dirname="ltr" name="tcm_code" class="tcm-textarea" style="width:100%; height:175px;"></textarea>
        </div>
    <?php } else { ?>
        <span style="color:red;font-weight:bold;"><?php $tcm->Lang->P('FreeLicenseReached')?></span>
    <?php } ?>

    <div style="clear:both"></div>
    <i>Saving the post you'll save the tracking code</i>
<?php }

//si aggancia per creare i metabox in post e page
add_action('add_meta_boxes', 'tcm_add_meta_box');
function tcm_add_meta_box() {
    global $tcm;

    $free=array('post', 'page');
    $options=$tcm->Options->getMetaboxPostTypes();
    $screens=array();
    foreach($options as $k=>$v) {
        if(intval($v)>0) {
            $screens[]=$k;
        }
    }
    if(count($screens)>0) {
        foreach ($screens as $screen) {
            add_meta_box(
                'tcm_sectionid'
                , $tcm->Lang->L('Tracking Code by IntellyWP')
                , 'tcm_ui_metabox'
                , $screen
                , 'side'
            );
        }
    }
}
function tcm_edit_snippet_array($post, &$snippet, $prefix, $diff) {
    global $tcm;
    $postId=$tcm->Utils->get($post, 'ID', FALSE);
    if($postId===FALSE) {
        $postId=$tcm->Utils->get($post, 'post_ID');
    }
    $postType=$tcm->Utils->get($post, 'post_type');

    $keyArray='PostsOfType_'.$postType;
    $keyActive=$keyArray.'_Active';
    if($snippet[$prefix.$keyActive]==0) {
        $snippet[$prefix.$keyArray]=array();
    }
    $k=$prefix.$keyArray;
    if($diff) {
        $snippet[$k]=array_diff($snippet[$k], array($postId));
    } else {
        $snippet[$k]=array_merge($snippet[$k], array($postId));
        if(in_array(-1, $snippet[$k])) {
            $snippet[$k]=array(-1);
        }
    }
    $snippet[$k]=array_unique($snippet[$k]);
    $snippet[$prefix.$keyActive]=(count($snippet[$k])>0 ? 1 : 0);
    return $snippet;
}
//si aggancia a quando un post viene salvato per salvare anche gli altri dati del metabox
add_action('save_post', 'tcm_save_meta_box_data');
function tcm_save_meta_box_data($postId) {
    global $tcm;

    //in case of custom post type edit_ does not exist
    //if (!current_user_can('edit_'.$postType, $postId)) {
    //    return;
    //}

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!isset($_POST['tcm_meta_box_nonce']) || !isset($_POST['post_type'])) {
        return;
    }
    // Verify that the nonce is valid.
    if (!wp_verify_nonce( $_POST['tcm_meta_box_nonce'], 'tcm_meta_box')) {
        return;
    }

    $args=array('metabox'=>TRUE, 'field'=>'id');
    $ids=$tcm->Manager->getCodes(-1, $_POST, $args);
    if(!is_array($ids)) {
        $ids=array();
    }

    $allIds=$tcm->Utils->qs('tcm_all_ids');
    if($allIds===FALSE || $allIds=='') {
        $allIds=array();
    } else {
        $allIds=explode(',', $allIds);
    }
    $currentIds=$tcm->Utils->qs('tcm_ids', array());
    if(!is_array($currentIds)) {
        $currentIds=array();
    }

    if($ids!=$currentIds) {
        foreach($allIds as $id) {
            $id=intval($id);
            if($id<=0) {
                continue;
            }
            if(in_array($id, $currentIds) && in_array($id, $ids)) {
                //selected now and already selected
                continue;
            }
            if(!in_array($id, $currentIds) && !in_array($id, $ids)) {
                //not selected now and not already selected
                continue;
            }

            $snippet=$tcm->Manager->get($id);
            if($snippet==NULL) {
                continue;
            }

            $snippet=tcm_edit_snippet_array($_POST, $snippet, 'include', TRUE);
            $snippet=tcm_edit_snippet_array($_POST, $snippet, 'except', TRUE);
            if(in_array($id, $currentIds)) {
                $snippet=tcm_edit_snippet_array($_POST, $snippet, 'include', FALSE);
            } else {
                $snippet=tcm_edit_snippet_array($_POST, $snippet, 'except', FALSE);
            }
            $tcm->Manager->put($id, $snippet);
        }
    }

    $name=stripslashes($tcm->Utils->qs('tcm_name'));
    $code=$tcm->Utils->qs('tcm_code');
    if($name!='' && $code!='') {
        $postType=$_POST['post_type'];
        $keyArray='PostsOfType_'.$postType;
        $keyActive=$keyArray.'_Active';

        $snippet=array(
            'active'=>1
            , 'name'=>$name
            , 'code'=>$code
            , 'trackPage'=>TCM_TRACK_PAGE_SPECIFIC
            , 'trackMode'=>TCM_TRACK_MODE_CODE
        );
        $snippet['include'.$keyActive]=1;
        $snippet['include'.$keyArray]=array($postId);
        $snippet=$tcm->Manager->put('', $snippet);
        $tcm->Log->debug("NEW SNIPPET REGISTRED=%s", $snippet);
    }
}
