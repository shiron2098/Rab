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
    protected $IdJobScheduler;
    protected $IDDataTableRows;
    protected $IDTimeDataTableRows;
    protected $TimeTaskToRepeat;
    protected $DateForMYSQL;

    public function __construct()
    {

        $this->Timestamp = strtotime(MysqlDbConnect::Time);
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
            /*           if ($file = !file_exists(self::FileRepeatToTask)) {
                           fopen(self::FileRepeatToTask, 'a+');
                       }
                       if (!empty(file_get_contents(self::FileRepeatToTask))) {
                           $fileRepeat = file_get_contents(self::FileRepeatToTask);
                           $this->IdJobScheduler = current(explode(PHP_EOL, $fileRepeat));
                           $this->RepeatData($this->IdJobScheduler);
                           $rowOfDb = $this->DataFromVendmax($this->IdJobScheduler);
                           if($this->TimeTableDate($this->IDDataTableRows) !== null) {
                               $response = $this->CheckDataAndSendMessage($rowOfDb);
                               if (!empty($response)) {
                                   $this->DeleteRepeat($rowOfDb['id']);
                                   $text = 'Delete Repeat complete #' . $rowOfDb['id'];
                                   $this->log($text);
                               }
                           }
                           else
                           {
                               return null;
                           }

                       } else {*/
            foreach ($response as $arrayTime) {
                $this->IdJobScheduler = $arrayTime['id'];
                $this->startSql = $arrayTime['StartScheduler'];
                $this->RepeatData($this->IdJobScheduler);
                if ($this->startSql <= $this->Timestamp) {
                    $response = $this->DataFromVendmax($this->IdJobScheduler);
                    $responseTimeTableDate = $this->TimeTableDate($this->IDDataTableRows);
                    if (!empty($responseTimeTableDate)) {
                        /*     print_r(date('Y-m-d H:i:s',$responseOFdbTableDate . PHP_EOL));*/
                        $this->CheckDataAndSendMessage($response);
                    }
                } else {
                    $text = 'time is not STARTSQL' . $this->IdJobScheduler;
                    $this->log($text);
                }
            }
        }
        else
        {
            $text = '$Response null' . $this->IdJobScheduler;
            $this->log($text);
        }
    }
    protected function TimeTableDate($id)
    {
        $responseOFdbTableDate = $this->RepeatSingle($id);
        if (!empty($responseOFdbTableDate)) {
            /** insert to time  */
            $check = $this->CheckDatetime($this->IDTimeDataTableRows);
            if(!empty($check)) {
                foreach($responseOFdbTableDate as $rob) {
                    $this->timetask = $rob;
                    return $rob;
                }
            }
            else
            {
                $text='There is no time to repeat';
                $this->log($text);
                return null;
            }
        } else {
            $text = 'No schedule for the day job #' . $this->idtask;
            $this->log($text);
            return null;
        }
    }
    private function UpdateBaseTableDateMYSQL(){
        $result = mysqli_query(
            $this->linkConnect,
            "UPDATE TableDate SET $this->DateForMYSQL = $this->TimeTaskUpdate WHERE id=$this->IDDataTableRows"
        );
        try {
            if ($result === true) {
                $a = 'Update complete timestamp to TableDate MYSQL #' . $this->IDDataTableRows;
                return $a;
            } else {
                throw new Exception('Error update Tabledate #' . $this->DateForMYSQL . $this->IDDataTableRows);
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
                $rabbitResponse = $Rabbi->index($row);
                $results = print_r($rabbitResponse, true);
                if(!empty($results)) {
                    $this->TimeTaskUpdate = strtotime($this->TimeTaskToRepeat, $this->Timestamp);
                    $responseTableDate = $this->UpdateBaseTableDateMYSQL();
                    $this->log($responseTableDate);
                    $responseJobScheduler=$this->UpdateJobScheduler();
                    $this->log($responseJobScheduler);

                    return $responseJobScheduler;
                }
            } else {
                throw new Exception('TIme is not come job ' . $row['SQL_ZAP'] . ' # ' . $this->IdJobScheduler);

            }
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->log($e->getMessage());
        }
    }

    public function DataFromVendmax($idtask){
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT JobScheduler.id,Operatorid,Name,Password,connection_string,SQL_ZAP FROM Operator
                  JOIN JobScheduler on JobScheduler.userid =  Operator.Operatorid
                  WHERE JobScheduler.id = $idtask"
        );
        $row = mysqli_fetch_assoc($result);
        try {
            if (!empty($row)) {
                return $row;
            } else {
                throw new Exception('Response from Jobscheduler and OPerator null #' . $this->IdJobScheduler);
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
            "SELECT $this->DateForMYSQL FROM TableDate WHERE id = $this->IDDataTableRows"
        );
        $row = mysqli_fetch_assoc($result);
        try {
            if (!empty($row)) {
                    return $row;
            }
            else
            {
                throw new Exception('Response of Tabledate null');
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
            "SELECT JobScheduler.id as JobSchedulerid,TableDate.id as TableDateid,TableTimeDate.id as TableTimeDateid FROM JobScheduler
                JOIN TableDate on TableDate.Jobid =  JobScheduler.id
                join TableTimeDate ON TableTimeDate.JobTimeid = TableDate.id
                WHERE JobScheduler.id = $idtask"
        );
        $row = mysqli_fetch_assoc($result);
        try {
            if (!empty($row)) {
                $this->IdJobScheduler = $row['JobSchedulerid'];
                $this->IDDataTableRows = $row['TableDateid'];
                $this->IDTimeDataTableRows = $row ['TableTimeDateid'];
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