<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class TCM_Language {
    var $domain;
    function load($domain, $file) {
        $this->domain=$domain;
        if(!file_exists($file)) {
            return;
        }
        $file=file_get_contents($file);
        if($file!=NULL && strlen($file)>0) {
            $bundle=array();
            $file=str_replace("\r\n", "\n", $file);
            $file=str_replace("\n\n", "\n", $file);
            $file=explode("\n", $file);

            foreach($file as $row) {
                $index=strpos($row, "=");
                if($index===FALSE) continue;

                $k=trim(substr($row, 0, $index));
                $v=trim(substr($row, $index+1));
                $bundle[$k]=$v;
            }

            global $wp_session;
            $wp_session['LanguageBundle_'.$domain]=$bundle;
        }
    }
    //echo the $tcm->Lang->L result
    function P($key, $v1=NULL, $v2=NULL, $v3=NULL, $v4=NULL, $v5=NULL) {
        $what=$this->L($key, $v1, $v2, $v3, $v4, $v5);
        echo $what;
    }
    //verify if the key is defined or not
    function H($key) {
        global $wp_session;
        $bundle=$wp_session['LanguageBundle_'.$this->domain];
        if($bundle==NULL || count($bundle)==0) {
            return FALSE;
        }

        $result=FALSE;
        if(isset($bundle[$key])) {
            $result=TRUE;
        } elseif(isset($bundle[$key.'1'])) {
            $result=TRUE;
        } else {
            //special way to call this function passing arguments
            //WTF_something means key=WTF and something as first argument
            $s=strpos($result, '_');
            if ($s!==FALSE) {
                $text = substr($result, 0, $s);
                $value = substr($result, $s + 1);
                $e = strrpos($value, '_');
                if ($e!==FALSE) {
                    $text .= substr($value, $e + 1);
                    $value = substr($value, 0, $e);
                }
                if (isset($bundle[$text])) {
                    $result = TRUE;
                }
            }
        }
        return $result;
    }
    //read the key from a text file with its translation. Try to translate using __(
    function L($key, $v1=NULL, $v2=NULL, $v3=NULL, $v4=NULL, $v5=NULL) {
        global $wp_session;
        $bundle=$wp_session['LanguageBundle_'.$this->domain];
        $result = $key;
        $args = array($v1, $v2, $v3, $v4, $v5);

        if($bundle==NULL || count($bundle)==0) {
            $result=__($result, $this->domain);
        } else {
            //i use the file to store the translations without writing it inside the code
            if (isset($bundle[$key])) {
                $result = $bundle[$key];
                $result = __($result, $this->domain);
            } elseif (isset($bundle[$key . '1'])) {
                $result = '';
                $n = 1;
                while (isset($bundle[$key . $n])) {
                    if ($result != '') {
                        $result .= '<br/>';
                    }
                    $result .= __($bundle[$key . $n], $this->domain);
                    ++$n;
                }
            } else {
                //special way to call this function passing arguments
                //WTF_something means key=WTF and something as first argument
                $s=strpos($result, '_');
                if ($s!==FALSE) {
                    $text = substr($result, 0, $s);
                    $value = substr($result, $s + 1);
                    $e = strrpos($value, '_');
                    if ($e!==FALSE) {
                        $text .= substr($value, $e + 1);
                        $value = substr($value, 0, $e);
                    }
                    if (isset($bundle[$text])) {
                        $result = $bundle[$text];
                        $args=array($value);
                    }
                }
                $result = __($result, $this->domain);
            }
        }
        //here i translate it using WP
        foreach($args as $k=>$v) {
            $k='{'.$k.'}';
            while(strpos($result, $k)!==FALSE) {
                $result=str_replace($k, $v, $result);
            }
        }
        return $result;
    }
}
