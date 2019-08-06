<?php
session_start();
require_once('CreateOperator/CreateTask.php');
require_once('RequestProcessor.php');
class Run2 extends CreateTask
{
    const rows = 2;
    public function Injection()
    {



        for ($i = 0; $i < self::rows; $i++) {
            $row=null;
            $result = mysqli_query(
                $this->linkConnect,
                "SELECT * FROM operators"

            );
            foreach($result as $operator){
                $row[] =$operator;
            }
                foreach($row as $oper) {
                    if ($oper['streams'] == 0) {
                        exec('php Job.php > /dev/null 2>/dev/null &');
                    }
                }
        }
    }
}
$a = new Run2();
$a->Injection();
?>