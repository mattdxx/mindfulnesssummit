<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

define('TCM_TRACK_MODE_CODE', 0);
//others track mode are plugin enumeration
define('TCM_TRACK_PAGE_ALL', 0);
define('TCM_TRACK_PAGE_SPECIFIC', 1);

class TCM_Manager {
    public function __construct() {
    }

    public function exists($name) {
        $snippets = $this->values();
        $result = NULL;
        $name=strtoupper($name);
        if (isset($snippets[$name])) {
            $result=$snippets[$name];
        }
        return $result;
    }

    public function get($id, $new = FALSE) {
        global $tcm;

        $snippet=$tcm->Options->getSnippet($id);
        if (!$snippet && $new) {
            $snippet=array();
            $snippet['active']=1;
            $snippet['trackMode']=-1;
            $snippet['trackPage']=-1;
        }

        $snippet=$this->sanitize($id, $snippet);
        return $snippet;
    }

    public function sanitize($id, $snippet) {
        global $tcm;
        if($snippet==NULL || !is_array($snippet)) return;

        $page=0;
        if(isset($snippet['includeEverywhereActive'])) {
            $page=(intval($snippet['includeEverywhereActive']==1) ? 0 : 1);
        }
        $defaults=array(
            'id'=>$id
            , 'active'=>0
            , 'name'=>''
            , 'code'=>''
            , 'position'=>TCM_POSITION_HEAD
            , 'trackMode'=>TCM_TRACK_MODE_CODE
            , 'trackPage'=>$page
            , 'includeEverywhereActive'=>0
        );

        $types=$tcm->Utils->query(TCM_QUERY_POST_TYPES);
        foreach($types as $v) {
            $defaults['includePostsOfType_'.$v['name'].'_Active']=0;
            $defaults['includePostsOfType_'.$v['name']]=array();
            $defaults['exceptPostsOfType_'.$v['name'].'_Active']=0;
            $defaults['exceptPostsOfType_'.$v['name']]=array();
        }

        $types=$tcm->Utils->query(TCM_QUERY_CONVERSION_PLUGINS);
        foreach($types as $v) {
            //CP stands for ConversionTrackingCode
            //$defaults['CTC_'.$v['id'].'_Active']=0;
            $defaults['CTC_'.$v['id'].'_ProductsIds']=array();
            $defaults['CTC_'.$v['id'].'_CategoriesIds']=array();
            $defaults['CTC_'.$v['id'].'_TagsIds']=array();
        }
        $snippet=$tcm->Utils->parseArgs($snippet, $defaults);
        //$snippet['includeLastPosts'] = intval($snippet['includeLastPosts']);

        foreach ($snippet as $k => $v) {
            if (stripos($k, 'active') !== FALSE) {
                $snippet[$k]=intval($v);
            } elseif (is_array($v)) {
                switch ($k) {
                    /*
                    case 'includePostsTypes':
                    case 'excludePostsTypes':
                        //keys are string and not number
                        $result = $this->uarray($snippet, $k, FALSE);
                        break;
                    */
                    default:
                        //keys are number
                        $result = $this->uarray($snippet, $k, TRUE);
                        break;
                }
            }
        }
        $snippet['code']=trim($snippet['code']);
        $snippet['position']=intval($snippet['position']);
        if($snippet['trackMode']==='') {
            $snippet['trackMode']=TCM_TRACK_MODE_CODE;
        } else {
            $snippet['trackMode']=intval($snippet['trackMode']);
        }
        if($snippet['trackPage']==='') {
            $snippet['trackPage']=$page;
        } else {
            $snippet['trackPage']=intval($snippet['trackPage']);
        }

        $snippet['includeEverywhereActive']=0;
        if($snippet['trackPage']==TCM_TRACK_PAGE_ALL) {
            $snippet['includeEverywhereActive']=1;
        }

        $code=strtolower($snippet['code']);
        $cnt=substr_count($code, '<iframe')+substr_count($code, '<script');
        if($cnt<=0) {
            $cnt=1;
        }
        $snippet['codesCount']=$cnt;
        return $snippet;
    }
    private function uarray($snippet, $key, $isInteger = TRUE) {
        $array = $snippet[$key];
        if (!is_array($array)) {
            $array = explode(',', $array);
        }

        if ($isInteger) {
            for ($i = 0; $i < count($array); $i++) {
                $array[$i] = intval($array[$i]);
            }
        }

        $array = array_unique($array);
        $snippet[$key] = $array;
        return $snippet;
    }

    public function rc() {
        global $tcm;
        $result = 6-$this->codesCount();
        return $result;
    }

    //add or update a snippet (html tracking code)
    public function put($id, $snippet) {
        global $tcm;

        if ($id == '' || intval($id) <= 0) {
            //if is a new code create a new unique id
            $id = $this->getLastId() + 1;
            $snippet['id'] = $id;
        }
        $snippet=$this->sanitize($id, $snippet);
        $tcm->Options->setSnippet($id, $snippet);

        $keys = $this->keys();
        if (is_array($keys) && !in_array($id, $keys)) {
            $keys[] = $id;
            $this->keys($keys);
        }
        return $snippet;
    }

    //remove the id snippet
    public function remove($id) {
        global $tcm;
        $tcm->Options->removeSnippet($id);
        $keys=$this->keys();
        $result = FALSE;
        if (is_array($keys) && in_array($id, $keys)) {
            $keys = array_diff($keys, array($id));
            $this->keys($keys);
            $result = TRUE;
        }
        return $result;
    }

    //verify if match with this snippet
    private function matchSnippet($postId, $postType, $categoriesIds, $tagsIds, $prefix, $snippet) {
        global $tcm;

        $include=FALSE;
        $postId=intval($postId);
        if($postId>0) {
            $what=$prefix.'PostsOfType_'.$postType;
            if(!$include && isset($snippet[$what.'_Active']) && isset($snippet[$what]) && $snippet[$what.'_Active'] && $tcm->Utils->inArray($postId, $snippet[$what])) {
                $tcm->Log->debug('MATCH=%s SNIPPET=%s[%s] DUE TO POST=%s OF TYPE=%s IN [%s]'
                    , $prefix, $snippet['id'], $snippet['name'], $postId, $postType, $snippet[$what]);
                $include=TRUE;
            }
        }

        return $include;
    }

    public function writeCodes($position) {
        global $tcm;

        $text='';
        switch ($position) {
            case TCM_POSITION_HEAD:
                $text='HEAD';
                break;
            case TCM_POSITION_BODY:
                $text='BODY';
                break;
            case TCM_POSITION_FOOTER:
                $text='FOOTER';
                break;
            case TCM_POSITION_CONVERSION:
                $text='CONVERSION';
                break;
        }

        $post=$tcm->Options->getPostShown();
        $args=array('field'=>'code');
        $codes=$tcm->Manager->getCodes($position, $post, $args);
        if(is_array($codes) && count($codes)>0) {
            echo "\n<!--BEGIN: TRACKING CODE MANAGER BY INTELLYWP.COM IN $text//-->";
            foreach($codes as $v) {
                echo "\n$v";
            }
            echo "\n<!--END: https://wordpress.org/plugins/tracking-code-manager IN $text//-->";
        }
    }

    //return snippets that match with options
    public function getConversionSnippets($options=NULL) {
        global $tcm;

        $defaults=array(
            'pluginId'=>0
            , 'categoriesIds'=>array()
            , 'productsIds'=>array()
            , 'tagsIds'=>array()
        );
        $options=$tcm->Utils->parseArgs($options, $defaults);

        $result=array();
        $pluginId=intval($options['pluginId']);
        $ids=$this->keys();

        foreach($ids as $id) {
            $snippet=$this->get($id);
            if($snippet && $snippet['trackMode']>0 && $snippet['trackMode']==$pluginId) {
                $match=FALSE;

                $match=($match || $this->matchConversion($snippet, $pluginId, 'ProductsIds', $options['productsIds']));
                $match=($match || $this->matchConversion($snippet, $pluginId, 'CategoriesIds', $options['categoriesIds']));
                $match=($match || $this->matchConversion($snippet, $pluginId, 'TagsIds', $options['tagsIds']));
                if(!$match) {
                    //no selected so..all match! :)
                    if(count($snippet['CTC_'.$pluginId.'_ProductsIds'])==0
                        && count($snippet['CTC_'.$pluginId.'_CategoriesIds'])==0
                        && count($snippet['CTC_'.$pluginId.'_TagsIds'])==0) {
                        $match=TRUE;
                    }
                }

                if($match) {
                    $result[]=$snippet;
                }
            }
        }
        return $result;
    }
    private function matchConversion($snippet, $pluginId, $suffix, $array) {
        global $tcm;

        $v='CTC_'.$pluginId.'_'.$suffix;
        if(isset($snippet[$v])) {
            $v=$snippet[$v];
        } else {
            $v=array();
        }

        $result=$tcm->Utils->inArray($array, $v);
        return $result;
    }

    //from a post retrieve the html code that is needed to insert into the page code
    public function getCodes($position, $post, $args=array()) {
        global $tcm;

        $defaults=array('field'=>'code');
        $args=$tcm->Utils->parseArgs($args, $defaults);

        $postId=0;
        $postType='page';
        $tagsIds=array();
        $categoriesIds=array();
        if($post) {
            $postId=$tcm->Utils->get($post, 'ID', FALSE);
            if($postId===FALSE) {
                $postId=$tcm->Utils->get($post, 'post_ID');
            }
            $postType=$tcm->Utils->get($post, 'post_type');
        }

        $tcm->Options->clearSnippetsWritten();
        if($position==TCM_POSITION_CONVERSION) {
            //write snippets previously appended
            $ids=$tcm->Options->getConversionSnippetIds();
            foreach($ids as $id) {
                $snippet=$tcm->Manager->get($id);
                if($snippet) {
                    $tcm->Options->pushSnippetWritten($snippet);
                }
            }
        } else {
            $keys = $this->keys();
            foreach ($keys as $id) {
                $v = $this->get($id);
                if (!$v || ($position > -1 && $v['position'] != $position) || $v['code'] == '' || !$v['active']) {
                    continue;
                }
                if ($v['trackMode']!=TCM_TRACK_MODE_CODE) {
                    continue;
                }
                if ($tcm->Options->hasSnippetWritten($v)) {
                    $tcm->Log->debug('SKIPPED SNIPPET=%s[%s] DUE TO ALREADY WRITTEN', $v['id'], $v['name']);
                    continue;
                }

                $match = FALSE;
                if (!$match && ($v['trackPage']==TCM_TRACK_PAGE_ALL || $v['includeEverywhereActive'])) {
                    $tcm->Log->debug('INCLUDED SNIPPET=%s[%s] DUE TO EVERYWHERE', $v['id'], $v['name']);
                    $match = TRUE;
                }
                if (!$match && $postId > 0 && $this->matchSnippet($postId, $postType, $categoriesIds, $tagsIds, 'include', $v)) {
                    $match = TRUE;
                }

                if ($match && $postId > 0) {
                    if ($this->matchSnippet($postId, $postType, $categoriesIds, $tagsIds, 'except', $v)) {
                        $tcm->Log->debug('FOUND AT LEAST ON EXCEPT TO EXCLUDE SNIPPET=%s [%s]', $v['id'], $v['name']);
                        $match = FALSE;
                    }
                }

                if ($match) {
                    $tcm->Options->pushSnippetWritten($v);
                }
            }
        }

        //obtain result as snippets or array of one field (tipically "id")
        $result=$tcm->Options->getSnippetsWritten();
        if ($args['field']!='all') {
            $array=array();
            foreach($result as $k=>$v) {
                $k=$args['field'];
                if(isset($v[$k])) {
                    $array[]=$v[$k];
                } else {
                    $tcm->Log->error('SNIPPET=%s [%s] WITHOUT FIELD=%s', $v['id'], $v['name'], $k);
                }
            }
            $result=$array;
        }
        return $result;
    }

    //ottiene o salva tutte le chiavi dei tracking code utilizzati ordinati per id
    public function keys($keys=NULL) {
        global $tcm;

        if (is_array($keys)) {
            $tcm->Options->setSnippetList($keys);
            $result=$keys;
        } else {
            $result=$tcm->Options->getSnippetList();
        }

        if (!is_array($result)) {
            $result = array();
        } else {
            sort($result);
        }
        return $result;
    }

    //ottiene il conteggio attuale dei tracking code
    public function count() {
        $result = count($this->keys());
        return $result;
    }
    public function codesCount() {
        $result=0;
        $ids=$this->keys();
        foreach($ids as $id) {
            $snippet=$this->get($id);
            if($snippet) {
                $result+=1;
                /*
                if($snippet['codesCount']>0) {
                    $result+=intval($snippet['codesCount']);
                } else {
                    $result+=1;
                }
                */
            }
        }
        return $result;
    }
    public function getLastId() {
        $result = 0;
        $list = $this->keys();
        foreach ($list as $v) {
            $v = intval($v);
            if ($v > $result) {
                $result = $v;
            }
        }
        return $result;
    }

    //ottiene tutti i tracking code ordinati per nome
    public function values()  {
        $keys = $this->keys();
        $result = array();
        foreach ($keys as $k) {
            $v = $this->get($k);
            $result[strtoupper($v['name'])] = $v;
        }
        ksort($result);
        return $result;
    }
}