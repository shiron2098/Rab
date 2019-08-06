<?php

require_once('CreateOperator/CreateTask.php');
require_once('RequestProcessor.php');

class Run3 extends CreateTask
{
    public function Injection()
    {
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM operators"

        );

        $rows = $result->num_rows;
        for ($i = 0; $i < $rows; $i++) {
            exec('php Job_StreamOut.php > /dev/null 2>/dev/null &');
        }
    }
}

$a = new Run3();
$a->Injection();