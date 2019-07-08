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
    public $ResponseMySQL;
    public $TimeAndTaskDolg;

    public function index($reponse){
        $this->ResponseMySQL = $reponse;
        $results = print_r($this->ResponseMySQL,
            true);
        $this->log($results);
        $rabbi=new RabbiSendSqlTakeInDbMYSQL();
        try {
            $rabbi->CheckRabbit();
            if (isset($_SESSION['Zapros'])) {
                if ($_SESSION['Zapros'] !== true) {
                    $rabbi->AMQPConnect('localhost','5672','shir','1995','/');
                    $rabbi->CreateExchange('Type','direct');
                    $rabbi->CreateQueue('Operator24',false, false ,false,'operator333',false);
                    $rabbi->MessageOut($this->ResponseMySQL);
                    $text = 'message delivery is complete Rabbit #' . $this->ResponseMySQL['id'];
                    $_SESSION['Zapros']=true;
                    $rabbi->log($text);
/*                   include_once('RabbitMqSendMessageDAWS.php');*/
                    return $text;
                } else {
                    $rabbi->SearchRepeat($this->ResponseMySQL['id'] . PHP_EOL);
                    if(!empty($_SESSION['String'])=== true && isset($_SESSION['String']) === true) {
                        throw new Exception('error download into rabbit because the message exists MYSQL #' . $this->ResponseMySQL['id']);
                    }
                    else
                    {
                        file_put_contents(self::FileRepeatToTask, $this->ResponseMySQL['id'] . PHP_EOL, FILE_APPEND);
                    }
                }
            }
        }
        catch (Exception $e) {
            echo $e->getMessage();
            $rabbi->log($e->getMessage());
        }
    }
}