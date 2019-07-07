<?php
session_start();
include_once ('MysqlDbConnect.php');
include_once ('RabbiSendSqlTakeInDbMYSQL.php');
include_once ('Job.php');


class JobScheduler extends Job
{
    protected $id;
    protected $Time;
    protected $TImeToServer;
    protected $response;
    protected $Tasks;


    public function index()
    {

        $newdb = new JobScheduler();
        $newdb->SelectToDbJobSCheduler();
/*      $newdb->Tointo();
        $newdb->TimeTask();*/
        $rep2 =$newdb->SchedulerSingle();
        $db = new MysqlDbConnect();
        $a = $db->SelectDb($rep2);
        $db->log($a);

      /*  $b = ($a['code']['time']);
        $this->id = $a['code']['id'];
        $timeNow = time();
        $time = strtotime('+5minutes', $b) . PHP_EOL;
        print_r(date('H:i:s', $timeNow));
        print_r(date('H:i:s', $time));
        try {
            if ($timeNow >= $time) {
                $Rabbi = new RabbiSendSqlTakeInDbMYSQL();
                $a = $Rabbi->index();
                $db->log($a);
                $response = $db->UpdateBaseMYSQL('Operator', $this->id);
                $db->log($response);
            } else {
                throw new Exception('TIme is not come');

            }
        } catch (Exception $e) {
            echo $e->getMessage();
            $db->log($e->getMessage());
        }*/
    }
    public function Tointo(){
            $result = mysqli_query(
                $this->linkConnect,
                "insert into JobScheduler (StartScheduler,LastTake,SQL_ZAP,Userid) values ('1562350645','23423','Select * from PRereedsl',$this->Userid)"
            );
    }
}
$a = new JobScheduler();
$a->index();
?>