<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Date.php';




class CSVInsertProductTaxRates extends Date
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

    private static $Country_code = null;
    private static $State_code= null;
    private static $postcode = null;
    private static $city = null;
    private static $rate = null;
    private static $tax_name = null;
    private static $compound = null ;
    private static $shipping = null;
    private static $tax_class = null;
    private static $tax_priority = null;

    public static function createCsvArray($xml)
    {
        if (!empty($xml) && isset($xml)) {
            static::start($xml);
            static::InsertCSv();
        }
    }

    private static function start($xml)
    {

        $data2 = file_get_contents(self::$pathCsvTax_rates);
        static::$array = [];
        static::$arraycolumn = explode(',', $data2);
        /*        $data = json_encode($xml->attributes());
                $jsondecode = json_decode($data, true);
                foreach ($jsondecode as $xmlstring) {*/
        foreach ($xml as $key => $value) {
            switch ($key) {
                case 'Country_code':
                    static::$Country_code = $value;
                    break;
                case 'State_code':
                    static::$State_code = $value;
                    break;
                case 'postcode':
                    static::$postcode = $value;
                    break;
                case 'city':
                    static::$city = $value;
                    break;
                case 'rate':
                    static::$rate = $value;
                    break;
                case 'tax_name':
                    static::$tax_name = $value;
                    break;
                case 'tax_priority':
                    static::$tax_priority = $value;
                    break;
                case 'compound':
                    static::$compound = $value;
                    break;
                case 'shipping':
                    static::$shipping = $value;
                    break;
                case 'tax_class':
                    static::$tax_class = $value;
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
                    case '"Country code"':
                        array_push(static::$array, static::$Country_code);
                        break;
                    case '"State code"':
                        array_push(static::$array, static::$State_code);
                        break;
                    case '"Postcode / ZIP"':
                        array_push(static::$array, static::$postcode);
                        break;
                    case 'City':
                        array_push(static::$array, static::$city);
                        break;
                    case '"Rate %"':
                        array_push(static::$array, static::$rate);
                        break;
                    case '"Tax name"':
                        array_push(static::$array, static::$tax_name);
                        break;
                    case 'Priority':
                        array_push(static::$array, static::$tax_priority);
                        break;
                    case 'Compound':
                        array_push(static::$array, static::$compound);
                        break;
                    case 'Shipping':
                        array_push(static::$array, static::$shipping);
                        break;
                    case '"Tax class"':
                        array_push(static::$array, static::$tax_class);
                        break;
                }
            }
            $file = fopen(self::$pathCsvTax_rates, 'a+');
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