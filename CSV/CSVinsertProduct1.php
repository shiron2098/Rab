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
    private static $pro_id = null ;
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
                    case 'pro_id':
                        static::$pro_id = $value;
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
                    if ($attributepakages['package'] !== null) {
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
                    case 'post_name':
                        array_push(static::$array, static::$pro_description);
                        break;
                    case 'ID':
                        array_push(static::$array, static::numberzero);
                        break;
                    case 'post_excerpt':
                        array_push(static::$array, static::$pro_description);
                        break;
                    case 'post_content':
                        array_push(static::$array, "");
                        break;
                    case 'post_status':
                        if (static::$change_or_new === static::postsome) {
                            array_push(static::$array, static::post_statusSame);
                        } else if (static::$change_or_new === static::postdelete) {
                            array_push(static::$array, static::post_statusDelete);
                        }
                        break;
                    case 'menu_order':
                        array_push(static::$array, static::numberone);
                        break;
                    case 'post_date':
                        array_push(static::$array, static::date);
                        break;
                    case 'post_parent':
                        array_push(static::$array, static::numberzero);
                        break;
                    case 'post_author':
                        array_push(static::$array, static::numberzero);
                        break;
                    case 'comment_status':
                        array_push(static::$array, 'closed');
                        break;
                    case 'sku':
                        array_push(static::$array, static::$pro_id);
                        break;
                    case 'downloadable':
                        array_push(static::$array, static::no);
                        break;
                    case 'virtual':
                        array_push(static::$array, static::no);
                        break;
                    case 'visibility':
                        array_push(static::$array, 'visible');
                        break;
                    case 'stock':
                        array_push(static::$array, "");
                        break;
                    case 'stock_status':
                        array_push(static::$array, 'instock');
                        break;
                    case 'backorders':
                        array_push(static::$array, static::yes);
                        break;
                    case 'manage_stock':
                        array_push(static::$array, static::no);
                        break;
                    case 'regular_price':
                        array_push(static::$array, "");
                        break;
                    case 'sale_price':
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
                    case 'tax_status':
                        array_push(static::$array, "");
                        break;
                    case 'tax_class':
                        array_push(static::$array, "");
                        break;
                    case 'upsell_ids':
                        array_push(static::$array, static::numberzero);
                        break;
                    case 'crossell_ids':
                        array_push(static::$array, static::numberzero);
                        break;
                    case 'featured':
                        array_push(static::$array, static::no);
                        break;
                    case 'sale_price_dates_from':
                        array_push(static::$array, "");
                        break;
                    case 'sale_price_dates_to':
                        array_push(static::$array, "");
                        break;
                    case 'download_limit':
                        array_push(static::$array, static::numberzero);
                        break;
                    case 'download_expiry':
                        array_push(static::$array, "");
                        break;
                    case 'product_url':
                        array_push(static::$array, "");
                        break;
                    case 'button_text':
                        array_push(static::$array, "");
                        break;
                    case 'meta:_yoast_wpseo_focuskw':
                        array_push(static::$array, "");
                        break;
                    case 'meta:_yoast_wpseo_title':
                        array_push(static::$array, "");
                        break;
                    case 'meta:_yoast_wpseo_metadesc':
                        array_push(static::$array, "");
                        break;
                    case 'meta:_yoast_wpseo_metakeywords':
                        array_push(static::$array, "");
                        break;
                    case 'images':
                        array_push(static::$array, "");
                        break;
                    case 'downloadable_files':
                        array_push(static::$array, static::no);
                        break;
                    case 'tax:product_type':
                        array_push(static::$array, 'variable');
                        break;
                    case 'tax:product_cat':
                        array_push(static::$array, "");
                        break;
                    case 'tax:product_tag':
                        array_push(static::$array, "");
                        break;
                    case 'tax:product_shipping_class':
                        array_push(static::$array, "");
                        break;
                    case 'meta:Brand':
                        array_push(static::$array, "");
                        break;
                    case 'meta:eg_html5_ratio':
                        array_push(static::$array, static::numberzero);
                        break;
                    case 'meta:eg_settings_custom_meta_element':
                        array_push(static::$array, "");
                        break;
                    case 'meta:eg_settings_custom_meta_setting':
                        array_push(static::$array, "");
                        break;
                    case 'meta:eg_settings_custom_meta_skin':
                        array_push(static::$array, "");
                        break;
                    case 'meta:eg_settings_custom_meta_style':
                        array_push(static::$array, "");
                        break;
                    case 'meta:eg_soundcloud_ratio':
                        array_push(static::$array, static::numberzero);
                        break;
                    case 'meta:eg_sources_html5_mp4':
                        array_push(static::$array, "");
                        break;
                    case 'meta:eg_sources_html5_ogv':
                        array_push(static::$array, "");
                        break;
                    case 'meta:eg_sources_html5_webm':
                        array_push(static::$array, "");
                        break;
                    case 'meta:eg_sources_iframe':
                        array_push(static::$array, "");
                        break;
                    case 'meta:eg_sources_image':
                        array_push(static::$array, "");
                        break;
                    case 'meta:eg_sources_revslider':
                        array_push(static::$array, "");
                        break;
                    case 'meta:eg_sources_soundcloud':
                        array_push(static::$array, "");
                        break;
                    case 'meta:eg_sources_vimeo':
                        array_push(static::$array, "");
                        break;
                    case 'meta:eg_sources_wistia':
                        array_push(static::$array, "");
                        break;
                    case 'meta:eg_sources_youtube':
                        array_push(static::$array, "");
                        break;
                    case 'meta:eg_vimeo_ratio':
                        array_push(static::$array, static::numberzero);
                        break;
                    case 'meta:eg_wistia_ratio':
                        array_push(static::$array, static::numberzero);
                        break;
                    case 'meta:eg_youtube_ratio':
                        array_push(static::$array, static::numberzero);
                        break;
                    case 'meta:slide_template':
                        array_push(static::$array, 'default');
                        break;
                    case 'meta:total_sales':
                        array_push(static::$array, static::numberzero);
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
                    case 'meta:_enable_role_based_price':
                        array_push(static::$array, "");
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