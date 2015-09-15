<?php
function tcm_ui_editor_check($snippet) {
    global $tcm;

    $snippet['trackMode']=intval($snippet['trackMode']);
    $snippet['trackPage']=intval($snippet['trackPage']);

    $snippet['includeEverywhereActive']=0;
    if($snippet['trackPage']==TCM_TRACK_PAGE_ALL) {
        $snippet['includeEverywhereActive']=1;
    }
    $snippet=$tcm->Manager->sanitize($snippet['id'], $snippet);

    if ($snippet['name'] == '') {
        $tcm->Options->pushErrorMessage('Please enter a unique name');
    } else {
        $exist=$tcm->Manager->exists($snippet['name']);
        if ($exist && $exist['id'] != $snippet['id']) {
            //nonostante il tutto il nome deve essee univoco
            $tcm->Options->pushErrorMessage('You have entered a name that already exists. IDs are NOT case-sensitive');
        }
    }
    if ($snippet['code'] == '') {
        $tcm->Options->pushErrorMessage('Paste your HTML Tracking Code into the textarea');
    }

    if($snippet['trackMode']==TCM_TRACK_MODE_CODE) {

        $types=$tcm->Utils->query(TCM_QUERY_POST_TYPES);
        if($snippet['trackPage']==TCM_TRACK_PAGE_SPECIFIC) {
            foreach ($types as $v) {
                $includeActiveKey = 'includePostsOfType_' . $v['name'] . '_Active';
                $includeArrayKey = 'includePostsOfType_' . $v['name'];
                $exceptActiveKey = 'exceptPostsOfType_' . $v['name'] . '_Active';
                $exceptArrayKey = 'exceptPostsOfType_' . $v['name'];

                if ($snippet[$includeActiveKey] == 1 && $snippet[$exceptActiveKey] == 1) {
                    if (in_array(-1, $snippet[$includeArrayKey]) && in_array(-1, $snippet[$exceptArrayKey])) {
                        $tcm->Options->pushErrorMessage('Error.IncludeExcludeAll', $v['name']);
                    }
                }
                if ($snippet[$includeActiveKey] == 1 && count($snippet[$includeArrayKey]) == 0) {
                    $tcm->Options->pushErrorMessage('Error.IncludeSelectAtLeastOne', $v['name']);
                }
            }

            //second loop to respect the display order
            foreach ($types as $v) {
                $includeActiveKey = 'includePostsOfType_' . $v['name'] . '_Active';
                $includeArrayKey = 'includePostsOfType_' . $v['name'];
                $exceptActiveKey = 'exceptPostsOfType_' . $v['name'] . '_Active';
                $exceptArrayKey = 'exceptPostsOfType_' . $v['name'];

                if ($snippet[$includeActiveKey] == 1 && in_array(-1, $snippet[$includeArrayKey])) {
                    if ($snippet[$exceptActiveKey] == 1 && count($snippet[$exceptArrayKey]) == 0) {
                        $tcm->Options->pushErrorMessage('Error.ExcludeSelectAtLeastOne', $v['name']);
                    }
                }
            }
        } else {
            foreach($types as $v) {
                $exceptActiveKey='exceptPostsOfType_'.$v['name'].'_Active';
                $exceptArrayKey='exceptPostsOfType_'.$v['name'];

                if($snippet[$exceptActiveKey]==1
                    && count($snippet[$exceptArrayKey])==0) {
                    $tcm->Options->pushErrorMessage('Error.ExcludeSelectAtLeastOne', $v['name']);
                }
            }
        }
    }
}
function tcm_ui_editor() {
    global $tcm;

    $tcm->Form->prefix = 'Editor';
    $id = intval($tcm->Utils->qs('id', 0));
    $action = $tcm->Utils->qs('action');
    $snippet = $tcm->Manager->get($id, TRUE);
    //var_dump($snippet);

    if (wp_verify_nonce($tcm->Utils->qs('tcm_nonce'), 'tcm_nonce')) {
        //var_dump($_POST);
        //var_dump($_GET);
        foreach ($snippet as $k => $v) {
            $snippet[$k] = $tcm->Utils->qs($k);
            if (is_string($snippet[$k])) {
                $snippet[$k] = stripslashes($snippet[$k]);
            }
        }
        tcm_ui_editor_check($snippet);

        if (!$tcm->Options->hasErrorMessages()) {
            $snippet = $tcm->Manager->put($snippet['id'], $snippet);
            /*if ($id <= 0) {
                $tcm->Options->pushSuccessMessage('Editor.Add', $snippet['id'], $snippet['name']);
                $snippet = $tcm->Manager->get('', TRUE);
            } else {
                $tcm->Utils->redirect(TCM_PAGE_MANAGER.'&id='.$id);
                exit();
            }*/
            $id=$snippet['id'];
            $tcm->Utils->redirect(TCM_PAGE_MANAGER.'&id='.$id);
        }
    }
    if(!$tcm->Options->writeMessages()) {
        tcm_ui_free_notice();
    }
    if($tcm->Manager->rc()<=0 && $id<=0) {
        $tcm->Utils->redirect(TCM_PAGE_MANAGER);
        exit();
    }

    ?>
    <script>
        jQuery(function(){
            var tcmPostTypes=[];

            <?php
            $types=$tcm->Utils->query(TCM_QUERY_POST_TYPES);
            foreach($types as $v) { ?>
                tcmPostTypes.push('<?php echo $v['name']?>');
            <?php } ?>

            //enable/disable some part of except creating coherence
            function tcmCheckVisible() {
                var showExceptCategories=true;
                var showExceptTags=true;
                var showExceptPostTypes={};
                jQuery.each(tcmPostTypes, function (i,v) {
                    showExceptPostTypes[v]=true;
                });

                var $mode=jQuery('[name=trackMode]:checked');
                var showTrackCode=false;
                var showTrackConversion=false;
                if($mode.length>0) {
                    if(parseInt($mode.val())!=<?php echo TCM_TRACK_MODE_CODE ?>) {
                        showTrackConversion=true;
                        jQuery('[name=position]').val(<?php echo TCM_POSITION_FOOTER?>);
                        jQuery('[name=position]').prop('disabled', true);

                        tcmShowHide('.box-track-conversion', false);
                        tcmShowHide('#box-track-conversion-'+$mode.val(), true);
                    } else {
                        showTrackCode=true;
                        jQuery('[name=position]').prop('disabled', false);
                    }
                }
                tcmShowHide('#box-track-conversion', showTrackConversion);
                tcmShowHide('#box-track-code', showTrackCode);

                var $all=jQuery('[name=trackPage]:checked');
                if($all.length>0 && parseInt($all.val())==<?php echo TCM_TRACK_PAGE_SPECIFIC ?>) {
                    showExceptCategories=false;
                    showExceptTags=false;

                    jQuery.each(tcmPostTypes, function (i,v) {
                        isCheck=jQuery('#includePostsOfType_'+v+'_Active').is(':checked');
                        selection=jQuery('#includePostsOfType_'+v).select2("val");
                        found=false;
                        for(i=0; i<selection.length; i++) {
                            if(parseInt(selection[i])==-1){
                                found=true;
                            }
                        }

                        showExceptPostTypes[v]=false;
                        if(isCheck && found) {
                            showExceptPostTypes[v]=true;
                            if(v!='page') {
                                showExceptCategories=true;
                                showExceptTags=true;
                            }
                        }
                    });
                }

                //hide/show except post type if all the website is selected
                //or [All] is selected in a specific post type select
                var showExcept=false;
                jQuery.each(showExceptPostTypes, function (k,v) {
                    if(v) {
                        //at least one post type to show except
                        showExcept=true;
                    }
                    tcmShowHide('#exceptPostsOfType_'+k+'Box', v);
                });

                //tcmShowHide('#exceptCategoriesBox', showExceptCategories);
                //tcmShowHide('#exceptTagsBox', showExceptTags);
                showInclude=false;
                if($all.length==0) {
                    showExcept=false;
                } else {
                    showExcept=(showExcept || showExceptTags || showExceptCategories);
                    if(parseInt($all.val())==<?php echo TCM_TRACK_PAGE_ALL ?>) {
                        showExcept=true;
                    } else {
                        showInclude=true;
                    }
                }
                tcmShowHide('#tcm-except-div', showExcept);
                tcmShowHide('#tcm-include-div', showInclude);
            }
            function tcmShowHide(selector, show) {
                $selector=jQuery(selector);
                if(show) {
                    $selector.show();
                } else {
                    $selector.hide();
                }
            }

            /*jQuery(".tcmTags").select2({
                placeholder: "Type here..."
                , theme: "classic"
            }).on('change', function() {
                tcmCheckVisible();
            });*/
            jQuery(".tcmLineTags").select2({
                placeholder: "Type here..."
                , theme: "classic"
                , width: '550px'
            });

            jQuery('.tcm-hideShow').click(function() {
                tcmCheckVisible();
            });
            jQuery('.tcm-hideShow, input[type=checkbox], input[type=radio]').change(function() {
                tcmCheckVisible();
            });
            jQuery('.tcmLineTags').on('change', function() {
                tcmCheckVisible();
            });
            tcmCheckVisible();
        });
    </script>
    <?php

    $tcm->Form->formStarts();
    $tcm->Form->hidden('id', $snippet);
    $tcm->Form->checkbox('active', $snippet);
    $tcm->Form->text('name', $snippet);
    $tcm->Form->textarea('code', $snippet);
    $values = array(TCM_POSITION_HEAD, TCM_POSITION_BODY, TCM_POSITION_FOOTER);
    $tcm->Form->select('position', $snippet, $values, FALSE);

    $args=array('id'=>'box-track-mode');
    $tcm->Form->divStarts($args);
    {
        $tcm->Form->p('Where do you want to add this code?');
        $tcm->Form->radio('trackMode', $snippet['trackMode'], TCM_TRACK_MODE_CODE);
        $plugins=$tcm->Ecommerce->getActivePlugins();
        if(count($plugins)==0) {
            $plugins=array('Ecommerce'=>array(
                'name'=>'Ecommerce'
                , 'id'=>TCM_PLUGINS_NO_PLUGINS
                , 'version'=>'')
            );
        }
        $tcm->Form->tagNew=TRUE;
        foreach($plugins as $k=>$v) {
            $ecommerce=$v['name'];
            if(isset($v['version']) && $v['version']!='') {
                $ecommerce.=' (v.'.$v['version'].')';
            }
            $args=array('label'=>$tcm->Lang->L('Editor.trackMode_1', $ecommerce));
            $tcm->Form->radio('trackMode', $snippet['trackMode'], $v['id'], $args);
        }
        $tcm->Form->tagNew=FALSE;

    }
    $tcm->Form->divEnds();

    $args=array('id'=>'box-track-conversion');
    $tcm->Form->divStarts($args);
    {
        $tcm->Form->p('In which products do you want to insert this code?');
        ?>
        <p style="font-style: italic;"><?php $tcm->Lang->P('Editor.PositionBlocked') ?></p>
        <?php
        foreach($plugins as $k=>$v) {
            $args=array('id'=>'box-track-conversion-'.$v['id'], 'class'=>'box-track-conversion');
            $tcm->Form->divStarts($args);
            {
                if($v['id']==TCM_PLUGINS_NO_PLUGINS) {
                    $plugins=$tcm->Ecommerce->getPlugins(FALSE);
                    $ecommerce='';
                    foreach($plugins as $k=>$v) {
                        if($ecommerce!='') {
                            $ecommerce.=', ';
                        }
                        $ecommerce.=$k;
                    }
                    $tcm->Options->pushErrorMessage('Editor.NoEcommerceFound', $ecommerce);
                    $tcm->Options->writeMessages();
                } else {
                    $postType=$tcm->Ecommerce->getCustomPostType($v['id']);
                    $keyActive='CTC_'.$v['id'].'_Active';
                    $label=$tcm->Lang->L('Editor.EcommerceCheck', $v['name'], $v['version']);

                    if($postType!='') {
                        $args = array('post_type' => $postType, 'all' => TRUE);
                        $values = $tcm->Utils->query(TCM_QUERY_POSTS_OF_TYPE, $args);
                        $keyArray='CTC_'.$v['id'].'_ProductsIds';
                        if(count($snippet[$keyArray])==0) {
                            //when enabled default selected -1
                            $snippet[$keyArray]=array(-1);
                        }

                        $args=array('label'=>$label, 'class'=>'tcm-select tcmLineTags');
                        $tcm->Form->labels=FALSE;
                        $tcm->Form->select($keyArray, $snippet[$keyArray], $values, TRUE, $args);
                        $tcm->Form->labels=TRUE;
                    } else {
                        $args=array('label'=>$label);
                        $tcm->Form->checkbox($keyActive, $snippet[$keyActive], 1, $args);
                    }
                }
            }
            $tcm->Form->divEnds();
        }
    }
    $tcm->Form->divEnds();

    $args=array('id'=>'box-track-code');
    $tcm->Form->divStarts($args);
    {
        $tcm->Form->p('In which page do you want to insert this code?');
        $tcm->Form->radio('trackPage', $snippet['trackPage'], TCM_TRACK_PAGE_ALL);
        $tcm->Form->radio('trackPage', $snippet['trackPage'], TCM_TRACK_PAGE_SPECIFIC);

        //, 'style'=>'margin-top:10px;'
        $args=array('id'=>'tcm-include-div');
        $tcm->Form->divStarts($args);
        {
            $tcm->Form->p('Include tracking code in which pages?');
            tcm_formOptions('include', $snippet);
        }
        $tcm->Form->divEnds();

        $args=array('id'=>'tcm-except-div');
        $tcm->Form->divStarts($args);
        {
            $tcm->Form->p('Do you want to exclude some specific pages?');
            tcm_formOptions('except', $snippet);
        }
        $tcm->Form->divEnds();
    }
    $tcm->Form->divEnds();

    $tcm->Form->nonce('tcm_nonce', 'tcm_nonce');
    tcm_notice_pro_features();
    $tcm->Form->submit('Save');
    if($id>0) {
        $tcm->Form->delete($id);
    }
    $tcm->Form->formEnds();
}

function tcm_notice_pro_features() {
    global $tcm;

    ?>
    <br/>
    <div class="message updated below-h2" style="max-width:600px;">
        <div style="height:10px;"></div>
        <?php
        $i=1;
        while($tcm->Lang->H('Notice.ProHeader'.$i)) {
            $tcm->Lang->P('Notice.ProHeader'.$i);
            echo '<br/>';
            ++$i;
        }
        $i=1;
        ?>
        <br/>
        <?php

        $options = array('public' => TRUE, '_builtin' => FALSE);
        $q=get_post_types($options, 'names');
        if(is_array($q) && count($q)>0) {
            sort($q);
            $q=implode(', ', $q);
            $q='(<b>'.$q.'</b>)';
        } else {
            $q='';
        }

        while($tcm->Lang->H('Notice.ProFeature'.$i)) { ?>
            <div style="clear:both; margin-top: 2px;"></div>
            <div style="float:left; vertical-align:middle; height:24px; margin-right:5px;">
                <img src="<?php echo TCM_PLUGIN_IMAGES?>tick.png" />
            </div>
            <div style="float:left; vertical-align:middle; height:24px;">
                <?php $tcm->Lang->P('Notice.ProFeature'.$i, $q)?>
            </div>
            <?php ++$i;
        }
        ?>
        <div style="clear:both;"></div>
        <div style="height:10px;"></div>
        <div style="float:right;">
            <?php
            $url='http://intellywp.com/tracking-code-manager/?utm_source=free-users&utm_medium=tcm-cta&utm_campaign=TCM';
            ?>
            <a href="<?php echo $url?>" target="_blank">
                <b><?php $tcm->Lang->P('Notice.ProCTA')?></b>
            </a>
        </div>
        <div style="height:10px; clear:both;"></div>
    </div>
    <br/>
<?php }

function tcm_formOptions($prefix, $snippet) {
    global $tcm;

    $types=$tcm->Utils->query(TCM_QUERY_POST_TYPES);
    foreach($types as $v) {
        $args = array('post_type' => $v['name'], 'all' => TRUE);
        $values = $tcm->Utils->query(TCM_QUERY_POSTS_OF_TYPE, $args);

        $keyActive=$prefix.'PostsOfType_'.$v['name'].'_Active';
        $keyArray=$prefix.'PostsOfType_'.$v['name'];
        if($snippet[$keyActive]==0 && count($snippet[$keyArray])==0 && $prefix!='except') {
            //when enabled default selected -1
            $snippet[$keyArray]=array(-1);
        }
        $tcm->Form->checkSelect($keyActive, $keyArray, $snippet, $values);
    }
}
