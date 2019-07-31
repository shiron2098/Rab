<?php
require_once __DIR__ . '/vendor/autoload.php';
include_once 'RabbitSendSqlTakeInDbMYSQL.php';


class CheckDataMYSQL extends RabbitSendSqlTakeInDbMYSQL
{


    private  $checkrowstime;
    protected $jobcheckrowtime;
    protected $TimeTaskUpdate;
    protected $timetask;
    protected   $timestamp;
    private  $check;
    protected $timetasklogstart;
    protected $TimeTaskToRepeat;
    protected $timeMYSQLRabbit;

    public function __construct()
    {

        $this->time();
        $this->Dbconnect();
        $this->check = 0;
        $this->checkrowstime=0;


    }

    public function RunAndCheck($response)
    {

        if (!empty($response) && isset($response)) {
            foreach ($response as $arrayTime) {
                foreach ($arrayTime as $data) {
                    $this->check++;
/*                   $this->timestamp = Date('Y-m-d H:i:s', time());*/
                    /*$date = new DateTime('now');*/

                   $this->time();
                 /*   $this->timestamp = $date->format('Y-m-d H:i:s.u');*/
                    $this->IDOperators = $data['id'];
                    $this->IdOperatorsFull($this->IDOperators);
                    $this->logDB($this->IDJobs,$this->timestamp,self::statusRUN,'[Job id #' . $this->IDJobs . ']' . 'On processing');
                    $response = $this->DataFromVendmax($this->IDJobs);
                    $responseTimeTableDate = $this->JobScheduleTime($this->IDJobs);
                    $this->StringToUnix();
                    if (!empty($responseTimeTableDate)) {
                       $this->CheckDataAndSendMessage($response);
                    } else {
                        $text = '[Job id #' . $this->IDJobs . ']' . 'Schedule is not defined';
                        $this->logDB($this->IDJobs,$this->time(),self::statusERROR,$text);
                    }
                }
            }
        } else {
            $text = '[Job id #'. $this->IDOperators . ']' . 'Operator cannot be found';
            $this->logtext($text);
            $this->logDB($this->IDJobs,$this->time(),self::statusERROR,$text);
            return $this->idcolumnjob;
        }
        if($this->check === $this->checkrowstime){
            $this->UpdateOperStreamsUp();
        }
        return $this->idcolumnjob;
    }


    protected function StringToUnix()
    {
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT last_execute_dt FROM jobs WHERE id = $this->IDJobs"
        );
        $row = mysqli_fetch_assoc($result);
        foreach ($row as $response) ;
        $TimeToUnix = strtotime($response);
        $this->timetask = Date('Y-m-d H:i:s', $TimeToUnix + $this->TimeTaskToRepeat);
    }

    protected function JobScheduleTime($id)
    {
        $responseScheduleTime = $this->TimeJob($id);
        if (!empty($responseScheduleTime)) {
            /** insert to time  */
            foreach ($responseScheduleTime as $rob) {
                $this->TimeTaskToRepeat = $rob;
                return $rob;
            }
        } else {
            return null;
        }
    }
    protected function CheckDataAndSendMessage($row)
    {
        try {
            if ($this->timestamp >= $this->timetask) {

                $response = ['time' => $this->timeMYSQLRabbit,
                    'code' => $row];
             $responseSendAndCheckMessageMYSQL = $this->SendAndCheckMessageMYSQL($response);
            } else {
                $text='Task tried to be performed out of schedule ' . $row['command'] . '#' . $this->IDJobs;
                $this->logDB($this->IDJobs,$this->time(),self::statusERROR,$text);
                $this->checkrowstime++;
                throw new Exception($text);
            }

        } catch (Exception $e) {
            echo $e->getMessage();
            $this->logtext($e->getMessage());
        }
    }

    protected function DataFromVendmax($idtask)
    {
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT jobs.operator_id as operatorid,jobs.id as Jobsid,commands.code as command,software_providers.code as software_provider FROM operators
                  JOIN jobs on jobs.operator_id =  operators.id
                  join commands on commands.id = jobs.command_id
                  join software_providers on software_providers.id = operators.software_provider_id
                  WHERE jobs.id = $idtask"
        );
        try {
            if (!empty($row = mysqli_fetch_assoc($result))) {
                $this->timeMYSQLRabbit = time();
                return $row;
            } else {
                $text = '[Job id #' . $this->IDOperators . ']'  . 'Job cannot be found';
                $this->logDB($this->IDJobs,$this->time(),self::statusERROR,$text);
                throw new Exception($text);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->logtext($e->getMessage());
        }
    }
    private function TimeJob()
    {
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT execute_interval FROM job_schedule WHERE job_id = $this->IDJobs"
        );
        if (!empty($row = mysqli_fetch_assoc($result))) {
            return $row;
        } else {
            return null;
        }

    }


}
?>