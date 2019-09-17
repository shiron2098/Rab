<?php

class date
{
    const wp_product = 'WP-Product';
    const wp_provariation = 'WP-ProVariation';
    const wp_provariationx = 'WP-ProVariationEx';
    const wp_users = 'WP-Users';

    protected static $Filetime;
    protected static $ArrayCSvPOS;
    protected static $arrayCsvPRODUCT;
    protected static $arrayCsvPRODUCT2;
    protected static $arrayCsvPRODUCT3;
    protected static $pathtofileproduct1;
    protected static $pathtofileproduct2;
    protected static $pathtofileproduct3;
    protected static $pathtofilepos;

    public static function index(){
        static::$ArrayCSvPOS = ['id', 'user_login', 'user_pass', 'user_nicename', 'user_email', 'user_url', 'user_registered', 'user_activation_key', 'user_status', 'display_name', 'meta_wp_ocs_user_role', 'meta_wp_ocs_user_level',
            'meta_dismissed_wp_pointers', 'meta_last_name', 'meta_paying_customer', 'meta_nickname', 'meta_first_name', 'meta_description', 'meta_rich_editing', 'meta_comment_shortcuts',
            'meta_admin_color', 'meta_use_ssl', 'meta_show_admin_bar_front', 'meta__money_spent', 'meta__order_count', 'meta_billing_first_name',
            'meta_billing_last_name', 'meta_billing_company', 'meta_billing_email', 'meta_billing_phone', 'meta_billing_country', 'meta_billing_address_1',
            'meta_billing_address_2', 'meta_billing_city', 'meta_billing_state', 'meta_billing_postcode', 'meta_shipping_first_name', 'meta_shipping_last_name', 'meta_shipping_company', 'meta_shipping_country',
            'meta_shipping_address_1', 'meta_shipping_address_2', 'meta_shipping_city', 'meta_shipping_state', 'meta_shipping_postcode', 'meta_sesssion_tokens', 'meta__woocommerce_persistent_cart',
            'meta_manageedit-shop_ordercolumnshidden', 'next_del_date', 'vmax_type', 'vmax_id', ""];
        static::$arrayCsvPRODUCT = ['post_title','post_excerpt','post_status','sku','downloadable','visibility','stock_status','manage_stock','tax_status','tax_class','attribute:Package Size','attribute_data:Package Size','attribute_default:Package Size',""];
        static::$arrayCsvPRODUCT2 = ['parent_sku','post_status','sku','stock_status','tax_class','meta:attribute_package-size','meta:_enable_role_based_price','meta:_role_based_price',""];
    }
    public static function pathfileproduct(){
            $f = fopen(MYSQLDataOperator::filepathCsv . static::wp_product . ' ' . static::$Filetime . ' ' . '.csv', 'a+');
            static::$pathtofileproduct1 = MYSQLDataOperator::filepathCsv . static::wp_product  . ' ' . static::$Filetime . ' '  . '.csv';
            return $f;
    }
    public static function pathfileproduct2(){
        $f = fopen(MYSQLDataOperator::filepathCsv . static::wp_provariation  . ' '. static::$Filetime . ' '  . '.csv', 'a+');
        static::$pathtofileproduct2 = MYSQLDataOperator::filepathCsv . static::wp_provariation  . ' '  . static::$Filetime . ' '  . '.csv';
        return $f;
    }
    public static function pathfilepos(){
        $f = fopen(MYSQLDataOperator::filepathCsv . static::wp_users . static::$Filetime .  ' '  . '.csv', 'a+');
        static::$pathtofilepos = MYSQLDataOperator::filepathCsv . static::wp_users . static::$Filetime . ' '  . '.csv';
        return $f;
    }
}