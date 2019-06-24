<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
spl_autoload('Rabbimq');
spl_autoload('MysqlDbConnect');
spl_autoload('WorkerReceiver1');
spl_autoload('RabbitMqSendMessageDAWS');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;


/*$host = 'localhost';
$port = 5672;
$user = 'Shiro';
$pass = '1995';
$vhost = '/';
$exchange = 'Type';
$queue = 'Operator24';
$routing_key = 'oper22';*/


class RabbiSendSqlTakeInDbMYSQL extends Rabbimq
{

    public function index(){


        $MYSQL = new MysqlDbConnect('localhost','root','','daws');
        $response = $MYSQL->SelectDb();
        $rabbi=new RabbiSendSqlTakeInDbMYSQL();
        $rabbi->AMQPConnect('localhost','5672','Shiro','1995','/');
        $rabbi->CreateExchange('rer','direct');
        $rabbi->CreateQueue('Operator24',false, true ,false,'operator333',false);
        try {
            $rabbi->CheckRabbit();
            if ($_SESSION['Zapros'] !== true)
            {
                $rabbi->MessageOut($response);
                echo 'message delivery is complete';
                $a = new RabbitMqSendMessageDAWS();
                $a->Connect();
            }
            else
            {

                throw new Exception('error download into rabbit because the message exists');

            }
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}

$a = new RabbiSendSqlTakeInDbMYSQL();
$a->index();