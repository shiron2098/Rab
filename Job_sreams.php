<?php

class Job_sreams extends Thread
{

    private $idstreams;
    public static $rows;

    public function __construct($arg)
    {
        $this->arg = $arg;
    }

    public function run()
    {
        if ($this->arg) {
            $this->idstreams = Thread::getCurrentThreadId();

            printf("%s is JobStreams #%lu\n", __CLASS__, Thread::getCurrentThreadId());
        }
    }
}

// Create a array
$stack = array();

//Initiate Multiple Thread
foreach (range("A", "D") as $i) {
    $stack[] = new Job_sreams($i);
}

// Start The Threads
foreach ($stack as $t) {
    $t->start();
}

/*$classCreatetask = new CreateTask();
$dataresponseOperator = $classCreatetask->SelectToDbOperators();
foreach($dataresponseOperator as $operator) {
    if($dataresponseOperator['streams'] == 0) {
        $my = new Job();
        $my->run($operator);
    }
}*/
