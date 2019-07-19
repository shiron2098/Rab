<?php
session_start();
require_once('CreateTask.php');
require_once('RabbitMqSendMessageDAWS.php');


class Streams extends CreateTask
{
    public function ds()
    {
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM operators"

        );

        FOREACH ($result as $row) {
            if($row['streams'] === null || $row['streams'] == 0){
                exec("php Run.php > test.txt &");
            }
        }
    }

    /*for ($i = 0; $i < 2; $i += 1) {
        exec("php script2.php $i > test.txt &");
    }*/

    /*for ($i=0; $i<10; $i++) {
        // open ten processes
        for ($j=0; $j<10; $j++) {
            $pipe[$j] = popen(__dir__ . '/script2.php', 'w');
        }

        // wait for them to finish
        for ($j=0; $j<10; ++$j) {
            pclose($pipe[$j]);
        }
    }*/
}
$a=new Streams();
$a->ds();