<?php
session_start();
require_once('CreateOperator/CreateTask.php');
require_once('RequestProcessor.php');
class Run2 extends CreateTask
{
    public function Injection()
    {
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM operators"

        );

        $rows = $result->num_rows;
        for ($i = 0; $i < $rows; $i++) {
            exec('php Job.php > /dev/null 2>/dev/null &');
            sleep(1);
        }
    }
}
$a = new Run2();
$a->Injection();
?>