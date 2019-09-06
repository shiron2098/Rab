<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/RequestProcessor.php';
require_once __DIR__ . '/CreateOperator/CreateTask.php';
require_once __DIR__ . '/VendmaxAndNayaxAndConnect/DbConnectProvider.php';
require_once __DIR__ . '/Worker/WorkerReceiver1.php';
require_once __DIR__ . '/vendor/php-amqplib/php-amqplib/PhpAmqpLib/Connection/AMQPStreamConnection.php';


use app\WorkerReceiver1;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;


class Task extends Threaded
{
    public $response;
    public $idstream;
    public $idoper;
    public $responsework;
    public $responseoperator;


    public function someWork()
    {
        $bool = 0;
        $this->idstream = Thread::getCurrentThreadId();
        $WorkerOfDb = new WorkerReceiver1();
        $request = new RequestProcessor();
        $operator = $request->UpdateOperStreamsUp($this->idstream, $this->idoper['id'],$bool);
        $responseOfRabbit = $WorkerOfDb->Index($this->idoper,$bool);


/*        $request = new RequestProcessor();
        $operator = $request->UpdateOperStreamsUp($this->idstream, $this->idoper['id'],$bool);
        sleep(5);

        $request->read_job_from_queue($this->idoper);
        echo Thread::getCurrentThreadId();*/
    }
      public function rep(){
         return $this->responsework;
      }
    public function single()
    {
        $classCreatetask = new CreateTask();
        $dataresponseOperator = $classCreatetask->SelectToDbOperatorsStreams();
        foreach ($dataresponseOperator as $operator) {
            if ($operator['streams'] == 0) {
                return $this->idoper = $operator;
            }
        }
    }
    public function Work(){
        $time = time();
        $bool = 0;
        $WorkerOfDb = new WorkerReceiver1();
        $responseOfRabbit = $WorkerOfDb->Index($this->idoper,$bool);
        foreach ($responseOfRabbit as $job) {
            $response = json_decode($job);
        }
        return $response;
    }
    public function rows(){
        $link = mysqli_connect(
            '127.0.0.1',
            'ret',
            '123',
            'daws2'
        ) or die (CheckDataMYSQL::NoConnect);

        $result = mysqli_query(
            $link,
            "SELECT * FROM operators"

        );
        $rows = $result->num_rows;
        return $rows;
    }
}
/*$task = new Task();
$task->responseoperator = $task->Single();
$task->responsework = $task->Work();*/



class readWorker extends Thread {
    public function run(){
        $a = new task();
        $a->someWork();
        }
}
$a = new Task();
$res = $a->rows();
for($i = 0;$i<$res;$i++) {
    $Thres = new readWorker();
    $Thres->start();
}
