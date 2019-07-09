<?php
session_start();
include_once ('MysqlDbConnect.php');
include_once ('RabbiSendSqlTakeInDbMYSQL.php');
include_once ('Job.php');
include_once ('RepeatQueue.php');


class CRON extends RepeatQueue
{
    protected $id;
    protected $Time;
    protected $TImeToServer;
    protected $response;
    protected $Tasks;
    protected $TimeTaskToRepeat;


    public function index($newdb)
    {
        $newdb->SelectToDbJobSCheduler();
        $this->TimeTaskToRepeat = '+10minutes';
/*        $rowJob =  $newdb->Tointo();

        $rowTableDate = $newdb->TimeTask($rowJob);
        $newdb->InsertDateTime($this->TimeTaskToRepeat,$rowTableDate);*/
        $rep2 =$newdb->SchedulerSingle();
        $db = new MysqlDbConnect();
        $a = $db->SelectDb($rep2);
    }




    public function Tointo(){
            $result = mysqli_query(
                $this->linkConnect,
                "insert into JobScheduler (StartScheduler,LastTake,SQL_ZAP,Userid) values ('1562350645','23423','select pro.code,pro.description from Products pro',$this->Userid)"
            );
            return $this->RowsNewColumnInsert();
    }
}
$a = new CRON();
$a->index($a);
?>