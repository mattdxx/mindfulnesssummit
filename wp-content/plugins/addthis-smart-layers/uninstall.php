<?php

## This file removes all saved settings from the database.

if(WP_UNINSTALL_PLUGIN){

    delete_option('smart_layer_activated');
    delete_option('smart_layer_settings');
    delete_option('smart_layer_settings_advanced');
    delete_option('smart_layer_profile');
} 