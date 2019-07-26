<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once('WorkerReceiver1.php');
require_once ('DbConnectToDAWS.php');
require_once('VendmaxCommands.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use \app\WorkerReceiver1;

/*'SELECT pro.code,pro.description FROM Products pro'
'http://web-server:8083/VmoDataAccessWS.asmx?swCode=CLASS2'*/

class RabbitMqSendMessageDAWS extends WorkerReceiver1
{
    const NameFile = "data";
    const provider = "vendmax";

    protected $DataOperators;
    private  $DataSql;

    public function Connect($logstart,$id)
    {
        if (!empty($logstart) && isset($logstart)) {
            $this->timetasklogstart = $logstart;
            $WorkerOfDb = new WorkerReceiver1();
            $responseOfRabbit = $WorkerOfDb->Index($id);
            if (!empty($responseOfRabbit)) {
                try {
                        foreach ($responseOfRabbit as $array) {
                            $response = null;
                            $responseJson = json_decode($array);
                            if (!empty($responseJson) && isset($responseJson)) {
                                $responseDATAMYSQL = $this->DataFromOperators($responseJson->Code->operatorid);
                                $VendmaxCommands = new VendmaxCommands($responseJson->Code->Jobsid,$responseJson->Code->operatorid,$responseJson->Code->command,$responseJson->Code->software_provider);
                                if($responseJson->Code->software_provider == self::provider){
                                    switch($responseJson->Code->command){
                                        case 'Get CUS_View':
                                            $response = $VendmaxCommands->Get_Products();
                                            break;

                                        case 'Get VVI_View':
                                            $response = $VendmaxCommands->Get_Customers();
                                            break;

                                        case 'Get code, cat, description, cost, in_service_date, out_service_date':
                                            $response = $VendmaxCommands->Get_VendVisits();
                                            break;
                                        case 'Get POS_View':
                                            $response = $VendmaxCommands->Get_Select();
                                    }
                                }


                                /*sleep(7);*/
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
            }else{
                return null;
            }
        }
        return $text;
    }
}
/*$a = new RabbitMqSendMessageDAWS();
$a->Connect(1,1);*/
?>
