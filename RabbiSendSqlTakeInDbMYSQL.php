<?php
require_once __DIR__ . '/vendor/autoload.php';
spl_autoload('Rabbimq');
spl_autoload('MysqlDbConnect');
require_once('Rabbimq.php');
require_once('MysqlDbConnect.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;


class RabbiSendSqlTakeInDbMYSQL extends Rabbimq
{
    protected $ResponseMySQL;

    public function index($reponse){
        $this->ResponseMySQL = $reponse;
        $rabbi=new RabbiSendSqlTakeInDbMYSQL();
        $rabbi->AMQPConnect('localhost','5672','Shiro','1995','/');
        $rabbi->CreateExchange('rer','direct');
        $rabbi->CreateQueue('Operator24',false, true ,false,'operator333',false);
        try {
            $rabbi->CheckRabbit();
            if (isset($_SESSION['Zapros'])) {
                if ($_SESSION['Zapros'] !== true) {
                    $rabbi->MessageOut($this->ResponseMySQL);
                    $text = 'message delivery is complete MYSQL';
                    $_SESSION['Zapros']=true;
                    return $text;
                    /*                $a = new RabbitMqSendMessageDAWS();
                                    $a->Connect();*/
                } else {
                    throw new Exception('error download into rabbit because the message exists MYSQL');

                }
            }
        }
        catch (Exception $e) {
            echo $e->getMessage();
            $rabbi->log($e->getMessage());
        }
    }
}