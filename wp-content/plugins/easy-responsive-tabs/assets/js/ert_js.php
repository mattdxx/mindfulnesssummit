<?php
session_start();
header("Content-type: text/javascript");
if(isset($_SESSION['ert_js']) && is_array( $_SESSION['ert_js']) && count($_SESSION['ert_js'])){
    echo 'jQuery(document).ready(function() {';
    foreach($_SESSION['ert_js'] as $val){
        echo $val;
    }
    echo '});';
}
