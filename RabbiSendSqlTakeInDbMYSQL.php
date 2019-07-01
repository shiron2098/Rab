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
        print_r($reponse);
        $rabbi=new RabbiSendSqlTakeInDbMYSQL();
        $rabbi->AMQPConnect('localhost','5672','shir','1995','/');
        $rabbi->CreateExchange('rer','direct');
        $rabbi->CreateQueue('Operator24',false, true ,false,'operator333',false);
        try {
            $rabbi->CheckRabbit();
            if ($_SESSION['Zapros'] !== true)
            {
                $rabbi->MessageOut($this->ResponseMySQL);
                echo $text ='message delivery is complete MYSQL';
                $rabbi->log($text);
/*                $a = new RabbitMqSendMessageDAWS();
                $a->Connect();*/
            }
            else
            {

                throw new Exception('error download into rabbit because the message exists MYSQL');

            }
        }
        catch (Exception $e) {
            echo $e->getMessage();
            $rabbi->log($e->getMessage());
        }
    }
}