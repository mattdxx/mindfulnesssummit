<?php
session_start();
header("Content-type: text/css");
if(isset($_SESSION['ert_css']) && is_array( $_SESSION['ert_css']) && count($_SESSION['ert_css'])){
    foreach( $_SESSION['ert_css'] as $val){
        echo $val;
    }
}
