<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/RequestProcessor.php';
require_once __DIR__ . '/CreateOperator/CreateTask.php';
require_once __DIR__ . '/Worker/WorkerReceiver1.php';
require_once __DIR__ . '/Simple_Parser.php';
require_once __DIR__ . '/AbstractClass/MYSQLDataOperator.php';
require_once __DIR__ . '/Interface/mysql_insert_interface.php';



use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use \app\mysql_insert_interface;
use \app\WorkerReceiver1;

class Job extends Threaded
{

    private $idstreams;
    protected $id_operator;
    protected $id_jobs;
    Protected $command;
    private $provider;
    private $StartXML;
    private $batch_id;
    private $time;
    private $NameDataForProccesing;
    private static $operator;

    public function Run()
    {
        $bool = 1;
        $this->idstreams = Thread::getCurrentThreadId();
        $classCreatetask = new CreateTask();
        $classCreatetask->UpdateOperStreamsUp($this->idstreams, Job::$operator['id'],$bool);
        $request = new WorkerReceiver1();
        $responseWorker = $request->Index(Job::$operator, $bool);
        if (!empty($responseWorker) && isset($responseWorker)) {
            foreach ($responseWorker as $workerjob) {
                $json = json_decode($workerjob);
                $this->variable($json);
                MYSQLDataOperator::OperatorL2D(Job::$operator, $this->provider);
                $this->StartXML = file_get_contents($json->Code->filepath);
               $xml_parser = new Simple_Parser;
                $xml_parser->parse($this->StartXML);
                $xmlstring = $xml_parser->data['NEWDATASET']['0']['child']['TABLE'];
                $file[] = null;
                foreach ($file as $i => $key) {
                    unset($file[$i]);
                }
                foreach ($xmlstring as $atribut) {
                    foreach ($atribut['attribs'] as $fullstring) {
                        $string = str_replace("'", "", "$fullstring");
                        $file[] = $string;
                        file_put_contents(__DIR__ . '/' . $this->time, $file);
                    }
                }
                $this->DataXmlConverter($this->time);
            }
        } else {
            $text = 'Rabbit ResponseOperator null #' . Job::$operator['id'];
            Log::logtext($text);
        }
        $request->UpdateOperStreams(Job::$operator['id'], $bool);
    }

    private function DataXmlConverter($Path)
    {
        $data = file_get_contents(__DIR__ . '/' . $Path);
        $response = simplexml_load_string($data);
        foreach ($response as $dataname => $dataxml) {
            $this->NameDataForProccesing = $dataname;
            $dataxmljson = json_encode($dataxml);
            $dataxmlobject = json_decode($dataxmljson);
            foreach ($dataxmlobject as $dataxmlcompleteobject) {
                $this->batch_id = $dataxmlcompleteobject->batch_id;
                switch ($this->NameDataForProccesing) {
                    case 'Product':
                        $response = MYSQLDataOperator::ProductOut($dataxmlcompleteobject);
                        break;
                    case 'Visit':
                        $response = MYSQLDataOperator::VisitsOut($dataxmlcompleteobject);
                        break;
                    case 'POS':
                        $response = MYSQLDataOperator::Points_of_saleOut($dataxmlcompleteobject);
                        break;
                }
            }
        }
     unlink(__DIR__ . '/' . $Path);
       MYSQLDataOperator::LogL2D($this->id_operator, $this->command, $this->StartXML, $this->batch_id);
    }

    private function Variable($json)
    {
        $this->id_operator = $json->Code->operatorid;
        $this->id_jobs = $json->Code->jobsid;
        $this->command = $json->Code->command;
        $this->provider = $json->Code->software_provider;
        $this->time = Date('Y-m-d H:i:s', time());
    }
}

$classCreatetask = new CreateTask();
$dataresponseOperator = $classCreatetask->SelectToDbOperatorsStreamsOut();
foreach($dataresponseOperator as $operator) {
    if ($operator['streams_response'] == 0) {
        $my = new Job();
        Job::$operator = $operator;
        $my->Run();
    }
}