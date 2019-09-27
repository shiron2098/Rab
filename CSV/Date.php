<?php

class date
{
    const wp_product = 'WP-Product';
    const wp_provariation = 'WP-ProVariation';
    const wp_provariationx = 'WP-ProVariationEx';
    const wp_users = 'WP-Users';
    const wp_location = 'LocationFile';
    const wp_tax_rates = 'tax_rates';

    protected static $Filetime;
    protected static $ArrayCSvPOS;
    protected static $arrayCsvPRODUCT;
    protected static $arrayCsvPRODUCT2;
    protected static $arrayCsvTax_rates;
    protected static $arrayCsvLOCATIONFILE;
    protected static $pathtofileproduct1;
    protected static $pathtofileproduct2;
    protected static $pathCsvTax_rates;
    protected static $pathtofilepos;
    protected static $pathtofileposlocation;

    public static function index(){
        static::$ArrayCSvPOS = ['user_login', 'user_pass', 'user_email', 'display_name', 'meta_wp_ocs_user_role', 'meta_last_name', 'meta_paying_customer', 'meta_first_name', 'meta_billing_first_name',
            'meta_billing_last_name', 'meta_billing_company','meta_billing_email','meta_billing_phone','meta_billing_country','meta_billing_address_1','meta_billing_address_2','meta_billing_city','meta_billing_state',
            'meta_billing_postcode','meta_shipping_first_name','meta_shipping_last_name','meta_shipping_company','meta_shipping_country','meta_shipping_address_1','meta_shipping_address_2','meta_shipping_city',
            'meta_shipping_state','meta_shipping_postcode','next_del_date','vmax_type','vmax_id','Pos_Code','ocs_tax_exampt','user_control_record',
             ""];
        static::$arrayCsvPRODUCT = ['post_title','post_excerpt','post_status','sku','downloadable','visibility','stock_status','manage_stock','tax_status','tax_class','attribute:Package Size','attribute_data:Package Size','attribute_default:Package Size','Product_id',""];
        static::$arrayCsvPRODUCT2 = ['parent_sku','post_status','sku','stock_status','tax_class','meta:attribute_package-size','meta:_enable_role_based_price','meta:_role_based_price',""];
        static::$arrayCsvLOCATIONFILE = ['user_login','user_email','ocs_address_name','first_name','last_name','company','address_1','address_2','city','state','zip','country','shipping_email','default','Delivery Date'
        ,'Pos Code','ocs_tax_exampt','vmax_id','changed_or_new','user_control_record',""];
        static::$arrayCsvTax_rates = ['Country code','State code','Postcode / ZIP','City','Rate %','Tax name','Priority','Compound','Shipping','Tax class',""];
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
    public static function pathfileposlocation(){
        $f = fopen(MYSQLDataOperator::filepathCsv . static::wp_location . static::$Filetime .  ' '  . '.csv', 'a+');
        static::$pathtofileposlocation = MYSQLDataOperator::filepathCsv . static::wp_location . static::$Filetime . ' '  . '.csv';
        return $f;
    }
    public static function pathfiletaxrates(){
        $f = fopen(MYSQLDataOperator::filepathCsv . static::wp_tax_rates . static::$Filetime .  ' '  . '.csv', 'a+');
        static::$pathCsvTax_rates = MYSQLDataOperator::filepathCsv . static::wp_tax_rates . static::$Filetime . ' '  . '.csv';
        return $f;
    }
}