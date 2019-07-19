<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once ('WorkerReceiver1.php');
require_once ('DbConnectToDAWS.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use \app\WorkerReceiver1;

/*'SELECT pro.code,pro.description FROM Products pro'
'http://web-server:8083/VmoDataAccessWS.asmx?swCode=CLASS2'*/

class RabbitMqSendMessageDAWS extends WorkerReceiver1
{
    const NameFile = __DIR__ . "/data";

    protected $DataOperators;

    public function Connect($logstart,$id)
    {
        if (!empty($logstart) && isset($logstart)) {
            $this->timetasklogstart = $logstart;
            $WorkerOfDb = new WorkerReceiver1();
            $responseOfRabbit = $WorkerOfDb->Index($id);

            if (!empty($responseOfRabbit)) {
                try {
                        foreach ($responseOfRabbit as $array) {
                            $responseJson = json_decode($array);
                            if (!empty($responseJson) && isset($responseJson)) {
                                $responseDATAMYSQL = $this->DataFromOperators($responseJson->Code->operatorid);
                                $this->IdOperatorsFull($responseJson->Code->Jobsid);
                                sleep(5);
                                $DAWS = new DbConnectToDAWS($responseJson->Code->command, $responseDATAMYSQL['connection_url'], $responseDATAMYSQL['user_name'], $responseDATAMYSQL['user_password']);
                                $response = $DAWS->ResponseOfDbToLogFile();
                                if (!empty($response) && isset($response)) {
                                    $_SESSION['Zapros'] = false;
                                    $this->AMQPConnect(self::hostrabbit, self::port, self::username, self::passwordrabbit, self::vhost);
                                    $this->CreateExchange(self::exchange, self::type);
                                    $this->CreateQueue(self::NameConfigDAWS . $responseDATAMYSQL['operatorid'], false, false, false, $responseDATAMYSQL['name'], false);
                                    $this->MessageOut($response, $responseJson);
                                    $text = 'message delivery is complete RabbitDAWS #' . $responseJson->Code->Jobsid;
                                    $this->logtext($text);
                                } else {
                                    $text = 'response server DAWS null #' . $responseJson->Code->Jobsid;
                                    $this->logDB($responseJson->Code->Jobsid, $this->timetasklogstart, self::statusERROR,$text);
                                    $this->logtext($text);
                                    /*throw new Exception('error download into rabbit because the message exists DAWS' . $responseJson->Code->Jobsid);*/
                                }
                            } else {
                                throw new Exception('Response of mysql array null');
                            }
                        }
                } catch (Exception $e) {
                    echo $e->getMessage();
                    $this->logDB($responseJson->Code->Jobsid, $this->timetasklogstart, self::statusERROR,$e->getMessage());
                    $this->logtext($e->getMessage());
                }
            }
        }
    }
}
/*$a = new RabbitMqSendMessageDAWS();
$a->Connect();*/
?>
