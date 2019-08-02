<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once('RequestProcessor.php');
require_once('CreateOperator/CreateTask.php');



class Job extends Threaded {

    private $idstreams;
    public static $rows;
    public function run() {

    $this->idstreams = Thread::getCurrentThreadId();
       $a = new RequestProcessor();
        $a->UpdateOperStreamsUp();
       $a->read_job_from_queue($this->idstreams);
        printf("%s is Thread #%lu\n", __CLASS__, Thread::getCurrentThreadId());
    }
}

    $my = new Job();
        $my->run();
/*    $my->start();*/