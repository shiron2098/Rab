<?php
session_start();
require_once('CreateTask.php');
require_once('RabbitMqSendMessageDAWS.php');


class script2 extends CreateTask
{

    public function index()
    {
        $data = $this->SelectToDbOperator();
        $response = $this->JobsOperator($data);
        $responseRunAndCheck = $this->RunAndCheck($response);

    }


    public function RunAndCheck($response)
    {

        if (!empty($response) && isset($response)) {
            foreach ($response as $arrayTime) {
                foreach ($arrayTime as $data) {
/*                    $this->timestamp = Date('Y-m-d H:i:s', time());
                    $file[$data ['command_id']] = $this->timestamp;
                    $this->timetasklogstart = $file;*/
                    $this->IDOperators = $data['id'];
                    $this->IdOperatorsFull($this->IDOperators);
                    $response = $this->DataFromVendmax($this->IDJobs);
                    $responseTimeTableDate = $this->JobScheduleTime($this->IDJobs);
                    $this->StringToUnix();
                    if (!empty($responseTimeTableDate)) {
                        $this->CheckDataAndSendMessage($response);
                    } else {
                        $this->logDB($this->IDJobs, $this->timetasklogstart, self::statusERROR);
                    }
                }
            }
        } else {
            $text = '$Response null' . $this->IDOperators;
            $this->logtext($text);
            $this->logDB($this->IDJobs, $this->timetasklogstart, self::statusERROR);
        }
        $result = mysqli_query(
            $this->linkConnect,
            "UPDATE operators SET streams = 0 WHERE id=$this->IDOperators"
        );
    }
    private function SelectToDbOperator()
    {
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM operators"

        );

        FOREACH ($result as $row) {
            if($row['streams'] === null || $row['streams'] === 0){
                $file[]=$row;
                return $file;
            }
        }
    }
    protected function JobsOperator($data)
    {
        foreach ($data as $dataOperators) {
            $this->Userid = $dataOperators['id'];
            $result = mysqli_query(
                $this->linkConnect,
                "SELECT * FROM jobs WHERE operator_id = $this->Userid"
            );
            if (!empty($result)) {
                foreach ($result as $date)
                    $file[$dataOperators['name']][] = $date;
            } else {
                $a = 'error empty response';
                return $a;
            }
        }
        return $file;
    }
}
$a = new script2();
$a->index();