<?php
session_start();
include_once ('MysqlDbConnect.php');
include_once ('RabbiSendSqlTakeInDbMYSQL.php');
/* $fd = fopen("/home/shir/Documents/rab/lorrr.txt", 'a+');
  fwrite($fd,'OBRACHEN K FAILY -'. DATE("d.m.Y H:i")."\r\n");*/



class JobScheduler extends MysqlDbConnect
{
    protected $id;
    protected $Time;
    protected $TImeToServer;
    protected $response;
    protected $Tasks;


    public function index()
    {
        print_r($this->Tasks);
        $db = new MysqlDbConnect();
        $a = $db->SelectDb();

        exit();
        $b = ($a['code']['time']);
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
        }
    }
}
$a = new JobScheduler();
$a->index();
?>