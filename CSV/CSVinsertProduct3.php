<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Date.php';


class CSVinsertProduct3 extends date
{
    private static $price = null;
    private static $array = null;
    private static $arraycolumn = null;
    private static $package = null;

    private static $pro_description = null;
    private static $pkp_id = null;
    private static $pro_id = null;
    private static $role_based_price = null;
    private static $paskagedata = null ;
    private static $paskagedata2 = null;


    public static function createCsvArray($xml)
    {
        if (!empty($xml) && isset($xml)) {
           static::start($xml);
           static::packagedata();
           static::paskage(static::$paskagedata);
        }
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
                        $jsondata[$jsondecode['pos_id']] = ["regular_price" => $jsondecode['regular_price'], "selling_price" => $jsondecode['regular_price']];
                        $jsondatafinish = json_encode($jsondata);
                }
            }
            return static::$role_based_price = $jsondatafinish;
        }else{
            $text  = 'Price column data for insert in csv empty (product3)';
            log::logtext($text);
            return null;
        }
    }
    private static function priceArray($price)
    {
        if (!empty(static::$price) && isset(static::$price)) {
            $json = json_encode($price);
            $jsondecode = json_decode($json, true);
            foreach ($jsondecode as $atrribute) {
                foreach ($atrribute as $dataprice) {
                    $json = json_encode($dataprice);
                    $jsondecode = json_decode($json, true);
                    foreach ($jsondecode as $finishdata) {
                        $jsondata[$finishdata['pos_id']] = ["regular_price" => $finishdata['regular_price'], "selling_price" => $finishdata['regular_price']];
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

    private static function start($xml)
    {
        $data2 = file_get_contents(self::$pathtofileproduct3);
        static::$package = $xml->packages;
        static::$price = $xml->packages->package->prices;
        static::$array = [];
        static::$arraycolumn = explode(',', $data2);
        $data = json_encode($xml->attributes());
        $jsondecode = json_decode($data, true);
        foreach ($jsondecode as $xmlstring) {
            foreach ($xmlstring as $key => $value) {
                switch ($key) {
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
    private static function InsertCSv($price,$pkp_id)
    {
        if (!empty(static::$arraycolumn) && isset(static::$arraycolumn)) {
            if(!empty(static::$array)) {
                static::DeleteArrayFile(static::$array);
            }
            foreach (static::$arraycolumn as $column) {
                switch ($column) {
                    case 'Parent_sku':
                        array_push(static::$array, static::$pro_id);
                        break;
                    case 'sku':
                        array_push(static::$array, $pkp_id);
                        break;
                    case 'post_name':
                        array_push(static::$array, static::$pro_description);
                        break;
                    case 'post_type':
                        array_push(static::$array, 'product_variation');
                        break;
                    case 'meta:_role_based_price':
                        array_push(static::$array, $price);
                        break;
                }
            }
            $file = fopen(self::$pathtofileproduct3, 'a+');
            if (fputcsv($file, static::$array, ',', '"') !== false) {
                $text = 'CSV product insert full data file complete';
                log::logtext($text);
                fclose($file);
                return true;
            } else {
                $text = 'CSV product insert full data file error';
                log::logtext($text);
                fclose($file);
                return false;
            }
        }else{
            $text  = 'Array column for insert in csv empty (product3)';
            log::logtext($text);
            return null;
        }
    }
    private  static function paskage($paskage)
    {
        $value = 0;
        if (!empty($paskage)) {
            static::$role_based_price = null;
            foreach ($paskage as $packagedata) {
                if ($packagedata['pkp_id']) {
                    $full[] = $packagedata['pkp_id'];
                }
                if ($packagedata['price']) {
                    $a = static::price($packagedata['price']);
                }
            }
            foreach ($full as $item) {
                static::InsertCSv($a, $item);
                break;
            }
            $full = null;
            static::$role_based_price = null;
            foreach ($paskage as $paskageArray) {
                foreach ($paskageArray as $demo) {
                    if ($demo['pkp_id']) {
                        $full[] = $demo['pkp_id'];
                    }
                    if ($demo['price']) {
                        $price[] = static::priceArray($demo);
                    }
                }
            }
            if ($price['0'] !== null) {
                if (!empty($full) && !empty($price)) {
                    foreach ($full as $item) {
                        static::InsertCSv($price[$value], $item);
                        $value++;
                    }
                }
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
        static:: $paskagedata2 = $file;
        return $file;
    }
    private static function packagedata(){
        if (!empty(self::$package) && isset(self::$package)) {
            static::$paskagedata = [];
            static::$paskagedata2 = [];
            if(!empty(static::$paskagedata)) {
                static::DeleteArrayFile(static::$paskagedata);
            }
            if(!empty(static::$paskagedata2)) {
                static::DeleteArrayFile(static::$paskagedata2);
            }
            $json = json_encode(self::$package);
            $jsondecode = json_decode($json, true);
            foreach ($jsondecode as $atrribute) {
                foreach ($atrribute as $attributepakages) {
                    if ($attributepakages !== null) {
                        static::$paskagedata[] = $attributepakages;
                    }
                }
            }
            return true;
        } else {
            $text  = 'Pkp_id column data for insert in csv empty (product2)';
            log::logtext($text);
            return null;
        }
    }

}
