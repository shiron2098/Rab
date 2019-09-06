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

    private static $pro_description = null;
    private static $pkp_id = null;
    private static $pkp_id2 = null;
    private static $pro_id = null;
    private static $paskagedata = null ;
    private static $paskagedata2 = null;

    public static function createCsvArray($xml)
    {
        if (!empty($xml) && isset($xml)) {
            self::start($xml);
            self::packagedata();
            self::paskage(static::$paskagedata,static::$paskagedata2);
        }
    }

    private static function start($xml)
    {
        $data2 = file_get_contents(self::$pathtofileproduct2);
        static::$package = $xml->packages;
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
                    if ($attributepakages['package'] !== null) {
                        static::$paskagedata[] = $attributepakages['package'];
                    }
                    $json = json_encode($attributepakages);
                    $jsondecode = json_decode($json, true);
                    foreach ($jsondecode as $item) {
                        if (!empty($item['package'])) {
                            static::$paskagedata2[] = $item['package'];
                        }
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

    private static function pkp()
    {
        if (!empty(self::$package) && isset(self::$package)) {
            static::$pkp_id = [];
            static::$pkp_id2 = [];
            $json = json_encode(self::$package);
            $jsondecode = json_decode($json, true);
            foreach ($jsondecode as $atrribute) {
                foreach ($atrribute as $attributepakages) {
                    if(!empty($attributepakages['pkp_id'])) {
                        static::$pkp_id2 []= $attributepakages['pkp_id'];
                        return static::$pkp_id2;
                    }
                    $json = json_encode($attributepakages);
                    $jsondecode = json_decode($json, true);
                    foreach ($jsondecode as $item) {
                        if(!empty($item['pkp_id'])) {
                            static::$pkp_id []= $item['pkp_id'];
                        }
                    }
                }
                return static::$pkp_id;
            }
        } else {
            $text  = 'Pkp_id column data for insert in csv empty (product2)';
            log::logtext($text);
            return null;
        }
    }

    private static function InsertCSv($package,$pkp_id)
    {
        if (!empty(static::$arraycolumn) && isset(self::$arraycolumn)) {
            if(!empty(static::$array)) {
                static::DeleteArrayFile(static::$array);
            }
                foreach (static::$arraycolumn as $column) {
                    switch ($column) {
                        case 'Parent':
                            array_push(static::$array, static::$pro_description);
                            break;
                        case 'parent_sku':
                            array_push(static::$array, static::$pro_id);
                            break;
                        case 'post_parent':
                            array_push(static::$array, static::numberzero);
                            break;
                        case 'ID':
                            array_push(static::$array, static::numberzero);
                            break;
                        case 'post_status':
                            array_push(static::$array, 'publish');
                            break;
                        case 'menu_order':
                            array_push(static::$array, static::numberone);
                            break;
                        case 'sku':
                                array_push(static::$array, $pkp_id);
                                break;
                        case 'downloadable':
                            array_push(static::$array, static::no);
                            break;
                        case 'virtual':
                            array_push(static::$array, static::no);
                            break;
                        case 'stock':
                            array_push(static::$array, "");
                            break;
                        case 'stock_status':
                            array_push(static::$array, 'instock');
                            break;
                        case 'regular_price':
                            array_push(static::$array, "");
                            break;
                        case 'saleprice':
                            array_push(static::$array, "");
                            break;
                        case 'weight':
                            array_push(static::$array, "");
                            break;
                        case 'length':
                            array_push(static::$array, "");
                            break;
                        case 'width':
                            array_push(static::$array, "");
                            break;
                        case 'height':
                            array_push(static::$array, "");
                            break;
                        case 'tax_class':
                            array_push(static::$array, "");
                            break;
                        case 'file_path':
                            array_push(static::$array, "");
                            break;
                        case 'file_paths':
                            array_push(static::$array, "");
                            break;
                        case 'download_limit':
                            array_push(static::$array, "");
                            break;
                        case 'images':
                            array_push(static::$array, "");
                            break;
                        case 'downloadable_files':
                            array_push(static::$array, static::no);
                            break;
                        case 'tax:product_shipping_class':
                            array_push(static::$array, "");
                            break;
                        case 'meta:attribute_package-size':
                                array_push(static::$array, $package);
                            break;
                        case 'meta:_enable_role_based_price':
                            array_push(static::$array, static::numberone);
                            break;
                        case 'meta:_role_based_price':
                            array_push(static::$array, "");
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
    private  static function paskage($paskage,$paskage2)
    {
        if(!empty($paskage)) {
            foreach ($paskage as  $packagedata) {
                $packagefinish = $packagedata;
            }
                $full = static::pkp();
                foreach ($full as $item) {
                    static::InsertCSv($packagefinish,$item);
                }
        }
        if(!empty($paskage2)){
                $full = static::pkp();
                $item= 0;
            foreach ($paskage2 as $packagedata2) {
                $packagefinish = $packagedata2;
                    static::InsertCSv($packagefinish,$full[$item]);
                $item++;
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
}