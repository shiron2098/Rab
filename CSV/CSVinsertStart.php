<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/CSVinsertPOS.php';
require_once __DIR__ . '/CSVinsertProduct1.php';
require_once __DIR__ . '/CSVinsertProduct2.php';
require_once __DIR__ . '/Date.php';


class CSVinsertStart extends Date
{

    private static $pathproduct1;
    private static $pathproduct2;
    private static $pathproduct3;
    private static $pathpos;


    public static function InsertCsvFile($path, $name)
    {
        $bool = true;
        Date::index();
        self::$Filetime = Date('YmdHis', time());
        if (file_exists(__DIR__ . '/../' . $path)) {
            $xml = simplexml_load_file(__DIR__ . '/../'. $path);
            if (!file_exists(MYSQLDataOperator::filepathCsv)) {
                mkdir(MYSQLDataOperator::filepathCsv, 0777, true);
            }
            foreach ($xml as $data) {
                switch ($name) {
                      case 'product':
                        if ($bool === true) {
                           static::$pathproduct1 = Date::pathfileproduct();
                           fputcsv( static::$pathproduct1, static::$arrayCsvPRODUCT, ',', '"');
                            fclose(static::$pathproduct1);
                            static::$pathproduct2 = Date::pathfileproduct2();
                            fputcsv( static::$pathproduct2, static::$arrayCsvPRODUCT2, ',', '"');
                            fclose(static::$pathproduct2);
                            $bool = false;
                        }
                         CSVinsertProduct1::createCsvArray($data);
                         CSVinsertProduct2::createCsvArray($data);
                        break;
                    case 'T2S_POS_Info_WbStore';
                        if ($bool === true) {
                            static::$pathpos = date::pathfilepos();
                            fputcsv(static::$pathpos, Date::$ArrayCSvPOS, ',', '"');
                            $bool = false;
                        }
                        CSVinsertPOS::createCsvArray($data);
                        break;
                }
            }
        }
    }
}