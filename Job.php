<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/RequestProcessor.php';
require_once __DIR__ . '/CreateOperator/CreateTask.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;


class Job extends Threaded
{

    private $idstreams;
    public static  $operator;
    private $bool = 0;

    public function Run()
    {

                 $this->idstreams = Thread::getCurrentThreadId();
                 $request = new RequestProcessor();
                   $request->UpdateOperStreamsUp($this->idstreams, Job::$operator['id'],$this->bool);
                 $request->read_job_from_queue(Job::$operator);
/*                printf("%s is JobStreams #%lu\n", __CLASS__, Thread::getCurrentThreadId());
                echo Thread::getCurrentThreadId();*/
    }
}



   $classCreatetask = new CreateTask();
    $dataresponseOperator = $classCreatetask->SelectToDbOperatorsStreams();
        foreach($dataresponseOperator as $operator) {
            if($operator['streams'] == 0) {
                $my = new Job();
                Job::$operator = $operator;
                $my->Run();
            }
        }