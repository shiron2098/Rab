<?php

require_once __DIR__ . '/CreateOperator/CreateTask.php';
require_once __DIR__ . '/RequestProcessor.php';

class Run3 extends CreateTask
{
    public function Start()
    {
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM operators"

        );

        $rows = $result->num_rows;
        for ($i = 0; $i < $rows; $i++) {
            exec('/usr/bin/php /home/shiro/Downloads/Rab/Job_StreamOut.php > /dev/null 2>/dev/null &');
            sleep(1);
        }
    }
}

$a = new Run3();
$a->Start();