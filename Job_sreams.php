<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once('RequestProcessor.php');
require_once('CreateOperator/CreateTask.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;


class Task extends Threaded
{
    public $response;
    public $idstream;
    public $idoper;

    public function someWork()
    {
        $bool = 0;
        require_once __DIR__ . '/vendor/autoload.php';
        $this->idstream = Thread::getCurrentThreadId();
        $connection = new AMQPStreamConnection();
/*        $request = new RequestProcessor();
        $operator = $request->UpdateOperStreamsUp($this->idstream, $this->idoper['id'],$bool);
        sleep(5);

        $request->read_job_from_queue($this->idoper);
        echo Thread::getCurrentThreadId();*/
    }

    public function single()
    {
        $classCreatetask = new CreateTask();
        $dataresponseOperator = $classCreatetask->SelectToDbOperatorsStreams();
        foreach ($dataresponseOperator as $operator) {
            if ($dataresponseOperator['streams'] == 0) {
                return $this->idoper = $operator;
            }
        }
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

$task = new Task;

$thread = new class($task) extends Thread {
    private $task;

    public function __construct(Threaded $task)
    {
        $this->task = $task;
    }

    public function run()
    {
        $this->task->single();
        $this->task->someWork();
    }
};
$a = new Task();
$res = $a->rows();
for($i = 0;$i<$res;$i++) {
    $thread->start() && $thread->join();
}
