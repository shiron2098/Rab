<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Date.php';


class CSVinsertProduct1 extends date
{
    const postsome = 'same';
    const postdelete = 'delete';
    const post_statusSame = 'publish';
    const post_statusDelete = 'trash';
    const numberone = '1';
    const numberzero = '0';
    const date = '2010-01-01';
    const no = 'no';
    const yes = 'yes';

    private static $array = null;
    private static $arraycolumn = null;
    private static $package = null;

    private static $pro_description = null;
    private static $change_or_new = null ;
    private static $pro_code = null ;
    private static $paskagedata = null;
    private static $paskagedata2 = null;


    public static function createCsvArray($xml)
    {
        if (!empty($xml) && isset($xml)) {
            static::start($xml);
            static::pkp();
            static::InsertCSv();
        }
    }
    private static function start($xml)
    {
        $data2 = file_get_contents(self::$pathtofileproduct1);
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
                    case 'pro_code':
                        static::$pro_code = $value;
                        break;
                    case 'changed_or_new':
                        static::$change_or_new = $value;
                        break;
                }
            }
        }
        return static::$arraycolumn;
    }

    private static function pkp()
    {
        if (!empty(static::$package) && isset(static::$package)) {
            static::$paskagedata = [];
            static::$paskagedata2 = [];
            $json = json_encode(static::$package);
            $jsondecode = json_decode($json, true);
            foreach ($jsondecode as $atrribute) {
                foreach ($atrribute as $attributepakages) {
                    if (!empty($attributepakages['package'])&& isset($attributepakages['package'])) {
                        static::$paskagedata[] = $attributepakages['package'];
                    }
                    $json = json_encode($attributepakages);
                    $jsondecode = json_decode($json, true);
                    foreach ($jsondecode as $item){
                        if(!empty($item['package'])) {
                            static::$paskagedata2[] = $item['package'];
                        }
                    }
                }
            } return true;
        } else {
            $text  = 'Pkp_id column data for insert in csv empty (product1)';
            log::logtext($text);
            return null;
        }
    }

    private static function InsertCSv()
    {
        if (!empty(static::$arraycolumn) && isset(self::$arraycolumn)) {
            foreach (static::$arraycolumn as $column) {
                switch ($column) {
                    case 'post_title':
                        array_push(static::$array, static::$pro_description);
                        break;
                    case 'post_excerpt':
                        array_push(static::$array, static::$pro_description);
                        break;
                    case 'post_status':
                        if (static::$change_or_new === static::postsome) {
                            array_push(static::$array, static::post_statusSame);
                        } else if (static::$change_or_new === static::postdelete) {
                            array_push(static::$array, static::post_statusDelete);
                        }
                        break;
                    case 'sku':
                        array_push(static::$array, static::$pro_code);
                        break;
                    case 'downloadable':
                        array_push(static::$array, static::no);
                        break;
                    case 'visibility':
                        array_push(static::$array, 'visible');
                        break;
                    case 'stock_status':
                        array_push(static::$array, 'instock');
                        break;
                    case 'manage_stock':
                        array_push(static::$array, static::no);
                        break;
                    case 'tax_status':
                        array_push(static::$array, "");
                        break;
                    case 'tax_class':
                        array_push(static::$array, "");
                        break;
                    case 'tax:product_type':
                        array_push(static::$array, 'variable');
                        break;
                    case '"attribute:Package Size"':
                        if(!empty(static::$paskagedata)){
                            array_push(static::$array, static::$paskagedata['0']);
                            break;
                        }
                        if(!empty(static::$paskagedata2)) {
                            $arrayPacakage = implode('|', static::$paskagedata2);
                                array_push(static::$array, $arrayPacakage);
                                break;
                            }
                        break;
                    case '"attribute_data:Package Size"':
                        array_push(static::$array, '0|0|1');
                        break;
                    case '"attribute_default:Package Size"':
                        if(!empty(static::$paskagedata)){
                            array_push(static::$array, static::$paskagedata['0']);
                        }
                        if(!empty(static::$paskagedata2)) {
                            array_push(static::$array, static::$paskagedata2['0']);
                        }
                        break;
                }
            }
            $file = fopen(self::$pathtofileproduct1, 'a+');
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
            $text  = 'Array column for insert in csv empty (product1)';
            log::logtext($text);
            return null;
        }
    }
}