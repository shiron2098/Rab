<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Date.php';

class CSVinsertProduct2 extends Date
{
    const numberone = '1';
    const numberzero = '0';
    const date = '2010-01-01';
    const no = 'no';

    private static $array = null;
    private static $arraycolumn = null;
    private static $package = null;
    private static $price = null;
    private static $packageforprice = null;
    private static $pro_code = null ;
    private static $item;

    private static $pro_description = null;
    private static $pkp_upc = null;
    private static $pkp_upc2 = null;
    private static $pro_id = null;
    private static $paskagedata = null ;

    private static $role_based_price = null;

    public static function createCsvArray($xml)
    {
        if (!empty($xml) && isset($xml)) {
            self::start($xml);
            self::packagedata();
            self::paskage(static::$paskagedata);
        }
    }

    private static function start($xml)
    {
        $data2 = file_get_contents(self::$pathtofileproduct2);
        static::$package = $xml->packages;
        static::$packageforprice = $xml->packages->package;
        static::$price = $xml->packages->package->prices;
        static::$array = [];
        static::$arraycolumn = explode(',', $data2);
        $data = json_encode($xml->attributes());
        $jsondecode = json_decode($data, true);
        foreach ($jsondecode as $xmlstring) {
            foreach ($xmlstring as $key => $value) {
                switch ($key) {
                    case 'pro_code':
                        static::$pro_code = $value;
                        break;
                    case 'pro_description':
                        static::$pro_description = $value;
                        break;
                    case 'pro_id':
                        static::$pro_id = $value;
                        break;
                }
            }
        }
        return static::$arraycolumn;
    }
    private static function packagedata(){
        if (!empty(self::$package) && isset(self::$package)) {
            static::$paskagedata = [];
            if(!empty(static::$paskagedata)) {
                static::DeleteArrayFile(static::$paskagedata);
            }
            foreach(static::$packageforprice as $paskageprice) {
                        static::$paskagedata[] = $paskageprice;
            }
            return true;
        } else {
            $text  = 'Pkp_id column data for insert in csv empty (product2)';
            log::logtext($text);
            return null;
        }
    }

    private static function pkp()
    {
        if (!empty(self::$package) && isset(self::$package)) {
            static::$pkp_upc = [];
            static::$pkp_upc2 = [];
            $json = json_encode(self::$package);
            $jsondecode = json_decode($json, true);
            foreach ($jsondecode as $atrribute) {
                foreach ($atrribute as $attributepakages) {
                    if(!empty($attributepakages['pkp_upc'])) {
                        static::$pkp_upc2 []= $attributepakages['pkp_upc'];
                        return static::$pkp_upc2;
                    }
                    $json = json_encode($attributepakages);
                    $jsondecode = json_decode($json, true);
                    foreach ($jsondecode as $item) {
                        if(!empty($item['pkp_upc'])) {
                            static::$pkp_upc []= $item['pkp_upc'];
                        }
                    }
                }
                return static::$pkp_upc;
            }
        } else {
            $text  = 'Pkp_id column data for insert in csv empty (product2)';
            log::logtext($text);
            return null;
        }
    }

    private static function InsertCSv($package,$pkp_upc,$price)
    {
        if (!empty(static::$arraycolumn) && isset(self::$arraycolumn)) {
            if(!empty(static::$array)) {
                static::DeleteArrayFile(static::$array);
            }
                foreach (static::$arraycolumn as $column) {
                    switch ($column) {
                        case 'parent_sku':
                            array_push(static::$array, static::$pro_code);
                            break;
                        case 'post_status':
                            array_push(static::$array, 'publish');
                            break;
                        case 'sku':
                                array_push(static::$array, $pkp_upc);
                                break;
                        case 'stock_status':
                            array_push(static::$array, 'instock');
                            break;
                        case 'tax_class':
                            array_push(static::$array, "parent");
                            break;
                        case 'meta:attribute_package-size':
                                array_push(static::$array, $package);
                            break;
                        case 'meta:_enable_role_based_price':
                            array_push(static::$array, static::numberone);
                            break;
                        case 'meta:_role_based_price':
                            array_push(static::$array, $price);
                            break;
                    }
                }
                $file = fopen(static::$pathtofileproduct2, 'a+');
                if (fputcsv($file, static::$array, ',', '"') !== false) {
                    $text = 'CSV product insert full data file complete';
                    log::logtext($text);
                    fclose($file);
                } else {
                    $text = 'CSV product insert full data file error';
                    log::logtext($text);
                    fclose($file);
                }
            }else {
            $text = 'Array column for insert in csv empty (product2)';
            log::logtext($text);
            return null;
        }
    }
    private  static function paskage($paskage)
    {
        static::$item = 0;
        foreach($paskage as $paskagedatafinish) {
                if (!empty($paskagedatafinish)) {
                    $full = static::pkp();
                    $attribute = $paskagedatafinish->attributes();
                        static::$price = $paskagedatafinish->prices;
                        $array = $attribute['package'];
                        if (static::$price) {
                            $price = static::price(static::$price);
                        }
                        static::InsertCSv($array['0'], $full[static::$item], $price);
                        static::$item++;
                    }
                }
    }
    private  static function DeleteArrayFile($file){
        $file[] = null;
        foreach ($file as $i => $key) {
            unset($file[$i]);
        }
        static::$array = $file;
        static::$paskagedata = $file;
        return $file;
    }
    private static function price($price)
    {
        if (!empty(static::$price) && isset(static::$price)) {
            $json = json_encode($price);
            $jsondecode = json_decode($json, true);
            foreach ($jsondecode as $atrribute) {
                foreach ($atrribute as $dataprice) {
                    $json = json_encode($dataprice);
                    $jsondecode = json_decode($json, true);
                    foreach ($jsondecode as $jsondecode2) {
                        $jsondata[$jsondecode2['pos_id']] = ["regular_price" => $jsondecode2['regular_price'], "selling_price" => $jsondecode2['regular_price']];
                        $jsondatafinish = json_encode($jsondata);
                    }
                }
            }
            return static::$role_based_price = $jsondatafinish;
        }else{
            $text  = 'Price column data for insert in csv empty (product3)';
            log::logtext($text);
            return null;
        }
    }
}