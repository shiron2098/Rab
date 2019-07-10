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
/*        $results = print_r($responseOfMYSQL,
            true);
        $rabbi->log($results);*/
            $DAWS = new DbConnectToDAWS($responseOfMYSQL->Code->Command,$responseOfMYSQL->Code->Connection_Url,$responseOfMYSQL->Code->User_name,$responseOfMYSQL->Code->User_password,$responseOfMYSQL->Code->Connection_Softprovider);
            $response = $DAWS->ResponseOfDbToLogFile();
            try {
                if(!empty($response)&& isset($response)) {
                    $_SESSION['Zapros'] = false;
                    $this->AMQPConnect('localhost', '5672', 'shir', '1995', '/');
                    $this->CreateExchange('Type', 'direct');
                    $this->CreateQueue('Type', false, false, false, 'Data', false);
                    $this->MessageOut($response);
                    $text = 'message delivery is complete RabbitDAWS #' . $responseOfMYSQL->Code->id;
                    $this->log($text);
                }
                else
                {
                    throw new Exception('error download into rabbit because the message exists DAWS' . $responseOfMYSQL->Code->id);


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