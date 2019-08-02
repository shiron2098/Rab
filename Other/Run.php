<?php
session_start();
require_once('CreateTask.php');
require_once('RabbitMqSendMessageConnect.phpphp');


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
        for($i=0;$i<$this->rows;$i++) {
            exec('php Inception.php > /dev/null 2>/dev/null &');
            sleep(1);
        }
/*        $provider = new DataProvider();
        $pool = new Pool($this->rows, 'Workers', [$provider]);*/
        $start = microtime(true);
/*         $pool->submit(new Work($this->rows));
        $pool->shutdown();*/
        printf("Done for %.2f seconds" . PHP_EOL, microtime(true) - $start);
    }

}
$a = new Run();
$a->index();
?>