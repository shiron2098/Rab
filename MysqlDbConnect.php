<?php
require_once __DIR__ . '/vendor/autoload.php';
spl_autoload('Rabbimq');
include_once 'Rabbimq.php';


class MysqlDbConnect extends Rabbimq
{

    const NoConnect = 'No connect';
    const Time = 'now';
    const FileResponseName = __DIR__ . 'Response';

    private $timeTask;
    private $Timestamp;
    protected $linkConnect;
    private $idOperator;
    private $TableName;

    public function __construct()
    {

        $this->Timestamp = strtotime(MysqlDbConnect::Time);
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

    public function SelectDb()
    {

        /*        $shm_key = rand('441324953','634345634345');
                $shm_id = shmop_open($shm_key, "c", 0644, 100);
                 print_R($shm_id);*/
        $row = $this->RowsDataTable();
        if (!empty($row) && isset($row)) {
            if (file_exists(self::FileRepeatToTask) && !empty($fileRepeat)) {
                $fileRepeat = file_get_contents(self::FileRepeatToTask);
                $rowOfDb = $this->SeletDb($fileRepeat);
                $this->timeTask = strtotime('+1minutes', $rowOfDb['TimeTask']);
                $response = $this->TimeTaskAnd($rowOfDb);

                if(!empty($response)) {
                    $this->DeleteRepeat($rowOfDb['DBNAME']);
                }
            }
            else
            {
                foreach ($row as $task) {
                    $this->TableName = $task['TABLE_NAME'];
                    $rowOfDb = $this->SeletDb($this->TableName);
                    $results = print_r($row, true);
                    $this->log($results);
                    $this->timeTask = strtotime('+1minutes', $row['TimeTask']) . PHP_EOL;
                    $this->TimeTaskAnd($rowOfDb);
/*                    if (!empty($_SESSION['Povtor']) === true && isset ($_SESSION['Povtor']) === true) {
                        $this->TimeTaskAnd($rowOfDb);
                    }
                    else {*/

                    /*           print_r(date('Y-m-d H:i:s',$timeNow . PHP_EOL));
                                print_r(date('Y-m-d H:i:s',$time) . PHP_EOL);*/

                    /*|| $timeNow>= $timeProduct*/
                }

            }
            /*            $this->Operatorid = $row['operatorid'];*/
            /*            if (!empty($result)) {
                            mysqli_query(
                                $this->linkConnect,
                                "UPDATE Operator SET TimeInput=$this->Timestamp WHERE id=$this->Operatorid"
                            );
                        }*/
            /*        $row = mysqli_fetch_assoc($result);
                    $results = print_r($row, true);*/
            /*$results = print_r($row, true);
                if (file_exists($row['Name'])) {
                    try {
                        $_SESSION['Zapros'] = false;
                        throw new Exception('message is allready sent ');
                    }catch(Exception $e) {
                       echo $e->getMessage();
                    }
                }
                file_put_contents($row['Name'], $results);
                $_SESSION['FileZip'] = false;*/

            /*                $file = ['code' => $row,
                                'timestamp' => $this->Timestamp];
                            $_SESSION['FileZip']=false;
                            return $file;*/

            /*                if ($f = fopen(MysqlDbConnect::FileResponseName, 'a+')) {
                                fwrite($f, $row);
                                fclose($f);*/

        }
    }
    protected function UpdateBaseMYSQL($nameTable,$UserId){
        $result = mysqli_query(
            $this->linkConnect,
            "UPDATE $nameTable SET TimeTask = $this->Timestamp WHERE id=$UserId"
        );
        $a = 'Update complete timestamp to Database MYSQL' . PHP_EOL;
        return $a;
    }
    protected function RowsDataTable(){
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='daws'"
        );
        foreach ($result as $res){
            $MYSQLdbname[]= $res;
        }
        return $MYSQLdbname;
    }
    protected function TimeTaskAnd($row){
        try {
            if ($this->Timestamp >= $this->timeTask) {
                $Rabbi = new RabbiSendSqlTakeInDbMYSQL();
                $rabbitResponse = $Rabbi->index($row);
                $results = print_r($rabbitResponse, true);
                if(!empty($results)) {
                    $this->log($results);
                    $response = $this->UpdateBaseMYSQL($row['DBNAME'], $row['id']);
                    $this->log($response);
                    return $response;
                }
            } else {
                throw new Exception('TIme is not come');

            }
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->log($e->getMessage());
        }
    }
/*    private function PovtorTask(){
        $shm_key = ftok(__FILE__, 't');
        $shm_id = shmop_open($shm_key, "c", 0644, 100);
    }*/

    public function SeletDb($NameTables){
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM $NameTables"
        );
        $row = mysqli_fetch_assoc($result);
        return $row;
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

}
?>