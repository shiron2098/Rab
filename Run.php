<?php
session_start();
require_once('CreateTask.php');
require_once('RabbitMqSendMessageDAWS.php');
require_once 'MyWorker.php';
require_once 'Mywork.php';
require_once 'MyDataProvider.php';


class Run extends CreateTask
{

    public function index()
    {
/*        $this->TointoSoftware_providers();
        $this->Tointo();
        $this->TointoCommands();
        if ($this->commands !== '0' && $this->commands !== null) {
            $this->TointoCommandDetails();
            $this->TointoJob();
            $this->TointoJob_Schedule();
        }
    }*/
            $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM operators"

        );
        $this->rows = $result->num_rows;
        $provider = new MyDataProvider();
        $pool = new Pool($this->rows, 'MyWorker', [$provider]);
        $start = microtime(true);
         $pool->submit(new MyWork($this->rows));
        $pool->shutdown();
        printf("Done for %.2f seconds" . PHP_EOL, microtime(true) - $start);
    }

}
$a = new Run();
$a->index();
?>