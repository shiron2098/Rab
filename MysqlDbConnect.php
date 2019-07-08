<?php
require_once __DIR__ . '/vendor/autoload.php';
spl_autoload('Rabbimq');
include_once 'Rabbimq.php';


class MysqlDbConnect extends Rabbimq
{

    const NoConnect = 'No connect';
    const Time = 'now';
    const FileResponseName = __DIR__ . 'Response';



    protected $idtask;
    protected $userid;
    private $startSql;
    private $TimeTaskUpdate;
    protected $timetask;
    private $Timestamp;
    protected $linkConnect;
    private $idOperator;
    private $TableName;
    private $TimeTaskToRepeat;
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
            if ($file = !file_exists(self::FileRepeatToTask)) {
                fopen(self::FileRepeatToTask, 'a+');
            }
            if (!empty(file_get_contents(self::FileRepeatToTask))) {
                $fileRepeat = file_get_contents(self::FileRepeatToTask);
                $this->idtask = current(explode(PHP_EOL, $fileRepeat));
                $rowOfDb = $this->DataFromVendmax($this->idtask);

                if($this->TimeTableDate($this->idtask) !== null) {
                    $response = $this->CheckDataAndSendMessage($rowOfDb);
                    if (!empty($response)) {
                        $this->DeleteRepeat($rowOfDb['id']);
                    }
                }
                else
                {
                    return null;
                }

            } else {
                foreach ($response as $arrayTime) {
                    $this->TimeTaskToRepeat = '+10minutes';
                    $this->idtask = $arrayTime['id'];
                    $this->startSql = $arrayTime['StartScheduler'];
                    if ($this->startSql <= $this->Timestamp) {
                        $response = $this->DataFromVendmax($this->idtask);
                        $responseTimeTableDate = $this->TimeTableDate($this->idtask);
                        if (!empty($responseTimeTableDate)) {
                            /*     print_r(date('Y-m-d H:i:s',$responseOFdbTableDate . PHP_EOL));*/
                            $this->CheckDataAndSendMessage($response);
                        }
                    }
                }
            }
        }
    }

    protected function TimeTableDate($id)
    {
        $responseOFdbTableDate = $this->RepeatSingle($id);
        if (!empty($responseOFdbTableDate)) {
            /** insert to time  */
            $this->InsertDateTime($this->TimeTaskToRepeat);
            $check = $this->CheckDatetime();
            if(!empty($check)) {
                $this->timetask = $responseOFdbTableDate;
                return $responseOFdbTableDate;
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
    private function UpdateBaseMYSQL(){
        $result = mysqli_query(
            $this->linkConnect,
            "UPDATE TableDate SET $this->DateForMYSQL = $this->TimeTaskUpdate WHERE id=$this->idtask"
        );
        try {
            if ($result === true) {
                $a = 'Update complete timestamp to TableDate MYSQL #' . $this->idtask;
                return $a;
            } else {
                throw new Exception('Error update Tabledate #' . $this->DateForMYSQL . $this->idtask);
            }
        }catch(Exception $e){
            echo $e->getMessage();
            $this->log($e->getMessage());
        }
    }
    Private function UpdateJobScheduler(){
        $result = mysqli_query(
            $this->linkConnect,
            "UPDATE JobScheduler SET LastTake = $this->Timestamp WHERE id=$this->idtask"
        );
        try {
            if ($result === true) {
                $a = 'Update complete timestamp to Database JobScheduler MYSQL #' .  $this->idtask;
                return $a;
            } else {
                throw new Exception('Error update JobScheduler MYSQL #' . $this->idtask);
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
                    $responseTableDate = $this->UpdateBaseMYSQL();
                    $this->log($responseTableDate);
                    $responseJobScheduler=$this->UpdateJobScheduler();
                    $this->log($responseJobScheduler);

                    return $responseJobScheduler;
                }
            } else {
                throw new Exception('TIme is not come job ' . $row['SQL_ZAP'] . ' # ' . $this->idtask);

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
                throw new Exception('Response from Jobscheduler and OPerator null #' . $this-$idtask);
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
    public function RepeatSingle(){
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT $this->DateForMYSQL FROM TableDate WHERE id = $this->idtask"
        );
        $row = mysqli_fetch_assoc($result);
        try {
            if (!empty($row)) {
                foreach ($row as $time){
                    return $time;
                }
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
    private function CheckDatetime(){
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT $this->DateForMYSQL FROM TableTimeDate WHERE id = $this->idtask"
        );
        $row = mysqli_fetch_assoc($result);
        try {
            if (!empty($row)) {
                foreach ($row as $time){
                    $this->TimeTaskToRepeat = $time;
                    return $this->TimeTaskToRepeat;
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
    private function InsertDateTime($Time){
        $result = mysqli_query(
            $this->linkConnect,
            "insert into TableTimeDate ($this->DateForMYSQL) values ('" . $Time . "')"
        );
        try {
            if ($result === true) {
                $text = 'Complete update record string';
                $this->log($text);
            }
            else
            {
                throw new Exception('Insert no TableTimeDate # ' . $this->DateForMYSQL . $Time ) ;
            }
        }catch(Exception $e){
            echo $e->getMessage();
            $this->log($e->getMessage());
        }
    }

}
?>