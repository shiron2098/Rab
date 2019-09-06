<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Date.php';




class CSVinsertPOS extends Date
{

    const user_pass = "Web123";
    const null = "0";
    const meta_paying_customer = "1";
    const meta_rich_editing = 'TRUE';
    const meta_comment_shortcuts = "FALSE";
    const meta_admin_color = "fresh";
    const meta_use_ssl = "0";
    const meta_show_admin_bar_front = "TRUE";

    private static $array = null;
    private static $arraycolumn = null;

    private static $user_email = null;
    private static $costumer_name = null;
    private static $pos_price = null;
    private static $last_name = null;
    private static $first_name = null;
    private static $billing_first_name = null;
    private static $billing_last_name = null;
    private static $billing_company = null;
    private static $billing_email = null;
    private static $billing_phone = null;
    private static $billing_country = null;
    private static $billing_addr1 = null;
    private static $billing_addr2 = null;
    private static $billing_city = null;
    private static $billing_state = null ;
    private static $billing_zip = null;
    private static $ship_to_first_name = null;
    private static $ship_to_last_name = null;
    private static $ship_to_company = null;
    private static $ship_to_country = null ;
    private static $ship_to_addr1 = null;
    private static $ship_to_addr2 = null;
    private static $ship_to_city = null;
    private static $ship_to_state = null;
    private static $ship_to_zip = null;
    private static $pos_id = null;
    private static $next_delivery_date = null;


    public static function createCsvArray($xml)
    {
        if (!empty($xml) && isset($xml)) {
            static::start($xml);
            static::InsertCSv();
        }
    }

    private static function start($xml)
    {

        $data2 = file_get_contents(self::$pathtofilepos);
        static::$array = [];
        static::$arraycolumn = explode(',', $data2);
        $data = json_encode($xml->attributes());
        $jsondecode = json_decode($data, true);
        foreach ($jsondecode as $xmlstring) {
            foreach ($xmlstring as $key => $value) {
                switch ($key) {
                    case 'user_email':
                        static::$user_email = $value;
                        break;
                    case 'customer_name':
                        static::$costumer_name = $value;
                        break;
                    case 'POS_Price':
                        static::$pos_price = $value;
                        break;
                    case 'last_name':
                        static::$last_name = $value;
                        break;
                    case 'first_name':
                        static::$first_name = $value;
                        break;
                    case 'billing_first_name':
                        static::$billing_first_name = $value;
                        break;
                    case 'billing_last_name':
                        static::$billing_last_name = $value;
                        break;
                    case 'billing_company':
                        static::$billing_company = $value;
                        break;
                    case 'billing_email':
                        static::$billing_email = $value;
                        break;
                    case 'billing_phone':
                        static::$billing_phone = $value;
                        break;
                    case 'billing_country':
                        static::$billing_country = $value;
                        break;
                    case 'billing_addr1':
                        static::$billing_addr1 = $value;
                        break;
                    case 'billing_addr2':
                        static::$billing_addr2 = $value;
                        break;
                    case 'billing_city':
                        static::$billing_city = $value;
                        break;
                    case 'billing_state':
                        static::$billing_state = $value;
                        break;
                    case 'billing_zip':
                        static::$billing_zip = $value;
                        break;
                    case 'ship_to_first_name':
                        static::$ship_to_first_name = $value;
                        break;
                    case 'ship_to_last_name':
                        static::$ship_to_last_name = $value;
                        break;
                    case 'ship_to_company':
                        static::$ship_to_company = $value;
                        break;
                    case 'ship_to_country':
                        static::$ship_to_country = $value;
                        break;
                    case 'ship_to_addr1':
                        static::$ship_to_addr1 = $value;
                        break;
                    case 'ship_to_addr2':
                        static::$ship_to_addr2 = $value;
                        break;
                    case 'ship_to_city':
                        static::$ship_to_city = $value;
                        break;
                    case 'ship_to_state':
                        static::$ship_to_state = $value;
                        break;
                    case 'ship_to_zip':
                        static::$ship_to_zip = $value;
                        break;
                    case 'next_delivery_date':
                        static::$next_delivery_date = $value;
                        break;
                    case 'pos_id':
                        $value2 = str_replace(';', ':', $value);
                        static::$pos_id = $value2;
                        break;

                }
            }
        }
        return static::$arraycolumn;
    }

    private function InsertCSv()
    {
        if (!empty(static::$arraycolumn) && isset(self::$arraycolumn)) {
            foreach (static::$arraycolumn as $item) {
                switch ($item) {
                    case 'id':
                        array_push(static::$array, static::null);
                        break;
                    case 'user_login':
                        array_push(static::$array, static::$user_email);
                        break;
                    case 'user_pass':
                        array_push(static::$array, static::user_pass);
                        break;
                    case 'user_nicename':
                        array_push(static::$array, static::$costumer_name);
                        break;
                    case 'user_email':
                        array_push(static::$array, static::$user_email);
                        break;
                    case 'user_url':
                        array_push(static::$array, "");
                        break;
                    case 'user_registered':
                        array_push(static::$array, '');
                        break;
                    case 'user_activation_key':
                        array_push(static::$array, '');
                        break;
                    case 'user_status':
                        array_push(static::$array, static::null);
                        break;
                    case 'display_name':
                        array_push(static::$array, static::$costumer_name);
                        break;
                    case 'meta_wp_ocs_user_role':
                        array_push(static::$array, static::$pos_price);
                        break;
                    case 'meta_wp_ocs_user_level':
                        array_push(static::$array, static::null);
                        break;
                    case 'meta_dismissed_wp_pointers':
                        array_push(static::$array, '');
                        break;
                    case 'meta_last_name':
                        array_push(static::$array, static::$last_name);
                        break;
                    case 'meta_paying_customer':
                        array_push(static::$array, static::meta_paying_customer);
                        break;
                    case 'meta_nickname':
                        array_push(static::$array, static::$costumer_name);
                        break;
                    case 'meta_first_name':
                        array_push(static::$array, static::$first_name);
                        break;
                    case 'meta_description':
                        array_push(static::$array, static::$costumer_name);
                        break;
                    case 'meta_rich_editing':
                        array_push(static::$array, static::meta_rich_editing);
                        break;
                    case 'meta_comment_shortcuts':
                        array_push(static::$array, static::meta_comment_shortcuts);
                        break;
                    case 'meta_admin_color':
                        array_push(static::$array, static::meta_admin_color);
                        break;
                    case 'meta_use_ssl':
                        array_push(static::$array, static::meta_use_ssl);
                        break;
                    case 'meta_show_admin_bar_front':
                        array_push(static::$array, static::meta_show_admin_bar_front);
                        break;
                    case 'meta__money_spent':
                        array_push(static::$array, "");
                        break;
                    case 'meta__order_count':
                        array_push(static::$array, "");
                        break;
                    case 'meta_billing_first_name':
                        array_push(static::$array, static::$billing_first_name);
                        break;
                    case 'meta_billing_last_name':
                        array_push(static::$array, static::$billing_last_name);
                        break;
                    case 'meta_billing_company':
                        array_push(static::$array, static::$billing_company);
                        break;
                    case 'meta_billing_email':
                        array_push(static::$array, static::$billing_email);
                        break;
                    case 'meta_billing_phone':
                        array_push(static::$array, static::$billing_phone);
                        break;
                    case 'meta_billing_country':
                        array_push(static::$array, static::$billing_country);
                        break;
                    case 'meta_billing_address_1':
                        array_push(static::$array, static::$billing_addr1);
                        break;
                    case 'meta_billing_address_2':
                        array_push(static::$array, static::$billing_addr2);
                        break;
                    case 'meta_billing_city':
                        array_push(static::$array, static::$billing_city);
                        break;
                    case 'meta_billing_state':
                        array_push(static::$array, static::$billing_state);
                        break;
                    case 'meta_billing_postcode':
                        array_push(static::$array, static::$billing_zip);
                        break;
                    case 'meta_shipping_first_name':
                        array_push(static::$array, static::$ship_to_first_name);
                        break;
                    case 'meta_shipping_last_name':
                        array_push(static::$array, static::$ship_to_last_name);
                        break;
                    case 'meta_shipping_company':
                        array_push(static::$array, static::$ship_to_company);
                        break;
                    case 'meta_shipping_country':
                        array_push(static::$array, static::$ship_to_country);
                        break;
                    case 'meta_shipping_address_1':
                        array_push(static::$array, static::$ship_to_addr1);
                        break;
                    case 'meta_shipping_address_2':
                        array_push(static::$array, static::$ship_to_addr2);
                        break;
                    case 'meta_shipping_city':
                        array_push(static::$array, static::$ship_to_city);
                        break;
                    case 'meta_shipping_state':
                        array_push(static::$array, static::$ship_to_state);
                        break;
                    case 'meta_shipping_postcode':
                        array_push(static::$array, static::$ship_to_zip);
                        break;
                    case 'meta_sesssion_tokens':
                        array_push(static::$array, "");
                        break;
                    case 'meta__woocommerce_persistent_cart':
                        array_push(static::$array, "");
                        break;
                    case 'meta_manageedit-shop_ordercolumnshidden':
                        array_push(static::$array, "");
                        break;
                    case 'next_del_date':
                        array_push(static::$array, static::$next_delivery_date);
                        break;
                    case 'vmax_type':
                        array_push(static::$array, 'P');
                        break;
                    case 'vmax_id':
                        array_push(static::$array, static::$pos_id);
                        break;
                }
            }
            $file = fopen(self::$pathtofilepos, 'a+');
            if (fputcsv($file, static::$array, ',', '"') !== false) {
                $text = 'CSV product insert full data file complete';
                log::logtext($text);
                fclose($file);
            } else {
                $text = 'CSV product insert full data file error';
                log::logtext($text);
                fclose($file);
            }
        }else{
            $text  = 'Array column for insert in csv empty (POS)';
            log::logtext($text);
            return null;
        }
    }
}



