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
/*        $newdb->SelectToDbJobSCheduler();
        $this->TimeTaskToRepeat = '+10minutes';*/
/*        $rowJob =  $newdb->Tointo();
        $rowJob2 = $newdb->TointoJob();
        $rowJOb3 = $newdb->TointoJob_Schedule();*/
/*
        $rowTableDate = $newdb->TimeTask($rowJob);
        $newdb->InsertDateTime($this->TimeTaskToRepeat,$rowTableDate);*/
        $newdb->SelectToDbJobSCheduler();
        $rep2 =$newdb->SchedulerSingle();
        $db = new MysqlDbConnect();
        $a = $db->SelectDb($rep2);
    }

    public function index2(){

    }




    public function Tointo()
    {
        $result = mysqli_query(
            $this->linkConnect,
            "insert into Operators (Name,Code,Connection_Softprovider,Connection_Url,User_name,User_password) values ('Anton','class2','Vendmax','http://web-server:8083/VmoDataAccessWS.asmx?swCode=CLASS2','Admin','00734070407B3472366F4B7A3F082408417A2278246551674B1553603A7D3D0D4105340B403F1466')"
        );
        $result = mysqli_query(
            $this->linkConnect,
              "SELECT id FROM Operators"
        );
        foreach($result as $response){
            $this->IDOperators = $response['id'];
        }


    }
    public function TointoJob(){
        $result = mysqli_query(
            $this->linkConnect,
            "insert into Jobs (Operatorid,Command) values ('" . $this->IDOperators . "','SELECT pro.description from Products pro')"
        );
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT id FROM Jobs"
        );
        foreach($result as $response){
            $this->IDJobs = $response['id'];
        }

    }
    public function TointoJob_Schedule(){
        $time=  '40';
        $result = mysqli_query(
            $this->linkConnect,
            "insert into job_scheduler (job_id,execute_interval) values ('" . $this->IDJobs . "',$time)"
        );
    }

}
$a = new CRON();
$a->index($a);
?>