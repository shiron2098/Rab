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
    Public function SelectToDbJobSCheduler(){
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM  "
        );
        $row = mysqli_fetch_assoc($result);
        $this->Userid = $row['Operatorid'];
        return $row;
    }
    public function InsertToDbJobScheduler(){
        $result = mysqli_query(
            $this->linkConnect,
            "insert into JobScheduler (StartScheduler,Scheduler,SQL_ZAP,Userid) values ('" . time() . "','" . job::Schedule . "',
'" . job::SQL . "','" . $this->Userid  . "')"
        );
    }
    public function SelectJobScheduler(){
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT * FROM  JobScheduler"
        );
        $row = mysqli_fetch_assoc($result);
        return $row;
    }
    public function TimeTask(){
        $date = date('l',strtotime('now'));
        print_R($date);
    }
}
$a = new Job();
$a->TimeTask();

