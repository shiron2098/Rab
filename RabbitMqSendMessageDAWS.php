<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
spl_autoload('Rabbimq');
spl_autoload('DbConnectToDAWS');
include_once ('Rabbimq.php');
include_once ('DbConnectToDAWS.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;

/*'SELECT pro.code,pro.description FROM Products pro'
'http://web-server:8083/VmoDataAccessWS.asmx?swCode=CLASS2'*/

class RabbitMqSendMessageDAWS extends Rabbimq
{
    const NameFile = __DIR__ . "/data";

    public function Connect(){
        include_once ('WorkerReceiver1.php');
        $WorkerOfDb = new \app\WorkerReceiver1();
        $responseOfMYSQL = $WorkerOfDb->Index();
        $results = print_r($responseOfMYSQL,
            true);
        /*$rabbi->log($results);*/
            $DAWS = new DbConnectToDAWS($responseOfMYSQL->Code->SQL_ZAP,$responseOfMYSQL->Code->connection_string);
            $response = $DAWS->ResponseOfDbToLogFile();
            try {
                if(!empty($response)&& isset($response)) {
                    $_SESSION['Zapros'] = false;
                    $this->AMQPConnect('localhost', '5672', 'shir', '1995', '/');
                    $this->CreateExchange('Type', 'direct');
                    $this->CreateQueue('Type', false, false, false, 'Data', false);
                    $this->MessageOut($response);
                    $text = 'message delivery is complete RabbitDAWS #' . $this->$responseOfMYSQL['id'];
                    $this->log($text);
                }
                else
                {
                    throw new Exception('error download into rabbit because the message exists DAWS');


                }
            }catch (Exception $e) {
                echo $e->getMessage();
                $this->log($e->getMessage());
            }
        }
}
$a = new RabbitMqSendMessageDAWS();
$a->Connect();
?>