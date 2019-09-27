<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Date.php';




class CSVInsertPOSLocationFile extends Date
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
    private static $ship_to_email= null;
    private static $default_col = null;
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
    private static $changed_or_new = null;
    private static $pos_code = null;
    private static $ocs_tax_exampt = null;
    private static $user_control_record = null;

    public static function createCsvArray($xml)
    {
        if (!empty($xml) && isset($xml)) {
            static::start($xml);
            static::InsertCSv();
        }
    }

    private static function start($xml)
    {

        $data2 = file_get_contents(self::$pathtofileposlocation);
        static::$array = [];
        static::$arraycolumn = explode(',', $data2);
        /*        $data = json_encode($xml->attributes());
                $jsondecode = json_decode($data, true);
                foreach ($jsondecode as $xmlstring) {*/
        foreach ($xml as $key => $value) {
            switch ($key) {
                case 'user_email':
                    static::$user_email = $value;
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
                case 'ship_to_email':
                    static::$ship_to_email = $value;
                    break;
                case 'ship_to_zip':
                    static::$ship_to_zip = $value;
                    break;
                case 'default_col':
                    static::$default_col = $value;
                    break;
                case 'next_delivery_date':
                    static::$next_delivery_date = $value;
                    break;
                case 'pos_id':
                    $value2 = str_replace(';', ':', $value);
                    static::$pos_id = $value2;
                    break;
                case 'pos_code':
                    static::$pos_code = $value;
                    break;
                case 'ocs_tax_exampt':
                    static::$ocs_tax_exampt =$value;
                    break;
                case 'user_control_record':
                    static::$user_control_record =$value;
                    break;
            }
        }
        return static::$arraycolumn;
    }

    private function InsertCSv()
    {
        if (!empty(static::$arraycolumn) && isset(self::$arraycolumn)) {
            foreach (static::$arraycolumn as $item) {
                switch ($item) {
                    case 'user_login':
                        array_push(static::$array, static::$user_email);
                        break;
                    case 'user_email':
                        array_push(static::$array, static::$user_email);
                        break;
                    case 'ocs_address_name':
                        array_push(static::$array, static::$pos_code);
                        break;
                    case 'first_name':
                        array_push(static::$array, static::$ship_to_first_name);
                        break;
                    case 'last_name':
                        array_push(static::$array, static::$ship_to_last_name);
                        break;
                    case 'company':
                        array_push(static::$array, static::$ship_to_company);
                        break;
                    case 'address_1':
                        array_push(static::$array, static::$ship_to_addr1);
                        break;
                    case 'address_2':
                        array_push(static::$array, static::$ship_to_addr2);
                        break;
                    case 'city':
                        array_push(static::$array, static::$ship_to_city);
                        break;
                    case 'state':
                        array_push(static::$array, static::$ship_to_state);
                        break;
                    case 'zip':
                        array_push(static::$array, static::$ship_to_zip);
                        break;
                    case 'country':
                        array_push(static::$array, static::$ship_to_country);
                        break;
                    case 'shipping_email':
                        array_push(static::$array, static::$ship_to_email);
                        break;
                    case 'default':
                        array_push(static::$array, static::$default_col);
                        break;
                    case '"Delivery Date"':
                        array_push(static::$array, static::$next_delivery_date);
                        break;
                    case '"Pos Code"':
                        array_push(static::$array, static::$pos_code);
                        break;
                    case 'ocs_tax_exampt':
                        array_push(static::$array, static::$ocs_tax_exampt);
                        break;
                    case 'vmax_id':
                        array_push(static::$array, static::$pos_id);
                        break;
                    case 'changed_or_new':
                        array_push(static::$array, static::$changed_or_new);
                        break;
                    case 'user_control_record':
                        array_push(static::$array, static::$user_control_record);
                        break;
                }
            }
            $file = fopen(self::$pathtofileposlocation, 'a+');
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