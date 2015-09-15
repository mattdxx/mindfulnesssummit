<?php

global $addthis_addjs;

$addthis_smart_layer_include = 'addthis.layers(' . get_option('smart_layer_settings') . ');';
$addthis_addjs->addAfterScript($addthis_smart_layer_include);
$addthis_addjs->output_script();

?>
