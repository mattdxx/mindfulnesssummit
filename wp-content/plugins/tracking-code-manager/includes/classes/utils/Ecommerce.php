<?php
if (!defined('ABSPATH')) exit;

class TCM_Ecommerce {
    function __construct() {
        add_action('woocommerce_thankyou', array(&$this, 'wooCommerceThankYou'));
        add_action('edd_payment_receipt_after_table', array(&$this, 'eddThankYou'));
        add_action('wpsc_transaction_result_cart_item', array(&$this, 'eCommerceThankYou'));
    }

    public function getCustomPostType($pluginId) {
        $result='';
        switch (intval($pluginId)) {
            case TCM_PLUGINS_WOOCOMMERCE:
                $result='product';
                break;
            case TCM_PLUGINS_EDD:
                $result='download';
                break;
            case TCM_PLUGINS_WP_ECOMMERCE:
                $result='wpsc-product';
                break;
        }
        return $result;
    }

    //WPSC_Purchase_Log_Customer_HTML_Notification
    function eCommerceThankYou($order) {
        global $tcm;

        $orderId=intval($order['purchase_id']);
        $tcm->Log->debug('Ecommerce: ECOMMERCE THANKYOU');
        $tcm->Log->debug('Ecommerce: NEW ECOMMERCE ORDERID=%s', $orderId);

        $order=new WPSC_Purchase_Log($orderId);
        $items=$order->get_cart_contents();
        $productsIds=array();
        foreach ($items as $v) {
            if(isset($v->prodid)) {
                $k=intval($v->prodid);
                if($k) {
                    $v=$v->name;
                    $productsIds[]=$k;
                    $tcm->Log->debug('Ecommerce: ITEM %s=%s IN CART', $k, $v);
                }
            }
        }

        $args=array(
            'pluginId'=>TCM_PLUGINS_WP_ECOMMERCE
            , 'productsIds'=>$productsIds
            , 'categoriesIds'=>array()
            , 'tagsIds'=>array()
        );
        $tcm->Options->pushConversionSnippets($args);
        return '';
    }

    function eddThankYou($payment, $edd_receipt_args=NULL) {
        global $tcm;

        $tcm->Log->debug('Ecommerce: EDD THANKYOU');
        $tcm->Log->debug('Ecommerce: NEW EDD ORDERID=%s', $payment->ID);
        $cart=edd_get_payment_meta_cart_details($payment->ID, TRUE);
        $productsIds=array();
        foreach ($cart as $key=>$item) {
            if(isset($item['id'])) {
                $k=intval($item['id']);
                if($k) {
                    $v=$item['name'];
                    $productsIds[]=$k;
                    $tcm->Log->debug('Ecommerce: ITEM %s=%s IN CART', $k, $v);
                }
            }
        }

        $args=array(
            'pluginId'=>TCM_PLUGINS_EDD
            , 'productsIds'=>$productsIds
            , 'categoriesIds'=>array()
            , 'tagsIds'=>array()
        );
        $tcm->Options->pushConversionSnippets($args);
    }
    function wooCommerceThankYou($orderId) {
        global $tcm;
        $tcm->Log->debug('Ecommerce: WOOCOMMERCE THANKYOU');

        $order=new WC_Order($orderId);
        $items=$order->get_items();
        $tcm->Log->debug('Ecommerce: NEW WOOCOMMERCE ORDERID=%s', $orderId);
        $productsIds=array();
        foreach($items as $k=>$v) {
            $k=intval($v['product_id']);
            if($k>0) {
                $v=$v['name'];
                $tcm->Log->debug('Ecommerce: ITEM %s=%s IN CART', $k, $v);
                $productsIds[]=$k;
            }
        }

        $args=array(
            'pluginId'=>TCM_PLUGINS_WOOCOMMERCE
            , 'productsIds'=>$productsIds
            , 'categoriesIds'=>array()
            , 'tagsIds'=>array()
        );
        $tcm->Options->pushConversionSnippets($args);
    }

    function getActivePlugins() {
        return $this->getPlugins(TRUE);
    }
    function getPlugins($onlyActive=TRUE) {
        global $tcm;

        $array=array();
        $array[]=TCM_PLUGINS_WOOCOMMERCE;
        $array[]=TCM_PLUGINS_EDD;
        $array[]=TCM_PLUGINS_WP_ECOMMERCE;
        /*
        $array[]=TCM_PLUGINS_WP_SPSC;
        $array[]=TCM_PLUGINS_S2MEMBER;
        $array[]=TCM_PLUGINS_MEMBERS;
        $array[]=TCM_PLUGINS_CART66;
        $array[]=TCM_PLUGINS_ESHOP;
        $array[]=TCM_PLUGINS_JIGOSHOP;
        $array[]=TCM_PLUGINS_MARKETPRESS;
        $array[]=TCM_PLUGINS_SHOPP;
        $array[]=TCM_PLUGINS_SIMPLE_WP_ECOMMERCE;
        $array[]=TCM_PLUGINS_CF7;
        $array[]=TCM_PLUGINS_GRAVITY;
        */

        $array=$tcm->Plugin->getPlugins($array, $onlyActive);
        return $array;
    }
}
