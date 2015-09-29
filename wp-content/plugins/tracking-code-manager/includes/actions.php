<?php
/**
 * Front-end Actions
 *
 * @package     EDD
 * @subpackage  Functions
 * @copyright   Copyright (c) 2015, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.8.1
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Hooks EDD actions, when present in the $_GET superglobal. Every edd_action
 * present in $_GET is called using WordPress's do_action function. These
 * functions are called on init.
 *
 * @since 1.0
 * @return void
*/
add_action('init', 'tcm_do_action');
function tcm_do_action() {
    global $tcm;

	if (isset($tcm) && isset($tcm->Utils) && $tcm->Utils->qs('tcm_action')) {
        $args=array_merge($_GET, $_POST, $_COOKIE, $_SERVER);
        $name='tcm_'.$tcm->Utils->qs('tcm_action');
        if(has_action($name)) {
            $tcm->Log->debug('EXECUTING ACTION=%s', $name);
            do_action($name, $args);
        } elseif(function_exists($name)) {
            $tcm->Log->debug('EXECUTING FUNCTION=%s DATA=%s', $name, $args);
            call_user_func($name, $args);
        } elseif(strpos($tcm->Utils->qs('tcm_action'), '_')!==FALSE) {
            $pos=strpos($tcm->Utils->qs('tcm_action'), '_');
            $what=substr($tcm->Utils->qs('tcm_action'), 0, $pos);
            $function=substr($tcm->Utils->qs('tcm_action'), $pos+1);

            $class=NULL;
            switch (strtolower($what)) {
                case 'manager':
                    $class=$tcm->Manager;
                    break;
                case 'cron':
                    $class=$tcm->Cron;
                    break;
                case 'tracking':
                    $class=$tcm->Tracking;
                    break;
                case 'properties':
                    $class=$tcm->Options;
                    break;
            }

            if(!$class) {
                $tcm->Log->fatal('NO CLASS FOR=%s IN ACTION=%s', $what, $tcm->Utils->qs('tcm_action'));
            } elseif(!method_exists ($class, $function)) {
                $tcm->Log->fatal('NO METHOD FOR=%s IN CLASS=%s IN ACTION=%s', $function, $what, $tcm->Utils->qs('tcm_action'));
            } else {
                $tcm->Log->debug('METHOD=%s OF CLASS=%s', $function, $what);
                call_user_func(array($class, $function), $args);
            }
        } else {
            $tcm->Log->fatal('NO FUNCTION FOR==%s', $tcm->Utils->qs('tcm_action'));
        }
	}
}
