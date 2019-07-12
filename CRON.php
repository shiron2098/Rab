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
/*        $rowJob =  $newdb->Tointo();
        $rowJob2 = $newdb->TointoJob();
        $rowJOb3 = $newdb->TointoJob_Schedule();*/
        $newdb->SelectToDbOperators();
        $response =$newdb->SchedulerSingle();
        $db = new MysqlDbConnect();
        $db->SelectDb($response);
    }




    public function Tointo()
    {
        $result = mysqli_query(
            $this->linkConnect,
            "insert into operators (name,code,software_provider,connection_url,user_name,user_password) values ('Anton','class2','Vendmax','http://web-server:8083/VmoDataAccessWS.asmx?swCode=CLASS2','Admin','00734070407B3472366F4B7A3F082408417A2278246551674B1553603A7D3D0D4105340B403F1466')"
        );

        $result = mysqli_query(
            $this->linkConnect,
              "SELECT id FROM operators"
        );
        foreach($result as $response){
            $this->IDOperators = $response['id'];
        }


    }
    public function TointoJob(){
        $result = mysqli_query(
            $this->linkConnect,
            "insert into jobs (operator_id,command) values ('" . $this->IDOperators . "','SELECT pro.description from Products pro')"
        );
        $result = mysqli_query(
            $this->linkConnect,
            "SELECT id FROM jobs"
        );
        foreach($result as $response){
            $this->IDJobs = $response['id'];
        }

    }
    public function TointoJob_Schedule(){
        $time=  '50';
        $result = mysqli_query(
            $this->linkConnect,
            "insert into job_schedule (job_id,execute_interval) values ('" . $this->IDJobs . "',$time)"
        );
    }

}
$a = new CRON();
$a->index($a);
?>