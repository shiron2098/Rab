<?php
require_once __DIR__ . '/vendor/autoload.php';
include_once 'Rabbimq.php';


class MysqlDbConnect extends Rabbimq
{

    const NoConnect = 'No connect';
    const Time = 'now';
    const FileResponseName = __DIR__ . 'Response';



    protected $idtask;
    protected $userid;
    protected $startSql;
    protected $TimeTaskUpdate;
    protected $timetask;
    protected $Timestamp;
    protected $linkConnect;
    protected $IDOperators;
    protected $IDJobs;
    protected $IDJob_Scheduler;
    protected $TimeTaskToRepeat;
    protected $DateForMYSQL;
    protected $timeMYSQLRabbit;
    protected $idfull;

    public function __construct()
    {

        $this->Timestamp = date('Y-m-d H:i:s' ,strtotime(MysqlDbConnect::Time));
        $this->DateForMYSQL= date('l',strtotime('now'));

        $this->Dbconnect();


    }

    public function Dbconnect()
    {
        $link = mysqli_connect(
            self::host,
            self::user,
            self::password,
            self::database
        ) or die (MysqlDbConnect::NoConnect);
        $this->linkConnect = $link;

    }

    public function SelectDb($response)
    {

        if (!empty($response) && isset($response)) {
            foreach ($response as $arrayTime) {
                $this->IDOperators = $arrayTime['id'];
                $this->RepeatData($this->IDOperators);
                    $response = $this->DataFromVendmax($this->IDJobs);
                    $responseTimeTableDate = $this->TimeTableDate($this->IDJobs);
                    $this->StringToUnix();
                    if (!empty($responseTimeTableDate)) {
                        $this->CheckDataAndSendMessage($response);
                    }
            }
        }
        else
        {
            $text = '$Response null' . $this->IDOperators;
            $this->log($text);
        }
    }








    protected function StringToUnix(){
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT last_execute_dt FROM jobs WHERE id = $this->IDJobs"
        );
        $row = mysqli_fetch_assoc($result);
        foreach ($row as $response);
        $TimeToUnix = strtotime($response);
        $this->timetask = Date('Y-m-d H:i:s',$TimeToUnix + $this->TimeTaskToRepeat);
    }
    protected function TimeTableDate($id)
    {
        $responseOFdbTableDate = $this->RepeatSingle($id);
        if (!empty($responseOFdbTableDate)) {
            /** insert to time  */
          /*  $check = $this->CheckDatetime($this->IDTimeDataTableRows);*/
                foreach($responseOFdbTableDate as $rob) {
                    $this->TimeTaskToRepeat=$rob;
                    return $rob;
                }
        } else {
            $text = 'No schedule for the day job';
            $this->log($text);
            return null;
        }
    }
    private function UpdateJobs(){
        $result = mysqli_query(
            $this->linkConnect,
            "UPDATE jobs SET last_execute_dt = now() WHERE id=$this->IDJobs"
        );
        try {
            if ($result === true) {
                $a = 'Update complete timestamp to Jobs MYSQL #' . $this->IDJobs;
                return $a;
            } else {
                throw new Exception('Error update Tabledate #' . $this->DateForMYSQL . $this->IDJobs);
            }
        }catch(Exception $e){
            echo $e->getMessage();
            $this->log($e->getMessage());
        }
    }
    Private function UpdateJobScheduler(){
        $result = mysqli_query(
            $this->linkConnect,
            "UPDATE JobScheduler SET LastTake = $this->Timestamp WHERE id=$this->IdJobScheduler"
        );
        try {
            if ($result === true) {
                $a = 'Update complete timestamp to Database JobScheduler MYSQL #' .  $this->IdJobScheduler;
                return $a;
            } else {
                throw new Exception('Error update JobScheduler MYSQL #' . $this->IdJobScheduler);
            }
        }catch(Exception $e){
            echo $e->getMessage();
            $this->log($e->getMessage());
        }
    }


    protected function CheckDataAndSendMessage($row){
        try {
            if ($this->Timestamp >= $this->timetask) {

                $Rabbi = new RabbiSendSqlTakeInDbMYSQL();
                $response = ['time' => $this->timeMYSQLRabbit,
                            'code' => $row];
                $rabbitResponse = $Rabbi->index($response);
                $results = print_r($rabbitResponse, true);
                if(!empty($results)) {
                    $responseTableDate = $this->UpdateJobs();
                    $this->log($responseTableDate);

                    return $responseTableDate;
                }
            } else {
                throw new Exception('TIme is not come job ' . $row['command'] . ' # ' . $this->IDJobs);

            }
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->log($e->getMessage());
        }
    }

    protected function DataFromVendmax($idtask){
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT jobs.operator_id as operatorid,jobs.id as Jobsid,name,code,software_provider,connection_url,user_name,user_password,jobs.command FROM operators
                  JOIN jobs on jobs.operator_id =  operators.id
                  WHERE jobs.id = $idtask"
        );
        try {
            if (!empty($row = mysqli_fetch_assoc($result))) {
                $this->timeMYSQLRabbit = time();
                return $row;
            } else {
                throw new Exception('Response from Jobscheduler and OPerator null #' . $this->IDOperators);
            }
        }catch(Exception $e){
           echo $e->getMessage();
            $this->log($e->getMessage());
        }
    }
    protected function DeleteRepeat($NameDelete){

        $DELETE = $NameDelete;

        $data = file(self::FileRepeatToTask);

        $out = array();

        foreach($data as $line) {
            if(trim($line) != $DELETE) {
                $out[] = $line;
            }
        }

        $fp = fopen(self::FileRepeatToTask, "w+");
        flock($fp, LOCK_EX);
        foreach($out as $line) {
            fwrite($fp, $line);
        }
        flock($fp, LOCK_UN);
        fclose($fp);
    }
    private function RepeatSingle(){
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT execute_interval FROM job_schedule WHERE id = $this->IDJobs"
        );
        try {
            if (!empty($row = mysqli_fetch_assoc($result))) {
                    return $row;
            }
            else
            {
                throw new Exception('Response of job_scheduler null');
            }
        }catch(Exception $e){
           echo $e->getMessage();
            $this->log($e->getMessage());
        }
    }
    private function CheckDatetime($Idjob){
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT $this->DateForMYSQL FROM TableTimeDate WHERE id = $Idjob"
        );
        $row = mysqli_fetch_assoc($result);
        try {
            if (!empty($row)) {
                foreach ($row as $time){
                    return $this->TimeTaskToRepeat = $time;
                }
            }
            else
            {
                throw new Exception('No repeat time');
            }
        }catch(Exception $e){
            echo $e->getMessage();
            $this->log($e->getMessage());
        }
    }
    Protected function RepeatData($idtask){
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT operators.id as Operatorsid,jobs.id as Jobsid,job_schedule.job_id as JobSchedulerid FROM operators
                JOIN jobs on jobs.operator_id =  operators.id
                join job_schedule ON job_schedule.job_id = jobs.id
                WHERE jobs.id = $idtask"
        );
        try {
            if (!empty($result)) {
                foreach ($result as $row ) {
                    $this->IDOperators = $row['Operatorsid'];
                    $this->IDJobs = $row['Jobsid'];
                    $this->IDJob_Scheduler = $row ['JobSchedulerid'];
                }
            }
            else
            {
                throw new Exception('error no date ID MYSQL');
            }
        }catch(Exception $e){
            echo $e->getMessage();
            $this->log($e->getMessage());
        }
    }



}
?>