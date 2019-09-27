<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/CSVinsertPOS.php';
require_once __DIR__ . '/CSVinsertProduct1.php';
require_once __DIR__ . '/CSVinsertProduct2.php';
require_once __DIR__ . '/CSVInsertPOSLocationFile.php';
require_once __DIR__ . '/CSVInsertProductTaxRates.php';
require_once __DIR__ . '/Date.php';


class CSVinsertStart extends Date
{

    private static $pathproduct1;
    private static $pathproduct2;
    private static $pathtaxrates;
    private static $pathlocationfile;
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
                      case 'products':
                          foreach($data as $posname => $posdata) {
                              if ($bool === true) {
                                  static::$pathproduct1 = Date::pathfileproduct();
                                  fputcsv(static::$pathproduct1, static::$arrayCsvPRODUCT, ',', '"');
                                  fclose(static::$pathproduct1);
                                  static::$pathproduct2 = Date::pathfileproduct2();
                                  fputcsv(static::$pathproduct2, static::$arrayCsvPRODUCT2, ',', '"');
                                  fclose(static::$pathproduct2);
                                  static::$pathtaxrates = Date::pathfiletaxrates();
                                  fputcsv(static::$pathtaxrates, static::$arrayCsvTax_rates, ',', '"');
                                  fclose(static::$pathtaxrates);
                                  $bool = false;
                              }
                              if($posname == 'product') {
                                  CSVinsertProduct1::createCsvArray($posdata);
                                  CSVinsertProduct2::createCsvArray($posdata);
                              }
                              if($posname == 'tax'){
                                      $array = $posdata->attributes();
                                      $json = json_encode($array);
                                      $array2 = json_decode($json, TRUE);
                                      foreach ($array2 as $k => $v) {
                                          $json2 = json_decode(json_encode($v), false);
                                      }
                                      CSVInsertProductTaxRates::createCsvArray($json2);
                              }
                          }
                          break;
                    case 'pos_users';
                        if ($bool === true) {
                            static::$pathpos = date::pathfilepos();
                            fputcsv(static::$pathpos, Date::$ArrayCSvPOS, ',', '"');
                            fclose(static::$pathpos);
                            static::$pathlocationfile = Date::pathfileposlocation();
                            fputcsv( static::$pathlocationfile, static::$arrayCsvLOCATIONFILE, ',', '"');
                            fclose(static::$pathlocationfile);
                            $bool = false;
                        }
                        foreach($data as $posname => $posdata) {
                            if($posname == 'users') {
                                foreach($posdata as $datausers) {
                                    $array = $datausers->attributes();
                                    $json = json_encode($array);
                                    $array2 = json_decode($json, TRUE);
                                    foreach ($array2 as $k => $v) {
                                        $json2 = json_decode(json_encode($v), false);
                                    }
                                    CSVinsertPOS::createCsvArray($json2);
                                }
                            }
                            if($posname =='multi_pos') {
                                foreach ($posdata as $datausers) {
                                    $array = $datausers->attributes();
                                    $json = json_encode($array);
                                    $array2 = json_decode($json, TRUE);
                                    foreach ($array2 as $k => $v) {
                                        $json2 = json_decode(json_encode($v), false);
                                    }
                                    CSVInsertPOSLocationFile::createCsvArray($json2);
                                }
                            }
                        }
                        break;
                }
            }
        }
    }
}