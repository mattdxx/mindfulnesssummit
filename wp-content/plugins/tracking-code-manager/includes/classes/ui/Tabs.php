<?php
class TCM_Tabs {
    private $tabs = array();

    function __construct() {
    }
    public function init() {
        global $tcm;
        if($tcm->Utils->isAdminUser()) {
            add_filter('plugin_action_links', array(&$this, 'pluginActions'), 10, 2);
            add_action('admin_menu', array(&$this, 'attachMenu'));
            if($tcm->Utils->isPluginPage()) {
                add_action('admin_enqueue_scripts',  array(&$this, 'enqueueScripts'));
            }
        }
    }

    function attachMenu() {
        global $tcm;
        if(!$tcm->Plugin->isActive(TCM_PLUGINS_TRACKING_CODE_MANAGER_PRO)) {
            $name='Tracking Code Manager';
            add_submenu_page('options-general.php'
                , $name, $name
                , 'manage_options', TCM_PLUGIN_SLUG, array(&$this, 'showTabPage'));
        }
    }
    function pluginActions($links, $file) {
        global $tcm;
        if($file==TCM_PLUGIN_SLUG.'/index.php'){
            $settings = "<a href='".TCM_PAGE_MANAGER."'>" . $tcm->Lang->L('Settings') . '</a> ';
            $url='http://intellywp.com/tracking-code-manager/?utm_source=free-users&utm_medium=tcm-plugins&utm_campaign=TCM';
            $premium = "<a href='".$url."'>" . $tcm->Lang->L('PREMIUM') . '</a> ';
            $links = array_merge(array($settings, $premium), $links);
        }
        return $links;
    }
    function enqueueScripts() {
        wp_enqueue_script('jquery');
        wp_enqueue_script('suggest');
        wp_enqueue_script('jquery-ui-autocomplete');

        $this->wpEnqueueStyle('assets/css/style.css');
        $this->wpEnqueueStyle('assets/deps/select2-3.5.2/select2.css');
        $this->wpEnqueueScript('assets/deps/select2-3.5.2/select2.min.js');
        $this->wpEnqueueScript('assets/deps/starrr/starrr.js');

        $p='?p='.TCM_PLUGIN_VERSION;
        wp_register_script('tcm-autocomplete', plugins_url('assets/js/tcm-autocomplete.js', __FILE__ ).$p, array('jquery', 'jquery-ui-autocomplete'), '1.0', FALSE);
        wp_localize_script('tcm-autocomplete', 'TCMAutocomplete', array('url' => admin_url('admin-ajax.php')
        ));
        wp_enqueue_script('tcm-autocomplete');
    }
    function wpEnqueueStyle($uri, $name='') {
        if($name=='') {
            $name=explode('/', $uri);
            $name=$name[count($name)-1];
            $dot=strrpos($name, '.');
            if($dot!==FALSE) {
                $name=substr($name, 0, $dot);
            }
            $name=TCM_PLUGIN_PREFIX.'_'.$name;
        }

        $v='?v='.TCM_PLUGIN_VERSION;
        wp_enqueue_style($name, TCM_PLUGIN_URI.$uri.$v);
    }
    function wpEnqueueScript($uri, $name='', $version=FALSE) {
        if($name=='') {
            $name=explode('/', $uri);
            $name=$name[count($name)-1];
            $dot=strrpos($name, '.');
            if($dot!==FALSE) {
                $name=substr($name, 0, $dot);
            }
            $name=TCM_PLUGIN_PREFIX.'_'.$name;
        }

        $v='?v='.TCM_PLUGIN_VERSION;
        $deps=array();
        wp_enqueue_script($name, TCM_PLUGIN_URI.$uri.$v, $deps, $version, FALSE);
    }

    function showTabPage() {
        global $tcm;

        $defaultTab=TCM_TAB_MANAGER;
        if($tcm->Options->isShowWhatsNew()) {
            $tab=TCM_TAB_WHATS_NEW;
            $defaultTab=$tab;
            $this->tabs[TCM_TAB_WHATS_NEW]=$tcm->Lang->L('What\'s New');
            //$this->tabs[TCM_TAB_MANAGER]=$tcm->Lang->L('Start using the plugin!');
        } else {
            if($tcm->Plugin->isActive(TCM_PLUGINS_TRACKING_CODE_MANAGER_PRO)) {
                $this->tabs[TCM_TAB_MANAGER]=$tcm->Lang->L('Manager');
                $tab=TCM_TAB_MANAGER;
                $defaultTab=$tab;
            } else {
                $id=intval($tcm->Utils->qs('id', 0));
                $tab=$tcm->Utils->qs('tab', $defaultTab);

                if($id>0 || $tcm->Manager->rc()>0) {
                    $this->tabs[TCM_TAB_EDITOR]=$tcm->Lang->L($id>0 && $tab==TCM_TAB_EDITOR ? 'Edit' : 'Add new');
                } elseif($tab==TCM_TAB_EDITOR) {
                    $tab=TCM_TAB_MANAGER;
                }
                $this->tabs[TCM_TAB_MANAGER]=$tcm->Lang->L('Manager');
                $this->tabs[TCM_TAB_SETTINGS]=$tcm->Lang->L('Settings');
                $this->tabs[TCM_TAB_DOCS]=$tcm->Lang->L('Docs & FAQ');
            }
        }

        ?>
        <div class="wrap" style="margin:5px;">
            <?php
            $this->showTabs($defaultTab);
            $header='';
            switch ($tab) {
                case TCM_TAB_EDITOR:
                    $header=($id>0 ? 'Edit' : 'Add');
                    break;
                case TCM_TAB_WHATS_NEW:
                    $header='';
                    break;
                case TCM_TAB_MANAGER:
                    $header='Manager';
                    break;
                case TCM_TAB_SETTINGS:
                    $header='Settings';
                    break;
            }

            if($header!='' && $tcm->Lang->H($header.'Title')) { ?>
                <h2><?php $tcm->Lang->P($header . 'Title', TCM_PLUGIN_VERSION) ?></h2>
                <?php if ($tcm->Lang->H($header . 'Subtitle')) { ?>
                    <div><?php $tcm->Lang->P($header . 'Subtitle') ?></div>
                <?php } ?>
                <br/>
            <?php }

            tcm_ui_first_time();
            switch ($tab) {
                case TCM_TAB_WHATS_NEW:
                    tcm_ui_whats_new();
                    break;
                case TCM_TAB_EDITOR:
                    tcm_ui_editor();
                    break;
                case TCM_TAB_MANAGER:
                    tcm_ui_manager();
                    break;
                case TCM_TAB_SETTINGS:
                    tcm_ui_track();
                    tcm_ui_settings();
                    break;
            } ?>
        </div>
    <?php }

    function showTabs($defaultTab) {
        global $tcm;
        $tab=$tcm->Check->of('tab', $defaultTab);
        if($tab==TCM_TAB_DOCS) {
            $tcm->Utils->redirect(TCM_TAB_DOCS_URI);
        }
        if($tcm->Options->isShowWhatsNew()) {
            $tab=TCM_TAB_WHATS_NEW;
        }

        ?>
        <h2 class="nav-tab-wrapper" style="float:left; width:97%;">
            <?php
            foreach ($this->tabs as $k=>$v) {
                $active=($tab==$k ? 'nav-tab-active' : '');
                $style='';
                if($tcm->Options->isShowWhatsNew() && $k==TCM_TAB_MANAGER) {
                    $active='';
                    $style='background-color:#F2E49B';
                }
                ?>
                <a style="float:left; margin-left:10px; <?php echo $style?>" class="nav-tab <?php echo $active?>" href="?page=<?php echo TCM_PLUGIN_SLUG?>&tab=<?php echo $k?>"><?php echo $v?></a>
            <?php
            }
            ?>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css">
            <style>
                .starrr {display:inline-block}
                .starrr i{font-size:16px;padding:0 1px;cursor:pointer;color:#2ea2cc;}
            </style>
            <div style="float:right; display:none;" id="rate-box">
                <span style="font-weight:700; font-size:13px; color:#555;"><?php $tcm->Lang->P('Rate us')?></span>
                <div id="tcm-rate" class="starrr" data-connected-input="tcm-rate-rank"></div>
                <input type="hidden" id="tcm-rate-rank" name="tcm-rate-rank" value="5" />
                <?php  $tcm->Utils->twitter('intellywp') ?>
            </div>
            <script>
                jQuery(function() {
                    jQuery(".starrr").starrr();
                    jQuery('#tcm-rate').on('starrr:change', function(e, value){
                        var url='https://wordpress.org/support/view/plugin-reviews/tracking-code-manager?rate=5#postform';
                        window.open(url);
                    });
                    jQuery('#rate-box').show();
                });
            </script>
        </h2>
        <div style="clear:both;"></div>
    <?php }
}
