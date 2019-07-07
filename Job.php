<?php
require_once __DIR__ . '/vendor/autoload.php';
include_once ('MysqlDbConnect.php');
include_once ('RabbiSendSqlTakeInDbMYSQL.php');
use GO\Scheduler;

class Job extends MysqlDbConnect{

    const NameTable = 'JobScheduler';
    const Schedule = "*/5 * * * 4,5 /usr/bin/php7.3 /var/www/html/rab/index.php >/dev/null 2>&1";
    const SQL = 'SELECT * FROM Product';

    protected $Userid;
    protected $TimeForScheduler;
    protected $DateForMYSQL;
    Public function SelectToDbJobSCheduler(){
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM Operator"
        );
        $row = mysqli_fetch_assoc($result);
        $this->Userid = $row['Operatorid'];
    }
    public function InsertToDbJobScheduler(){
        $result = mysqli_query(
            $this->linkConnect,
            "insert into JobScheduler (StartScheduler,Scheduler,SQL_ZAP,Userid) values ('" . time() . "','" . job::Schedule . "',
'" . job::SQL . "','" . $this->Userid . "')"
        );
    }
/*    public function SelectJobScheduler(){
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM Tabledate WHERE Userid = $this->Userid"
        );
        $row = mysqli_fetch_assoc($result);
        foreach ($result as $res){
            if(!empty($res['Monday'])){
                $this->TimeForScheduler[]= $res['Monday'];
            }
            elseif(!empty($res['Tuesday'])){
                $this->TimeForScheduler[]= $res['Tuesday'];
            }
            elseif(!empty($res['Wednesday'])){
                $this->TimeForScheduler[]= $res['Wednesday'];
            }
            elseif(!empty($res['Thursday'])){
                $this->TimeForScheduler[]= $res['Thursday'];
            }
            elseif(!empty($res['Friday'])){
                $this->TimeForScheduler[]= $res['Friday'];
            }
            elseif(!empty($res['Saturday'])){
                $this->TimeForScheduler[]= $res['Saturday'];
            }
            elseif(!empty($res['Sunday'])){
                $this->TimeForScheduler[]= $res['Sunday'] ;
            }
        }
        return $this->TimeForScheduler;
    }*/

    public function TimeTask(){
        $time = (time());
        $userid = 'UserTimeId';
        $result = mysqli_query(
            $this->linkConnect,
            "SHOW COLUMNS FROM TableDate;"
        );
        foreach ($result as $res) {
            if($this->DateForMYSQL === $res['Field']){
                $result = mysqli_query(
                    $this->linkConnect,
                    "insert into TableDate ($this->DateForMYSQL,$userid) values ($time,$this->Userid)"
                );
                if(!empty($this->linkConnect->error_list)){
                    $result = mysqli_query(
                        $this->linkConnect,
                        "UPDATE TableDate SET $this->DateForMYSQL = $time WHERE UserTimeId=$this->Userid"
                    );
                }
            }
        }
    }

    public function SchedulerSingle(){
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM JobScheduler WHERE Userid = $this->Userid"
        );
        if(!empty($result)) {
            foreach ($result as $date)
               $file[] =$date;
                return $file;
        }
        else
        {
            $a = 'error empty response';
            return $a;
        }
    }
}
/*$e = $a->RepeatSingle();*/
/*$e = $a->TimeTask();*/

