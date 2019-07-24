<?php
require_once __DIR__ . '/vendor/autoload.php';
include_once 'RabbitSendSqlTakeInDbMYSQL.php';


class CheckDataMYSQL extends RabbitSendSqlTakeInDbMYSQL
{

    const NoConnect = 'No connect';
    const FileResponseName = __DIR__ . 'Response';
    const complete = 'jobs complete to rabbit';
    const PASSIVETrue = TRUE;
    const passivefalse = False;
    const Time = 'now';
    const hostrabbit = 'localhost';
    const port = '5672';
    const username = 'shir';
    const passwordrabbit = '1995';
    const vhost = '/';
    const exchange = 'Type';
    const type = 'direct';
    const NameConfig = 'JobOperator#';
    const NameConfigDAWS = 'ResponseOperator#';


    protected $TimeTaskUpdate;
    protected $timetask;
    protected   $timestamp;
    protected $timetasklogstart;
    protected $TimeTaskToRepeat;
    protected $timeMYSQLRabbit;

    public function __construct()
    {

        $this->time();
        $this->Dbconnect();


    }

    public function RunAndCheck($response)
    {

        if (!empty($response) && isset($response)) {
            foreach ($response as $arrayTime) {
                foreach ($arrayTime as $data) {
                   $this->timestamp = Date('Y-m-d H:i:s', time());
                   $file[$data ['command_id']]= $this->timestamp;
                   $this->timetasklogstart= $file;
                    $this->IDOperators = $data['id'];
                    $this->IdOperatorsFull($this->IDOperators);
                    $response = $this->DataFromVendmax($this->IDJobs);
                    $responseTimeTableDate = $this->JobScheduleTime($this->IDJobs);
                    $this->StringToUnix();
                    if (!empty($responseTimeTableDate)) {
                        $this->CheckDataAndSendMessage($response);
                    } else {
                        $text = 'Time jobschedule null' . $this->IDJobs;
                        $this->logDB($this->IDJobs,$this->timetasklogstart,self::statusERROR,$text);
                    }
                }
            }
        } else {
            $text = '$Response null#' . $this->IDOperators;
            $this->logtext($text);
            $this->logDB($this->IDJobs,$this->timetasklogstart,self::statusERROR,$text);
           return $this->timetasklogstart;
        }
        return $this->timetasklogstart;
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
            $text = 'No schedule for the day job';
            $this->logtext($text);
            return null;
        }
    }
    protected function CheckDataAndSendMessage($row)
    {
        try {
            if ($this->timestamp >= $this->timetask) {

                $response = ['time' => $this->timeMYSQLRabbit,
                    'code' => $row];
                $this->SendAndCheckMessageMYSQL($response);

            } else {
                $text='TIme is not come job ' . $row['commandzzz'] . ' # ' . $this->IDJobs;
                $this->logDB($this->IDJobs,$this->timetasklogstart,self::statusERROR,$text);
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
            "SELECT jobs.operator_id as operatorid,jobs.id as Jobsid,command_details.execute_statement as command FROM operators
                  JOIN jobs on jobs.operator_id =  operators.id
                  join command_details on command_details.id = jobs.id
                  WHERE jobs.id = $idtask"
        );
        try {
            if (!empty($row = mysqli_fetch_assoc($result))) {
                $this->timeMYSQLRabbit = time();
                return $row;
            } else {
                $text = 'Response from Jobscheduler and OPerator null #' . $this->IDOperators;
                $this->logDB($this->IDJobs,$this->timetasklogstart,self::statusERROR,$text);
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
        try {
            if (!empty($row = mysqli_fetch_assoc($result))) {
                return $row;
            } else {
                $text= 'Response of job_scheduler null' . $this->IDJobs;
                $this->logDB($this->IDJobs,$this->timetasklogstart,self::statusERROR,$text);
                throw new Exception($text);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->logtext($e->getMessage());
        }
    }



}
?>