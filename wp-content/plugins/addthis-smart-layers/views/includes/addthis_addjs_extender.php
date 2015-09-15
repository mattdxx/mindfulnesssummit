<?php

Class AddThis_addjs_extender extends AddThis_addjs{

    var $jsAfterAdd;

    function getAtPluginPromoText(){
        if (! did_action('admin_init') && !  current_filter('admin_init'))
        {
            _doing_it_wrong('getAtPluginPromoText', 'This function should only be called on an admin page load and no earlier the admin_init', 1);
            return null;
        }
        if (apply_filters('addthis_crosspromote', '__return_true'))
        {
            $plugins = get_plugins();
            if (empty($this->_atInstalled))
            {
                foreach($plugins as $plugin)
                {
                    if (substr($plugin['Name'], 0, 7) === 'AddThis')
                        array_push($this->_atInstalled, $plugin['Name']);
                }
            }
            $keys = array_keys($this->_atPlugins);
            $uninstalled = array_diff( $keys, $this->_atInstalled);
            if (empty($uninstalled))
                return false;

            // Get rid of our keys, we just want the names which are the keys elsewhere
            $uninstalled = array_values($uninstalled);

            $string = __('Want to increase your site traffic?  AddThis also has ');
            $count = count($uninstalled);
            if ($count == 1){
                $string .= __('a plugin for ', 'addthis');
                $string .= __( sprintf('<a href="%s" target="_blank">' .$this->_atPlugins[$uninstalled[0]][1] .'</a>', $this->_atPlugins[$uninstalled[0]][0]), 'addthis');
            }  else {
                $string . __('plugins for ');
                
                for ($i = 0; $i < $count; $i++) {
                    $string .= __( sprintf('<strong><a href="%s" target="_blank" >' .$this->_atPlugins[$uninstalled[$i]][1] .'</a></strong>', $this->_atPlugins[$uninstalled[$i]][0]), 'addthis');
                    if ($i < ($count - 2))
                        $string .= ', ';
                    else if ($i == ($count -2))
                        $string .= ' and ';
                    else if ($i == ($count -1))
                        $string .= ' plugins available.';
                    
                }


            }

            return '<p class="addthis_more_promo">' .$string . '</p>';
            





        }
    }

    function addAfterScript($newData){
        $this->jsAfterAdd .= $newData;
    }

    function addAfterToJs(){
        if (! empty($this->jsAfterAdd));
            $this->jsToAdd .= '<script type="text/javascript">' . $this->jsAfterAdd . '</script>';
    }

    function output_script(){
        if ($this->_js_added != true)
        {
            $this->wrapJs();
            $this->addWidgetToJs();
            $this->addAfterToJs();
            echo $this->jsToAdd;
            $this->_js_added = true;
        }
    }

    function output_script_filter($content){
        if ($this->_js_added != true && ! is_admin() && ! is_feed() )
        {
            $this->wrapJs();
            $this->addWidgetToJs();
            $this->addAfterToJs();
            $content = $content . $this->jsToAdd;
            $this->_js_added = true;
        }
        return $content;
    }
}
