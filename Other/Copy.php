<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once('RequestProcessor.php');
require_once('CreateOperator/CreateTask.php');
require_once('Worker/WorkerReceiver1.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;

class Job extends Threaded
{

    private $idstreams;
    protected $IDOperators;
    protected $IDJobs;
    Protected $IDJob_Scheduler;

    public function Run($operator)
    {

        $bool= 1;
        $this->idstreams = Thread::getCurrentThreadId();
        $response = new RequestProcessor();
        $response->UpdateOperStreamsUp($this->idstreams, $operator['id'],$bool);
        $request = new \app\WorkerReceiver1();
        $responseWorker =$request->Index($operator,$bool);
        if(!empty($responseWorker) && isset($responseWorker)) {
            foreach ($responseWorker as $workerjob) {
                $json = json_decode($workerjob);
                $response = file_get_contents($json->Code->filepath);
                $folder = __DIR__ . '/VendmaxAndNayaxAndConnect/FileNew/' . $json->Code->software_provider . '/' . $json->Code->command . '/';
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }
                $perem = Date('Y-m-d H:i:s', time());
                file_put_contents($folder . 'NewData'. '('.rand(254,435234) .')' . $perem, $response);
            }
        }else{
            $text = 'Rabbit ResponseOperator null #' . $operator['id'];
            $response->logtext($text);
        }
        $request->UpdateOperStreams($operator['id'], $bool);
    }
}


$classCreatetask = new CreateTask();
$dataresponseOperator = $classCreatetask->SelectToDbOperatorsStreamsOut();
foreach($dataresponseOperator as $operator) {
    if ($operator['streams_response'] == 0) {
        $my = new Job();
        $my->Run($operator, ENT_XML1);
    }
}