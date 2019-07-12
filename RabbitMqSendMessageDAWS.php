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

    public function Connect()
    {
        include_once('WorkerReceiver1.php');
        $WorkerOfDb = new \app\WorkerReceiver1();
        $responseOfMYSQL = $WorkerOfDb->Index();
        /*        $results = print_r($responseOfMYSQL,
                    true);
                $rabbi->log($results);*/
        try {
            foreach ($responseOfMYSQL as $array) {
                $responseDATAMYSQL = json_decode($array);
                if (!empty($responseDATAMYSQL) && isset($responseDATAMYSQL)) {
                    $DAWS = new DbConnectToDAWS($responseDATAMYSQL->Code->command, $responseDATAMYSQL->Code->connection_url, $responseDATAMYSQL->Code->user_name, $responseDATAMYSQL->Code->user_password, $responseDATAMYSQL->Code->software_provider);
                    $response = $DAWS->ResponseOfDbToLogFile();
                    if (!empty($response) && isset($response)) {
                        $_SESSION['Zapros'] = false;
                        $this->AMQPConnect('localhost', '5672', 'shir', '1995', '/');
                        $this->CreateExchange('Type', 'direct');
                        $this->CreateQueue('ResponseOperator#' . $responseDATAMYSQL->Code->operatorid, false, false, false, $responseDATAMYSQL->Code->name, false);
                        $this->MessageOut($response);
                        $text = 'message delivery is complete RabbitDAWS #' . $responseDATAMYSQL->Code->Jobsid;
                        $this->log($text);
                    } else {
                        throw new Exception('error download into rabbit because the message exists DAWS' . $responseDATAMYSQL->Code->Jobsid);
                    }
                } else {
                    throw new Exception('Response of mysql array null');
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->log($e->getMessage());
        }
    }
}
$a = new RabbitMqSendMessageDAWS();
$a->Connect();
?>