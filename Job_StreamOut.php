<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/RequestProcessor.php';
require_once __DIR__ . '/CreateOperator/CreateTask.php';
require_once __DIR__ . '/Worker/WorkerReceiver1.php';
require_once __DIR__ . '/Simple_Parser.php';
require_once __DIR__ . '/AbstractClass/MYSQLDataOperator.php';
require_once __DIR__ . '/Interface/mysql_insert_interface.php';
require_once __DIR__ . '/CSV/CSVinsertStart.php';
ini_set('mysql.connect_timeout', 1000);
/*set global net_buffer_length=1000000;
set global max_allowed_packet=1000000000;*/

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use \app\mysql_insert_interface;
use \app\WorkerReceiver1;

class Job_StreamOut extends Threaded
{

    private $idstreams;
    protected $id_operator;
    protected $id_jobs;
    Protected $command;
    private $bool = 1;
    private $provider;
    private $StartXML;
    private $batch_id;
    private $time;
    private $bollpos;
    private $NameDataForProccesing;
    public static $operator;

    public function Run()
    {
        if ($this->bool === 1) {
            if (!empty(Job_StreamOut::$operator) && isset(Job_StreamOut::$operator)) {
                $this->idstreams = Thread::getCurrentThreadId();
                $CreateTask = new CreateTask();
                $CreateTask->UpdateOperStreamsUp($this->idstreams, Job_StreamOut::$operator['id'], $this->bool);
                $request = new WorkerReceiver1();
                $responseWorker = $request->Index(Job_StreamOut::$operator, $this->bool);
                if (!empty($responseWorker) && isset($responseWorker)) {
                    foreach ($responseWorker as $WorkerJob) {
                        $this->bollpos = false;
                        $json = json_decode($WorkerJob);
                        $this->variable($json);
                        $this->StartXML = file_get_contents($json->Code->filepath);
                        $response = simplexml_load_file($json->Code->filepath);
/*                        $response2 = $response->Table;
                        $xml_parser = new Simple_Parser;
                        $xml_parser->parse($this->StartXML);
                        $XmlString = $xml_parser->data['NEWDATASET']['0']['child']['TABLE'];*/
                        if (!empty($file) && isset($file)) {
                            $file = $this->DeleteArrayFile($file);
                        }
                        $this->CreateFileDecoding();
                        $this->PosRootStart($json);
                        foreach ($response as $Atribut) {
                            foreach ($Atribut->attributes() as $FullString) {
                                if ($json->Code->command == 'export_pos_wbstore' || $json->Code->command == 'export_pro_wbstore') {
                                    $string = str_replace("'", "'", "$FullString");
                                    $file[] = $string;
                                } else {
                                    $string = str_replace("'", "\'", "$FullString");
                                    $file[] = $string;
                                }
                            }
                        }
                        file_put_contents(__DIR__ . '/' . $this->time, $file, FILE_APPEND);
                        $this->PosRootFinish($json);
                        $this->DataXmlConverter($this->time);
                    }
                } else {
                    $text = 'Rabbit ResponseOperator queue empty #' . Job_StreamOut::$operator['id'];
                    Log::logtext($text);
                }
            } else {
                $text = 'Incorrect value in variable $operator or null (DIR = Job_StreamOut)';
                Log::logtext($text);
            }
        } else {
            $text = 'Incorrect value in variable $bool';
            Log::logtext($text);
        }
        $CreateTask->UpdateOperStreams(Job_StreamOut::$operator['id'], $this->bool);
    }

    public function DataXmlConverter($Path)
    {
        /*$data = file_get_contents(__DIR__ . '/' . '/VendmaxAndNayaxAndConnect/File/Visits.xml');*/
       $data = file_get_contents(__DIR__ . '/' . $Path);
        $response = simplexml_load_string($data);
        foreach ($response as $DataName => $DataXml) {
                       if ($DataName == 'Visits') {
                            foreach ($DataXml as $dataArrayValue) {
                                $this->batch_id = $dataArrayValue->batch_id;
                                $array = $dataArrayValue->attributes();
                                $json = json_encode($array);
                                $array2 = json_decode($json, TRUE);
                                foreach ($array2 as $k => $v) {
                                    $json2 = json_decode(json_encode($v), false);
                                }
                                $this->batch_id = $json2->batch_id;
                                $responselog = MYSQLDataOperator::VisitsOut($json2);
                            }
                        }
            if($DataName == 'pos_users'){
                $this->NameDataForProccesing = $DataName;
                CSVinsertStart::InsertCsvFile($Path, $this->NameDataForProccesing);
            }
            if($DataName == 'products'){
                $this->NameDataForProccesing = $DataName;
                CSVinsertStart::InsertCsvFile($Path, $this->NameDataForProccesing);
            }
             if ($DataName == 'product_sellouts') {
                   foreach ($DataXml as $dataArrayValue) {
                       $array = $dataArrayValue->attributes();
                       $json = json_encode($array);
                       $array2 = json_decode($json, TRUE);
                       foreach ($array2 as $k => $v) {
                           $json2 = json_decode(json_encode($v), false);
                       }
                       $responselog = MYSQLDataOperator::Product_sold_OUT($json2);
                   }
               }
            if($DataName == 'not_picked_items'){
                foreach ($DataXml as $dataArrayValue) {
                    $this->batch_id = $dataArrayValue->batch_id;
                    $array = $dataArrayValue->attributes();
                    $json = json_encode($array);
                    $array2 = json_decode($json, TRUE);
                    foreach ($array2 as $k => $v) {
                        $json2 = json_decode(json_encode($v), false);
                    }
                    $responselog = MYSQLDataOperator::not_picked_OUT($json2);
                }
            }
            $this->NameDataForProccesing = $DataName;
            $DataXmlJson = json_encode($DataXml);
            $DataXmlObject = json_decode($DataXmlJson);
            foreach ($DataXmlObject as $DataXmlCompleteObject) {
                if (!empty($DataXmlCompleteObject->batch_id) && isset($DataXmlCompleteObject->batch_id)) {
                    $this->batch_id = $DataXmlCompleteObject->batch_id;
                    switch ($this->NameDataForProccesing) {
                        case 'Product':
                            $responselog = MYSQLDataOperator::ProductOut($DataXmlCompleteObject);
                            break;
                        case 'POS':
                            $responselog = MYSQLDataOperator::Points_of_saleOut($DataXmlCompleteObject);
                            break;
                    }
                } else {
                    break;
                }
            }
        }
            if (!empty($responselog) && isset($responselog)) {
                if ($responselog === true) {
                    MYSQLDataOperator::OperatorL2D(Job_StreamOut::$operator, $this->provider);
                    MYSQLDataOperator::LogL2D($this->id_operator, $this->command, $this->StartXML, $this->batch_id);
                    MYSQLDataOperator::InsertTableT2s_dashboard($this->batch_id);
                }
            }
        unlink(__DIR__ . '/' . $Path);
    }

    private function Variable($json)
    {
        $this->id_operator = $json->Code->operatorid;
        $this->id_jobs = $json->Code->jobsid;
        $this->command = $json->Code->command;
        $this->provider = $json->Code->software_provider;
        $this->time = Date('Y-m-d H:i:s', time() . rand(234545, 623552423));
    }
    private function CreateFileDecoding()
    {
        if (!file_exists($this->time)) {
            $open = fopen(__DIR__ .'/'. $this->time, 'a+');
            $close = fclose($open);
            return true;
        }else{
            return false;
        }
    }
    private function PosRootStart($json)
    {
        if (!empty($json) && isset($json)) {
            if ($json->Code->command == 'export_pos_wbstore') {
                $open = fopen(__DIR__ . '/' . $this->time, 'r+');
                $stringStart = "<root>";
                fputs($open, $stringStart);
                $this->bollpos = true;
                $close = fclose($open);
                return true;
            }else{
                return false;
            }
        }
    }
    private function PosRootFinish($json)
    {
        if (!empty($json) && isset($json)) {
            if ($json->Code->command == 'export_pos_wbstore') {
                $open = fopen(__DIR__  . '/' . $this->time, 'a+');
                $stirngEnd = '</root>';
                fputs($open, $stirngEnd);
                $close = fclose($open);
                return true;
            }else{
                return false;
            }
        }
    }
    private function DeleteArrayFile($file){
        $file[] = null;
        foreach ($file as $i => $key) {
            unset($file[$i]);
        }
        return $file;
    }
}


    $CreateTask = new CreateTask();
    $DataResponseOperator = $CreateTask->SelectToDbOperatorsStreamsOut();
    foreach ($DataResponseOperator as $operator) {
        if ($operator['streams_response'] == 0) {
            $my = new Job_StreamOut();
            Job_StreamOut::$operator = $operator;
            /* $my->DataXmlConverter('49738231075-03-18 12:43:07');*/
            $my->Run();
        }

}