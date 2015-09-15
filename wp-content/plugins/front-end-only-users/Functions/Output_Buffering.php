<?php

function add_ob_start() {
    ob_start();
}
add_action('init', 'add_ob_start');

function flush_ob_end() {
    ob_end_flush();
}
add_action('wp_footer', 'flush_ob_end');