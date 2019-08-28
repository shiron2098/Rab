<?php

class date
{
    const wp_product = 'WP-Product';
    const wp_provariation = 'WP-ProVariation';
    const wp_provariationx = 'WP_ProVariationEx';

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
        static::$arrayCsvPRODUCT = ['post_title','post_name','ID','post_excerpt','post_content','post_status','menu_order','post_date','post_parent','post_author','comment_status','sku',
            'downloadable','virtual','visibility','stock','stock_status','backorders','manage_stock','regular_price','sale_price','weight','length','width','height','tax_status',
            'tax_class','upsell_ids','crossell_ids','featured','sale_price_dates_from','sale_price_dates_to','download_limit','download_expiry','product_url','button_text','meta:_yoast_wpseo_focuskw',
            'meta:_yoast_wpseo_title','meta:_yoast_wpseo_metadesc','meta:_yoast_wpseo_metakeywords','images','downloadable_files','tax:product_type','tax:product_cat','tax:product_tag','tax:product_shipping_class',
            'meta:Brand','meta:eg_html5_ratio','meta:eg_settings_custom_meta_element','meta:eg_settings_custom_meta_setting','meta:eg_settings_custom_meta_skin','meta:eg_settings_custom_meta_style','meta:eg_soundcloud_ratio',
            'meta:eg_sources_html5_mp4','meta:eg_sources_html5_ogv','meta:eg_sources_html5_webm','meta:eg_sources_iframe','meta:eg_sources_image','meta:eg_sources_revslider','meta:eg_sources_soundcloud','meta:eg_sources_vimeo',
            'meta:eg_sources_wistia','meta:eg_sources_youtube','meta:eg_vimeo_ratio','meta:eg_wistia_ratio','meta:eg_youtube_ratio','meta:slide_template','meta:total_sales','attribute:Package Size','attribute_data:Package Size',
            'attribute_default:Package Size','meta:_enable_role_based_price',""];
        static::$arrayCsvPRODUCT2 = ['Parent','parent_sku','post_parent','ID','post_status','menu_order','sku','downloadable','virtual','stock','stock_status','regular_price','saleprice','weight','length','width','height','tax_class',
            'file_path','file_paths','download_limit','images','downloadable_files','tax:product_shipping_class','meta:attribute_package-size','meta:_enable_role_based_price','meta:_role_based_price',""];
        static::$arrayCsvPRODUCT3 = ['Parent_sku','sku','post_name','post_type','meta:_role_based_price',""];
    }
    public static function pathfileproduct(){
        $rand = rand(1,10000000);
            $f = fopen(MYSQLDataOperator::filepathCsv . static::wp_product . ' ' . static::$Filetime . ' ' . '.csv', 'a+');
            static::$pathtofileproduct1 = MYSQLDataOperator::filepathCsv . static::wp_product  . ' ' . static::$Filetime . ' '  . '.csv';
            return $f;
    }
    public static function pathfileproduct2(){
        $rand = rand(1,10000000);
        $f = fopen(MYSQLDataOperator::filepathCsv . static::wp_provariation  . ' '. static::$Filetime . ' '  . '.csv', 'a+');
        static::$pathtofileproduct2 = MYSQLDataOperator::filepathCsv . static::wp_provariation  . ' '  . static::$Filetime . ' '  . '.csv';
        return $f;
    }
    public static function pathfileproduct3(){
        $rand = rand(1,10000000);
        $f = fopen(MYSQLDataOperator::filepathCsv . static::wp_provariationx . ' ' . static::$Filetime . ' ' . '.csv', 'a+');
        static::$pathtofileproduct3 = MYSQLDataOperator::filepathCsv . static::wp_provariationx . ' ' . static::$Filetime . ' '  . '.csv';
        return $f;
    }
    public static function pathfilepos($name){
        $rand = rand(1,10000000);
        $f = fopen(MYSQLDataOperator::filepathCsv . $name . static::$Filetime .  ' '  . '.csv', 'a+');
        static::$pathtofilepos = MYSQLDataOperator::filepathCsv . $name . static::$Filetime . ' '  . '.csv';
        return $f;
    }
}