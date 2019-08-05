<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once('RequestProcessor.php');
require_once('CreateOperator/CreateTask.php');
require_once('Job_sreams.php');


class Job extends Threaded
{

    private $idstreams;

    public function Public($operator)
    {

                 $this->idstreams = Thread::getCurrentThreadId();
               $a = new RequestProcessor();
                $a->UpdateOperStreamsUp($this->idstreams, $operator['id']);
                sleep(5);
                $a->read_job_from_queue($operator);
                printf("%s is JobStreams #%lu\n", __CLASS__, Thread::getCurrentThreadId());
                echo Thread::getCurrentThreadId();
    }
}

     $classCreatetask = new CreateTask();
    $dataresponseOperator = $classCreatetask->SelectToDbOperatorsStreams();
        foreach($dataresponseOperator as $operator) {
            if($dataresponseOperator['streams'] == 0) {
                $my = new Job();
                $my->Public($operator);
            }
        }
/*    $my->start();*/