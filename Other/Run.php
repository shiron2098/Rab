<?php
session_start();
require_once('CreateOperator/CreateTask.php');
require_once('RequestProcessor.php');
require_once('DataProvider.php');
require_once('Work.php');
require_once('Workers.php');


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
        $provider = new DataProvider();
        $pool = new Pool($this->rows, 'Workers', [$provider]);
        $start = microtime(true);
         $pool->submit(new Work($this->rows));
        $pool->shutdown();
        printf("Done for %.2f seconds" . PHP_EOL, microtime(true) - $start);
    }

}
$a = new Run();
$a->index();
?>