<?php
session_start();
require_once __DIR__ . '/CreateOperator/CreateTask.php';
require_once __DIR__ . '/RequestProcessor.php';
class Run2 extends CreateTask
{
    const rows = 3;

    public function Start()
    {

        for ($i = 0; $i < self::rows; $i++) {
             $row = $this->SingleJobArray();
                foreach($row as $oper) {
                    if ($oper['streams'] == 0) {
                        exec('/usr/bin/php /home/ubuntu/Downloads/Rab/Job.php > /dev/null 2>/dev/null &');
                    }
                }
        }
    }
    public function SingleJobArray(){
        $row=null;
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM operators"

        );
        foreach($result as $operator){
            $row[] =$operator;
        }
        return $row;
    }
}
$a = new Run2();
$a->Start();
?>