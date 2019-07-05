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
       $newdb->Tointo();
/*        $rep = $newdb->RepeatSingle();
        $db = new MysqlDbConnect();
        $a = $db->SelectDb($rep);
        $db->log($a);*/
       $newdb->TimeTask();

        print_r($newdb);
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
                "insert into JobScheduler (StartScheduler,Scheduler,SQL_ZAP,Userid) values ('1562350645','retret','SELECT * FROM Product',$this->Userid)"
            );
            print_r($this->linkConnect);
    }
}
$a = new JobScheduler();
$a->index();
?>